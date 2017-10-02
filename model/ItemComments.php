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
 * @property integer $_item_commentID;
 * @property integer $_itemID;
 * @property integer $_commentID;
 */

class ItemComments {
	private $_item_commentID= '';
	private $_itemID= '';
	private $_commentID= '';
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
	 * The get() function first confirms that the object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * true is returned.
	 */
	public function get() {
		if ($this->exists ()) {
			$query = "SELECT * FROM Item_comments WHERE item_commentID = :item_commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_commentID', $this->_item_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_itemID = $row ['itemID'];
			$this->_commentID = $row ['commentID'];
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
		$query = "SELECT * FROM Item_comments 
					WHERE itemID = :itemID
					AND commentID = :commentID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->bindParam ( ':commentID', $this->_commentID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		$this->_item_commentID = $row ['item_commentID'];
		
		if ($this->_item_commentID > 0) {
			return $this->_item_commentID;
		} else {
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
			
			$query = "SELECT * FROM Item_comments WHERE item_commentID = :item_commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_commentID', $this->_item_commentID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_itemID ) < 1) {
				$this->_itemID = $row ['itemID'];
			}
			if (strlen ( $this->_commentID ) < 1) {
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
	
	/*
	 * The delete() checks the object exists in the database. If it does,
	 * true is returned.
	 */
	public function delete() {
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
			return false;
		}
	}
	
	/*
	 * The exists() function checks to see if the id exists in the database,
	 * if it does, true is returned.
	 */
	public function exists() {
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
	
	/*
	 * Count number of occurences of a comment for an item.
	 */
	public function count() {
		$query = "SELECT COUNT(*) as num
							FROM Item_comments
							WHERE itemID = :itemID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}
	
	/*
	 * getComments() retrieves all Comment Objects for an Item.
	 */
	public function getComments(){
		
		$query = "SELECT * FROM Item_comments WHERE itemID = :itemID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$objects = array();
		while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
			$itemComment = new ItemComments($this->db);
			$itemComment->item_commentID = $row ['item_commentID'];
			$itemComment->itemID = $row ['itemID'];
			$itemComment->commentID = $row ['commentID'];
			
			$objects[] = $itemComment;
		}
		
		return $objects;
	}
	
	/*
	 * The getItemComment() method returns the object based on the itemID and the commentID.
	 */
	public function getItemComment() {
		
		$query = "SELECT * FROM Item_comments WHERE commentID = :commentID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':commentID', $this->_commentID );
		$stmt->execute ();
		if($stmt->rowCount() > 0){
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_id = $row ['item_commentID'];
			$this->_itemID = $row ['itemID'];
			return $this;
		} else {
			throw new ItemCommentsException ( 'Could not retrieve itemComment.' );
		}
	}
	
	/*
	 * The deleteComment() deletes a comment based on the commentID
	 */
	public function deleteComment() {
		
		$comment = new Comment($this->db);
		$comment->commentID = $this->_commentID;
			
			$query = "DELETE FROM Item_comments
						WHERE commentID = :commentID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':commentID', $this->_commentID );
			$stmt->execute ();
			if (! $this->exists ()) {
				if($comment->delete()){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
	}
	
	// Display Object Contents
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