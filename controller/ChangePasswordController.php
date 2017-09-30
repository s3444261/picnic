<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

class ChangePasswordController {
	
	// Displays the Change Password Page.
	public function index() {

		if ($this->auth()) {
			if (isset ( $_POST ['changePassword'] )) {
				unset ( $_POST ['changePassword'] );
				$user = new User ();
				$v = new Validation ();

				if (isset ( $_POST ['password'] ) && isset ( $_POST ['newPassword'] ) && isset ( $_POST ['confirm'] )) {

					// Validate the password.
					try {
						$v->password ( $_POST ['password'] );
					} catch ( ValidationException $e ) {
						$_SESSION ['error'] = $e->getError ();
					}

					if (isset ( $_SESSION ['error'] )) {
						unset ( $_POST ['password'] );
						unset ( $_POST ['newPassword'] );
						unset ( $_POST ['confirm'] );
						header ( 'Location: ChangePassword' );
					} else {

						// Validate the new password.
						try {
							$v->password ( $_POST ['newPassword'] );
						} catch ( ValidationException $e ) {
							$_SESSION ['error'] = $e->getError ();
						}

						if (isset ( $_SESSION ['error'] )) {
							unset ( $_POST ['password'] );
							unset ( $_POST ['newPassword'] );
							unset ( $_POST ['confirm'] );
							header ( 'Location: ChangePassword' );
						} else {

							// Compare passwords.
							try {
								$v->comparePasswords ( $_POST ['newPassword'], $_POST ['confirm'] );
							} catch ( ValidationException $e ) {
								$_SESSION ['error'] = $e->getError ();
							}

							if (isset ( $_SESSION ['error'] )) {
								unset ( $_POST ['password'] );
								unset ( $_POST ['newPassword'] );
								unset ( $_POST ['confirm'] );
								header ( 'Location: ChangePassword' );
							} else {

								// Confirm current password is correct.
								$user->user = $_SESSION['user'];
								$user->email = $_SESSION['email'];
								$user->password = $_POST ['password'];
								$user->checkPassword();
								if ($user->userID != $_SESSION['userID']) {
									$_SESSION ['error'] = 'Password Error: Incorrect Password!';
									unset ( $_POST ['password'] );
									unset ( $_POST ['newPassword'] );
									unset ( $_POST ['confirm'] );
									header ( 'Location: ChangePassword' );

								} else {
									$user->password = $_POST ['newPassword'];
									$user->update ();
									unset ( $_POST ['password'] );
									unset ( $_POST ['newPassword'] );
									unset ( $_POST ['confirm'] );
									$_SESSION ['message'] = 'Password Changed!';
									header ( 'Location: Home' );
								}
							}
						}
					}
				}
			} else {
				include 'view/layout/changePassword.php';
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	private function auth(){
		if(isset($_SESSION[MODULE]) && isset($_SESSION['userID'])){
			if($_SESSION['userID'] > 0){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>