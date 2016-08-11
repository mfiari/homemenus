<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Modification.php";

class Controller_Index extends Controller_Admin_Restaurant_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "updateInformation" :
					$this->updateInformation($request);
					break;
				case "cancelModification" :
					$this->cancelModification($request);
					break;
				case "logout" :
					$this->logout($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Administration";
		$modelRestaurant = new Model_Restaurant();
		$uid = $request->_auth->id;
		$request->restaurant = $modelRestaurant->loadByUser($uid);
		$modelUser = new Model_User();
		$request->users = $modelUser->getByRestaurant($request->_restaurant->id);
		$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places");
		$request->vue = $this->render("index.php");
	}
	
	public function updateInformation ($request) {
		if ($request->request_method == "POST") {
			$modelRestaurant = new Model_Restaurant();
			$uid = $request->_auth->id;
			$restaurant = $modelRestaurant->getByUser(array('nom', 'telephone'), $uid);
			$nom = $_POST['nom'];
			$telephone = $_POST['telephone'];
			$modelModification = new Model_Modification();
			if ($nom != $restaurant->nom) {
				$modelModification->prepareModification('restaurants', $restaurant->id, 'nom', $restaurant->nom, $nom, 'UPDATE', $uid);
			}
			if ($telephone != $restaurant->telephone) {
				$modelModification->prepareModification('restaurants', $restaurant->id, 'telephone', $restaurant->telephone, $telephone, 'UPDATE', $uid);
			}
		}
		$this->redirect();
	}
	
	public function cancelModification ($request) {
		$modelModification = new Model_Modification();
		$modelModification->id = $_GET['id'];
		$modelModification->cancelModification();
		$this->redirect();
	}
	
	public function logout ($request) {
		session_destroy();
		$this->redirect();
	}
}