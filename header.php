<?php

$myTimeout = 2*60;

if (isset($_SESSION['last_activity']) && 
	(time() - $_SESSION['last_activity'] > $myTimeout)) 
	{
		// logout(); //session expires after 2 minutes
		destroySession();
		header('Location: login.php');
	}
update_session_time();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php if(isset($title)){ echo $title; }?></title>

	<style>

	#wrapper {
		width:800px;
		overflow:hidden;
		padding:10px;
	}

	#content {
		margin: 0 0 0 200px;
		box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
		padding: 30px;
		background-color: #ffffff;
		min-height: 400px;
	}
	</style>


</head>

<body>
<noscript><h1 class="error">Your browser does not support JavaScript!</h1></noscript>

<div id="wrapper">
	<h1><?php if(isset($title)){ echo $title; }?></h1>
		<div id="sidebar">
		<ul>
			<li><a href="index.php">Home</a>
			<?php if(logged_in()) echo "<li> <a href='profile.php'>Profile</a> </li>"; ?>
			<?php if(logged_in()) echo "<li> <a href='logout.php'>Logout</a> </li>"; ?>
			<?php if(!logged_in()) echo "<li> <a href='login.php'>Login</a> </li>"; ?>
			<?php if(!logged_in()) echo "<li> <a href='register.php'>Register</a> </li>"; ?>
		</ul>
</div>
<div id="content">
