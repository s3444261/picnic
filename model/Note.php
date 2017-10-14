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
	private $_noteID = '';
	private $_note = '';
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
	 * The get() function first confirms that the item object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * true is returned.
	 */
	public function get() {
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
			throw new ModelException('Could not retrieve note.');
		}
	}
	
	/*
	 * The set() function inserts the object paramaters into the
	 * database. The objectID is returned.
	 */
	public function set() {
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
	}
	
	/*
	 * The update() function confirms the object already exists in the database.
	 * If it does, all the current attributes are retrieved. Where the new
	 * attributes have not been set, they are set with the values already existing in
	 * the database.
	 */
	public function update() {
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
			return false;
		}
	}
	
	/*
	 * The delete() checks the object exists in the database. If it does,
	 * true is returned.
	 */
	public function delete() {
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
			return false;
		}
	}
	
	/*
	 * The exists() function checks to see if the id exists in the database,
	 * if it does, true is returned.
	 */
	public function exists() {
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