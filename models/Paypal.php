<?php

class Paypal {

	private $environnement;
	private $version;
	private $user;
	private $password;
	private $signature;
	
	private $cancelUrl;
	private $returnUrl;
	
	private $items;
	
	private $adresse_name;
	private $adresse_rue;
	private $adresse_ville;
	private $adresse_pays;
	private $adresse_cp;
	private $adresse_telephone;
	
	private $amount;
	private $taxAmount;
	private $shippinCost;
	private $handalingCost;
	private $shippingDiscount;
	private $insuranceCost;
	private $currency;
	
	private $img;
	private $locale;
	
	private $token;
	private $payer;
	
	private $errorMessage;
	
	public function __construct() {
		$this->environnement = PAYPAL_ENV;
		$this->version = 109.0;
		$this->user = PAYPAL_USER;
		$this->password = PAYPAL_PASSWORD;
		$this->signature = PAYPAL_SIGNATURE;
		
		$this->items = array();
		
		$this->taxAmount = 0;
		$this->shippinCost = 0;
		$this->handalingCost = 0;
		$this->shippingDiscount = 0;
		$this->insuranceCost = 0;
		$this->currency = "EUR";
		
		$this->local = "FR";
		$this->img = WEBSITE_URL."res/img/logo_mail.png";
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}
	
	public function addItem ($item) {
		$this->items[] = $item;
	}
	
	private function getPaypalUrl () {
		if ($this->environnement == "PROD") {
			$api_paypal = "https://api-3t.paypal.com/nvp?";
		} else {
			$api_paypal = "https://api-3t.sandbox.paypal.com/nvp?"; 
		}
		return $api_paypal;
	}

	private function recup_param_paypal($resultat_paypal) {
		// On récupère la liste de paramètres, séparés par un 'et' commercial (&)
		$liste_parametres = explode("&",$resultat_paypal); // Crée un tableau de paramètres
		// Pour chacun de ces paramètres, on exécute le bloc suivant, en intégrant le paramètre dans la variable $param_paypal
		foreach($liste_parametres as $param_paypal)
		{
			// On récupère le nom du paramètre et sa valeur dans 2 variables différentes. Elles sont séparées par le signe égal (=)
			list($nom, $valeur) = explode("=", $param_paypal);
			// On crée un tableau contenant le nom du paramètre comme identifiant et la valeur comme valeur.
			$liste_param_paypal[$nom]=urldecode($valeur); // Décode toutes les séquences %##  et les remplace par leur valeur.
		}
		return $liste_param_paypal; // Retourne l'array
	}
	
	public function setExpressCheckout () {
		$api_paypal = $this->getPaypalUrl();
		
		$indice = 0;
		$postData = "METHOD=SetExpressCheckout".
			'&VERSION='.$this->version.
			'&USER='.$this->user.
			'&PWD='.$this->password.
			'&SIGNATURE='.$this->signature.
			'&RETURNURL='.urlencode($this->returnUrl).
			'&CANCELURL='.urlencode($this->cancelUrl).
			'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
		foreach ($this->items as $item) {
			$postData .= '&L_PAYMENTREQUEST_0_NAME'.$indice.'='.urlencode($item->name).
                '&L_PAYMENTREQUEST_0_NUMBER'.$indice.'='.urlencode($item->number).
                '&L_PAYMENTREQUEST_0_DESC'.$indice.'='.urlencode($item->desc).
                '&L_PAYMENTREQUEST_0_AMT'.$indice.'='.urlencode($item->price).
                '&L_PAYMENTREQUEST_0_QTY'.$indice.'='. urlencode($item->quantity);
			$indice++;
		}
		
		$totalAmount = $this->amount + $this->taxAmount + $this->shippinCost + $this->handalingCost + $this->shippingDiscount + $this->insuranceCost;
		
		//Override the buyer's shipping address stored on PayPal, The buyer cannot edit the overridden address.
		$postData .= '&ADDROVERRIDE=1'.
		'&PAYMENTREQUEST_0_SHIPTONAME='.$this->adresse_name.
		'&PAYMENTREQUEST_0_SHIPTOSTREET='.$this->adresse_rue.
		'&PAYMENTREQUEST_0_SHIPTOCITY='.$this->adresse_ville.
		'&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE='.$this->adresse_pays.
		'&PAYMENTREQUEST_0_SHIPTOZIP='.$this->adresse_cp.
		'&PAYMENTREQUEST_0_SHIPTOPHONENUM='.$this->adresse_telephone.
		
		'&NOSHIPPING=0'. //set 1 to hide buyer's shipping address, in-case products that do not require shipping
                
		'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($this->amount).
		'&PAYMENTREQUEST_0_TAXAMT='.urlencode($this->taxAmount).
		'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($this->shippinCost).
		'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($this->handalingCost).
		'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($this->shippingDiscount).
		'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($this->insuranceCost).
		'&PAYMENTREQUEST_0_AMT='.urlencode($totalAmount).
		'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->currency).
		'&LOCALECODE='.$this->local. //PayPal pages to match the language on your website.
		'&LOGOIMG='.urlencode(WEBSITE_URL."res/img/logo.png"). //site logo
		'&CARTBORDERCOLOR=FFFFFF'. //border color of cart
		'&ALLOWNOTE=1';
		
		// Affiche la chaîne pour vérifier que la chaîne est bien formatée :
		//echo $requete;

		// Initialise notre session cURL. On lui donne la requête à exécuter
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_paypal); // Définit une option de transmission cURL
		// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné. 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //la fonction retournera le résultat en cas de succès
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Définit une option de transmission cURL	
		$resultat_paypal = curl_exec($ch); // Obtient la réponse du server
		// On ferme notre session cURL.
		curl_close($ch);
		
