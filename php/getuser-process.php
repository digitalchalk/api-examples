<?php
	
	require_once('apicommon.php');
	require_once('apiconfig.php');
	
	$userApiUrl = 'https://' . $orgHostName . '/dc/api/v5/users';
	$id = '';
	$email = '';
	$offset = '';
	$searchResults = '';
	
	parse_str($_SERVER['QUERY_STRING'], $parametersIn);
	
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
	} else {
		// it is a get all
		if($offset) {
			$dataToSend['offset'] = $offset;
			$encoded = http_build_query($dataToSend);
			$userApiUrl .= '?' . $encoded;
		}
	}
	
	$result = makeApiCall($userApiUrl, $oauthToken, 'GET');
	
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
<script type="text/javascript">

function deleteUser(userId) {
	alertify.confirm("Delete user?", function(e) {
		if(e) {
			window.location.href = 'deleteuser-process.php?id=' + userId;
		} 
	});
}

function editUser(userId) {
	window.location.href = 'edituser-start.php?id=' + userId;
}
</script>
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

		<div class="span-4">
			<p>Results (<?php echo $numResults; ?><?php 
				if(isset($result['prevOffset']) || isset($result['nextOffset'])) {
					echo '+';
				}
			?>)</p>
			<?php 
				if(isset($result['prevOffset'])) {
					$prevParms = $parametersIn;
					$prevParms['offset'] = $result['prevOffset'];
					$prevLinkParms = http_build_query($prevParms); 			
			?>
			<p><a href="getuser-process.php?<?php echo $prevLinkParms; ?>">Previous Page</a></p>
			<?php } ?>
			<?php 
				if(isset($result['nextOffset'])) {
					$nextParms = $parametersIn;
					$nextParms['offset'] = $result['nextOffset'];
					$nextLinkParms = http_build_query($nextParms); 			
			?>
			<p><a href="getuser-process.php?<?php echo $nextLinkParms; ?>">Next Page</a></p>
			<?php } ?>
		</div>
		<div class="span-20 last">
			<?php if(is_array($searchResults)) {
				    foreach($searchResults as $resNum=>$searchResult) {
			?>
			<p id="user<?php echo $resNum; ?>"><?php $userId = print_user($searchResult); ?>
			<?php if($userId) { ?>
			<button onclick="editUser('<?php echo $userId; ?>');">Edit User</button>&nbsp;<button onclick="deleteUser('<?php echo $userId; ?>');">Delete User</button>
			<?php  } // if userid ?>
			<?php 	}  // end foreach?>
			<?php if(count($searchResults) == 0) { ?>
				<p><b>No Results Found</b></p>
			<?php } ?>
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
</html>