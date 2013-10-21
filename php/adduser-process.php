<?php

	require_once('apicommon.php');
	require_once('apiconfig.php');
	
	$userApiUrl = 'https://' . $orgHostName . '/dc/api/v5/users';
	
	$userData = array(
			'firstName' => $_POST['firstName'],
			'lastName' => $_POST['lastName'],
			'email' => $_POST['email'],
			'password' => $_POST['password']
	);
	
	$jsonToSend = json_encode($userData);
	
	$ch = curl_init($userApiUrl);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonToSend);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	
	// SSL_VERIFYIER == false allows self-signed certificates.  You should remove the following 2 lines in a production environment
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Accept: application/json',
	'Authorization: Bearer ' . $oauthToken)
	);
	$result = curl_exec($ch);
	
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$message = '';
	$errorMsg = '';
	$newUserId = '';
	if($http_status == 201) {
		// 201 means created, which is success for add user
		$message = "<p>Success creating user</p>";
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headerpart = substr($result, 0, $header_size);
		$body = substr($result, $header_size);
		$headers = http_parse_headers($headerpart);
		foreach($headers as $key=>$header) {
			if($key == 'Location') {
				$urlParts = explode('/', $header);  // intermediate value for STRICT compliance
				$newUserId = array_pop($urlParts);
				$message .= '<p>New User ID : ' . $newUserId . '</p>';
			}
		}
	} else if(!$result) {
		$errorMsg = curl_error($ch);
	} else {
		try {
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$headerpart = substr($result, 0, $header_size);
			$body = substr($result, $header_size);
			$error = json_decode($body);
			if($error->fieldErrors) {
				$errorMsg = print_r((array)$error->fieldErrors, true);
			} else {
				$errorMsg= print_r(json_decode($body), true);
			}
		} catch(Exception $e) {
			$errorMsg = $e->getMessage();
		}
		
	}
	curl_close($ch);	

?>
<html>
<head><title>Add User Result</title>
<?php  include('inccss.php'); ?>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>Add User Results</h2>
		</div>
		<?php if($errorMsg) { ?>
		<div class="span-24">
			<div class="error"><p>Create User was not successful</p><pre> <?php echo $errorMsg; ?></pre></div>
		</div>
		<?php } ?>
		<?php if($message) { ?>
		<div class="span-24">
			<div class="success"><?php echo $message; ?></div>
		</div>
		<?php } ?>
		<div class="span-24">
		<p>
			<a href="adduser-start.php">Back to the Add User Start page</a>
		</p>
		</div>
	</div>
</body>
</html>