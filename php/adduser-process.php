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
	
	$result = makeApiCall($userApiUrl, $oauthToken, "POST", $userData);
	
	$success = FALSE;
	
	$newUserId = '';
	
	if($result['http_status_code'] == 201) {
		// 201 means success for an add
		$success = TRUE;
		foreach($result['response_headers'] as $key => $header) {
			if(strtoupper($key) == 'LOCATION') {
				$urlParts = explode('/', $header);
				$newUserId = array_pop($urlParts);
			}
		}
	}
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
		<?php if($success) { ?>
		<div class="span-24">
			<div class="success">
			<p>User was successfully created</p>
			<?php if($newUserId) { ?>
			<p>New user ID is <?php echo $newUserId; ?></p>
			<p>Lookup up new user using a <a href="getuser-process.php?id=<?php echo $newUserId ?>">GetUser API call</a></p>
			<?php } ?>
			</div>
		</div>
		<?php } else {  // not success ?>
		<div class="span-24">
			<div class="error">
			<p>User creation was NOT successful</p>
			<?php if(isset($result['error'])) {?>
			<p>Error: <?php echo $result['error']; ?></p>
			<?php } ?>
			<?php if(isset($result['error_description'])) {?>
			<p>Error Description: <?php echo $result['error_description']; ?></p>
			<?php } ?>
			<?php if(isset($result['errors'])) {?>
			<p>Errors</p>
			<p>
			<ul>
			<?php foreach($result['errors'] as $errno => $errordesc) { ?>
			<li><?php echo $errordesc; ?></li>
			<?php } ?>
			</ul>
			</p>
			<?php } ?>			
			<?php if(isset($result['fieldErrors'])) {?>
			<p>Field Errors</p>
			<p>
			<ul>
			<?php foreach($result['fieldErrors'] as $field => $fieldError) { ?>
			<li><?php echo $field . ' : ' . $fieldError; ?></li>
			<?php } ?>
			</ul>
			</p>
			<?php } ?>
			</div>
		</div>
		<?php } // end not success ?>
		<div class="span-24">
		<p>
			<a href="javascript:void(0);" onclick="window.history.back();">Back</a><br/><a href="index.php">Back to home</a>
		</p>
		</div>
		
		<hr>		
		<div class="span-24">
			<p>Raw Data <a href="javascript:void(0);" onclick="$('#rawdata').toggle();">Toggle</a></p>
			<div id="rawdata" style="display:none">
			<pre><?php print_r($result); ?></pre>
			</div>
		</div>

	</div>
</body>
</html>