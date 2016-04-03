<?php
	
	$dom = new DOMDocument();
	
	$nodeRestaurant = $dom->createElement("restaurant");
	$nodeRestaurant->setAttribute("id", $restaurant->id);
	$nodeNom = $dom->createElement("nom");
	$texteNom = $dom->createTextNode(utf8_encode($restaurant->nom));
	$nodeNom->appendChild($texteNom);
	$nodeRestaurant->appendChild($nodeNom);
	
	$nodeCategorie = $dom->createElement("categorie");
	$nodeCategorie->setAttribute("id", $categorie->id);
	$nodeNom = $dom->createElement("nom");
	$texteNom = $dom->createTextNode(utf8_encode($categorie->nom));
	$nodeNom->appendChild($texteNom);
	$nodeCategorie->appendChild($nodeNom);
	
	$nodeRestaurant->appendChild($nodeCategorie);
	
	$nodeChildrens = $dom->createElement("childrens");
	
	foreach ($restaurant->categories as $children) {
		$nodeChildren = $dom->createElement("children");
		$nodeChildren->setAttribute("id", $children->id);
		$nodeNom = $dom->createElement("nom");
		$texteCategorie = $dom->createTextNode(utf8_encode($children->nom));
		$nodeNom->appendChild($texteCategorie);
		$nodeChildren->appendChild($nodeNom);
	
		$nodeContenus = $dom->createElement("contenus");
		
		foreach ($children->contenus as $contenu) {
			$nodeContenu = $dom->createElement("contenu");
			$nodeContenu->setAttribute("id", $contenu->id);
			$nodeNom = $dom->createElement("nom");
			$texteContenu = $dom->createTextNode(utf8_encode($contenu->nom));
			$nodeNom->appendChild($texteContenu);
			$nodeContenu->appendChild($nodeNom);
			$nodeLogo = $dom->createElement("logo");
			$texteLogo = $dom->createTextNode($contenu->logo);
			$nodeLogo->appendChild($texteLogo);
			$nodeContenu->appendChild($nodeLogo);
			
			$nodeContenus->appendChild($nodeContenu);
		}
		$nodeChildren->appendChild($nodeContenus);
		$nodeChildrens->appendChild($nodeChildren);
	}
	$nodeCategorie->appendChild($nodeChildrens);
	$dom->appendChild($nodeRestaurant);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>