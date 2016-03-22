<?php
	
	$retour = array();
	$retour["id"] = $result->id;
	$retour["nom"] = utf8_encode($result->nom);
	$retour["rue"] = $result->rue;
	$retour["ville"] = $result->ville;
	$retour["code_postal"] = $result->code_postal;
	$retour["telephone"] = $result->telephone;
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>