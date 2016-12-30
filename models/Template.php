<?php

abstract class Model_Template {
	
	protected $id;
	protected $db;
	protected $sqlHasFailed;
	protected $_tableName;

	public function __construct($db = null){
		if ($db === null) {
			$this->db = new PDO("mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DBNAME,MYSQL_LOGIN,MYSQL_PASSWORD);
			// Forcer la communication en utf-8
			$this->db->exec("SET character_set_client = 'utf8'");
		} else {
			$this->db = $db;
		}
		$this->sqlHasFailed = false;
		$this->id = -1;
	}
	
	public function beginTransaction () {
		
	}
	
	public function endTransaction () {
		if ($this->sqlHasFailed === true) {
			/* rollback */
		} else {
			/* commit */
		}
	}
	
	public function getDbConnector () {
		return $this->db;
	}
	
	public function save () {
		if ($this->id == -1) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	public function insert () {
		
	}
	
	public function update () {
		
	}
	
	public function deleted () {
		$sql = "UPDATE $this->_tableName SET deleted = 1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function request ($sql, $params) {
		
	}
	
	public function executeSql ($sql, $params, $mode) {
		$stmt = $this->db->prepare($sql);
		foreach ($params as $key => $value) {
			$stmt->bindValue($key, $value);
		}
		if (!$stmt->execute()) {
			foreach ($params as $key => $value) {
				$sql = str_replace($key, $value, $sql);
			}
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		if ($mode == PDO::FETCH_ASSOC) {
			$value = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($value == null || $value == false) {
				foreach ($params as $key => $value) {
					$sql = str_replace($key, $value, $sql);
				}
				writeLog(SQL_LOG, 'Aucun résultats requete', LOG_LEVEL_WARNING, $sql);
				$this->sqlHasFailed = true;
				return false;
			}
			return $value;
		}
		
		return true;
	}
}
