<?php
	
	$retour = array();
	
	foreach ($result as $key => $value) {
		$retour[] = array();
		$indice = count($retour)-1;
		$retour[$indice]["id"] = $value->id;
		$retour[$indice]["nom"] = utf8_encode($value->nom);
		$retour[$indice]["ville"] = utf8_encode($value->ville);
		$retour[$indice]["logo"] = $value->logo;
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>