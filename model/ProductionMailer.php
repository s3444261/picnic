<?php
/**
 * Created by PhpStorm.
 * User: My Peeps
 * Date: 13/11/2017
 * Time: 6:22 PM
 */

require_once  __DIR__ . '/../vendor/autoload.php';

class ProductionMailer extends Mailer
{
	public function SendMail(string $name, string $emailAddress, string $subject, string $body)
	{
		$mail = $this->getMailer();
		$mail->addAddress('s3202752@student.rmit.edu.au', 'Humphree Debug');
		$mail->addAddress($emailAddress, $name);
		$mail->Subject  = $subject;
		$mail->Body     = $body;

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