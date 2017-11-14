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
 * @property integer $_item_noteID;
 * @property integer $_itemID;
 * @property integer $_noteID;
 */
class ItemNotes {
	private $_item_noteID = 0;
	private $_itemID = 0;
	private $_noteID = 0;
	private $db;
	const ERROR_ITEM_NOTE_ID_NOT_EXIST = 'The ItemNoteID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_NOTE_ID_NOT_EXIST = 'The NoteID does not exist!';
	const ERROR_NOTE_ID_ALREADY_EXIST = 'The NoteID is already in Item_notes!';
	const ERROR_ITEM_NOTE_NOT_DELETED = 'The ItemNote was not deleted!';
	
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
	 * @return ItemNotes
	 */
	public function get(): ItemNotes {
		if ($this->exists ()) {
			$query = "SELECT * FROM Item_notes WHERE item_noteID = :item_noteID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':item_noteID', $this->_item_noteID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_itemID = $row ['itemID'];
			$this->_noteID = $row ['noteID'];
			return $this;
		} else {
			throw new ModelException ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
		}
	}
	
	/**
	 * if itemID and/or noteID are invalid a ModelException is thrown.
	 * If not,
	 * a the database is checked to ensure the combination doesn't already exist.
	 * If it does, the item_noteID of that entry is returned. If not, the data is
	 * entered into the database and the newly created item_noteID is returned.
	 *
	 * @throws ModelException
	 * @return int
	 */
	public function set(): int {
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		$n = new Note ( $this->db );
		$n->noteID = $this->_noteID;
		
		if ($i->exists ()) {
			if ($n->exists ()) {
				if (! $this->existsNoteID ()) {
					$query = "SELECT * FROM Item_notes
					WHERE itemID = :itemID
					AND noteID = :noteID";
					
					$stmt = $this->db->prepare ( $query );
					$stmt->bindValue ( ':itemID', $this->_itemID );
					$stmt->bindValue ( ':noteID', $this->_noteID );
					$stmt->execute ();
					if ($stmt->rowCount () > 0) {
						$row = $stmt->fetch ( PDO::FETCH_ASSOC );
						$this->_item_noteID = $row ['item_noteID'];
						return $this->_item_noteID;
					} else {
						$query = "INSERT INTO Item_notes
					SET itemID = :itemID,
						noteID = :noteID";
						
						$stmt = $this->db->prepare ( $query );
						$stmt->bindValue ( ':itemID', $this->_itemID );
						$stmt->bindValue ( ':noteID', $this->_noteID );
						$stmt->execute ();
						$this->_item_noteID = $this->db->lastInsertId ();
						if ($this->_item_noteID > 0) {
							return $this->_item_noteID;
						} else {
							return 0;
						}
					}
				} else {
					throw new ModelException ( self::ERROR_NOTE_ID_ALREADY_EXIST );
				}
			} else {
				throw new ModelException ( self::ERROR_NOTE_ID_NOT_EXIST );
			}
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
	}
	
	/**
	 * First the existence of the item_noteID is confirmed.
	 * If it exists, the
	 * validity of both the itemID and noteID are checked. If either are found
	 * to be invalid, the update is carried out with the original values.
	 *
	 * @return bool
	 */
	public function update(): bool {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM Item_notes WHERE item_noteID = :item_noteID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':item_noteID', $this->_item_noteID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			$i = new Item ( $this->db );
			$i->itemID = $this->_itemID;
			$n = new Note ( $this->db );
			$n->noteID = $this->_noteID;
			
			if (! $i->exists ()) {
				$this->_itemID = $row ['itemID'];
			}
			if (! $n->exists ()) {
				$this->_noteID = $row ['noteID'];
			} elseif ($this->existsNoteID ()) {
				$this->_noteID = $row ['noteID'];
			}
			
			$query = "UPDATE Item_notes
						SET itemID = :itemID,
							noteID = :noteID
						WHERE item_noteID = :item_noteID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':item_noteID', $this->_item_noteID );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->bindValue ( ':noteID', $this->_noteID );
			$stmt->execute ();
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Deletes the ItemNote from the database.
	 *
	 * @return boolean
	 */
	public function delete(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField ( $this->_item_noteID );
			
			if ($this->exists ()) {
				$query = "DELETE FROM Item_notes
						WHERE item_noteID = :item_noteID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindValue ( ':item_noteID', $this->_item_noteID );
				$stmt->execute ();
				if (! $this->exists ()) {
					return true;
				} else {
					return false;
				}
			} else {
				throw new ModelException ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Confirms the existence of the ItemNote in the database.
	 *
	 * @return bool
	 */
	public function exists(): bool {
		if ($this->_item_noteID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Item_notes WHERE item_noteID = :item_noteID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':item_noteID', $this->_item_noteID );
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
	 * Confirms the existence of noteID in ItemNotes.
	 *
	 * @return bool
	 */
	public function existsNoteID(): bool {
		$n = new Note ( $this->db );
		$n->noteID = $this->_noteID;
		if ($n->exists ()) {
			$query = "SELECT COUNT(*) AS numRows FROM Item_notes WHERE noteID = :noteID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':noteID', $this->_noteID );
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
	 * Confirms the existence of itemID in ItemNotes.
	 *
	 * @return bool
	 */
	public function existsItemID(): bool {
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		if ($i->exists ()) {
			$query = "SELECT COUNT(*) AS numRows FROM Item_notes WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
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
	 * Counts the number of notes for an item.
	 *
	 * @return int
	 */
	public function count(): int {
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		
		if ($i->exists ()) {
			$query = "SELECT COUNT(*) as num
							FROM Item_notes
							WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			return $row ['num'];
		} else {
			return 0;
		}
	}
	
	/**
	 * Retrieves all noteID's for an item and returns them as an
	 * array of itemNote Objects.
	 *
	 * @return array
	 */
	public function getItemNotes(): array {
		$objects = array ();
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		
		if ($i->exists ()) {
			$query = "SELECT * FROM Item_notes WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->execute ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$itemNote = new ItemNotes ( $this->db );
				$itemNote->item_noteID = $row ['item_noteID'];
				$itemNote->itemID = $row ['itemID'];
				$itemNote->noteID = $row ['noteID'];
				
				$objects [] = $itemNote;
			}
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
		return $objects;
	}
	
	/**
	 * Retrieves an ItemID for a note and returns it as an itemNote
	 * Object.
	 *
	 * @return ItemNotes
	 */
	public function getItemNote(): ItemNotes {
		$n = new Note ( $this->db );
		$n->noteID = $this->_noteID;
		
		if ($n->exists ()) {
			$query = "SELECT * FROM Item_notes WHERE noteID = :noteID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':noteID', $this->_noteID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_item_noteID = $row ['item_noteID'];
			$this->_itemID = $row ['itemID'];
		} else {
			throw new ModelException ( self::ERROR_NOTE_ID_NOT_EXIST );
		}
		return $this;
	}
	
	/**
	 * Deletes an ItemNote based on a noteID.
	 *
	 * @return boolean
	 */
	public function deleteItemNote(): bool {
		$n = new Note ( $this->db );
		$n->noteID = $this->_noteID;
		
		if ($n->exists ()) {
			
			$query = "DELETE FROM Item_notes
					WHERE noteID = :noteID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':noteID', $this->_noteID );
			$stmt->execute ();
			if ($stmt->rowCount () > 0) {
				if ($n->delete ()) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			throw new ModelException ( self::ERROR_NOTE_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Deletes an ItemNote and all associated notes based on an itemID.
	 *
	 * @return boolean
	 */
	public function deleteItemNotes(): bool {
		if ($this->existsItemID ()) {
			
			$itemNotes = $this->getItemNotes ();
			
			foreach ( $itemNotes as $itemNote ) {
				$note = new Note ( $this->db );
				$note->noteID = $itemNote->noteID;
				if ($itemNote->delete ()) {
					try {
						$note->delete ();
					} catch ( ModelException $e ) {
						throw new ModelException ( $e->getMessage () );
					}
				} else {
					throw new ModelException ( self::ERROR_ITEM_NOTE_NOT_DELETED );
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