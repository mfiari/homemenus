<?php
	
	$retour = array();
	
	foreach ($restaurant->categories as $categorie) {
		$retour[] = array();
		$indice = count($retour) -1;
		$retour[$indice]['id'] = $categorie->id;
		$retour[$indice]['nom'] = $categorie->nom;
	}
	
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>