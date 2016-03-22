<?php
	
	$dom = new DOMDocument();
	$categoriesDom = $dom->createElement("categories");
	$dom->appendChild($categoriesDom);
	
	foreach ($restaurant->categories as $categorie) {
		$nodeCategorie = $dom->createElement("categorie");
		$nodeCategorie->setAttribute("id", $categorie->id);
		$nodeNom = $dom->createElement("nom");
		$texteCategorie = $dom->createTextNode($categorie->nom);
		$nodeNom->appendChild($texteCategorie);
		$nodeCategorie->appendChild($nodeNom);
		$categoriesDom->appendChild($nodeCategorie);
	}
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>