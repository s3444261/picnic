<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

/**
 * @property int itemID
 * @property int categoryID
 */
class CategoryItems {
	private $_categoryID = 0;
	private $_itemID = 0;
	private $db;

	const ERROR_CATEGORY_ITEM_EXISTS = 'The categoryItem already exists!';
	const ERROR_CATEGORY_ID_NOT_EXIST = 'The categoryID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The itemID does not exist!';
	
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
	 * First checks to see if the object parameters already
	 * exist.
	 * If they do the objectID is retrieved and returned. If they
	 * don't, they are inserted into the table and the objectID is
	 * retrieved and returned.
	 * @return bool
	 * @throws ModelException
	 */
	public function set(): bool {
		$c = new Category ( $this->db );
		$c->categoryID = $this->_categoryID;
		$i = new Item ( $this->db );
		$i->itemID = $this->_itemID;
		
		if ($c->exists ()) {
			if ($i->exists ()) {
				$query = "SELECT * FROM Category_items
				          WHERE itemID = :itemID
					        AND categoryID = :categoryID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindValue ( ':itemID', $this->_itemID );
				$stmt->bindValue ( ':categoryID', $this->_categoryID );
				$stmt->execute ();
				if ($stmt->rowCount () == 0) {
					
					$query = "INSERT INTO Category_items
					          SET  itemID = :itemID,
					               categoryID = :categoryID";
					
					$stmt = $this->db->prepare ( $query );
					$stmt->bindValue ( ':itemID', $this->_itemID );
					$stmt->bindValue ( ':categoryID', $this->_categoryID );
					$stmt->execute ();

					return ($stmt->rowCount () == 1);
				} else {
					throw new ModelException ( self::ERROR_CATEGORY_ITEM_EXISTS );
				}
			} else {
				throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
			}
		} else {
			throw new ModelException ( self::ERROR_CATEGORY_ID_NOT_EXIST );
		}
	}

	/**
	 * Checks the object exists in the database.
	 * If it does,
	 * true is returned.
	 *
	 * @return bool
	 */
	public function delete(): bool {
		$query = "DELETE FROM Category_items
				  WHERE itemID = :itemID
					AND categoryID = :categoryID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':itemID', $this->_itemID );
		$stmt->bindValue ( ':categoryID', $this->_categoryID );
		$stmt->execute ();
		return ($stmt->rowCount () == 1);
	}
	
	/**
	 * Deletes all CategoryItems based on itemID.
	 *
	 * @return bool
	 */
	public function deleteItem(): bool {

		$category = new Category( $this->db );
		$category->categoryID = $this->_categoryID;

		$item = new Item ( $this->db );
		$item->itemID = $this->_itemID;
		if ($item->exists () && $category->exists()) {
			
			$query = "DELETE FROM Category_items
					  WHERE itemID = :itemID
					    AND categoryID = :categoryID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->bindValue ( ':categoryID', $this->_categoryID );
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
	 * Checks to see if the id exists in the database,
	 * if it does, true is returned.
	 *
	 * @return bool
	 */
	public function exists(): bool {
		if ($this->_itemID > 0 && $this->_categoryID > 0) {

			$query = "SELECT COUNT(*) AS numRows 
                      FROM Category_items 
                      WHERE itemID = :itemID
					    AND categoryID = :categoryID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->bindValue ( ':categoryID', $this->_categoryID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			return ($row['numRows'] > 0);
		} else {
			return false;
		}
	}

	/**
	 * Counts number of occurrences of an item for a category.
	 *
	 * @return int
	 */
	public function count(): int {

		$query = "SELECT COUNT(*) as num
						FROM Category_items
						WHERE categoryID = :categoryID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':categoryID', $this->_categoryID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}

	/**
	 * Gets the first category associated with the given item.
	 *
	 * @param int $itemID
	 * @return Category
	 * @throws ModelException
	 */
	public function getItemCategory(int $itemID): Category {

		$query = "SELECT * FROM Category_items
					WHERE itemID = :itemID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':itemID', $itemID );
		$stmt->execute ();

		$category = new Category($this->db);

		if ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$category->categoryID = $row ['categoryID'];
			try {
				$category->get ();
			} catch ( ModelException $e ) {
				throw new ModelException ( $e->getMessage () );
			}
		}

		return $category;
	}

	/**
	 * Retrieves all the items for a the objects CategoryID.
	 * @return array
	 * @throws ModelException
	 */
	public function getCategoryItems(): array {

		$query = "SELECT * FROM Category_items
				WHERE categoryID = :categoryID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':categoryID', $this->_categoryID );
		$stmt->execute ();
		$objects = array ();
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$item = new Item ( $this->db );
			$item->itemID = $row ['itemID'];
			try {
				$item->get ();
			} catch ( ModelException $e ) {
				throw new ModelException ( $e->getMessage () );
			}

			$objects [] = $item;
		}
		return $objects;
	}
	
	/**
	 * Retrieves all items held by the category and returns
	 * them as an array of item objects.
	 *
	 * @param int $pageNumber        	
	 * @param int $itemsPerPage        	
	 * @param string $type
	 * @throws ModelException
	 * @return array
	 */
	public function getCategoryItemsByPage(int $pageNumber, int $itemsPerPage, string $type): array {
		$v = new Validation ();
		
		try {
			$v->number ( $pageNumber );
			$v->number ( $itemsPerPage );
			$v->numberGreaterThanZero ( $pageNumber );
			$v->numberGreaterThanZero ( $itemsPerPage );
			
			$pn = $pageNumber;
			$ipp = $itemsPerPage;
			
			$pn = ($pn - 1) * $ipp;
			
			$query = "SELECT Category_items.itemID FROM Category_items INNER JOIN Items ON Category_items.itemID = Items.itemID
					WHERE Category_items.categoryID = :categoryID
					AND status = :status
					AND Items.type = :type
					LIMIT " . $pn . "," . $ipp;
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':categoryID', $this->_categoryID );
			$stmt->bindValue ( ':status', 'Active' );
			$stmt->bindValue ( ':type', $type );
			$stmt->execute ();
			$objects = array ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$item = new Item ( $this->db );
				$item->itemID = $row ['itemID'];
				try {
					$item->get ();
				} catch ( ModelException $e ) {
					throw new ModelException ( $e->getMessage () );
				}
				
				$objects [] = $item;
			}
			return $objects;
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
}
