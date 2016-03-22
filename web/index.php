<?php

ini_set('display_errors', '1');
ini_set('max_execution_time', 120);

include_once "../config.php";
include_once WEBSITE_PATH."core/Request.php";
include_once ROOT_PATH."function.php";

session_start();

$request = new Request();

$request->request_method = $_SERVER['REQUEST_METHOD'];
$request->home = false;

if (isset($_SESSION["uid"]) && isset($_SESSION["session"])) {
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/User.php";
	$user = new Model_User();
	if ($user->getBySession($_SESSION["uid"], $_SESSION["session"])) {
		$request->_auth = $user;
	} else {
		session_destroy();
	}
}

if ($request->_auth && $request->_auth->status == "USER") {
	include_once ROOT_PATH."models/Panier.php";
	$panier = new Model_Panier();
	$panier->uid = $request->_auth->id;
	$request->_itemsPanier = $panier->getNbArticle();
	$request->_id_restaurant_panier = $panier->getRestaurant();
}

if (isset($_GET["module"])) {
	$module = $_GET["module"];
	switch ($module) {
		case "admin" :
			include_once WEBSITE_PATH."modules/admin/Manager.php";
			$manager = new Admin_Manager();
			break;
		case "default" :
			include_once WEBSITE_PATH."modules/default/Manager.php";
			$manager = new Default_Manager();
			break;
		case "livreur" :
			include_once WEBSITE_PATH."modules/livreur/Manager.php";
			$manager = new Default_Manager();
			break;
		case "restaurant" :
			include_once WEBSITE_PATH."modules/restaurant/Manager.php";
			$manager = new Default_Manager();
			break;
	}
	$manager->dispatch($request);
} else {
	if ($request->_auth) {
		if ($request->_auth->status == "ADMIN") {
			include_once WEBSITE_PATH."modules/admin/Manager.php";
			$manager = new Admin_Manager();
		} else if ($request->_auth->status == "LIVREUR") {
			include_once WEBSITE_PATH."modules/livreur/Manager.php";
			$manager = new Default_Manager();
		} else if ($request->_auth->status == "RESTAURANT") {
			include_once WEBSITE_PATH."modules/restaurant/Manager.php";
			$manager = new Default_Manager();
		} else if ($request->_auth->status == "ADMIN_RESTAURANT") {
			include_once WEBSITE_PATH."modules/admin_restaurant/Manager.php";
			$manager = new Default_Manager();
		} else {
			include_once WEBSITE_PATH."modules/default/Manager.php";
			$manager = new Default_Manager();
		}
	} else {
		include_once WEBSITE_PATH."modules/default/Manager.php";
		$manager = new Default_Manager();
	}
	$manager->dispatch($request);
}

if ($request->noRender === false) {
	if ($request->disableLayout) {
		require $request->vue;
	} else {
		require WEBSITE_PATH.'layouts/page.php';
	}
}

?>