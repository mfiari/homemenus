<?php
	
	$dom = new DOMDocument();
	$livreurs = $dom->createElement("livreurs");
	$dom->appendChild($livreurs);
	$nbResult = 0;
	foreach ($result as $key => $value) {
		$livreur = $dom->createElement("livreur");
		$livreurs->appendChild($livreur);
		$livreur->setAttribute("id", $value["uid"]);
		$nodeNom = $dom->createElement("nom");
		$texteNom = $dom->createTextNode($value["nom"]);
		$nodeNom->appendChild($texteNom);
		$livreur->appendChild($nodeNom);
		$nbResult++;
	}
	$livreurs->setAttribute("nbResult", $nbResult);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>