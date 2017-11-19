<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
require_once __DIR__ . '/../config/config.php';

define ( 'SALT', 'TooMuchSalt' );

/**
 *
 * @property int userID
 * @property string user
 * @property string email
 * @property string status
 * @property bool blocked
 * @property string activate
 * @property string password
 */
class User {
	private $_userID = '';
	private $_user = '';
	private $_email = '';
	private $_password = '';
	private $_status = '';
	private $_activate = '';
	private $_blocked = 0;
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
	function __construct(PDO $pdo, $args = []) {
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
	
	/**
	 * Static method for initialStatus
	 * 
	 * @return string
	 */
	private static function initialStatus() {
		return 'active';
	}
	
	/**
	 * Static method for Salt
	 *
	 * @return string
	 */
	private static function salt() {
		return SALT;
	}
	
	/**
	 * First confirms that the user object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * true is returned.
	 *
	 * @throws ModelException
	 * @return User
	 */
	public function get(): User {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->userID );
			$v->number ( $this->userID );
			
			if ($this->exists ()) {
				$query = "SELECT * FROM Users WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindValue ( ':userID', $this->_userID );
				$stmt->execute ();
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				$this->_user = $row ['user'];
				$this->_email = $row ['email'];
				$this->_password = $row ['password'];
				$this->_status = $row ['status'];
				$this->_activate = $row ['activate'];
				$this->_blocked = $row ['blocked'];
				$this->_created_at = $row ['created_at'];
				$this->_updated_at = $row ['updated_at'];
				return $this;
			} else {
				throw new ModelException ( self::ERROR_USER_NOT_EXIST );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Searches for a user based on email address
	 *
	 * @throws ModelException
	 * @return int
	 */
	public function getUserIdByEmail(): int {
		$v = new Validation ();
		
		try {
			$v->email ( $this->email );
			
			$query = "SELECT userID FROM Users WHERE email = :email";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':email', $this->_email );
			$stmt->execute ();
			if ($stmt->rowCount () > 0) {
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				$this->_userID = $row ['userID'];
				return $this->userID;
			} else {
				throw new ModelException ( self::ERROR_EMAIL_NOT_EXIST );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Inserts the user parameters into the database including a
	 * salted/encrypted password.
	 * Initial status is 'active'.
	 * The userID is returned.
	 *
	 * @throws ModelException
	 * @return int
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
    					activate = :activate";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':user', $this->_user );
			$stmt->bindValue ( ':email', $this->_email );
			$encryptedPassword = $this->encryptPassword ();
			$stmt->bindValue ( ':password', $encryptedPassword );
			$initialStatus = $this->initialStatus ();
			$stmt->bindValue ( ':status', $initialStatus );
			$activationCode = $this->activationCode ();
			$stmt->bindValue ( ':activate', $activationCode );
			$stmt->execute ();
			if ($stmt->rowCount () > 0) {
				$this->_userID = $this->db->lastInsertId ();
				return $this->_userID;
			} else {
				throw new ModelException ( self::ERROR_USER_SET );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Confirms the object already exists in the database.
	 * If it does, all the current attributes are retrieved. Where the new
	 * attributes have not been set, they are set with the values already existing in
	 * the database. Activate can not be updated as it is set to null by activate() once
	 * the email address has been verified and serves no other purpose.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function update(): bool {
		$v = new Validation ();
		
		if ($this->exists ()) {
			
			try {
				$v->userName ( $this->_user );
				$v->email ( $this->_email );
				
				$query = "SELECT * FROM Users WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindValue ( ':userID', $this->_userID );
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
						status = :status,
						blocked = :blocked
						WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindValue ( ':userID', $this->_userID );
				$stmt->bindValue ( ':user', $this->_user );
				$stmt->bindValue ( ':email', $this->_email );
				$stmt->bindValue ( ':status', $this->_status );
				$stmt->bindValue ( ':blocked', $this->_blocked );
				$stmt->execute ();
				if ($stmt->rowCount () > 0) {
					return true;
				} else {
					throw new ModelException ( self::ERROR_USER_NOT_UPDATED );
				}
			} catch ( ValidationException $e ) {
				throw new ModelException ( $e->getMessage () );
			}
		} else {
			throw new ModelException ( self::ERROR_USER_NOT_EXIST );
		}
	}


	public function getUserOwnedItems(): array
	{
		$query = "SELECT * FROM Items WHERE owningUserID = :userID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':userID', $this->userID );
		$stmt->execute ();

		$objects = [];

		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$userItem = new Item ($this->db);
			$userItem->itemID = $row ['itemID'];
			$userItem->owningUserID = $row ['owningUserID'];
			$userItem->title = $row ['title'];
			$userItem->description = $row ['description'];
			$userItem->quantity = $row ['quantity'];
			$userItem->itemcondition = $row ['itemcondition'];
			$userItem->price = $row ['price'];
			$userItem->status = $row ['status'];
			$userItem->type = $row ['type'];
			$userItem->created_at = $row ['created_at'];
			$userItem->updated_at = $row ['updated_at'];
			$objects [] = $userItem;
		}

		return $objects;
	}

	/**
	 * Updates the password only.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function updatePassword(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->userID );
			$v->number ( $this->userID );
			
			if ($this->exists ()) {
				
				try {
					$v->password ( $this->_password );
					$this->_password = $this->encryptPassword ();
					
					$query = "UPDATE Users
						SET password = :password
						WHERE userID = :userID";
					
					$stmt = $this->db->prepare ( $query );
					$stmt->bindValue ( ':userID', $this->_userID );
					$stmt->bindValue ( ':password', $this->_password );
					$stmt->execute ();
					if ($stmt->rowCount () > 0) {
						return true;
					} else {
						throw new ModelException ( self::ERROR_PASSWORD_NOT_UPDATED );
					}
				} catch ( ValidationException $e ) {
					throw new ModelException ( $e->getMessage () );
				}
			} else {
				throw new ModelException ( self::ERROR_USER_NOT_EXIST );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Checks the object exists in the database.
	 * If it does,
	 * true is returned.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function delete(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->_userID );
			$v->number ( $this->_userID );
			
			if ($this->exists ()) {
				
				$query = "DELETE FROM Users
								WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindValue ( ':userID', $this->_userID );
				$stmt->execute ();
				try {
					if (! $this->exists ()) {
						return true;
					} else {
						return false;
					}
				} catch ( ModelException $e ) {
					throw new ModelException ( $e->getMessage () );
				}
			} else {
				throw new ModelException ( self::ERROR_USER_ID_INVALID );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Checks to see if the id exists in the database,
	 * if it does, true is returned.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function exists(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->userID );
			$v->number ( $this->userID );
			
			if ($this->_userID > 0) {
				$query = "SELECT COUNT(*) AS numRows FROM Users WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindValue ( ':userID', $this->_userID );
				$stmt->execute ();
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				if ($row ['numRows'] > 0) {
					return true;
				} else {
					return false;
				}
			} else {
				throw new ModelException ( self::ERROR_USER_ID_NOT_INT );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Count number of occurrences of a user.
	 *
	 * @throws ModelException
	 * @return int
	 */
	public function countUser(): int {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->user );
			$v->atLeastFour ( $this->user );
			$v->alpha ( $this->user );
			
			$query = "SELECT COUNT(*) as numUsers
							FROM Users
							WHERE user = :user";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':user', $this->_user );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			return $row ['numUsers'];
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Count number of occurrences of an email address.
	 *
	 * @throws ModelException
	 * @return int
	 */
	public function countEmail(): int {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->email );
			$v->email ( $this->email );
			
			$query = "SELECT COUNT(*) as numUsers
							FROM Users
							WHERE email = :email";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':email', $this->_email );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			return $row ['numUsers'];
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Salt & Encrypt the password.
	 *
	 * @return string
	 */
	private function encryptPassword(): string {
		return sha1 ( $this->_password . $this->salt () );
	}
	
	/**
	 * Generate a random activation code.
	 *
	 * @return string
	 */
	private function activationCode(): string {
		date_default_timezone_set ( 'UTC' );
		return md5 ( strtotime ( "now" ) . $this->_user . $this->_email );
	}
	
	/**
	 * Generate a random activation code.
	 *
	 * @throws ModelException
	 * @return int
	 */
	public function getUserIdByActivationCode(): int {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->_activate );
			$v->activation ( $this->_activate );
			
			$query = "SELECT userID FROM Users WHERE activate = :activate";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':activate', $this->_activate );
			$stmt->execute ();
			if ($stmt->rowCount () > 0) {
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				return $row ['userID'];
			} else {
				throw new ModelException ( self::ERROR_ACTIVATION_CODE_RETRIEVE );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Verify users email address.
	 * This method sets activate to null
	 * once the email address for the user has been verified.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function activate(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->userID );
			$v->number ( $this->userID );
			
			try {
				$this->exists ();
				
				$query = "UPDATE Users
					SET activate = NULL
					WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindValue ( ':userID', $this->_userID );
				$stmt->execute ();
				
				try {
					$this->get ();
					
					if (is_null ( $this->_activate )) {
						return true;
					} else {
						throw new ModelException ( self::ERROR_ACTIVATION_FAILURE );
					}
				} catch ( ModelException $e ) {
					throw new ModelException ( $e->getMessage () );
				}
			} catch ( ModelException $e ) {
				throw new ModelException ( $e->getMessage () );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * For the user to login, the object must contain at least an email and password.
	 * The supplied password is encrypted. The user is only logged in for this application.
	 * Data is then retrieved from the database based on the email and password. The
	 * status of the user must also be 'active' and the user must have been activated
	 * which is verified by activate being set to NULL. Should all the stars align, the
	 * remaining attributes for the object are retrieved from the database. Providing
	 * a session exists, the session variables are set and a string is returned announcing
	 * that the user is logged in. In any other instance a message is returned announcing
	 * that the user is not logged in.
	 *
	 * @throws ModelException
	 * @return bool
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
    				AND status != 'deleted'
    				AND blocked = 0
					AND activate IS NULL";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':email', $this->_email );
			$stmt->bindValue ( ':password', $this->_password );
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
	
	/**
	 * For the user to logout, first the session variables are destroyed,
	 * then the session itself is destroyed.
	 * Finally the cookie is
	 * destroyed. If successfully logged out, true is returned.
	 *
	 * @return bool
	 */
	public function logout(): bool {
		$_SESSION = [];
		if (session_status () == PHP_SESSION_ACTIVE) {
			session_destroy ();
		}
		return true;
	}
	
	/**
	 * Compares a password with the one in the database
	 *
	 * @return bool
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
		$stmt->bindValue ( ':email', $this->_email );
		$stmt->bindValue ( ':password', $this->_password );
		$stmt->execute ();
		if ($stmt->rowCount () == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Generates a random password
	 * This method was obtained from:
	 * https://www.phpjabbers.com/generate-a-random-password-with-php-php70.html
	 *
	 * @param int $length
	 * @param int $count
	 * @param string $characters
	 * @return array
	 */
	public function randomPassword(int $length, int $count, string $characters): array {
		
		// $length - the length of the generated password
		// $count - number of passwords to be generated
		// $characters - types of characters to be used in the password
		
		// define variables used within the function
		$symbols = [];
		$passwords = [];
		$used_symbols = '';

		// an array of different character types
		$symbols ["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
		$symbols ["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$symbols ["numbers"] = '1234567890';
		$symbols ["special_symbols"] = '!?~@#-_+[]{}';
		
		$characters = explode ( ",", $characters ); // get characters types to be used for the password
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
	
	/**
	 * Returns a single random password
	 *
	 * @return string
	 */
	public function getRandomPassword(): string {
		$passwords = $this->randomPassword ( 10, 1, "lower_case,upper_case,numbers,special_symbols" );
		return $passwords [0];
	}
	
	/**
	 * Check to see if user exists.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function checkUserExist(): bool {
		$query = "SELECT * FROM Users WHERE user = :user";
		
		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':user', $this->_user );
		$stmt->execute ();
		$numUser = $stmt->rowCount ();
		
		if ($numUser > 0) {
			throw new ModelException ( self::ERROR_USER_DUPLICATE );
		} else {
			return false;
		}
	}
	
	/**
	 * Check to see if user and/or email exists.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function checkEmailExist(): bool {
		$query = "SELECT * FROM Users WHERE email = :email";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->_email);
		$stmt->execute();
		$numEmail = $stmt->rowCount();

		if ($numEmail > 0) {
			throw new ModelException (self::ERROR_EMAIL_DUPLICATE);
		} else {
			return false;
		}
	}
}
