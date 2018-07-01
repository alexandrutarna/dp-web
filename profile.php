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

$my_username = get_username_by_id($connection, $_SESSION['uid']);

$rcvd_departure = '';
$rcvd_destination = '';
if(isset($_POST['submit'])) {
	if (!isset($_POST['departure']) || $_POST['departure'] === '')
		$error[] = 'Invalid departure';
	if (!isset($_POST['destination']) || $_POST['destination'] === '')
		$error[] = 'Invalid destination';
	
	$rcvd_departure = sanitizeString($_POST['departure']);
	$rcvd_departure = strtoupper($rcvd_departure);
	$rcvd_destination = sanitizeString($_POST['destination']);
	$rcvd_destination = strtoupper($rcvd_destination);
	$rcvd_nr_passengers = sanitizeString($_POST['nr_passengers']);

	echo $rcvd_departure . '<br>';
	echo $rcvd_destination . '<br>';
	echo $rcvd_nr_passengers . '<br>';

	if ($rcvd_departure === '' || $rcvd_destination === ''
		|| $rcvd_departure >= $rcvd_destination ){
		echo "<b>Your itinerary is not valid!</b>" . '<br>';
	}
	else
	{
		//valid itinerary


		if (create_booking($connection, $my_username, $rcvd_departure, $rcvd_destination, $rcvd_nr_passengers)){
			// success
			echo '<b id="book_ok">Booking confirmed!</b>' . '<br>';
		}
		else{
			echo "<b>Booking failed! Try again.</b>" . '<br>';
		}



		// $x = get_max($connection, $rcvd_departure, $rcvd_destination) ;
		// echo 'maaaaaax - ' . $x;  



		}  // ELSE

	} // POST

	// echo 'result is:' . enough_places($connection, $rcvd_departure, $rcvd_destination, $rcvd_nr_passengers);




// check if there are any bookings
$bookings = get_bookings($connection);


if($bookings == null ){
	echo '<h3>There are no bookings</h3>  <br>' ;

	?>

	<!-- HTML -->
	<form name = "book" action="profile.php" method="POST" onsubmit="return validateForm()">
	<p>
	  <label>Deparure:</label>
	  <input type="text" name="departure" maxlength="50" required="required"  placeholder="your departure point"><br />
	</p>
	<p>
	  <label>Destination: </label>
	  <input type="text" name="destination" maxlength="50" required="required" placeholder="your destination point"><br />	
	</p>
	<p>
		<label>Passengers: </label>
		<input type="number" name="nr_passengers" maxlength="2" min="1" required="required" placeholder="number of passengers"><br />	
	</p>
	<p>
	  <input class = "button" type="submit" value="Book Trip" name="submit" />
	</p>
	</form>
	
	<!-- include JS -->
	<script type="text/javascript" src="jsfunctions.js"></script>


	<?php
	}   // end if bookings = null  

	else
	{   // if bookins is not null

		// check if user has booked
		$user_dep = '';
		$user_dest = '';
		$user_nr_passengers = '' ;
		$has_booked = false;


		foreach($bookings as $booking ){

			$username = $booking['username'];
			$departure = $booking['departure'];
			$destination = $booking['destination'];
			$nr_passengers = $booking['nr_passengers'];
		
			if ($my_username == $username){
				$user_dep = $departure;
				$user_dest = $destination;
				$user_nr_passengers = $nr_passengers;
				$has_booked = true;
			}
		}

		$has_booked = false;
		if($has_booked){

			// echo $user_dep . '<br>';
			// echo $user_dest . '<br>';
			// echo $user_nr_passengers . '<br>';

			echo "<table border='1'>
			<tr>
			<th>departure</th>
			<th>destination</th>
			<th>passengers</th>
			</tr>";

			echo "<td align='center'>" . $user_dep . "</td>";
			echo "<td align='center'>" . $user_dest . "</td>";
			echo "<td align='center'>" . $user_nr_passengers . "</td>";




		}
		
		else {
			// if not booked start
			?>	
		
			<!-- HTML -->
			<form name = "book" action="profile.php" method="POST" onsubmit="return validateForm()">
			<p>
			  <label>Deparure:</label>
			  <input type="text" name="departure" maxlength="50" required="required"  placeholder="your departure point"><br />
			</p>
			<p>
			  <label>Destination: </label>
			  <input type="text" name="destination" maxlength="50" required="required" placeholder="your destination point"><br />	
			</p>
			<p>
			  <label>Passengers: </label>
			  <input type="number" name="nr_passengers" maxlength="2" min="1" required="required" placeholder="number of passengers"><br />	
			</p>
			<p>
			  <input class = "button" type="submit" value="Book Trip" name="submit" />
			</p>
			</form>
			
			<!-- include JS -->
			<script type="text/javascript" src="jsfunctions.js"></script>
		
		
			<?php
			} // else if not booked end





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
		

	echo "<table border='1'>
		<tr>
		<th>departure</th>
		<th>destination</th>
		<th>passengers</th>
		</tr>";

	$users_txt = array();
	$totals = array();
	for ($i=0; $i< $len_dep; $i++){

		$total = 0;
		$str = "";
		foreach($bookings as $booking ){

			$username = $booking['username'];
			$departure = $booking['departure'];
			$destination = $booking['destination'];
			$nr_passengers = $booking['nr_passengers'];
	
			if ($departure <= $myDep[$i] && $destination >= $myDest[$i]){

				if ($my_username !== $username){
					$str .= ($username . ' (' . $nr_passengers .  (($nr_passengers==1) ? ' passenger' : ' passengers') .  '), ');
				
					}
					else
					{
						$str .= ('<b>' . $username . ' (' . $nr_passengers .  (($nr_passengers==1) ? ' passenger' : ' passengers') .  ')</b>, ');
					}
				$total += $nr_passengers;
			}
		}
		
		

		if ($total == 0){
			$str = 'empty';
		}
		else{
			$str = explode(',', $str);
			unset($str[count($str)-1]);
			$str = implode(',', $str);
		}

			echo "<tr>";
		if ($user_dep !== $myDep[$i]){
			echo "<td align='center'>" . $myDep[$i] . "</td>";
		}
		else{
			echo "<td align='center'>" . '<b>' . $myDep[$i] . '</b>' . "</td>";
		}
		if ($user_dest !== $myDest[$i]){
		echo "<td align='center'>" . $myDest[$i] . "</td>";
		}
		else{
			echo "<td align='center'>" . '<b>' . $myDest[$i] . '</b>' . "</td>";
		}
		echo "<td>" . 'total ' . $total . ': ' . $str . "</td>";
		echo "</tr>";

}

}
require('footer.php'); 
?>