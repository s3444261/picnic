<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

class AdministrationController {
	
	// Displays the Administration Page.
	public function display() {
		$users = new Users ();
		$_SESSION ['users'] = $users->getUsers ();
		
		if (isset ( $_POST ['add'] )) {
			unset ( $_POST ['add'] );
			$user = new Users ();
			$v = new Validation ();
			
			// Process on submission of user.
			if (isset ( $_POST ['user'] )) {
				
				// Validate the user.
				try {
					$v->userName ( $_POST ['user'] );
				} catch ( ValidationException $e ) {
					$_SESSION ['error'] = $e->getError ();
				}
				
				if (isset ( $_SESSION ['error'] )) {
					unset ( $_POST ['user'] );
					header ( 'Location: Administration' );
				} else {
					$user->user = $_POST ['user'];
					unset ( $_POST ['user'] );
					
					// Validate the email.
					try {
						$v->email ( $_POST ['email'] );
					} catch ( ValidationException $e ) {
						$_SESSION ['error'] = $e->getError ();
					}
					
					if (isset ( $_SESSION ['error'] )) {
						unset ( $_POST ['email'] );
						header ( 'Location: Administration' );
					} else {
						$user->email = $_POST ['email'];
						unset ( $_POST ['email'] );
						
						// Validate the password.
						try {
							$v->password ( $_POST ['password'] );
						} catch ( ValidationException $e ) {
							$_SESSION ['error'] = $e->getError ();
						}
						
						if (isset ( $_SESSION ['error'] )) {
							unset ( $_POST ['password'] );
							unset ( $_POST ['confirm'] );
							header ( 'Location: Administration' );
						} else {
							
							// Compare passwords.
							try {
								$v->comparePasswords ( $_POST ['password'], $_POST ['confirm'] );
							} catch ( ValidationException $e ) {
								$_SESSION ['error'] = $e->getError ();
							}
							
							if (isset ( $_SESSION ['error'] )) {
								unset ( $_POST ['password'] );
								unset ( $_POST ['confirm'] );
								header ( 'Location: Administration' );
							} else {
								$user->password = $_POST ['password'];
								unset ( $_POST ['password'] );
								unset ( $_POST ['confirm'] );
								$user->set ();
								$user->get();
								$user->activate ();
								$_SESSION ['message'] = 'User Added';
								header ( 'Location: Administration' );
							}
						}
					}
				}
			}
		}
		
		if (isset ( $_POST ['update'] )) {
			unset ( $_POST ['update'] );
			$user = new Users ();
			$v = new Validation ();
				
			// Process on submission of user.
			if (isset ( $_POST ['user'] )) {
		
				// Validate the user.
				try {
					$v->userName ( $_POST ['user'] );
				} catch ( ValidationException $e ) {
					$_SESSION ['error'] = $e->getError ();
				}
		
				if (isset ( $_SESSION ['error'] )) {
					unset ( $_POST ['user'] );
					header ( 'Location: Administration' );
				} else {
					$user->user = $_POST ['user'];
					unset ( $_POST ['user'] );
						
					// Validate the email.
					try {
						$v->email ( $_POST ['email'] );
					} catch ( ValidationException $e ) {
						$_SESSION ['error'] = $e->getError ();
					}
						
					if (isset ( $_SESSION ['error'] )) {
						unset ( $_POST ['email'] );
						header ( 'Location: Administration' );
					} else {
						$user->email = $_POST ['email'];
						unset ( $_POST ['email'] );
						$user->status = $_POST ['status'];
						unset ( $_POST ['status'] );
						$user->userID = $_POST ['userID'];
						unset ( $_POST ['userID'] );
						$user->update ();
						header ( 'Location: Administration' );
					}
				}
			}
		}
		
		if (isset ( $_POST ['changePassword'] )) {
			unset ( $_POST ['changePassword'] );
			$user = new Users ();
			$v = new Validation ();
			
			if (isset ( $_POST ['password'] ) && isset ( $_POST ['confirm'] )) {
			
				// Validate the password.
				try {
					$v->password ( $_POST ['password'] );
				} catch ( ValidationException $e ) {
					$_SESSION ['error'] = $e->getError ();
				}
	
				if (isset ( $_SESSION ['error'] )) {
					unset ( $_POST ['password'] );
					unset ( $_POST ['confirm'] );
					header ( 'Location: Administration' );
				} else {
						
					// Compare passwords.
					try {
						$v->comparePasswords ( $_POST ['password'], $_POST ['confirm'] );
					} catch ( ValidationException $e ) {
						$_SESSION ['error'] = $e->getError ();
					}
						
					if (isset ( $_SESSION ['error'] )) {
						unset ( $_POST ['password'] );
						unset ( $_POST ['confirm'] );
						header ( 'Location: Administration' );
					} else {
						$user->password = $_POST ['password'];
						unset ( $_POST ['password'] );
						unset ( $_POST ['confirm'] );
						$user->userID = $_POST ['userID'];
						unset ( $_POST ['userID'] );
						$user->update ();
						header ( 'Location: Administration' );
					}
				}
			}
		}
		
		$routes = Routes::getInstance ();
		
		if ($routes->action == 'Edit' && $routes->id > 0) {
			$user = new Users ();
			$user->userID = $routes->id;
			if($user->get ()){
				$_SESSION ['editUser'] = array('userID' => $user->userID,
												'user' => $user->user,
												'email' => $user->email,
												'status' => $user->status
				);
			}
			
			include 'view/layout/editUser.php';
		} elseif ($routes->action == 'Delete' && $routes->id > 0) {
				$user = new Users ();
				$user->userID = $routes->id;
				$user->delete();
				header('Location: ' . PATH . 'Administration');			
		} else {
	include 'view/layout/administration.php';
}
	}
}
?>