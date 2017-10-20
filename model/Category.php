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
 * @property integer $_categoryID;
 * @property integer $_parentID;
 * @property string $_category;
 * @property string $_created_at;
 * @property string $_updated_at;
 */

class Category {

	private $_categoryID = 0;
	private $_parentID = 0;
	private $_category = '';
	private $_created_at;
	private $_updated_at;
	private $db;
	
	const ROOT_CATEGORY = 0;
	const ERROR_CATEGORY_NOT_EXIST = 'Category does not exist!';
	const ERROR_CATEGORY_NOT_CREATED = 'The category was not created!';
	const ERROR_CATEGORY_NOT_UPDATED = 'The category was not updated!';
	const ERROR_PARENT_ID_NOT_EXIST = 'The parent category does not exist!';
	const ERROR_PARENT_ID_NONE = 'Input is required!';

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
	 * The get() method first confirms that the item object exists in the database.
	 * It then retrieves the attributes from the database. The attributes are set and
	 * returned as a Category object.  If the category doesn't exist, an exception is 
	 * thrown.
	 */
	public function get(): Category {
		if ($this->exists ()) {
			$query = "SELECT * FROM Categories WHERE categoryID = :categoryID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_parentID = $row ['parentID'];
			$this->_category = $row ['category'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
		} else {
			throw new ModelException ( self::ERROR_CATEGORY_NOT_EXIST );
		}
		return $this;
	}
	
	/**
	 * The set() method inserts the category paramaters into the
	 * database. The categoryID is returned.  If the parentID doesn't
	 * exist or the category parameter is empty, an exception is thrown.
	 */
	public function set(): int {
		$v = new Validation();
		
		try {
			$v->emptyField($this->parentID);
			$v->number($this->parentID);
			$v->emptyField($this->category);
			
			if($this->parentID > 0){
				$this->categoryID = $this->parentID;
				if(!$this->exists()){
					throw new ModelException ( self::ERROR_PARENT_ID_NOT_EXIST);
				}
				$this->categoryID = NULL;
			}
			
			$query = "SELECT COUNT(*) AS numCategories FROM Categories";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if($row['numCategories'] > 1 && $this->parentID == 0){
				throw new ModelException ( self::ERROR_PARENT_ID_NONE);
			}
			
			$query = "INSERT INTO Categories
					SET parentID = :parentID,
						category = :category,
						created_at = NULL";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':parentID', $this->_parentID );
			$stmt->bindParam ( ':category', $this->_category );
			$stmt->execute ();
			
			if($stmt->rowCount() > 0){
				return $this->_categoryID = $this->db->lastInsertId ();
			} else {
				throw new ModelException ( self::ERROR_CATEGORY_NOT_CREATED);
			}
		} catch (ValidationException $e) {
			throw new ModelException ( $e->getMessage() );
		}
	}
	
	/**
	 * The update() method confirms the object already exists in the database.
	 * If it does, all the current attributes are retrieved. Where the new
	 * attributes have not been set, they are set with the values already existing in
	 * the database.  The method returns a boolean.
	 */
	public function update(): bool {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM Categories WHERE categoryID = :categoryID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (is_numeric( $this->_parentID ) && $this->parentID > 0) {
				$trueCategoryID = $this->_categoryID;
				$this->_categoryID = $this->parentID;
				if($this->exists()){
					$this->_categoryID = $trueCategoryID;
				} else {
					$this->_parentID = $row ['parentID'];
				}
			} else {
				$this->_parentID = $row ['parentID'];
			}
			if (strlen ( $this->_category ) < 1) {
				$this->_category = $row ['category'];
			}
			
			$query = "UPDATE Categories
						SET parentID = :parentID,
							category = :category
						WHERE categoryID = :categoryID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
			$stmt->bindParam ( ':parentID', $this->_parentID );
			$stmt->bindParam ( ':category', $this->_category );
			$stmt->execute ();
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * The delete() method checks the object exists in the database. If it does,
	 * it is deleted and true is returned.
	 */
	public function delete(): bool {
		if ($this->exists ()) {
			
			$query = "DELETE FROM Categories
						WHERE categoryID = :categoryID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
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
	
	/**
	 * The exists() method checks to see if the id exists in the database,
	 * if it does, true is returned.
	 */
	public function exists(): bool {
		if ($this->_categoryID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Categories WHERE categoryID = :categoryID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
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
		echo '<br /><strong>Category Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_parentID) {
			echo 'parentID => ' . $this->_parentID . '<br/>';
		}
		if ($this->_category) {
			echo 'category => ' . $this->_category . '<br/>';
		}
	}
}
?>