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
 * @property integer $_toUserID;
 * @property integer $_fromUserID;
 * @property integer $_itemID;
 * @property string $_comment;
 * @property string $_status;
 * @property string $_created_at;
 * @property string $_updated_at;
 */
class Comment {
	private $_commentID = 0;
	private $_toUserID = 0;
	private $_fromUserID = 0;
	private $_itemID = 0;
	private $_comment = '';
	private $_status = '';
	private $_created_at;
	private $_updated_at;
	private $db;

	const DB_COL_ID 			= 'commentID';
	const DB_COL_TO_USER_ID 	= 'toUserID';
	const DB_COL_FROM_USER_ID 	= 'fromUserID';
	const DB_COL_ITEM_ID		= 'itemID';
	const DB_COL_COMMENT 		= 'comment';
	const DB_COL_STATUS			= 'status';
	const DB_COL_CREATED_AT 	= 'created_at';
	const DB_COL_MODIFIED_AT 	= 'updated_at';

	const ERROR_COMMENT_ID_NOT_EXIST 	= 'The CommentID does not exist!';
	const ERROR_USER_ID_NOT_EXIST 		= 'The User ID does not exist!';
	const ERROR_COMMENT_NOT_DELETED 	= 'The comment was not deleted!';
	const ERROR_ITEM_ID_NOT_EXIST 		= 'The ItemID does not exist!';
	const ERROR_USER_ID_NOT_INT 		= 'UserID must be an integer!';

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
			$stmt->bindValue ( ':commentID', $this->_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_toUserID = $row [self::DB_COL_TO_USER_ID];
			$this->_fromUserID = $row [self::DB_COL_FROM_USER_ID];
			$this->_itemID = $row [self::DB_COL_ITEM_ID];
			$this->_comment = $row [self::DB_COL_COMMENT];
			$this->_status = $row [self::DB_COL_STATUS];
			$this->_created_at = $row [self::DB_COL_CREATED_AT];
			$this->_updated_at = $row [self::DB_COL_MODIFIED_AT];
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

		$toUser = new User ( $this->db );
		$toUser->userID = $this->_toUserID;

		$fromUser = new User ( $this->db );
		$fromUser->userID = $this->_fromUserID;

		$item = new Item ( $this->db );
		$item->itemID = $this->_itemID;

		try {
			$v = new Validation ();
			$v->emptyField($this->_comment);
			$v->emptyField($this->_status);
		} catch (ValidationException $e) {
			throw new ModelException ( $e->getMessage() );
		}


		if (!$toUser->exists() || !$fromUser->exists()) {
			throw new ModelException (self::ERROR_USER_ID_NOT_EXIST);
		} else if (!$item->exists()) {
			throw new ModelException (self::ERROR_ITEM_ID_NOT_EXIST);
		} else {
			$query = "INSERT INTO Comments
				SET toUserID = :toUserID,
					fromUserID = :fromUserID,
					itemID = :itemID,
					comment = :comment,
					status = :status";

			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':toUserID', $this->_toUserID);
			$stmt->bindValue(':fromUserID', $this->_fromUserID);
			$stmt->bindValue(':itemID', $this->_itemID);
			$stmt->bindValue(':comment', $this->_comment);
			$stmt->bindValue(':status', $this->_status);
			$stmt->execute();
			$this->_commentID = $this->db->lastInsertId();
			if ($this->_commentID > 0) {
				return $this->_commentID;
			} else {
				return 0;
			}
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
		if ($this->exists()) {

			try {
				$query = "SELECT * FROM Comments WHERE commentID = :commentID";

				$stmt = $this->db->prepare($query);
				$stmt->bindValue(':commentID', $this->_commentID);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if (strlen($this->_comment) < 1) {
					$this->_comment = $row [self::DB_COL_COMMENT];
				}

				if (strlen($this->_status) < 1) {
					$this->_status = $row [self::DB_COL_STATUS];
				}

				$v = new Validation ();
				$v->emptyField($this->_comment);
				$v->emptyField($this->_status);

				$query = "UPDATE Comments
						SET comment = :comment,
						    status = :status
						WHERE commentID = :commentID";

				$stmt = $this->db->prepare($query);
				$stmt->bindValue(':commentID', $this->_commentID);
				$stmt->bindValue(':comment', $this->_comment);
				$stmt->bindValue(':status', $this->_status);
				$stmt->execute();
				return true;
			} catch (ValidationException $e) {
				throw new ModelException ($e->getMessage());
			}
		} else {
			throw new ModelException (self::ERROR_COMMENT_ID_NOT_EXIST);
		}
	}

