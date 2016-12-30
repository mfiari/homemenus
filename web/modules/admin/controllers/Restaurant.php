<?php

include_once ROOT_PATH."function.php";

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Tag.php";
include_once ROOT_PATH."models/Supplement.php";
include_once ROOT_PATH."models/Option.php";
include_once ROOT_PATH."models/OptionValue.php";
include_once ROOT_PATH."models/Accompagnement.php";

class Controller_Restaurant extends Controller_Admin_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "edit" :
					$this->edit($request);
					break;
				case "enable" :
					$this->enable($request);
					break;
				case "disable" :
					$this->disable($request);
					break;
				case "deleted" :
					$this->deleted($request);
					break;
				case "view" :
					$this->view($request);
					break;
				case "adduser" :
					$this->adduser($request);
					break;
				case "enableUser" :
					$this->enableUser($request);
					break;
				case "disableUser" :
					$this->disableUser($request);
					break;
				case "deleteUser" :
					$this->deleteUser($request);
					break;
				case "viewCategorie" :
					$this->viewCategorie($request);
					break;
				case "editMenu" :
					$this->editMenu($request);
					break;
				case "viewMenu" :
					$this->viewMenu($request);
					break;
				case "modifyMenu" :
					$this->modifyMenu($request);
					break;
				case "deleteMenu" :
					$this->deleteMenu($request);
					break;
				case "editContenu" :
					$this->editContenu($request);
					break;
				case "removeStock" :
					$this->removeStock($request);
					break;
				case "addStock" :
					$this->addStock($request);
					break;
				case "deleteContenu" :
					$this->deleteContenu($request);
					break;
				case "addContenuToMenu" :
					$this->addContenuToMenu($request);
					break;
				case "addCategorie" :
					$this->addCategorie($request);
					break;
				case "modifyCategorie" :
					$this->modifyCategorie($request);
					break;
				case "deleteCategorie" :
					$this->deleteCategorie($request);
					break;
				case "addCategorieToMenu" :
					$this->addCategorieToMenu($request);
					break;
				case "addFormat" :
					$this->addFormat($request);
					break;
				case "modifyFormat" :
					$this->modifyFormat($request);
					break;
				case "deleteFormat" :
					$this->deleteFormat($request);
					break;
				case "addFormule" :
					$this->addFormule($request);
					break;
				case "modifyFormule" :
					$this->modifyFormule($request);
					break;
				case "deleteFormule" :
					$this->deleteFormule($request);
					break;
				case "addSupplement" :
					$this->addSupplement($request);
					break;
				case "modifySupplement" :
					$this->modifySupplement($request);
					break;
				case "deleteSupplement" :
					$this->deleteSupplement($request);
					break;
				case "addOption" :
					$this->addOption($request);
					break;
				case "viewOption" :
					$this->viewOption($request);
					break;
				case "addOptionValue" :
					$this->addOptionValue($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Administration - restaurant";
		$modelRestaurant = new Model_Restaurant();
		$request->restaurants = $modelRestaurant->getAll();
		$request->vue = $this->render("restaurant/index.php");
	}
	
	public function edit ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$nom = $_POST['nom'];
			$telephone = $_POST['telephone'];
			$pourcentage = $_POST['pourcentage'];
			$virement = $_POST['virement'];
			$short_desc = $_POST['short_desc'];
			$long_desc = $_POST['long_desc'];
			
			$adresse = $_POST["adresse"];
			$geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
			$localisation = urlencode($adresse);
			$query = sprintf($geocoder,$localisation);
			$rd = json_decode(file_get_contents($query));
			if ($rd->{'status'} == "OK") {
				$addressComponents = $rd->{'results'}[0]->{'address_components'};
				$code_postal = "";
				$ville = "";
				$street_number = "";
				$route = "";
				for ($i = 0 ; $i < count($addressComponents) ; $i++) {
					if ($addressComponents[$i]->{'types'}[0] == 'postal_code') {
						$code_postal = $addressComponents[$i]->{'short_name'};
					} else if ($addressComponents[$i]->{'types'}[0] == 'locality') {
						$ville = $addressComponents[$i]->{'long_name'};
					} else if ($addressComponents[$i]->{'types'}[0] == 'street_number') {
						$street_number = $addressComponents[$i]->{'long_name'};
					} else if ($addressComponents[$i]->{'types'}[0] == 'route') {
						$route = $addressComponents[$i]->{'long_name'};
					}
				}
				$rue = $street_number.' '.$route;
				$coord = $rd->{'results'}[0]->{'geometry'}->{'location'};
				$latitude = $coord->{'lat'};
				$longitude = $coord->{'lng'};
				
				$modelRestaurant = new Model_Restaurant();
				$modelRestaurant->id = $id_restaurant;
				$modelRestaurant->nom = $nom;
				$modelRestaurant->rue = $rue;
				$modelRestaurant->ville = $ville;
				$modelRestaurant->code_postal = $code_postal;
				$modelRestaurant->telephone = $telephone;
				$modelRestaurant->pourcentage = $pourcentage;
				$modelRestaurant->latitude = $latitude;
				$modelRestaurant->longitude = $longitude;
				$modelRestaurant->short_desc = $short_desc;
				$modelRestaurant->long_desc = $long_desc;
				
				$periodes = array("midi", "soir");
				
				for ($i = 1 ; $i <= 7 ; $i++) {
					foreach ($periodes as $periode) {
						if (!isset($_POST['ferme_'.$i.'_'.$periode]) || $_POST['ferme_'.$i.'_'.$periode] != 'on') {
							$horaire = new Model_Horaire();
							$horaire->id_jour = $i;
							$horaire->heure_debut = $_POST['de_'.$i.'_'.$periode.'_heure'];
							$horaire->minute_debut = $_POST['de_'.$i.'_'.$periode.'_minute'];
							$horaire->heure_fin = $_POST['a_'.$i.'_'.$periode.'_heure'];
							$horaire->minute_fin = $_POST['a_'.$i.'_'.$periode.'_minute'];
							$modelRestaurant->addHoraire($horaire);
						}
					}
				}
				
				$modelRestaurant->save();
				if (isset($_FILES) && isset($_FILES['logo']) && $_FILES['logo']['name'] != '') {
					$logo = $_FILES['logo'];
					$ext = pathinfo($logo['name'],  PATHINFO_EXTENSION);
					
					$uploaddir = WEBSITE_PATH.'res/img/restaurant/';
					if (!file_exists($uploaddir.$modelRestaurant->id)) {
						mkdir($uploaddir.$modelRestaurant->id);
						mkdir($uploaddir.$modelRestaurant->id.'/categories');
						mkdir($uploaddir.$modelRestaurant->id.'/contenus');
					}
					$uploadfile = $uploaddir.$modelRestaurant->id.'/logo'.'.'.$ext;
					
					if (!move_uploaded_file($logo['tmp_name'], $uploadfile)) {
						writeLog (SERVER_LOG, "erreur upload file $uploadfile", LOG_LEVEL_WARNING);
					}
				}
				$this->redirect('index', 'restaurant');
			} else {
				var_dump($rd); die();
			}
		} else {
			$request->title = "Administration - restaurant";
			if (isset($_GET['id_restaurant'])) {
				$modelRestaurant = new Model_Restaurant();
				$modelRestaurant->id = $_GET['id_restaurant'];
				$request->restaurant = $modelRestaurant->getOne();
			}
			$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places");
			$request->vue = $this->render("restaurant/edit.php");
		}
	}
	
	public function enable ($request) {
		$model = new Model_Restaurant();
		$model->id = trim($_GET["id_restaurant"]);
		$model->enable();
		$this->redirect('index', 'restaurant');
	}
	
	public function disable ($request) {
		$model = new Model_Restaurant();
		$model->id = trim($_GET["id_restaurant"]);
		$model->disable();
		$this->redirect('index', 'restaurant');
	}
	
	public function deleted ($request) {
		$model = new Model_Restaurant();
		$model->id = trim($_GET["id_restaurant"]);
		$model->deleted();
		$this->redirect('index', 'restaurant');
	}
	
	public function view ($request) {
		if (!isset($_GET['id_restaurant'])) {
			$this->redirect();
		}
		$request->title = "Administration - restaurant";
		$users = new Model_User();
		$request->users = $users->getByRestaurant($_GET['id_restaurant']);
		
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id_restaurant'];
		$fields = array(
			"nom", "rue", "code_postal", "ville", "latitude", "longitude"
		);
		$request->restaurant = $modelRestaurant->get($fields);
		$request->restaurant->loadCategories();
		$request->restaurant->loadMenus();
		$request->restaurant->loadTags();
		$request->restaurant->loadFormat();
		$request->restaurant->loadFormule();
		$request->restaurant->loadSupplements();
		$request->restaurant->loadOptions();
		$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places");
		$request->vue = $this->render("restaurant/view.php");
	}
	
	public function adduser ($request) {
		if ($request->request_method == "POST") {
			$nom = $_POST['nom'];
			$prenom = $_POST['prenom'];
			$login = $_POST['login'];
			$email = $_POST['email'];
			$status = $_POST['status'];
			$restaurant = $_POST['restaurant'];
			
			$modelUser = new Model_User();
			$modelUser->nom = $nom;
			$modelUser->prenom = $prenom;
			$modelUser->login = $login;
			$modelUser->email = $email;
			$modelUser->password = generatePassword();
			$modelUser->inscription_token = generateToken();
			$modelUser->status = $status;
			$modelUser->id_restaurant = $restaurant;
			
			$modelUser->save();
			
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $_GET['id_restaurant'];
			$modelRestaurant->loadMinInformation();
			
			$messageContent =  file_get_contents (ROOT_PATH.'mails/inscription_restaurant.html');
			
			$messageContent = str_replace("[NOM]", $nom, $messageContent);
			$messageContent = str_replace("[PRENOM]", $prenom, $messageContent);
			$messageContent = str_replace("[STATUS]", $modelUser->status == 'ADMIN_RESTAURANT' ? 'administrateur' : 'utilisateur', $messageContent);
			$messageContent = str_replace("[RESTAURANT]", $modelRestaurant->nom, $messageContent);
			$messageContent = str_replace("[LOGIN]", $login, $messageContent);
			$messageContent = str_replace("[PASSWORD]", $modelUser->password, $messageContent);
			$messageContent = str_replace("[UID]", $modelUser->id, $messageContent);
			$messageContent = str_replace("[TOKEN]", $modelUser->inscription_token, $messageContent);
			$messageContent = str_replace("[WEBSITE_URL]", WEBSITE_URL, $messageContent);
			
			send_mail ($modelUser->email, "Création de votre compte restaurateur", $messageContent);
			$this->redirect('view', 'restaurant', '', array('id_restaurant' => $restaurant));
		} else {
			$request->title = "Administration - user";
			if (isset($_GET['id_user'])) {
				$modelUser = new Model_User();
				$modelUser->id = $_GET['id_user'];
				$request->user = $modelUser->get();
			}
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $_GET['id_restaurant'];
			$request->restaurant = $modelRestaurant->getOne();
			$request->vue = $this->render("restaurant/adduser.php");
		}
	}
	
	public function enableUser ($request) {
		$model = new Model_User();
		$id_restaurant = trim($_GET["id_restaurant"]);
		$model->id = trim($_GET["id_user"]);
		$model->enable();
		$this->redirect('view', 'restaurant', '', array('id_restaurant' => $id_restaurant));
	}
	
	public function disableUser ($request) {
		$model = new Model_User();
		$id_restaurant = trim($_GET["id_restaurant"]);
		$model->id = trim($_GET["id_user"]);
		$model->disable();
		$this->redirect('view', 'restaurant', '', array('id_restaurant' => $id_restaurant));
	}
	
	public function deleteUser ($request) {
		$model = new Model_User();
		$id_restaurant = trim($_GET["id_restaurant"]);
		$model->id = trim($_GET["id_user"]);
		$model->deleted();
		$this->redirect('view', 'restaurant', '', array('id_restaurant' => $id_restaurant));
	}
	
	public function viewCategorie ($request) {
		if (!isset($_GET['id_restaurant'])) {
			$this->redirect();
		}
		if (!isset($_GET['id_categorie'])) {
			$this->redirect();
		}
		$request->title = "Administration - restaurant";
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id_restaurant'];
		$request->restaurant = $modelRestaurant->loadMinInformation();
		$modelCategorie = new Model_Categorie();
		$request->categorie = $modelCategorie;
		$request->categorie->id = $_GET['id_categorie'];
		$request->categorie->load();
		$request->categorie->getContenu($modelRestaurant->id);
		$request->vue = $this->render("restaurant/viewCategorie.php");
	}
	
	public function editMenu ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$id_menu = $_POST['id_menu'];
			$nom = $_POST['nom'];
			$commentaire = $_POST['commentaire'];
			
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $id_restaurant;
			$modelRestaurant->loadFormat();
			$modelRestaurant->loadFormule();
			$modelRestaurant->loadHoraires();
			
			$modelMenu = new Model_Menu();
			$modelMenu->id = $id_menu;
			$modelMenu->nom = $nom;
			$modelMenu->id_restaurant = $id_restaurant;
			$modelMenu->ordre = 0;
			$modelMenu->commentaire = $commentaire;
			
			foreach ($modelRestaurant->formats as $format) {
				if (isset($_POST['format_'.$format->id]) && $_POST['format_'.$format->id] == 'on') {
					$format->prix = $_POST['format_'.$format->id.'_prix'];
					$format->temps_preparation = $_POST['format_'.$format->id.'_temps'];
					$modelMenu->addFormat($format);
				}
			}
			foreach ($modelRestaurant->formules as $formule) {
				if (isset($_POST['formule_'.$formule->id]) && $_POST['formule_'.$formule->id] == 'on') {
					$modelMenu->addFormule($formule);
				}
			}
			foreach ($modelRestaurant->horaires as $horaire) {
				if (isset($_POST['disponibilite_'.$horaire->id]) && $_POST['disponibilite_'.$horaire->id] == 'on') {
					$modelMenu->addHoraire($horaire);
				}
			}
			$modelMenu->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $modelRestaurant->id));
		} else {
			$request->title = "Administration - restaurant";
			if (!isset($_GET['id_restaurant'])) {
				$this->redirect();
			}
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $_GET['id_restaurant'];
			$request->restaurant = $modelRestaurant->loadMinInformation();
			$request->restaurant->loadFormat();
			$request->restaurant->loadFormule();
			$request->restaurant->loadHoraires();
			if (isset($_GET['id_contenu'])) {
				$modelCarte = new Model_Carte();
				$modelCarte->id = $_GET['id_contenu'];
				$request->carte = $modelCarte->load();
			}
			$request->vue = $this->render("restaurant/editMenu.php");
		}
	}
	
	public function viewMenu ($request) {
		if (!isset($_GET['id_restaurant'])) {
			$this->redirect();
		}
		if (!isset($_GET['id_menu'])) {
			$this->redirect();
		}
		$request->title = "Administration - restaurant";
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id_restaurant'];
		$request->restaurant = $modelRestaurant->loadMinInformation();
		$request->restaurant->loadAllContenu();
		$modelMenu = new Model_Menu();
		$request->menu = $modelMenu;
		$request->menu->id = $_GET['id_menu'];
		$request->menu->load();
		$request->vue = $this->render("restaurant/viewMenu.php");
	}
	
	public function modifyMenu ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$modelMenu = new Model_Menu();
			$modelMenu->id = $_POST['id_menu'];
			$modelMenu->nom = $_POST['nom'];
			$modelMenu->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function deleteMenu ($request) {
		$model = new Model_Menu();
		$id_restaurant = trim($_GET["id_restaurant"]);
		$model->id = trim($_GET["id_menu"]);
		$model->deleted();
		$this->redirect('view', 'restaurant', '', array('id_restaurant' => $id_restaurant));
	}
	
	public function editContenu ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$id_categorie = $_POST['id_categorie'];
			$id_contenu = $_POST['id_contenu'];
			$nom = $_POST['nom'];
			$is_visible = (isset($_POST['is_visible']) && $_POST['is_visible'] == 'on');
			$commentaire = $_POST['commentaire'];
			
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $id_restaurant;
			$modelRestaurant->loadFormat();
			$modelRestaurant->loadAccompagnements();
			$modelRestaurant->loadSupplements();
			$modelRestaurant->loadOptions();
			$modelRestaurant->loadHoraires();
			
			$modelCarte = new Model_Carte();
			$modelCarte->id = $id_contenu;
			$modelCarte->nom = $nom;
			$modelCarte->id_categorie = $id_categorie;
			$modelCarte->ordre = 0;
			$modelCarte->commentaire = $commentaire;
			$modelCarte->is_visible = $is_visible;
			
			foreach ($modelRestaurant->formats as $format) {
				if (isset($_POST['format_'.$format->id]) && $_POST['format_'.$format->id] == 'on') {
					$format->prix = $_POST['format_'.$format->id.'_prix'];
					$format->temps_preparation = $_POST['format_'.$format->id.'_temps'];
					$modelCarte->addFormat($format);
				}
			}
			foreach ($modelRestaurant->categories as $categorie) {
				if (isset($_POST['limite_accompagnement_'.$categorie->id]) && $_POST['limite_accompagnement_'.$categorie->id] > 0) {
					$accompagnement = new Model_Accompagnement();
					$accompagnement->limite = $_POST['limite_accompagnement_'.$categorie->id];
					$accompagnement->id_categorie = $categorie->id;
					foreach ($categorie->contenus as $contenu) {
						if (isset($_POST['accompagnement_'.$categorie->id.'_'.$contenu->id]) && $_POST['accompagnement_'.$categorie->id.'_'.$contenu->id] == 'on') {
							$carte = new Model_Carte();
							$carte->id = $contenu->id;
							$accompagnement->addCarte($carte);
						}
					}
					$modelCarte->addAccompagnement($accompagnement);
				}
			}
			foreach ($modelRestaurant->supplements as $supplement) {
				if (isset($_POST['supplement_'.$supplement->id]) && $_POST['supplement_'.$supplement->id] == 'on') {
					$modelCarte->addSupplement($supplement);
				}
			}
			foreach ($modelRestaurant->options as $option) {
				if (isset($_POST['option_'.$option->id]) && $_POST['option_'.$option->id] == 'on') {
					$modelOption = new Model_Option();
					$modelOption->id = $option->id;
					$modelCarte->addOption($modelOption);
				}
			}
			foreach ($modelRestaurant->horaires as $horaire) {
				if (isset($_POST['disponibilite_'.$horaire->id]) && $_POST['disponibilite_'.$horaire->id] == 'on') {
					$modelCarte->addHoraire($horaire);
				}
			}
			$modelCarte->save();
			if (isset($_FILES) && isset($_FILES['logo']) && $_FILES['logo']['name'] != '') {
				$logo = $_FILES['logo'];
				$ext = pathinfo($logo['name'],  PATHINFO_EXTENSION);
				$uploaddir = WEBSITE_PATH.'res/img/restaurant/';
				$uploadfile = $uploaddir.$modelRestaurant->id.'/contenus/'.$modelCarte->id.'.'.$ext;
				
				if (!move_uploaded_file($logo['tmp_name'], $uploadfile)) {
					writeLog (SERVER_LOG, "erreur upload file $uploadfile", LOG_LEVEL_WARNING);
				}
			}
			$this->redirect('viewCategorie', 'restaurant', '', array ('id_restaurant' => $modelRestaurant->id, 'id_categorie' => $id_categorie));
		} else {
			$request->title = "Administration - restaurant";
			if (!isset($_GET['id_restaurant'])) {
				$this->redirect();
			}
			if (!isset($_GET['id_categorie'])) {
				$this->redirect();
			}
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $_GET['id_restaurant'];
			$request->restaurant = $modelRestaurant->loadMinInformation();
			$request->restaurant->loadFormat();
			$request->restaurant->loadAccompagnements();
			$request->restaurant->loadSupplements();
			$request->restaurant->loadHoraires();
			$request->restaurant->loadOptions();
			$modelCategorie = new Model_Categorie();
			$request->categorie = $modelCategorie;
			$request->categorie->id = $_GET['id_categorie'];
			$request->categorie->load();
			if (isset($_GET['id_contenu'])) {
				$modelCarte = new Model_Carte();
				$modelCarte->id = $_GET['id_contenu'];
				$request->contenu = $modelCarte->load();
				$request->contenu->getLogo($modelRestaurant->id);
			}
			$request->vue = $this->render("restaurant/editContenu.php");
		}
	}
	
	public function removeStock ($request) {
		$id_restaurant = $_GET['id_restaurant'];
		$id_categorie = $_GET['id_categorie'];
		$modelCarte = new Model_Carte();
		$modelCarte->id = $_GET['id_contenu'];
		$modelCarte->removeStock();
		$this->redirect('viewCategorie', 'restaurant', '', array ('id_restaurant' => $id_restaurant, 'id_categorie' => $id_categorie));
	}
	
	public function addStock ($request) {
		$id_restaurant = $_GET['id_restaurant'];
		$id_categorie = $_GET['id_categorie'];
		$modelCarte = new Model_Carte();
		$modelCarte->id = $_GET['id_contenu'];
		$modelCarte->addStock();
		$this->redirect('viewCategorie', 'restaurant', '', array ('id_restaurant' => $id_restaurant, 'id_categorie' => $id_categorie));
	}
	
	public function deleteContenu ($request) {
		$id_restaurant = $_GET['id_restaurant'];
		$id_categorie = $_GET['id_categorie'];
		$modelCarte = new Model_Carte();
		$modelCarte->id = $_GET['id_contenu'];
		$modelCarte->deleted();
		$this->redirect('viewCategorie', 'restaurant', '', array ('id_restaurant' => $id_restaurant, 'id_categorie' => $id_categorie));
	}
	
	public function addContenuToMenu ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$id_menu = $_POST['id_menu'];
			$id_categorie = $_POST['id_categorie'];
			$contenu = $_POST['contenu'];
			$obligatoire = false;
			$supplement = 0;
			$accompagnement = 0;
			$commentaire = "";
			$modelMenu = new Model_Menu();
			$modelMenu->addContenuToMenu($id_categorie, $contenu, $obligatoire, $supplement, $accompagnement, $commentaire);
			$this->redirect('viewMenu', 'restaurant', '', array ('id_restaurant' => $id_restaurant, 'id_menu' => $id_menu));
		}
	}
	
	public function addCategorie ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$nom = $_POST['nom'];
			$parent = $_POST['parent'];
			$modelCategorie = new Model_Categorie();
			$modelCategorie->parent_categorie = $parent;
			$modelCategorie->id_restaurant = $id_restaurant;
			$modelCategorie->nom = $nom;
			$modelCategorie->ordre = 0;
			$modelCategorie->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function modifyCategorie ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$modelCategorie = new Model_Categorie();
			$modelCategorie->id = $_POST['id_categorie'];
			$modelCategorie->nom = $_POST['nom'];
			$modelCategorie->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function deleteCategorie ($request) {
		$id_restaurant = $_GET['id_restaurant'];
		$id_categorie = $_GET['id_categorie'];
		$modelCategorie = new Model_Categorie();
		$modelCategorie->id = $id_categorie;
		$modelCategorie->deleted();
		$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
	}
	
	public function addCategorieToMenu ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$id_menu = $_POST['id_menu'];
			$id_formule = $_POST['id_formule'];
			$nom = $_POST['categorie'];
			$quantite = $_POST['quantite'];
			$categorie = new Model_Categorie();
			$categorie->nom = $nom;
			$categorie->quantite = $quantite;
			$modelMenu = new Model_Menu();
			$modelMenu->id = $id_menu;
			$modelMenu->id_formule = $id_formule;
			$modelMenu->addCategorie($categorie);
			$this->redirect('viewMenu', 'restaurant', '', array ('id_restaurant' => $id_restaurant, 'id_menu' => $id_menu));
		}
	}
	
	public function addFormat ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$nom = $_POST['nom'];
			$modelFormat = new Model_Format();
			$modelFormat->id_restaurant = $id_restaurant;
			$modelFormat->nom = $nom;
			$modelFormat->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function modifyFormat ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$modelFormat = new Model_Format();
			$modelFormat->id = $_POST['id_format'];
			$modelFormat->nom = $_POST['nom'];
			$modelFormat->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function deleteFormat ($request) {
		$id_restaurant = $_GET['id_restaurant'];
		$id_format = $_GET['id_format'];
		$modelFormat = new Model_Format();
		$modelFormat->id = $id_format;
		$modelFormat->deleted();
		$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
	}
	
	public function addFormule ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$nom = $_POST['nom'];
			$modelFormule = new Model_Formule();
			$modelFormule->id_restaurant = $id_restaurant;
			$modelFormule->nom = $nom;
			$modelFormule->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function modifyFormule ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$modelFormule = new Model_Formule();
			$modelFormule->id = $_POST['id_formule'];
			$modelFormule->nom = $_POST['nom'];
			$modelFormule->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function deleteFormule ($request) {
		$id_formule = $_GET['id_formule'];
		$id_restaurant = $_GET['id_restaurant'];
		$modelFormule = new Model_Formule();
		$modelFormule->id = $id_formule;
		$modelFormule->deleted();
		$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
	}
	
	public function addSupplement ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$nom = $_POST['nom'];
			$prix = $_POST['prix'];
			$commentaire = $_POST['commentaire'];
			$modelSupplement = new Model_Supplement();
			$modelSupplement->id_restaurant = $id_restaurant;
			$modelSupplement->nom = $nom;
			$modelSupplement->prix = $prix;
			$modelSupplement->commentaire = $commentaire;
			$modelSupplement->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function modifySupplement ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$modelSupplement = new Model_Supplement();
			$modelSupplement->id = $_POST['id_supplement'];
			$modelSupplement->nom = $_POST['nom'];
			$modelSupplement->prix = $_POST['prix'];
			$modelSupplement->commentaire = $_POST['commentaire'];
			$modelSupplement->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function deleteSupplement ($request) {
		$id_restaurant = $_GET['id_restaurant'];
		$id_supplement = $_GET['id_supplement'];
		$modelSupplement = new Model_Supplement();
		$modelSupplement->id = $id_supplement;
		$modelSupplement->deleted();
		$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
	}
	
	public function addOption ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$nom = $_POST['nom'];
			$modelOption = new Model_Option();
			$modelOption->id_restaurant = $id_restaurant;
			$modelOption->nom = $nom;
			$modelOption->save();
			$this->redirect('view', 'restaurant', '', array ('id_restaurant' => $id_restaurant));
		}
	}
	
	public function viewOption ($request) {
		if (!isset($_GET['id_restaurant'])) {
			$this->redirect();
		}
		if (!isset($_GET['id_option'])) {
			$this->redirect();
		}
		$request->title = "Administration - restaurant";
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id_restaurant'];
		$request->restaurant = $modelRestaurant->loadMinInformation();
		$modelOption = new Model_Option();
		$request->option = $modelOption;
		$request->option->id = $_GET['id_option'];
		$request->option->load();
		$request->vue = $this->render("restaurant/viewOption.php");
	}
	
	public function addOptionValue ($request) {
		if ($request->request_method == "POST") {
			$id_restaurant = $_POST['id_restaurant'];
			$id_option = $_POST['id_option'];
			$nom = $_POST['nom'];
			$modelOption = new Model_Option();
			$modelOption->id = $id_option;
			$modelOptionValue = new Model_Option_Value();
			$modelOptionValue->nom = $nom;
			$modelOption->saveValue($modelOptionValue);
			$this->redirect('viewOption', 'restaurant', '', array ('id_restaurant' => $id_restaurant, 'id_option' => $id_option));
		}
	}
}