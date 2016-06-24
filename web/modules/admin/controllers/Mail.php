<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Mail.php";

class Controller_Mail extends Controller_Admin_Template {
	
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
		$modelMail = new Model_Mail();
		$request->mails = $modelMail->getAll($dateDebut, $dateFin);
		$request->vue = $this->render("mails/index.php");
	}
	
	public function detail ($request) {
		$request->title = "Administration";
		$modelMail = new Model_Mail();
		$modelMail->id = $_GET['id'];
		$request->mail = $modelMail->load();
		$request->vue = $this->render("mails/detail.php");
	}
}