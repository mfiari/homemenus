<?php

class Model_Certificat {
	
	private $id;
	private $nom;
	private $description;
	private $logo;
	private $url;
	
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