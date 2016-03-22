<?php
	
	$dom = new DOMDocument();
	$menuDom = $dom->createElement("menu");
	$dom->appendChild($menuDom);
	$menuDom->setAttribute("id", $menu["id"]);
	$nodeNom = $dom->createElement("nom");
	$texteNom = $dom->createTextNode($menu["nom"]);
	$nodeNom->appendChild($texteNom);
	$menuDom->appendChild($nodeNom);
	$nodePrix = $dom->createElement("prix");
	$textePrix = $dom->createTextNode($menu["prix"]);
	$nodePrix->appendChild($textePrix);
	$menuDom->appendChild($nodePrix);
	$nodePreparation = $dom->createElement("preparation");
	$textePreparation = $dom->createTextNode($menu["preparation"]);
	$nodePreparation->appendChild($textePreparation);
	$menuDom->appendChild($nodePreparation);
	$nodeCommentaire = $dom->createElement("commentaire");
	$texteCommentaire = $dom->createTextNode($menu["commentaire"]);
	$nodeCommentaire->appendChild($texteCommentaire);
	$menuDom->appendChild($nodeCommentaire);
	
	$categoriesDom = $dom->createElement("categories");
	$categories = $menu["categories"];
	foreach ($categories as $id_categorie => $categorie) {
		$categorieDom = $dom->createElement("categorie");
		$categorieDom->setAttribute("id", $categorie["id"]);
		$categorieDom->setAttribute("id_categorie", $id_categorie);
		$nodeNom = $dom->createElement("nom");
		$texteNom = $dom->createTextNode($categorie["nom"]);
		$nodeNom->appendChild($texteNom);
		$categorieDom->appendChild($nodeNom);
		$nodeQuantite = $dom->createElement("quantite");
		$texteQuantite = $dom->createTextNode($categorie["quantite"]);
		$nodeQuantite->appendChild($texteQuantite);
		$categorieDom->appendChild($nodeQuantite);
		$categoriesDom->appendChild($categorieDom);
		
		$contenusDom = $dom->createElement("contenus");
		$contenus = $categorie["contenus"];
		foreach ($contenus as $contenu) {
			$contenuDom = $dom->createElement("contenu");
			$contenuDom->setAttribute("id", $contenu["id"]);
			$nodeNom = $dom->createElement("nom");
			$texteNom = $dom->createTextNode($contenu["nom"]);
			$nodeNom->appendChild($texteNom);
			$contenuDom->appendChild($nodeNom);
			$nodeObligatoire = $dom->createElement("obligatoire");
			$texteObligatoire = $dom->createTextNode($contenu["obligatoire"]);
			$nodeObligatoire->appendChild($texteObligatoire);
			$contenuDom->appendChild($nodeObligatoire);
			$nodeCommentaire = $dom->createElement("commentaire");
			$texteCommentaire = $dom->createTextNode($menu["commentaire"]);
			$nodeCommentaire->appendChild($texteCommentaire);
			$contenuDom->appendChild($nodeCommentaire);
			
			$contenusDom->appendChild($contenuDom);
		}
		$categorieDom->appendChild($contenusDom);
	}
	$menuDom->appendChild($categoriesDom);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>