<?php
	
	$retour = array();
	
	$retour['id'] = $panier->id;
	$retour['rue'] = utf8_encode($panier->rue);
	$retour['ville'] = utf8_encode($panier->ville);
	$retour['code_postal'] = $panier->code_postal;
	$retour['telephone'] = $panier->telephone;
	$retour['heure_souhaite'] = $panier->heure_souhaite;
	$retour['minute_souhaite'] = $panier->minute_souhaite;
	$retour['prix_livraison'] = $panier->prix_livraison;
	$retour['prix_minimum'] = $panier->prix_minimum;
	
	$retour['restaurant'] = array();
	$retour['restaurant']['id'] = $panier->restaurant->id;
	$retour['restaurant']['nom'] = utf8_encode($panier->restaurant->nom);
	
	$retour['cartes'] = array();
	foreach ($panier->carteList as $carte) {
		$retour['cartes'][] = array();
		$indice = count($retour['cartes']) -1;
		$retour['cartes'][$indice]['id'] = $carte->id;
		$retour['cartes'][$indice]['nom'] = utf8_encode($carte->nom);
		$retour['cartes'][$indice]['prix'] = $carte->prix;
		$retour['cartes'][$indice]['quantite'] = $carte->quantite;
	}
	
	$retour['menus'] = array();
	foreach ($panier->menuList as $menu) {
		$retour['menus'][] = array();
		$indice = count($retour['menus']) -1;
		$retour['menus'][$indice]['id'] = $menu->id;
		$retour['menus'][$indice]['nom'] = utf8_encode($menu->nom);
		$retour['menus'][$indice]['prix'] = $menu->prix;
		$retour['menus'][$indice]['quantite'] = $menu->quantite;
	}
	
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>