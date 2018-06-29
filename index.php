<?php
require 'functions.php';
$title = 'Booking Shared Shuttle';

require('header.php'); 

$connection = database_connect();

// check if there are any bookings
// $bookings = get_bookings($connection);
$bookings  = true; // added for debug


if($bookings == null)
	echo '<h3>There are no bookings</h3>';
else { // display all the bookings
  echo '<h3>Here are the bookings</h3>';
}


require('footer.php'); 
?>