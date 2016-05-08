<?php

class Controller_Cron extends Controller_Admin_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "runDispatchLivreur" :
					$this->runDispatchLivreur($request);
					break;
				case "runCreateCommande" :
					$this->runCreateCommande($request);
					break;
				case "runCommandeHistory" :
					$this->runCommandeHistory($request);
					break;
				case "runPreCommande" :
					$this->runPreCommande($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Administration - Cron";
		$request->vue = $this->render("cron/index.php");
	}
	
	public function runDispatchLivreur ($request) {
		exec(ROOT_PATH.'cron/dispatch_livreur.php');
		$this->redirect('index', 'cron');
	}
	
	public function runCreateCommande ($request) {
		exec(ROOT_PATH.'cron/create_commande.php');
		$this->redirect('index', 'cron');
	}
	
	public function runCommandeHistory ($request) {
		exec(ROOT_PATH.'cron/commande_in_history.php');
		$this->redirect('index', 'cron');
	}
	
	public function runPreCommande ($request) {
		exec(ROOT_PATH.'cron/manage_pre_commande.php');
		$this->redirect('index', 'cron');
	}
}