<?php
require 'functions.php';

$connection = database_connect();

if(!logged_in()) {
	header('Location: login.php');
	die();
}

$title = "Profile";
require('header.php'); 


echo '<h3>Welcome to the booking <span class="username"> </span> !</h3></br>';

require('footer.php'); 
?>