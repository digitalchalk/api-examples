<html>
<head><title>Add User Example</title>
<?php  include('inccss.php'); ?>
</head>
<body>
<div class="container">
	<div class="span-24">
	<h2>Add User - API Example</h2>
	</div>
	<div class="span-24">
	<form action="adduser-process.php" method="POST" class="form.inline">
		<fieldset>
			<legend>Add User Form</legend>
			<p>
			<label>First Name</label><br>
			<input type="text" class="text" name="firstName"/><br>
			</p>
			<p>
			<label>Last Name</label><br>
			<input type="text" class="text" name="lastName"/><br>
			</p>
			<p>
			<label>Email</label><br>
			<input type="email" name="email"/><br>
			</p>
			<p>
			<label>Password</label><br>
			<input type="password" name="password"/><br>
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
