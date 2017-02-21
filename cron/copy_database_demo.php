<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."Database.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "copy database demo ".MYSQL_DEMO_DBNAME);
	
	$modelDatabase = new Model_Database();
	$modelDatabase->copy_database(MYSQL_DEMO_DBNAME);
	
	
	writeLog (CRON_LOG, "fin copy database demo ".MYSQL_DEMO_DBNAME);