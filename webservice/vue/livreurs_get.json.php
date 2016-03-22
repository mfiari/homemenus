<?php
	
	$retour = array();
	
	foreach ($result as $key => $value) {
		$retour[] = array();
		$indice = count($retour)-1;
		$retour[$indice]["id"] = $value["uid"];
		$retour[$indice]["nom"] = $value["nom"];
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>