<?php

class Model_News extends Model_Template {
	
	protected $id;
	private $titre;
	private $text;
	private $image;
	private $link_text;
	private $link_url;
	private $actif;
	private $date_creation;
	private $date_debut;
	private $date_fin;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
		$this->id = -1;
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
	
	public function getAll () {
		$sql = "SELECT id, titre, actif, date_creation, date_debut, date_fin FROM news";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listNews = array();
		foreach ($result as $item) {
			$news = new Model_News(false);
			$news->id = $item["id"];
			$news->titre = $item["titre"];
			$news->actif = $item["actif"];
			$news->date_creation = $item["date_creation"];
			$news->date_debut = $item["date_debut"];
			$news->date_fin = $item["date_fin"];
			$listNews[] = $news;
		}
		return $listNews;
	}
	
	public function getAllActiveNews () {
		$sql = "SELECT id, titre, text, image, link_text, link_url FROM news WHERE actif = 1 ORDER BY ordre ASC";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listNews = array();
		foreach ($result as $item) {
			$news = new Model_News(false);
			$news->id = $item["id"];
			$news->titre = $item["titre"];
			$news->text = $item["text"];
			$news->image = $item["image"];
			$news->link_text = $item["link_text"];
			$news->link_url = $item["link_url"];
			$listNews[] = $news;
		}
		return $listNews;
	}
	
	public function save () {
	}
}