<?php
	
	$retour = array();
	
	$retour['id'] = $restaurant->id;
	$retour['nom'] = $restaurant->nom;
	$retour['categories'] = array();
	
	foreach ($restaurant->carte as $categorie => $value) {
		$retour['categories'][] = array();
		$indice = count($retour['categories']) -1;
		$retour['categories'][$indice]['nom'] = $categorie;
		$retour['categories'][$indice]['carte'] = array();
		foreach ($value as $carte) {
			$retour['categories'][$indice]['carte'][] = array();
			$indiceCarte = count($retour['categories'][$indice]['carte']) -1;
			$retour['categories'][$indice]['carte'][$indiceCarte]['id'] = $carte->id;
			$retour['categories'][$indice]['carte'][$indiceCarte]['nom'] = $carte->nom;
		}
	}
	
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>