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
				case "updateDistanceRestaurant" :
					$this->updateDistanceRestaurant($request);
					break;
				case "updateDistanceLivreur" :
					$this->updateDistanceLivreur($request);
					break;
				case "livreurPlaning" :
					$this->livreurPlaning($request);
					break;
				case "dumpDatabase" :
					$this->dumpDatabase($request);
					break;
				case "copyDatabase" :
					$this->copyDatabase($request);
					break;
				case "videPanier" :
					$this->videPanier($request);
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
		exec('php '.ROOT_PATH.'cron/dispatch_livreur.php');
		$this->redirect('index', 'cron');
	}
	
	public function runCreateCommande ($request) {
		exec('php '.ROOT_PATH.'cron/create_commande.php');
		$this->redirect('index', 'cron');
	}
	
	public function runCommandeHistory ($request) {
		exec('php '.ROOT_PATH.'cron/commande_in_history.php');
		$this->redirect('index', 'cron');
	}
	
	public function runPreCommande ($request) {
		exec('php '.ROOT_PATH.'cron/manage_pre_commande.php');
		$this->redirect('index', 'cron');
	}
	
	public function updateDistanceRestaurant ($request) {
		exec('php '.ROOT_PATH.'cron/distance_resto_livreur.php');
		$this->redirect('index', 'cron');
	}
	
	public function updateDistanceLivreur ($request) {
		exec('php '.ROOT_PATH.'cron/distance_livreur_dispo.php');
		$this->redirect('index', 'cron');
	}
	
	public function livreurPlaning ($request) {
		exec('php '.ROOT_PATH.'cron/livreur_planing.php');
		$this->redirect('index', 'cron');
	}
	
	public function dumpDatabase ($request) {
		exec('php '.ROOT_PATH.'cron/dump_database.php');
		$this->redirect('index', 'cron');
	}
	
	public function copyDatabase ($request) {
		$database = $_GET['databse'];
		if ($database == 'test') {
			exec('php '.ROOT_PATH.'cron/copy_database_test.php');
		} else if ($database == 'demo') {
			exec('php '.ROOT_PATH.'cron/copy_database_demo.php');
		} else if ($database == 'recette') {
			exec('php '.ROOT_PATH.'cron/copy_database_recette.php');
		}
		$this->redirect('index', 'cron');
	}
	
	public function videPanier ($request) {
		exec('php '.ROOT_PATH.'cron/vide_panier.php');
		$this->redirect('index', 'cron');
	}
}