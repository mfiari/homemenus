<?php

include_once ROOT_PATH."models/Notification.php";

class Controller_Notification extends Controller_Admin_Template {
	
	public function manage ($request) {
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "detail" :
					$this->detail($request);
					break;
				case "smsling" :
					$this->smsling($request);
					break;
				case "send" :
					$this->send($request);
					break;
				default :
					$this->redirect('404', '', 'default');
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('notification/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('notification/'.$vue.'.php');
	}
	
	public function index ($request) {
		if (isset($_POST['date_debut'])) {
			$request->date_debut = $_POST['date_debut'];
		} else {
			$request->date_debut = date('d/m/Y');
		}
		$dateDebut = datepickerToDatetime($request->date_debut).' 00:00:00';
		
		if (isset($_POST['date_fin'])) {
			$request->date_fin = $_POST['date_fin'];
		} else {
			$request->date_fin = date('d/m/Y');
		}
		$dateFin = datepickerToDatetime($request->date_fin).' 23:59:59';
		
		$request->title = "Administration";
		$modelNotification = new Model_Notification(true, $request->dbConnector);
		$request->notifications = $modelNotification->getAll($dateDebut, $dateFin);
		$request->vue = $this->render("index");
	}
	
	public function detail ($request) {
		$request->title = "Administration";
		$modelNotification = new Model_Notification(true, $request->dbConnector);
		$modelNotification->id = $_GET['id'];
		$request->sms = $modelNotification->load();
		$request->vue = $this->render("detail");
	}
	
	public function smsling ($request) {
		$modelSMS = new Model_SMS(true, $request->dbConnector);
		$request->users = $modelSMS->getAllClientTel();
		$request->title = "Administration - SMSling";
		$request->vue = $this->render("smsling");
	}
	
	public function send ($request) {
		$modelSMS = new Model_SMS(true, $request->dbConnector);
		$message = $_POST['message'];
		$users = $modelSMS->getAllClientTel();
		foreach ($users as $user) {
			if (isset($_POST['user-'.$user->id]) && $_POST['user-'.$user->id] == 'on') {
				//if ($client->parametre->send_sms_commande /* && $client->telephone commence par 06 ou 07 */) {
					$sms = new Clickatell();
					$sms->message = $message;
					$sms->addNumero($user->telephone);
					$send = $sms->sendMessage();
					
					$modelSMS->telephone = $user->telephone;
					$modelSMS->message = $message;
					$modelSMS->is_send = $send;
					
					$modelSMS->save();
				//}
			}
		}
		$this->redirect('smsling', 'sms');
	}
}