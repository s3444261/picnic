<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}

/**
 *
 * @property integer $_category_itemID;
 * @property integer $_categoryID;
 * @property integer $_itemID;
 */

class CategoryItems {
	private $_category_itemID= '';
	private $_categoryID= '';
	private $_itemID= '';
	
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
	 * The get() function first confirms that the object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * true is returned.
	 */
	public function get() {
		if ($this->exists ()) {
			$query = "SELECT * FROM Category_items WHERE category_itemID = :category_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_categoryID = $row ['categoryID'];
			$this->_itemID = $row ['itemID'];
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
		$query = "SELECT * FROM Category_items 
					WHERE categoryID = :categoryID
					AND itemID = :itemID";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':categoryID', $this->_categoryID );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		$this->_category_itemID = $row ['category_itemID'];
		
		if ($this->_category_itemID > 0) {
			return $this->_category_itemID;
		} else {
			$query = "INSERT INTO Category_items
					SET categoryID = :categoryID,
						itemID = :itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$this->_category_itemID = $db->lastInsertId ();
			if ($this->_category_itemID > 0) {
				return $this->_category_itemID;
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
			
			$query = "SELECT * FROM Category_items WHERE category_itemID = :category_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_categoryID ) < 1) {
				$this->_categoryID = $row ['categoryID'];
			}
			if (strlen ( $this->_itemID ) < 1) {
				$this->_itemID = $row ['itemID'];
			}
			
			$query = "UPDATE Category_items
						SET categoryID = :categoryID,
							itemID = :itemID
						WHERE category_itemID = :category_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
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
			
			$query = "DELETE FROM Category_items
						WHERE category_itemID = :category_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
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
		if ($this->_category_itemID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Category_items WHERE category_itemID = :category_itemID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
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
	 * Count number of occurences of an item for a category.
	 */
	public function count() {
		$query = "SELECT COUNT(*) as num
							FROM Category_items
							WHERE categoryID = :categoryID";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':categoryID', $this->_categoryID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}
	
	/*
	 * The getCategoryItems() method retrieves all items held by the category and returns
	 * them as an array of item objects.
	 */
	public function getCategoryItems(): array {
		
		$query = "SELECT * FROM Category_items WHERE categoryID = :categoryID";
		
		$db = Picnic::getInstance ();
		$stmt = $db->prepare ( $query );
		$stmt->bindParam ( ':categoryID', $this->_categoryID );
		$stmt->execute ();
		$objects = array();
		while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
			$item = new Item();
			$item->itemID = $row ['itemID'];
			$item->get();
			
			$objects[] = $item;
		}
		return $objects;
	}
	
	// Display Object Contents
	public function printf() {
		echo '<br /><strong>CategoryItem Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_categoryID) {
			echo 'categoryID => ' . $this->_categoryID . '<br/>';
		}
		if ($this->_itemID) {
			echo 'itemID => ' . $this->_itemID . '<br/>';
		}
	}
}
?>