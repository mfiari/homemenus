<?php

class Clickatell {
	
	private $x_version;
	private $content_type;
	private $accept;
	private $token;
	private $url;

	private $message;
	private $numeros;
	
	public function __construct() {
		$this->url = 'https://api.clickatell.com/rest/message';
		$this->x_version = 1;
		$this->content_type = 'application/json';
		$this->accept = 'application/json';
		$this->token = CLICKATELL_REST_API_KEY;
		
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
		//open connection
		$ch = curl_init();
		
		$to = '[';
		$separateur = '';
		
		foreach ($this->numeros as $numero) {
			$to .= $separateur.'"'.$numero.'"';
			$separateur = ',';
		}
		$to .= ']';
		
		$datas = '{"from" : "33661459733", "text":"'.$this->message.'","to":'.$to.'}';

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-Version: '.$this->x_version,
			'Content-Type: '.$this->content_type,
			'Accept: application/json'.$this->content_type,
			'Authorization: Bearer '.$this->token,
			'Content-Length: ' . strlen($datas))
		);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $datas);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);
	}

}