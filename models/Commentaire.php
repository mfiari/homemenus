<?php

class Model_Commentaire {
	
	private $id;
	private $note;
	private $vote;
	private $commentaire;
	private $anonyme;
	private $social;
	private $date;
	private $validation;
	private $user;
	
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