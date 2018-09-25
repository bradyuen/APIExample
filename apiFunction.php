<?php
/*============================================
	Api function to handle submit Data

============================================*/


//Decrypt function will not available for client, Server side only
function apiDecrypt($string, $apiPassword){
	//IOS_APP_KEY = "BPPMange" defined in config

	$ciphertext_base64 = urlencode($string);
	
	$ciphertext_base64 = str_replace("+", "%2B",$ciphertext_base64);
	$ciphertext_base64 = urldecode($ciphertext_base64);
	$iv_size = mcrypt_get_iv_size(MCRYPT_3DES, "ecb");
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	
	$ciphertext_dec = base64_decode($ciphertext_base64);
	# retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
	$iv_dec = substr($ciphertext_dec, 0, $iv_size);
	
	# retrieves the cipher text (everything except the $iv_size in the front)
	//$ciphertext_dec = substr($ciphertext_dec, $iv_size);
	
	# may remove 00h valued characters from end of plain text
	if($request_dec = mcrypt_decrypt(MCRYPT_3DES, $apiPassword,$ciphertext_dec, "ecb", $iv_dec)){
		return rtrim($request_dec, "\x00..\x1F");
	}else{
		return false;
	}
}


function apiEncrypt($requestArray, $apiPassword){
	//IOS_APP_KEY = "BPPMange" defined in config
	
	$iv_size = mcrypt_get_iv_size(MCRYPT_3DES, "ecb");
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	//echo $iv_size;
	# creates a cipher text compatible with AES (Rijndael block size = 128)
	# to keep the text confidential 
	# only suitable for encoded input that never ends with value 00h
	# (because of default zero padding)
	if($ciphertext = mcrypt_encrypt(MCRYPT_3DES, $apiPassword, $requestArray, "ecb", $iv)){
		$ciphertext_base64 = base64_encode($ciphertext);
		return $ciphertext_base64;
	}else{
		return false;
	}
}


function BPPAPICall($url, $requestArray=array(), $username, $apiPassword, $debug=0){
	$authKey = base64_encode($username.":".$apiPassword);
	$pass_query_json = json_encode($requestArray);
	$request = apiEncrypt($pass_query_json, $apiPassword);
	
	$postField = 'request='.$request;
	if($debug == 1){
		echo $postField;
	}

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $postField,
		CURLOPT_HTTPHEADER => array(
		"authorization: Basic ".$authKey,
		"cache-control: no-cache",
		"content-type: application/x-www-form-urlencoded",
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  return "cURL Error #:" . $err;
	} else {
	  return $response;
	}


	// $apiCall = $url.'?request='.$request;

	// if($debug == 1){
	// 	echo $apiCall;
	// }

	// //open connection
	// $ch = curl_init();

	// //set the url, number of POST vars, POST data
	// curl_setopt($ch,CURLOPT_URL, $apiCall);
	// curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,20); 
	// curl_setopt($ch, CURLOPT_TIMEOUT, 20);	
	// curl_setopt($ch, CURLOPT_HTTPHEADER, $authArray);	
	// //execute post
	// $get_result = json_decode( curl_exec($ch), true);
	


	// //close connection
	// curl_close($ch);

	// return $get_result;
}
?>