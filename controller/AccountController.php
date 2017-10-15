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
require_once  __DIR__ . '/../vendor/autoload.php';

class AccountController {

	public function Login()
	{
		$view = new View();
		$view->SetData('navData', new NavData(NavData::Account));
		$view->Render('signin');
	}

	public function DoLogin()
	{
		if (!$this->auth()) {
			if (isset ($_POST ['email']) && isset ($_POST ['password'])) {
				try {
					$validate = new Validation ();
					$validate->email($_POST ['email']);
					$validate->password($_POST ['password']);

					$user = new User (Picnic::getInstance());
					$user->email = $_POST ['email'];
					$user->password = $_POST ['password'];

					unset ($_POST ['email']);
					unset ($_POST ['password']);

					if ($user->login()) {
						$_SESSION[MODULE] = true;
						$view = new View();
						$view->SetData('navData', new NavData(NavData::Account));
						$view->Render('loggedIn');
						return;
					} else {
						$_SESSION ['error'] = 'Not Logged In - No current session.';
					}
				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
				}
			}
		}

		header('Location:Login');
	}

	public function Logout() {

		$user = new User (Picnic::getInstance());
		$user->logout();

		$view = new View();
		$view->SetData('navData',  new NavData(NavData::Account));
		$view->Render('loggedOut');
	}

