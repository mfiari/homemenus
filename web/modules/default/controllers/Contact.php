<?php

include_once ROOT_PATH."models/recaptchalib.php";

class Controller_Contact extends Controller_Default_Template {
	
	public function manage ($request) {
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "livreur" :
					$this->livreur($request);
					break;
				case "restaurant" :
					$this->restaurant($request);
					break;
				case "entreprise" :
					$this->entreprise($request);
					break;
				case "evenement" :
					$this->evenement($request);
					break;
				case "avis" :
					$this->avis($request);
					break;
				default :
					$this->redirect('404');
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('contact/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('contact/'.$vue.'.php');
	}
	
	public function index ($request) {
		$sujets = array(
			"QUESTION" => "J'ai une question",
			"BUG" => "J'ai trouvé un bug",
			"COMPTE_BLOQUE" => "Mon compte est bloqué"
		);
		if ($request->request_method == "POST") {
			$errorMessage = array();
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"] != '') {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp == null || !$resp->success) {
					$errorMessage["ERROR_CAPTCHA"] = "Une erreur est survenu, veuillez réessayer";
				}
			} else {
				$errorMessage["NO_CAPTCHA"] = "Veuillez valider le reCAPTCHA afin de prouvez que vous n'êtes pas un robot";
			}
			if (!isset($_POST["sujet"]) || trim($_POST["sujet"]) == "") {
				$errorMessage["EMPTY_SUJET"] = "Veuillez renseigner le sujet";
			} else {
				$sujet = $_POST["sujet"];
			}
			if (!isset($_POST["email"]) || trim($_POST["email"]) == "") {
				$errorMessage["EMPTY_EMAIL"] = "Veuillez renseigner votre email";
			} else {
				$email = $_POST["email"];
			}
			if (!isset($_POST["message"]) || trim($_POST["message"]) == "") {
				$errorMessage["EMPTY_MESSAGE"] = "Veuillez renseigner le message";
			} else {
				$message = $_POST["message"];
			}
			if (count($errorMessage) == 0) {
				
				$messageContent =  file_get_contents (ROOT_PATH.'mails/contact.html');
				
				$messageContent = str_replace("[SUJET]", $sujets[$sujet], $messageContent);
				$messageContent = str_replace("[EMAIL]", $email, $messageContent);
				$messageContent = str_replace("[MESSAGE]", nl2br($message), $messageContent);
				
				if (send_mail (MAIL_CONTACT, "demande de contact", $messageContent)) {
					
					$messageContent =  file_get_contents (ROOT_PATH.'mails/confirmation_contact.html');
					$messageContent = str_replace("[MESSAGE]", nl2br($message), $messageContent);
					
					send_mail ($email, "confirmation demande de contact", $messageContent);
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			} else {
				$request->sujet = $sujet;
				$request->errorMessage = $errorMessage;
				$request->fieldEmail = $email;
				$request->fieldMessage = $message;
			}
		}
		$request->sujets = $sujets;
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact/contact.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "HoMe Menus - Contact";
		$request->vue = $this->render("contact");
	}
	
