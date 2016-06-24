<?php

include_once WEBSITE_PATH."modules/entreprise/controllers/Template.php";

class Default_Manager {
	
	public function dispatch ($request) {
		if (isset($_GET["controler"])) {
			$controler = $_GET["controler"];
			$controller;
			switch ($controler) {
				case "index" :
					include_once WEBSITE_PATH."modules/entreprise/controllers/Index.php";
					$controller = new Controller_Index();
					$controller->manage($request);
					break;
				case "commande" :
					include_once WEBSITE_PATH."modules/entreprise/controllers/Commande.php";
					$controller = new Controller_Commande();
					$controller->manage($request);
					break;
				case "restaurant" :
					include_once WEBSITE_PATH."modules/entreprise/controllers/Restaurant.php";
					$controller = new Controller_Restaurant();
					$controller->manage($request);
					break;
				case "compte" :
					include_once WEBSITE_PATH."modules/entreprise/controllers/Compte.php";
					$controller = new Controller_Compte();
					$controller->manage($request);
					break;
				case "precommande" :
					include_once WEBSITE_PATH."modules/entreprise/controllers/PreCommande.php";
					$controller = new Controller_Pre_Commande();
					$controller->manage($request);
					break;
			}
		} else {
			include_once WEBSITE_PATH."modules/entreprise/controllers/Index.php";
			$controller = new Controller_Index();
			$controller->manage($request);
		}
	}
	
	
}

?>