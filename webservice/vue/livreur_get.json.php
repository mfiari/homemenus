<?php
	
	$retour = array();
	$retour["id"] = $result["uid"];
	$retour["nom"] = $result["nom"];
	$retour["login"] = $result["login"];
	$retour["password"] = $result["password"];
	$retour["status"] = $result["status"];
	$retour["dispo"] = utf8_encode($result["dispo"]);
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>