<?php
	
	$dom = new DOMDocument();
	$menus = $dom->createElement("menus");
	$dom->appendChild($menus);
	$nbResult = 0;
	foreach ($result as $key => $value) {
		$menu = $dom->createElement("menu");
		$menus->appendChild($menu);
		$menu->setAttribute("id", $value["id"]);
		$nodeNom = $dom->createElement("nom");
		$texteNom = $dom->createTextNode($value["nom"]);
		$nodeNom->appendChild($texteNom);
		$menu->appendChild($nodeNom);
		$nodeRue = $dom->createElement("prix");
		$texteRue = $dom->createTextNode($value["prix"]);
		$nodeRue->appendChild($texteRue);
		$menu->appendChild($nodeRue);
		$nodePreparation = $dom->createElement("preparation");
		$textePreparation = $dom->createTextNode($value["temps_preparation"]);
		$nodePreparation->appendChild($textePreparation);
		$menu->appendChild($nodePreparation);
		$nbResult++;
	}
	$menus->setAttribute("nbResult", $nbResult);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>