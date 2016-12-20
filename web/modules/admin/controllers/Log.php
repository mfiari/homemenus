<?php

include_once ROOT_PATH."models/Log.php";

class Controller_Log extends Controller_Admin_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "server" :
					$this->server($request);
					break;
				case "cron" :
					$this->cron($request);
					break;
				case "sql" :
					$this->sql($request);
					break;
				case "mail" :
					$this->mail($request);
					break;
				case "ws" :
					$this->ws($request);
					break;
				case "vue" :
					$this->vue($request);
					break;
			}
		} else {
			$this->server($request);
		}
	}
	
	public function server ($request) {
		$request->title = "Administration - Log";
		$today = date('Y-m-d');
		if (file_exists(ROOT_PATH.'log/server/log_'.$today)) {
			$request->logs = file (ROOT_PATH.'log/server/log_'.$today);
		}
		$request->vue = $this->render("log/server.php");
	}
	
	public function cron ($request) {
		/*$request->title = "Administration - Log";
		$today = date('Y-m-d');
		if (file_exists(ROOT_PATH.'log/cron/log_'.$today)) {
			$request->logs = file (ROOT_PATH.'log/cron/log_'.$today);
		}
		$request->vue = $this->render("log/cron.php");*/
		$request->title = "Administration - Log";
		if (isset($_POST['date'])) {
			$request->date_log = $_POST['date'];
		} else {
			$request->date_log = date('d/m/Y');
		}
		$today = datepickerToDatetime($request->date_log);
		if (file_exists(ROOT_PATH.'log/cron/log_'.$today)) {
			$request->logs = Model_Log::parse(ROOT_PATH.'log/cron/log_'.$today);
		}
		$request->vue = $this->render("log/cron.php");
	}
	
	public function sql ($request) {
		$request->title = "Administration - Log";
		if (isset($_POST['date'])) {
			$request->date_log = $_POST['date'];
		} else {
			$request->date_log = date('d/m/Y');
		}
		$today = datepickerToDatetime($request->date_log);
		if (file_exists(ROOT_PATH.'log/sql/log_'.$today)) {
			$request->logs = Model_Log::parse(ROOT_PATH.'log/sql/log_'.$today);
		}
		$request->vue = $this->render("log/sql.php");
	}
	
	public function mail ($request) {
		$request->title = "Administration - Log";
		$today = date('Y-m-d');
		if (file_exists(ROOT_PATH.'log/mail/log_'.$today)) {
			$request->logs = file (ROOT_PATH.'log/mail/log_'.$today);
		}
		$request->vue = $this->render("log/mail.php");
	}
	
	public function ws ($request) {
		$request->title = "Administration - Log";
		$today = date('Y-m-d');
		if (file_exists(ROOT_PATH.'log/ws/log_'.$today)) {
			$request->logs = file (ROOT_PATH.'log/ws/log_'.$today);
		}
		$request->vue = $this->render("log/ws.php");
	}
	
	public function vue ($request) {
		$request->title = "Administration - Log";
		$today = date('Y-m-d');
		if (file_exists(ROOT_PATH.'log/vue/log_'.$today)) {
			$request->logs = file (ROOT_PATH.'log/vue/log_'.$today);
		}
		$request->vue = $this->render("log/vue.php");
	}
	
	public function readLogFile ($filename) {
		$handle = fopen($filename, "r");
		$array = array();
		$logObj = array();
		$isText = false;
		while ($line = fgets($handle)) {
			if ($line == "[BEGIN_LOG]") {
				//$logObj = array();
				$logObj = new Model_Log();
			} else if ($line == "[END_LOG]") {
				$array[] = $logObj;
			} else {
				if ($isText) {
					$logObj[$key][] = $line;
				} else {
					$key = "";
					$value = "";
					if (trim($key) == "TEXT" && trim($value) == '') {
						$logObj[$key] = array();
						$isText = true;
					} else {
						$logObj[$key] = $value;
					}
				}
			}
		}
		return $array;
	}
}