	/**
	 * Checks the object exists in the database.
	 * If it does,
	 * it is deleted and true is returned.
	 * @return bool
	 * @throws ModelException
	 */
	public function delete() {
		if ($this->exists ()) {
			
			$query = "DELETE FROM Comments WHERE commentID = :commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':commentID', $this->_commentID );
			$stmt->execute ();
			return (! $this->exists ());
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
	 * Returns all comments associated with the given user ID, where the user is the receiver.
	 *
	 * @param int $userID	The user ID whose comments will be returned.
	 * @return array       	An array of associated Comment objects.
	 */
	public function getUserCommentsAsSender(int $userID): array {
		$query = "SELECT * FROM Comments WHERE fromUserID = :userID AND status != 'deleted' ORDER BY updated_at DESC";
		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':userID', $userID);
		$stmt->execute ();

		$objects = array ();
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$comment = new Comment ( $this->db );
			$comment->_commentID = $row [self::DB_COL_ID];
			$comment->get();
			$objects [] = $comment;
		}

		return $objects;
	}

	/**
	 * Returns all comments associated with the given user ID, where the user is the sender.
	 *
	 * @param int $userID	The user ID whose comments will be returned.
	 * @return array       	An array of associated Comment objects.
	 */
	public function getUserCommentsAsReceiver(int $userID): array {
		$query = "SELECT * FROM Comments WHERE toUserID = :userID AND status != 'deleted' ORDER BY updated_at DESC";
		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':userID', $userID);
		$stmt->execute ();

		$objects = array ();
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$comment = new Comment ( $this->db );
			$comment->_commentID = $row [self::DB_COL_ID];
			$comment->get();
			$objects [] = $comment;
		}

		return $objects;
	}
	/**
	 * Returns all comments associated with the given user ID, either as the sender or as the
	 * receiver.
	 *
	 * @param int $userID	The user ID whose comments will be returned.
	 * @return array       	An array of associated Comment objects.
	 */
	public function getAllUserComments(int $userID): array {
		$query = "SELECT * FROM Comments WHERE toUserID = :userID OR fromUserID = :userID AND status != 'deleted' ORDER BY updated_at DESC";
		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':userID', $userID);
		$stmt->execute ();

		$objects = array ();
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$comment = new Comment ( $this->db );
			$comment->_commentID = $row [self::DB_COL_ID];
			$comment->get();
			$objects [] = $comment;
		}

		return $objects;
	}

	/**
	 * Retrieves all commentID's for an item and returns them as an
	 * array of Comment Objects.
	 * @return array
	 * @throws ModelException
	 */
	public function getItemComments(): array {
		$objects = array ();
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;

		if ($i->exists ()) {
			$query = "SELECT * FROM Comments WHERE itemID = :itemID ORDER BY updated_at DESC";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->execute ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$comment = new Comment ( $this->db );
				$comment->_commentID = $row [self::DB_COL_ID];
				$comment->get();
				$objects [] = $comment;
			}
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}

		return $objects;
	}

	/**
	 * Gets the number of comments associated with the current item.
	 *
	 * @return int
	 */
	public function countCommentsForItem() : int {
		$query = "SELECT COUNT(*) as num FROM Comments WHERE itemID = :itemID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}
}
