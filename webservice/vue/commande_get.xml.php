<?php
	
	$dom = new DOMDocument();
	$commandesDom = $dom->createElement("commandes");
	$dom->appendChild($commandesDom);
	$nbResult = 0;
	foreach ($retour as $id_commande => $commande) {
		$commandeDom = $dom->createElement("commande");
		$commandesDom->appendChild($commandeDom);
		$commandeDom->setAttribute("id", $id_commande);
		$nodeDate = $dom->createElement("date_commande");
		$texteDate = $dom->createTextNode($commande["commande"]["date_commande"]);
		$nodeDate->appendChild($texteDate);
		$commandeDom->appendChild($nodeDate);
		
		$user = $commande["user"];
		$userDom = $dom->createElement("user");
		$commandeDom->appendChild($userDom);
		$userDom->setAttribute("id", $user["id"]);
		$nodeNom = $dom->createElement("nom");
		$texteNom = $dom->createTextNode($user["nom"]);
		$nodeNom->appendChild($texteNom);
		$userDom->appendChild($nodeNom);
		$nodeRue = $dom->createElement("rue");
		$texteRue = $dom->createTextNode($user["rue"]);
		$nodeRue->appendChild($texteRue);
		$userDom->appendChild($nodeRue);
		$nodeVille = $dom->createElement("ville");
		$texteVille = $dom->createTextNode($user["ville"]);
		$nodeVille->appendChild($texteVille);
		$userDom->appendChild($nodeVille);
		$nodeCodePostal = $dom->createElement("code_postal");
		$texteCodePostal = $dom->createTextNode($user["code_postal"]);
		$nodeCodePostal->appendChild($texteCodePostal);
		$userDom->appendChild($nodeCodePostal);
		$nodeTelephone = $dom->createElement("telephone");
		$texteTelephone = $dom->createTextNode($user["telephone"]);
		$nodeTelephone->appendChild($texteTelephone);
		$userDom->appendChild($nodeTelephone);
		
		$menus = $commande["commande"]["menus"];
		$menusDom = $dom->createElement("menus");
		foreach ($menus as $id_menu => $menu) {
			$menuDom = $dom->createElement("menu");
			$menuDom->setAttribute("id", $id_menu);
			$nodeNom = $dom->createElement("nom");
			$texteNom = $dom->createTextNode($menu["nom"]);
			$nodeNom->appendChild($texteNom);
			$menuDom->appendChild($nodeNom);
			$nodeQuantite = $dom->createElement("quantite");
			$texteQuantite = $dom->createTextNode($menu["quantite"]);
			$nodeQuantite->appendChild($texteQuantite);
			$menuDom->appendChild($nodeQuantite);
			$nodePrix = $dom->createElement("prix");
			$textePrix = $dom->createTextNode($menu["prix"]);
			$nodePrix->appendChild($textePrix);
			$menuDom->appendChild($nodePrix);
			$menusDom->appendChild($menuDom);
			
			$restaurant = $menu["restaurant"];
			$restaurantDom = $dom->createElement("restaurant");
			$restaurantDom->setAttribute("id", $restaurant["id"]);
			$nodeNom = $dom->createElement("nom");
			$texteNom = $dom->createTextNode($restaurant["nom"]);
			$nodeNom->appendChild($texteNom);
			$restaurantDom->appendChild($nodeNom);
			$menuDom->appendChild($restaurantDom);
			
			$categories = $menu["categories"];
			$categoriesDom = $dom->createElement("categories");
			foreach ($categories as $id_categorie => $categorie) {
				$categorieDom = $dom->createElement("categorie");
				$categorieDom->setAttribute("id", $id_categorie);
				$nodeNom = $dom->createElement("nom");
				$texteNom = $dom->createTextNode($categorie["nom"]);
				$nodeNom->appendChild($texteNom);
				$categorieDom->appendChild($nodeNom);
				$categoriesDom->appendChild($categorieDom);
				
				$contenus = $categorie["contenus"];
				$contenusDom = $dom->createElement("contenus");
				foreach ($contenus as $id_contenu => $contenu) {
					$contenuDom = $dom->createElement("contenu");
					$contenuDom->setAttribute("id", $id_contenu);
					$nodeNom = $dom->createElement("nom");
					$texteNom = $dom->createTextNode($contenu["nom"]);
					$nodeNom->appendChild($texteNom);
					$contenuDom->appendChild($nodeNom);
					$contenusDom->appendChild($contenuDom);
				}
				$categorieDom->appendChild($contenusDom);
			}
			$menuDom->appendChild($categoriesDom);
		}
		$commandeDom->appendChild($menusDom);
		$nbResult++;
	}
	$commandesDom->setAttribute("nbResult", $nbResult);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>