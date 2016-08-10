<?php
 
    include_once '../config.php';
	include_once ROOT_PATH."function.php";
	
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Database.php";
	
	$today = date('Y-m-d');
	
	$directory = ROOT_PATH.'files/dump/'.$today;
	
	$filename = 'dump.sql';
	
	if (file_exists($directory.'/'.$filename)) {
		$indice = 1;
		$filename = 'dump-'.$indice.'.sql';
		while (file_exists($directory.'/'.$filename)) {
			$indice++;
			$filename = 'dump-'.$indice.'.sql';
		}
	}
	
	$fullPath = $directory.'/'.$filename;
	
	$tables = array(
		"days",
		"tags",
		"certificats",
		"restaurants",
		"restaurant_virement",
		"restaurant_tag",
		"restaurant_certificat",
		"restaurant_horaires",
		"restaurant_format",
		"restaurant_formule",
		"restaurant_categorie",
		"restaurant_option",
		"restaurant_option_value",
		"supplements",
		"carte",
		"carte_format",
		"carte_disponibilite",
		"carte_option",
		"carte_supplement",
		"carte_accompagnement",
		"carte_accompagnement_contenu",
		"menus",
		"menu_format",
		"menu_disponibilite",
		"menu_formule",
		"menu_categorie",
		"menu_contenu",
		/*"entreprises",
		"entreprise_site",
		"entreprise_groupe",*/
		"users",
		/*"user_session",*/
		"user_client",
		"user_client_information",
		"user_client_premium",
		"user_livreur",
		"user_livreur_dispo",
		/*"user_livreur_position",*/
		"user_livreur_virement",
		"user_restaurant",
		/*"user_entreprise",*/
		"distance_livreur_resto",
		"update_distance_dispo",
		"update_distance_restaurant",
		"code_promo",
		"code_promo_restaurant",
		"code_promo_user",
		"panier",
		"panier_menu",
		"panier_menu_contenu",
		"panier_menu_supplement",
		"panier_menu_accompagnement",
		"panier_carte",
		"panier_carte_option",
		"panier_carte_supplement",
		"panier_carte_accompagnement",
		"prix_livraison",
		"pre_commande",
		"pre_commande_menu",
		"pre_commande_menu_contenu",
		"pre_commande_menu_supplement",
		"pre_commande_menu_accompagnement",
		"pre_commande_carte",
		"pre_commande_carte_supplement",
		"pre_commande_carte_option",
		"pre_commande_carte_accompagnement",
		"commande",
		"commande_menu",
		"commande_menu_contenu",
		"commande_menu_supplement",
		"commande_menu_accompagnement",
		"commande_carte",
		"commande_carte_supplement",
		"commande_carte_option",
		"commande_carte_accompagnement",
		"chat_commande",
		"commande_history",
		"commande_menu_history",
		"commande_menu_contenu_history",
		"commande_menu_supplement_history",
		"commande_menu_accompagnement_history",
		"commande_carte_history",
		"commande_carte_supplement_history",
		"commande_carte_accompagnement_history",
		"modifications",
		"modifications_history",
		"mails",
		"recherches",
		"recherche_detail"
	);
	
	$modelDatabase = new Model_Database();
	$output = $modelDatabase->dump($tables);
	
	$dirname = dirname($fullPath);
	if (!is_dir($dirname)) {
		mkdir($dirname, 0755, true);
	}
	
	$dumpfile = fopen($fullPath, "a");
	fwrite($dumpfile, $output);
	fclose($dumpfile);