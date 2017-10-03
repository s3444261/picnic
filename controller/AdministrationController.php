<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

require_once  __DIR__ . '/../config/Picnic.php';

class AdministrationController extends BaseController  {

	/**
	 * Displays the main admin page.
	 */
	public function index() : void {

		if ($this->auth())
		{
			$users = new Users (Picnic::getInstance());
			$_SESSION ['users'] = $users->getUsers ();

			if (isset ( $_POST ['add'] )) {
				unset ( $_POST ['add'] );
				$this->add();
			}

			if (isset ( $_POST ['update'] )) {
				unset ( $_POST ['update'] );
				$this->update();
			}

			if (isset ( $_POST ['changePassword'] )) {
				unset ( $_POST ['changePassword'] );
				$this->changePassword();
			}

			$this->RenderInMainTemplate('view/layout/administration.php');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Adds a new user, then displays the main admin page.
	 */
	public function add(): void
	{
		if ($this->auth()) {
			$user = new User (Picnic::getInstance());
			$v = new Validation ();

			// Process on submission of user.
			if (isset ($_POST ['user'])) {

			// Validate the user.
			try {
				$v->userName($_POST ['user']);
			} catch (ValidationException $e) {
				$_SESSION ['error'] = $e->getError();
			}

			if (isset ($_SESSION ['error'])) {
				unset ($_POST ['user']);
				header('Location: Administration');
			} else {
				$user->user = $_POST ['user'];
				unset ($_POST ['user']);

				// Validate the email.
				try {
					$v->email($_POST ['email']);
				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
				}

				if (isset ($_SESSION ['error'])) {
					unset ($_POST ['email']);
					header('Location: Administration');
				} else {
					$user->email = $_POST ['email'];
					unset ($_POST ['email']);

					// Validate the password.
					try {
						$v->password($_POST ['password']);
					} catch (ValidationException $e) {
						$_SESSION ['error'] = $e->getError();
					}

					if (isset ($_SESSION ['error'])) {
						unset ($_POST ['password']);
						unset ($_POST ['confirm']);
						header('Location: Administration');
					} else {

						// Compare passwords.
						try {
							$v->comparePasswords($_POST ['password'], $_POST ['confirm']);
						} catch (ValidationException $e) {
							$_SESSION ['error'] = $e->getError();
						}

						if (isset ($_SESSION ['error'])) {
							unset ($_POST ['password']);
							unset ($_POST ['confirm']);
							header('Location: Administration');
						} else {
							$user->password = $_POST ['password'];
							unset ($_POST ['password']);
							unset ($_POST ['confirm']);
							$user->set();
							$user->get();
							$user->activate();
							$_SESSION ['message'] = 'User Added';

							header('Location: ' . BASE . '/Administration');
						}
					}
				}
			}
		}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Updates the user attributes if they are valid, then displays the main admin page.
	 */
	public function update(): void
	{
		if ($this->auth()) {
			$user = new User (Picnic::getInstance());
			$v = new Validation ();

			// Process on submission of user.
			if (isset ($_POST ['user'])) {

				// Validate the user.
				try {
					$v->userName($_POST ['user']);
				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
				}

				if (isset ($_SESSION ['error'])) {
					unset ($_POST ['user']);
					header('Location: Administration');
				} else {
					$user->user = $_POST ['user'];
					unset ($_POST ['user']);

					// Validate the email.
					try {
						$v->email($_POST ['email']);
					} catch (ValidationException $e) {
						$_SESSION ['error'] = $e->getError();
					}

					if (isset ($_SESSION ['error'])) {
						unset ($_POST ['email']);
						header('Location: Administration');
					} else {
						$user->email = $_POST ['email'];
						unset ($_POST ['email']);
						$user->status = $_POST ['status'];
						unset ($_POST ['status']);
						$user->userID = $_POST ['userID'];
						unset ($_POST ['userID']);
						$user->update();

						header('Location: ' . BASE . '/Administration');
					}
				}
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Updates the user password if it is valid, then displays the main admin page.
	 */
	public function changePassword(): void
	{
		if ($this->auth()) {
			$user = new User (Picnic::getInstance());
			$v = new Validation ();

			if (isset ($_POST ['password']) && isset ($_POST ['confirm'])) {

				// Validate the password.
				try {
					$v->password($_POST ['password']);
				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
				}

				if (isset ($_SESSION ['error'])) {
					unset ($_POST ['password']);
					unset ($_POST ['confirm']);
					header('Location: Administration');
				} else {

					// Compare passwords.
					try {
						$v->comparePasswords($_POST ['password'], $_POST ['confirm']);
					} catch (ValidationException $e) {
						$_SESSION ['error'] = $e->getError();
					}

					if (isset ($_SESSION ['error'])) {
						unset ($_POST ['password']);
						unset ($_POST ['confirm']);
						header('Location: Administration');
					} else {
						$user->password = $_POST ['password'];
						unset ($_POST ['password']);
						unset ($_POST ['confirm']);
						$user->userID = $_POST ['userID'];
						unset ($_POST ['userID']);
						$user->update();

						header('Location: ' . BASE . '/Administration');
					}
				}
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Displays a page allowing editing of the user with the given ID.
	 */
	public function edit($id): void
	{
		if ($this->auth()) {
			$user = new User (Picnic::getInstance());
			$user->userID = $id;

			if ($user->get()) {
				$_SESSION ['editUser'] = [
					'userID' => $user->userID,
					'user' => $user->user,
					'email' => $user->email,
					'status' => $user->status
				];
			}

			$this->RenderInMainTemplate('view/layout/editUser.php');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Deletes the user with the given ID.
	 */
	public function delete($id): void
	{
		if ($this->auth()) {
			$user = new User (Picnic::getInstance());
			$user->userID = $id;
			$user->delete();
			header('Location: ' . BASE . '/Administration');
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
