<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

class Validation {
	
	/*
	 * Compiled Functions
	 */
	
	// Validates a username.
	public function userName($content) {
		$errorMessage = null;
		
		try {
			$this->emptyField ( $content );
			$this->alphaNumeric ( $content );
			$this->atLeastFour ( $content );
		} catch ( ValidationException $e ) {
			if ($errorMessage == null) {
				$errorMessage = 'Username Error: ' . $e->getError ();
			} else {
				$errorMessage = $errorMessage . '<br />' . $e->getError ();
			}
		}
		
		if ($errorMessage != null) {
			throw new ValidationException ( $errorMessage );
		}
	}
	
	// Validates an email.
	public function email($content) {
		$errorMessage = null;
		
		try {
			$this->emptyField ( $content );
			$this->validateEmail ( $content );
		} catch ( ValidationException $e ) {
			if ($errorMessage == null) {
				$errorMessage = 'Email Error: ' . $e->getError ();
			} else {
				$errorMessage = $errorMessage . '<br />' . $e->getError ();
			}
		}
		
		if ($errorMessage != null) {
			throw new ValidationException ( $errorMessage );
		}
	}
	
	// Validates a password.
	public function password($content) {
		$errorMessage = null;
		
		try {
			$this->emptyField ( $content );
			$this->oneUpperOneLowerOneDigitGreaterEight ( $content );
		} catch ( ValidationException $e ) {
			if ($errorMessage == null) {
				$errorMessage = 'Password Error: ' . $e->getError ();
			} else {
				$errorMessage = $errorMessage . '<br />' . $e->getError ();
			}
		}
		
		if ($errorMessage != null) {
			throw new ValidationException ( $errorMessage );
		}
	}
	
	// Compares two passwords
	public function comparePasswords($password1, $password2) {
		$errorMessage = null;
		
		if (strcmp ( $password1, $password2 ) != 0) {
			$errorMessage = 'Password Error: Passwords do not match!';
			throw new ValidationException ( $errorMessage );
		}
	}
	
	// Validates a date.
	public function confirmDate($content) {
		$errorMessage = null;
		
		try {
			$this->isDate ( $content );
		} catch ( ValidationException $e ) {
			if ($errorMessage == null) {
				$errorMessage = 'Date Error: ' . $e->getError ();
			} else {
				$errorMessage = $errorMessage . '<br />' . $e->getError ();
			}
		}
		
		if ($errorMessage != null) {
			throw new ValidationException ( $errorMessage );
		}
	}
	
	/*
	 * Base Functions.
	 */
	
	// Tests for an empty field.
	public function emptyField($content) {
		if (strlen ( $content ) == 0) {
			throw new ValidationException ( 'Input is required!' );
		}
	}
	
	// Tests for a number.
	public function number($content) {
		if (! is_numeric ( $content ) && strlen ( $content ) > 0) {
			throw new ValidationException ( 'Input must be a number!' );
		}
	}
	
	// Tests for at least one upper case character.
	// At least one lower case character.
	// At least one digit.
	// At least eight characters long.
	public function oneUpperOneLowerOneDigitGreaterEight($content) {
		if (! preg_match ( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}+$/', $content )) {
			throw new ValidationException ( 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' );
		}
	}
	
	// Tests that input is alphanumeric.
	public function alphaNumeric($content) {
		$content = preg_replace ( '/\s+/', '', $content );
		
		if (! ctype_alnum ( $content ) && strlen ( $content ) > 0) {
			throw new ValidationException ( 'Input must be a alphanumeric!' );
		}
	}
	
	// Tests that input contains letters only.
	public function alpha($content) {
		$content = preg_replace ( '/\s+/', '', $content );
		
		if (! ctype_alpha ( $content ) && strlen ( $content ) > 0) {
			throw new ValidationException ( 'Input must consist of letters only!' );
		}
	}
	
	// Tests that the input only contains numbers or hyphens.
	public function numberHyphen($content) {
		if (! preg_match ( '/^[0-9-]+$/', $content )) {
			throw new ValidationException ( 'Must only consist of numbers and/or hyphens!' );
		}
	}
	
	// Tests that input is a date.
	public function isDate($content) {
		if (! $this->validateDate ( $content, 'Y-m-d' ) && strlen ( $content ) > 0) {
			throw new ValidationException ( 'Input must be valid date!' );
		}
	}
	
	// Tests that the date is either today or a future date.
	public function notPastDate($content) {
		$date = date_create ( $content );
		$paymentDate = date_format ( $date, 'Yz' );
		$paymentDate = intval ( $paymentDate );
		$currentDate = date_create ( date ( 'm/d/Y h:i:s a', time () ) );
		$currentDate = date_format ( $currentDate, 'Yz' );
		$currentDate = intval ( $currentDate );
		
		if ($paymentDate < $currentDate) {
			throw new ValidationException ( 'Date must be todays date or a future date!' );
		}
	}
	
	// From PHP Manual
	public function validateDate($date, $format) {
		$d = DateTime::createFromFormat ( $format, $date );
		return $d && $d->format ( $format ) == $date;
	}
	
	// Tests for valid email address
	public function validateEmail($content) {
		if (! filter_var ( $content, FILTER_VALIDATE_EMAIL )) {
			throw new ValidationException ( 'Email address must be valid!' );
		}
	}
	
	// Tests for string length atleast 4 characters long.
	public function atLeastFour($content) {
		if (strlen ( $content ) < 4) {
			throw new ValidationException ( 'Input must be atleast 4 characters in length!' );
		}
	}
	
	// Tests for string length atleast 8 characters long.
	public function atLeastEight($content) {
		if (strlen ( $content ) < 8) {
			throw new ValidationException ( 'Input must be atleast 8 characters in length!' );
		}
	}
}
?>