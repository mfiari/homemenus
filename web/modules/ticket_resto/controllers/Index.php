<?php

include_once MODEL_PATH."TransfertPaiement.php";
include_once MODEL_PATH."CommandeHistory.php";
include_once MODEL_PATH."Restaurant.php";
include_once MODEL_PATH."Horaire.php";

class Controller_Index extends Controller_TicketResto_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "exportExcel" :
					$this->exportExcel($request);
					break;
				case "exportContrat" :
					$this->exportContrat($request);
					break;
				case "compte" :
					$this->compte($request);
					break;
				case "modify_password" :
					$this->modify_password($request);
					break;
				case "logout" :
					$this->logout($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		if (isset($_GET['date_debut'])) {
			$request->date_debut = $_GET['date_debut'];
		} else {
			$request->date_debut = '01/'.date('m').'/'.date('Y');
		}
		$dateDebut = datepickerToDatetime($request->date_debut);
		
		if (isset($_GET['date_fin'])) {
			$request->date_fin = $_GET['date_fin'];
		} else {
			$request->date_fin = date('d').'/'.date('m').'/'.date('Y');
		}
		$dateFin = datepickerToDatetime($request->date_fin);
		
		$modelTransfertPaiement = new Model_TransfertPaiement(true, $request->dbConnector);
		$request->result = $modelTransfertPaiement->getTitreRestaurant($dateDebut, $dateFin);
		
		$modelCommandeHistory = new Model_Commande_History(true, $request->dbConnector);
		$request->restaurants = $modelCommandeHistory->getTitreRestaurant($dateDebut, $dateFin);
		
		$request->title = "Ticket restaurant";
		$request->vue = $this->render("index.php");
	}
	
	public function exportExcel ($request) {
		if (isset($_GET['date_debut'])) {
			$request->date_debut = $_GET['date_debut'];
		} else {
			$request->date_debut = '01/'.date('m').'/'.date('Y');
		}
		$dateDebut = datepickerToDatetime($request->date_debut);
		
		if (isset($_GET['date_fin'])) {
			$request->date_fin = $_GET['date_fin'];
		} else {
			$request->date_fin = date('d').'/'.date('m').'/'.date('Y');
		}
		$dateFin = datepickerToDatetime($request->date_fin);
		
		$modelTransfertPaiement = new Model_TransfertPaiement(true, $request->dbConnector);
		$result = $modelTransfertPaiement->getTitreRestaurant($dateDebut, $dateFin);
		
		$modelCommandeHistory = new Model_Commande_History(true, $request->dbConnector);
		$restaurants = $modelCommandeHistory->getTitreRestaurant($dateDebut, $dateFin);
		
		require_once WEBSITE_PATH.'/res/lib/PHPExcel/PHPExcel.php';
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()
			->setCreator("HoMe Menus")
			->setLastModifiedBy("HoMe Menus")
			->setTitle("Export titre restaurant HoMr Menus du ".$request->date_debut." au ".$request->date_fin)
			->setSubject("Export titre restaurant HoMr Menus")
			->setDescription("Export titre restaurant HoMr Menus");
			
		$activeSheet = $objPHPExcel->setActiveSheetIndex(0);
			
		
		$activeSheet->setCellValue('B2', 'Nombre de ticket restaurant :')
            ->setCellValue('C2', $result["quantite"] == '' ? '0' : $result["quantite"])
            ->setCellValue('B3', 'Montant total :')
            ->setCellValue('C3', $result["montant"] == '' ? '0,00 €' : $result["montant"]);
			
		$activeSheet->setCellValue('B5', 'Mois :')
            ->setCellValue('C5', 'Restaurant')
            ->setCellValue('D5', 'Quantité')
            ->setCellValue('E5', 'Montant');
		
		$quantiteTotal = 0; $prixTotal = 0;
		$ligne = 6;
		foreach ($restaurants as $restaurant) {
			if ($restaurant['month'] == '' || $restaurant['year'] == '') {
				$activeSheet->setCellValue('B'.$ligne, getMonthByIndex(date('m')).' '.date('Y'));
			} else {
				$activeSheet->setCellValue('B'.$ligne, getMonthByIndex($restaurant['month']).' '.$restaurant['year']);
			}
			$activeSheet->setCellValue('C'.$ligne, utf8_encode($restaurant['nom']).' ('.$restaurant['siret'].')');
			$activeSheet->setCellValue('D'.$ligne, $restaurant['quantite_total']);
			$activeSheet->setCellValue('E'.$ligne, number_format($restaurant['prix_total'], 2, ',', ' ').' €');
			$quantiteTotal += $restaurant['quantite_total'];
			$prixTotal += $restaurant['prix_total'];
			$ligne++;
		}
		$activeSheet->setCellValue('D'.$ligne, $quantiteTotal);
		$activeSheet->setCellValue('E'.$ligne, number_format($prixTotal, 2, ',', ' ').' €');
		
		$filename = 'titre_restaurant_home_menus_'.date('Y').date('m').date('d').date('H').date('i').date('s').'.xlsx';
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(ROOT_PATH.'files/titre_resto/'.$filename);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
			
	}
	
	public function exportContrat ($request) {
		if (isset($_GET['id_restaurant'])) {
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$modelRestaurant->id = $_GET['id_restaurant'];
			$modelRestaurant->getOne();
			getContratRestaurant($modelRestaurant);
		} else {
			$this->redirect();
		}
	}
	
	public function compte ($request) {
		if ($request->_auth) {
			$request->title = "Compte";
			if ($request->request_method == "POST") {
				$modelUser = new Model_User(true, $request->dbConnector);
				$modelUser->id = $request->_auth->id;
				$oldUser = $modelUser->getById();
				if (isset($_POST["nom"]) && trim($_POST["nom"]) != '') {
					$modelUser->nom = $_POST["nom"];
				}
				if (isset($_POST["prenom"]) && trim($_POST["prenom"]) != '') {
					$modelUser->prenom = $_POST["prenom"];
				}
				if (isset($_POST["login"]) && trim($_POST["login"]) != '') {
					$modelUser->login = $_POST["login"];
				}
				if (isset($_POST["email"]) && trim($_POST["email"]) != '') {
					$modelUser->email = $_POST["email"];
				}
				if ($modelUser->save()) {
					$request->user = $modelUser;
					$request->successMessage = "Votre compte a bien été modifié.";
				} else {
					$request->errorMessage = array("Une erreur est survenue lors de la modification des données. Veuillez réessayer ultérieurement.");
					$request->user = $oldUser;
				}
			} else {
				$modelUser = new Model_User(true, $request->dbConnector);
				$modelUser->id = $request->_auth->id;
				$request->user = $modelUser->getById();
			}
			$request->vue = $this->render("compte.php");
		} else {
			$this->redirect();
		}
	}
	
	public function modify_password ($request) {
		$modelUser = new Model_User(true, $request->dbConnector);
		$modelUser->id = $request->_auth->id;
		if ($request->request_method == "POST") {
			$errors = array();
			$oldPassword = $_POST['old_password'];
			$newPassword = $_POST['new_password'];
			$confirmPassword = $_POST['confirm_password'];
			$errors = array();
			if ($newPassword != $confirmPassword) {
				$errors["DIFFERENT_PASSWORD"] = "Les champs mot de passe et confirmations sont différents.";
			} else {
				
				if ($modelUser->getByLoginAndPassword($request->_auth->login, $oldPassword) === false) {
					$errors["WRONG_PASSWORD"] = "Le mot de passe saisie est incorrect";
				} else {
					if ($modelUser->modifyPassword($newPassword) === false) {
						$errors["MODIFY_ERROR"] = "Erreur lors de la modification du mot de passe, veuillez réessayer";
					} else {
						$request->modifyPasswordSuccess = true;
					}
				}
			}
			if (count($errors) > 0) {
				$request->errorMessage = $errors;
			} else {
				$request->successMessage = "Votre mot de passe a bien été modifié.";
			}
		}
		$request->user = $modelUser->getById();
		$request->vue = $this->render("compte.php");
	}
	
	public function logout ($request) {
		$user = new Model_User(true, $request->dbConnector);
		$user->id = $request->_auth->id;
		if ($user->logout()) {
			session_destroy();
		}
		$this->redirect();
	}
}