<?php

class Nexmo {
	
	private $url;
	private $api_key;
	private $api_secret;
	private $from;

	private $message;
	private $numeros;
	
	public function __construct() {
		$this->url = 'https://rest.nexmo.com/sms/json';
		$this->api_key = NEXMO_API_KEY;
		$this->api_secret = NEXMO_API_SECRET;
		$this->from = urlencode("HoMe Menus");
		
		$this->numeros = array();
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
	
	public function addNumero ($numero) {
		$numero = substr($numero, 1);
		$this->numeros[] = '33'.$numero;
	}
	
	public function sendMessage () {
		
		if (!SEND_SMS) {
			return false;
		}
		
		$modelSMS = new Model_SMS();
		
		foreach ($this->numeros as $numero) {
			
			$requete = $this->url.'?api_key='.$this->api_key.'&api_secret='.$this->api_secret.'&to='.$numero.'&from='.$this->from.'&text='.urlencode($this->message);

			// Initialise notre session cURL. On lui donne la requête à exécuter
			$ch = curl_init($requete);
			
			// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné. 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			// Retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec() au lieu de l'afficher directement. 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			// On lance l'exécution de la requête URL et on récupère le résultat dans une variable
			$resultat = curl_exec($ch);

			// On ferme notre session cURL.
			curl_close($ch);
			
			$modelSMS->telephone = $numero;
			$modelSMS->message = $this->message;
			$modelSMS->is_send = true;
			
			$modelSMS->save();
		}
		
		return true;
	}

}