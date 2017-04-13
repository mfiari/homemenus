<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."Database.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "début dump database");
	
	$today = date('Y-m-d');
	
	$directory = ROOT_PATH.'files/dump/'.$today;
	
	$filename = 'dump.sql';
	
	if (file_exists($directory.'/'.$filename)) {
		$indice = 1;
		$filename = 'dump-'.$indice.'.sql';
		while (file_exists($directory.'/'.$filename)) {
			$indice++;
			$filename = 'dump-'.$indice.'.sql';
		}
	}
	
	$fullPath = $directory.'/'.$filename;
	
	$modelDatabase = new Model_Database();
	$output = $modelDatabase->dump(true);
	
	$dirname = dirname($fullPath);
	if (!is_dir($dirname)) {
		mkdir($dirname, 0755, true);
	}
	
	$dumpfile = fopen($fullPath, "a");
	fwrite($dumpfile, $output);
	fclose($dumpfile);
	
	writeLog (CRON_LOG, "fin dump database");