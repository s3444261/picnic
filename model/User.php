<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */
require_once __DIR__ . '/../config/config.php';

define ( 'SALT', 'TooMuchSalt' );

/**
 *
 * @property integer $_userID;
 * @property string $_user;
 * @property string $_email;
 * @property string $_password;
 * @property string $_status;
 * @property string $_activate;
 */
class User {
	private $_userID = '';
	private $_user = '';
	private $_email = '';
	private $_password = '';
	private $_status = '';
	private $_activate = '';
	private $_created_at;
	private $_updated_at;
	private $db;
	
	const ERROR_USER_NOT_EXIST = 'User does not exist!';
	const ERROR_EMAIL_NOT_EXIST = 'Email does not exist!';
	const ERROR_USER_SET = 'Failed to create user!';
	const ERROR_USER_NOT_UPDATED = 'User Not Updated!';
	const ERROR_PASSWORD_NOT_UPDATED = 'Password Not Updated!';
	const ERROR_USER_ID_NOT_INT = 'UserID must be an integer!';
	const ERROR_USER_ID_INVALID = 'Invalid userID!';
	const ERROR_ACTIVATION_FAILURE = 'Failed to activate account!';
	const ERROR_ACTIVATION_CODE_RETRIEVE = 'Failed to retrieve user!';
	const ERROR_ACTIVATION_CODE_NOT_MATCH = 'Activation Codes did not match!';
	const ERROR_USER_DUPLICATE = 'This user name is not available!';
	const ERROR_EMAIL_DUPLICATE = 'This email address is not available!';
	
	
	function __construct(PDO $pdo, $args = array()) {
		$this->db = $pdo;
		
		foreach ( $args as $key => $val ) {
			$name = '_' . $key;
			if (isset ( $this->{$name} )) {
				$this->{$name} = $val;
			}
		}
	}
	public function &__get($name) {
		$name = '_' . $name;
		return $this->$name;
	}
	public function __set($name, $value) {
		$name = '_' . $name;
		$this->$name = $value;
	}
	
	/*
	 * There are no private static constants in PHP so I have used
	 * a private static function instead.
	 */
	private static function initialStatus() {
		return 'active';
	}
	private static function salt() {
		return SALT;
	}
	
	/*
	 * The get() function first confirms that the user object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * true is returned.
	 */
	public function get(): User {
		$v = new Validation();
		
		try {
			$v->emptyField($this->userID);
			$v->number($this->userID);
			
			if ($this->exists ()) {
				$query = "SELECT * FROM Users WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
				$stmt->execute ();
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				$this->_user = $row ['user'];
				$this->_email = $row ['email'];
				$this->_password = $row ['password'];
				$this->_status = $row ['status'];
				$this->_activate = $row ['activate'];
				$this->_created_at = $row ['created_at'];
				$this->_updated_at = $row ['updated_at'];
				return $this;
			} else {
				throw new ModelException ( self::ERROR_USER_NOT_EXIST);
			}			
		} catch (ValidationException $e) {
			throw new ModelException ( $e->getMessage() );
		}
	}
	
