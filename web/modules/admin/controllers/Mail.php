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
		$request->title = "Administration";
		$modelMail = new Model_Mail();
		$request->mails = $modelMail->getAll();
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