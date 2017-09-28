<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

define('SALT', 'TooMuchSalt');

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
	function __construct($args = array()) {
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
		if ($this->exists ()) {
			$query = "SELECT * FROM Users WHERE userID = :userID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
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
			throw new UserException ( 'Could not retrieve user.' );
		}
	}
	
	/*
	 * The set() function inserts the user paramaters into the
	 * database including a salted/encrypted password. Initial
	 * status is 'active'. The userID is returned.
	 */
	public function set(): int {
		$query = "INSERT INTO Users 
					SET user = :user,
    					email = :email,
    					password = :password,
    					status = :status,
    					activate = :activate,
						created_at = NULL";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':user', $this->_user );
		$stmt->bindParam ( ':email', $this->_email );
		$encryptedPassword = $this->encryptPassword ();
		$stmt->bindParam ( ':password', $encryptedPassword );
		$initialStatus = $this->initialStatus ();
		$stmt->bindParam ( ':status', $initialStatus );
		$activationCode = $this->activationCode ();
		$stmt->bindParam ( ':activate', $activationCode );
		$stmt->execute ();
		$this->_userID = $db->lastInsertId ();
		if ($this->_userID > 0) {
			return $this->_userID;
		} else {
			return 0;
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
		if ($this->exists ()) {
			
			$query = "SELECT * FROM Users WHERE userID = :userID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_user ) < 1) {
				$this->_user = $row ['user'];
			}
			if (strlen ( $this->_email ) < 1) {
				$this->_email = $row ['email'];
			}
			if (strlen ( $this->_password ) < 1) {
				$this->_password = $row ['password'];
			} else {
				$this->_password = $this->encryptPassword ();
			}
			if (strlen ( $this->_status ) < 1) {
				$this->_status = $row ['status'];
			}
			
			$query = "UPDATE Users 
						SET user = :user,
	    					email = :email,
							password = :password,
							status = :status
						WHERE userID = :userID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->bindParam ( ':user', $this->_user );
			$stmt->bindParam ( ':email', $this->_email );
			$stmt->bindParam ( ':password', $this->_password );
			$stmt->bindParam ( ':status', $this->_status );
			$stmt->execute ();
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The delete() checks the object exists in the database. If it does,
	 * true is returned.
	 */
	public function delete(): bool {
		if ($this->exists ()) {
			
			$query = "DELETE FROM Users
								WHERE userID = :userID";
					
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->execute ();
			if (! $this->exists ()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The exists() function checks to see if the id exists in the database,
	 * if it does, true is returned.
	 */
	public function exists(): bool {
		if ($this->_userID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Users WHERE userID = :userID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			if ($row ['numRows'] > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * Count number of occurences of a user.
	 */
	public function countUser(): int {
		$query = "SELECT COUNT(*) as numUsers
							FROM Users
							WHERE user = :user";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':user', $this->_user );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['numUsers'];
	}
	
	/*
	 * Count number of occurences of an email address.
	 */
	public function countEmail(): int {
		$query = "SELECT COUNT(*) as numUsers
							FROM Users
							WHERE email = :email";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':email', $this->_email );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['numUsers'];
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
	 * Verify users email address. This function sets activate to null
	 * once the email address for the user has been verified.
	 */
	public function activate(): bool {
		if ($this->exists ()) {
			$us = new User ();
			$us->userID = $this->_userID;
			$us->get ( $this->initialStatus () );
			if ($us->activate == $this->_activate) {
				$query = "UPDATE Users
							SET activate = NULL
							WHERE userID = :userID";
				
				$db = Picnic::getInstance ();
				$stmt = $db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
				$stmt->execute ();
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * Retrieves all users that haven't been deleted.
	 */
	public function getUsers(): array {
		
		$query = "SELECT userID, user, email, status FROM Users
					WHERE status != 'deleted'";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->execute ();
		$users = array ();
		
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			
			$user = array (
					'userID' => $row ['userID'],
					'user' => $row ['user'],
					'email' => $row ['email'],
					'status' => $row ['status'] 
			);
			
			$users [] = $user;
		}
		
		return $users;
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
		$this->_password = $this->encryptPassword ();

		$query = "SELECT userID, user, email, status
					FROM Users
					WHERE email = :email
    				AND password = :password
    				AND (status != 'deleted' && status != 'suspended')
					AND activate IS NULL";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':email', $this->_email );
		$stmt->bindParam ( ':password', $this->_password );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );

		if ($row)
		{
			$this->_userID = $row ['userID'];
			$this->_user = $row ['user'];
			$this->_status = $row ['status'];

			$_SESSION ['picnic'] = true;
			$_SESSION ['userID'] = $this->_userID;
			$_SESSION ['user'] = $this->_user;
			$_SESSION ['email'] = $this->_email;
			$_SESSION ['status'] = $this->_status;
			return true;
		} else {
			return false;
		} 
	}
	
	/*
	 * For the user to logout, first the session variables are destroyed,
	 * then the session itself is destroyed. Finally the cookie is
	 * destroyed. If successfully logged out, true is returned.
	 */
	public function logout(): bool {
		$_SESSION = array();
		if(session_status() == PHP_SESSION_ACTIVE){
			session_destroy ();
		}
		return true;
	}
	
	/*
	 * Compares a password with the one in the database
	 */
	public function checkPassword(): void {
		$this->_password = $this->encryptPassword ();
		
		$query = "SELECT *
					FROM Users
					WHERE email = :email
    				AND password = :password
    				AND (status != 'deleted' || status != 'suspended')
					AND activate IS NULL";
	
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':email', $this->_email );
		$stmt->bindParam ( ':password', $this->_password );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		$this->_userID = $row ['userID'];
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
		} elseif($this->_activate == NULL) {
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