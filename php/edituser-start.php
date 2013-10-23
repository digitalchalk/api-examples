<?php 

require_once('apicommon.php');
require_once('apiconfig.php');

$userApiUrl = 'https://' . $orgHostName . '/dc/api/v5/users';

if(!isset($_REQUEST['id'])) {
	exit("Parameter id is required for an edit operation");
}

$userId = $_REQUEST['id'];

// GET the current user info for editing
$result = makeApiCall($userApiUrl . '/' . $userId, $oauthToken, "GET");
$success = FALSE;

if($result['http_status_code'] == 200) {
	$success = TRUE;
	if(isset($result['results'])) {
		$searchResults = (array)$result['results'];
	} else {
		if(isset($result['body'])) {
			$searchResults = array();
			$searchResults[] = $result['body'];
		}
	}
	$userToEdit = $searchResults[0];
} 


?>
<html>
<head><title>Edit User</title>
<?php  include('inccss.php'); ?>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>Edit User</h2>
		</div>
		<?php if($success) { ?>
		<div class="span-24">
			<form action="edituser-process.php?id=<?php echo $userId; ?>" method="POST" class="form.inline">
				<fieldset>
				<legend>Edit User <?php echo $userToEdit->id; ?></legend>
				<p>
				<label for="firstName">First Name</label><br/>
				<input type="text" class="text" name="firstName" <?php if(isset($userToEdit->firstName)) {?> value="<?php echo $userToEdit->firstName; ?>" <?php } ?>/>
				</p>
				<p>
				<label for="lastName">Last Name</label><br/>
				<input type="text" class="text" name="lastName" <?php if(isset($userToEdit->lastName)) {?> value="<?php echo $userToEdit->lastName; ?>" <?php } ?>/>
				</p>
				<p>
				<label for="email">Email</label><br/>
				<input type="text" class="text" name="email" <?php if(isset($userToEdit->email)) {?> value="<?php echo $userToEdit->email; ?>" <?php } ?>/>
				</p>				
				<p>
				<label for="tags">Tags</label><br/>
				<input type="text" class="text" name="tags" <?php if(isset($userToEdit->tags)) {?> value="<?php echo implode(' ', $userToEdit->tags); ?>" <?php } ?>/>
				</p>
				<p>
				<input type="submit" value="Submit"/>
				</p>								
				</fieldset>
			</form>
		</div>
		<?php } else { // not success ?>
		<div class="span-24">
			<div class="error">
				<p>Couldn't get user info</p>
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
			<p><a href="index.php">Back to Home</a></p>
		</div>
	</div>
</body>
</html>
