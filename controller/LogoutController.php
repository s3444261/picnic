<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

require_once __DIR__ . '/../model/User.php';

class LogoutController
{
	// Log the user out.
	public function index() {
		$user = new User ();
		$user->logout ();
		header ( 'Location: Home' );
	}
}