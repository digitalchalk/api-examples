<html>
<head><title>Get User By Email</title>
<?php  include('inccss.php'); ?>
</head>
<body>
<div class="container">
	<div class="span-24">
	<h2>Get User By Email</h2>
	</div>
	<div class="span-24">
	<form action="getuser-process.php" method="GET" class="form.inline">
		<fieldset>
			<legend>Get User Form</legend>
			<p class="info">
			Note: Matches full and partial matches to the email given
			</p>
			<p>
			<label>Email</label><br/>
			<input type="text" class="email" name="email"/><br/>
			</p>
			<p>
			<input type="submit" value="Submit"/>
			</p>
		</fieldset>
	</form>
	</div>
</div>
</body>
</html>
