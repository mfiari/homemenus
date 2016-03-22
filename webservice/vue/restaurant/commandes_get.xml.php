<?php
	
	$dom = new DOMDocument();
	$commandes = $dom->createElement("commandes");
	$dom->appendChild($commandes);
	$nbResult = 0;
	foreach ($result as $key => $value) {
		$commande = $dom->createElement("commande");
		$commandes->appendChild($commande);
		$commande->setAttribute("id", $value->id);
		$nodeDate = $dom->createElement("date");
		$texteDate = $dom->createTextNode(utf8_encode($value->date_commande));
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
		$nodeEtape = $dom->createElement("etape");
		$texteEtape = $dom->createTextNode($value->etape);
		$nodeEtape->appendChild($texteEtape);
		$commande->appendChild($nodeEtape);
		$nbResult++;
	}
	$commandes->setAttribute("nbResult", $nbResult);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>