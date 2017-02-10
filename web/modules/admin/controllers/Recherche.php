<?php

include_once ROOT_PATH."models/Recherche.php";
include_once ROOT_PATH."models/Restaurant.php";

class Controller_Recherche extends Controller_Admin_Template {
	
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
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('recherche/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('recherche/'.$vue.'.php');
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
		$modelRecherche = new Model_Recherche(true, $request->dbConnector);
		$request->recherches = $modelRecherche->getAll($dateDebut, $dateFin);
		$request->vue = $this->render("index");
	}
	
	public function detail ($request) {
		$request->title = "Administration";
		$modelRecherche = new Model_Recherche(true, $request->dbConnector);
		$modelRecherche->id = $_GET['id'];
		$request->recherche = $modelRecherche->load();
		$request->vue = $this->render("detail");
	}
}