<?php
	
	$dom = new DOMDocument();
	$livreur = $dom->createElement("livreur");
	$dom->appendChild($livreur);
	$livreur->setAttribute("uid", $result["uid"]);
	$nodeNom = $dom->createElement("nom");
	$texteNom = $dom->createTextNode($result["nom"]);
	$nodeNom->appendChild($texteNom);
	$livreur->appendChild($nodeNom);
	$nodeLogin = $dom->createElement("login");
	$texteLogin = $dom->createTextNode($result["login"]);
	$nodeLogin->appendChild($texteLogin);
	$livreur->appendChild($nodeLogin);
	$nodePassword = $dom->createElement("password");
	$textePassword = $dom->createTextNode($result["password"]);
	$nodePassword->appendChild($textePassword);
	$livreur->appendChild($nodePassword);
	$nodeStatus = $dom->createElement("status");
	$texteStatus = $dom->createTextNode($result["status"]);
	$nodeStatus->appendChild($texteStatus);
	$livreur->appendChild($nodeStatus);
	$nodeDispo = $dom->createElement("dispo");
	$texteDispo = $dom->createTextNode($result["dispo"]);
	$nodeDispo->appendChild($texteDispo);
	$livreur->appendChild($nodeDispo);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>