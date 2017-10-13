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
 * @property integer $_commentID;
 * @property integer $_userID;
 * @property string $_comment;
 * @property string $_created_at;
 * @property string $_updated_at;
 */

class Comment {
	private $_commentID = '';
	private $_userID = '';
	private $_comment = '';
	private $_created_at;
	private $_updated_at;
	private $db;

	// Constructor
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
			$query = "SELECT * FROM Comments WHERE commentID = :commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_userID = $row ['userID'];
			$this->_comment = $row ['comment'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
			return $this;
		} else {
			throw new ModelException('Could not retrieve comment.');
		}
	}
	
	/*
	 * The set() function inserts the objects paramaters into the
	 * database. The objectID is returned.
	 */
	public function set() {
		$query = "INSERT INTO Comments
					SET userID = :userID,
						comment = :comment,
						created_at = NULL";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':userID', $this->_userID );
		$stmt->bindParam ( ':comment', $this->_comment );
		$stmt->execute ();
		$this->_commentID = $this->db->lastInsertId ();
		if ($this->_commentID > 0) {
			return $this->_commentID;
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
			
			$query = "SELECT * FROM Comments WHERE commentID = :commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_userID ) < 1) {
				$this->_userID = $row ['userID'];
			}
			if (strlen ( $this->_comment ) < 1) {
				$this->_comment = $row ['comment'];
			}
			
			$query = "UPDATE Comments
						SET userID = :userID,
							comment = :comment
						WHERE commentID = :commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->bindParam ( ':comment', $this->_comment );
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
			
			$query = "DELETE FROM Comments
						WHERE commentID = :commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
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
		if ($this->_commentID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Comments WHERE commentID = :commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
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
	 * Count number of occurences of comment for a user.
	 */
	public function count() {
		$query = "SELECT COUNT(*) as num
							FROM Comments
							WHERE userID = :userID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':userID', $this->_userID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}
	
	// Display Object Contents
	public function printf() {
		echo '<br /><strong>Comment Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_userID) {
			echo 'userID => ' . $this->_userID . '<br/>';
		}
		if ($this->_comment) {
			echo 'comment => ' . $this->_comment . '<br/>';
		}
	}
}
?>