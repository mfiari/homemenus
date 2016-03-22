<?php
	
	$dom = new DOMDocument();
	$userDom = $dom->createElement("user");
	$dom->appendChild($userDom);
	$userDom->setAttribute("uid", $user->id);
	$nodeNom = $dom->createElement("nom");
	$texteNom = $dom->createTextNode($user->nom);
	$nodeNom->appendChild($texteNom);
	$userDom->appendChild($nodeNom);
	$nodeLogin = $dom->createElement("login");
	$texteLogin = $dom->createTextNode($user->login);
	$nodeLogin->appendChild($texteLogin);
	$userDom->appendChild($nodeLogin);
	$nodeStatus = $dom->createElement("status");
	$texteStatus = $dom->createTextNode($user->status);
	$nodeStatus->appendChild($texteStatus);
	$userDom->appendChild($nodeStatus);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>