	public function Register()
	{
		if (!$this->auth()) {
			$view = new View();
			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('register');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function DoRegister()
	{
		if (!$this->auth()) {
			if (isset ($_POST ['email']) && isset ($_POST ['password']) && isset ($_POST ['password2']) && isset ($_POST ['username'])) {
				try {
					$validate = new Validation ();
					$validate->email($_POST ['email']);
					$validate->userName($_POST ['username']);
					$validate->password($_POST ['password']);
					$validate->comparePasswords($_POST ['password'], $_POST ['password2']);

					$h = new Humphree(Picnic::getInstance());

					$userID = $h->addUser($_POST ['username'], $_POST ['email'], $_POST ['password']);

					if ($userID != 0) {
						unset ($_POST ['email']);
						unset ($_POST ['password']);
						unset ($_POST ['password2']);
						unset ($_POST ['username']);

						$this->sendActivationEmail($userID);

						header('Location: ' . BASE . '/Account/RegisterSuccess');
						return;
					} else {
						$_SESSION ['error'] = 'Registration failed.';
						$view = new View();
						$view->SetData('navData',  new NavData(NavData::Account));
						$view->Render('register');
					}
				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
					$view = new View();
					$view->SetData('navData',  new NavData(NavData::Account));
					$view->Render('register');
				}
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function RegisterSuccess()
	{
		if (!$this->auth()) {
			$view = new View();
			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('registerSuccessful');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function Activate($activationCode)
	{
		try {
			$h = new Humphree(Picnic::getInstance());
			$validate = new Validation();
			$validate->activation($activationCode);

			if ($activationCode != "") {

				$userId = $h->getUserIdByActivationCode($activationCode);

				if ($userId != 0) {
					if ($h->activateAccount($userId)) {
						$view = new View();
						$view->SetData('navData',  new NavData(NavData::Account));
						$view->Render('accountActivated');
						return;
					}
				}
			}
		} catch (ValidationException $e) {
			$_SESSION ['error'] = $e->getError();
		}

		$view = new View();
		$view->SetData('navData',  new NavData(NavData::Account));
		$view->Render('accountActivationFailed');
	}

	public function ForgotPassword() {
		if (!$this->auth()) {
			$view = new View();
			$view->SetData('navData', new NavData(NavData::Account));
			$view->Render('forgotPassword');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function DoForgotPassword() {
		if (!$this->auth()) {
			if (isset ($_POST ['email'])) {
				try {
					$validate = new Validation ();
					$validate->email($_POST ['email']);

					$h = new Humphree(Picnic::getInstance());
					$userID = $h->getUserIdByEmailAddress($_POST ['email']);

					if ($userID != 0) {
						$user = new User(Picnic::getInstance());
						$user->userID = $userID;

						// bit of a hack until getRandomPassword() consistently
						// generates valid passwords -- we'll keep generating until
						// we get a good one.
						$newPassword = '';
						while (true) {
							$newPassword = $user->getRandomPassword();

							try {
								$validate->password($newPassword);
								break;
							} catch (ValidationException $e) {
							}
						}

						$user->password = $newPassword;
						$user->updatePassword();

						$this->sendForgotPasswordEmail($user->userID, $newPassword);
					}

					$view = new View();
					$view->SetData('navData',  new NavData(NavData::Account));
					$view->Render('passwordSent');

				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
				}
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function ChangePassword() {
		if ($this->auth()) {
			$view = new View();
			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('changePassword');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function DoChangePassword() {
		if ($this->auth()) {
			if (isset ($_POST ['changePassword'])) {
				unset ($_POST ['changePassword']);
				$user = new User (Picnic::getInstance());
				$v = new Validation ();

				if (isset ($_POST ['password']) && isset ($_POST ['newPassword']) && isset ($_POST ['confirm'])) {

					// Validate the password.
					try {
						$v->password($_POST ['password']);
					} catch (ValidationException $e) {
						$_SESSION ['error'] = $e->getError();
					}

					if (isset ($_SESSION ['error'])) {
						unset ($_POST ['password']);
						unset ($_POST ['newPassword']);
						unset ($_POST ['confirm']);
						header('Location: ChangePassword');
					} else {

						// Validate the new password.
						try {
							$v->password($_POST ['newPassword']);
						} catch (ValidationException $e) {
							$_SESSION ['error'] = $e->getError();
						}

						if (isset ($_SESSION ['error'])) {
							unset ($_POST ['password']);
							unset ($_POST ['newPassword']);
							unset ($_POST ['confirm']);
							header('Location: ChangePassword');
						} else {

							// Compare passwords.
							try {
								$v->comparePasswords($_POST ['newPassword'], $_POST ['confirm']);
							} catch (ValidationException $e) {
								$_SESSION ['error'] = $e->getError();
							}

							if (isset ($_SESSION ['error'])) {
								unset ($_POST ['password']);
								unset ($_POST ['newPassword']);
								unset ($_POST ['confirm']);
								header('Location: ChangePassword');
							} else {

								// Confirm current password is correct.
								$user->userID = $_SESSION['userID'];
								$user->user = $_SESSION['user'];
								$user->email = $_SESSION['email'];
								$user->password = $_POST ['password'];
								$user->checkPassword();
								if ($user->userID != $_SESSION['userID']) {
									$_SESSION ['error'] = 'Password Error: Incorrect Password!';
									unset ($_POST ['password']);
									unset ($_POST ['newPassword']);
									unset ($_POST ['confirm']);
									header('Location: ChangePassword');

								} else {
									$user->password = $_POST ['newPassword'];
									$user->updatePassword();
									unset ($_POST ['password']);
									unset ($_POST ['newPassword']);
									unset ($_POST ['confirm']);
									$view = new View();
									$view->SetData('navData',  new NavData(NavData::Account));
									$view->Render('passwordChanged');
								}
							}
						}
					}
				}
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

	private function sendActivationEmail($userID)
	{
		$h = new Humphree(Picnic::getInstance());
		$user = $h->getUser($userID);

		$mail = $this->getMailer();
		$mail->addAddress($user['email'], $user['user']);
		$mail->Subject  = 'Activate your Humphree account';
		$mail->Body     = 'Click here to <a href="http://humphree.org/Account/Activate/' . $user['activate'] . '">Activate your account</a>.';

		if(!$mail->send()) {
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent.';
		}
	}

	private function sendForgotPasswordEmail($userID, $password)
	{
		$h = new Humphree(Picnic::getInstance());
		$user = $h->getUser($userID);

		$mail = $this->getMailer();
		$mail->addAddress($user['email'], $user['user']);
		$mail->Subject  = 'Humphree password reset';
		$mail->Body     = 'Your new password is <strong>' . $password . '</strong>.';

		if(!$mail->send()) {
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent.';
		}
	}

	private function getMailer() : PHPMailer\PHPMailer\PHPMailer {
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->setFrom('no-reply@humphree.org', 'Humphree');
		$mail->isHTML(true);
		$mail->IsSMTP();
		$mail->Host = "smtp.office365.com";
		$mail->SMTPAuth = true;
		$mail->Username = 'no-reply@humphree.org';
		$mail->Password = 'TestTest88';
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';

		return $mail;
	}
}
