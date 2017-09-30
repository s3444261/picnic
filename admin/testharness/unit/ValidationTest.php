<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

declare(strict_types=1);

require_once dirname(__FILE__) . '/../../../model/Validation.php';
require_once dirname(__FILE__) . '/../../../model/ValidationException.php';

class ValidationTests extends PHPUnit\Framework\TestCase {

	public function testEmptyFieldThrowsForEmptyString() {
		$sut = new Validation();
		$this->expectException(ValidationException::class);
		$sut->emptyField("");
	}

	public function testEmptyFieldThrowsForNull() {
		$sut = new Validation();
		$this->expectException(ValidationException::class);
		$sut->emptyField(null);
	}

	public function testEmptyFieldPassesForNonEmptyString() {
		$sut = new Validation();
		$sut->emptyField("NotEmpty");
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testEmptyFieldPassesForNumber() {
		$sut = new Validation();
		$sut->emptyField(232);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testNumberPassesForEmptyString() {
		$sut = new Validation();
		$sut->number("");
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testNumberPassesForNull() {
		$sut = new Validation();
		$sut->number(null);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testNumberThrowsForNonNumeric() {
		$sut = new Validation();
		$this->expectException(ValidationException::class);
		$sut->number("NotANumber");
	}

	public function testNumberPassesForZero() {
		$sut = new Validation();
		$sut->number(0);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testNumberPassesForPositiveInteger() {
		$sut = new Validation();
		$sut->number(12223);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testNumberPassesForNegativeInteger() {
		$sut = new Validation();
		$sut->number(-54);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testNumberPassesForPositiveFloat() {
		$sut = new Validation();
		$sut->number(123.43565);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testNumberPassesForNegativeFloat() {
		$sut = new Validation();
		$sut->number(-4.2);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaNumericPassesForEmptyString() {
		$sut = new Validation();
		$sut->alphaNumeric("");
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaNumericPassesForNull() {
		$sut = new Validation();
		$sut->alphaNumeric(null);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaNumericPassesForNumeric() {
		$sut = new Validation();
		$sut->alphaNumeric(33233);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaNumericPassesForAlpha() {
		$sut = new Validation();
		$sut->alphaNumeric('AlphaOnly');
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaNumericPassesForAlphaNumeric() {
		$sut = new Validation();
		$sut->alphaNumeric('A1');
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaNumericPassesForSpaceCharacters() {
		$sut = new Validation();
		$sut->alphaNumeric('    ');
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaNumericThrowsForSpecialCharacters() {
		$sut = new Validation();
		$this->expectException(ValidationException::class);
		$sut->alphaNumeric('_');
	}

	public function testAlphaNumericThrowsForTabs() {
		$sut = new Validation();
		$this->expectException(ValidationException::class);
		$sut->alphaNumeric('\t');
	}

	public function testAlphaPassesForEmptyString() {
		$sut = new Validation();
		$sut->alpha("");
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaPassesForNull() {
		$sut = new Validation();
		$sut->alpha(null);
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaPassesForAlpha() {
		$sut = new Validation();
		$sut->alpha('AlphaOnly');
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaNumericThrowsForNumeric() {
		$sut = new Validation();
		$this->expectException(ValidationException::class);
		$sut->alpha(33233);
	}

	public function testAlphaPassesForSpaceCharacters() {
		$sut = new Validation();
		$sut->alpha('    ');
		$this->addToAssertionCount(1);  // does not throw an exception
	}

	public function testAlphaThrowsForSpecialCharacters() {
		$sut = new Validation();
		$this->expectException(ValidationException::class);
		$sut->alpha('_');
	}

	public function testAlphaThrowsForTabs() {
		$sut = new Validation();
		$this->expectException(ValidationException::class);
		$sut->alpha('\t');
	}

    public function testOthers()
	{

		$unitTestResults = null;
		$v = new Validation();
// Test oneUpperOneLowerOneDigitGreaterEight($content)
// oneUpperOneLowerOneDigitGreaterEight($content) with correct content
		$content = 'TestTest88';
		$b5 = true;
		$oneUpperOneLowerOneDigitGreaterEightError = null;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!') == 0) {
				$b5 = false;
				$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed caught error oneUpperOneLowerOneDigitGreaterEight($content) with correct content.<br />';
			}
		}

// oneUpperOneLowerOneDigitGreaterEight($content) without an uppercase character
		$content = 'testtest88';
		$b6 = false;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!') == 0) {
				$b6 = true;
			}
		}
		if (!$b6) {
			$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed validated oneUpperOneLowerOneDigitGreaterEight($content) without an uppercase character.<br />';
		}
// oneUpperOneLowerOneDigitGreaterEight($content) without a lowercase character
		$content = 'TESTTEST88';
		$b7 = false;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!') == 0) {
				$b7 = true;
			}
		}
		if (!$b7) {
			$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed validated oneUpperOneLowerOneDigitGreaterEight($content) without an lowercase character.<br />';
		}
// oneUpperOneLowerOneDigitGreaterEight($content) without a numeric character
		$content = 'TestTestTest';
		$b8 = false;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!') == 0) {
				$b8 = true;
			}
		}
		if (!$b8) {
			$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed validated oneUpperOneLowerOneDigitGreaterEight($content) without a numeric character.<br />';
		}
// oneUpperOneLowerOneDigitGreaterEight($content) less than eight characters
		$content = 'Test88';
		$b9 = false;
		try {
			$v->oneUpperOneLowerOneDigitGreaterEight($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!') == 0) {
				$b9 = true;
			}
		}
		if (!$b9) {
			$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed validated oneUpperOneLowerOneDigitGreaterEight($content) less than eight characters.<br />';
		}

		if ($oneUpperOneLowerOneDigitGreaterEightError) {
			$unitTestResults = $unitTestResults . $oneUpperOneLowerOneDigitGreaterEightError;
		}

// Test numberHyphen($content)
// numberHyphen($content) with number & hyphen content
		$content = '123-456';
		$b14 = true;
		$numberHyphenError = null;
		try {
			$v->numberHyphen($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Must only consist of numbers and/or hyphens!') == 0) {
				$b14 = false;
				$numberHyphenError = 'Failed caught error numberHyphen($content) with number & hyphen content.<br />';
			}
		}
// numberHyphen($content) with content other than numbers & hyphens
		$content = '123-456a';
		$b15 = false;
		try {
			$v->numberHyphen($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Must only consist of numbers and/or hyphens!') == 0) {
				$b15 = true;
			}
		}
		if (!$b15) {
			$numberHyphenError = $numberHyphenError = 'Failed validated alpha($content) with content other than numbers & hyphens.<br />';
		}

		if ($numberHyphenError) {
			$unitTestResults = $unitTestResults . $numberHyphenError;
		}

// Test isDate($content)
// isDate($content) with valid date
		$content = '2015-02-28';
		$b16 = true;
		$isDateError = null;
		try {
			$v->isDate($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Input must be valid date!') == 0) {
				$b16 = false;
				$isDateError = 'Failed caught error isDate($content) with valid date.<br />';
			}
		}
// isDate($content) with invalid date
		$content = '2015-02-30';
		$b17 = false;
		try {
			$v->isDate($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Input must be valid date!') == 0) {
				$b17 = true;
			}
		}
		if (!$b17) {
			$isDateError = $isDateError = 'Failed validated isDate($content) with invalid date.<br />';
		}

		if ($isDateError) {
			$unitTestResults = $unitTestResults . $isDateError;
		}

// Test notPastDate($content)
		date_default_timezone_set('Australia/Melbourne');
		$currentDate = date_create(date('m/d/Y h:i:s a', time()));
		$todayDate = date_format($currentDate, 'Y-m-d');
		$currentYear = date_format($currentDate, 'Y');
		$currentMonth = date_format($currentDate, 'm');
		$currentDay = date_format($currentDate, 'd');
		$nextYear = $currentYear + 1;
		$lastYear = $currentYear - 1;
		$pastDate = $lastYear . '-' . $currentMonth . '-' . $currentDay;
		$futureDate = $nextYear . '-' . $currentMonth . '-' . $currentDay;

// notPastDate($content) with past date
		$content = $pastDate;
		$b18 = false;
		$notPastDateError = null;
		try {
			$v->notPastDate($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Date must be todays date or a future date!') == 0) {
				$b18 = true;
			}
		}
		if (!$b18) {
			$notPastDateError = 'Failed validated notPastDate($content) with past date.<br />';
		}
// notPastDate($content) with todays date
		$content = $todayDate;
		$b19 = true;
		try {
			$v->notPastDate($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Date must be todays date or a future date!') == 0) {
				$b19 = false;
				$notPastDateError = $notPastDateError . 'Failed caught error notPastDate($content) with todays date.<br />';
			}
		}
// notPastDate($content) with future date
		$content = $futureDate;
		$b20 = true;
		try {
			$v->notPastDate($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Date must be todays date or a future date!') == 0) {
				$b20 = false;
				$notPastDateError = $notPastDateError . 'Failed caught error notPastDate($content) with future date.<br />';
			}
		}

		if ($notPastDateError) {
			$unitTestResults = $unitTestResults . $notPastDateError;
		}

// Test validateEmail($content)
// validateEmail($content) with valid email
		$content = 'john@hotmail.com';
		$b21 = true;
		$validateEmailError = null;
		try {
			$v->validateEmail($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Email address must be valid!') == 0) {
				$b21 = false;
				$validateEmailError = 'Failed validated validateEmail($content) with valid email.<br />';
			}
		}

// validateEmail($content) with invalid email
		$content = 'john.hotmail.com';
		$b22 = false;
		try {
			$v->validateEmail($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Email address must be valid!') == 0) {
				$b22 = true;
			}
		}
		if (!$b22) {
			$validateEmailError = $validateEmailError . 'Failed validated validateEmail($content) with invalid email.<br />';
		}

		if ($validateEmailError) {
			$unitTestResults = $unitTestResults . $validateEmailError;
		}

// Test atLeastFour($content)
// atLeastFour($content) with valid content
		$content = 'aaaa';
		$b23 = true;
		$atLeastFourError = null;
		try {
			$v->atLeastFour($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Input must be atleast 4 characters in length!') == 0) {
				$b23 = false;
				$atLeastFourError = 'Failed validated atLeastFour($content) with valid content.<br />';
			}
		}

// atLeastFour($content) with invalid content
		$content = 'aaa';
		$b24 = false;
		try {
			$v->atLeastFour($content);
		} catch (ValidationException $e) {
			if (strcmp($e->getError(), 'Input must be atleast 4 characters in length!') == 0) {
				$b24 = true;
			}
		}
		if (!$b24) {
			$atLeastFourError = $atLeastFourError . 'Failed validated atLeastFour($content) with invalid content.<br />';
		}

		if ($atLeastFourError) {
			$unitTestResults = $unitTestResults . $atLeastFourError;
		}

		$this->assertTrue($b5 && $b6 && $b7 && $b8 && $b9 && $b14 && $b15 && $b16 && $b17 && $b18 && $b19 && $b20 && $b21 && $b22 && $b23 && $b24);
	}
}
