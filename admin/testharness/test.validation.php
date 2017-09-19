<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 *
 * Validation Class Unit Tests
 */
$v = new Validation ();

/*
 * Test Base Functions
 */

// Test emptyField($content)
// emptyField($content) with empty content
$validationTest = false;
$content = '';
$b1 = false;
$emptyFieldError = null;
try {
	$v->emptyField ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input is required!' ) == 0) {
		$b1 = true;
	}
}
if (! $b1) {
	$emptyFieldError = 'Failed to validate emptyField($content) with empty content.<br />';
}
// emptyField($content) with non-empty content
$content = 'a';
$b2 = true;
try {
	$v->emptyField ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input is required!' ) == 0) {
		$b2 = false;
		$emptyFieldError = $emptyFieldError . 'Failed caught error emptyField($content) with non-empty content.<br />';
	}
}

if ($emptyFieldError) {
	$unitTestResults = $unitTestResults . $emptyFieldError;
}

// Test number($content)
// number($content) with numeric content
$content = '123';
$b3 = true;
$numberError = null;
try {
	$v->number ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be a number!' ) == 0) {
		$b3 = false;
		$numberError = 'Failed to validate number($content) with numeric content.<br />';
	}
}
// number($content) with non-numeric content
$content = 'abc';
$b4 = false;
try {
	$v->number ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be a number!' ) == 0) {
		$b4 = true;
	}
}
if (! $b4) {
	$numberError = $numberError . 'Failed caught error number($content) with numeric content.<br />';
}

if ($numberError) {
	$unitTestResults = $unitTestResults . $numberError;
}

// Test oneUpperOneLowerOneDigitGreaterEight($content)
// oneUpperOneLowerOneDigitGreaterEight($content) with correct content
$content = 'TestTest88';
$b5 = true;
$oneUpperOneLowerOneDigitGreaterEightError = null;
try {
	$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
		$b5 = false;
		$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed caught error oneUpperOneLowerOneDigitGreaterEight($content) with correct content.<br />';
	}
}

// oneUpperOneLowerOneDigitGreaterEight($content) without an uppercase character
$content = 'testtest88';
$b6 = false;
try {
	$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
		$b6 = true;
	}
}
if (! $b6) {
	$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed validated oneUpperOneLowerOneDigitGreaterEight($content) without an uppercase character.<br />';
}
// oneUpperOneLowerOneDigitGreaterEight($content) without a lowercase character
$content = 'TESTTEST88';
$b7 = false;
try {
	$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
		$b7 = true;
	}
}
if (! $b7) {
	$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed validated oneUpperOneLowerOneDigitGreaterEight($content) without an lowercase character.<br />';
}
// oneUpperOneLowerOneDigitGreaterEight($content) without a numeric character
$content = 'TestTestTest';
$b8 = false;
try {
	$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
		$b8 = true;
	}
}
if (! $b8) {
	$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed validated oneUpperOneLowerOneDigitGreaterEight($content) without a numeric character.<br />';
}
// oneUpperOneLowerOneDigitGreaterEight($content) less than eight characters
$content = 'Test88';
$b9 = false;
try {
	$v->oneUpperOneLowerOneDigitGreaterEight ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!' ) == 0) {
		$b9 = true;
	}
}
if (! $b9) {
	$oneUpperOneLowerOneDigitGreaterEightError = $oneUpperOneLowerOneDigitGreaterEightError . 'Failed validated oneUpperOneLowerOneDigitGreaterEight($content) less than eight characters.<br />';
}

if ($oneUpperOneLowerOneDigitGreaterEightError) {
	$unitTestResults = $unitTestResults . $oneUpperOneLowerOneDigitGreaterEightError;
}

// Test alphaNumeric($content)
// alphaNumeric($content) with alphanumeric content
$content = 'TestTest88';
$b10 = true;
$alphaNumericError = null;
try {
	$v->alphaNumeric ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be a alphanumeric!' ) == 0) {
		$b10 = false;
		$alphaNumericError = 'Failed caught error alphaNumeric($content) with alphanumeric content.<br />';
	}
}
// alphaNumeric($content) with non-alphanumeric content
$content = 'Test%Test88';
$b11 = false;
try {
	$v->alphaNumeric ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be a alphanumeric!' ) == 0) {
		$b11 = true;
	}
}
if (! $b11) {
	$alphaNumericError = $alphaNumericError = 'Failed validated alphaNumeric($content) with non-alphanumeric content.<br />';
}

if ($alphaNumericError) {
	$unitTestResults = $unitTestResults . $alphaNumericError;
}

