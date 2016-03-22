<?php

include_once "../config.php";
include_once 'controllers/Template.php';
include_once 'controllers/User.php';
include_once 'controllers/Restaurant.php';
include_once 'controllers/Menu.php';
include_once 'controllers/Commande.php';
include_once 'controllers/Panier.php';
include_once 'controllers/Livreur.php';

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