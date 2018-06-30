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

echo 'UID:' . $_SESSION['uid'];

// check if there are any bookings
$bookings = get_bookings($connection);

if($bookings == null){
	echo '<h3>There are no bookings</h3>';

}
else
{
	echo '<h3>There are no bookings</h3>';

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