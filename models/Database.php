<?php

class Model_Database extends Model_Template {
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
	}
	
	public function dump ($tables = false, $tableStructure = true, $tableData = true) {
		$output = "-- PHP MySQL Dump\n--\n";
		$output .= "-- Environnement: ".ENVIRONNEMENT."\n";
		$output .= "-- Generated: " . date("r", time()) . "\n";
		$output .= "-- PHP Version: " . phpversion() . "\n\n";
		$output .= "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n\n";
		$output .= "--\n-- Database: ".MYSQL_DBNAME."\n--\n";
		// get all table names in db and stuff them into an array
		if ($tables === false) {
			$tables = array();
			$stmt = $this->db->query("SHOW TABLES");
			while($row = $stmt->fetch(PDO::FETCH_NUM)){
				$tables[] = $row[0];
			}
		}
		// process each table in the db
		foreach($tables as $table){
			$output .= "\n-- " . str_repeat("-", 60) . "\n\n";
			if ($tableStructure) {
				$output .= $this->getTableStructure($table);
			}
			if ($tableData) {
				$output .= $this->getTableData($table);
			}
		}
		return $output;
	}
	
	public function getTableStructure ($table) {
		$output = "--\n-- Table structure for table `$table`\n--\n\n";
		// get table create info
		$stmt = $this->db->query("SHOW CREATE TABLE $table");
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$output.= $row[1].";\n\n";
		return $output;
	}
	
	public function getTableData ($table) {
		$fields = "";
		$sep2 = "";
		// get table data
		$output = "--\n-- Dumping data for table `$table`\n--\n\n";
		$stmt = $this->db->query("SELECT * FROM $table");
		while($row = $stmt->fetch(PDO::FETCH_OBJ)){
			// runs once per table - create the INSERT INTO clause
			if($fields == ""){
				$fields = "INSERT INTO `$table` (";
				$sep = "";
				// grab each field name
				foreach($row as $col => $val){
					$fields .= $sep . "`$col`";
					$sep = ", ";
				}
				$fields .= ") VALUES";
				$output .= $fields . "\n";
			}
			// grab table data
			$sep = "";
			$output .= $sep2 . "(";
			foreach($row as $col => $val){
				// add slashes to field content
				$val = addslashes($val);
				// replace stuff that needs replacing
				$search = array("\'", "\n", "\r");
				$replace = array("''", "\\n", "\\r");
				$val = str_replace($search, $replace, $val);
				$output .= $sep . "'$val'";
				$sep = ", ";
			}
			// terminate row data
			$output .= ")";
			$sep2 = ",\n";
		}
		// terminate insert data
		if($fields !== ""){
			$output .= ";\n";
		}
		return $output;
	}
	
	public function copy_database ($database) {
		$stmt = $this->db->query("SHOW TABLES");
		while($row = $stmt->fetch(PDO::FETCH_NUM)){
			$table = $row[0];
			$this->db->query("DROP TABLE IF EXISTS ".$database.".$table");
			$this->db->query("CREATE TABLE ".$database.".$table LIKE $table");
			$this->db->query("INSERT INTO ".$database.".$table SELECT * FROM $table");
		}
	}
	
	public function changePassword ($database, $newPassword) {
		$sql = "UPDATE $database.users SET password = sha1(:password)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":password", $newPassword);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
	}
	
	public function changeEmail ($database, $newEmail) {
		$sql = "UPDATE $database.commande_history SET email_user = :email";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":email", $newEmail);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "UPDATE $database.users SET email = :email";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":email", $newEmail);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
	}
	
	public function changePhoneNumber ($database, $newPhoneNumber) {
		$sql = "UPDATE ".$database.".commande SET telephone = :telephone";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":telephone", $newPhoneNumber);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "UPDATE ".$database.".commande_history SET telephone_commande = :telephone";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":telephone", $newPhoneNumber);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "UPDATE ".$database.".user_client SET telephone = :telephone";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":telephone", $newPhoneNumber);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "UPDATE ".$database.".user_livreur SET telephone = :telephone";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":telephone", $newPhoneNumber);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
	}
}