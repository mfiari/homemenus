<?php

ini_set('display_errors', '1');
ini_set('max_execution_time', 120);

include_once "../config.php";
include_once WEBSITE_PATH."core/Request.php";
include_once ROOT_PATH."function.php";
include_once MODEL_PATH."Template.php";
include_once MODEL_PATH."DbConnector.php";

require_once WEBSITE_PATH.'res/lib/Mobile-Detect/Mobile_Detect.php';

register_shutdown_function( "fatal_error_handler" );

session_start();

$request = new Request();

$request->request_method = $_SERVER['REQUEST_METHOD'];
$request->home = false;

$mobileDetect = new Mobile_Detect;

$request->mobileDetect = $mobileDetect;
$request->dbConnector = Model_Template::getDbConnector();

if (MAINTENANCE) {
	$allowedUrls = explode(',', ALLOWED_IP);
	if (!in_array($_SERVER['REMOTE_ADDR'], $allowedUrls)) {
		require WEBSITE_PATH.'layouts/maintenance.php';
		exit;
	}
}

if (isset($_SESSION["uid"]) && isset($_SESSION["session"])) {
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/Parametre.php";
	$user = new Model_User(true, $request->dbConnector);
	if ($user->getBySession($_SESSION["session"])) {
		$request->_auth = $user;
	} else {
		session_destroy();
		if (isset($_COOKIE["SESSION_KEY"])) {
			unset($_COOKIE["SESSION_KEY"]);
			setcookie("SESSION_KEY", "", time()-3600);
		}
	}
} else if (isset($_COOKIE["SESSION_KEY"])) {
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/Parametre.php";
	$user = new Model_User(true, $request->dbConnector);
	if ($user->getBySession($_COOKIE["SESSION_KEY"])) {
		$request->_auth = $user;
	} else {
		session_destroy();
		unset($_COOKIE["SESSION_KEY"]);
		setcookie("SESSION_KEY", "", time()-3600);
	}
}

if ($request->_auth) {
	$request->_auth->updateSession();
	if ($request->_auth->status == USER_CLIENT) {
		include_once ROOT_PATH."models/Panier.php";
		include_once ROOT_PATH."models/Commande.php";
		$panier = new Model_Panier(true, $request->dbConnector);
		$panier->uid = $request->_auth->id;
		$request->_itemsPanier = $panier->getNbArticle();
		$request->_id_restaurant_panier = $panier->getRestaurant();
		$commande = new Model_Commande(true, $request->dbConnector);
		$commande->uid = $request->_auth->id;
		$request->_hasCommandeEnCours = $commande->hasCommandeEnCours();
		if ($request->_hasCommandeEnCours) {
			$request->_idCommandes = $commande->getIdCommandeEnCoursClient();
		}
	} else if ($request->_auth->status == USER_LIVREUR) {
		include_once ROOT_PATH."models/Commande.php";
		$commande = new Model_Commande(true, $request->dbConnector);
		$commande->uid = $request->_auth->id;
		$request->_hasCommandeEnCours = $commande->hasCommandeEnCoursLivreur();
		if ($request->_hasCommandeEnCours) {
			$request->_idCommandes = $commande->getIdCommandeEnCoursLivreur();
		}
	} else if ($request->_auth->status == USER_ADMIN_RESTAURANT) {
		include_once ROOT_PATH."models/Restaurant.php";
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
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
		if ($request->_auth->status == USER_ADMIN || $request->_auth->status == USER_ADMIN_INFO || $request->_auth->status == USER_ADMIN_CLIENT) {
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
		} else if ($request->_auth->status == USER_TICKET_RESTO) {
			include_once WEBSITE_PATH."modules/ticket_resto/Manager.php";
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

$request->dbConnector->closeConnection();

?>