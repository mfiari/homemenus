<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Database.php";
	
	writeLog (CRON_LOG, "copy database demo ".MYSQL_DEMO_DBNAME);
	
	$modelDatabase = new Model_Database();
	$modelDatabase->copy_database(MYSQL_DEMO_DBNAME);
	
	
	writeLog (CRON_LOG, "fin copy database demo ".MYSQL_DEMO_DBNAME);