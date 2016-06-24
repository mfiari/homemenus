<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";

class Controller_Compte extends Controller_Admin_Restaurant_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Compte";
		$modelUser = new Model_User();
		$modelUser->id = $request->_auth->id;
		$request->user = $modelUser->getById();
		$request->vue = $this->render("compte.php");
	}
}