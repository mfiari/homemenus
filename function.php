<?php

function filterRestaurant ($var) {
	global $user_latitude, $user_longitude, $distanceKm;
	$latituteResto = $var->latitude;
	$longitudeResto = $var->longitude;
	$distance = getDistanceFromLatLonInKm($user_latitude, $user_longitude, $latituteResto, $longitudeResto);
	return $distance <= $distanceKm;
}

function getDistanceFromLatLonInKm($lat1,$lon1,$lat2,$lon2) {
  $radius = 6371; // Radius of the earth in km
  $dLat = deg2rad($lat2-$lat1);  // deg2rad below
  $dLon = deg2rad($lon2-$lon1);
  $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2); 
  $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
  return $radius * $c; // Distance in km
}

function getDistance ($adresse1, $adresse2) {
	
	$url='http://maps.googleapis.com/maps/api/distancematrix/json?origins='.$adresse1.'&destinations='.$adresse2.'&sensor=false';
	$json = file_get_contents($url);
	$details = json_decode($json, TRUE);
	if ($details['rows'][0]['elements'][0]['status'] == 'ZERO_RESULTS') {
		return array(
			'status' => $details['status'],
			'distance' => -1,
			'duration' => -1
		);
	}
	return array(
		'status' => $details['status'],
		'distance' => $details['rows'][0]['elements'][0]['distance']['value'],
		'duration' => $details['rows'][0]['elements'][0]['duration']['value']
	);
}

function array_object_column($array,$column_name) {
	return array_map(function($element) use($column_name){return $element->$column_name;}, $array);
}

function formatTimestampToDateHeure ($timestamp) {
	list($date, $heure) = explode(" ", $timestamp);
	list($year, $month, $day) = explode("-", $date);
	return $day.'/'.$month.'/'.$year.' '.$heure;
}

function formatTimestampToDate ($timestamp) {
	list($year, $month, $day) = explode("-", $timestamp);
	return $day.'/'.$month.'/'.$year;
}

function generateToken ($length = 32) {
	$characters = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
	$chaine = "";
	$nbChar = strlen($characters)-1;
	while (strlen($chaine) < $length) {
		$alea = rand(0, $nbChar);
		$char = $characters[$alea];
		$chaine .= $char;
	}
	return $chaine;
}

function generatePassword ($length = 8, $strong = false) {
	$characters = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
	if ($strong) {
		$characters .= '#*$+=';
	}
	$chaine = "";
	$nbChar = strlen($characters);
	while (strlen($chaine) < $length) {
		$alea = rand(0, $nbChar);
		$char = $characters[$alea];
		$chaine .= $char;
	}
	return $chaine;
}

function writeLog ($type, $texte, $level = LOG_LEVEL_INFO, $message = null) {
	$directory = "log";
	$filename = "log_".date('Y-m-d');
	switch ($type) {
		case SQL_LOG :
		    /* écrit dans le fichier log/sql/log_aaaa-mm-dd */
			$directory = "log/sql/";
			break;
		case VIEW_LOG :
			/* écrit dans le fichier log/view/log_aaaa-mm-dd */
			$directory = "log/view/";
			break;
		case SERVER_LOG :
			/* écrit dans le fichier log/server/log_aaaa-mm-dd */
			$directory = "log/server/";
			break;
		case WS_LOG :
			/* écrit dans le fichier log/ws/log_aaaa-mm-dd */
			$directory = "log/ws/";
			break;
		case CRON_LOG :
			/* écrit dans le fichier log/cron/log_aaaa-mm-dd */
			$directory = "log/cron/";
			break;
		case MAIL_LOG :
			/* écrit dans le fichier log/cron/log_aaaa-mm-dd */
			$directory = "log/mail/";
			break;
	}
	
	$mailMessage = "";
	
	$logfile = fopen(ROOT_PATH.$directory.$filename, "a");
	$debug = debug_backtrace();
	fwrite($logfile, "[BEGIN_LOG]\n");
	fwrite($logfile, "[LEVEL]\n");
	fwrite($logfile, $level."\n");
	fwrite($logfile, "[DATE]\n");
	fwrite($logfile, date('Y-m-d H:i:s')."\n");
	$mailMessage .= 'date : ' .date('Y-m-d H:i:s').'<br /><br />';
	fwrite($logfile, "[FILE]\n");
	fwrite($logfile, $debug[0]['file']."\n");
	$mailMessage .= 'file : '.$debug[0]['file'].'<br /><br />';
	fwrite($logfile, "[FUNCTION]\n");
	fwrite($logfile, $debug[0]['function']."\n");
	$mailMessage .= 'function : '.$debug[0]['function'].'<br /><br />';
	fwrite($logfile, "[LINE]\n");
	fwrite($logfile, $debug[0]['line']."\n");
	$mailMessage .= 'line : '.$debug[0]['line'].'<br /><br />';
	if ($message !== null) {
		fwrite($logfile, "[MESSAGE]\n");
		fwrite($logfile, $message."\n");
		$mailMessage .= 'message : '.$message.'<br /><br />';
	}
	fwrite($logfile, "[TEXT]\n");
	$mailMessage .= 'text : <br />';
	if (is_array($texte)) {
		foreach ($texte as $key => $value) {
			fwrite($logfile, $key. ' => '.$value."\n");
			$mailMessage .= $key. ' => '.$value.'<br />';
		}
		$mailMessage .= '<br />';
	} else {
		fwrite($logfile, $texte."\n");
		$mailMessage .= $texte.'<br /><br />';
	}
	fwrite($logfile, "[END_LOG]\n");
	fclose($logfile);
	if ($level == LOG_LEVEL_ERROR) {
		send_mail ("informatique@homemenus.fr", "Erreur de type ".$type, $mailMessage);
	}
	if ((ENVIRONNEMENT == "DEV" || ENVIRONNEMENT == "TEST") && $level == LOG_LEVEL_ERROR) {
		var_dump($texte);
	}
}

