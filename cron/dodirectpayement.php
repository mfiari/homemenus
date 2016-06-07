<?php
 
    include_once '../config.php';
	
	$request_params = array (
		'METHOD' => 'DoDirectPayment', 
		'USER' => PAYPAL_USER, 
		'PWD' => PAYPAL_PASSWORD, 
		'SIGNATURE' => PAYPAL_SIGNATURE, 
		'VERSION' => 109.0, 
		'PAYMENTACTION' => 'Sale',                   
		'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
		'CREDITCARDTYPE' => 'MasterCard', 
		'ACCT' => '5133790091720081',                        
		'EXPDATE' => '012018',           
		'CVV2' => '753', 
		'FIRSTNAME' => 'Mike', 
		'LASTNAME' => 'Fiari', 
		'STREET' => '22 rue du commerce', 
		'CITY' => 'Juziers',                    
		'COUNTRYCODE' => 'FR', 
		'ZIP' => '78820', 
		'AMT' => '1.00', 
		'CURRENCYCODE' => 'EUR', 
		'DESC' => 'Testing Payments Pro'
	);
	
	$nvp_string = '';
	foreach($request_params as $var=>$val)
	{
		$nvp_string .= '&'.$var.'='.urlencode($val);    
	}
	
	// Send NVP string to PayPal and store response
	$curl = curl_init();
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_URL, 'https://api-3t.paypal.com/nvp');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
	 
	$result = curl_exec($curl);     
	curl_close($curl);
	
	var_dump($result); die();