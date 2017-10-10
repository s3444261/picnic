<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */



class Categories {

	private $db;

	const ROOT_CATEGORY = 0;
	const PARENT_ID_NOT_EXIST = 'The parentID does not exist!';
	
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
	 * The getCategories() method retrieves all categories and returns them as an array
	 * of category objects.
	 */
	public function getCategories(): array {
		
		$query = "SELECT * FROM Categories";

		$stmt = $this->db->prepare ( $query );
		$stmt->execute ();
		$objects = array();
		while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
			
			$object = new Category($this->db);
			$object->categoryID = $row ['categoryID'];
			$object->parentID = $row ['parentID'];
			$object->category = $row ['category'];
			$object->created_at = $row ['created_at'];
			$object->updated_at = $row ['updated_at'];
			
			$objects[] = $object;
		} 
		return $objects;
	}

	/*
	 * The getCategories() method retrieves all categories for the given parent category and
	 * returns them as an array of category objects.
	 */
	public function getCategoriesIn(int $parentID): array {
		$v = new Validation();
		$objects = array();
		
		try {
			$v->emptyField($parentID);
			$v->number($parentID);

			// If we get passed ROOT_CATEGORY, it means we want categories that do not
			// have a parent. The following check doesn't apply in that case, so we only
			// do it if we have an actual parent ID.
			if ($parentID != self::ROOT_CATEGORY) {
				$c = new Category($this->db);
				$c->categoryID = $parentID;

				if (!$c->exists()) {
					throw new CategoriesException(self::PARENT_ID_NOT_EXIST);
				}
			}

			if ($parentID == self::ROOT_CATEGORY) {
				// The database uses NULL to indicate no parent, so we have a special
				// case to handle here.
				$query = "SELECT * FROM Categories WHERE parentID IS NULL";
				$stmt = $this->db->prepare($query);
			} else {
				$query = "SELECT * FROM Categories WHERE parentID = :parentID";
				$stmt = $this->db->prepare($query);
				$stmt->bindParam(':parentID', $parentID);
			}

			$stmt->execute();

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

				$object = new Category($this->db);
				$object->categoryID = $row ['categoryID'];
				$object->parentID = $row ['parentID'];
				$object->category = $row ['category'];
				$object->created_at = $row ['created_at'];
				$object->updated_at = $row ['updated_at'];

				$objects[] = $object;
			}
		} catch (ValidationException $e) {
			throw new CategoriesException($e->getMessage());
		}
		return $objects;
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