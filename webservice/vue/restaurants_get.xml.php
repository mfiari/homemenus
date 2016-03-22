<?php
	
	$dom = new DOMDocument();
	$restaurants = $dom->createElement("restaurants");
	$dom->appendChild($restaurants);
	$nbResult = 0;
	foreach ($result as $key => $value) {
		$restaurant = $dom->createElement("restaurant");
		$restaurants->appendChild($restaurant);
		$restaurant->setAttribute("id", $value->id);
		$nodeNom = $dom->createElement("nom");
		$texteNom = $dom->createTextNode(utf8_encode($value->nom));
		$nodeNom->appendChild($texteNom);
		$restaurant->appendChild($nodeNom);
		$nodeRue = $dom->createElement("rue");
		$texteRue = $dom->createTextNode($value->rue);
		$nodeRue->appendChild($texteRue);
		$restaurant->appendChild($nodeRue);
		$nodeVille = $dom->createElement("ville");
		$texteVille = $dom->createTextNode($value->ville);
		$nodeVille->appendChild($texteVille);
		$restaurant->appendChild($nodeVille);
		$nodeCodePostal = $dom->createElement("code_postal");
		$texteCodePostal = $dom->createTextNode($value->code_postal);
		$nodeCodePostal->appendChild($texteCodePostal);
		$restaurant->appendChild($nodeCodePostal);
		$nodeTelephone = $dom->createElement("telephone");
		$texteTelephone = $dom->createTextNode($value->telephone);
		$nodeTelephone->appendChild($texteTelephone);
		$restaurant->appendChild($nodeTelephone);
		$nodeLogo = $dom->createElement("logo");
		$texteLogo = $dom->createTextNode($value->logo);
		$nodeLogo->appendChild($texteLogo);
		$restaurant->appendChild($nodeLogo);
		$nbResult++;
	}
	$restaurants->setAttribute("nbResult", $nbResult);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>