<?php
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'dumperdogg';

// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}

// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}

// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, password FROM user_accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo 'Username exists, please choose another!';
	} else {
		// Username doesn't exists, insert new account
		if ($stmt = $con->prepare('INSERT INTO user_accounts (username, password) VALUES (?, ?)')) {
			// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$stmt->bind_param('ss', $_POST['username'], $password);

			// Execute the statement and check if it was successful.
			if ($stmt->execute()) {
				// Start the session.
				session_start();

				// Store some session variables.
				$_SESSION['loggedin'] = true;
				$_SESSION['username'] = $_POST['username'];

				// Redirect the user to the dashboard page.
				header('Location: ../dashboard.php');
				exit();
			} else {
				// Display an error message if the execution failed.
				echo 'ERROR: ' . $stmt->error;
			}
			$stmt->close();
		} else {
			// Display an error message if the prepare statement failed.
			echo 'ERROR: username and password required';
		}
	}
	$stmt->close();
} else {
	// Display an error message if the prepare statement failed.
	echo 'ERROR: ' . $con->error;
}

$con->close();
