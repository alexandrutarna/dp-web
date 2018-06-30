<?php
require 'init.php';

$shuttleCapacity = 4;

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
    $md5_password = md5($password);

    if($passDB === $md5_password)
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


?>
