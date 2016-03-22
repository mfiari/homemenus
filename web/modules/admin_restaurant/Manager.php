<?php

include_once WEBSITE_PATH."modules/admin_restaurant/controllers/Template.php";

class Default_Manager {
	
	public function dispatch ($request) {
		if (isset($_GET["controler"])) {
			$controler = $_GET["controler"];
			$controller;
			switch ($controler) {
				case "index" :
					include_once WEBSITE_PATH."modules/admin_restaurant/controllers/Index.php";
					$controller = new Controller_Index();
					$controller->manage($request);
					break;
				case "commande" :
					include_once WEBSITE_PATH."modules/admin_restaurant/controllers/Commande.php";
					$controller = new Controller_Commande();
					$controller->manage($request);
					break;
			}
		} else {
			include_once WEBSITE_PATH."modules/admin_restaurant/controllers/Index.php";
			$controller = new Controller_Index();
			$controller->manage($request);
		}
	}
	
	
}

?>