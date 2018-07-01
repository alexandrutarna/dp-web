<?php
require 'init.php';



// connect to database
function database_connect() {
    global $host, $user, $pwd, $db;
    $connection = @new mysqli($host, $user, $pwd, $db);
    if($connection->connect_error) {
      die('<h1>Database not working</h1>');
    }
    if(!$connection) {
      die('<h1>Cannot connect to database</h1>');
    }
    unset($host);
    unset($user);
    unset($pwd);
    unset($db);
    $connection->autocommit(false);
    //$connection->autocommit(0);
  
    return $connection;
  }

function sanitizeString($var)
  {
      $var = strip_tags($var);
      $var = htmlentities($var);
      $var = stripslashes($var);
      return mysql_real_escape_string($var);
  }


// ======= AUTH =========================== 
function login_user($user_id) {
	
	$_SESSION['logged_in'] = true;
	$_SESSION['uid'] = $user_id;
	update_session_time();
}

function logged_in() {
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
		return true;
		
	return false;
}

function logout() {
    destroySession();
	header('Location: index.php');
	die();
}

// ========================================


// ======= SESSION ========================
function save_session($user_id) {
	
	$_SESSION['logged_in'] = true;
	$_SESSION['uid'] = $user_id;
	update_session_time();
}

function update_session_time() {
	$_SESSION['last_activity'] = time();
}


function destroySession() {
    session_unset();
    session_destroy();
}
// ========================================


function user_exists($connection, $username){
   $result = $connection->query("SELECT username FROM users WHERE username = '$username'");
    if($result && $result->num_rows != 0)
        return true;
    else 
        return false;
}

function password_matches($connection, $username, $password){
    $result = $connection->query("SELECT password FROM users WHERE username = '$username'");
    $row = $result->fetch_assoc();
    $passDB = $row['password'];

    if($passDB === md5($password))
        return true;
    else 
        return false;
 }


function get_user_id($connection, $username){
    $result = $connection->query("SELECT uid FROM users WHERE username = '$username'");
    $row = $result->fetch_assoc();
    $user_id = $row['uid'];
    return $user_id;
 }

 function get_username_by_id($connection, $uid){
    $result = $connection->query("SELECT username FROM users WHERE uid = '$uid'");
    $row = $result->fetch_assoc();
    $username = $row['username'];
    return $username;
 }

 function get_bookings($connection){
    $result = $connection->query("SELECT * FROM bookings ");
    
    if($result && $result->num_rows != 0) {
        $rows = [];
        while($row = $result->fetch_assoc())
            $rows[] = $row;
        return $rows;
    }
    return null;
}


function create_booking( $connection, $username, $dep, $dest, $psg){

    if(!enough_places($connection, $dep, $dest, $psg)){
        return false;
    }
    else
        {
        $myQuery = "INSERT INTO  bookings(username, departure, destination, nr_passengers)
         VALUES ('$username', '$dep', '$dest', $psg)";
        $result = $connection->query($myQuery);

        if(!$connection->commit()) {
            return false;
        }
    }
    return true;
}


function enough_places($connection, $dep, $dest, $psg_nr){
    global $shuttleCapacity;
    $myQuery = "SELECT *  FROM  bookings 
                WHERE departure >= '$dep' and destination <= '$dest' FOR UPDATE";
    $result = $connection->query($myQuery);

    if($result && $result->num_rows != 0) {
        $rows = [];
        while($row = $result->fetch_assoc())
            $rows[] = $row;

            $max = get_max($connection, $dep, $dest);

            echo '<br>';
            echo ' tot ' . $i . ' ' . $total . '<br>';
            echo ' max ' . $i . ' ' . $max . '<br>';

        if (($shuttleCapacity - $max) >= $psg_nr){

            echo '<br> cap111' . $shuttleCapacity . '<br>';
            echo $max . '<br>';
            echo $psg_nr . '<br>'; 
        return true;
        }else
            return false;  
    }

    $max = get_max($connection, $dep, $dest);
    if (($shuttleCapacity - $max) >= $psg_nr){
        echo '<br> cap 2222' . $shuttleCapacity . '<br>';
        echo $max . '<br>';
        echo $psg_nr . '<br>'; 
        return true;

    }
        
    else 
    return false;
}


function compute_max_per_trip($connection, $rows, $dep, $dest, $psg){

    $max = 0;
    $itn = get_itinerary_for_booking($rows);

    $myDep = $itn['departures'];
    $myDest = $itn['destinations'];

    $len_dep = count($myDep);
    for ($i=0; $i< $len_dep; $i++){
        $total = 0;
        foreach($rows as $row ){

            echo implode(',', $row);
            $departure = $row['departure'];
            $destination = $row['destination'];
            $nr_passengers = $row['nr_passengers'];
    
            if ($departure <= $myDep[$i] && $destination >= $myDest[$i]){
                $total += $nr_passengers;
                if ($total>$max)
                    $max = $total;
            }
            echo '<br>';
            echo ' tot ' . $i . ' ' . $total . '<br>';
            echo ' max ' . $i . ' ' . $max . '<br>';
        }

    }
    return $max;
}

