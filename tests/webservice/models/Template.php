<?php

abstract class Model_Template {

	protected $db; 

	public function __construct(){
		$this->db = new PDO("mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DBNAME,MYSQL_LOGIN,MYSQL_PASSWORD);
		// Forcer la communication en utf-8
		$this->db->exec("SET character_set_client = 'utf8'");
	}
}