	/*
	 * The getUserIdByEmail searches for a user based on email address
	 */
	public function getUserIdByEmail(): int {
		$v = new Validation ();
		
		try {
			$v->email ( $this->email );
			
			$query = "SELECT userID FROM Users WHERE email = :email";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':email', $this->_email );
			$stmt->execute ();
			if ($stmt->rowCount () > 0) {
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				$this->_userID = $row ['userID'];
				return $this->userID;
			} else {
				throw new ModelException ( self::ERROR_EMAIL_NOT_EXIST);
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	/*
	 * 
	 */
	
	/*
	 * The set() function inserts the user paramaters into the
	 * database including a salted/encrypted password. Initial
	 * status is 'active'. The userID is returned.
	 */
	public function set(): int {
		$v = new Validation ();
		
		try {
			$v->userName ( $this->_user );
			$v->email ( $this->_email );
			$v->password ( $this->_password );
			$this->checkUserExist ();
			$this->checkEmailExist ();
			
			$query = "INSERT INTO Users
					SET user = :user,
    					email = :email,
    					password = :password,
    					status = :status,
    					activate = :activate,
						created_at = NULL";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user', $this->_user );
			$stmt->bindParam ( ':email', $this->_email );
			$encryptedPassword = $this->encryptPassword ();
			$stmt->bindParam ( ':password', $encryptedPassword );
			$initialStatus = $this->initialStatus ();
			$stmt->bindParam ( ':status', $initialStatus );
			$activationCode = $this->activationCode ();
			$stmt->bindParam ( ':activate', $activationCode );
			$stmt->execute ();
			if ($stmt->rowCount () > 0) {
				$this->_userID = $this->db->lastInsertId ();
				return $this->_userID;
			} else {
				throw new ModelException ( self::ERROR_USER_SET);
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/*
	 * The update() function confirms the object already exists in the database.
	 * If it does, all the current attributes are retrieved. Where the new
	 * attributes have not been set, they are set with the values already existing in
	 * the database. Activate can not be updated as it is set to null by activate() once
	 * the email address has been verified and serves no other purpose.
	 */
	public function update(): bool {
		$v = new Validation ();
		
		if ($this->exists ()) {
			
			try {
				$v->userName ( $this->_user );
				$v->email ( $this->_email );
				
				$query = "SELECT * FROM Users WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
				$stmt->execute ();
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				
				if ($this->user != $row ['user']) {
					try {
						$this->checkUserExist ();
					} catch ( ModelException $e ) {
						throw new ModelException ( $e->getMessage () );
					}
				}
						
				if ($this->email != $row ['email']) {
					try {
						$this->checkEmailExist ();
					} catch ( ModelException $e ) {
						throw new ModelException ( $e->getMessage () );
					}
				}
								
				if (strlen ( $this->_status ) < 1) {
					$this->_status = $row ['status'];
				}
							
				$query = "UPDATE Users
					   	SET user = :user,
    					email = :email,
						status = :status
						WHERE userID = :userID";
							
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
				$stmt->bindParam ( ':user', $this->_user );
				$stmt->bindParam ( ':email', $this->_email );
				$stmt->bindParam ( ':status', $this->_status );
				$stmt->execute ();
				if($stmt->rowCount() > 0){
					return true;
				} else {
					throw new ModelException ( self::ERROR_USER_NOT_UPDATED);
				}
			} catch ( ValidationException $e ) {
				throw new ModelException ( $e->getMessage () );
			}
		} else {
			throw new ModelException ( self::ERROR_USER_NOT_EXIST);
		}
	}
	
	/*
	 * The method updates the password only.
	 */
	public function updatePassword(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField($this->userID);
			$v->number($this->userID);
			
			if ($this->exists ()) {
				
				try {
					$v->password ( $this->_password );
					$this->_password = $this->encryptPassword ();
					
					$query = "UPDATE Users
						SET password = :password
						WHERE userID = :userID";
					
					$stmt = $this->db->prepare ( $query );
					$stmt->bindParam ( ':userID', $this->_userID );
					$stmt->bindParam ( ':password', $this->_password );
					$stmt->execute ();
					if ($stmt->rowCount () > 0) {
						return true;
					} else {
						throw new ModelException ( self::ERROR_PASSWORD_NOT_UPDATED);
					}
				} catch ( ValidationException $e ) {
					throw new ModelException ( $e->getMessage () );
				}
			} else {
				throw new ModelException ( self::ERROR_USER_NOT_EXIST);
			}
		} catch (ValidationException $e) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/*
	 * The delete() checks the object exists in the database. If it does,
	 * true is returned.
	 */
	public function delete(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField($this->_userID);
			$v->number($this->_userID);
			
			if ($this->exists ()) {
				
				$query = "DELETE FROM Users
								WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
				$stmt->execute ();
				try {
					if (! $this->exists ()) {
						return true;
					} else {
						return false;
					}
				} catch (ModelException $e) {
					throw new ModelException($e->getMessage());
				}
			} else {
				throw new ModelException(self::ERROR_USER_ID_INVALID);
			}
		} catch (ValidationException $e) {
			throw new ModelException($e->getMessage());
		}
	}
	
	/*
	 * The exists() function checks to see if the id exists in the database,
	 * if it does, true is returned.
	 */
	public function exists(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField($this->userID);
			$v->number($this->userID);
			
			if ($this->_userID > 0) {
				$query = "SELECT COUNT(*) AS numRows FROM Users WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
				$stmt->execute ();
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				if ($row ['numRows'] > 0) {
					return true;
				} else {
					return false;
				}
			} else {
				throw new ModelException(self::ERROR_USER_ID_NOT_INT);
			}
		} catch (ValidationException $e) {
			throw new ModelException($e->getMessage());
		}
	}
	
	/*
	 * Count number of occurences of a user.
	 */
	public function countUser(): int {
		$v = new Validation ();
		
		try {
			$v->emptyField($this->user);
			$v->atLeastFour($this->user);
			$v->alpha($this->user);
			
			$query = "SELECT COUNT(*) as numUsers
							FROM Users
							WHERE user = :user";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user', $this->_user );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			return $row ['numUsers'];
			
		} catch (ValidationException $e) {
			throw new ModelException($e->getMessage());
		}
	}
	
	/*
	 * Count number of occurences of an email address.
	 */
	public function countEmail(): int {
		$v = new Validation ();
		
		try {
			$v->emptyField($this->email); 
			$v->email($this->email); 
			
			$query = "SELECT COUNT(*) as numUsers
							FROM Users
							WHERE email = :email";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':email', $this->_email );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			return $row ['numUsers'];
			
		} catch (ValidationException $e) { 
			throw new ModelException($e->getMessage());
		}
	}
	
	/*
	 * Salt & Encrypt the password.
	 */
	private function encryptPassword(): string {
		return sha1 ( $this->_password . $this->salt () );
	}
	
	/*
	 * Generate a random activation code.
	 */
	private function activationCode(): string {
		date_default_timezone_set ( 'UTC' );
		return md5 ( strtotime ( "now" ) . $this->_user . $this->_email );
	}
	
	/*
	 * Generate a random activation code.
	 */
	public function getUserIdByActivationCode(): int {
		$v = new Validation ();
		
		try {
			$v->emptyField($this->activate);
			$v->activation($this->activate);
			
			$query = "SELECT userID FROM Users WHERE activate = :activate";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':activate', $this->_activate );
			$stmt->execute ();
			if ($stmt->rowCount () > 0) {
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				return $row['userID'];
			} else {
				throw new ModelException ( self::ERROR_ACTIVATION_CODE_RETRIEVE);
			}
		} catch (ValidationException $e) {
			throw new ModelException($e->getMessage());
		}
	}
	
	/*
	 * Verify users email address. This function sets activate to null
	 * once the email address for the user has been verified.
	 */
	public function activate(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField($this->userID);
			$v->number($this->userID);
			
			try {
				$this->exists ();
				
				$query = "UPDATE Users
					SET activate = NULL
					WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
				$stmt->execute ();
						
				try {
					$this->get();
					
					if(is_null($this->activate)){
						return true;
					} else {
						throw new ModelException(self::ERROR_ACTIVATION_FAILURE);
					}
				} catch (ModelException $e) {
					throw new ModelException($e->getMessage());
				}
			} catch (ModelException $e) {
				throw new ModelException($e->getMessage());
			}
		} catch (ValidationException $e) {
			throw new ModelException($e->getMessage());
		}
	}
	
	/*
	 * For the user to login, the object must contain at least an email and password.
	 * The supplied password is encrypted. The user is only logged in for this application.
	 * Data is then retrieved from the database based on the email and password. The
	 * status of the user must also be 'active' and the user must have been activated
	 * which is verified by activate being set to NULL. Should all the stars align, the
	 * remaining attributes for the object are retrieved from the database. Providing
	 * a session exists, the session variables are set and a string is returned announcing
	 * that the user is loggedin. In any other instance a message is returned announcing
	 * that the user is not logged in.
	 */
	public function login(): bool {
		$v = new Validation ();
		
		try {
			$v->email ( $this->_email );
			$v->password ( $this->_password );
			
			$this->_password = $this->encryptPassword ();
			
			$query = "SELECT userID, user, email, status
					FROM Users
					WHERE email = :email
    				AND password = :password
    				AND (status != 'deleted' && status != 'suspended')
					AND activate IS NULL";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':email', $this->_email );
			$stmt->bindParam ( ':password', $this->_password );
			$stmt->execute ();
			
			if ($stmt->rowCount () > 0) {
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				$this->_userID = $row ['userID'];
				$this->_user = $row ['user'];
				$this->_status = $row ['status'];
				
				$_SESSION ['userID'] = $this->_userID;
				$_SESSION ['user'] = $this->_user;
				$_SESSION ['email'] = $this->_email;
				$_SESSION ['status'] = $this->_status;
				$_SESSION [MODULE] = true; // We can get rid of this.
				return true;
			} else {
				return false;
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/*
	 * For the user to logout, first the session variables are destroyed,
	 * then the session itself is destroyed. Finally the cookie is
	 * destroyed. If successfully logged out, true is returned.
	 */
	public function logout(): bool {
		$_SESSION = array ();
		if (session_status () == PHP_SESSION_ACTIVE) {
			session_destroy ();
		}
		return true;
	}
	
	/*
	 * Compares a password with the one in the database
	 */
	public function checkPassword(): bool {
		$this->_password = $this->encryptPassword ();
		
		$query = "SELECT *
					FROM Users
					WHERE email = :email
    				AND password = :password
    				AND (status != 'deleted' || status != 'suspended')
					AND activate IS NULL";
		
		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':email', $this->_email );
		$stmt->bindParam ( ':password', $this->_password );
		$stmt->execute ();
		if ($stmt->rowCount () == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * Generates a random password
	 * This method was obtained from:
	 * https://www.phpjabbers.com/generate-a-random-password-with-php-php70.html
	 */
	public function randomPassword($length, $count, $characters): array {
		
		// $length - the length of the generated password
		// $count - number of passwords to be generated
		// $characters - types of characters to be used in the password
		
		// define variables used within the function
		$symbols = array ();
		$passwords = array ();
		$used_symbols = '';
		$pass = '';
		
		// an array of different character types
		$symbols ["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
		$symbols ["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$symbols ["numbers"] = '1234567890';
		$symbols ["special_symbols"] = '!?~@#-_+<>[]{}';
		
		$characters = explode ( ",", $characters ); // get characters types to be used for the passsword
		foreach ( $characters as $key => $value ) {
			$used_symbols .= $symbols [$value]; // build a string with all characters
		}
		$symbols_length = strlen ( $used_symbols ) - 1; // strlen starts from 0 so to get number of characters deduct 1
		
		for($p = 0; $p < $count; $p ++) {
			$pass = '';
			for($i = 0; $i < $length; $i ++) {
				$n = rand ( 0, $symbols_length ); // get a random character from the string with all characters
				$pass .= $used_symbols [$n]; // add the character to the password string
			}
			$passwords [] = $pass;
		}
		
		return $passwords; // return the generated password
	}
	
	/*
	 * Returns a single random password
	 */
	public function getRandomPassword(): string {
		$passwords = $this->randomPassword ( 10, 1, "lower_case,upper_case,numbers,special_symbols" );
		return $passwords [0];
	}
	
	/*
	 * Check to see if user exists.
	 */
	public function checkUserExist(): bool {
		$query = "SELECT * FROM Users WHERE user = :user";
		
		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':user', $this->_user );
		$stmt->execute ();
		$numUser = $stmt->rowCount ();
		
		if ($numUser > 0) {
			throw new ModelException ( self::ERROR_USER_DUPLICATE);
		} else {
			return false;
		}
	}
	
	/*
	 * Check to see if user and/or email exists.
	 */
	public function checkEmailExist(): bool {
		$query = "SELECT * FROM Users WHERE email = :email";
		
		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':email', $this->_email );
		$stmt->execute ();
		$numEmail = $stmt->rowCount ();
		
		if ($numEmail > 0) {
			throw new ModelException ( self::ERROR_EMAIL_DUPLICATE);
		} else {
			return false;
		}
	}
	
	// Display Object Contents
	public function printf(): string {
		echo '<br /><strong>User Object:</strong><br />';
		if ($this->_userID) {
			echo 'userID => ' . $this->_userID . '<br/>';
		}
		if ($this->_user) {
			echo 'user => ' . $this->_user . '<br/>';
		}
		if ($this->_email) {
			echo 'email => ' . $this->_email . '<br/>';
		}
		if ($this->_password) {
			echo 'password => ' . $this->_password . '<br/>';
		}
		if ($this->_status) {
			echo 'status => ' . $this->_status . '<br/>';
		}
		if ($this->_activate) {
			echo 'activate => ' . $this->_activate . '<br/>';
		} elseif ($this->_activate == NULL) {
			echo 'activate => NULL<br/>';
		}
		if ($this->_created_at) {
			echo 'created_at => ' . $this->_created_at . '<br/>';
		}
		if ($this->_updated_at) {
			echo 'updated_at => ' . $this->_updated_at . '<br/>';
		}
	}
}
?>