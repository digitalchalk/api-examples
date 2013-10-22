<?php

function makeApiCall($url, $token, $method, $dataToSend) {
	$return = array();
	
	if(strtoupper($method) == 'GET') {
		if($dataToSend) {
			$url .= $url . '?' . http_build_query($dataToSend);
		}
	}
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	if(strtoupper($method) == 'POST') {
		$jsonToSend = json_encode($dataToSend);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonToSend);
	}
	// SSL_VERIFYIER == false allows self-signed certificates.  You should remove the following 2 lines in a production environment
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // since we are separating the headers and body anyway
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Accept: application/json',
		'Authorization: Bearer ' . $token)
	);
	
	$result = curl_exec($ch);
	//$return['curl_info'] = curl_getinfo($ch);
	$return['http_status_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($result === FALSE) {
		$return['error'] = curl_error($ch);
	} else {
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headerpart = substr($result, 0, $header_size);
		$body = substr($result, $header_size);
		$return['response_headers'] = http_parse_headers($headerpart);
		
		if($body) {
			try {
				$bodyJson = json_decode($body);
				$return['body'] = $bodyJson;
				if(isset($bodyJson->error)) {
					$return['error'] = $bodyJson->error;
				}
				if(isset($bodyJson->error_description)) {
					$return['error_description'] = $bodyJson->error_description;
				}
				if(isset($bodyJson->errors)) {
					$return['errors'] = (array)$bodyJson->errors;
				}
				if(isset($bodyJson->fieldErrors)) {
					$return['fieldErrors'] = (array)$bodyJson->fieldErrors;
				}
				if(isset($bodyJson->results)) {
					$return['results'] = (array)$bodyJson->results;
				}
				if(isset($bodyJson->previous)) {
					$return['previous'] = $bodyJson->previous;
					$prevParts = explode('?', $bodyJson->previous);
					if(count($prevParts) > 1) {
						parse_str($prevParts[1], $qarray);
						if(array_key_exists("offset", $qarray)) {
							$return['prevOffset'] = $qarray['offset'];
						}
					}
				}
				if(isset($bodyJson->next)) {
					$return['next'] = $bodyJson->next;
					$nextParts = explode('?', $bodyJson->next);
					if(count($nextParts) > 1) {
						parse_str($nextParts[1], $qarray);
						if(array_key_exists("offset", $qarray)) {
							$return['nextOffset'] = $qarray['offset'];
						}
					}
				}
			} catch(Exception $e) {
				$return['bodyexception'] = $e;
			}
		}
	}
	
	curl_close($ch);
	
	return $return;
}

function print_user($user) {
	$userArray = $user;
	if(!is_array($userArray)) {
		$userArray = (array)$user;
	}
	echo '<p>';
	foreach($userArray as $field=>$value) {
		if($field == 'id') {
			echo $field . ' => <a href="getuser-process.php?id=' . $value . '" title="View with GetUser">' . $value . '</a><br/>';
		} else {
			echo $field . ' => ' . $value . '<br/>';
		}
	}
	echo '</p>';
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