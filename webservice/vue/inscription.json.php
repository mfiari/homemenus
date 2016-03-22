<?php
	
	$retour = array();
	$retour["id"] = $user->id;
	$retour["nom"] = $user->nom;
	$retour["prenom"] = $user->prenom;
	$retour["login"] = $user->login;
	$retour["status"] = $user->status;
	$retour["session"] = $user->session;
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>