		//var_dump($resultat_paypal); die();

		// S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		if (!$resultat_paypal) {
			$this->errorMessage = curl_error($ch);
			return false;
		} else // S'il s'est exécuté correctement, on effectue les traitements...
		{
			$liste_param_paypal = $this->recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array
			
			// On affiche le tout pour voir que tout est OK.
			/*echo "<pre>";
			print_r($liste_param_paypal);
			echo "</pre>";*/

			// Si la requête a été traitée avec succès
			if ($liste_param_paypal['ACK'] == 'Success') {
				// Redirige le visiteur sur le site de PayPal
				/*header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']);*/
				header("Location: https://www.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']);
				exit();
			} else // En cas d'échec, affiche la première erreur trouvée.
			{
				var_dump($liste_param_paypal); die();
				$this->errorMessage = "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
				return false;
			}
		}
	}
	
	public function doExpressCheckout () {
		$api_paypal = $this->getPaypalUrl();
		
		$indice = 0;
		$postData = "METHOD=DoExpressCheckoutPayment".
			'&VERSION='.$this->version.
			'&USER='.$this->user.
			'&PWD='.$this->password.
			'&SIGNATURE='.$this->signature.
			'&TOKEN='.$this->token.
			'&PAYERID='.$this->payer.
			'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
		foreach ($this->items as $item) {
			$postData .= '&L_PAYMENTREQUEST_0_NAME'.$indice.'='.urlencode($item->name).
                '&L_PAYMENTREQUEST_0_NUMBER'.$indice.'='.urlencode($item->number).
                '&L_PAYMENTREQUEST_0_DESC'.$indice.'='.urlencode($item->desc).
                '&L_PAYMENTREQUEST_0_AMT'.$indice.'='.urlencode($item->price).
                '&L_PAYMENTREQUEST_0_QTY'.$indice.'='. urlencode($item->quantity);
			$indice++;
		}
		
		$totalAmount = $this->amount + $this->taxAmount + $this->shippinCost + $this->handalingCost + $this->shippingDiscount + $this->insuranceCost;
                
		$postData .= '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($this->amount).
		'&PAYMENTREQUEST_0_TAXAMT='.urlencode($this->taxAmount).
		'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($this->shippinCost).
		'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($this->handalingCost).
		'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($this->shippingDiscount).
		'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($this->insuranceCost).
		'&PAYMENTREQUEST_0_AMT='.urlencode($totalAmount).
		'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->currency);
		
		// Affiche la chaîne pour vérifier que la chaîne est bien formatée :
		//echo $requete;

		// Initialise notre session cURL. On lui donne la requête à exécuter
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_paypal); // Définit une option de transmission cURL
		// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné. 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //la fonction retournera le résultat en cas de succès
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Définit une option de transmission cURL	
		$resultat_paypal = curl_exec($ch); // Obtient la réponse du server
		// On ferme notre session cURL.
		curl_close($ch);
		
		//var_dump($resultat_paypal); die();

		// S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		if (!$resultat_paypal) {
			$this->errorMessage = curl_error($ch);
			return false;
		} else // S'il s'est exécuté correctement, on effectue les traitements...
		{
			$liste_param_paypal = $this->recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array
			
			// On affiche le tout pour voir que tout est OK.
			/*echo "<pre>";
			print_r($liste_param_paypal);
			echo "</pre>";*/

			// Si la requête a été traitée avec succès
			if ($liste_param_paypal['ACK'] == 'Success') {
				return true;
			} else // En cas d'échec, affiche la première erreur trouvée.
			{
				$this->errorMessage = "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
				return false;
			}
		}
	}

}