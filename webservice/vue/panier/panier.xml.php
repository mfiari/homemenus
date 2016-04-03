<?php
	
	$dom = new DOMDocument();
	$nodePanier = $dom->createElement("panier");
	$dom->appendChild($nodePanier);
	$nodePanier->setAttribute("id", $panier->id);
	addTextNode ($dom, $nodePanier, "rue", utf8_encode($panier->rue));
	addTextNode ($dom, $nodePanier, "ville", utf8_encode($panier->ville));
	addTextNode ($dom, $nodePanier, "code_postal", $panier->code_postal);
	addTextNode ($dom, $nodePanier, "telephone", $panier->telephone);
	addTextNode ($dom, $nodePanier, "heure_souhaite", $panier->heure_souhaite);
	addTextNode ($dom, $nodePanier, "minute_souhaite", $panier->minute_souhaite);
	addTextNode ($dom, $nodePanier, "prix_livraison", $panier->prix_livraison);
	addTextNode ($dom, $nodePanier, "prix_minimum", $panier->prix_minimum);
	
	$nodeRestaurant = $dom->createElement("restaurant");
	$nodeRestaurant->setAttribute("id", $panier->restaurant->id);
	addTextNode ($dom, $nodeRestaurant, "nom", $panier->restaurant->nom);
	
	$nodeHoraire = $dom->createElement("horaire");
	addTextNode ($dom, $nodeHoraire, "id_jour", $panier->restaurant->horaire->id_jour);
	addTextNode ($dom, $nodeHoraire, "heure_debut", $panier->restaurant->horaire->heure_debut);
	addTextNode ($dom, $nodeHoraire, "minute_debut", $panier->restaurant->horaire->minute_debut);
	addTextNode ($dom, $nodeHoraire, "heure_fin", $panier->restaurant->horaire->heure_fin);
	addTextNode ($dom, $nodeHoraire, "minute_fin", $panier->restaurant->horaire->minute_fin);
	$nodeRestaurant->appendChild($nodeHoraire);
	
	$nodePanier->appendChild($nodeRestaurant);
	
	$nodeCartes = $dom->createElement("cartes");
	foreach ($panier->carteList as $carte) {
		$nodeCarte = $dom->createElement("carte");
		$nodeCarte->setAttribute("id", $carte->id);
		addTextNode ($dom, $nodeCarte, "nom", utf8_encode($carte->nom));
		addTextNode ($dom, $nodeCarte, "prix", $carte->prix);
		addTextNode ($dom, $nodeCarte, "quantite", $carte->quantite);
		$nodeCartes->appendChild($nodeCarte);
	}
	$nodePanier->appendChild($nodeCartes);
	
	$nodeMenus = $dom->createElement("menus");
	foreach ($panier->menuList as $menu) {
		$nodeMenu = $dom->createElement("menus");
		$nodeMenu->setAttribute("id", $menu->id);
		addTextNode ($dom, $nodeMenu, "nom", utf8_encode($menu->nom));
		addTextNode ($dom, $nodeMenu, "prix", $menu->prix);
		addTextNode ($dom, $nodeMenu, "quantite", $menu->quantite);
		$nodeMenus->appendChild($nodeMenu);
	}
	$nodePanier->appendChild($nodeMenus);
	
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>