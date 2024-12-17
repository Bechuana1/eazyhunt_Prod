<?php
session_start();

// Define database
define('dbhost', 'localhost');
define('dbuser', 'root');
define('dbpass', '');
define('dbname', 'final_db');


// Connecting database
try {
	$connect = new PDO("mysql:host=" . dbhost . ";dbname=" . dbname, dbuser, dbpass);
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo 'connected';
	function getUniqueDeviceIdentifier() {
		$ip = $_SERVER['REMOTE_ADDR']; // Get user's IP address
		$user_agent = $_SERVER['HTTP_USER_AGENT']; // Get user agent string

		// Generate a unique identifier using IP address and user agent
		$unique_identifier = md5($ip . $user_agent);

		return $unique_identifier;
	}

	// echo $unique_identifier;
} catch (PDOException $e) {
	echo $e->getMessage();
}

	// #NOTE => made it talk to the local database
