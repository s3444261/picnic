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
 * @property integer $_item_commentID;
 * @property integer $_itemID;
 * @property integer $_commentID;
 */
class ItemComments {
	private $_item_commentID = 0;
	private $_itemID = 0;
	private $_commentID = 0;
	private $db;
	const ERROR_ITEM_COMMENT_ID_NOT_EXIST = 'The ItemCommentID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_COMMENT_ID_NOT_EXIST = 'The CommentID does not exist!';
	const ERROR_COMMENT_ID_ALREADY_EXIST = 'The CommentID is already in Item_comments!';
	const ERROR_ITEM_COMMENT_NOT_DELETED = 'The ItemComment was not deleted!';
	
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
	 * First confirms that the object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * the object returned.
	 *
	 * @throws ModelException
	 * @return ItemComments
	 */
	public function get(): ItemComments {
		if ($this->exists ()) {
			$query = "SELECT * FROM Item_comments WHERE item_commentID = :item_commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_commentID', $this->_item_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_itemID = $row ['itemID'];
			$this->_commentID = $row ['commentID'];
			return $this;
		} else {
			throw new ModelException ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
		}
	}
	
	/**
	 * if itemID and/or commentID are invalid a ModelException is thrown.
	 * If not,
	 * a the database is checked to ensure the combination doesn't already exist.
	 * If it does, the item_commentID of that entry is returned. If not, the data is
	 * entered into the database and the newly created item_commentID is returned.
	 *
	 * @throws ModelException
	 * @return int
	 */
	public function set(): int {
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		$n = new Comment ( $this->db );
		$n->commentID = $this->_commentID;
		
		if ($i->exists ()) {
			if ($n->exists ()) {
				if (! $this->existsCommentID ()) {
					
					$query = "INSERT INTO Item_comments
								SET itemID = :itemID,
					commentID = :commentID";
					
					$stmt = $this->db->prepare ( $query );
					$stmt->bindParam ( ':itemID', $this->_itemID );
					$stmt->bindParam ( ':commentID', $this->_commentID );
					$stmt->execute ();
					$this->_item_commentID = $this->db->lastInsertId ();
					if ($this->_item_commentID > 0) {
						return $this->_item_commentID;
					} else {
						return 0;
					}
				} else {
					throw new ModelException ( self::ERROR_COMMENT_ID_ALREADY_EXIST );
				}
			} else {
				throw new ModelException ( self::ERROR_COMMENT_ID_NOT_EXIST );
			}
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
	}
	
	/**
	 * First the existence of the item_commentID is confirmed.
	 * If it exists, the
	 * validity of both the itemID and commentID are checked. If either are found
	 * to be invalid, the update is carried out with the original values.
	 *
	 * @return bool
	 */
	public function update(): bool {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM Item_comments WHERE item_commentID = :item_commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_commentID', $this->_item_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			$i = new Item ( $this->db );
			$i->itemID = $this->_itemID;
			$n = new Comment ( $this->db );
			$n->commentID = $this->_commentID;
			
			if (! $i->exists ()) {
				$this->_itemID = $row ['itemID'];
			}
			if (! $n->exists ()) {
				$this->_commentID = $row ['commentID'];
			} elseif ($this->existsCommentID ()) {
				$this->_commentID = $row ['commentID'];
			}
			
			$query = "UPDATE Item_comments
						SET itemID = :itemID,
							commentID = :commentID
						WHERE item_commentID = :item_commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_commentID', $this->_item_commentID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Deletes the ItemComment from the database.
	 *
	 * @return boolean
	 */
	public function delete(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->_item_commentID );
			
			if ($this->exists ()) {
				$query = "DELETE FROM Item_comments
						WHERE item_commentID = :item_commentID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':item_commentID', $this->_item_commentID );
				$stmt->execute ();
				if (! $this->exists ()) {
					return true;
				} else {
					return false;
				}
			} else {
				throw new ModelException ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Confirms the existence of the ItemComment in the database.
	 *
	 * @return bool
	 */
	public function exists(): bool {
		if ($this->_item_commentID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Item_comments WHERE item_commentID = :item_commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_commentID', $this->_item_commentID );
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
	
	/**
	 * Confirms the existence of commentID in ItemComments.
	 *
	 * @return bool
	 */
	public function existsCommentID(): bool {
		$n = new Comment ( $this->db );
		$n->commentID = $this->_commentID;
		if ($n->exists ()) {
			$query = "SELECT COUNT(*) AS numRows FROM Item_comments WHERE commentID = :commentID";
			
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
	
	/**
	 * Confirms the existence of itemID in ItemComments.
	 *
	 * @return bool
	 */
	public function existsItemID(): bool {
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		if ($i->exists ()) {
			$query = "SELECT COUNT(*) AS numRows FROM Item_comments WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
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
	
	/**
	 * Counts the number of comments for an item.
	 *
	 * @return int
	 */
	public function count(): int {
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		
		if ($i->exists ()) {
			$query = "SELECT COUNT(*) as num
							FROM Item_comments
							WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			return $row ['num'];
		} else {
			return 0;
		}
	}
	
	/**
	 * Retrieves all commentID's for an item and returns them as an
	 * array of itemComment Objects.
	 *
	 * @return array
	 */
	public function getItemComments(): array {
		$objects = array ();
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		
		if ($i->exists ()) {
			$query = "SELECT * FROM Item_comments WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$itemComment = new ItemComments ( $this->db );
				$itemComment->item_commentID = $row ['item_commentID'];
				$itemComment->itemID = $row ['itemID'];
				$itemComment->commentID = $row ['commentID'];
				
				$objects [] = $itemComment;
			}
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
		return $objects;
	}
	
	/**
	 * Retrieves an ItemID for a comment and returns it as an itemComment
	 * Object.
	 *
	 * @return ItemComments
	 */
	public function getItemComment(): ItemComments {
		$n = new Comment ( $this->db );
		$n->commentID = $this->_commentID;
		
		if ($n->exists ()) {
			$query = "SELECT * FROM Item_comments WHERE commentID = :commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_item_commentID = $row ['item_commentID'];
			$this->_itemID = $row ['itemID'];
		} else {
			throw new ModelException ( self::ERROR_COMMENT_ID_NOT_EXIST );
		}
		return $this;
	}
	
	/**
	 * Deletes an ItemComment based on a commentID.
	 *
	 * @return boolean
	 */
	public function deleteItemComment(): bool {
		$c = new Comment ( $this->db );
		$c->commentID = $this->_commentID;
		
		if ($c->exists ()) {
			
			$query = "DELETE FROM Item_comments
					WHERE commentID = :commentID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			if ($stmt->rowCount () > 0) {
				if ($c->delete ()) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			throw new ModelException ( self::ERROR_COMMENT_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Deletes an ItemComment and all associated comments based on an itemID.
	 *
	 * @return boolean
	 */
	public function deleteItemComments(): bool {
		if ($this->existsItemID ()) {
			
			$itemComments = $this->getItemComments ();
			
			foreach ( $itemComments as $itemComment ) {
				$comment = new Comment ( $this->db );
				$comment->commentID = $itemComment->commentID;
				if ($itemComment->delete ()) {
					try {
						$comment->delete ();
					} catch ( ModelException $e ) {
						throw new ModelException ( $e->getMessage () );
					}
				} else {
					throw new ModelException ( self::ERROR_ITEM_COMMENT_NOT_DELETED );
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
		if ($this->_itemID) {
			echo 'itemID => ' . $this->_itemID . '<br/>';
		}
		if ($this->_commentID) {
			echo 'commentID => ' . $this->_commentID . '<br/>';
		}
	}
}
?>