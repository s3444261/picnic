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
 * @property integer $_categoryID;
 * @property integer $_parentID;
 * @property string $_category;
 * @property string $_created_at;
 * @property string $_updated_at;
 */

class Categories {

	private $db;

	const ROOT_CATEGORY = 0;
	
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
			$object->_categoryID = $row ['categoryID'];
			$object->_parentID = $row ['parentID'];
			$object->_category = $row ['category'];
			$object->_created_at = $row ['created_at'];
			$object->_updated_at = $row ['updated_at'];
			
			$objects[] = $object;
		}
		return $objects;
	}

	/*
	 * The getCategories() method retrieves all categories for the given parent category and
	 * returns them as an array of category objects.
	 */
	public function getCategoriesIn(int $parentCategory): array {

		if ($parentCategory == self::ROOT_CATEGORY) {
			$query = "SELECT * FROM Categories WHERE parentID IS NULL";
			$stmt = $this->db->prepare($query);
		}
		else {
			$query = "SELECT * FROM Categories WHERE parentID = :parentCategoryId";
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(':parentCategoryId', $parentCategory);
		}

		$stmt->execute();
		$objects = array();
		while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){

			$object = new Category($this->db);
			$object->_categoryID = $row ['categoryID'];
			$object->_parentID = $row ['parentID'];
			$object->_category = $row ['category'];
			$object->_created_at = $row ['created_at'];
			$object->_updated_at = $row ['updated_at'];

			$objects[] = $object;
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