function fatal_error_handler () {
	/*$errfile = "unknown file";
	$errstr  = "shutdown";
	$errno   = E_CORE_ERROR;
	$errline = 0;

	$error = error_get_last();

	if ($error !== NULL) {
		$errno   = $error["type"];
		$errfile = $error["file"];
		$errline = $error["line"];
		$errstr  = $error["message"];
		$mailMessage = format_error($errno, $errstr, $errfile, $errline);
		send_mail ("informatique@homemenus.fr", "Fatal error", $mailMessage);
	}*/
}

function format_error ($errno, $errstr, $errfile, $errline) {
	$trace ='';
	//$trace = print_r(debug_backtrace(), true);

	$content = "
		<table>
			<thead><th>Item</th><th>Description</th></thead>
			<tbody>
				<tr>
					<th>Error</th>
					<td><pre>$errstr</pre></td>
				</tr>
				<tr>
					<th>Errno</th>
					<td><pre>$errno</pre></td>
				</tr>
				<tr>
					<th>File</th>
					<td>$errfile</td>
				</tr>
				<tr>
					<th>Line</th>
					<td>$errline</td>
				</tr>
				<tr>
					<th>Trace</th>
					<td><pre>$trace</pre></td>
				</tr>
			</tbody>
		</table>";
		
	return $content;
}

function send_mail ($to, $subject, $message, $from = MAIL_FROM_DEFAULT, $attachments = array()) {
	require_once WEBSITE_PATH.'res/lib/phpmailer/class.phpmailer.php';
	require_once MODEL_PATH.'Template.php';
	require_once MODEL_PATH.'Mail.php';
	
	$message = ajout_environnement_mail ($message);
	
	$mailSend = false;
	
	$modelMail = new Model_Mail();
	$modelMail->from = $from;
	$modelMail->to = $to;
	$modelMail->sujet = $subject;
	$modelMail->contenu = $message;
	if (isset($request) && $request->_auth) {
		$modelMail->id_user = $request->_auth->id;
	}
	
	if (!SEND_MAIL) {
		$modelMail->is_send = false;
		$modelMail->save();
		return true;
	}

	$mail = new PHPMailer;

	$mail->CharSet = 'utf-8';
	$mail->setFrom($from);
	$mail->addAddress($to);

	foreach ($attachments as $attachment) {
		$mail->addAttachment($attachment); 
		$modelMail->addAttachment($attachment); 
	}
	
	$mail->isHTML(true);

	$mail->Subject = $subject;
	$mail->Body    = $message;

	if(!$mail->send()) {
		writeLog (MAIL_LOG, $mail->ErrorInfo, LOG_LEVEL_ERROR, $subject, $to);
		writeLog (MAIL_LOG, $message, LOG_LEVEL_ERROR, $subject, $to);
		$modelMail->is_send = false;
		$modelMail->save();
		return false;
	}
	$modelMail->is_send = true;
	$modelMail->save();
	return true;
}

function ajout_environnement_mail ($messageContent) {
	if (ENVIRONNEMENT == "DEV") {
		$messageContent = str_replace("[ENVIRONNEMENT]", "ENVIRONNEMENT DE DÉVELOPPEMENT", $messageContent);
	} else if (ENVIRONNEMENT == "TEST") {
		$messageContent = str_replace("[ENVIRONNEMENT]", "ENVIRONNEMENT DE TEST", $messageContent);
	} else if (ENVIRONNEMENT == "DEMO") {
		$messageContent = str_replace("[ENVIRONNEMENT]", "ENVIRONNEMENT DE DEMO", $messageContent);
	} else if (ENVIRONNEMENT == "PREPROD") {
		$messageContent = str_replace("[ENVIRONNEMENT]", "ENVIRONNEMENT DE RECETTE", $messageContent);
	} else if (ENVIRONNEMENT == "PROD") {
		$messageContent = str_replace("[ENVIRONNEMENT]", "", $messageContent);
	}
	return $messageContent;
}

