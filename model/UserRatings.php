<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

/**
 *
 * @property integer $_user_ratingID;
 * @property integer $_itemID;
 * @property integer $_sellrating;
 * @property integer $_userID;
 * @property integer $_buyrating;
 * @property string $_transaction;
 * @property string $_created_at;
 * @property string $_updated_at;
 */
class UserRatings {
	private $_user_ratingID = '';
	private $_itemID = '';
	private $_sellrating = 0;
	private $_userID = '';
	private $_buyrating = 0;
	private $_transaction = '';
	private $_created_at;
	private $_updated_at;
	private $db;

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
	 * The get() function first confirms that the user object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * true is returned.
	 */
	public function get() {
		if ($this->exists ()) {
			$query = "SELECT * FROM User_ratings WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_itemID = $row ['itemID'];
			$this->_sellrating = $row ['sellrating'];
			$this->_userID = $row ['userID'];
			$this->_buyrating = $row ['buyrating'];
			$this->_transaction = $row ['transaction'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
			return $this;
		} else {
			throw new UserRatingsException ( 'Could not retrieve userrating.' );
		}
	}
	
	/*
	 * The set() function inserts the objects paramaters into the
	 * database. The objectID is returned.
	 */
	public function set() {
		$query = "INSERT INTO User_ratings
					SET itemID = :itemID,
						sellrating = :sellrating,
						buyrating = :buyrating,
						transaction = :transaction,
						created_at = NULL";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->bindParam ( ':sellrating', $this->_sellrating );
		$stmt->bindParam ( ':buyrating', $this->_buyrating );
		$transactionCode = $this->transactionCode();
		$stmt->bindParam ( ':transaction', $transactionCode);
		$stmt->execute ();
		$this->_user_ratingID = $this->db->lastInsertId ();
		if ($this->_user_ratingID > 0) {
			return $this->_user_ratingID;
		} else {
			return 0;
		}
	}
	
	/*
	 * The update() function confirms the object already exists in the database.
	 * If it does, all the current attributes are retrieved. Where the new
	 * attributes have not been set, they are set with the values already existing in
	 * the database.
	 */
	public function update() {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM User_ratings WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_itemID ) < 1) {
				$this->_itemID = $row ['itemID'];
			}
			if (strlen ( $this->_sellrating ) < 1) {
				$this->_sellrating = $row ['sellrating'];
			}
			if (strlen ( $this->_userID ) < 1) {
				$this->_userID = $row ['userID'];
			}
			if (strlen ( $this->_buyrating ) < 1) {
				$this->_buyrating = $row ['buyrating'];
			}
			if (strlen ( $this->_transaction ) < 1) {
				$this->_transaction = $row ['transaction'];
			}
			
			$query = "UPDATE User_ratings
						SET itemID = :itemID,
							sellrating = :sellrating,
							userID = :userID,
							buyrating = :buyrating,
							transaction = :transaction
						WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->bindParam ( ':sellrating', $this->_sellrating );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->bindParam ( ':buyrating', $this->_buyrating );
			$stmt->bindParam ( ':transaction', $this->_transaction );
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
	public function delete() {
		if ($this->exists ()) {
			
			$query = "DELETE FROM User_ratings
						WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
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
	public function exists() {
		if ($this->_user_ratingID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM User_ratings WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
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
	 * Count number of occurences of sellrating for a user.
	 */
	public function count() {
		$query = "SELECT COUNT(*) as num
							FROM User_ratings
							WHERE itemID = :itemID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}
	
	/*
	 * The update() function confirms the object already exists in the database.
	 * If it does, all the current attributes are retrieved. Where the new
	 * attributes have not been set, they are set with the values already existing in
	 * the database.
	 */
	public function updateTransaction(): bool {
		$query = "SELECT * FROM User_ratings WHERE transaction = :transaction";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':transaction', $this->_transaction );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		$this->_user_ratingID = $row ['user_ratingID'];
		$this->_itemID = $row ['itemID'];
		$this->_sellrating = $row ['sellrating'];
		$this->_transaction = NULL; 
		if ($this->_userID > 0 && $this->_buyrating > 0) {
			if ($this->update ()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * Generate a random transaction code.
	 */
	private function transactionCode(): string {
		date_default_timezone_set ( 'UTC' );
		return md5 ( strtotime ( "now" ) . $this->_itemID . $this->_sellrating );
	}
	
	// Display Object Contents
	public function printf() {
		echo '<br /><strong>UserRatings Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_itemID) {
			echo 'itemID => ' . $this->_itemID . '<br/>';
		}
		if ($this->_sellrating) {
			echo 'sellrating => ' . $this->_sellrating . '<br/>';
		}
		if ($this->_userID) {
			echo 'userID => ' . $this->_userID . '<br/>';
		}
		if ($this->_buyrating) {
			echo 'buyrating => ' . $this->_buyrating . '<br/>';
		}
		if ($this->_transaction) {
			echo 'transaction => ' . $this->_transaction . '<br/>';
		}
	}
}
?>