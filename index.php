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


echo '<br> <br>';

echo "<table border='1'>
  <tr>
    <th>departure</th>
    <th>destination</th>
    <th>nr_passengers</th>
  </tr>";

$itn = get_itinerary($connection);

$myDep = $itn['departures'];
$myDest = $itn['destinations'];

$dep_str = implode("," , $myDep);
echo 'departures: ' . $dep_str . '<br>';

$dest_str = implode("," , $myDest);
echo 'destinations: ' . $dest_str . '<br>';

$len_dep = count($myDep);
echo 'len_dep ' . $len_dep . '<br>';

for ($i=0; $i< $len_dep; $i++){
 echo "<tr>";
  echo "<td>" . $myDep[$i] . "</td>";
  echo "<td>" .$myDest[$i] . "</td>";
  // echo $myDep[$i] . ' --> ' . $myDest[$i] . ' ';

  $total = 0;
  foreach($bookings as $booking ){
    $user_id = $booking['user_id'];
    $departure = $booking['departure'];
    $destination = $booking['destination'];
    $nr_passengers = $booking['nr_passengers'];
  
    if ($departure <= $myDep[$i] && $destination >= $myDest[$i]){
      // echo $user_id . ' ' . $nr_passengers .  ', ';
      $total += $nr_passengers;
    }
 

  }
  echo "<td>" . $total . "</td>";
  echo "</tr>";

}

}

require('footer.php'); 
?>