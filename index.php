<?php
require 'functions.php';
$title = 'Booking Shared Shuttle';

require('header.php'); 

$connection = database_connect();

// check if there are any bookings
$bookings = get_bookings($connection);


if($bookings == null){
  echo '<h3>There are no bookings</h3>';

    // array containing data
    $array = array(
      "u1@p.it" => 2,
      "u2@p.it" => 1,
      "u3@p.it" => 1
   );
  
  $sqldata = serialize($array);
  echo $sqldata;

  echo '<br> <br>';
  $items = unserialize($sqldata);

  $keys = array_keys($items);
  foreach($keys as $key) {

    if ($key === "u1@p.it"){
      echo '<b> user ' . $key .  ' ' . '(' . $items[$key] . ' ';
      echo (($items[$key] === 1) ? 'passenger' : 'passengers' );
      echo  ')' . ', </b>';
    }
else {
    echo 'user ' . $key .  ' ' . '(' . $items[$key] . ' ';
    echo (($items[$key] === 1) ? 'passenger' : 'passengers' );
    echo ')' . ', ';
}

  }


  echo '<br> <br>';
  unset($array["u2@p.it"]);
  $sqldata = serialize($array);
  echo $sqldata;

  echo '<br> <br>';
  $items = unserialize($sqldata);

  $keys = array_keys($items);
  $total = 0;
  foreach($keys as $key) {
    $total += $items[$key];
  }




  echo '<br> <br>';
  echo 'total ' . $total .': ';

  foreach($keys as $key) {

    if ($key === "u3@p.it"){
      echo '<b> user ' . $key .  ' ' . '(' . $items[$key] . ' ';
      echo (($items[$key] === 1) ? 'passenger' : 'passengers' );
      echo  ')' . ', </b>';
    }
    else{
    echo 'user ' . $key .  ' ' . '(' . $items[$key] . ' ';
    echo (($items[$key] === 1) ? 'passenger' : 'passengers' );
    echo  ')' . ', ';
    }
  }

  // Empty string when using an empty array:
  // var_dump(implode('hello', array())); // string(0) ""
}

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