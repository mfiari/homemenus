<?php
	
	$retour = array();
	
	$retour['restaurant'] = array();
	
	$retour['restaurant']['id'] = $restaurant->id;
	$retour['restaurant']['nom'] = utf8_encode($restaurant->nom);
	$retour['restaurant']['code_postal'] = $restaurant->code_postal;
	$retour['restaurant']['ville'] = utf8_encode($restaurant->ville);
	$retour['restaurant']['livreur'] = $has_livreur_dispo;
	
	$retour['restaurant']['menu'] = array();
	
	$retour['restaurant']['menu']['id'] = $menu->id;
	$retour['restaurant']['menu']['nom'] = utf8_encode($menu->nom);
	$retour['restaurant']['menu']['commentaire'] = utf8_encode($menu->commentaire);
	
	$retour['restaurant']['menu']['formats'] = array();
	
	foreach ($menu->formats as $format) {
		$formatData = array();
		$formatData['id'] = $format->id;
		$formatData['nom'] = utf8_encode($format->nom);
		$formatData['prix'] = $format->prix;
		$formatData['temps_preparation'] = $format->temps_preparation;
		$retour['restaurant']['menu']['formats'][] = $formatData;
	}
	
	$retour['restaurant']['menu']['formules'] = array();
	
	foreach ($menu->formules as $formule) {
		$formuleData = array();
		$formuleData['id'] = $formule->id;
		$formuleData['nom'] = utf8_encode($formule->nom);
		
		$formuleData['categories'] = array();
		
		foreach ($formule->categories as $categorie) {
			$categorieData = array();
			$categorieData['id'] = $categorie->id;
			$categorieData['nom'] = utf8_encode($categorie->nom);
			$categorieData['quantite'] = $categorie->quantite;
			
			$categorieData['contenus'] = array();
			
			foreach ($categorie->contenus as $contenu) {
				$contenuData = array();
				$contenuData['id'] = $contenu->id;
				$contenuData['obligatoire'] = $contenu->obligatoire;
				$contenuData['limite_supplement'] = $contenu->limite_supplement;
				$contenuData['commentaire'] = utf8_encode($contenu->commentaire);
				$contenuData['supplement'] = $contenu->supplement;
				$contenuData['carte'] = array();
				
				$contenuData['carte']['id'] = $contenu->carte->id;
				$contenuData['carte']['nom'] = utf8_encode($contenu->carte->nom);
				$contenuData['carte']['commentaire'] = utf8_encode($contenu->carte->commentaire);
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
						$accompagnementCarteData['nom'] = utf8_encode($accompagnementCarte->nom);
						
						$accompagnementData['carte'][] = $accompagnementCarteData;
					}
					$contenuData['carte']['accompagnements'][] = $accompagnementData;
				}
				
				$contenuData['carte']['supplements'] = array();
				
				foreach ($contenu->carte->supplements as $supplement) {
					$supplementData = array();
					$supplementData['id'] = $supplement->id;
					$supplementData['nom'] = utf8_encode($supplement->nom);
					$supplementData['prix'] = $supplement->prix;
					$supplementData['commentaire'] = utf8_encode($supplement->commentaire);
					
					$contenuData['carte']['supplements'][] = $supplementData;
				}
				
				$contenuData['carte']['options'] = array();
				
				foreach ($contenu->carte->options as $option) {
					$optionData = array();
					$optionData['id'] = $option->id;
					$optionData['nom'] = utf8_encode($option->nom);
					$optionData['values'] = array();
					
					foreach ($option->values as $value) {
						$valueData = array();
						$valueData['id'] = $value->id;
						$valueData['nom'] = utf8_encode($value->nom);
						$optionData['values'][] = $valueData;
					}
					
					$contenuData['carte']['options'][] = $optionData;
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