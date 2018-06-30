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



  class indexRow { 
    public $departure = ''; 
    public $destination = '';
    public $total = 0; 
    
    // function aMemberFunc() { 
    //     print 'Inside `aMemberFunc()`'; 
    // } 
}


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

// $dep = array ();
// $dest = array ();



// foreach($bookings as $booking) {

//   $departure = $booking['departure'];
//   $destination = $booking['destination'];

//   $dep[$departure] = $departure;
//   $dest[$destination] = $destination;
// }


// $dep_str = implode("," , $dep);
// echo 'departure: ' . $dep_str . '<br>';

// $dest_str = implode("," , $dest);
// echo 'destination: ' . $dest_str . '<br>';

// // create itinerary list
// $itinerary = $dep + $dest;
// sort($itinerary);
// $itineray_str = implode("," , $itinerary);

// echo 'itinerary: ' . $itineray_str . '<br>';




// echo '<br>';
// // number of destination in the itineray 
// $itinerary_length = count($itinerary)-1;
// echo 'itinerary_length: ' . $itinerary_length . '<br>';

// echo '<br>';
// echo 'myDep' . '<br>';

// $myDep = array();
// $i = 0;
// foreach($itinerary as $point) {
//   // echo $i . ' ' . $point . "<br>";
//   $myDep[$i] = $point;
//   $i++;
// }



// $length = count($myDep)-1;
// // unset($myDep[$length]);

// $myDest = array();
// $i = 0;
// foreach($myDep as $point) {

//   if ($i >= 0 && $i < $length){
//     $myDest[$i] = $myDep[$i+1];
//   }
//   else 
//     if ($i == $length) {
//       $myDest[$i] = $myDep[$i-1];
//     }
//   $i++;
// }

// $dep_len = count($myDep)-1;
// unset($myDep[$dep_len]);

// $dep_str = implode("," , $myDep);
// echo 'departure: ' . $dep_str . '<br>';



// $dest_len = count($myDest)-1;
// unset($myDest[$dest_len]);

// $dest_str = implode("," , $myDest);
// echo 'destination: ' . $dest_str . '<br>';

// echo '<br>';

// echo 'length ' . $length . '<br>';



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




}

require('footer.php'); 
?>