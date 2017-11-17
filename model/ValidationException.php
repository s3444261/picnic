<?php
/**
 * Authors:
 * @author Derrick, Troy - s3202752@student.rmit.edu.au
 * @author Foster, Diane - s3387562@student.rmit.edu.au
 * @author Goodreds, Allen - s3492264@student.rmit.edu.au
 * @author Kinkead, Grant - s3444261@student.rmit.edu.au
 * @author Putro, Edwan - s3418650@student.rmit.edu.au
 */

class ValidationException extends Exception {
	protected $message;
	function __construct($message) {
		$this->message = $message;
	}
	public function getError() {
		return $this->message;
	}
}
