<?php
	
	$retour = array();
	
	$retour['restaurant'] = array();
	
	$retour['restaurant']['id'] = $restaurant->id;
	$retour['restaurant']['nom'] = $restaurant->nom;
	$retour['restaurant']['code_postal'] = $restaurant->code_postal;
	$retour['restaurant']['ville'] = $restaurant->ville;
	$retour['restaurant']['livreur'] = $has_livreur_dispo;
	
	$retour['restaurant']['menu'] = array();
	
	$retour['restaurant']['menu']['id'] = $menu->id;
	$retour['restaurant']['menu']['nom'] = $menu->nom;
	$retour['restaurant']['menu']['commentaire'] = $menu->commentaire;
	
	$retour['restaurant']['menu']['formats'] = array();
	
	foreach ($menu->formats as $format) {
		$formatData = array();
		$formatData['id'] = $format->id;
		$formatData['nom'] = $format->nom;
		$formatData['prix'] = $format->prix;
		$formatData['temps_preparation'] = $format->temps_preparation;
		$retour['restaurant']['menu']['formats'][] = $formatData;
	}
	
	$retour['restaurant']['menu']['formules'] = array();
	
	foreach ($menu->formules as $formule) {
		$formuleData = array();
		$formuleData['id'] = $formule->id;
		$formuleData['nom'] = $formule->nom;
		
		$formuleData['categories'] = array();
		
		foreach ($formule->categories as $categorie) {
			$categorieData = array();
			$categorieData['id'] = $categorie->id;
			$categorieData['nom'] = $categorie->nom;
			$categorieData['quantite'] = $categorie->quantite;
			
			$categorieData['contenus'] = array();
			
			foreach ($categorie->contenus as $contenu) {
				$contenuData = array();
				$contenuData['id'] = $contenu->id;
				$contenuData['obligatoire'] = $contenu->obligatoire;
				$contenuData['limite_supplement'] = $contenu->limite_supplement;
				$contenuData['commentaire'] = $contenu->commentaire;
				$contenuData['carte'] = array();
				
				$contenuData['carte']['id'] = $contenu->carte->id;
				$contenuData['carte']['nom'] = $contenu->carte->nom;
				$contenuData['carte']['commentaire'] = $contenu->carte->commentaire;
				$contenuData['carte']['limite_supplement'] = $contenu->carte->limite_supplement;
				$contenuData['carte']['logo'] = $contenu->carte->logo;
				
				$contenuData['carte']['accompagnements'] = array();
				
				foreach ($contenu->carte->accompagnements as $accompagnement) {
					$accompagnementData = array();
					$accompagnementData['id'] = $accompagnement->id;
					$accompagnementData['limite'] = $accompagnement->limite;
					$accompagnementData['carte'] = array();
					
					foreach ($accompagnement->cartes AS $accompagnementCarte) {
						$accompagnementCarteData = array();
						$accompagnementCarteData['id'] = $accompagnementCarte->id;
						$accompagnementCarteData['nom'] = $accompagnementCarte->nom;
						
						$accompagnementData['carte'][] = $accompagnementCarteData;
					}
					$contenuData['carte']['accompagnements'][] = $accompagnementData;
				}
				
				$contenuData['carte']['supplements'] = array();
				
				foreach ($contenu->carte->supplements as $supplement) {
					$supplementData = array();
					$supplementData['id'] = $supplement->id;
					$supplementData['nom'] = $supplement->nom;
					$supplementData['prix'] = $supplement->prix;
					$supplementData['commentaire'] = $supplement->commentaire;
					
					$contenuData['carte']['supplements'][] = $supplementData;
				}
				$categorieData['contenus'][] = $contenuData;
			}
			
			$formuleData['categories'][] = $categorieData;
		}
		
		$retour['restaurant']['menu']['formules'][] = $formuleData;
	}
	
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>