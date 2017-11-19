<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
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
		$mail->isSMTP();
		$mail->Host = "smtp.office365.com";
		$mail->SMTPAuth = true;
		$mail->Username = 'no-reply@humphree.org';
		$mail->Password = 'TestTest88';
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';

		return $mail;
	}
}