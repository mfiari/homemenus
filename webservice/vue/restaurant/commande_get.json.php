<?php
	
	$retour = array();
	
	$retour['id'] = $result->id;
	$retour['rue'] = utf8_encode($result->rue);
	$retour['ville'] = utf8_encode($result->ville);
	$retour['code_postal'] = $result->code_postal;
	$retour['telephone'] = $result->telephone;
	$retour['heure_souhaite'] = $result->heure_souhaite;
	$retour['minute_souhaite'] = $result->minute_souhaite;
	$retour['heure_restaurant'] = $result->heure_restaurant;
	$retour['minute_restaurant'] = $result->minute_restaurant;
	$retour['etape'] = $result->etape;
	$retour['prix'] = $result->prix;
	$retour['date_validation_restaurant'] = $result->date_validation_restaurant;
	$retour['date_fin_preparation_restaurant'] = $result->date_fin_preparation_restaurant;
	
	if ($result->client) {
		$retour['client'] = array();
		$retour['client']['id'] = $result->client->id;
		$retour['client']['nom'] = $result->client->nom;
		$retour['client']['prenom'] = $result->client->prenom;
	}
	
	if ($result->restaurant) {
		$retour['restaurant'] = array();
		$retour['restaurant']['id'] = $result->restaurant->id;
		$retour['restaurant']['nom'] = utf8_encode($result->restaurant->nom);
		$retour['restaurant']['rue'] = utf8_encode($result->restaurant->rue);
		$retour['restaurant']['ville'] = utf8_encode($result->restaurant->ville);
		$retour['restaurant']['code_postal'] = $result->restaurant->code_postal;
		$retour['restaurant']['telephone'] = $result->restaurant->telephone;
	}
	
	if ($result->livreur && $result->livreur->id != '') {
		$retour['livreur'] = array();
		$retour['livreur']['id'] = $result->livreur->id;
		$retour['livreur']['nom'] = utf8_encode($result->livreur->nom);
		$retour['livreur']['prenom'] = utf8_encode($result->livreur->prenom);
	}
	
	$retour['menus'] = array();
	foreach ($result->menus as $menu) {
		$retour['menus'][$menu->id] = array();
		$retour['menus'][$menu->id]['nom'] = utf8_encode($menu->nom);
		$retour['menus'][$menu->id]['quantite'] = utf8_encode($menu->quantite);
		
		$retour['menus'][$menu->id]['formats'] = array();
		
		foreach ($menu->formats as $format) {
			$retour['menus'][$menu->id]['formats'][$format->id] = array();
			$retour['menus'][$menu->id]['formats'][$format->id]['nom'] = utf8_encode($format->nom);
		}
		
		$retour['menus'][$menu->id]['formules'] = array();
		
		foreach ($menu->formules as $formule) {
			$retour['menus'][$menu->id]['formules'][$formule->id] = array();
			$retour['menus'][$menu->id]['formules'][$formule->id]['nom'] = utf8_encode($formule->nom);
			
			$retour['menus'][$menu->id]['formules'][$formule->id]['categories'] = array();
			
			foreach ($formule->categories as $categorie) {
				$retour['menus'][$menu->id]['formules'][$formule->id]['categories'][$categorie->id] = array();
				$retour['menus'][$menu->id]['formules'][$formule->id]['categories'][$categorie->id]['nom'] = utf8_encode($categorie->nom);
				
				$retour['menus'][$menu->id]['formules'][$formule->id]['categories'][$categorie->id]['contenus'] = array();
				
				$contenus = $dom->createElement("contenus");
				foreach ($categorie->contenus as $contenu) {
					$retour['menus'][$menu->id]['formules'][$formule->id]['categories'][$categorie->id]['contenus'][$contenu->id] = array();
					$retour['menus'][$menu->id]['formules'][$formule->id]['categories'][$categorie->id]['contenus'][$contenu->id]['nom'] = utf8_encode($contenu->nom);
				}
			}
		}
	}
	
	$retour['cartes'] = array();
	foreach ($result->cartes as $carte) {
		$retour['cartes'][$carte->id] = array();
		$retour['cartes'][$carte->id]['nom'] = utf8_encode($carte->nom);
		$retour['cartes'][$carte->id]['prix'] = $carte->prix;
		$retour['cartes'][$carte->id]['quantite'] = $carte->quantite;
		
		$retour['cartes'][$carte->id]['options'] = array();
		
		foreach ($carte->options as $option) {
			$retour['cartes'][$carte->id]['options'][$option->id] = array();
			$retour['cartes'][$carte->id]['options'][$option->id]['nom'] = utf8_encode($option->nom);
			$value = $option->values[0];
			$retour['cartes'][$carte->id]['options'][$option->id]['value'] = utf8_encode($value->nom);
		}
		
		$retour['cartes'][$carte->id]['supplements'] = array();
		
		foreach ($carte->supplements as $supplement) {
			$retour['cartes'][$carte->id]['supplements'][$supplement->id] = array();
			$retour['cartes'][$carte->id]['supplements'][$supplement->id]['nom'] = utf8_encode($supplement->nom);
		}
		
		$retour['cartes'][$carte->id]['accompagnements'] = array();
		
		foreach ($carte->accompagnements as $accompagnement) {
			$retour['cartes'][$carte->id]['accompagnements'][$accompagnement->id] = array();
			$retour['cartes'][$carte->id]['accompagnements'][$accompagnement->id]['cartes'] = array();
			foreach ($accompagnement->cartes as $accompagnementCarte) {
				$retour['cartes'][$carte->id]['accompagnements'][$accompagnement->id]['cartes'][$accompagnementCarte->id] = array();
				$retour['cartes'][$carte->id]['accompagnements'][$accompagnement->id]['cartes'][$accompagnementCarte->id]['nom'] = utf8_encode($accompagnementCarte->nom);
			}
		}
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>