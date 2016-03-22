<?php
	
	$dom = new DOMDocument();
	$messagesDom = $dom->createElement("messages");
	$dom->appendChild($messagesDom);
	$nbResult = 0;
	foreach ($messages as $message) {
		$messageDom = $dom->createElement("message");
		addTextNode ($dom, $messageDom, "sender", $message->sender);
		addTextNode ($dom, $messageDom, "text", $message->message);
		addTextNode ($dom, $messageDom, "date", $message->date);
		$messagesDom->appendChild($messageDom);
		$nbResult++;
	}
	$messagesDom->setAttribute("nbResult", $nbResult);
	header("Content-type: text/xml; charset=utf-8");
	print $dom->saveXML();
	
?>