<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 *
 * index.php
 * This is the only file that is accessed in the application.
 * It calls the appropriate views as required.
 */

// Start a session if one hasn't already been started.
if (! isset ( $_SESSION )) {
	session_start ();
}

// Include config file.
include 'config/config.php';

// Call a singleton instance of the Driver class.
$driver = Driver::getInstance ();

// Display the required content.
$driver->display ();

?>