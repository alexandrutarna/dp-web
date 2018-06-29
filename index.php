<?php
require 'functions.php';
$title = 'Booking Shared Shuttle';

require('header.php'); 

$connection = database_connect();

// check if there are any bookings
$bookings = get_bookings($connection);


if($bookings == null)
	echo '<h3>There are no bookings</h3>';
else { // display all the bookings
  echo '<h3>Here are the bookings</h3>';

  echo "<table border='1'>
      <tr>
      <th>user_id</th>
      <th>departure</th>
      <th>destination</th>
      <th>nr_passengers</th>
      </tr>";


  foreach($bookings as $booking) {

      $user_id = $booking['user_id'];
      $departure = $booking['departure'];
      $destination = $booking['destination'];
      $nr_passengers = $booking['nr_passengers'];


      echo "<tr>";
      echo "<td>" . $user_id . "</td>";
      echo "<td>" . $departure . "</td>";
      echo "<td>" . $destination . "</td>";
      echo "<td>" . $nr_passengers . "</td>";
      echo "</tr>";


}
}

require('footer.php'); 
?>