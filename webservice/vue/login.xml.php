<?php
	
	$dom = new DOMDocument();
	$userDom = $dom->createElement("user");
	$dom->appendChild($userDom);
	$userDom->setAttribute("uid", $user->id);
	
	addTextNode ($dom, $userDom, "nom", utf8_encode($user->nom));
	addTextNode ($dom, $userDom, "login", $user->login);
	addTextNode ($dom, $userDom, "status", $user->status);
	addTextNode ($dom, $userDom, "session", $user->session);
	
	if ($user->status == USER_CLIENT) {
		addTextNode ($dom, $userDom, "rue", utf8_encode($user->rue));
		addTextNode ($dom, $userDom, "ville", utf8_encode($user->ville));
		addTextNode ($dom, $userDom, "code_postal", $user->code_postal);
		addTextNode ($dom, $userDom, "telephone", $user->telephone);
	}
	
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>