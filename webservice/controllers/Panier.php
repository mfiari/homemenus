<?php

include_once MODEL_PATH.'Template.php';
/*include_once MODEL_PATH.'Panier.php';
include_once MODEL_PATH.'Commande.php';*/

class Controller_Panier extends Controller_Template {
	
	public function handle() {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "all" :
					$this->getAll();
					break;
				case "getByUser" :
					$this->getByUser();
					break;
				case "ajouter" :
					$this->ajouter();
					break;
				case "commande" :
					$this->commande();
					break;
				case "remove" :
					$this->remove();
					break;
			}
		} else {
			$this->getAll();
		}
	}
	
	private function getAll () {
		
	}
	
	private function getByUser () {
		if (isset($_GET["id_user"])) {
			$id_user = $_GET["id_user"];
			$ext = $this->getExtension();
			$model = new Model_Panier();
			$result = $model->getByUser($id_user);
			if ($result !== false) {
				require 'vue/panier_get.'.$ext.'.php';
			}
		}
	}
	
	private function ajouter () {
		if (!isset($_POST["id_menu"])) {
			die();
		}
		if (!isset($_POST["id_user"])) {
			die();
		}
		if (!isset($_POST["quantite"])) {
			die();
		}
		$id_menu = $_POST["id_menu"];
		$id_user = $_POST["id_user"];
		$quantite = $_POST["quantite"];
		$modelPanier = new Model_Panier();
		$id_panier = $modelPanier->create($id_user);
		$id_panier_menu = $modelPanier->addMenu($id_panier, $id_menu, $quantite);
		$modelContenu = new Model_Contenu();
		$result = $modelContenu->getCategorieByMenu($id_menu);
		foreach ($result as $key => $value) {
			$id_categorie = $value["id_categorie"];
			$id_contenu = $value["id"];
			$quantite = $value["quantite"];
			if (isset($_POST[$id_categorie."_".$id_contenu])) {
				$nbEnregistrement = 1;
				if ($quantite > 1) {
					$nbEnregistrement = $_POST["select".$id_categorie."_".$id_contenu];
				}
				for ($i = 0 ; $i < $nbEnregistrement ; $i++) {
					$modelPanier->create($id_panier_menu, $id_contenu);
				}
			}
		}
	}
	
	private function commande () {
		if (!isset($_POST["id_user"])) {
			die();
		}
		if (!isset($_POST["id_panier"])) {
			die();
		}
		$id_panier = $_POST["id_panier"];
		$id_user = $_POST["id_user"];
		$modelPanier = new Model_Panier();
		$modelCommande = new Model_Commande();
		$id_commande = $modelCommande->create($id_user);
		$menus = $modelPanier->getMenus($id_panier);
		foreach ($menus as $menu) {
			$id_commande_menu = $modelCommande->createMenu($id_commande, $menu["id_menu"], $menu["quantite"]);
			$contenus = $modelPanier->getContenus($menu["id"]);
			foreach ($contenus as $contenu) {
				$modelCommande->createContenu($id_commande_menu, $contenu["id_contenu"]);
			}
		}
		$modelPanier->deleteAll($id_user);
	}
	
	private function remove () {
		if (!isset($_POST["id_menu"])) {
			die();
		}
		if (!isset($_POST["id_user"])) {
			die();
		}
		$id_menu = $_POST["id_menu"];
		$id_user = $_POST["id_user"];
		$modelPanier = new Model_Panier();
		$modelPanier->remove($id_user, $id_menu);
	}
}
