<?php

class Model_Contenu {
	
	private $id;
	private $nom;
	private $carte;
	private $obligatoire;
	private $supplement;
	private $limite_supplement;
	private $commentaire;
	private $logo;
	private $supplements;
	private $accompagnements;
	
	public function __construct() {
		$this->supplements = array();
		$this->accompagnements = array();
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
	
	public function addSupplement ($supplement) {
		$this->supplements[] = $supplement;
	}
	
	public function addAccompagnement ($accompagnement) {
		$this->accompagnements[] = $accompagnement;
	}
	
	public function getLogo ($id_restaurant) {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$id_restaurant)) {
			if (file_exists($logoDirectory.$id_restaurant.'/contenus/'.$this->id.'.png')) {
				$this->logo = $imgPath.$id_restaurant.'/contenus/'.$this->id.'.png';
			} else if (file_exists($logoDirectory.$id_restaurant.'/contenus/'.$this->id.'.jpg')) {
				$this->logo = $imgPath.$id_restaurant.'/contenus/'.$this->id.'.jpg';
			} else if (file_exists($logoDirectory.$id_restaurant.'/contenus/'.$this->id.'.gif')) {
				$this->logo = $imgPath.$id_restaurant.'/contenus/'.$this->id.'.gif';
			} else {
				$this->logo = $imgPath.'default/cloche.jpg';
			}
		} else {
			$this->logo = $imgPath.'default/cloche.jpg';
		}
	}
}