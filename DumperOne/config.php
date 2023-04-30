<?php
//Database credentials.
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'dumperdogg');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}




// The below function will check if the user is logged-in and also check the remember me cookie
function check_loggedin($link, $redirect_file = 'index.php') {
	// If you want to update the "last seen" column on every page load, you can uncomment the below code
	/*
	if (isset($_SESSION['loggedin'])) {
		$date = date('Y-m-d\TH:i:s');
		$stmt = $con->prepare('UPDATE accounts SET last_seen = ? WHERE id = ?');
		$stmt->bind_param('si', $date, $id);
		$stmt->execute();
		$stmt->close();
	}
	*/
	// Check for loggedin session variable
    if (!isset($_SESSION['loggedin'])) {
	// If the user is not logged-in, redirect to the login page.
    	header('Location: ' . $redirect_file);
    	exit;
    }
}
?>