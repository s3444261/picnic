<?php
use phpDocumentor\Reflection\Types\Self_;

/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */
require_once dirname ( __FILE__ ) . '/ModelException.php';

/**
 *
 * @property string $_searchString;
 */
class Items {
	private $db;
	
	const SEARCH_TITLE = 'srchTitle';
	const SEARCH_DESCRIPTION = 'srchDescription';
	const SEARCH_PRICE = 'srchPrice';
	const SEARCH_QUANTITY = 'srchQuantity';
	const SEARCH_CONDITION = 'srchCondition';
	const SEARCH_STATUS = 'srchStatus';
	
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
	 * Searches Items and returns an array of item objects.
	 * 
	 * @return array
	 */
	public function search(string $searchString): array {
		$items = array ();
		
		if (strlen ( $this->_searchString ) > 0) {
			
			$query = "SELECT * FROM Items
						WHERE title LIKE %:searchString%";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':searchString', $searchString );
			$stmt->execute ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$item = new Item ( $this->db );
				$item->title = $row ['title'];
				$item->description = $row ['description'];
				$item->quantity = $row ['quantity'];
				$item->itemcondition = $row ['itemcondition'];
				$item->price = $row ['price'];
				$item->status = $row ['itemStatus'];
				$item->created_at = $row ['created_at'];
				$item->updated_at = $row ['updated_at'];
				
				$items [] = $item;
			}
			
			return $items;
		} else {
			return $items;
		}
	}
	
	/**
	 * Interim advanced search method.
	 * 
	 * @param array $args
	 * @return array
	 */
	public function searchAdvanced(array $args): array {
		$items = array();
		
		$query = "SELECT * FROM Items";
		$initialLength = strlen($query);
		
		if(strlen($args[self::SEARCH_TITLE] > 0)){
			$srchTitle = $args[self::SEARCH_TITLE];
			$query = $query . " WHERE title LIKE %:srchTitle%";
		}
		
		if(strlen($args[self::SEARCH_DESCRIPTION] > 0)){
			$srchDescription = $args[self::SEARCH_DESCRIPTION];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE title LIKE %:srchDescription%";
			} else {
				$query = $query . " AND title LIKE %:srchDescription%";
			}
		}
		
		if(strlen($args[self::SEARCH_PRICE] > 0)){
			$srchPrice = $args[self::SEARCH_PRICE];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE price LIKE %:srchPrice%";
			} else {
				$query = $query . " AND price LIKE %:srchPrice%";
			}
		}
		
		if(strlen($args[self::SEARCH_QUANTITY] > 0)){
			$srchQuantity = $args[self::SEARCH_QUANTITY];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE quantity LIKE %:srchQuantity%";
			} else {
				$query = $query . " AND quantity LIKE %:srchQuantity%";
			}
		}
		
		if(strlen($args[self::SEARCH_CONDITION] > 0)){
			$srchCondition = $args[self::SEARCH_CONDITION];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE itemcondition LIKE %:srchCondition%";
			} else {
				$query = $query . " AND itemcondition LIKE %:srchCondition%";
			}
		}
		
		if(strlen($args[self::SEARCH_STATUS] > 0)){
			$srchStatus = $args[self::SEARCH_STATUS];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE itemstatus LIKE %:srchStatus%";
			} else {
				$query = $query . " AND itemstatus LIKE %:srchStatus%";
			}
		}
		
		$stmt = $this->db->prepare ( $query );
		
		if(strlen($args[self::SEARCH_TITLE] > 0)){
			$stmt->bindParam ( ':srchTitle', $srchTitle );
		}
		if(strlen($args[self::SEARCH_DESCRIPTION] > 0)){
			$stmt->bindParam ( ':srchDescription', $srchDescription );
		}
		if(strlen($args[self::SEARCH_PRICE] > 0)){
			$stmt->bindParam ( ':srchPrice', $srchPrice );
		}
		if(strlen($args[self::SEARCH_QUANTITY] > 0)){
			$stmt->bindParam ( ':srchQuantity', $srchQuantity );
		}
		if(strlen($args[self::SEARCH_CONDITION] > 0)){
			$stmt->bindParam ( ':srchCondition', $srchCondition );
		}
		if(strlen($args[self::SEARCH_STATUS] > 0)){
			$stmt->bindParam ( ':srchStatus', $srchStatus );
		}
		$stmt->execute ();
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$item = new Item ( $this->db );
			$item->title = $row ['title'];
			$item->description = $row ['description'];
			$item->quantity = $row ['quantity'];
			$item->itemcondition = $row ['itemcondition'];
			$item->price = $row ['price'];
			$item->status = $row ['itemStatus'];
			$item->created_at = $row ['created_at'];
			$item->updated_at = $row ['updated_at'];
			
			$items [] = $item;
		}
		
		return $items;
	}
}
?>