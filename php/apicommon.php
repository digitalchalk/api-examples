<?php

function makeApiCall($url, $token, $method, $dataToSend) {
	$result = array();
	
	if(strtoupper($method) == 'GET') {
		if($dataToSend) {
			$url .= $url . '?' . http_build_query($dataToSend);
		}
	}
	
	$ch = curl_init($userApiUrl);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	if(strtoupper($method) == 'POST') {
		$jsonToSend = json_encode($dataToSend);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonToSend);
	}
	// SSL_VERIFYIER == false allows self-signed certificates.  You should remove the following 2 lines in a production environment
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Accept: application/json',
		'Authorization: Bearer ' . $token)
	);
	
	
	
	curl_close($ch);
	
	return $result;
}

function http_parse_headers( $header )
{
	$retVal = array();
	$fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
	foreach( $fields as $field ) {
		if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
			$match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
			if( isset($retVal[$match[1]]) ) {
				$retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
			} else {
				$retVal[$match[1]] = trim($match[2]);
			}
		}
	}
	return $retVal;
}

?>