<?php
	
	$dom = new DOMDocument();
	$contenusDom = $dom->createElement("contenus");
	$dom->appendChild($contenusDom);
	
	foreach ($categorie->contenus as $contenu) {
		$nodeContenu = $dom->createElement("contenu");
		$nodeContenu->setAttribute("id", $contenu->id);
		$nodeNom = $dom->createElement("nom");
		$texteContenu = $dom->createTextNode($contenu->nom);
		$nodeNom->appendChild($texteContenu);
		$nodeContenu->appendChild($nodeNom);
		$contenusDom->appendChild($nodeContenu);
	}
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>