function get_itinerary($connection){

    $bookings = get_bookings($connection);

    $dep = array ();
    $dest = array ();

    foreach($bookings as $booking) {

        $departure = $booking['departure'];
        $destination = $booking['destination'];
      
        $dep[$departure] = $departure;
        $dest[$destination] = $destination;
      }

    // create itinerary list
    $itinerary = $dep + $dest;
    sort($itinerary);

    $myDep = array();
    $i = 0;
    foreach($itinerary as $point) {
    // echo $i . ' ' . $point . "<br>";
    $myDep[$i] = $point;
    $i++;
    }

    $length = count($myDep)-1;
    $myDest = array();
    $i = 0;
    foreach($myDep as $point) {
    
      if ($i >= 0 && $i < $length){
        $myDest[$i] = $myDep[$i+1];
      }
      else 
        if ($i == $length) {
          $myDest[$i] = $myDep[$i-1];
        }
      $i++;
    }

    // remove last entries 
    $dep_len = count($myDep)-1;
    unset($myDep[$dep_len]);

    $dest_len = count($myDest)-1;
    unset($myDest[$dest_len]);

    $result = array(
        "departures" => $myDep,
        "destinations" => $myDest
     );
    return $result;
}



function get_itinerary_for_booking($rows){
    $dep = array ();
    $dest = array ();
    
    foreach($rows as $row) {

        $departure = $row['departure'];
        $destination = $row['destination'];
      
        $dep[$departure] = $departure;
        $dest[$destination] = $destination;
      }

    // create itinerary list
    $itinerary = $dep + $dest;
    sort($itinerary);
    echo implode (',', $itinerary);

    $myDep = array();
    $i = 0;
    foreach($itinerary as $point) {
    // echo $i . ' ' . $point . "<br>";
    $myDep[$i] = $point;
    $i++;
    }

    $length = count($myDep)-1;
    $myDest = array();
    $i = 0;
    foreach($myDep as $point) {
    
      if ($i >= 0 && $i < $length){
        $myDest[$i] = $myDep[$i+1];
      }
      else 
        if ($i == $length) {
          $myDest[$i] = $myDep[$i-1];
        }
      $i++;
    }

    // remove last entries 
    $dep_len = count($myDep)-1;
    unset($myDep[$dep_len]);

    $dest_len = count($myDest)-1;
    unset($myDest[$dest_len]);

    $result = array(
        "departures" => $myDep,
        "destinations" => $myDest
     );
    return $result;
}


function make_itinerary($connection, $rcv_dep, $rcv_dest){

    $bookings = get_bookings($connection);

    $dep = array ();
    $dest = array ();

    foreach($bookings as $booking) {

        $departure = $booking['departure'];
        $destination = $booking['destination'];
      
        $dep[$departure] = $departure;
        $dest[$destination] = $destination;
      }


      $dep[$rcv_dep] = $rcv_dep;
      $dest[$rcv_dest] = $rcv_dest;


      // create itinerary list
    $itinerary = $dep + $dest;
    sort($itinerary);

    $myDep = array();
    $i = 0;
    foreach($itinerary as $point) {
    // echo $i . ' ' . $point . "<br>";
    $myDep[$i] = $point;
    $i++;
    }

    $length = count($myDep)-1;
    $myDest = array();
    $i = 0;
    foreach($myDep as $point) {
    
      if ($i >= 0 && $i < $length){
        $myDest[$i] = $myDep[$i+1];
      }
      else 
        if ($i == $length) {
          $myDest[$i] = $myDep[$i-1];
        }
      $i++;
    }

    // remove last entries 
    $dep_len = count($myDep)-1;
    unset($myDep[$dep_len]);

    $dest_len = count($myDest)-1;
    unset($myDest[$dest_len]);

    $result = array(
        "departures" => $myDep,
        "destinations" => $myDest,
        "itinerary" => $itinerary
     );
    return $result;
    }




    function get_max($connection, $rcvd_departure, $rcvd_destination){

        $itn = make_itinerary($connection, $rcvd_departure, $rcvd_destination);

		$myDep = $itn['departures'];
		$myDest = $itn['destinations'];
	
		$dep_str = implode("," , $myDep);
		echo 'departures: ' . $dep_str . '<br>';
	
		$dest_str = implode("," , $myDest);
		echo 'destinations: ' . $dest_str . '<br>';
	
		$len_dep = count($myDep);
		echo 'len_dep ' . $len_dep . '<br>';

		// check if there are any bookings
		$bookings = get_bookings($connection);     ////// TO BE REMOVED

		$max = 0;
		for ($i=0; $i< $len_dep; $i++){
		
		$total = 0;
		foreach($bookings as $booking ){

			$username = $booking['username'];
			$departure = $booking['departure'];
			$destination = $booking['destination'];
			$nr_passengers = $booking['nr_passengers'];
	

			// if ( ($departure <= $myDep[$i]) && ($destination >= $myDest[$i]) ){

			if ( $departure <= $myDep[$i] && $destination >= $myDest[$i] ){

				if ( $myDep[$i] >= $rcvd_departure && $myDest[$i] <= $rcvd_destination){
				$total += $nr_passengers;

				echo '------- ' . $i . '<br>';
				echo '$departure ' . $departure . '<br>';
				echo '$myDep[$i] ' .  $myDep[$i]. '<br>';
				echo '$destination ' . $destination . '<br>';
				echo '$myDest[$i] ' . $myDest[$i] . '<br>';
				echo '$rcvd_departure ' . $rcvd_departure. '<br>';
				echo '$rcvd_destination ' . $rcvd_destination. '<br>';

				echo 'total ' . $total . '<br>';
			}
		}

			if ($total > $max)
				$max = $total;
		}
		echo 'max ' . $max . '<br>';
    }
    
    return $max;
    }


?>
