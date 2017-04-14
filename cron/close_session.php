<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."User.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "début close session");
	
	/*$dateFin = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')));*/
	$dateFin = date('Y-m-d h:i:s', strtotime("-".SESSION_MAX_TIME." minutes"));
	
	$modelUser = new Model_User();
	
	$modelUser->closeSessionBeforeDate($dateFin);
	
	writeLog (CRON_LOG, "fin close session");