	public function livreur ($request) {
		if ($request->request_method == "POST") {
			$errorMessage = array();
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"] != '') {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp == null || !$resp->success) {
					$errorMessage["ERROR_CAPTCHA"] = "Une erreur est survenu, veuillez réessayer";
				}
			} else {
				$errorMessage["NO_CAPTCHA"] = "Veuillez valider le reCAPTCHA afin de prouvez que vous n'êtes pas un robot";
			}
			if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
				$errorMessage["EMPTY_NOM"] = "Veuillez renseigner votre nom";
			} else {
				$nom = $_POST["nom"];
			}
			if (!isset($_POST["prenom"]) || trim($_POST["prenom"]) == "") {
				$errorMessage["EMPTY_PRENOM"] = "Veuillez renseigner votre prénom";
			} else {
				$prenom = $_POST["prenom"];
			}
			if (!isset($_POST["telephone"]) || trim($_POST["telephone"]) == "") {
				$errorMessage["EMPTY_TEL"] = "Veuillez renseigner votre telephone";
			} else {
				$telephone = $_POST["telephone"];
			}
			if (!isset($_POST["email"]) || trim($_POST["email"]) == "") {
				$errorMessage["EMPTY_EMAIL"] = "Veuillez renseigner votre email";
			} else {
				$email = $_POST["email"];
			}
			$transports = array();
			if (isset($_POST['velo'])) {
				$transports[] = "velo";
			}
			if (isset($_POST['voiture'])) {
				$transports[] = "voiture";
			}
			if (isset($_POST['scooter'])) {
				$transports[] = "scooter";
			}
			if (isset($_POST['autre'])) {
				$transports[] = $_POST['transport'];
			}
			$transportContenu = '<ul>';
			foreach ($transports as $transport) {
				$transportContenu .= '<li>'.$transport.'</li>';
			}
			$transportContenu .= '</ul>';
			
			if (!isset($_POST["message"]) || trim($_POST["message"]) == "") {
				$message = '';
			} else {
				$message = $_POST['message'];
			}
			
			if (count($errorMessage) == 0) {
			
				$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_livreur.html');
				
				$messageContent = str_replace("[NOM]", $nom, $messageContent);
				$messageContent = str_replace("[PRENOM]", $prenom, $messageContent);
				$messageContent = str_replace("[EMAIL]", $email, $messageContent);
				$messageContent = str_replace("[TELEPHONE]", $telephone, $messageContent);
				$messageContent = str_replace("[TRANSPORT]", $transportContenu, $messageContent);
				$messageContent = str_replace("[MESSAGE]", nl2br($message), $messageContent);
				
				if (send_mail (MAIL_LIVREUR, "demande de contact d'un livreur", $messageContent)) {
					
					$messageContent =  file_get_contents (ROOT_PATH.'mails/confirmation_contact_livreur.html');
					
					send_mail ($email, "confirmation de demande de contact", $messageContent);
					
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			} else {
				$request->errorMessage = $errorMessage;
				$request->fieldNom = $nom;
				$request->fieldPrenom = $prenom;
				$request->fieldTelephone = $telephone;
				$request->fieldEmail = $email;
				$request->fieldMessage = $message;
				$request->transports = $transports;
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact/livreur.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "HoMe Menus - Contact livreur";
		$request->vue = $this->render("livreur");
	}
	
	public function restaurant ($request) {
		if ($request->request_method == "POST") {
			$errorMessage = array();
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"] != '') {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp == null || !$resp->success) {
					$errorMessage["ERROR_CAPTCHA"] = "Une erreur est survenu, veuillez réessayer";
				}
			} else {
				$errorMessage["NO_CAPTCHA"] = "Veuillez valider le reCAPTCHA afin de prouvez que vous n'êtes pas un robot";
			}
			if (!isset($_POST["restaurant"]) || trim($_POST["restaurant"]) == "") {
				$errorMessage["EMPTY_RESTAURANT"] = "Veuillez renseigner le nom de votre restaurant";
			} else {
				$restaurant = $_POST["restaurant"];
			}
			if (!isset($_POST["code_postal"]) || trim($_POST["code_postal"]) == "") {
				$errorMessage["EMPTY_CP"] = "Veuillez renseigner le code postal de votre restaurant";
			} else {
				$code_postal = $_POST["code_postal"];
			}
			if (!isset($_POST["ville"]) || trim($_POST["ville"]) == "") {
				$errorMessage["EMPTY_VILLE"] = "Veuillez renseigner la ville de votre restaurant";
			} else {
				$ville = $_POST["ville"];
			}
			if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
				$errorMessage["EMPTY_NOM"] = "Veuillez renseigner votre nom";
			} else {
				$nom = $_POST["nom"];
			}
			if (!isset($_POST["prenom"]) || trim($_POST["prenom"]) == "") {
				$errorMessage["EMPTY_PRENOM"] = "Veuillez renseigner votre prénom";
			} else {
				$prenom = $_POST["prenom"];
			}
			if (!isset($_POST["telephone"]) || trim($_POST["telephone"]) == "") {
				$errorMessage["EMPTY_TEL"] = "Veuillez renseigner votre telephone";
			} else {
				$telephone = $_POST["telephone"];
			}
			if (!isset($_POST["fonction"]) || trim($_POST["fonction"]) == "") {
				$errorMessage["EMPTY_FONCTION"] = "Veuillez renseigner votre fonction";
			} else {
				$fonction = $_POST["fonction"];
			}
			if (!isset($_POST["message"]) || trim($_POST["message"]) == "") {
				$message = '';
			} else {
				$message = $_POST['message'];
			}
			
			if (count($errorMessage) == 0) {
			
				$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_restaurant.html');
				
				$messageContent = str_replace("[restaurant]", $restaurant, $messageContent);
				$messageContent = str_replace("[ville]", $ville, $messageContent);
				$messageContent = str_replace("[code_postal]", $code_postal, $messageContent);
				$messageContent = str_replace("[nom]", $nom, $messageContent);
				$messageContent = str_replace("[prenom]", $prenom, $messageContent);
				$messageContent = str_replace("[telephone]", $telephone, $messageContent);
				$messageContent = str_replace("[fonction]", $fonction, $messageContent);
				$messageContent = str_replace("[message]", nl2br($message), $messageContent);
				
				if (send_mail (MAIL_RESTAURANT, "demande de contact", $messageContent)) {
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			} else {
				$request->errorMessage = $errorMessage;
				$request->fieldRestaurant = $restaurant;
				$request->fieldCP = $code_postal;
				$request->fieldVille = $ville;
				$request->fieldNom = $nom;
				$request->fieldPrenom = $prenom;
				$request->fieldTelephone = $telephone;
				$request->fieldFonction = $fonction;
				$request->fieldMessage = $message;
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact/restaurant.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "HoMe Menus - Devenir restaurant partenaire";
		$request->vue = $this->render("restaurant");
	}
	
	public function entreprise ($request) {
		if ($request->request_method == "POST") {
			$errorMessage = array();
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"] != '') {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp == null || !$resp->success) {
					$errorMessage["ERROR_CAPTCHA"] = "Une erreur est survenu, veuillez réessayer";
				}
			} else {
				$errorMessage["NO_CAPTCHA"] = "Veuillez valider le reCAPTCHA afin de prouvez que vous n'êtes pas un robot";
			}
			if (!isset($_POST["entreprise"]) || trim($_POST["entreprise"]) == "") {
				$errorMessage["EMPTY_ENTREPRISE"] = "Veuillez renseigner le nom de votre entreprise";
			} else {
				$entreprise = $_POST["entreprise"];
			}
			if (!isset($_POST["code_postal"]) || trim($_POST["code_postal"]) == "") {
				$errorMessage["EMPTY_CP"] = "Veuillez renseigner le code postal de votre entreprise";
			} else {
				$code_postal = $_POST["code_postal"];
			}
			if (!isset($_POST["ville"]) || trim($_POST["ville"]) == "") {
				$errorMessage["EMPTY_VILLE"] = "Veuillez renseigner la ville de votre entreprise";
			} else {
				$ville = $_POST["ville"];
			}
			if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
				$errorMessage["EMPTY_NOM"] = "Veuillez renseigner votre nom";
			} else {
				$nom = $_POST["nom"];
			}
			if (!isset($_POST["prenom"]) || trim($_POST["prenom"]) == "") {
				$errorMessage["EMPTY_PRENOM"] = "Veuillez renseigner votre prénom";
			} else {
				$prenom = $_POST["prenom"];
			}
			if (!isset($_POST["telephone"]) || trim($_POST["telephone"]) == "") {
				$errorMessage["EMPTY_TEL"] = "Veuillez renseigner votre telephone";
			} else {
				$telephone = $_POST["telephone"];
			}
			if (!isset($_POST["email"]) || trim($_POST["email"]) == "") {
				$errorMessage["EMPTY_EMAIL"] = "Veuillez renseigner votre email";
			} else {
				$email = $_POST["email"];
			}
			if (!isset($_POST["fonction"]) || trim($_POST["fonction"]) == "") {
				$errorMessage["EMPTY_FONCTION"] = "Veuillez renseigner votre fonction";
			} else {
				$fonction = $_POST["fonction"];
			}
			if (!isset($_POST["message"]) || trim($_POST["message"]) == "") {
				$message = '';
			} else {
				$message = $_POST['message'];
			}
			
			if (count($errorMessage) == 0) {
			
				$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_pro_entreprise.html');
				
				$messageContent = str_replace("[entreprise]", $entreprise, $messageContent);
				$messageContent = str_replace("[ville]", $ville, $messageContent);
				$messageContent = str_replace("[code_postal]", $code_postal, $messageContent);
				$messageContent = str_replace("[nom]", $nom, $messageContent);
				$messageContent = str_replace("[prenom]", $prenom, $messageContent);
				$messageContent = str_replace("[telephone]", $telephone, $messageContent);
				$messageContent = str_replace("[email]", $email, $messageContent);
				$messageContent = str_replace("[fonction]", $fonction, $messageContent);
				$messageContent = str_replace("[message]", nl2br($message), $messageContent);
				
				if (send_mail (MAIL_PRO, "demande de contact d'une entreprise", $messageContent)) {
					
					$messageContent =  file_get_contents (ROOT_PATH.'mails/confirmation_contact_livreur.html');
				
					send_mail ($email, "confirmation de demande de contact", $messageContent);
					
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			} else {
				$request->errorMessage = $errorMessage;
				$request->fieldEntreprise = $entreprise;
				$request->fieldCP = $code_postal;
				$request->fieldVille = $ville;
				$request->fieldNom = $nom;
				$request->fieldPrenom = $prenom;
				$request->fieldTelephone = $telephone;
				$request->fieldEmail = $email;
				$request->fieldFonction = $fonction;
				$request->fieldMessage = $message;
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact/entreprise.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "HoMe Menus - Pour les entreprises";
		$request->vue = $this->render("entreprise");
	}
	
	public function evenement ($request) {
		if ($request->request_method == "POST") {
			$errorMessage = array();
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"] != '') {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp == null || !$resp->success) {
					$errorMessage["ERROR_CAPTCHA"] = "Une erreur est survenu, veuillez réessayer";
				}
			} else {
				$errorMessage["NO_CAPTCHA"] = "Veuillez valider le reCAPTCHA afin de prouvez que vous n'êtes pas un robot";
			}
			$categorie = $_POST['categorie'];
			if ($categorie == "entreprise") {
				if (!isset($_POST["entreprise"]) || trim($_POST["entreprise"]) == "") {
					$errorMessage["EMPTY_RESTAURANT"] = "Veuillez renseigner le nom de votre entreprise";
				} else {
					$entreprise = $_POST["entreprise"];
				}
				if (!isset($_POST["code_postal"]) || trim($_POST["code_postal"]) == "") {
					$errorMessage["EMPTY_CP"] = "Veuillez renseigner le code postal de votre entreprise";
				} else {
					$code_postal = $_POST["code_postal"];
				}
				if (!isset($_POST["ville"]) || trim($_POST["ville"]) == "") {
					$errorMessage["EMPTY_VILLE"] = "Veuillez renseigner la ville de votre entreprise";
				} else {
					$ville = $_POST["ville"];
				}
				if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
					$errorMessage["EMPTY_NOM"] = "Veuillez renseigner votre nom";
				} else {
					$nom = $_POST["nom"];
				}
				if (!isset($_POST["prenom"]) || trim($_POST["prenom"]) == "") {
					$errorMessage["EMPTY_PRENOM"] = "Veuillez renseigner votre prénom";
				} else {
					$prenom = $_POST["prenom"];
				}
				if (!isset($_POST["telephone"]) || trim($_POST["telephone"]) == "") {
					$errorMessage["EMPTY_TEL"] = "Veuillez renseigner votre telephone";
				} else {
					$telephone = $_POST["telephone"];
				}
				if (!isset($_POST["email"]) || trim($_POST["email"]) == "") {
					$errorMessage["EMPTY_EMAIL"] = "Veuillez renseigner votre email";
				} else {
					$email = $_POST["email"];
				}
				if (!isset($_POST["fonction"]) || trim($_POST["fonction"]) == "") {
					$errorMessage["EMPTY_FONCTION"] = "Veuillez renseigner votre fonction";
				} else {
					$fonction = $_POST["fonction"];
				}
				if (!isset($_POST["message"]) || trim($_POST["message"]) == "") {
					$message = '';
				} else {
					$message = $_POST['message'];
				}
				
				if (count($errorMessage) == 0) {
				
					$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_pro_entreprise.html');
					
					$messageContent = str_replace("[entreprise]", $entreprise, $messageContent);
					$messageContent = str_replace("[ville]", $ville, $messageContent);
					$messageContent = str_replace("[code_postal]", $code_postal, $messageContent);
					$messageContent = str_replace("[nom]", $nom, $messageContent);
					$messageContent = str_replace("[prenom]", $prenom, $messageContent);
					$messageContent = str_replace("[telephone]", $telephone, $messageContent);
					$messageContent = str_replace("[email]", $email, $messageContent);
					$messageContent = str_replace("[fonction]", $fonction, $messageContent);
					$messageContent = str_replace("[message]", nl2br($message), $messageContent);
					
					if (send_mail (MAIL_PRO, "demande de contact d'une entreprise", $messageContent)) {
						
						$messageContent =  file_get_contents (ROOT_PATH.'mails/confirmation_contact_livreur.html');
					
						send_mail ($email, "confirmation de demande de contact", $messageContent);
						
						$request->mailSuccess = true;
					} else {
						$request->mailSuccess = false;
					}
				} else {
					$request->errorMessage = $errorMessage;
					$request->fieldEntreprise = $entreprise;
					$request->fieldCP = $code_postal;
					$request->fieldVille = $ville;
					$request->fieldNom = $nom;
					$request->fieldPrenom = $prenom;
					$request->fieldTelephone = $telephone;
					$request->fieldEmail = $email;
					$request->fieldFonction = $fonction;
					$request->fieldMessage = $message;
				}
			} else {
				if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
					$errorMessage["EMPTY_NOM"] = "Veuillez renseigner votre nom";
				} else {
					$nom = $_POST["nom"];
				}
				if (!isset($_POST["prenom"]) || trim($_POST["prenom"]) == "") {
					$errorMessage["EMPTY_PRENOM"] = "Veuillez renseigner votre prénom";
				} else {
					$prenom = $_POST["prenom"];
				}
				if (!isset($_POST["telephone"]) || trim($_POST["telephone"]) == "") {
					$errorMessage["EMPTY_TEL"] = "Veuillez renseigner votre telephone";
				} else {
					$telephone = $_POST["telephone"];
				}
				if (!isset($_POST["email"]) || trim($_POST["email"]) == "") {
					$errorMessage["EMPTY_EMAIL"] = "Veuillez renseigner votre email";
				} else {
					$email = $_POST["email"];
				}
				if (!isset($_POST["message"]) || trim($_POST["message"]) == "") {
					$message = '';
				} else {
					$message = $_POST['message'];
				}
				
				if (count($errorMessage) == 0) {
				
					$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_pro_particulier.html');
					
					$messageContent = str_replace("[nom]", $nom, $messageContent);
					$messageContent = str_replace("[prenom]", $prenom, $messageContent);
					$messageContent = str_replace("[telephone]", $telephone, $messageContent);
					$messageContent = str_replace("[email]", $email, $messageContent);
					$messageContent = str_replace("[message]", nl2br($message), $messageContent);
					
					if (send_mail (MAIL_PRO, "demande de contact d'un particulier", $messageContent)) {
						
						$messageContent =  file_get_contents (ROOT_PATH.'mails/confirmation_contact_livreur.html');
					
						send_mail ($email, "confirmation de demande de contact", $messageContent);
						
						$request->mailSuccess = true;
					} else {
						$request->mailSuccess = false;
					}
				} else {
					$request->errorMessage = $errorMessage;
					$request->fieldNom = $nom;
					$request->fieldPrenom = $prenom;
					$request->fieldTelephone = $telephone;
					$request->fieldEmail = $email;
					$request->fieldMessage = $message;
				}
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact/evenement.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "HoMe Menus - Commande speciale";
		$request->vue = $this->render("evenements");
	}
	
	public function avis ($request) {
		if ($request->request_method == "POST") {
			$errorMessage = array();
			if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
				$errorMessage["EMPTY_RESTAURANT"] = "Veuillez renseigner le nom de votre restaurant";
			} else {
				$restaurant = $_POST["nom"];
			}
			if (!isset($_POST["ville"]) || trim($_POST["ville"]) == "") {
				$errorMessage["EMPTY_VILLE"] = "Veuillez renseigner la ville de votre restaurant";
			} else {
				$ville = $_POST["ville"];
			}
			if (!isset($_POST["ville_user"]) || trim($_POST["ville_user"]) == "") {
				$errorMessage["EMPTY_VILLE_USER"] = "Veuillez renseigner le code postal de votre restaurant";
			} else {
				$ville_user = $_POST["ville_user"];
			}
			if (!isset($_POST["email"]) || trim($_POST["email"]) == "") {
				$errorMessage["EMPTY_EMAIL"] = "Veuillez renseigner votre adresse email.";
			} else {
				$email = $_POST["email"];
			}
			if (!isset($_POST["from"]) || trim($_POST["from"]) == "") {
				$from = "recherche";
			} else {
				$from = $_POST["from"];
			}
			if (count($errorMessage) == 0) {
			
				$messageContent =  file_get_contents (ROOT_PATH.'mails/avis.html');
				
				$messageContent = str_replace("[RESTAURANT]", $restaurant, $messageContent);
				$messageContent = str_replace("[VILLE]", $ville, $messageContent);
				$messageContent = str_replace("[VILLEUSER]", $ville_user, $messageContent);
				$messageContent = str_replace("[EMAIL]", $email, $messageContent);
				if (send_mail (MAIL_CONTACT, "Avis", $messageContent)) {
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			} else {
				
			}
			if ($from == "partenaires") {
				$this->redirect('restaurants_partenaire', 'index', '', array('avis_send' => 'success'));
			} else if ($from == "recherche") {
				$this->redirect('recherche', 'restaurant', '', array('avis_send' => 'success'));
			}
		}
	}
}