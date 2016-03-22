<?php

include_once ROOT_PATH."models/recaptchalib.php";

class Controller_Contact extends Controller_Default_Template {
	
	public function manage ($request) {
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
				case "pro" :
					$this->pro($request);
					break;
				default :
					$this->redirect('404');
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$sujets = array(
			"BUG" => "J'ai trouvé un bug",
			"QUESTION" => "J'ai une question",
			"COMPTE_BLOQUE" => "Mon compte est bloqué"
		);
		if ($request->request_method == "POST") {
			$errorMessage = array();
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"])) {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp != null && $resp->success) {
					
				} else {
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
				
				if (send_mail ("contact@cservichezvous.fr", "demande de contact", $messageContent)) {
					send_mail ($email, "confirmation demande de contact", $messageContent);
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			} else {
				$request->errorMessage = $errorMessage;
				$request->fieldEmail = $email;
				$request->fieldMessage = $message;
			}
		}
		$request->sujets = $sujets;
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "Contact";
		$request->vue = $this->render("contact.php");
	}
	
	public function livreur ($request) {
		if ($request->request_method == "POST") {
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"])) {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp != null && $resp->success) {
					echo "OK";
				} else {
					echo "CAPTCHA incorrect";
				}
			}
			$nom = $_POST['nom'];
			$prenom = $_POST['prenom'];
			$telephone = $_POST['telephone'];
			$transports = array();
			if (isset($_POST['auncun'])) {
				$transports[] = "aucun";
			}
			if (isset($_POST['velo'])) {
				$transports[] = "velo";
			}
			if (isset($_POST['voiture'])) {
				$transports[] = "voiture";
			}
			if (isset($_POST['autre'])) {
				$transports[] = $_POST['transport'];
			}
			$transportContenu = '<ul>';
			foreach ($transports as $transport) {
				$transportContenu .= '<li>'.$transport.'</li>';
			}
			$transportContenu .= '</ul>';
			
			$message = $_POST['message'];
			
			$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_livreur.html');
			
			$messageContent = str_replace("[nom]", $nom, $messageContent);
			$messageContent = str_replace("[prenom]", $prenom, $messageContent);
			$messageContent = str_replace("[telephone]", $telephone, $messageContent);
			$messageContent = str_replace("[transport]", $transportContenu, $messageContent);
			$messageContent = str_replace("[message]", nl2br($message), $messageContent);
			
			if (send_mail ("livreur@cservichezvous.fr", "demande de contact", $messageContent)) {
				$request->mailSuccess = true;
			} else {
				$request->mailSuccess = false;
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "Contact";
		$request->vue = $this->render("contact/livreur.php");
	}
	
	public function restaurant ($request) {
		if ($request->request_method == "POST") {
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"])) {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp != null && $resp->success) {
					echo "OK";
				} else {
					echo "CAPTCHA incorrect";
				}
			}
			$restaurant = $_POST['restaurant'];
			$code_postal = $_POST['code_postal'];
			$ville = $_POST['ville'];
			$nom = $_POST['nom'];
			$prenom = $_POST['prenom'];
			$telephone = $_POST['telephone'];
			$fonction = $_POST['fonction'];
			$message = $_POST['message'];
			
			$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_restaurant.html');
			
			$messageContent = str_replace("[restaurant]", $restaurant, $messageContent);
			$messageContent = str_replace("[ville]", $ville, $messageContent);
			$messageContent = str_replace("[code_postal]", $code_postal, $messageContent);
			$messageContent = str_replace("[nom]", $nom, $messageContent);
			$messageContent = str_replace("[prenom]", $prenom, $messageContent);
			$messageContent = str_replace("[telephone]", $telephone, $messageContent);
			$messageContent = str_replace("[fonction]", $fonction, $messageContent);
			$messageContent = str_replace("[message]", nl2br($message), $messageContent);
			
			if (send_mail ("restaurant@cservichezvous.fr", "demande de contact", $messageContent)) {
				$request->mailSuccess = true;
			} else {
				$request->mailSuccess = false;
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "Contact";
		$request->vue = $this->render("contact/restaurant.php");
	}
	
	public function pro ($request) {
		if ($request->request_method == "POST") {
			$reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
			if(isset($_POST["g-recaptcha-response"])) {
				$resp = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]	
				);
				if ($resp != null && $resp->success) {
					echo "OK";
				} else {
					echo "CAPTCHA incorrect";
				}
			}
			$categorie = $_POST['categorie'];
			if ($categorie == "entreprise") {
				$restaurant = $_POST['entreprise'];
				$code_postal = $_POST['code_postal'];
				$ville = $_POST['ville'];
				
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				$telephone = $_POST['telephone'];
				$fonction = $_POST['fonction'];
				$message = $_POST['message'];
				
				$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_pro_entreprise.html');
				
				$messageContent = str_replace("[restaurant]", $restaurant, $messageContent);
				$messageContent = str_replace("[ville]", $ville, $messageContent);
				$messageContent = str_replace("[code_postal]", $code_postal, $messageContent);
				$messageContent = str_replace("[nom]", $nom, $messageContent);
				$messageContent = str_replace("[prenom]", $prenom, $messageContent);
				$messageContent = str_replace("[telephone]", $telephone, $messageContent);
				$messageContent = str_replace("[fonction]", $fonction, $messageContent);
				$messageContent = str_replace("[message]", nl2br($message), $messageContent);
				
				if (send_mail ("restaurant@cservichezvous.fr", "demande de contact d'une entreprise", $messageContent)) {
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			} else {
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				$telephone = $_POST['telephone'];
				$fonction = $_POST['fonction'];
				$message = $_POST['message'];
				
				$messageContent =  file_get_contents (ROOT_PATH.'mails/contact_pro_particulier.html');
				
				$messageContent = str_replace("[restaurant]", $restaurant, $messageContent);
				$messageContent = str_replace("[ville]", $ville, $messageContent);
				$messageContent = str_replace("[code_postal]", $code_postal, $messageContent);
				$messageContent = str_replace("[nom]", $nom, $messageContent);
				$messageContent = str_replace("[prenom]", $prenom, $messageContent);
				$messageContent = str_replace("[telephone]", $telephone, $messageContent);
				$messageContent = str_replace("[fonction]", $fonction, $messageContent);
				$messageContent = str_replace("[message]", nl2br($message), $messageContent);
				
				if (send_mail ("restaurant@cservichezvous.fr", "demande de contact d'un particulier", $messageContent)) {
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/contact.js", "https://www.google.com/recaptcha/api.js");
		$request->title = "Contact";
		$request->vue = $this->render("contact/pro.php");
	}
	
	public function send ($request) {
		if (isset($_POST["email"]) && isset($_POST["message"])) {
			
		}
	}
}