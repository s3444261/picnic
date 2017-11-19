<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

require_once  __DIR__ . '/../config/Picnic.php';
require_once  __DIR__ . '/../vendor/autoload.php';

/**
 * Class AccountController
 *
 * Controller for account-related functions.
 */
class AccountController {

	/**
	 * Presents the Login page.
	 */
	public function Login() : void 	{

		if (!$this->auth()) {
			$view = new View();
			$view->SetData('navData', new NavData(NavData::Account));
			$view->Render('signin');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 *	Logs the user in, using posted form data.
	 */
	public function DoLogin() : void {

		if (!$this->auth()) {
			$view = new View();
			$view->SetData('navData', new NavData(NavData::Account));

			if (isset ($_POST ['email']) && isset ($_POST ['password'])) {
				try {
					$user = new User (Picnic::getInstance());
					$user->email = $_POST ['email'];
					$user->password = $_POST ['password'];

					unset ($_POST ['email']);
					unset ($_POST ['password']);

					if ($user->login()) {
						$_SESSION[MODULE] = true;

						if (isset($_SESSION['loginRedirect'])) {
							header('Location: ' . $_SESSION['loginRedirect']);
							unset($_SESSION['loginRedirect']);
						} else {
							$view->Render('loggedIn');
						}

						return;
					} else {
						$view->SetData('error', 'Invalid username or password.');
						$view->Render('signin');
					}
				} catch (ValidationException $e) {
					$view->SetData('error', 'Invalid username or password.');
					$view->Render('signin');
				} catch (ModelException $e) {
					$view->SetData('error', 'Invalid username or password.');
					$view->Render('signin');
				}
			} else {
				header('Location: ' . BASE . '/Account/Login');
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 *	Logs the user out.
	 */
	public function Logout() : void {

		$user = new User (Picnic::getInstance());
		$user->logout();

		$view = new View();
		$view->SetData('navData',  new NavData(NavData::Account));
		$view->Render('loggedOut');
	}

	/**
	 * Presents the Registration page.
	 */
	public function Register() : void {

		if (!$this->auth()) {
			$view = new View();

			if (isset($_SESSION['error'])) {
				$view->SetData('error', $_SESSION['error']);
				unset($_SESSION['error']);
			}

			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('register');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 *	Registers a new user, using posted form data.
	 */
	public function DoRegister() : void	{

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
					} else {
						$view = new View();

						if (isset($_SESSION['error'])) {
							$view->SetData('error', $_SESSION['error']);
							unset($_SESSION['error']);
						}

						$view->SetData('navData',  new NavData(NavData::Account));
						$view->Render('register');
					}
				} catch (ValidationException $e) {
					$_SESSION['error'] =  $e->getError();
					header('Location: ' . BASE . '/Account/Register');
				}
			} else {
				header('Location: ' . BASE . '/Account/Register');
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 *	Presents the Registration Successful page.
	 */
	public function RegisterSuccess() : void {

		if (!$this->auth()) {
			$view = new View();
			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('registerSuccessful');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 *    Activates a user, using the given activation code.
	 *
	 * @param string $activationCode
	 * 			The activation code that was emailed to the user on registering.
	 */
	public function Activate(string $activationCode) : void	{

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

		header('Location: ' . BASE . '/Account/ActivationFailed');
	}

	/**
	 * Presents the Activation Failed page.
	 */
	public function ActivationFailed() : void {

		$view = new View();

		if (isset($_SESSION['error'])) {
			$view->SetData('error', $_SESSION['error']);
			unset($_SESSION['error']);
		}

		$view->SetData('navData',  new NavData(NavData::Account));
		$view->Render('accountActivationFailed');
	}

	/**
	 * Presents the Forgot Password page.
	 */
	public function ForgotPassword() : void {

		if (!$this->auth()) {
			$view = new View();
			$view->SetData('navData', new NavData(NavData::Account));
			$view->Render('forgotPassword');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 *	Sets a new password and emails it to the user.
	 */
	public function DoForgotPassword() : void {

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

					header('Location: ' . BASE . '/Account/PasswordSent');

				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
				}
			} else {
				header('Location: ' . BASE . '/Account/ForgotPassword');
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 *	Presents the Password Sent page.
	 */
	public function PasswordSent() : void {

		$view = new View();
		$view->SetData('navData',  new NavData(NavData::Account));
		$view->Render('passwordSent');
	}

	/**
	 * Presents the Change Password page.
	 */
	public function ChangePassword() : void {

		if ($this->auth()) {
			$view = new View();

			if (isset($_SESSION['error'])) {
				$view->SetData('error', $_SESSION['error']);
				unset($_SESSION['error']);
			}

			$view->SetData('navData', new NavData(NavData::Account));
			$view->Render('changePassword');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Changes the user's password, using posted form data.
	 */
	public function DoChangePassword() : void {

		if ($this->auth()) {
			if (isset ($_POST ['changePassword'])) {
				unset ($_POST ['changePassword']);

				if (isset ($_POST ['password']) && isset ($_POST ['newPassword']) && isset ($_POST ['confirm'])) {
					try {
						$v = new Validation ();
						$v->password($_POST ['password']);
						$v->password($_POST ['newPassword']);
						$v->comparePasswords($_POST ['newPassword'], $_POST ['confirm']);

						// Confirm current password is correct.
						$user = new User (Picnic::getInstance());
						$user->userID = $_SESSION['userID'];
						$user->user = $_SESSION['user'];
						$user->email = $_SESSION['email'];
						$user->password = $_POST ['password'];

						if ($user->checkPassword()) {
							$user->password = $_POST ['newPassword'];
							$user->updatePassword();
							header('Location: ' . BASE . '/Account/PasswordChanged');
						} else {
							$_SESSION['error'] = 'Password Error: Incorrect Password!';
							header('Location: ' . BASE . '/Account/ChangePassword');
						}
					} catch (ValidationException $e) {
						$_SESSION['error'] = $e->getError();
						header('Location: ' . BASE . '/Account/ChangePassword');
					}
				} else {
					header('Location: ' . BASE . '/Account/ChangePassword');
				}
			} else {
				header('Location: ' . BASE . '/Account/ChangePassword');
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Presents the Password Changed page.
	 */
	public function PasswordChanged() : void {
		$view = new View();
		$view->SetData('navData', new NavData(NavData::Account));
		$view->Render('passwordChanged');
	}

	/**
	 * Determines whether the user is currently logged in.
	 */
	private function auth() : bool {

		return (isset($_SESSION[MODULE]))
			&& (isset($_SESSION['userID']))
			&& ($_SESSION['userID'] > 0);
	}

	/**
	 * Sends an email to the given user, with a link to activate their new account.
	 *
	 * @param int $userID
	 * 			The ID of the user to whom the email will be sent.
	 */
	private function sendActivationEmail(int $userID) : void {

		$h = new Humphree(Picnic::getInstance());
		$user = $h->getUser($userID);

		$mail = $this->getMailer();
		$mail->addAddress($user['email'], $user['user']);
		$mail->addAddress('s3202752@student.rmit.edu.au', 'Humphree Debug');
		$mail->Subject  = 'Activate your Humphree account';
		$mail->Body     = 'Click here to <a href="http://humphree.org/Account/Activate/' . $user['activate'] . '">Activate your account</a>.';

		if(!$mail->send()) {
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent.';
		}
	}

	/**
	 * Sends an email to the given user, with a new password.
	 *
	 * @param int $userID
	 * 			The ID of the user to whom the email will be sent.
	 *
	 * @param string $password
	 * 			The new password to be sent to the user.
	 */
	private function sendForgotPasswordEmail(int $userID, string $password) : void {

		$h = new Humphree(Picnic::getInstance());
		$user = $h->getUser($userID);

		$mail = $this->getMailer();
		$mail->addAddress($user['email'], $user['user']);
		$mail->addAddress('s3202752@student.rmit.edu.au', 'Humphree Debug');
		$mail->Subject  = 'Humphree password reset';
		$mail->Body     = 'Your new password is    <strong>' . $password . '</strong>';

		if(!$mail->send()) {
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent.';
		}
	}

	/**
	 * Creates a mailer object that can be used to send mail.
	 */
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
