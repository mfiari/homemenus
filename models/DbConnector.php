<?php

class DbConnector {
	
	protected $db;
	protected $sqlHasFailed;

	public function __construct(){
		$this->db = new PDO("mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DBNAME,MYSQL_LOGIN,MYSQL_PASSWORD);
		// Forcer la communication en utf-8
		$this->db->exec("SET character_set_client = 'utf8'");
		$this->sqlHasFailed = false;
	}
	
	public function getDb () {
		return $this->db;
	}
	
	public function beginTransaction () {
		$this->db->beginTransaction();
	}
	
	public function endTransaction () {
		if ($this->sqlHasFailed === true) {
			/* rollback */
			$this->db->rollBack();
		} else {
			/* commit */
			$this->db->commit();
		}
	}
	
	public function closeConnection () {
		$this->db = null;
	}
	
	public function prepare ($sql) {
		return $this->db->prepare($sql);
	}
	
	public function lastInsertId () {
		return $this->db->lastInsertId();
	}
}