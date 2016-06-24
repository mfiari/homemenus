<?php

class Model_Log {
	
	private $id;
	private $level;
	private $date_log;
	private $filepath;
	private $fonction;
	private $line;
	private $message;
	private $texte;
	
	public function __construct() {
		$this->message = array();
		$this->texte = array();
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}
	
	public static function parse($filename) {
		$handle = fopen($filename, "r");
		$array = array();
		$logObj = new Model_Log();
		$currentKey = "";
		$id = 1;
		while ($line = fgets($handle)) {
			$line = trim($line);
			if ($line == "[BEGIN_LOG]") {
				$logObj = new Model_Log();
				$logObj->id = $id;
				$id++;
			} else if ($line == "[END_LOG]") {
				$array[] = $logObj;
			} else if ($line == "[LEVEL]") {
				$currentKey = "LEVEL";
			} else if ($line == "[DATE]") {
				$currentKey = "DATE";
			} else if ($line == "[FILE]") {
				$currentKey = "FILE";
			} else if ($line == "[FUNCTION]") {
				$currentKey = "FUNCTION";
			} else if ($line == "[LINE]") {
				$currentKey = "LINE";
			} else if ($line == "[MESSAGE]") {
				$currentKey = "MESSAGE";
			} else if ($line == "[TEXT]") {
				$currentKey = "TEXT";
			} else {
				switch ($currentKey) {
					case "LEVEL" :
						$logObj->level = $line;
						break;
					case "DATE" :
						$logObj->date_log = $line;
						break;
					case "FILE" :
						$logObj->filepath = $line;
						break;
					case "FUNCTION" :
						$logObj->fonction = $line;
						break;
					case "LINE" :
						$logObj->line = $line;
						break;
					case "MESSAGE" :
						$logObj->message[] = $line;
						break;
					case "TEXT" :
						$logObj->texte[] = $line;
						break;
				}
			}
		}
		return $array;
	}
}