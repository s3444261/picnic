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
 * @property integer $_noteID;
 * @property string $_note;
 * @property string $_created_at;
 * @property string $_updated_at;
 */

class Note {
	private $_noteID = 0;
	private $_note = '';
	private $_created_at;
	private $_updated_at;
	private $db;
	
	const ERROR_NOTE_ID_NOT_EXIST = 'The NoteID does not exist!';

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
	 * First confirms that the note object exists in the database.  If it doesn't, an
	 * exception is thrown.  If it does exist, it then retrieves the attributes from 
	 * the database. The attributes are set and true is returned.
	 * 
	 * @throws ModelException
	 * @return Note
	 */
	public function get(): Note {
		if ($this->exists ()) {
			$query = "SELECT * FROM Notes WHERE noteID = :noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':noteID', $this->_noteID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_note = $row ['note'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
			return $this;
		} else {
			throw new ModelException(self::ERROR_NOTE_ID_NOT_EXIST);
		}
	}
	
	/**
	 * Inserts the Note object paramaters into the
	 * database. The noteID is returned.
	 * 
	 * @return int
	 */
	public function set(): int {
		$v = new Validation();
		try {
			$v->emptyField($this->note);
			
			$query = "INSERT INTO Notes
					SET note = :note,
						created_at = NULL";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':note', $this->_note );
			$stmt->execute ();
			if($stmt->rowCount() > 0){
				$this->_noteID = $this->db->lastInsertId ();
				return $this->_noteID;
			} else {
				return 0;
			}
		} catch (ValidationException $e) {
			throw new ModelException($e->getMessage());
		}
	}
	
	/**
	 * Confirms the object already exists in the database.
	 * If it does, all the current attributes are retrieved. Where the new
	 * attributes have not been set, they are set with the values already existing in
	 * the database.
	 * 
	 * @return bool
	 */
	public function update(): bool {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM Notes WHERE noteID = :noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':noteID', $this->_noteID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_note ) < 1) {
				$this->_note = $row ['note'];
			}
			
			$query = "UPDATE Notes
						SET note = :note
						WHERE noteID = :noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':noteID', $this->_noteID );
			$stmt->bindParam ( ':note', $this->_note );
			$stmt->execute ();
			return true;
		} else {
			throw new ModelException(self::ERROR_NOTE_ID_NOT_EXIST);
		}
	}
	
	/**
	 * Checks the object exists in the database. If it does,
	 * true is returned.
	 * 
	 * @return bool
	 */
	public function delete(): bool {
		if ($this->exists ()) {
			
			$query = "DELETE FROM Notes
						WHERE noteID = :noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':noteID', $this->_noteID );
			$stmt->execute ();
			if (! $this->exists ()) {
				return true;
			} else {
				return false;
			}
		} else {
			throw new ModelException(self::ERROR_NOTE_ID_NOT_EXIST);
		}
	}
	
	/**
	 * Checks to see if the noteID exists in the database,
	 * if it does, true is returned.
	 * 
	 * @return bool
	 */
	public function exists(): bool {
		if ($this->_noteID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Notes WHERE noteID = :noteID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':noteID', $this->_noteID );
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
	
	// Display Object Contents
	public function printf() {
		echo '<br /><strong>Note Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_note) {
			echo 'note => ' . $this->_note . '<br/>';
		}
	}
}
?>