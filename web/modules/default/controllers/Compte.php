<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";

class Controller_Compte extends Controller_Default_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "activation" :
					$this->activation($request);
					break;
				default :
					$this->error_404($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		if ($request->user) {
			$request->title = "Compte";
			$request->vue = $this->render("compte.php");
		} else {
			$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/compte.js");
			$request->title = "Compte";
			$request->vue = $this->render("login.php");
		}
	}
	
	public function activation ($request) {
		$model = new Model_User();
		$model->id = trim($_GET["uid"]);
		$model->inscription_token = trim($_GET["token"]);
		if ($model->confirm()) {
			$_SESSION["uid"] = $model->id;
			$_SESSION["session"] = $model->session;
			$request->vue = $this->render("confirmation_inscription_success.php");
		} else {
			$request->vue = $this->render("confirmation_inscription_fail.php");
		}
	}
}