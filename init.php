<?php
ini_set('display_errors', 1);

session_start();
// use only https
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

// check if cookies are enabled
if (!isset($_COOKIE['shuttlecookie'])){
	setcookie('shuttlecookie', 'true', time()+3600);
	header('Location: checkcookies.php?prev_uri=https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
}	

// database

//local

$host = 'localhost';
$user = 'root';
$pwd = 'sqlsql';
$db = 's251897';


//polito
/*
$host = 'localhost';
$user = 's251897';
$pwd = 'sqlsql';
$db = 's251897';
*/

$title = 'Booking Shuttle';

?>
