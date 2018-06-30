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

echo '<br> <br>';

$dep = array ();
$dest = array ();



foreach($bookings as $booking) {

  $user_id = $booking['user_id'];
  $departure = $booking['departure'];
  $destination = $booking['destination'];
  $nr_passengers = $booking['nr_passengers'];

  $dep[$departure] = $departure;
  $dest[$destination] = $destination;

  // echo "<tr>";
  // echo "<td>" . $user_id . "</td>";
  // echo "<td>" . $departure . "</td>";
  // echo "<td>" . $destination . "</td>";
  // echo "<td>" . $nr_passengers . "</td>";
  // echo "</tr>";

  $arr = "";
  foreach($bookings as $booking2 ){
    $user_id2 = $booking2['user_id'];
    $departure2 = $booking2['departure'];
    $destination2 = $booking2['destination'];
    $nr_passengers2 = $booking2['nr_passengers'];


    if ($departure < $departure2 && $user_id != $user_id2){
      echo $departure . '<br>';
    }
    // echo $user_id2 . '<br>';
  }

}


$dep_str = implode("," , $dep);
echo 'departure: ' . $dep_str . '<br>';

$dest_str = implode("," , $dest);
echo 'departure: ' . $dest_str . '<br>';

$itinerary = $dep + $dest;
$itineray_str = implode("," , $itinerary);

echo 'itinerary: ' . $itineray_str . '<br>';



echo '<br>';
echo 'myDep' . '<br>';
$i = 0;
$myDep = array();
foreach($itinerary as $point) {
  echo $i . ' ' . $point . "<br>";
  $myDep[$i] = $point;
  $i++;
}


$i = 0;
$myDest = array();
$length = count($myDep)-1;
foreach($myDep as $point) {
  // echo $i . ' ' . $point . "<br>";

  if ($i >= 0 && $i < $length){
    // echo $i . ' ' . $myDep[$i+1] . "<br>";
    $myDest[$i] = $myDep[$i+1];
  }
  else if ($i == $length)
  {
    $myDest[$i] = $myDep[$i-1];
  }

  $i++;
}

echo '<br>';
echo 'myDest' . '<br>';
$i = 0;
foreach ($myDest as $dest){
  echo $i . ' ' . $dest . "<br>";
  $i++;
}


echo 'length ' . $length . '<br>';

$tmp = $myDep[$length];
$myDep[$length] = $myDest[$length];
$myDest[$length] = $tmp;

for ($i=0; $i< $length; $i++){

  echo $myDep[$i] . ' --> ' . $myDest[$i] . ' ';

  $total = 0;
  foreach($bookings as $booking ){
    $user_id = $booking['user_id'];
    $departure = $booking['departure'];
    $destination = $booking['destination'];
    $nr_passengers = $booking['nr_passengers'];
  
    // echo 'dep:  ' . $departure . '<br>'; 
    // echo 'dest: ' . $destination . '<br>'; 
    if ($departure <= $myDep[$i] && $destination >= $myDest[$i]){
      echo $user_id . ' ' . $nr_passengers .  ', ';
      $total += $nr_passengers;
    }
    
  }
echo  'total: ' . $total . '<br>';
}


// $arr = "";
// foreach($bookings as $booking ){
//   $user_id = $booking['user_id'];
//   $departure = $booking['departure'];
//   $destination = $booking['destination'];
//   $nr_passengers = $booking['nr_passengers'];

//   if ($departure >= $myDep[$i] && $departure <= $mydest[$i]){
//     echo $user_id . ' ' . $nr_passengers .  '<br>';
//   }
//   // echo $user_id2 . '<br>';
// }


}

require('footer.php'); 
?>