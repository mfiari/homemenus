<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/SMS.php";
include_once ROOT_PATH."models/Clickatell.php";

class Controller_SMS extends Controller_Admin_Template {
	
	public function manage ($request) {
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
		$modelSMS = new Model_SMS();
		$request->smsList = $modelSMS->getAll($dateDebut, $dateFin);
		$request->vue = $this->render("sms/index.php");
	}
	
	public function detail ($request) {
		$request->title = "Administration";
		$modelSMS = new Model_SMS();
		$modelSMS->id = $_GET['id'];
		$request->sms = $modelSMS->load();
		$request->vue = $this->render("sms/detail.php");
	}
	
	public function smsling ($request) {
		$modelSMS = new Model_SMS();
		$request->users = $modelSMS->getAllClientTel();
		$request->title = "Administration - SMSling";
		$request->vue = $this->render("sms/smsling.php");
	}
	
	public function send ($request) {
		$modelSMS = new Model_SMS();
		$message = $_POST['message'];
		$users = $modelSMS->getAllClientTel();
		foreach ($users as $user) {
			if (isset($_POST['user-'.$user->id]) && $_POST['user-'.$user->id] == 'on') {
				var_dump($user->nom.' '.$user->prenom.' ('.$user->telephone.')');
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
		die();
		$this->redirect('smsling', 'sms');
	}
}