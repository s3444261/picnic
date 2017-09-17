<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}

/**
 *
 * @property integer $_user_itemID;
 * @property integer $_userID;
 * @property integer $_itemID;
 */

class UserItems {
	private $_user_itemID= '';
	private $_userID= '';
	private $_itemID= '';
	
	// Constructor
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
	 * The get() function first confirms that the object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * true is returned.
	 */
	public function get() {
		if ($this->exists ()) {
			$query = "SELECT * FROM User_items WHERE user_itemID = :user_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_userID = $row ['userID'];
			$this->_itemID = $row ['itemID'];
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The set() function first checks to see if the object parameters already
	 * exist.  If they do the objectID is retrieved and returned.  If they 
	 * don't, they are insserted into the table and the objectID is
	 * retrieved and returned.
	 */
	public function set() {
		$query = "SELECT * FROM User_items 
					WHERE userID = :userID
					AND itemID = :itemID";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':userID', $this->_userID );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		$this->_user_itemID = $row ['user_itemID'];
		
		if ($this->_user_itemID > 0) {
			return $this->_user_itemID;
		} else {
			$query = "INSERT INTO User_items
					SET userID = :userID,
						itemID = :itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$this->_user_itemID = $db->lastInsertId ();
			if ($this->_user_itemID > 0) {
				return $this->_user_itemID;
			} else {
				return 0;
			}
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
			
			$query = "SELECT * FROM User_items WHERE user_itemID = :user_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_userID ) < 1) {
				$this->_userID = $row ['userID'];
			}
			if (strlen ( $this->_itemID ) < 1) {
				$this->_itemID = $row ['itemID'];
			}
			
			$query = "UPDATE User_items
						SET userID = :userID,
							itemID = :itemID
						WHERE user_itemID = :user_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
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
			
			$query = "DELETE FROM User_items
						WHERE user_itemID = :user_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
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
		if ($this->_user_itemID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM User_items WHERE user_itemID = :user_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
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
	 * Count number of occurences of an item for a user.
	 */
	public function count() {
		$query = "SELECT COUNT(*) as num
							FROM User_items
							WHERE userID = :userID";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':userID', $this->_userID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}
	
	// Display Object Contents
	public function printf() {
		echo '<br /><strong>UserItem Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_userID) {
			echo 'userID => ' . $this->_userID . '<br/>';
		}
		if ($this->_itemID) {
			echo 'itemID => ' . $this->_itemID . '<br/>';
		}
	}
}
?>