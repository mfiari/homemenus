<?php
	
	$dom = new DOMDocument();
	
	$nodeRestaurant = $dom->createElement("restaurant");
	$nodeRestaurant->setAttribute("id", $restaurant->id);
	$nodeNom = $dom->createElement("nom");
	$texteNom = $dom->createTextNode($restaurant->nom);
	$nodeNom->appendChild($texteNom);
	$nodeRestaurant->appendChild($nodeNom);
	
	$categoriesDom = $dom->createElement("categories");
	
	foreach ($restaurant->categories as $categorie) {
		$nodeCategorie = $dom->createElement("categorie");
		$nodeCategorie->setAttribute("id", $categorie->id);
		$nodeNom = $dom->createElement("nom");
		$texteCategorie = $dom->createTextNode($categorie->nom);
		$nodeNom->appendChild($texteCategorie);
		$nodeCategorie->appendChild($nodeNom);
		$nodeLogo = $dom->createElement("logo");
		$texteLogo = $dom->createTextNode($categorie->logo);
		$nodeLogo->appendChild($texteLogo);
		$nodeCategorie->appendChild($nodeLogo);
		$categoriesDom->appendChild($nodeCategorie);
	}
	$nodeRestaurant->appendChild($categoriesDom);
	$dom->appendChild($nodeRestaurant);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>