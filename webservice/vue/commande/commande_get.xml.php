<?php
	
	$dom = new DOMDocument();
	$commande = $dom->createElement("commande");
	$commande->setAttribute("id", $result->id);
	addTextNode ($dom, $commande, "rue", utf8_encode($result->rue));
	addTextNode ($dom, $commande, "ville", utf8_encode($result->ville));
	addTextNode ($dom, $commande, "code_postal", $result->code_postal);
	addTextNode ($dom, $commande, "telephone", $result->telephone);
	addTextNode ($dom, $commande, "heure_souhaite", $result->heure_souhaite);
	addTextNode ($dom, $commande, "minute_souhaite", $result->minute_souhaite);
	addTextNode ($dom, $commande, "heure_restaurant", $result->heure_restaurant);
	addTextNode ($dom, $commande, "minute_restaurant", $result->minute_restaurant);
	addTextNode ($dom, $commande, "etape", $result->etape);
	addTextNode ($dom, $commande, "prix", $result->prix);
	addTextNode ($dom, $commande, "date_validation_restaurant", $result->date_validation_restaurant);
	addTextNode ($dom, $commande, "date_fin_preparation_restaurant", $result->date_fin_preparation_restaurant);
	
	if ($result->client) {
		$client = $dom->createElement("client");
		$client->setAttribute("id", $result->client->id);
		addTextNode ($dom, $client, "nom", utf8_encode($result->client->nom));
		addTextNode ($dom, $client, "prenom", utf8_encode($result->client->prenom));
		$commande->appendChild($client);
	}
	
	if ($result->restaurant) {
		$restaurant = $dom->createElement("restaurant");
		$restaurant->setAttribute("id", $result->restaurant->id);
		addTextNode ($dom, $restaurant, "nom", utf8_encode($result->restaurant->nom));
		addTextNode ($dom, $restaurant, "rue", utf8_encode($result->restaurant->rue));
		addTextNode ($dom, $restaurant, "ville", utf8_encode($result->restaurant->ville));
		addTextNode ($dom, $restaurant, "code_postal", $result->restaurant->code_postal);
		addTextNode ($dom, $restaurant, "telephone", $result->restaurant->telephone);
		
		$commande->appendChild($restaurant);
	}
	
	if ($result->livreur && $result->livreur->id != '') {
		$livreur = $dom->createElement("livreur");
		$livreur->setAttribute("id", $result->livreur->id);
		addTextNode ($dom, $livreur, "nom", utf8_encode($result->livreur->nom));
		addTextNode ($dom, $livreur, "prenom", utf8_encode($result->livreur->prenom));
		
		$commande->appendChild($livreur);
	}
	
	$menus = $dom->createElement("menus");
	foreach ($result->menus as $menu) {
		$nodeMenu = $dom->createElement("menu");
		$nodeMenu->setAttribute("id", $menu->id);
		addTextNode ($dom, $nodeMenu, "nom", utf8_encode($menu->nom));
		addTextNode ($dom, $nodeMenu, "quantite", $menu->quantite);
		addTextNode ($dom, $nodeMenu, "prix", $menu->prix);
		
		$formats = $dom->createElement("formats");
		foreach ($menu->formats as $format) {
			$nodeFormat = $dom->createElement("format");
			$nodeFormat->setAttribute("id", $format->id);
			addTextNode ($dom, $nodeFormat, "nom", utf8_encode($format->nom));
			$formats->appendChild($nodeFormat);
		}
		$nodeMenu->appendChild($formats);
		
		$formules = $dom->createElement("formules");
		foreach ($menu->formules as $formule) {
			$nodeFormule = $dom->createElement("formule");
			$nodeFormule->setAttribute("id", $formule->id);
			addTextNode ($dom, $nodeFormule, "nom", utf8_encode($formule->nom));
			
			$categories = $dom->createElement("categories");
			foreach ($formule->categories as $categorie) {
				$nodeCategorie = $dom->createElement("categorie");
				$nodeCategorie->setAttribute("id", $categorie->id);
				addTextNode ($dom, $nodeCategorie, "nom", utf8_encode($categorie->nom));
				
				$contenus = $dom->createElement("contenus");
				foreach ($categorie->contenus as $contenu) {
					$nodeContenu = $dom->createElement("contenu");
					$nodeContenu->setAttribute("id", $contenu->id);
					addTextNode ($dom, $nodeContenu, "nom", utf8_encode($contenu->nom));
					
					$contenus->appendChild($nodeContenu);
				}
				$nodeCategorie->appendChild($contenus);
				$categories->appendChild($nodeCategorie);
			}
			$nodeFormule->appendChild($categories);
			
			$formules->appendChild($nodeFormule);
		}
		$nodeMenu->appendChild($formules);
		
		$menus->appendChild($nodeMenu);
	}
	$commande->appendChild($menus);
	
	$cartes = $dom->createElement("cartes");
	foreach ($result->cartes as $carte) {
		$nodeCarte = $dom->createElement("carte");
		$nodeCarte->setAttribute("id", $carte->id);
		addTextNode ($dom, $nodeCarte, "nom", utf8_encode($carte->nom));
		addTextNode ($dom, $nodeCarte, "prix", $carte->prix);
		addTextNode ($dom, $nodeCarte, "quantite", $carte->quantite);
		
		foreach ($carte->formats as $format) {
			$nodeFormat = $dom->createElement("format");
			$nodeFormat->setAttribute("id", $format->id);
			addTextNode ($dom, $nodeFormat, "nom", utf8_encode($format->nom));
			$nodeCarte->appendChild($nodeFormat);
		}
		
		$nodeOptions = $dom->createElement("options");
		foreach ($carte->options as $option) {
			$nodeOption = $dom->createElement("option");
			$nodeOption->setAttribute("id", $option->id);
			addTextNode ($dom, $nodeOption, "nom", utf8_encode($option->nom));
			$value = $option->values[0];
			addTextNode ($dom, $nodeOption, "value", utf8_encode($value->nom));
			
			$nodeOptions->appendChild($nodeOption);
		}
		$nodeCarte->appendChild($nodeOptions);
		
		$supplements = $dom->createElement("supplements");
		foreach ($carte->supplements as $supplement) {
			$nodeSupplement = $dom->createElement("supplement");
			$nodeSupplement->setAttribute("id", $supplement->id);
			addTextNode ($dom, $nodeSupplement, "nom", utf8_encode($supplement->nom));
			
			$supplements->appendChild($nodeSupplement);
		}
		
		$accompagnements = $dom->createElement("accompagnements");
		foreach ($carte->accompagnements as $accompagnement) {
			$nodeAccompagnement = $dom->createElement("accompagnement");
			$nodeAccompagnement->setAttribute("id", $accompagnement->id);
			foreach ($accompagnement->cartes as $accompagnementCarte) {
				$nodeAccompagnementCarte = $dom->createElement("carte");
				$nodeAccompagnementCarte->setAttribute("id", $accompagnementCarte->id);
				addTextNode ($dom, $nodeAccompagnementCarte, "nom", utf8_encode($accompagnementCarte->nom));
				$nodeAccompagnement->appendChild($nodeAccompagnementCarte);
			}
			$accompagnements->appendChild($nodeAccompagnement);
		}
		$nodeCarte->appendChild($accompagnements);
		
		$cartes->appendChild($nodeCarte);
	}
	$commande->appendChild($cartes);
	
	$dom->appendChild($commande);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>