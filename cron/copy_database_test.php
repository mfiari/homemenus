<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."Database.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "copy database test ".MYSQL_TEST_DBNAME);
	
	$modelDatabase = new Model_Database();
	if (!$modelDatabase->copy_database(MYSQL_TEST_DBNAME)) {
		writeLog (CRON_LOG, "Echec copy database test", LOG_LEVEL_ERROR);
	} else {
		$modelDatabase->changePassword(MYSQL_TEST_DBNAME, 'test');
		$modelDatabase->changeEmail(MYSQL_TEST_DBNAME, 'test@homemenus.fr');
		$modelDatabase->changePhoneNumber(MYSQL_TEST_DBNAME, '0636601045');
	}
	
	writeLog (CRON_LOG, "fin copy database test ".MYSQL_TEST_DBNAME);