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
	 * This method takes an array of strings and searches both title and descriptions
	 * for matches on all parameters.  If the second or subsequent arguments have
	 * a '-' in front of it, the '-' is stripped from the argument and the search
	 * excludes anything that contains those parameters.
	 * 
	 * @param array $searchArray
	 * @return array
	 */
	public function searchArray(array $searchArray): array {
		
		$items = array ();
		$query = "SELECT * FROM Items";
		
		if (count($searchArray) > 0) {
			for($i = 0; $i < count($searchArray); $i++){
				if(count($searchArray) == 1){
					$query = $query . " WHERE title LIKE :searchString" . $i;
					$query = $query . " OR description LIKE :searchString" . $i;
				} else {
					if($i = 0){
						$query1 = " WHERE (title LIKE :searchString" . $i;
						$query2 = " OR (description LIKE :searchString" . $i;
					} else {
						if(substr($searchArray[$i], 0, 1) === '-'){
							$searchArray[$i] = ltrim($searchArray[$i], '-');
							$not = ' NOT ';
						} else {
							$not = '';
						}
						$query1 = $query1 . " AND title . $not . LIKE :searchString" . $i;
						$query2 = $query2 . " AND description . $not . LIKE :searchString" . $i;
					}
				}
			}
			if(count($searchArray) > 1){
				$query = $query . $query1 . ')' . $query2 . ')';
			}
			
			$stmt = $this->db->prepare ( $query );
			for($i = 0; $i < count($searchArray); $i++){
				$stmt->bindValue ( ':searchString' . $i, '%' . $searchArray[$i] . '%' );
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
		
		if(strlen($args[self::SEARCH_MINOR_CATEGORY_ID]) > 0
				|| strlen($args[self::SEARCH_MAJOR_CATEGORY_ID]) > 0){
			$query = "SELECT i.itemID, i.owningUserID, i.title, i.description,";
			$query = $query . " i.quantity, i.itemcondition, i.price, i.itemStatus,";
			$query = $query . " i.created_at, i.updated_at";
			$query = $query . " from Items i";
			$query = $query . " join Category_items ci";
			$query = $query . " on i.itemID = ci.itemID";
		}

		$initialLength = strlen($query);
		
		if(strlen($args[self::SEARCH_TEXT]) > 0) {
			$query = $query . " WHERE title LIKE :srchText OR description LIKE :srchText";
		}

		if($args[self::SEARCH_MIN_PRICE] > 0) {
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE price >= :srchMinPrice";
			} else {
				$query = $query . " AND price >= :srchMinPrice";
			}
		}

		if($args[self::SEARCH_MAX_PRICE] < 0x7FFFFFFF) {
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE price <= :srchMaxPrice";
			} else {
				$query = $query . " AND price <= :srchMaxPrice";
			}
		}
		if($args[self::SEARCH_MIN_QUANTITY] > 1){
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE quantity >= :srchQuantity";
			} else {
				$query = $query . " AND quantity >= :srchQuantity";
			}
		}
		
		if(strlen($args[self::SEARCH_CONDITION]) > 0){
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE itemcondition = :srchCondition";
			} else {
				$query = $query . " AND itemcondition = :srchCondition";
			}
		}
		
		if(strlen($args[self::SEARCH_STATUS]) > 0){
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE itemstatus = :srchStatus";
			} else {
				$query = $query . " AND itemstatus = :srchStatus";
			}
		}
		
		if(strlen($args[self::SEARCH_MINOR_CATEGORY_ID]) > 0){
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE categoryID = :categoryID";
			} else {
				$query = $query . " AND categoryID = :categoryID";
			}
		} elseif(strlen($args[self::SEARCH_MAJOR_CATEGORY_ID]) > 0){
			if(strlen($query) == $initialLength){
				$query = $query . " WHERE categoryID IN(SELECT categoryID from Categories WHERE parentID = :categoryID)";
			} else {
				$query = $query . " AND categoryID IN(SELECT categoryID from Categories WHERE parentID = :categoryID)";
			}
		}
		
		$query = $query . " ORDER BY title";

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
		if(strlen($args[self::SEARCH_MINOR_CATEGORY_ID]) > 0){
			$stmt->bindValue ( ':categoryID', $args[self::SEARCH_MINOR_CATEGORY_ID] );
		} elseif (strlen($args[self::SEARCH_MAJOR_CATEGORY_ID]) > 0){
			$stmt->bindValue ( ':categoryID', $args[self::SEARCH_MAJOR_CATEGORY_ID] );
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