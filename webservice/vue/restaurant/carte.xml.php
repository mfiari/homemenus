<?php
	
	$dom = new DOMDocument();
	$restaurantDom = $dom->createElement("restaurant");
	$dom->appendChild($restaurantDom);
	$restaurantDom->setAttribute("id", $restaurant->id);
	$nodeNom = $dom->createElement("nom");
	$texteNom = $dom->createTextNode($restaurant->nom);
	$nodeNom->appendChild($texteNom);
	$restaurantDom->appendChild($nodeNom);
	
	$nodeCategories = $dom->createElement("categories");
	foreach ($restaurant->carte as $categorie => $value) {
		$nodeCategorie = $dom->createElement("categorie");
		$nodeNom = $dom->createElement("nom");
		$texteCategorie = $dom->createTextNode($categorie);
		$nodeNom->appendChild($texteCategorie);
		$nodeCategorie->appendChild($nodeNom);
		
		$nodeCartes = $dom->createElement("cartes");
		foreach ($value as $carte) {
			$nodeCarte = $dom->createElement("carte");
			$nodeCarte->setAttribute("id", $carte->id);
			$nodeNom = $dom->createElement("nom");
			$texteNom = $dom->createTextNode($carte->nom);
			$nodeNom->appendChild($texteNom);
			$nodeCarte->appendChild($nodeNom);
			$nodeCartes->appendChild($nodeCarte);
		}
		$nodeCategorie->appendChild($nodeCartes);
		$nodeCategories->appendChild($nodeCategorie);
	}
	$restaurantDom->appendChild($nodeCategories);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>