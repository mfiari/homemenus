<?php

include_once MODEL_PATH."News.php";

class Controller_News extends Controller_Admin_Template {
	
	public function manage ($request) {
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "livreur" :
					$this->livreur($request);
					break;
				case "livreurDispo" :
					$this->livreurDispo($request);
					break;
				case "edit" :
					$this->edit($request);
					break;
				case "clients" :
					$this->clients($request);
					break;
				case "addClient" :
					$this->addClient($request);
					break;
				case "client" :
					$this->client($request);
					break;
				case "enable" :
					$this->enable($request);
					break;
				case "disable" :
					$this->disable($request);
					break;
				case "deleted" :
					$this->deleted($request);
					break;
				case "add_dispo" :
					$this->add_dispo($request);
					break;
				case "deleteDispo" :
					$this->deleteDispo($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('news/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('news/'.$vue.'.php');
	}
	
	public function index ($request) {
		$modelNews = new Model_News(true, $request->dbConnector);
		$request->news = $modelNews->getAll();
		$request->title = "Administration - news";
		$request->vue = $this->render("index");
	}
	
	public function edit ($request) {
		if ($request->request_method == "POST") {
			$modelNews = new Model_News(true, $request->dbConnector);
			$modelNews->id = $_POST['id'];
			$modelNews->titre = $_POST['titre'];
			$modelNews->text = $_POST['texte'];
			$modelNews->link_text = $_POST['link_text'];
			$modelNews->link_url = $_POST['link_url'];
			$modelNews->date_debut = $_POST['date_debut'];
			$modelNews->date_fin = $_POST['date_fin'];
			
			$modelNews->save();
			
			if (isset($_FILES) && isset($_FILES['image']) && $_FILES['image']['name'] != '') {
				$image = $_FILES['image'];
				$ext = pathinfo($image['name'],  PATHINFO_EXTENSION);
				
				$uploaddir = WEBSITE_PATH.'res/img/news/';
				$uploadfile = $uploaddir.$modelNews->id.'.'.$ext;
				
				if (!move_uploaded_file($image['tmp_name'], $uploadfile)) {
					writeLog (SERVER_LOG, "erreur upload file $uploadfile", LOG_LEVEL_WARNING);
				} else {
					$modelNews->setImage('res/img/news/'.$modelNews->id.'.'.$ext);
				}
			}
			$this->redirect('index', 'news');
		} else {
			$request->title = "Administration - news";
			if (isset($_GET['id'])) {
				$modelNews = new Model_News(true, $request->dbConnector);
				$modelNews->id = $_GET['id'];
				$request->news = $modelNews->get();
			}
			$request->vue = $this->render("edit");
		}
	}
	
	public function enable ($request) {
		$model = new Model_News(true, $request->dbConnector);
		$model->id = trim($_GET["id_news"]);
		$model->enable();
		$this->redirect('index', 'news');
	}
	
	public function disable ($request) {
		$model = new Model_News(true, $request->dbConnector);
		$model->id = trim($_GET["id_news"]);
		$model->disable();
		$this->redirect('index', 'news');
	}
	
	public function deleted ($request) {
		$model = new Model_News(true, $request->dbConnector);
		$model->id = trim($_GET["id_news"]);
		$model->deleted();
		$this->redirect('index', 'news');
	}
}