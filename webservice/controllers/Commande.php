<?php

include_once MODEL_PATH.'Template.php';
include_once MODEL_PATH.'Commande.php';
include_once MODEL_PATH.'Format.php';
include_once MODEL_PATH.'Formule.php';
include_once MODEL_PATH.'Contenu.php';
include_once MODEL_PATH.'Chat.php';
include_once MODEL_PATH."User.php";
include_once MODEL_PATH."GCMPushMessage.php";
include_once MODEL_PATH."Option.php";
include_once MODEL_PATH."OptionValue.php";
include_once MODEL_PATH."Accompagnement.php";

class Controller_Commande extends Controller_Template {
	
	public function handle() {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "nonAttribue" :
					$this->nonAttribue();
					break;
				case "commandeEnCoursClient" :
					$this->commandeEnCoursClient();
					break;
				case "view" :
					$this->view();
					break;
				case "commandesRestaurant" :
					$this->commandesRestaurant();
					break;
				case "commandeRestaurantRecu" :
					$this->commandesRestaurant(0);
					break;
				case "commandeRestaurantPreparation" :
					$this->commandesRestaurant(1);
					break;
				case "commandeRestaurant" :
					$this->commandeRestaurant();
					break;
				case "validationRestaurant" :
					$this->validationRestaurant();
					break;
				case "preparationRestaurant" :
					$this->preparationRestaurant();
					break;
				case "commandeEnCoursLivreur" :
					$this->commandeEnCoursLivreur();
					break;
				case "commandeLivreur" :
					$this->commandeLivreur();
					break;
				case "validationLivreur" :
					$this->validationLivreur();
					break;
				case "recuperationCommandeLivreur" :
					$this->recuperationCommandeLivreur();
					break;
				case "livraison" :
					$this->livraison();
					break;
				case "noter" :
					$this->noter();
					break;
				case "actifClient" :
					$this->actifClient();
					break;
				case "actifLivreur" :
					$this->actifLivreur();
					break;
				case "attenteRestaurant" :
					$this->attenteRestaurant();
					break;
				case "messages" :
					$this->messages();
					break;
				case "send" :
					$this->send();
					break;
			}
		}
	}
	
	private function nonAttribue () {
		if (!isset($_GET["id_user"])) {
			die();
		}
		$id_user = $_GET["id_user"];
		$ext = $this->getExtension();
		$commande = new Model_Commande();
		$commande->uid = $id_user;
		$result = $commande->getCommandeNonAttribue();
		require 'vue/commande/non_attribue.'.$ext.'.php';
	}
	
	private function commandeEnCoursClient () {
		if (!isset($_GET["id_user"])) {
			die();
		}
		$id_user = $_GET["id_user"];
		$ext = $this->getExtension();
		$commande = new Model_Commande();
		$commande->uid = $id_user;
		$result = $commande->loadNotFinishedCommande();
		require 'vue/commande/commandes_get.'.$ext.'.php';
	}
	
	private function view () {
		if (!isset($_GET["id_commande"])) {
			die();
		}
		$ext = $this->getExtension();
		
		$commande = new Model_Commande();
		$commande->id = $_GET["id_commande"];
		$result = $commande->load();
		require 'vue/commande/commande_get.'.$ext.'.php';
	}
	
	private function commandesRestaurant ($etape = false) {
		if (!isset($_GET["id_user"])) {
			die();
		}
		$id_user = $_GET["id_user"];
		$ext = $this->getExtension();
		$commande = new Model_Commande();
		$commande->uid = $id_user;
		$result = $commande->getCommandesRestaurant($etape);
		require 'vue/restaurant/commandes_get.'.$ext.'.php';
	}
	
	private function commandeRestaurant () {
		if (!isset($_GET["id_commande"])) {
			die();
		}
		$id_commande = $_GET["id_commande"];
		$ext = $this->getExtension();
		$commande = new Model_Commande();
		$commande->id = $id_commande;
		$result = $commande->getCommandeRestaurant();
		if (file_exists('vue/restaurant/commande_get.'.$ext.'.php')) {
			require 'vue/restaurant/commande_get.'.$ext.'.php';
		} else {
			var_dump("file not exists ".'vue/restaurant/commande_get.'.$ext.'.php');
		}
	}
	
	private function validationRestaurant () {
		if (!isset($_POST["id_user_restaurant"])) {
			die();
		}
		if (!isset($_POST["id_commande"])) {
			die();
		}
		$commande = new Model_Commande();
		$commande->id = $_POST["id_commande"];
		$commande->uid = $_POST["id_user_restaurant"];
		if ($commande->validationRestaurant()) {
			$livreur = $commande->getLivreur();
			if ($livreur->is_login) {
				if ($livreur->gcm_token) {
					$gcm = new GCMPushMessage(GOOGLE_API_KEY);
					$registatoin_ids = array($livreur->gcm_token);
					$message = "La commande #".$commande->id." a été validé";
					// listre des utilisateurs à notifier
					$gcm->setDevices($registatoin_ids);
					// Le titre de la notification
					$data = array(
						"title" => "Commande validé",
						"key" => "livreur-validation-commande",
						"id_commande" => $commande->id
					);
					// On notifie nos utilisateurs
					$result = $gcm->send($message, $data);
				}
			}
		}
	}
	
	private function preparationRestaurant () {
		if (!isset($_POST["id_user_restaurant"])) {
			die();
		}
		if (!isset($_POST["id_commande"])) {
			die();
		}
		$commande = new Model_Commande();
		$commande->id = $_POST["id_commande"];
		$commande->uid = $_POST["id_user_restaurant"];
		if ($commande->finPreparationRestaurant()) {
			$livreur = $commande->getLivreur();
			if ($livreur->is_login) {
				if ($livreur->gcm_token) {
					$gcm = new GCMPushMessage(GOOGLE_API_KEY);
					$registatoin_ids = array($livreur->gcm_token);
					$message = "La commande #".$commande->id." est prête";
					// listre des utilisateurs à notifier
					$gcm->setDevices($registatoin_ids);
					// Le titre de la notification
					$data = array(
						"title" => "Commande prête",
						"key" => "livreur-preparation-commande",
						"id_commande" => $commande->id
					);
					// On notifie nos utilisateurs
					$result = $gcm->send($message, $data);
				}
			}
		}
	}
	
	private function commandeEnCoursLivreur () {
		if (!isset($_GET["livreur"])) {
			die();
		}
		$id_user = $_GET["livreur"];
		$ext = $this->getExtension();
		$commande = new Model_Commande();
		$commande->uid = $id_user;
		$result = $commande->getCommandeEnCours();
		require 'vue/commande/commandes_get.'.$ext.'.php';
	}
	
	private function commandeLivreur () {
		if (!isset($_GET["id_commande"])) {
			die();
		}
		$id_commande = $_GET["id_commande"];
		$ext = $this->getExtension();
		$commande = new Model_Commande();
		$commande->id = $id_commande;
		$result = $commande->getCommandeLivreur();
		require 'vue/restaurant/commande_get.'.$ext.'.php';
	}
	
	private function validationLivreur () {
		if (!isset($_POST["id_user"])) {
			die();
		}
		if (!isset($_POST["id_commande"])) {
			die();
		}
		$commande = new Model_Commande();
		$commande->id = $_POST["id_commande"];
		$commande->uid = $_POST["id_user"];
		$commande->validationLivreur();
	}
	
	private function recuperationCommandeLivreur () {
		if (!isset($_POST["id_livreur"])) {
			die();
		}
		if (!isset($_POST["id_commande"])) {
			die();
		}
		$commande = new Model_Commande();
		$commande->id = $_POST["id_commande"];
		$commande->uid = $_POST["id_livreur"];
		$commande->recuperationLivreur();
	}
	
	private function livraison () {
		if (!isset($_POST["id_livreur"])) {
			die();
		}
		if (!isset($_POST["id_commande"])) {
			die();
		}
		$commande = new Model_Commande();
		$commande->id = $_POST["id_commande"];
		$commande->uid = $_POST["id_livreur"];
		$commande->livraison();
	}
	
	private function noter () {
		if (!isset($_POST["id_user"])) {
			die();
		}
		if (!isset($_POST["id_commande"])) {
			die();
		}
		if (!isset($_POST["note"])) {
			die();
		}
		if (!isset($_POST["commentaire"])) {
			die();
		}
		$id_commande = $_POST["id_commande"];
		$id_user = $_POST["id_user"];
		$note = $_POST["note"];
		$commentaire = $_POST["commentaire"];
		$model = new Model_Commande();
		$model->noter($id_commande, $id_user, $note, $commentaire);
	}
	
	private function actifClient () {
		if (!isset($_POST["id_user"])) {
			die();
		}
		$ext = $this->getExtension();
		$id_user = $_POST["id_user"];
		$model = new Model_Commande();
		$commandes = $model->getCommandeActifClient($id_user);
		$retour = $this->getCommandeDetail ($commandes);
		require 'vue/commande_get.'.$ext.'.php';
	}
	
	private function actifLivreur () {
		if (!isset($_POST["id_livreur"])) {
			die();
		}
		$ext = $this->getExtension();
		$id_livreur = $_POST["id_livreur"];
		$model = new Model_Commande();
		$commandes = $model->getCommandeActifLivreur($id_livreur);
		$retour = $this->getCommandeDetail ($commandes);
		require 'vue/commande_get.'.$ext.'.php';
	}
	
	private function getCommandeDetail ($commandes) {
		$model = new Model_Commande();
		$retour = array();
		foreach ($commandes as $commande) {
			$commande_id = $commande["id"];
			$retour[$commande_id] = array();
			$retour[$commande_id]["user"] = array();
			$retour[$commande_id]["user"]["id"] = $commande["uid"];
			$retour[$commande_id]["user"]["nom"] = $commande["nom"];
			$retour[$commande_id]["user"]["prenom"] = $commande["prenom"];
			$retour[$commande_id]["user"]["rue"] = $commande["rue"];
			$retour[$commande_id]["user"]["ville"] = $commande["ville"];
			$retour[$commande_id]["user"]["code_postal"] = $commande["code_postal"];
			$retour[$commande_id]["user"]["telephone"] = $commande["telephone"];
			$retour[$commande_id]["commande"] = array();
			$retour[$commande_id]["commande"]["date_commande"] = $commande["date_commande"];
			$retour[$commande_id]["commande"]["etape"] = $commande["etape"];
			$retour[$commande_id]["commande"]["menus"] = array();
			
			$commandesMenu = $model->getCommandeMenu($commande_id);
			foreach ($commandesMenu as $commandeMenu) {
				$id_commande_menu = $commandeMenu["id"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu] = array();
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["quantite"] = $commandeMenu["quantite"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["nom"] = $commandeMenu["menu"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["prix"] = $commandeMenu["prix"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["restaurant"] = array();
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["restaurant"]["id"] = $commandeMenu["id_restaurant"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["restaurant"]["nom"] = utf8_encode($commandeMenu["nom"]);
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["restaurant"]["rue"] = $commandeMenu["rue"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["restaurant"]["ville"] = $commandeMenu["ville"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["restaurant"]["code_postal"] = $commandeMenu["code_postal"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["restaurant"]["telephone"] = $commandeMenu["telephone"];
				$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["categories"] = array();
				
				$commandesContenu = $model->getCommandeContenu($id_commande_menu);
				$previousCategorie = 0;
				foreach ($commandesContenu as $commandeContenu) {
					$id_categorie = $commandeContenu["id_categorie"];
					if ($previousCategorie != $id_categorie) {
						$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["categories"][$id_categorie] = array();
						$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["categories"][$id_categorie]["nom"] = $commandeContenu["categorie"];
						$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["categories"][$id_categorie]["contenus"] = array();
						$previousCategorie = $id_categorie;
					}
					$id_commande_contenu = $commandeContenu["id"];
					$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["categories"][$id_categorie]["contenus"][$id_commande_contenu] = array();
					$retour[$commande_id]["commande"]["menus"][$id_commande_menu]["categories"][$id_categorie]["contenus"][$id_commande_contenu]["nom"] = $commandeContenu["nom"];
				}
			}
		}
		return $retour;
	}
	
	private function attenteRestaurant() {
		if (!isset($_POST["id_user"])) {
			die();
		}
		$ext = $this->getExtension();
		$id_user = $_POST["id_user"];
		$model = new Model_Commande();
		$commandes = $model->getCommandeAttenteRestaurant($id_user);
		$retour = array();
		$previousCommande = 0;
		foreach ($commandes as $commande) {
			$id_commande = $commande["id_commande"];
			if ($previousCommande != $id_commande) {
				$retour[$id_commande]["menus"] = array();
				$previousCommande = $id_commande;
			}
			$id_commande_menu = $commande["id"];
			$retour[$id_commande]["menus"][$id_commande_menu] = array();
			$retour[$id_commande]["menus"][$id_commande_menu]["nom"] = $commande["nom"];
			$retour[$id_commande]["menus"][$id_commande_menu]["quantite"] = $commande["quantite"];
			$retour[$id_commande]["menus"][$id_commande_menu]["categories"] = array();
			$commandesContenu = $model->getCommandeContenu($id_commande_menu);
			$previousCategorie = 0;
			foreach ($commandesContenu as $commandeContenu) {
				$id_categorie = $commandeContenu["id_categorie"];
				if ($previousCategorie != $id_categorie) {
					$retour[$id_commande]["menus"][$id_commande_menu]["categories"][$id_categorie] = array();
					$retour[$id_commande]["menus"][$id_commande_menu]["categories"][$id_categorie]["nom"] = $commandeContenu["categorie"];
					$retour[$id_commande]["menus"][$id_commande_menu]["categories"][$id_categorie]["contenus"] = array();
					$previousCategorie = $id_categorie;
				}
				$id_commande_contenu = $commandeContenu["id"];
				$retour[$id_commande]["menus"][$id_commande_menu]["categories"][$id_categorie]["contenus"][$id_commande_contenu] = array();
				$retour[$id_commande]["menus"][$id_commande_menu]["categories"][$id_categorie]["contenus"][$id_commande_contenu]["nom"] = $commandeContenu["nom"];
			}
		}
		require 'vue/commande_get.'.$ext.'.php';
	}
	
	public function messages () {
		$id_commande = $_GET['id_commande'];
		$ext = $this->getExtension();
		$chat = new Model_Chat();
		$chat->id_commande = $id_commande;
		$messages = $chat->getChatCommande();
		require 'vue/commande/chat.'.$ext.'.php';
	}
	
	public function send () {
		$id_commande = $_POST['id_commande'];
		$message = $_POST['message'];
		$sender = "LIVREUR";
		$chat = new Model_Chat();
		$chat->id_commande = $id_commande;
		$chat->sender = $sender;
		$chat->message = $message;
		$chat->save();
	}
}