<?php
	
	$retour = array();
	
	$retour['id'] = $carte->id;
	$retour['nom'] = utf8_encode($carte->nom);
	$retour['commentaire'] = utf8_encode($carte->commentaire);
	
	$retour['formats'] = array();
	foreach ($carte->formats as $format) {
		$retour['formats'][] = array();
		$indice = count($retour['formats']) -1;
		$retour['formats'][$indice]['id'] = $format->id;
		$retour['formats'][$indice]['nom'] = utf8_encode($format->nom);
		$retour['formats'][$indice]['prix'] = $format->prix;
	}
	
	$retour['accompagnements'] = array();
	foreach ($carte->accompagnements as $accompagnement) {
		$retour['accompagnements'][] = array();
		$indice = count($retour['accompagnements']) -1;
		$retour['accompagnements'][$indice]['id'] = $accompagnement->id;
		$retour['accompagnements'][$indice]['limite'] = $accompagnement->limite;
		$retour['accompagnements'][$indice]['carte'] = array();
		foreach ($accompagnement->cartes as $carteAccompagement) {
			$retour['accompagnements'][$indice]['carte'][] = array();
			$indice2 = count($retour['accompagnements'][$indice]['carte']) -1;
			$retour['accompagnements'][$indice]['carte'][$indice2]['id'] = $carteAccompagement->id;
			$retour['accompagnements'][$indice]['carte'][$indice2]['nom'] = utf8_encode($carteAccompagement->nom);
		}
	}
	
	$retour['supplements'] = array();
	foreach ($carte->supplements as $supplement) {
		$retour['supplements'][] = array();
		$indice = count($retour['supplements']) -1;
		$retour['supplements'][$indice]['id'] = $supplement->id;
		$retour['supplements'][$indice]['nom'] = utf8_encode($supplement->nom);
		$retour['supplements'][$indice]['prix'] = $supplement->prix;
		$retour['supplements'][$indice]['commentaire'] = utf8_encode($supplement->commentaire);
	}
	
	$retour['options'] = array();
	foreach ($carte->options as $option) {
		$retour['options'][] = array();
		$indice = count($retour['options']) -1;
		$retour['options'][$indice]['id'] = $option->id;
		$retour['options'][$indice]['nom'] = utf8_encode($option->nom);
		$retour['options'][$indice]['values'] = array();
		foreach ($option->values as $value) {
			$retour['options'][$indice]['values'][] = array();
			$indice2 = count($retour['options'][$indice]['values']) -1;
			$retour['options'][$indice]['values'][$indice2]['id'] = $value->id;
			$retour['options'][$indice]['values'][$indice2]['nom'] = utf8_encode($value->nom);
		}
	}
	
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>