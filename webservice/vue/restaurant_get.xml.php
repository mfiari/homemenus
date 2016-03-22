<?php
	
	$dom = new DOMDocument();
	$restaurant = $dom->createElement("restaurant");
	$dom->appendChild($restaurant);
	$restaurant->setAttribute("id", $result->id);
	$nodeNom = $dom->createElement("nom");
	$texteNom = $dom->createTextNode($result->nom);
	$nodeNom->appendChild($texteNom);
	$restaurant->appendChild($nodeNom);
	$nodeRue = $dom->createElement("rue");
	$texteRue = $dom->createTextNode($result->rue);
	$nodeRue->appendChild($texteRue);
	$restaurant->appendChild($nodeRue);
	$nodeVille = $dom->createElement("ville");
	$texteVille = $dom->createTextNode($result->ville);
	$nodeVille->appendChild($texteVille);
	$restaurant->appendChild($nodeVille);
	$nodeCodePostal = $dom->createElement("code_postal");
	$texteCodePostal = $dom->createTextNode($result->code_postal);
	$nodeCodePostal->appendChild($texteCodePostal);
	$restaurant->appendChild($nodeCodePostal);
	$nodeTelephone = $dom->createElement("telephone");
	$texteTelephone = $dom->createTextNode($result->telephone);
	$nodeTelephone->appendChild($texteTelephone);
	$restaurant->appendChild($nodeTelephone);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>