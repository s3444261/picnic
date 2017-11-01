<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */

/**
 *
 * @property integer $_commentID;
 * @property integer $_userID;
 * @property integer $_itemID;
 * @property string $_comment;
 * @property string $_created_at;
 * @property string $_updated_at;
 */
class Comment {
	private $_commentID = 0;
	private $_userID = 0;
	private $_itemID = 0;
	private $_comment = '';
	private $_created_at;
	private $_updated_at;
	private $db;
	const ERROR_COMMENT_ID_NOT_EXIST = 'The CommentID does not exist!';
	const ERROR_USER_ID_NOT_EXIST = 'The User ID does not exist!';
	const ERROR_COMMENT_NOT_DELETED = 'The comment was not deleted!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';

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
	
	/**
	 * First confirms that the comment object exists in the database.
	 * If it doesn't, an
	 * exception is thrown. If it does exist, it then retrieves the attributes from
	 * the database. The attributes are set and returned.
	 *
	 * @throws ModelException
	 * @return Comment
	 */
	public function get(): Comment {
		if ($this->exists ()) {
			$query = "SELECT * FROM Comments WHERE commentID = :commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_userID = $row ['userID'];
			$this->_itemID = $row ['itemID'];
			$this->_comment = $row ['comment'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
			return $this;
		} else {
			throw new ModelException ( self::ERROR_COMMENT_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Tests to for a valid UserID and comment, then inserts them into
	 * the database.
	 * The commentID is returned.
	 *
	 * @throws ModelException
	 * @return int
	 */
	public function set(): int {
		$v = new Validation ();
		$user = new User ( $this->db );
		$user->userID = $this->userID;
		try {
			if ($user->exists ()) {
				$v->emptyField ( $this->comment );
				
				$query = "INSERT INTO Comments
					SET userID = :userID,
					    itemID = :itemID,
						comment = :comment";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
				$stmt->bindValue ( ':itemID', $this->_itemID );
				$stmt->bindParam ( ':comment', $this->_comment );
				$stmt->execute ();
				$this->_commentID = $this->db->lastInsertId ();
				if ($this->_commentID > 0) {
					return $this->_commentID;
				} else {
					return 0;
				}
			} else {
				throw new ModelException ( self::ERROR_USER_ID_NOT_EXIST );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Confirmat the object already exsits in the database.
	 * If it does, it
	 * goes on to check the UserID and the Comment. If either value is invalid
	 * then the original values are used.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function update(): bool {
		if ($this->exists ()) {

			$query = "SELECT * FROM Comments WHERE commentID = :commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );

			if (strlen ( $this->_comment ) < 1) {
				$this->_comment = $row ['comment'];
			}

			$query = "UPDATE Comments
						SET comment = :comment
						WHERE commentID = :commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->bindParam ( ':comment', $this->_comment );
			$stmt->execute ();
			return true;
		} else {
			throw new ModelException ( self::ERROR_COMMENT_ID_NOT_EXIST );
		}
	}

	/**
	 * Checks the object exists in the database.
	 * If it does,
	 * it is deleted and true is returned.
	 *
	 * @return bool
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
			throw new ModelException ( self::ERROR_COMMENT_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Checks to see if the commentID exists in the database,
	 * if it does, true is returned.
	 *
	 * @return bool
	 */
	public function exists() {
		if ($this->_commentID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Comments WHERE commentID = :commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			return ($row ['numRows'] > 0);
		} else {
			return false;
		}
	}

	/**
	 * Retrieves all commentID's for an item and returns them as an
	 * array of itemComment Objects.
	 * @return array
	 * @throws ModelException
	 */
	public function getItemComments(): array {
		$objects = array ();
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;

		if ($i->exists ()) {
			$query = "SELECT * FROM Comments WHERE itemID = :itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->execute ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$itemComment = new Comment ( $this->db );
				$itemComment->commentID = $row ['commentID'];
				$itemComment->itemID = $row ['itemID'];


				$objects [] = $itemComment;
			}
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
		return $objects;
	}

	public function countCommentsForItem() : int {
		$query = "SELECT COUNT(*) as num FROM Comments WHERE itemID = :itemID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}

	/**
	 * Deletes an ItemComment and all associated comments based on an itemID.
	 * @return bool
	 * @throws ModelException
	 */
	public function deleteItemComments(): bool {
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;

		if ($i->exists ()) {
			$itemComments = $this->getItemComments ();

			foreach ( $itemComments as $itemComment ) {
				if (!$itemComment->delete ()) {
					throw new ModelException ( self::ERROR_COMMENT_NOT_DELETED );
				}
			}
			return true;
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
	}

	/**
	 * Display Object Contents
	 */
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