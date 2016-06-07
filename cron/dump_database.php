<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Database.php";
	
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
	$output = $modelDatabase->dump();
	
	$dirname = dirname($fullPath);
	if (!is_dir($dirname)) {
		mkdir($dirname, 0755, true);
	}
	
	$dumpfile = fopen($fullPath, "a");
	fwrite($dumpfile, $output);
	fclose($dumpfile);
	
	/*exec('/usr/bin/mysqldump --user='.MYSQL_LOGIN.' --password='.MYSQL_PASSWORD.' --host='.MYSQL_HOST.' '.MYSQL_DBNAME.' > '.$fullPath, $output);
	
	writeLog(CRON_LOG, $output);
	
	var_dump($output);*/