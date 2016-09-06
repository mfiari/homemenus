<?php

include_once ROOT_PATH."function.php";

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/CommandeHistory.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Chat.php";

class Controller_Commande extends Controller_Livreur_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "history" :
					$this->history($request);
					break;
				case "validation" :
					$this->validation($request);
					break;
				case "recuperation" :
					$this->recuperation($request);
					break;
				case "livraison" :
					$this->livraison($request);
					break;
				case "detail" :
					$this->detail($request);
					break;
				case "hasChat" :
					$this->hasChat($request);
					break;
				case "getChat" :
					$this->getChat($request);
					break;
				case "sendMessage" :
					$this->sendMessage($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		if (isset($_GET["id"])) {
			$commande = new Model_Commande();
			$commande->uid = $request->_auth->id;
			$commande->id = $_GET["id"];
			$request->commande = $commande->load();
			$request->vue = $this->render("commande.php");
		} else {
			$commande = new Model_Commande();
			$commande->uid = $request->_auth->id;
			$request->commandes = $commande->getCommandesLivreur();
			$request->vue = $this->render("commandes.php");
		}
	}
	
	public function history ($request) {
		if (isset($_GET["id"])) {
			$commande = new Model_Commande_History();
			$commande->id = $_GET["id"];
			$request->commande = $commande->load();
			$request->vue = $this->render("commande_history.php");
		} else {
			$commande = new Model_Commande_History();
			$commande->uid = $request->_auth->id;
			$request->commandes = $commande->loadCommandeLivreur();
			$request->vue = $this->render("commandes_history.php");
		}
	}
	
	public function validation ($request) {
		if (!isset($_GET['id'])) {
			return;
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$commande->uid = $request->_auth->id;
		$commande->validationLivreur();
	}
	
	public function recuperation ($request) {
		if (!isset($_GET['id'])) {
			return;
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$commande->uid = $request->_auth->id;
		$commande->recuperationLivreur();
		$this->redirect("index", "index");
	}
	
	public function livraison ($request) {
		if (!isset($_GET['id'])) {
			return;
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$commande->uid = $request->_auth->id;
		$commande->livraison();
		$this->redirect("index", "index");
	}
	
	public function detail ($request) {
		if (!isset($_GET['id'])) {
			$this->redirect();
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$request->commande = $commande->getCommandeLivreur();
		$request->vue = $this->render("detail_commande.php");
	}
	
	public function hasChat ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		$id_commande = $_GET['id_commande'];
		$chat = new Model_Chat();
		$chat->id_commande = $id_commande;
		echo $chat->hasChatLivreur();
	}
	
	public function getChat ($request) {
		$request->disableLayout = true;
		$id_commande = $_GET['id_commande'];
		$request->id_commande = $id_commande;
		$chat = new Model_Chat();
		$chat->id_commande = $id_commande;
		$request->messages = $chat->getChatCommande();
		$chat->vueLivreur();
		$commande = new Model_Commande();
		$commande->id = $id_commande;
		$request->client = $commande->getClient();
		$request->vue = $this->render("chat.php");
	}
	
	public function sendMessage ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		$id_commande = $_POST['id_commande'];
		$message = $_POST['message'];
		$sender = "LIVREUR";
		$chat = new Model_Chat();
		$chat->id_commande = $id_commande;
		$chat->sender = $sender;
		$chat->message = $message;
		$chat->save();
		$commande = new Model_Commande();
		$commande->id = $id_commande;
		$client = $commande->getClient();
		$registatoin_ids = array($client->gcm_token);
		$gcm = new GCMPushMessage(GOOGLE_API_KEY);
		$message = "Vous avez un nouveau message";
		// listre des utilisateurs à notifier
		$gcm->setDevices($registatoin_ids);
		 
		// Le titre de la notification
		$data = array(
			"title" => "Nouveau message",
			"key" => "client-new-message",
			"id_commande" => $id_commande
		);
		 
		// On notifie nos utilisateurs
		$result = $gcm->send($message, $data);
	}
}