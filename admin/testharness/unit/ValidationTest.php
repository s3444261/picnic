<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
declare ( strict_types = 1 );

require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
class ValidationTest extends PHPUnit\Framework\TestCase {
	const ERROR_PASSWORDS_NOT_MATCH = 'Password Error: Passwords do not match!';
	const ERROR_FIELD_EMPTY = 'Input is required!';
	const ERROR_NOT_NUMBER = 'Input must be a number!';
	const ERROR_NOT_ZERO = 'Number must be greater than zero!';
	const ERROR_UPPER_LOWER_NUMBER = 'At least one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!';
	const ERROR_NOT_ALPHANUMERIC = 'Input must be a alphanumeric!';
	const ERROR_NOT_ALPHA = 'Input must consist of letters only!';
	const ERROR_NOT_NUMBER_OR_HYPHEN = 'Must only consist of numbers and/or hyphens!';
	const ERROR_EMAIL_NOT_VALID = 'Email address must be valid!';
	const ERROR_STRLEN_LESS_THAN_FOUR = 'Input must be at least 4 characters in length!';
	const ERROR_STRLEN_LESS_THAN_EIGHT = 'Input must be at least 8 characters in length!';
	const ERROR_ACTIVATION_STRLEN_NOT_THIRTY_TWO = 'Activation code must be 32 characters in length!';
	public function testEmptyFieldThrowsForEmptyString() {
		$sut = new Validation ();
		$this->expectException ( ValidationException::class );
		$sut->emptyField ( "" );
	}
	public function testEmptyFieldThrowsForNull() {
		$sut = new Validation ();
		$this->expectException ( ValidationException::class );
		$sut->emptyField ( null );
	}
	public function testEmptyFieldPassesForNonEmptyString() {
		$sut = new Validation ();
		$sut->emptyField ( "NotEmpty" );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testEmptyFieldPassesForNumber() {
		$sut = new Validation ();
		$sut->emptyField ( 232 );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testNumberPassesForEmptyString() {
		$sut = new Validation ();
		$sut->number ( "" );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testNumberPassesForNull() {
		$sut = new Validation ();
		$sut->number ( null );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testNumberThrowsForNonNumeric() {
		$sut = new Validation ();
		$this->expectException ( ValidationException::class );
		$sut->number ( "NotANumber" );
	}
	public function testNumberPassesForZero() {
		$sut = new Validation ();
		$sut->number ( 0 );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testNumberPassesForPositiveInteger() {
		$sut = new Validation ();
		$sut->number ( 12223 );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testNumberPassesForNegativeInteger() {
		$sut = new Validation ();
		$sut->number ( - 54 );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testNumberPassesForPositiveFloat() {
		$sut = new Validation ();
		$sut->number ( 123.43565 );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testNumberPassesForNegativeFloat() {
		$sut = new Validation ();
		$sut->number ( - 4.2 );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaNumericPassesForEmptyString() {
		$sut = new Validation ();
		$sut->alphaNumeric ( "" );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaNumericPassesForNull() {
		$sut = new Validation ();
		$sut->alphaNumeric ( null );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaNumericPassesForNumeric() {
		$sut = new Validation ();
		$sut->alphaNumeric ( 33233 );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaNumericPassesForAlpha() {
		$sut = new Validation ();
		$sut->alphaNumeric ( 'AlphaOnly' );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaNumericPassesForAlphaNumeric() {
		$sut = new Validation ();
		$sut->alphaNumeric ( 'A1' );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaNumericPassesForSpaceCharacters() {
		$sut = new Validation ();
		$sut->alphaNumeric ( '    ' );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaNumericThrowsForSpecialCharacters() {
		$sut = new Validation ();
		$this->expectException ( ValidationException::class );
		$sut->alphaNumeric ( '_' );
	}
	public function testAlphaNumericThrowsForTabs() {
		$sut = new Validation ();
		$this->expectException ( ValidationException::class );
		$sut->alphaNumeric ( '\t' );
	}
	public function testAlphaPassesForEmptyString() {
		$sut = new Validation ();
		$sut->alpha ( "" );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaPassesForNull() {
		$sut = new Validation ();
		$sut->alpha ( null );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaPassesForAlpha() {
		$sut = new Validation ();
		$sut->alpha ( 'AlphaOnly' );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaNumericThrowsForNumeric() {
		$sut = new Validation ();
		$this->expectException ( ValidationException::class );
		$sut->alpha ( 33233 );
	}
	public function testAlphaPassesForSpaceCharacters() {
		$sut = new Validation ();
		$sut->alpha ( '    ' );
		$this->addToAssertionCount ( 1 ); // does not throw an exception
	}
	public function testAlphaThrowsForSpecialCharacters() {
		$sut = new Validation ();
		$this->expectException ( ValidationException::class );
		$sut->alpha ( '_' );
	}
	public function testAlphaThrowsForTabs() {
		$sut = new Validation ();
		$this->expectException ( ValidationException::class );
		$sut->alpha ( '\t' );
	}
	public function testOthers() {
		$unitTestResults = null;
		$v = new Validation ();
		// Test oneUpperOneLowerOneDigitGreaterEight($content)
		// oneUpperOneLowerOneDigitGreaterEight($content) with correct content
		$content = 'TestTest88';
		$b5 = true;
		$oneUpperOneLowerOneDigitGreaterEightError = null;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'At least one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
				$b5 = false;
			}
		}
		
		// oneUpperOneLowerOneDigitGreaterEight($content) without an uppercase character
		$content = 'testtest88';
		$b6 = false;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'At least one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
				$b6 = true;
			}
		}

		// oneUpperOneLowerOneDigitGreaterEight($content) without a lowercase character
		$content = 'TESTTEST88';
		$b7 = false;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'At least one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
				$b7 = true;
			}
		}

		// oneUpperOneLowerOneDigitGreaterEight($content) without a numeric character
		$content = 'TestTestTest';
		$b8 = false;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'At least one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
				$b8 = true;
			}
		}

		// oneUpperOneLowerOneDigitGreaterEight($content) less than eight characters
		$content = 'Test88';
		$b9 = false;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'At least one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
				$b9 = true;
			}
		}

		// Test numberHyphen($content)
		// numberHyphen($content) with number & hyphen content
		$content = '123-456';
		$b14 = true;
		$numberHyphenError = null;
		try {
			$v->numberHyphen ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'Must only consist of numbers and/or hyphens!' ) == 0) {
				$b14 = false;
			}
		}
		// numberHyphen($content) with content other than numbers & hyphens
		$content = '123-456a';
		$b15 = false;
		try {
			$v->numberHyphen ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'Must only consist of numbers and/or hyphens!' ) == 0) {
				$b15 = true;
			}
		}

		// Test validateEmail($content)
		// validateEmail($content) with valid email
		$content = 'john@hotmail.com';
		$b21 = true;
		$validateEmailError = null;
		try {
			$v->validateEmail ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'Email address must be valid!' ) == 0) {
				$b21 = false;
			}
		}
		
		// validateEmail($content) with invalid email
		$content = 'john.hotmail.com';
		$b22 = false;
		try {
			$v->validateEmail ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'Email address must be valid!' ) == 0) {
				$b22 = true;
			}
		}

		// Test atLeastFour($content)
		// atLeastFour($content) with valid content
		$content = 'aaaa';
		$b23 = true;
		$atLeastFourError = null;
		try {
			$v->atLeastFour ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'Input must be at least 4 characters in length!' ) == 0) {
				$b23 = false;
			}
		}
		
		// atLeastFour($content) with invalid content
		$content = 'aaa';
		$b24 = false;
		try {
			$v->atLeastFour ( $content );
		} catch ( ValidationException $e ) {
			if (strcmp ( $e->getError (), 'Input must be at least 4 characters in length!' ) == 0) {
				$b24 = true;
			}
		}

		$this->assertTrue ( $b5 && $b6 && $b7 && $b8 && $b9 && $b14 && $b15 && $b21 && $b22 && $b23 && $b24 );
	}
}
