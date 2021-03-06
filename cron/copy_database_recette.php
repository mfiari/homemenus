<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."Database.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "copy database recette ".MYSQL_RECETTE_DBNAME);
	
	$modelDatabase = new Model_Database();
	if (!$modelDatabase->copy_database(MYSQL_RECETTE_DBNAME)) {
		writeLog (CRON_LOG, "Echec copy database recette", LOG_LEVEL_ERROR);
	}
	
	
	writeLog (CRON_LOG, "fin copy database recette ".MYSQL_RECETTE_DBNAME);