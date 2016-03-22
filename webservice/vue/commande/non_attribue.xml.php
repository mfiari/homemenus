<?php
	
	$dom = new DOMDocument();
	$commandes = $dom->createElement("commandes");
	$dom->appendChild($commandes);
	$nbResult = 0;
	foreach ($result as $key => $value) {
		$commande = $dom->createElement("commande");
		$commandes->appendChild($commande);
		$commande->setAttribute("id", $value->id);
		$nodeRue = $dom->createElement("rue");
		$texteRue = $dom->createTextNode(utf8_encode($value->rue));
		$nodeRue->appendChild($texteRue);
		$commande->appendChild($nodeRue);
		$nodeVille = $dom->createElement("ville");
		$texteVille = $dom->createTextNode(utf8_encode($value->ville));
		$nodeVille->appendChild($texteVille);
		$commande->appendChild($nodeVille);
		$nodeCP = $dom->createElement("code_postal");
		$texteCP = $dom->createTextNode($value->code_postal);
		$nodeCP->appendChild($texteCP);
		$commande->appendChild($nodeCP);
		$nodeDate = $dom->createElement("date");
		$texteDate = $dom->createTextNode($value->date_commande);
		$nodeDate->appendChild($texteDate);
		$commande->appendChild($nodeDate);
		$nodeHeureSouhaite = $dom->createElement("heure_souhaite");
		$texteHeureSouhaite = $dom->createTextNode($value->heure_souhaite);
		$nodeHeureSouhaite->appendChild($texteHeureSouhaite);
		$commande->appendChild($nodeHeureSouhaite);
		$nodeMinuteSouhaite = $dom->createElement("minute_souhaite");
		$texteMinuteSouhaite = $dom->createTextNode($value->minute_souhaite);
		$nodeMinuteSouhaite->appendChild($texteMinuteSouhaite);
		$commande->appendChild($nodeMinuteSouhaite);
		$nodeRestaurant = $dom->createElement("restaurant");
		$nodeRestaurant->setAttribute("id", $value->restaurant->id);
		$nodeNom = $dom->createElement("nom");
		$texteNom = $dom->createTextNode(utf8_encode($value->restaurant->nom));
		$nodeNom->appendChild($texteNom);
		$nodeRestaurant->appendChild($nodeNom);
		$nodeRue = $dom->createElement("rue");
		$texteRue = $dom->createTextNode(utf8_encode($value->restaurant->rue));
		$nodeRue->appendChild($texteRue);
		$nodeRestaurant->appendChild($nodeRue);
		$nodeVille = $dom->createElement("ville");
		$texteVile = $dom->createTextNode(utf8_encode($value->restaurant->ville));
		$nodeVille->appendChild($texteVile);
		$nodeRestaurant->appendChild($nodeVille);
		$nodeCP = $dom->createElement("code_postal");
		$texteCP = $dom->createTextNode($value->restaurant->code_postal);
		$nodeCP->appendChild($texteCP);
		$nodeRestaurant->appendChild($nodeCP);
		$nodeLatitude = $dom->createElement("latitude");
		$texteLatitude = $dom->createTextNode($value->restaurant->latitude);
		$nodeLatitude->appendChild($texteLatitude);
		$nodeRestaurant->appendChild($nodeLatitude);
		$nodeLongitude = $dom->createElement("longitude");
		$texteLongitude = $dom->createTextNode($value->restaurant->longitude);
		$nodeLongitude->appendChild($texteLongitude);
		$nodeRestaurant->appendChild($nodeLongitude);
		$commande->appendChild($nodeRestaurant);
		$nbResult++;
	}
	$commandes->setAttribute("nbResult", $nbResult);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>