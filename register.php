<?php
require 'functions.php';
$title = 'Register';
require('header.php'); 
?>

<!-- HTML -->
<form name = "register" action="register.php" method="POST" onsubmit="return validateForm()">
<p>
  <label>Username:</label>
  <input type="email" name="username" maxlength="50" required="required"  placeholder="your email"><br />
</p>
<p>
  <label>Password: </label>
  <input type="password" name="password" maxlength="50" required="required" placeholder="your password"><br />	
</p>
<p>
  <input class = "button" type="submit" value="Register" name="submit" />
</p>
</form>

<!-- include JS -->
<script type="text/javascript" src="jsfunctions.js"></script>

<?php

$connection = database_connect();


if(isset($_POST['submit'])) {
	if (!isset($_POST['username']) || $_POST['username'] === '')
		$error[] = 'Invalid username';
	if (!isset($_POST['password']) || $_POST['password'] === '')
		$error[] = 'Invalid password';
	
	$received_username = $_POST['username'];
	
	// Remove all illegal characters from email
	$username = filter_var($received_username,FILTER_SANITIZE_EMAIL);

	if(strlen($username) > 50)
		$error[] = 'Username is too long';


		// check if email contains invalid characters
		if($_POST['username'] === $username) {
			
			if(!filter_var($username, FILTER_VALIDATE_EMAIL)
				|| preg_match('/"/', $username) || preg_match("/'/", $username))
				$error[] = 'Please enter a valid email address';
			else {
				// check if username already exists
				$result = $connection->query("SELECT username FROM users WHERE username = '$username'");
				if($result && $result->num_rows != 0)
					$error[] = 'Username already exists';
				
				$password = $_POST['password'];
				
				if(strlen($password) > 50)
					$error[] = 'Password is too long.';
				
					// to do password checks
				if (!(preg_match('/[a-z]/', $password) 
					&& preg_match('/[0-9]/', $password)))
					$error[] = 'Password must contain at least a lowercase';
			}
		}		
		else 
			$error[] = 'Please enter a valid email address';



	// save user to database
		if (!isset($error)) {
			$password = md5($password);//protect password
			$newUserQuery = "INSERT INTO users(username, password) VALUES('$username', '$password')";
			$result = $connection->query($newUserQuery);
			if(!$result){
				die('Regisstration Error');
			}
			
			$user_id = $connection->insert_id;
			
			if(!$connection->commit()) {
				die('Error writing database');
			}
		
			echo $user_id;

			// save session
			login_user($user_id);

			header('Location: profile.php');
			die();
		}
		else
			foreach ($error as $err)
				echo '<h3 class="error">'.$err.'</h3>';
	}

?>