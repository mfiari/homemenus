<?php

include_once WEBSITE_PATH."modules/ticket_resto/controllers/Template.php";

class Default_Manager {
	
	public function dispatch ($request) {
		if (isset($_GET["controler"])) {
			$controler = $_GET["controler"];
			$controller;
			switch ($controler) {
				case "index" :
					include_once WEBSITE_PATH."modules/ticket_resto/controllers/Index.php";
					$controller = new Controller_Index();
					$controller->manage($request);
					break;
				default :
					include_once WEBSITE_PATH."modules/default/controllers/Index.php";
					$controller = new Controller_Index();
					$controller->error_404($request);
			}
		} else {
			include_once WEBSITE_PATH."modules/ticket_resto/controllers/Index.php";
			$controller = new Controller_Index();
			$controller->manage($request);
		}
	}
	
	
}

?>