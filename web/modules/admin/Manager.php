<?php

include_once WEBSITE_PATH."modules/admin/controllers/Template.php";

class Admin_Manager {
	
	public function dispatch ($request) {
		if (isset($_GET["controler"])) {
			$controler = $_GET["controler"];
			$controller;
			switch ($controler) {
				case "index" :
					include_once WEBSITE_PATH."modules/admin/controllers/Index.php";
					$controller = new Controller_Index();
					$controller->manage($request);
					break;
				case "restaurant" :
					include_once WEBSITE_PATH."modules/admin/controllers/Restaurant.php";
					$controller = new Controller_Restaurant();
					$controller->manage($request);
					break;
				case "user" :
					include_once WEBSITE_PATH."modules/admin/controllers/User.php";
					$controller = new Controller_User();
					$controller->manage($request);
					break;
				case "panier" :
					include_once WEBSITE_PATH."modules/admin/controllers/Panier.php";
					$controller = new Controller_Panier();
					$controller->manage($request);
					break;
				case "commande" :
					include_once WEBSITE_PATH."modules/admin/controllers/Commande.php";
					$controller = new Controller_Commande();
					$controller->manage($request);
					break;
				case "codePromo" :
					include_once WEBSITE_PATH."modules/admin/controllers/CodePromo.php";
					$controller = new Controller_CodePromo();
					$controller->manage($request);
					break;
				case "recherche" :
					include_once WEBSITE_PATH."modules/admin/controllers/Recherche.php";
					$controller = new Controller_Recherche();
					$controller->manage($request);
					break;
				case "mail" :
					include_once WEBSITE_PATH."modules/admin/controllers/Mail.php";
					$controller = new Controller_Mail();
					$controller->manage($request);
					break;
				case "sms" :
					include_once WEBSITE_PATH."modules/admin/controllers/SMS.php";
					$controller = new Controller_SMS();
					$controller->manage($request);
					break;
				case "commentaire" :
					include_once WEBSITE_PATH."modules/admin/controllers/Commentaire.php";
					$controller = new Controller_Commentaire();
					$controller->manage($request);
					break;
				case "log" :
					include_once WEBSITE_PATH."modules/admin/controllers/Log.php";
					$controller = new Controller_Log();
					$controller->manage($request);
					break;
				case "cron" :
					include_once WEBSITE_PATH."modules/admin/controllers/Cron.php";
					$controller = new Controller_Cron();
					$controller->manage($request);
					break;
			}
		} else {
			include_once WEBSITE_PATH."modules/admin/controllers/Index.php";
			$controller = new Controller_Index();
			$controller->manage($request);
		}
	}
}

?>