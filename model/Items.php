<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */

require_once dirname(__FILE__) . '/ModelException.php';

/**
 *
 * @property string $_searchString;
 */

class Items {
	private $_searchString = '';
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
	
	public function search(): array {
		$items = array();
		
		if(strlen($this->_searchString) > 0){
			
			$query = "SELECT * FROM Items
						WHERE title LIKE %searchString%";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
				$item = new Item($this->db);
				$item->title = $row ['title'];
				$item->description = $row ['description'];
				$item->quantity = $row ['quantity'];
				$item->itemcondition = $row ['itemcondition'];
				$item->price = $row ['price'];
				$item->status = $row ['itemStatus'];
				$item->created_at = $row ['created_at'];
				$item->updated_at = $row ['updated_at'];
				
				$items[] = $item;
			}
			
			return $items;
		} else {
			return $items;
		}
	}
}
?>