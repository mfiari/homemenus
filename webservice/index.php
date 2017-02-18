<?php

include_once "../config.php";
include_once 'controllers/Template.php';
include_once 'controllers/User.php';
include_once 'controllers/Restaurant.php';
include_once 'controllers/Menu.php';
include_once 'controllers/Commande.php';
include_once 'controllers/Panier.php';
include_once 'controllers/Livreur.php';

include_once ROOT_PATH."function.php";
include_once MODEL_PATH."Template.php";
include_once MODEL_PATH."DbConnector.php";

register_shutdown_function( "fatal_error_handler" );

session_start();

if (isset($_GET["module"])) {
	$module = $_GET["module"];
	$controller;
	switch ($module) {
		case "user" :
			$controller = new Controller_User();
			break;
		case "restaurant" :
			$controller = new Controller_Restaurant();
			break;
		case "menu" :
			$controller = new Controller_Menu();
			break;
		case "commande" :
			$controller = new Controller_Commande();
			break;
		case "panier" :
			$controller = new Controller_Panier();
			break;
		case "livreur" :
			$controller = new Controller_Livreur();
			break;
	}
	$controller->handle();
} else {
	
	
}


?>