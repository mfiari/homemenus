<?php
	
	$dom = new DOMDocument();
	$nodeCarte = $dom->createElement("carte");
	$dom->appendChild($nodeCarte);
	$nodeCarte->setAttribute("id", $carte->id);
	addTextNode ($dom, $nodeCarte, "nom", utf8_encode($carte->nom));
	addTextNode ($dom, $nodeCarte, "commentaire", utf8_encode($carte->commentaire));
	
	$nodeFormats = $dom->createElement("formats");
	foreach ($carte->formats as $format) {
		$nodeFromat = $dom->createElement("format");
		$nodeFromat->setAttribute("id", $format->id);
		addTextNode ($dom, $nodeFromat, "nom", utf8_encode($format->nom));
		addTextNode ($dom, $nodeFromat, "prix", utf8_encode($format->prix));
		$nodeFormats->appendChild($nodeFromat);
	}
	$nodeCarte->appendChild($nodeFormats);
	
	$nodeAccompagnements = $dom->createElement("accompagnements");
	foreach ($carte->accompagnements as $accompagnement) {
		$nodeAccompagnement = $dom->createElement("accompagnement");
		$nodeAccompagnement->setAttribute("id", $accompagnement->id);
		addTextNode ($dom, $nodeAccompagnement, "limite", $accompagnement->limite);
		
		$nodeCartes = $dom->createElement("cartes");
		foreach ($accompagnement->cartes as $carteAccompagement) {
			$nodeCarteAccompagnement = $dom->createElement("carte");
			$nodeCarteAccompagnement->setAttribute("id", $carteAccompagement->id);
			addTextNode ($dom, $nodeCarteAccompagnement, "nom", utf8_encode($nodeCarteAccompagnement->nom));
			$nodeCartes->appendChild($nodeCarteAccompagnement);
		}
		$nodeAccompagnement->appendChild($nodeCartes);
	}
	$nodeCarte->appendChild($nodeAccompagnements);
	
	$nodeSupplements = $dom->createElement("supplements");
	foreach ($carte->supplements as $supplement) {
		$nodeSupplement = $dom->createElement("supplement");
		$nodeSupplement->setAttribute("id", $supplement->id);
		addTextNode ($dom, $nodeSupplement, "nom", utf8_encode($supplement->nom));
		addTextNode ($dom, $nodeSupplement, "prix", $supplement->prix);
		addTextNode ($dom, $nodeSupplement, "commentaire", utf8_encode($supplement->commentaire));
		$nodeSupplements->appendChild($nodeSupplement);
	}
	$nodeCarte->appendChild($nodeSupplements);
	
	$nodeOptions = $dom->createElement("options");
	foreach ($carte->options as $option) {
		$nodeOption = $dom->createElement("option");
		$nodeOption->setAttribute("id", $option->id);
		addTextNode ($dom, $nodeOption, "nom", utf8_encode($option->nom));
	
		$nodeOptionValues = $dom->createElement("values");
		foreach ($option->values as $value) {
			$nodeOptionValue = $dom->createElement("value");
			$nodeOptionValue->setAttribute("id", $value->id);
			addTextNode ($dom, $nodeOptionValue, "nom", utf8_encode($value->nom));
			$nodeOptionValues->appendChild($nodeOptionValue);
		}
		$nodeOption->appendChild($nodeOptionValues);
		
		$nodeOptions->appendChild($nodeOption);
	}
	$nodeCarte->appendChild($nodeOptions);
	
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>