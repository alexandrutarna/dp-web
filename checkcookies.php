<?php
ini_set('display_errors', 1);

// use only https
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

if (isset($_COOKIE['shuttlecookie']))
	header('Location: '.$_GET['prev_uri']);
else
	die("<h1>Cookies must be enabled in order to access the website.</h1>");

?>