function datepickerToDatetime ($date) {
	list($day, $month, $year) = explode('/', $date);
	return $year.'-'.$month.'-'.$day;
}

function formatPrix ($prix) {
	$prixExplode = explode('.', $prix);
	$dizaine = $prixExplode[0];
	$prixFinal = $dizaine;
	if (count($prixExplode) > 1) {
		if (strlen($prixExplode[1]) == 1) {
			$prixFinal .= ','.$prixExplode[1].'0';
		} else {
			$prixFinal .= ','.$prixExplode[1];
		}
	} else {
		$prixFinal .= ',00';
	}
	$prixFinal .= ' €';
	return $prixFinal;
}

function formatHeureMinute ($heure, $minute) {
	if (strlen($minute) == 1) {
		$minute = '0'.$minute;
	}
	return $heure.'h'.$minute;
}

function construit_url_paypal() {
	/* $api_paypal = 'https://api-3t.sandbox.paypal.com/nvp?'; // Site de l'API PayPal test. On ajoute déjà le ? afin de concaténer directement les paramètres. */
	$api_paypal = 'https://api-3t.paypal.com/nvp?'; // Site de l'API PayPal prod. On ajoute déjà le ? afin de concaténer directement les paramètres.
	
	$version = 56.0; // Version de l'API

	/* $user = 'cservichezvous_api1.gmail.com'; // Utilisateur API test */
	$user = 'homemenus.inbox_api1.gmail.com'; // Utilisateur API prod
	
	/* $pass = 'D24DDXJJCGUJYKR9'; // Mot de passe API test */
	$pass = 'C29KYXQ9RNCB6ZLR'; // Mot de passe API prod
	
	/* $signature = 'ArNp9UkZWjxr7O8Wgxl1F5CN.u13A4.lDIpfU3dMcFsPcOT7XEZ6ubVT'; // Signature de l'API test */
	$signature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AvgsNoywKGG.vIR-KOkSRKLCbGw2'; // Signature de l'API prod

	$api_paypal = $api_paypal.'VERSION='.$version.'&USER='.$user.'&PWD='.$pass.'&SIGNATURE='.$signature; // Ajoute tous les paramètres

	return 	$api_paypal; // Renvoie la chaîne contenant tous nos paramètres.
}

function recup_param_paypal($resultat_paypal) {
	// On récupère la liste de paramètres, séparés par un 'et' commercial (&)
	$liste_parametres = explode("&",$resultat_paypal); // Crée un tableau de paramètres
	// Pour chacun de ces paramètres, on exécute le bloc suivant, en intégrant le paramètre dans la variable $param_paypal
	foreach($liste_parametres as $param_paypal)
	{
		// On récupère le nom du paramètre et sa valeur dans 2 variables différentes. Elles sont séparées par le signe égal (=)
		list($nom, $valeur) = explode("=", $param_paypal);
		// On crée un tableau contenant le nom du paramètre comme identifiant et la valeur comme valeur.
		$liste_param_paypal[$nom]=urldecode($valeur); // Décode toutes les séquences %##  et les remplace par leur valeur.
	}
	return $liste_param_paypal; // Retourne l'array
}

function addTextNode ($dom, $parent, $name, $value) {
	$node = $dom->createElement($name);
	$texte = $dom->createTextNode($value);
	$node->appendChild($texte);
	$parent->appendChild($node);
}

function getLogoRestaurant ($id_restaurant) {
	$imgPath = "res/img/restaurant/";
	$logoDirectory = WEBSITE_PATH.$imgPath;
	if (file_exists($logoDirectory.$id_restaurant)) {
		if (file_exists($logoDirectory.$id_restaurant.'/logo.png')) {
			return $imgPath.$id_restaurant.'/logo.png';
		} else if (file_exists($logoDirectory.$id_restaurant.'/logo.jpg')) {
			return $imgPath.$id_restaurant.'/logo.jpg';
		} else if (file_exists($logoDirectory.$id_restaurant.'/logo.gif')) {
			return $imgPath.$id_restaurant.'/logo.gif';
		} else {
			return $imgPath.'default/logo.jpg';
		}
	} else {
		return $imgPath.'default/logo.jpg';
	}
}

function restaurantToLink ($restaurant, $ville) {
	$ville = cleanString($ville);
	$name = cleanString($restaurant->nom);
	
	return "restaurant-".$restaurant->id.'-'.$ville.'-'.$name.'.html';
}

function cleanString ($name) {
	/* replace space by - */
	$name = str_replace(' ', '-', $name);
	/* replace à by a */
	$name = str_replace('à', 'a', $name);
	/* replace é by e */
	$name = str_replace('é', 'e', $name);
	
	return $name;
}

?>
