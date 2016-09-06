<?php

ini_set('display_errors', '1');
ini_set('max_execution_time', 120);

include_once "../config.php";
include_once WEBSITE_PATH."core/Request.php";
include_once ROOT_PATH."function.php";

require_once WEBSITE_PATH.'res/lib/Mobile-Detect/Mobile_Detect.php';

session_start();

$request = new Request();

$request->request_method = $_SERVER['REQUEST_METHOD'];
$request->home = false;

$mobileDetect = new Mobile_Detect;

$request->mobileDetect = $mobileDetect;

if (isset($_SESSION["uid"]) && isset($_SESSION["session"])) {
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/Parametre.php";
	$user = new Model_User();
	if ($user->getBySession($_SESSION["uid"], $_SESSION["session"])) {
		$request->_auth = $user;
	} else {
		session_destroy();
	}
}

if ($request->_auth) {
	if ($request->_auth->status == USER_CLIENT) {
		include_once ROOT_PATH."models/Panier.php";
		include_once ROOT_PATH."models/Commande.php";
		$panier = new Model_Panier();
		$panier->uid = $request->_auth->id;
		$request->_itemsPanier = $panier->getNbArticle();
		$request->_id_restaurant_panier = $panier->getRestaurant();
		$commande = new Model_Commande();
		$commande->uid = $request->_auth->id;
		$request->_hasCommandeEnCours = $commande->hasCommandeEnCours();
		if ($request->_hasCommandeEnCours) {
			$request->_idCommandes = $commande->getIdCommandeEnCoursClient();
		}
	} else if ($request->_auth->status == USER_LIVREUR) {
		include_once ROOT_PATH."models/Commande.php";
		$commande = new Model_Commande();
		$commande->uid = $request->_auth->id;
		$request->_hasCommandeEnCours = $commande->hasCommandeEnCoursLivreur();
		if ($request->_hasCommandeEnCours) {
			$request->_idCommandes = $commande->getIdCommandeEnCoursLivreur();
		}
	} else if ($request->_auth->status == USER_ADMIN_RESTAURANT) {
		include_once ROOT_PATH."models/Restaurant.php";
		$modelRestaurant = new Model_Restaurant();
		$fields = array('id', 'nom');
		$request->_restaurant = $modelRestaurant->getByUser ($fields, $request->_auth->id);
	}
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
		if ($request->_auth->status == USER_ADMIN) {
			include_once WEBSITE_PATH."modules/admin/Manager.php";
			$manager = new Admin_Manager();
		} else if ($request->_auth->status == USER_LIVREUR) {
			include_once WEBSITE_PATH."modules/livreur/Manager.php";
			$manager = new Default_Manager();
		} else if ($request->_auth->status == USER_RESTAURANT) {
			include_once WEBSITE_PATH."modules/restaurant/Manager.php";
			$manager = new Default_Manager();
		} else if ($request->_auth->status == USER_ADMIN_RESTAURANT) {
			include_once WEBSITE_PATH."modules/admin_restaurant/Manager.php";
			$manager = new Default_Manager();
		} else if ($request->_auth->status == USER_ENTREPRISE) {
			include_once WEBSITE_PATH."modules/entreprise/Manager.php";
			$manager = new Default_Manager();
		} else if ($request->_auth->status == USER_ADMIN_ENTREPRISE) {
			include_once WEBSITE_PATH."modules/entreprise/Manager.php";
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
		if ($mobileDetect->isMobile() && !$mobileDetect->isTablet()) {
			require WEBSITE_PATH.'layouts/page_mobile.php';
		} else {
			//require WEBSITE_PATH.'layouts/page_mobile.php';
			require WEBSITE_PATH.'layouts/page.php';
		}
	}
}

?>