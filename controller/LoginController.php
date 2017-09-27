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

class LoginController {
	
	// Log the user in.
	public function index() {
		
		// Process login form.
		if (isset ( $_POST ['email'] ) && isset ( $_POST ['password'] )) { 
			
			$validate = new Validation ();
			
			// Validate the email name.
			try {
				$validate->email ( $_POST ['email'] );
			} catch ( ValidationException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			
			if (! isset ( $_SESSION ['error'] )) {
				
				// Validate the password
				try {
					$validate->password ( $_POST ['password'] );
				} catch ( ValidationException $e ) {
					$_SESSION ['error'] = $e->getError ();
				}
				
				if (! isset ( $_SESSION ['error'] )) {
					$user = new User ();
					$user->email = $_POST ['email'];
					$user->password = $_POST ['password'];
					unset ( $_POST ['email'] );
					unset ( $_POST ['password'] );
					
					if (! isset ( $_SESSION ['error'] )) { 
						
						// Attempt the login.
						try {
							if ($user->login ())
							{
								$_SESSION ['message'] = 'Logged In';
							} else {
								$_SESSION ['error'] = 'Not Logged In - No current session.';
							}

							if(isset($_SESSION ['userID']) && $_SESSION ['userID'] > 0){
								header ( 'Location: Home' );
							}
						} catch ( ValidationException $e ) {
							$_SESSION ['error'] = $e->getError ();
						}
						
						if (isset ( $_SESSION [MODULE] )) {
							header ( 'Location: Home' );
						} else { 
							header ( 'Location: Home' ); 
						} 
					} else {
						header ( 'Location: Home' );
					} 
				} else {
					header ( 'Location: Home' );
				} 
			} else {
				header ( 'Location: Home' );
			} 
		}  
	}
}
?>