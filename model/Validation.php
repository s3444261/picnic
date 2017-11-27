<?php
/**
 * Authors:
 * @author Derrick, Troy - s3202752@student.rmit.edu.au
 * @author Foster, Diane - s3387562@student.rmit.edu.au
 * @author Goodreds, Allen - s3492264@student.rmit.edu.au
 * @author Kinkead, Grant - s3444261@student.rmit.edu.au
 * @author Putro, Edwan - s3418650@student.rmit.edu.au
 */

class Validation {
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
    
    /**
     * Validates a userName.
     *
     * @param $content
     * @throws ValidationException
     */
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
    
    /**
     * Validates an email address.
     *
     * @param $content
     * @throws ValidationException
     */
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
    
    /**
     * Validates a password.
     *
     * @param $content
     * @throws ValidationException
     */
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
    
    /**
     * Compares two passwords.
     *
     * @param $password1
     * @param $password2
     * @throws ValidationException
     */
    public function comparePasswords($password1, $password2) {
        $errorMessage = null;
        
        if (strcmp ( $password1, $password2 ) != 0) {
            $errorMessage = self::ERROR_PASSWORDS_NOT_MATCH;
            throw new ValidationException ( $errorMessage );
        }
    }

    /**
     * Tests for an empty field.
     *
     * @param $content
     * @throws ValidationException
     */
    public function emptyField($content) {
        if (!isset($content) || strlen ( $content ) == 0) {
            throw new ValidationException ( self::ERROR_FIELD_EMPTY );
        }
    }
    
    /**
     * Tests for a number.
     *
     * @param $content
     * @throws ValidationException
     */
    public function number($content) {
        if (! is_numeric ( $content ) && strlen ( $content ) > 0) {
            throw new ValidationException ( self::ERROR_NOT_NUMBER );
        }
    }
    
    /**
     * Tests for a number greater than zero.
     *
     * @param $content
     * @throws ValidationException
     */
    public function numberGreaterThanZero($content) {
        if (is_numeric ( $content ) && $content < 1) {
            throw new ValidationException ( self::ERROR_NOT_ZERO );
        }
    }
    
    /**
     * Tests for at least one upper case character.
     * At least one lower case character.
     * At least one digit.
     * At least eight characters long.
     *
     * @param $content
     * @throws ValidationException
     */
    public function oneUpperOneLowerOneDigitGreaterEight($content) {
        if (! preg_match ( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}+$/', $content )) {
            throw new ValidationException ( self::ERROR_UPPER_LOWER_NUMBER );
        }
    }
    
    /**
     * Tests that input is alphanumeric.
     *
     * @param $content
     * @throws ValidationException
     */
    public function alphaNumeric($content) {
        $content = preg_replace ( '/\s+/', '', $content );
        
        if (! ctype_alnum ( $content ) && strlen ( $content ) > 0) {
            throw new ValidationException ( self::ERROR_NOT_ALPHANUMERIC );
        }
    }
    
    /**
     * Tests that input contains letters only.
     *
     * @param $content
     * @throws ValidationException
     */
    public function alpha($content) {
        $content = preg_replace ( '/\s+/', '', $content );
        
        if (! ctype_alpha ( $content ) && strlen ( $content ) > 0) {
            throw new ValidationException ( self::ERROR_NOT_ALPHA );
        }
    }
    
    /**
     * Tests that the input only contains numbers or hyphens.
     *
     * @param $content
     * @throws ValidationException
     */
    public function numberHyphen($content) {
        if (! preg_match ( '/^[0-9-]+$/', $content )) {
            throw new ValidationException ( self::ERROR_NOT_NUMBER_OR_HYPHEN );
        }
    }

    /**
     * Validates a date.
     * From PHP Manual.
     *
     * @param $date
     * @param $format
     * @return boolean
     */
    public function validateDate($date, $format) {
        $d = DateTime::createFromFormat ( $format, $date );
        return $d && $d->format ( $format ) == $date;
    }
    
    /**
     * Validates an email address.
     * From PHP Manual.
     *
     * @param $content
     * @throws ValidationException
     */
    public function validateEmail($content) {
        if (! filter_var ( $content, FILTER_VALIDATE_EMAIL )) {
            throw new ValidationException ( self::ERROR_EMAIL_NOT_VALID );
        }
    }
    
    /**
     * Tests for string length at least 4 characters long.
     *
     * @param $content
     * @throws ValidationException
     */
    public function atLeastFour($content) {
        if (strlen ( $content ) < 4) {
            throw new ValidationException ( self::ERROR_STRLEN_LESS_THAN_FOUR );
        }
    }
    
    /**
     * Tests for string length at least 8 characters long.
     *
     * @param $content
     * @throws ValidationException
     */
    public function atLeastEight($content) {
        if (strlen ( $content ) < 8) {
            throw new ValidationException ( self::ERROR_STRLEN_LESS_THAN_EIGHT );
        }
    }
    
    /**
     * Tests for string length 32 characters long.
     *
     * @param string $content
     * @throws ValidationException
     */
    public function activation(string $content) {
        if (strlen ( $content ) != 32) {
            throw new ValidationException ( self::ERROR_ACTIVATION_STRLEN_NOT_THIRTY_TWO );
        }
    }
}
