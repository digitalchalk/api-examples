<?php
	
	require_once('apicommon.php');
	require_once('apiconfig.php');
	
	$userApiUrl = 'https://' . $orgHostName . '/dc/api/v5/users';
	$id = '';
	$email = '';
	$offset = '';
	
	if(isset($_REQUEST['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if(isset($_REQUEST['email'])) {
		$email = $_REQUEST['email'];
	}
	
	if(isset($_REQUEST['offset'])) {
		$offset = $_REQUEST['offset'];
	}
	
	$dataToSend = '';
	
	if($id) {
		$userApiUrl .= '/' . $id;
	} else if ($email) {
		$dataToSend = array( 'email' => $email);
		if($offset) {
			$dataToSend['offset'] = $offset; 
		}
		$encoded = http_build_query($dataToSend);
		$userApiUrl .= '?' . $encoded;
	}
	
	$result = makeApiCall($userApiUrl, $oauthToken, 'GET', null);
	
	$success = FALSE;
	
	if($result['http_status_code'] = 200) {
		$success = TRUE;
		if(isset($result['results'])) {
			$searchResults = (array)$result['results'];
		} else {
			$searchResults = array();
			if(isset($result['body'])) {
				
				$searchResults = (array)$result['body'];
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
			<h2>Get User(s) Results</h2>
		</div>
		<?php if($success) { ?>
		<div class="span-24">
			<div class="success">
			<p>Success</p>
			</div>
		</div>

		<div class="span-4"><p>Results</p></div>
		<div class="span-20 last">
			<pre><?php print_r($searchResults); ?></pre>
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
		
		<hr>		
		<div class="span-24">
			<p>Raw Data</p>
			<pre><?php print_r($result); ?></pre>
		</div>
	</div>
</body>
</html>