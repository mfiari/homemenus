<?php
	
	$dom = new DOMDocument();
	$panier = $dom->createElement("panier");
	$dom->appendChild($panier);
	$nbResult = 0;
	foreach ($result as $key => $value) {
		$menu = $dom->createElement("menu");
		$panier->appendChild($menu);
		$menu->setAttribute("id_panier", $value["id_panier"]);
		$menu->setAttribute("id", $value["id"]);
		$nodeQuantite = $dom->createElement("quantite");
		$texteQuantite = $dom->createTextNode($value["quantite"]);
		$nodeQuantite->appendChild($texteQuantite);
		$menu->appendChild($nodeQuantite);
		$nodeNom = $dom->createElement("nom");
		$texteNom = $dom->createTextNode($value["nom"]);
		$nodeNom->appendChild($texteNom);
		$menu->appendChild($nodeNom);
		$nodeRue = $dom->createElement("prix");
		$texteRue = $dom->createTextNode($value["prix"]);
		$nodeRue->appendChild($texteRue);
		$menu->appendChild($nodeRue);
		$nodePreparation = $dom->createElement("temps_preparation");
		$textePreparation = $dom->createTextNode($value["temps_preparation"]);
		$nodePreparation->appendChild($textePreparation);
		$menu->appendChild($nodePreparation);
		$nbResult++;
	}
	$panier->setAttribute("nbResult", $nbResult);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>