<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

require_once dirname(__FILE__) . '/ItemException.php';

if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}

/**
 *
 * @property integer $_itemID;
 * @property string $_title;
 * @property string $_description;
 * @property string $_quantity;
 * @property string $_itemcondition;
 * @property string $_price;
 * @property string $_status;
 * @property string $_created_at;
 * @property string $_updated_at;
 */

class Item {
	private $_itemID = '';
	private $_title = '';
	private $_description = '';
	private $_quantity = '';
	private $_itemcondition = '';
	private $_price = '';
	private $_status = '';
	private $_created_at;
	private $_updated_at;
	
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
	 * The get() function first confirms that the item object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * true is returned.
	 */
	public function get(): Item {
		if ($this->exists ()) {
			$query = "SELECT * FROM Items WHERE itemID = :itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_title = $row ['title'];
			$this->_description = $row ['description'];
			$this->_quantity = $row ['quantity'];
			$this->_itemcondition = $row ['itemcondition'];
			$this->_price = $row ['price'];
			$this->_status = $row ['status'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
			return $this;
		} else {
			throw new ItemException ( 'Could not retrieve item.' );
		}
	}
	
	/*
	 * The set() function inserts the item paramaters into the
	 * database. The itemID is returned.
	 */
	public function set(): int {
		$query = "INSERT INTO Items
					SET title = :title,
						description = :description,
						quantity = :quantity,
						itemcondition = :itemcondition,
						price = :price,
						status = :status,
						created_at = NULL";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':title', $this->_title );
		$stmt->bindParam ( ':description', $this->_description );
		$stmt->bindParam ( ':quantity', $this->_quantity );
		$stmt->bindParam ( ':itemcondition', $this->_itemcondition );
		$stmt->bindParam ( ':price', $this->_price );
		$stmt->bindParam ( ':status', $this->_status ); 
		$stmt->execute ();
		$this->_itemID = $db->lastInsertId ();
		if ($this->_itemID > 0) {
			return $this->_itemID;
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
	public function update(): bool {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM Items WHERE itemID = :itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_title ) < 1) {
				$this->_title = $row ['title'];
			}
			if (strlen ( $this->_description ) < 1) {
				$this->_description = $row ['description'];
			}
			if (strlen ( $this->_quantity ) < 1) {
				$this->_quantity = $row ['quantity'];
			}
			if (strlen ( $this->_itemcondition ) < 1) {
				$this->_itemcondition = $row ['itemcondition'];
			}
			if (strlen ( $this->_price ) < 1) {
				$this->_price = $row ['price'];
			}
			if (strlen ( $this->_status ) < 1) {
				$this->_status = $row ['title'];
			}
			
			$query = "UPDATE Items
						SET title = :title,
							description = :description,
							quantity = :quantity,
							itemcondition = :itemcondition,
							price = :price,
							status = :status
						WHERE itemID = :itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->bindParam ( ':title', $this->_title );
			$stmt->bindParam ( ':description', $this->_description );
			$stmt->bindParam ( ':quantity', $this->_quantity );
			$stmt->bindParam ( ':itemcondition', $this->_itemcondition );
			$stmt->bindParam ( ':price', $this->_price );
			$stmt->bindParam ( ':status', $this->_status );
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
	public function delete(): bool{
		if ($this->exists ()) {
			
			$query = "DELETE FROM Items
						WHERE itemID = :itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
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
	public function exists(): bool{
		if ($this->_itemID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Items WHERE itemID = :itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
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
	
	// Display Object Contents
	public function printf() {
		echo '<br /><strong>Item Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_title) {
			echo 'title => ' . $this->_title . '<br/>';
		}
		if ($this->_description) {
			echo 'description => ' . $this->_description . '<br/>';
		}
		if ($this->_quantity) {
			echo 'quantity => ' . $this->_quantity . '<br/>';
		}
		if ($this->_itemcondition) {
			echo 'itemcondition => ' . $this->_itemcondition . '<br/>';
		}
		if ($this->_price) {
			echo 'price => ' . $this->_price . '<br/>';
		}
		if ($this->_status) {
			echo 'status => ' . $this->_status . '<br/>';
		}
	}
}
?>