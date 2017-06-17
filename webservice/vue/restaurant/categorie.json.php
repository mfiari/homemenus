<?php
	
	$retour = array();
	
	foreach ($restaurant->categories as $children) {
		foreach ($children->contenus as $contenu) {
			$retour[] = array();
			$indice = count($retour)-1;
			$retour[$indice]["id"] = $contenu->id;
			$retour[$indice]["nom"] = utf8_encode($contenu->nom);
			$retour[$indice]["logo"] = $contenu->logo;
		}
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>