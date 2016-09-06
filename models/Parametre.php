<?php

class Model_Parametre {
	
	private $id;
	private $user;
	private $default_adresse_search;
	private $send_mail_commande;
	private $send_sms_commande;
	
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
}