// Test alpha($content)
// alpha($content) with alpha content
$content = 'TestTest';
$b12 = true;
$alphaError = null;
try {
	$v->alpha ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be a alpha!' ) == 0) {
		$b12 = false;
		$alphaError = 'Failed caught error alpha($content) with alpha content.<br />';
	}
}
// alpha($content) with non-alpha content
$content = 'TestTest88';
$b13 = false;
try {
	$v->alpha ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must consist of letters only!' ) == 0) {
		$b13 = true;
	}
}
if (! $b13) {
	$alphaError = $alphaError = 'Failed validated alpha($content) with non-alpha content.<br />';
}

if ($alphaError) {
	$unitTestResults = $unitTestResults . $alphaError;
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
		$numberHyphenError = 'Failed caught error numberHyphen($content) with number & hyphen content.<br />';
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
if (! $b15) {
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
	$v->isDate ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be valid date!' ) == 0) {
		$b16 = false;
		$isDateError = 'Failed caught error isDate($content) with valid date.<br />';
	}
}
// isDate($content) with invalid date
$content = '2015-02-30';
$b17 = false;
try {
	$v->isDate ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be valid date!' ) == 0) {
		$b17 = true;
	}
}
if (! $b17) {
	$isDateError = $isDateError = 'Failed validated isDate($content) with invalid date.<br />';
}

if ($isDateError) {
	$unitTestResults = $unitTestResults . $isDateError;
}

// Test notPastDate($content)
date_default_timezone_set ( 'Australia/Melbourne' );
$currentDate = date_create ( date ( 'm/d/Y h:i:s a', time () ) );
$todayDate = date_format ( $currentDate, 'Y-m-d' );
$currentYear = date_format ( $currentDate, 'Y' );
$currentMonth = date_format ( $currentDate, 'm' );
$currentDay = date_format ( $currentDate, 'd' );
$nextYear = $currentYear + 1;
$lastYear = $currentYear - 1;
$pastDate = $lastYear . '-' . $currentMonth . '-' . $currentDay;
$futureDate = $nextYear . '-' . $currentMonth . '-' . $currentDay;

// notPastDate($content) with past date
$content = $pastDate; 
$b18 = false;
$notPastDateError = null;
try {
	$v->notPastDate ( $content ); 
} catch ( ValidationException $e ) { 
	if (strcmp ( $e->getError (), 'Date must be todays date or a future date!' ) == 0) {
		$b18 = true;
	}
}
if (! $b18) {
	$notPastDateError = 'Failed validated notPastDate($content) with past date.<br />';
}
// notPastDate($content) with todays date
$content = $todayDate;
$b19 = true;
try {
	$v->notPastDate ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Date must be todays date or a future date!' ) == 0) {
		$b19 = false;
		$notPastDateError = $notPastDateError . 'Failed caught error notPastDate($content) with todays date.<br />';
	}
}
// notPastDate($content) with future date
$content = $futureDate;
$b20 = true;
try {
	$v->notPastDate ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Date must be todays date or a future date!' ) == 0) {
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
	$v->validateEmail ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Email address must be valid!' ) == 0) {
		$b21 = false;
		$validateEmailError = 'Failed validated validateEmail($content) with valid email.<br />';
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
if (! $b22) {
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
	$v->atLeastFour ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be atleast 4 characters in length!' ) == 0) {
		$b23 = false;
		$atLeastFourError = 'Failed validated atLeastFour($content) with valid content.<br />';
	}
}

// atLeastFour($content) with invalid content
$content = 'aaa';
$b24 = false;
try {
	$v->atLeastFour ( $content );
} catch ( ValidationException $e ) {
	if (strcmp ( $e->getError (), 'Input must be atleast 4 characters in length!' ) == 0) {
		$b24 = true;
	}
}
if (! $b24) {
	$atLeastFourError = $atLeastFourError . 'Failed validated atLeastFour($content) with invalid content.<br />';
}

if ($atLeastFourError) {
	$unitTestResults = $unitTestResults . $atLeastFourError;
}

$unitTestResults = $unitTestResults . 'Validation: <font color="';

if ($b1 && $b2 && $b3 && $b4 && $b5 && $b6 && $b7 && $b8 && $b9 && $b10 && $b11 && $b12 && $b13 && $b14 && $b15 && $b16 && $b17 && $b18 && $b19 && $b20 && $b21 && $b22 && $b23 && $b24) {
	$unitTestResults = $unitTestResults . 'green">PASS';
	$validationTest = true;
} else {
	$unitTestResults = $unitTestResults . 'red">FAIL';
}

$unitTestResults = $unitTestResults . '</font><br />';

?>