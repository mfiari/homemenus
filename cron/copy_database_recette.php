<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Database.php";
	
	writeLog (CRON_LOG, "copy database recette ".MYSQL_RECETTE_DBNAME);
	
	$modelDatabase = new Model_Database();
	$modelDatabase->copy_database(MYSQL_RECETTE_DBNAME);
	
	
	writeLog (CRON_LOG, "fin copy database recette ".MYSQL_RECETTE_DBNAME);