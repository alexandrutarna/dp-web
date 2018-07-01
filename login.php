<?php
require 'functions.php';
$title = 'Login';

require('header.php'); 
?>

<!-- HTML -->
<form name = "login" action="login.php" method="POST" onsubmit="return validateForm()">
<p>
  <label>Username:</label>
  <input type="email" name="username" maxlength="50" required="required"  placeholder="your email"><br />
</p>
<p>
  <label>Password: </label>
  <input type="password" name="password" maxlength="50" required="required" placeholder="your password"><br />	
</p>
<p>
  <input class = "button" type="submit" value="Login" name="submit" />
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

		// check if email and password contains invalid characters
		// and if they exist in DB
		if($_POST['username'] === $username) {

			if(!filter_var($username, FILTER_VALIDATE_EMAIL)
				|| preg_match('/"/', $username) || preg_match("/'/", $username))
				{
				$error[] = 'Please enter a valid email address';
				}
				else 
				{
					// check if username  exists
					if(user_exists($connection, $username)){
						// check if password matches

						$received_password = $_POST['password'];
						$password = sanitizeString($received_password);

						if (password_matches($connection, $username, $password))
						{
							/// get user_id
							echo 'Password Matches !!!! LOGIN SUCCESS';

							$user_id = get_user_id($connection, $username);
							login_user($user_id);

							// header('Location: profile.php');
							// die();

						}
						else 
						{
							$error[] = 'Wrong password. Please try again';
						}
					}
					else{
						$error[] = 'User not registered. Please register first';
					}
				}

		}

		if (!isset($error)) {
		
			echo $user_id;
			login_user($user_id);
			header('Location: profile.php');
			die();
		}
		else
			foreach ($error as $err)
				echo '<h3 class="error">'.$err.'</h3>';
	}

?>