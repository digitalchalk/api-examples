<?php
	
	require_once('apicommon.php');
	require_once('apiconfig.php');
	
	$userApiUrl = 'https://' . $orgHostName . '/dc/api/v5/users';
	$id = '';
	$email = '';
	$offset = '';
	$searchResults = '';
	
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
	
	if($result['http_status_code'] == 200) {
		$success = TRUE;
		if(isset($result['results'])) {
			$searchResults = (array)$result['results'];
		} else {
			$searchResults = array();
			if(isset($result['body'])) {
				
				$searchResults = array();
				$searchResults[] = $result['body'];
			}
		}
	}
	$numResults = 0;
	if(is_array($searchResults)) {
		$numResults = count($searchResults);
	}
	
?>
<html>
<head><title>Get User Result</title>
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

		<div class="span-4"><p>Results (<?php echo $numResults; ?>)</p></div>
		<div class="span-20 last">
			<?php if(is_array($searchResults)) {
				    foreach($searchResults as $resNum=>$searchResult) {
			?>
			<p><?php print_user($searchResult); ?></p>
			<?php 	}  // end foreach?>
			<?php } else { // not an array ?>
			<pre><?php print_r($searchResults); ?></pre>
			<?php } ?>
		</div>

		<?php } else {  // not success ?>
		<div class="span-24">
			<div class="error">
			<p>GetUser was NOT successful</p>
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
			<p>Raw Data <a href="javascript:void(0);" onclick="$('#rawdata').toggle();">Toggle</a></p>
			<div id="rawdata" style="display:none">
			<pre><?php print_r($result); ?></pre>
			</div>
		</div>
	</div>
</body>
</html>