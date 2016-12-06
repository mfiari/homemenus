<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Database.php";
	
	writeLog (CRON_LOG, "copy database test ".MYSQL_TEST_DBNAME);
	
	$modelDatabase = new Model_Database();
	$modelDatabase->copy_database(MYSQL_TEST_DBNAME);
	
	$modelDatabase->changePassword(MYSQL_TEST_DBNAME, 'test');
	$modelDatabase->changeEmail(MYSQL_TEST_DBNAME, 'test@homemenus.fr');
	$modelDatabase->changePhoneNumber(MYSQL_TEST_DBNAME, '0636601045');
	
	
	writeLog (CRON_LOG, "fin copy database test ".MYSQL_TEST_DBNAME);