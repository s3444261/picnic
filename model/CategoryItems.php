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
 * @property integer $_category_itemID;
 * @property integer $_categoryID;
 * @property integer $_itemID;
 */

class CategoryItems {
	private $_category_itemID= '';
	private $_categoryID= '';
	private $_itemID= '';
	private $db;
	
	const ERROR_CATEGORY_NOT_EXIST = 'The categoryItem does not exist!';
	const ERROR_CATEGORYITEM_EXISTS = 'The categoryItem already exists!';

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
	public function get(): CategoryItems {
		if ($this->exists ()) {
			$query = "SELECT * FROM Category_items WHERE category_itemID = :category_itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_categoryID = $row ['categoryID'];
			$this->_itemID = $row ['itemID'];
		} else {
			throw new ModelException ( self::ERROR_CATEGORYITEMS_NOT_EXIST );
		}
		return $this;
	}
	
	/*
	 * The set() function first checks to see if the object parameters already
	 * exist.  If they do the objectID is retrieved and returned.  If they 
	 * don't, they are insserted into the table and the objectID is
	 * retrieved and returned.
	 */
	public function set(): int {
		$c = new Category($this->db);
		$c->categoryID = $this->categoryID;
		$i = new Item($this->db);
		$i->itemID = $this->itemID;
		
		if($c->exists()){
			if($i->exists()){
				$query = "SELECT * FROM Category_items
					WHERE categoryID = :categoryID
					AND itemID = :itemID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':categoryID', $this->_categoryID );
				$stmt->bindParam ( ':itemID', $this->_itemID );
				$stmt->execute ();
				if($stmt->rowCount() == 0){
					
					$query = "INSERT INTO Category_items
					SET categoryID = :categoryID,
						itemID = :itemID";
					
					$stmt = $this->db->prepare ( $query );
					$stmt->bindParam ( ':categoryID', $this->_categoryID );
					$stmt->bindParam ( ':itemID', $this->_itemID );
					$stmt->execute ();
					if($stmt->rowCount() > 0){
						return $this->_category_itemID = $this->db->lastInsertId ();
					} else {
						return 0;
					}
				} else {
					throw new ModelException ( self::ERROR_CATEGORYITEM_EXISTS );
				}
			} else {
				throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
			}
		} else {
			throw new ModelException ( self::ERROR_CATEGORY_ID_NOT_EXIST );
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
			
			$query = "SELECT * FROM Category_items WHERE category_itemID = :category_itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (is_numeric( $this->_categoryID ) && $this->_categoryID > 1) {
				// Leave it as is
			} else {
				$this->_categoryID = $row ['categoryID'];
			}
			
			if (is_numeric( $this->_categoryID ) && $this->_categoryID > 1) {
				// Leave it as is
			} else {
				$this->_itemID = $row ['itemID'];
			}
			
			$query = "SELECT * FROM Category_items 
						WHERE categoryID = :categoryID
						AND itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			
			if($stmt->rowCount() == 0){
				$query = "UPDATE Category_items
						SET categoryID = :categoryID,
							itemID = :itemID
						WHERE category_itemID = :category_itemID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
				$stmt->bindParam ( ':categoryID', $this->_categoryID );
				$stmt->bindParam ( ':itemID', $this->_itemID );
				$stmt->execute ();
				if($stmt->rowCount() > 0){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The delete() checks the object exists in the database. If it does,
	 * true is returned.
	 */
	public function delete(): bool {
		if ($this->exists ()) {
			
			$query = "DELETE FROM Category_items
						WHERE category_itemID = :category_itemID";

			$stmt = $this->db->prepare ( $query );
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
	public function exists(): bool {
		if ($this->_category_itemID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Category_items WHERE category_itemID = :category_itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':category_itemID', $this->_category_itemID );
			$stmt->execute ();
			if ($stmt->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * Count number of occurrences of an item for a category.
	 */
	public function count(): int {
		$query = "SELECT COUNT(*) as num
							FROM Category_items
							WHERE categoryID = :categoryID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':categoryID', $this->_categoryID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}
	
	/*
	 * getCategoryItems()
	 */
	public function getCategoryItems(): array {
			
			$query = "SELECT * FROM Category_items
					WHERE categoryID = :categoryID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
			$stmt->execute ();
			$objects = array();
			while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
				$item = new Item($this->db);
				$item->itemID = $row ['itemID'];
				$item->get();
				
				$objects[] = $item;
			}
			return $objects;
	}
	
	/*
	 * The getCategoryItemsByPage() method retrieves all items held by the category and returns
	 * them as an array of item objects.
	 */
	public function getCategoryItemsByPage(int $pageNumber, int $itemsPerPage, string $status): array {
		$v = new Validation();
		
		try {
			$v->number($pageNumber);
			$v->number($itemsPerPage);
			$v->numberGreaterThanZero($pageNumber);
			$v->numberGreaterThanZero($itemsPerPage);
			
			$pn = $pageNumber;
			$ipp = $itemsPerPage;
			
			$pn = ($pn - 1)*$ipp;
		
			$query = "SELECT Category_items.itemID FROM Category_items INNER JOIN Items ON Category_items.itemID = Items.itemID
					WHERE Category_items.categoryID = :categoryID
					AND Items.itemStatus = :status
					LIMIT " . $pn . "," . $ipp; 

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':categoryID', $this->_categoryID );
			$stmt->bindParam ( ':status', $status );
			$stmt->execute (); 
			$objects = array();
			while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
				$item = new Item($this->db);
				$item->itemID = $row ['itemID']; 
				try {
					$item->get();
				} catch (ItemException $e) {
					throw new ModelException($e->getMessage());
				}
				
				$objects[] = $item;
			}
			return $objects;
		} catch (ValidationException $e) {
			throw new ModelException($e->getMessage());
		}
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