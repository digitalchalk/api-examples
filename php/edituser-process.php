<?php

	require_once('apicommon.php');
	require_once('apiconfig.php');
	
	$userApiUrl = 'https://' . $orgHostName . '/dc/api/v5/users';
	
	if(!isset($_GET['id'])) {
		exit("Parameter id is required for a delete operation");
	}
	
	$userId = $_GET['id'];
	
	$fieldsIn = $_POST;
	if(!$fieldsIn) {
		$fieldsIn = array();
	}
	
	// Fix tags into an array
	if(isset($fieldsIn['tags'])) {
		$fieldsIn['tags'] = preg_split("/(,| )/", $fieldsIn['tags']);
	} 
	
	$result = makeApiCall($userApiUrl . '/' . $userId, $oauthToken, "PUT", $fieldsIn);
	$success = FALSE;
	
	if($result['http_status_code'] == 204) {
		$success = TRUE;
	}
?>
<html>
<head><title>Edit User Result</title>
<?php  include('inccss.php'); ?>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>Edit User Results</h2>
		</div>
		<?php if($success) { ?>
		<div class="span-24">
			<div class="success">
				<p>Success editing user</p>
				<p>View edited user with <a href="getuser-process.php?id=<?php echo $userId; ?>">GetUser API Call</a></p>
			</div>
		</div>
		<?php } else { // not success ?>
		<div class="span-24">
			<div class="error">
				<p>Edit user was NOT successful</p>
				<?php if(isset($result['error'])) {?>
				<p>Error: <?php echo $result['error']; ?></p>
				<?php } ?>
				<?php if(isset($result['error_description'])) {?>
				<p>Error Description: <?php echo $result['error_description']; ?></p>
				<?php } ?>
				<?php if(isset($result['errors'])) {?>
				<p>Errors</p>
				
				<ul>
				<?php foreach($result['errors'] as $errno => $errordesc) { ?>
				<li><?php echo $errordesc; ?></li>
				<?php } ?>
				</ul>
				
				<?php } ?>			
				<?php if(isset($result['fieldErrors'])) {?>
				<p>Field Errors</p>			
				<ul>
				<?php foreach($result['fieldErrors'] as $field => $fieldError) { ?>
				<li><?php echo $field . ' : ' . $fieldError; ?></li>
				<?php } ?>
				</ul>
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