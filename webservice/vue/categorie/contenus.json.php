<?php
	
	$retour = array();
	
	foreach ($categorie->contenus as $contenu) {
		$retour[] = array();
		$indice = count($retour) -1;
		$retour[$indice]['id'] = $contenu->id;
		$retour[$indice]['nom'] = $contenu->nom;
	}
	
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>