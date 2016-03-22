<?php
	
	$dom = new DOMDocument();
	$carteDom = $dom->createElement("carte");
	$dom->appendChild($carteDom);
	
	$carteDom->setAttribute("id", $carte->id);
	$nodeNom = $dom->createElement("nom");
	$texteCarte = $dom->createTextNode($carte->nom);
	$nodeNom->appendChild($texteCarte);
	$carteDom->appendChild($nodeNom);
	$nodeCommentaire = $dom->createElement("commentaire");
	$commentaireCarte = $dom->createTextNode($carte->commentaire);
	$nodeCommentaire->appendChild($commentaireCarte);
	$carteDom->appendChild($nodeCommentaire);
	
	$nodeFormats = $dom->createElement("formats");
	foreach ($carte->formats as $format) {
		$nodeFormat = $dom->createElement("format");
		$nodeFormat->setAttribute("id", $format->id);
		$nodeNom = $dom->createElement("nom");
		$texteFormat = $dom->createTextNode($format->nom);
		$nodeNom->appendChild($texteFormat);
		$nodeFormat->appendChild($nodeNom);
		$nodePrix = $dom->createElement("prix");
		$textePrix = $dom->createTextNode($format->prix);
		$nodePrix->appendChild($textePrix);
		$nodeFormat->appendChild($nodePrix);
		$nodeTps = $dom->createElement("temps_preparation");
		$texteTps = $dom->createTextNode($format->temps_preparation);
		$nodeTps->appendChild($texteTps);
		$nodeFormat->appendChild($nodeTps);
		
		$nodeFormats->appendChild($nodeFormat);
	}
	$carteDom->appendChild($nodeFormats);
	
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>