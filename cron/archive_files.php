<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	
	writeLog (CRON_LOG, "début archive files");
	
	$PreviousDate = mktime(0, 0, 0, date('m')-2, 1, date('Y'));
	
	$previousYear = date('Y', $PreviousDate);
	$previousMonth = date('m', $PreviousDate);
	
	writeLog (CRON_LOG, "archive dump files");

	$zip = new ZipArchive();
	
	$filename = ROOT_PATH."files/dump/$previousYear-$previousMonth.zip";

	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		writeLog (CRON_LOG, "Impossible d'ouvrir le fichier <$filename>", LOG_LEVEL_ERROR);
		exit("Impossible d'ouvrir le fichier <$filename>\n");
	}
	
	$directories = glob(ROOT_PATH."files/dump/$previousYear-$previousMonth-*");
	
	foreach ($directories as $directory) {
		
		$directoryArray = explode('/', $directory);
		
		$directoryName = $directoryArray[count($directoryArray)-1];
		
		$zip->addEmptyDir($directoryName);
	
		$files = glob($directory."/*");

		// let's iterate
		foreach ($files as $file) {
			$fileArray = explode('/', $file);
		
			$fileName = $fileArray[count($fileArray)-1];
			
			$zip->addFile($file, $directoryName.'/'.$fileName);
		}
	}
	
	writeLog (CRON_LOG, "Nombre de fichiers dump archivé : " . $zip->numFiles);
	writeLog (CRON_LOG, "Statut :" . $zip->status);
	
	// close the zip file
	if (!$zip->close()) {
		writeLog (CRON_LOG, "There was a problem writing the dump ZIP archive", LOG_LEVEL_ERROR);
	} else {
		writeLog (CRON_LOG, "Successfully created the dump ZIP Archive!");
	}
	
	foreach ($directories as $directory) {
		$files = glob($directory."/*");
		foreach ($files as $file) {
			if (!unlink($file)) {
				writeLog (CRON_LOG, "Impossible de suprimer le fichier $file", LOG_LEVEL_WARNING);
			}
		}
		if (!rmdir($directory)) {
			writeLog (CRON_LOG, "Impossible de suprimer le dossier $directory", LOG_LEVEL_WARNING);
		}
	}
	
	writeLog (CRON_LOG, "fin archive dump files");
	
	writeLog (CRON_LOG, "archive commandes files");

	$zip = new ZipArchive();
	
	$filename = ROOT_PATH."files/commandes/$previousYear-$previousMonth.zip";

	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		writeLog (CRON_LOG, "Impossible d'ouvrir le fichier <$filename>", LOG_LEVEL_ERROR);
		exit("Impossible d'ouvrir le fichier <$filename>\n");
	}
	
	$directories = glob(ROOT_PATH."files/commandes/$previousYear-$previousMonth-*");
	
	foreach ($directories as $directory) {
		
		$directoryArray = explode('/', $directory);
		
		$directoryName = $directoryArray[count($directoryArray)-1];
		
		$zip->addEmptyDir($directoryName);
	
		$subdirectories = glob($directory."/*");
		
		foreach ($subdirectories as $subdirectory) {
			$subdirectoryArray = explode('/', $subdirectory);
		
			$subdirectoryName = $subdirectoryArray[count($subdirectoryArray)-1];
		
			$zip->addEmptyDir($directoryName.'/'.$subdirectoryName);
		
			$files = glob($subdirectory."/*");

			// let's iterate
			foreach ($files as $file) {
				$fileArray = explode('/', $file);
			
				$fileName = $fileArray[count($fileArray)-1];
				
				$zip->addFile($file, $directoryName.'/'.$subdirectoryName.'/'.$fileName);
			}
		}
	}
	
	writeLog (CRON_LOG, "Nombre de fichiers commandes archivé : " . $zip->numFiles);
	writeLog (CRON_LOG, "Statut :" . $zip->status);
	
	// close the zip file
	if (!$zip->close()) {
		writeLog (CRON_LOG, "There was a problem writing the commandes ZIP archive", LOG_LEVEL_ERROR);
	} else {
		writeLog (CRON_LOG, "Successfully created the commandes ZIP Archive!");
	}
	
	foreach ($directories as $directory) {
		$subdirectories = glob($directory."/*");
		foreach ($subdirectories as $subdirectory) {
			$files = glob($subdirectory."/*");
			foreach ($files as $file) {
				if (!unlink($file)) {
					writeLog (CRON_LOG, "Impossible de suprimer le fichier $file", LOG_LEVEL_WARNING);
				}
			}
			if (!rmdir($subdirectory)) {
				writeLog (CRON_LOG, "Impossible de suprimer le dossier $subdirectory", LOG_LEVEL_WARNING);
			}
		}
		if (!rmdir($directory)) {
			writeLog (CRON_LOG, "Impossible de suprimer le dossier $directory", LOG_LEVEL_WARNING);
		}
	}
	
	writeLog (CRON_LOG, "fin archive commandes files");
	
	writeLog (CRON_LOG, "archive planing files");

	$zip = new ZipArchive();
	
	$filename = ROOT_PATH."files/planing/$previousYear-$previousMonth.zip";

	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		writeLog (CRON_LOG, "Impossible d'ouvrir le fichier <$filename>", LOG_LEVEL_ERROR);
		exit("Impossible d'ouvrir le fichier <$filename>\n");
	}
	
	$directories = glob(ROOT_PATH."files/planing/$previousYear-$previousMonth-*");
	
	foreach ($directories as $directory) {
		
		$directoryArray = explode('/', $directory);
		
		$directoryName = $directoryArray[count($directoryArray)-1];
		
		$zip->addEmptyDir($directoryName);
	
		$files = glob($directory."/*");

		// let's iterate
		foreach ($files as $file) {
			$fileArray = explode('/', $file);
		
			$fileName = $fileArray[count($fileArray)-1];
			
			$zip->addFile($file, $directoryName.'/'.$fileName);
		}
	}
	
	writeLog (CRON_LOG, "Nombre de fichiers planing archivé : " . $zip->numFiles);
	writeLog (CRON_LOG, "Statut :" . $zip->status);
	
	// close the zip file
	if (!$zip->close()) {
		writeLog (CRON_LOG, "There was a problem writing the planing ZIP archive", LOG_LEVEL_ERROR);
	} else {
		writeLog (CRON_LOG, "Successfully created the planing ZIP Archive!");
	}
	
	foreach ($directories as $directory) {
		$files = glob($directory."/*");
		foreach ($files as $file) {
			if (!unlink($file)) {
				writeLog (CRON_LOG, "Impossible de suprimer le fichier $file", LOG_LEVEL_WARNING);
			}
		}
		if (!rmdir($directory)) {
			writeLog (CRON_LOG, "Impossible de suprimer le dossier $directory", LOG_LEVEL_WARNING);
		}
	}
	
	writeLog (CRON_LOG, "fin archive planing files");
	
	writeLog (CRON_LOG, "archive logs files");

	$zip = new ZipArchive();
	
	$filename = ROOT_PATH."log/$previousYear-$previousMonth.zip";

	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		writeLog (CRON_LOG, "Impossible d'ouvrir le fichier <$filename>", LOG_LEVEL_ERROR);
		exit("Impossible d'ouvrir le fichier <$filename>\n");
	}
	
	$directories = glob(ROOT_PATH."log/*");
	foreach ($directories as $directory) {
		
		$directoryArray = explode('/', $directory);
		
		$directoryName = $directoryArray[count($directoryArray)-1];
		
		$zip->addEmptyDir($directoryName);
		
		$files = glob($directory."/log_$previousYear-$previousMonth-*");

		// let's iterate
		foreach ($files as $file) {
			$fileArray = explode('/', $file);
		
			$fileName = $fileArray[count($fileArray)-1];
			
			$zip->addFile($file, $directoryName.'/'.$fileName);
		}
	}
	
	writeLog (CRON_LOG, "Nombre de fichiers logs archivé : " . $zip->numFiles);
	writeLog (CRON_LOG, "Statut :" . $zip->status);
	
	// close the zip file
	if (!$zip->close()) {
		writeLog (CRON_LOG, "There was a problem writing the logs ZIP archive", LOG_LEVEL_ERROR);
	} else {
		writeLog (CRON_LOG, "Successfully created the logs ZIP Archive!");
	}
	
	foreach ($directories as $directory) {
		$files = glob($directory."/*");
		foreach ($files as $file) {
			if (!unlink($file)) {
				writeLog (CRON_LOG, "Impossible de suprimer le fichier $file", LOG_LEVEL_WARNING);
			}
		}
	}
	
	writeLog (CRON_LOG, "fin archive logs files");
	
	writeLog (CRON_LOG, "fin archive files");
?>
