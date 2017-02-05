<?php

class Model_Modification extends Model_Template {
	
	protected $id;
	private $tables;
	private $id_column;
	private $field;
	private $old_value;
	private $new_value;
	private $types;
	private $id_user;
	private $date_modification;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}
	
	public function prepareModification ($table, $id_column, $field, $old_value, $new_value, $type, $user) {
		$sql = "INSERT INTO modifications (tables, id_column, field, old_value, new_value, types, id_user, date_modification)
		VALUES (:table, :id, :field, :old_value, :new_value, :type, :user, now())";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":table", $table);
		$stmt->bindValue(":id", $id_column);
		$stmt->bindValue(":field", $field);
		$stmt->bindValue(":old_value", $old_value);
		$stmt->bindValue(":new_value", $new_value);
		$stmt->bindValue(":type", $type);
		$stmt->bindValue(":user", $user);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		return $value === false;
	}
	
	public function runModification ($id) {
		$sql = "SELECT id, tables, id_column, field, old_value, new_value, types, id_user, date_modification FROM modifications WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$modification = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($modification["types"] == "UPDATE") {
			$sql = "UPDATE FROM ".$modification["tables"]." SET ".$modification["field"]." = :".$modification["field"]." WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":".$modification["field"], $modification["new_value"]);
			$stmt->bindValue(":id", $modification["id_column"]);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				$this->sqlHasFailed = true;
				return false;
			}
		} else if ($modification["types"] == "DELETE") {
			$sql = "DELETE FROM ".$modification["tables"]." WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $modification["id_column"]);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				$this->sqlHasFailed = true;
				return false;
			}
		} else {
			continue;
		}
		$sql = "INSERT INTO modifications_history (tables, id_column, field, old_value, new_value, types, id_user, date_modification, date_execution)
		VALUES (:table, :id, :field, :old_value, :new_value, :type, :user, :date, now())";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":table", $modification["tables"]);
		$stmt->bindValue(":id", $modification["id_column"]);
		$stmt->bindValue(":field", $modification["field"]);
		$stmt->bindValue(":old_value", $modification["old_value"]);
		$stmt->bindValue(":new_value", $modification["new_value"]);
		$stmt->bindValue(":type", $modification["types"]);
		$stmt->bindValue(":user", $modification["id_user"]);
		$stmt->bindValue(":date", $modification["date_modification"]);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "DELETE FROM modifications WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $modification["id"]);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function runModifications () {
		$sql = "SELECT id, tables, id_column, field, old_value, new_value, types, id_user, date_modification FROM modifications";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$modifications = $stmt->fetchAll();
		foreach ($modifications as $modification) {
			$sql = "";
			if ($modification["types"] == "UPDATE") {
				$sql = "UPDATE FROM ".$modification["tables"]." SET ".$modification["field"]." = :".$modification["field"]." WHERE id = :id";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":".$modification["field"], $modification["new_value"]);
				$stmt->bindValue(":id", $modification["id_column"]);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					$this->sqlHasFailed = true;
					return false;
				}
			} else if ($modification["types"] == "DELETE") {
				$sql = "DELETE FROM ".$modification["tables"]." WHERE id = :id";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $modification["id_column"]);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					$this->sqlHasFailed = true;
					return false;
				}
			} else {
				continue;
			}
			$sql = "INSERT INTO modifications_history (tables, id_column, field, old_value, new_value, types, id_user, date_modification, date_execution)
			VALUES (:table, :id, :field, :old_value, :new_value, :type, :user, :date, now())";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":table", $modification["tables"]);
			$stmt->bindValue(":id", $modification["id_column"]);
			$stmt->bindValue(":field", $modification["field"]);
			$stmt->bindValue(":old_value", $modification["old_value"]);
			$stmt->bindValue(":new_value", $modification["new_value"]);
			$stmt->bindValue(":type", $modification["types"]);
			$stmt->bindValue(":user", $modification["id_user"]);
			$stmt->bindValue(":date", $modification["date_modification"]);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				$this->sqlHasFailed = true;
				return false;
			}
			$sql = "DELETE FROM modifications WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $modification["id"]);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				$this->sqlHasFailed = true;
				return false;
			}
			return true;
		}
	}
	
	public function cancelModification () {
		$sql = "DELETE FROM modifications WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
}