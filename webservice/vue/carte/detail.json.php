<?php
	
	$retour = array();
	
	$retour['id'] = $carte->id;
	$retour['nom'] = $carte->nom;
	$retour['commentaire'] = $carte->commentaire;
	$retour['formats'] = array();
	
	foreach ($carte->formats as $format) {
		$retour['formats'][] = array();
		$indice = count($retour['formats']) -1;
		$retour['formats'][$indice]['id'] = $format->id;
		$retour['formats'][$indice]['nom'] = $format->nom;
		$retour['formats'][$indice]['prix'] = $format->prix;
		$retour['formats'][$indice]['temps_preparation'] = $format->temps_preparation;
	}
	$retour['supplements'] = array();
	
	foreach ($carte->supplements as $supplement) {
		$retour['supplements'][] = array();
		$indice = count($retour['supplements']) -1;
		$retour['supplements'][$indice]['id'] = $supplement->id;
		$retour['supplements'][$indice]['nom'] = $supplement->nom;
		$retour['supplements'][$indice]['prix'] = $supplement->prix;
		$retour['supplements'][$indice]['commentaire'] = $supplement->commentaire;
	}
	
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>
