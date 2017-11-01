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

class Items {
	private $db;
	
	const SEARCH_TEXT = 'srchText';
	const SEARCH_MINOR_CATEGORY_ID = 'srchMajorCategory';
	const SEARCH_MAJOR_CATEGORY_ID = 'srchMinorCategory';
	const SEARCH_MIN_PRICE = 'srchMinPrice';
	const SEARCH_MAX_PRICE = 'srchMaxPrice';
	const SEARCH_MIN_QUANTITY = 'srchQuantity';
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
		
		if (strlen ( $searchString ) > 0) {
			
			$query = "SELECT * FROM Items
						WHERE title LIKE :searchString 
						OR description LIKE :searchString";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':searchString', '%' . $searchString . '%' );
			$stmt->execute ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$item = new Item ( $this->db );
				$item->itemID = $row ['itemID'];
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
		
		if(strlen($args[self::SEARCH_TEXT]) > 0) {
			//$srchTitle = $args[self::SEARCH_TEXT];
			$query = $query . " WHERE title LIKE :srchText OR description LIKE :srchText";
		}

		if($args[self::SEARCH_MIN_PRICE] > 0) {
		//	$srchPrice = $args[self::SEARCH_MIN_PRICE];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE price >= :srchMinPrice";
			} else {
				$query = $query . " AND price >= :srchMinPrice";
			}
		}

		if($args[self::SEARCH_MAX_PRICE] < 0x7FFFFFFF) {
		//	$srchPrice = $args[self::SEARCH_MAX_PRICE];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE price <= :srchMaxPrice";
			} else {
				$query = $query . " AND price <= :srchMaxPrice";
			}
		}
		if($args[self::SEARCH_MIN_QUANTITY] > 1){

		//	$srchQuantity = $args[self::SEARCH_QUANTITY];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE quantity >= :srchQuantity";
			} else {
				$query = $query . " AND quantity >= :srchQuantity";
			}
		}
		
		if(strlen($args[self::SEARCH_CONDITION]) > 0){
			//$srchCondition = $args[self::SEARCH_CONDITION];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE itemcondition = :srchCondition";
			} else {
				$query = $query . " AND itemcondition = :srchCondition";
			}
		}
		
		if(strlen($args[self::SEARCH_STATUS]) > 0){
			//$srchStatus = $args[self::SEARCH_STATUS];
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE itemstatus = :srchStatus";
			} else {
				$query = $query . " AND itemstatus = :srchStatus";
			}
		}

		$stmt = $this->db->prepare ( $query );

		if(strlen($args[self::SEARCH_TEXT]) > 0){
			$stmt->bindValue ( ':srchText', '%' . $args[self::SEARCH_TEXT] . '%' );
		}
		if($args[self::SEARCH_MIN_PRICE] > 0) {
			$stmt->bindValue ( ':srchMinPrice', $args[self::SEARCH_MIN_PRICE] );
		}
		if($args[self::SEARCH_MAX_PRICE] < 0x7FFFFFFF) {
			$stmt->bindValue ( ':srchMaxPrice', $args[self::SEARCH_MAX_PRICE] );
		}
		if($args[self::SEARCH_MIN_QUANTITY] > 1){
			$stmt->bindValue ( ':srchQuantity', $args[self::SEARCH_MIN_QUANTITY] );
		}
		if(strlen($args[self::SEARCH_CONDITION]) > 0){
			$stmt->bindValue ( ':srchCondition',  $args[self::SEARCH_CONDITION] );
		}
		if(strlen($args[self::SEARCH_STATUS]) > 0){
			$stmt->bindValue ( ':srchStatus', $args[self::SEARCH_STATUS] );
		}

		$stmt->execute ();

		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$item = new Item ( $this->db );
			$item->itemID = $row ['itemID'];
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