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
	
	public function get () {
		$sql = "SELECT titre, text, image, link_text, link_url FROM news WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->titre = $value["titre"];
		$this->text = $value["text"];
		$this->image = $value["image"];
		$this->link_text = $value["link_text"];
		$this->link_url = $value["link_url"];
		return $this;
	}
	
	public function save () {
		if ($this->id == -1) {
			return $this->insert();
		} else {
			return $this->update();
		}
		return false;
	}
	
	public function insert() {
		$sql = "INSERT INTO news (titre, text, link_text, link_url, date_debut, date_fin) VALUES (:titre, :text, :link_text, :link_url, :date_debut, :date_fin)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":titre", $this->titre);
		$stmt->bindValue(":text", $this->text);
		$stmt->bindValue(":link_text", $this->link_text);
		$stmt->bindValue(":link_url", $this->link_url);
		$stmt->bindValue(":date_debut", $this->date_debut);
		$stmt->bindValue(":date_fin", $this->date_fin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	public function setImage ($img) {
		$sql = "UPDATE news SET image = :image WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":image", $img);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function enable () {
		$sql = "UPDATE news SET actif = true WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function disable () {
		$sql = "UPDATE news SET actif = false WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function deleted () {
		$sql = "UPDATE users SET deleted = true, date_suppression = NOW() WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
}