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
 * @property integer $_item_noteID;
 * @property integer $_itemID;
 * @property integer $_noteID;
 */

class ItemNotes {
	private $_item_noteID= '';
	private $_itemID= '';
	private $_noteID= '';
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
			$query = "SELECT * FROM Item_notes WHERE item_noteID = :item_noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_noteID', $this->_item_noteID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_itemID = $row ['itemID'];
			$this->_noteID = $row ['noteID'];
			return $this;
		} else {
			throw new ItemNotesException ( 'Could not retrieve Item note.' );
		}
	}
	
	/*
	 * The set() function first checks to see if the object parameters already
	 * exist.  If they do the objectID is retrieved and returned.  If they 
	 * don't, they are insserted into the table and the objectID is
	 * retrieved and returned.
	 */
	public function set() {
		$query = "SELECT * FROM Item_notes 
					WHERE itemID = :itemID
					AND noteID = :noteID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->bindParam ( ':noteID', $this->_noteID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		$this->_item_noteID = $row ['item_noteID'];
		
		if ($this->_item_noteID > 0) {
			return $this->_item_noteID;
		} else {
			$query = "INSERT INTO Item_notes
					SET itemID = :itemID,
						noteID = :noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->bindParam ( ':noteID', $this->_noteID );
			$stmt->execute ();
			$this->_item_noteID = $this->db->lastInsertId ();
			if ($this->_item_noteID > 0) {
				return $this->_item_noteID;
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
			
			$query = "SELECT * FROM Item_notes WHERE item_noteID = :item_noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_noteID', $this->_item_noteID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_itemID ) < 1) {
				$this->_itemID = $row ['itemID'];
			}
			if (strlen ( $this->_noteID ) < 1) {
				$this->_noteID = $row ['noteID'];
			}
			
			$query = "UPDATE Item_notes
						SET itemID = :itemID,
							noteID = :noteID
						WHERE item_noteID = :item_noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_noteID', $this->_item_noteID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->bindParam ( ':noteID', $this->_noteID );
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
			
			$query = "DELETE FROM Item_notes
						WHERE item_noteID = :item_noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_noteID', $this->_item_noteID );
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
		if ($this->_item_noteID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Item_notes WHERE item_noteID = :item_noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':item_noteID', $this->_item_noteID );
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
	 * Count number of occurences of a note for an item.
	 */
	public function count() {
		$query = "SELECT COUNT(*) as num
							FROM Item_notes
							WHERE itemID = :itemID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}
	
	/*
	 * getNotes() retrieves all Note Objects for an Item.
	 */
	public function getNotes(){
		
		$query = "SELECT * FROM Item_notes WHERE itemID = :itemID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$objects = array();
		while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
			$itemNote = new ItemNotes($this->db);
			$itemNote->item_noteID = $row ['item_noteID'];
			$itemNote->itemID = $row ['itemID'];
			$itemNote->noteID = $row ['noteID'];
			
			$objects[] = $itemNote;
		}
		
		return $objects;
	}
	
	/*
	 * The getItemNote() method returns the object based on the noteID.
	 */
	public function getItemNote() {
		
		$query = "SELECT * FROM Item_notes WHERE noteID = :noteID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':noteID', $this->_noteID );
		$stmt->execute ();
		if($stmt->rowCount() > 0){
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_item_noteID = $row ['item_noteID'];
			$this->_itemID = $row ['itemID']; 
			return true;
		} else {
			throw new ItemNotesException ( 'Could not retrieve itemNote.' );
		}
	}
	
	/*
	 * The deleteNote() deletes a note based on the noteID
	 */
	public function deleteNote() {
		
		$note = new Note($this->db);
		$note->noteID = $this->_noteID;
		
		$query = "DELETE FROM Item_notes
						WHERE noteID = :noteID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':noteID', $this->_noteID );
		$stmt->execute ();
		if (! $this->exists ()) {
			if($note->delete()){
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
		echo '<br /><strong>Note Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_itemID) {
			echo 'itemID => ' . $this->_itemID . '<br/>';
		}
		if ($this->_noteID) {
			echo 'noteID => ' . $this->_noteID . '<br/>';
		}
	}
}
?>