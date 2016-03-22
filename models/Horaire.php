<?php

class Model_Horaire {
	
	private $id;
	private $id_jour;
	private $name;
	private $heure_debut;
	private $minute_debut;
	private $heure_fin;
	private $minute_fin;
	
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