<?php

require(WEBSITE_PATH.'res/lib/fpdf/fpdf.php');

class PDF extends FPDF {
	
	private $title;
	private $logo;
	
	// En-tête
	function Header() {
		// Logo
		$this->Image(WEBSITE_PATH.'res/img/logo_mail.png',10,6,30);
		// Police Arial gras 15
		$this->SetFont('Arial','B',15);
		// Décalage à droite
		$this->Cell(70);
		// Titre
		$this->Cell(50,10,$this->title,1,0,'C');
		// Décalage à droite
		$this->Cell(30);
		if ($this->logo) {
			$this->Image(WEBSITE_PATH.$this->logo,160,6,30);
		}
		// Saut de ligne
		$this->Ln(20);
	}
	
	// Pied de page
	function Footer()
	{
		// Positionnement à 1,5 cm du bas
		$this->SetY(-15);
		// Police Arial italique 8
		$this->SetFont('Arial','I',8);
		// Numéro de page
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	public function generateFacturePremium ($date_debut, $date_fin, $prix) {
		$this->title = "Souscription au compte premium";
		
		$this->AliasNbPages();
		$this->AddPage();
		
		$width = 70;
		// Police Arial gras 12
		$this->SetFont('Arial','B',12);
		
		$this->Cell($width,10,'Date de souscription',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,$date_debut,0,1);
		
		$this->Cell($width,10,'Date de fin',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,$date_fin,0,1);
		
		$this->Cell($width,10,'Total',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,$prix,0,1);
	}
	
	public function generateFactureClient ($commande) {
		
		$this->title = "Commande #".$commande->id;
		
		$this->AliasNbPages();
		$this->AddPage();
		
		$width = 70;
		// Police Arial gras 12
		$this->SetFont('Arial','B',12);
		
		$this->Cell($width,10,'Restaurant',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,utf8_encode($commande->restaurant->nom),0,1);
		
		$this->Cell($width,10,'Date de commande',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,formatTimestampToDateHeure($commande->date_commande),0,1);
		
		// Saut de ligne
		$this->Ln(20);
		
		$width = 80;
		$this->SetFont('Times','',12);
		
		$totalPrix = 0;
		$totalQte = 0;
		foreach ($commande->menus as $menu) {
			$name = utf8_encode($menu->nom);
			if (count($menu->formats) == 1 && $menu->formats[0]->nom != "") {
				$name .= ' ('.utf8_encode($menu->formats[0]->nom).')';
			}
			$this->Cell($width,10,$name,0,0);
			$this->Cell($width,10,' X '.$menu->quantite,0,0);
			$this->Cell($width,10,$menu->prix.' '.chr(128),0,1);
			foreach ($menu->formules as $formule) {
				$this->Cell(0,10,utf8_encode($formule->nom),0,1);
				foreach ($formule->categories as $categorie) {
					$this->Cell(0,10,utf8_encode($categorie->nom),0,1);
					foreach ($categorie->contenus as $contenu) {
						$this->Cell(0,10,utf8_encode($contenu->nom),0,1);
					}
				}
			}
			$totalQte += $menu->quantite;
			$totalPrix += $menu->prix;
			$this->Cell(0,5,'','B',1);
		}
		foreach ($commande->cartes as $carte) {
			$name = utf8_encode($carte->nom);
			if (count($carte->formats) == 1 && $carte->formats[0]->nom != "") {
				$name .= ' ('.utf8_encode($carte->formats[0]->nom).')';
			}
			$this->Cell($width,10,$name,0,0);
			$this->Cell($width,10,' X '.$carte->quantite,0,0);
			$this->Cell($width,10,$carte->prix.' '.chr(128),0,1);
			if (count($carte->supplements) > 0) {
				$this->Cell(0,10,'Suppléments : ',0,1);
				foreach ($carte->supplements as $supplement) {
					$this->Cell(0,10,$supplement->nom,0,1);
				}
			}
			$this->Cell(0,5,'','B',1);
			$totalQte += $carte->quantite;
			$totalPrix += $carte->prix;
		}
		$this->Cell($width,10,'Prix de livraison :',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,$commande->prix_livraison.' '.chr(128),0,1);
		$totalPrix += $commande->prix_livraison;
		$this->Cell(0,5,'','B',1);
		$this->Cell($width,10,'Total :',0,0);
		$this->Cell($width,10,$totalQte,0,0);
		$this->Cell($width,10,$totalPrix.' '.chr(128),0,1);
	}
	
	public function generateFactureRestaurant ($commande) {
		
		$this->title = "Commande #".$commande->id;
		
		$this->logo = getLogoRestaurant($commande->restaurant->id);
		
		$this->AliasNbPages();
		$this->AddPage();
		
		// Saut de ligne
		$this->Ln(5);
		
		$width = 70;
		// Police Arial gras 12
		$this->SetFont('Arial','B',12);
		
		$this->Cell($width,10,'Date de commande',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,formatTimestampToDateHeure($commande->date_commande),0,1);
		
		// Saut de ligne
		$this->Ln(5);
		
		$this->Cell($width,10,'Livreur',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,$commande->livreur->prenom,0,1);
		
		// Saut de ligne
		$this->Ln(20);
		
		$width = 80;
		$this->SetFont('Times','',12);
		
		$totalPrix = 0;
		$totalQte = 0;
		foreach ($commande->menus as $menu) {
			$name = $menu->nom;
			if (count($menu->formats) == 1 && $menu->formats[0]->nom != "") {
				$name .= ' ('.$menu->formats[0]->nom.')';
			}
			$this->Cell($width,10,$name,0,0);
			$this->Cell($width,10,' X '.$menu->quantite,0,0);
			$this->Cell($width,10,$menu->prix.' '.chr(128),0,1);
			foreach ($menu->formules as $formule) {
				foreach ($formule->categories as $categorie) {
					$this->Cell(20,10,'',0,0);
					$this->Cell($width,10,$categorie->nom.' : ',0,0);
					foreach ($categorie->contenus as $contenu) {
						$this->Cell(0,10,$contenu->nom,0,1);
					}
				}
			}
			$totalQte += $menu->quantite;
			$totalPrix += $menu->prix;
			$this->Cell(0,5,'','B',1);
		}
		foreach ($commande->cartes as $carte) {
			$name = $carte->nom;
			if (count($carte->formats) == 1 && $carte->formats[0]->nom != "") {
				$name .= ' ('.$carte->formats[0]->nom.')';
			}
			$this->Cell($width,10,$name,0,0);
			$this->Cell($width,10,' X '.$carte->quantite,0,0);
			$this->Cell($width,10,$carte->prix.' '.chr(128),0,1);
			if (count($carte->supplements) > 0) {
				$this->Cell(20,10,'',0,0);
				$this->Cell(0,10,'Supplements : ',0,1);
				foreach ($carte->supplements as $supplement) {
					$this->Cell(40,10,'',0,0);
					$this->Cell(0,10,$supplement->nom,0,1);
				}
			}
			if (count($carte->options) > 0) {
				foreach ($carte->options as $option) {
					foreach ($option->values as $value) {
						$this->Cell(20,10,'',0,0);
						$this->Cell(0,10,utf8_encode($option->nom).' : '.utf8_encode($value->nom),0,1);
					}
				}
			}
			if (count($carte->accompagnements) > 0) {
				$this->Cell(20,10,'',0,0);
				$this->Cell(0,10,utf8_encode('accompagnements : '),0,1);
				foreach ($carte->accompagnements as $accompagnement) {
					foreach ($accompagnement->cartes as $carteAccompagnement) {
						$this->Cell(40,10,'',0,0);
						$this->Cell(0,10,$carteAccompagnement->nom,0,1);
					}
				}
			}
			$this->Cell(0,5,'','B',1);
			$totalQte += $carte->quantite;
			$totalPrix += $carte->prix;
		}
		
		$pourcentage = $commande->restaurant->pourcentage;
		
		$this->Cell($width,10,'Total commande :',0,0);
		$this->Cell($width,10,$totalQte,0,0);
		$this->Cell($width,10,$totalPrix.' '.chr(128),0,1);
		
		/*$this->Cell($width,10,'Part HoMe Menus :',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,($totalPrix * $pourcentage / 100).' '.chr(128),0,1);
		
		$this->Cell($width,10,'Total gain:',0,0);
		$this->Cell($width,10,'',0,0);
		$this->Cell($width,10,($totalPrix - ($totalPrix * $pourcentage / 100)).' '.chr(128).' TTC',0,1);*/
	}
	
	public function generateHoraireLivreur ($livreur) {
		$this->title = "Horaires";
		
		$this->AliasNbPages();
		$this->AddPage();
		
		$width = 70;
		// Police Arial gras 12
		$this->SetFont('Arial','B',12);
		
		//En-tête
		$this->Cell(80, 7, utf8_decode('Adresse de départ'), 1, 0, 'C');
		$this->Cell(35, 7, utf8_decode('Périmètre'), 1, 0, 'C');
		$this->Cell(35, 7, utf8_decode('Véhicule'), 1, 0, 'C');
		$this->Cell(35, 7, 'Horaires', 1, 0, 'C');
		$this->Ln();
		
		//Données
		foreach($livreur->dispos as $dispo) {
			$this->Cell(80, 6, utf8_decode($dispo->rue).', '.$dispo->code_postal.' '.utf8_decode($dispo->ville), 1);
			$this->Cell(35, 6, $dispo->perimetre.' km', 1);
			$this->Cell(35, 6, $dispo->vehicule, 1);
			$this->Cell(35, 6, 'De '.$dispo->heure_debut.'h'.$dispo->minute_debut.utf8_decode(' à ').$dispo->heure_fin.'h'.$dispo->minute_fin, 1);
			$this->Ln();
		}
		
		//Trait de terminaison
		$this->Cell(4,0,'','T');
		
	}
	
	public function render ($dest = "I", $filename = "doc.pdf") {
		$this->Output($dest, $filename);
	}

}