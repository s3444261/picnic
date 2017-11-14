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
	const SEARCH_MINOR_CATEGORY_ID = 'srchMinorCategory';
	const SEARCH_MAJOR_CATEGORY_ID = 'srchMajorCategory';
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
	 * @param string $searchString
	 * @param int $pageNumber
	 * @param int $itemsPerPage
	 * @return array
	 */
	public function search(string $searchString, int $pageNumber = 1, int $itemsPerPage = 5000): array {
		$items = array ();
		
		if (strlen ( $searchString ) > 0) {
			
			$query = 'SELECT *, MATCH (title, description) AGAINST (:searchString) AS relevance FROM Items
						WHERE status = :status AND 
						MATCH (title, description)
						AGAINST (:searchString)
						ORDER BY relevance DESC
						LIMIT ' . ($pageNumber - 1) * $itemsPerPage . ', ' . $itemsPerPage;
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':status', 'Active' );
			$stmt->bindValue ( ':searchString', $searchString );
			$stmt->execute ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$item = new Item ( $this->db );
				$item->itemID = $row ['itemID'];
				$item->title = $row ['title'];
				$item->description = $row ['description'];
				$item->quantity = $row ['quantity'];
				$item->itemcondition = $row ['itemcondition'];
				$item->price = $row ['price'];
				$item->type = $row ['type'];
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
	 * This method uses boolean full text searches.  See http://www.vionblog.com/mysql-full-text-search-with-multiple-words/
	 * for a full explanation.
	 * + means AND
	 * � means NOT
	 * [no operator] means OR
	 * 
	 * @param array $searchArray
	 * @return array
	 */
	public function searchArray(string $searchString): array {
		
		$items = array ();
		$query = "SELECT * FROM Items
					WHERE status = :status AND 
					MATCH (title, description)
					AGAINST (:searchString IN BOOLEAN MODE)";
		
		if (strlen($searchString) > 0) {
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':status', 'Active' );
			$stmt->bindValue ( ':searchString', $searchString );
			$stmt->execute ();
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$item = new Item ( $this->db );
				$item->itemID = $row ['itemID'];
				$item->title = $row ['title'];
				$item->description = $row ['description'];
				$item->quantity = $row ['quantity'];
				$item->itemcondition = $row ['itemcondition'];
				$item->price = $row ['price'];
				$item->type = $row ['type'];
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
	 * @param int $pageNumber
	 * @param int $itemsPerPage
	 * @return array
	 * @internal param int $maxResults
	 */
	public function searchAdvanced(array $args, int $pageNumber = 1, int $itemsPerPage = 5000): array {
		$items = array();

		$query = "SELECT *";
		if (strlen($args[self::SEARCH_TEXT]) > 0) {
			$query = $query . 		", MATCH (title, description) AGAINST (:srchText IN BOOLEAN MODE) AS relevance";
		}

		$query = $query . 		" FROM Items";
		
		if($args[self::SEARCH_MINOR_CATEGORY_ID] > 0
				|| $args[self::SEARCH_MAJOR_CATEGORY_ID] > 0){
			$query = "SELECT i.itemID, i.owningUserID, i.title, i.description,
					 i.quantity, i.itemcondition, i.price, i.type,
					 i.created_at, i.updated_at";

			if (strlen($args[self::SEARCH_TEXT]) > 0) {
				$query = $query . 		", MATCH (title, description) AGAINST (:srchText IN BOOLEAN MODE) AS relevance";
			}

			$query = $query . " FROM Items i
								JOIN Category_items ci
								ON i.itemID = ci.itemID";
		}

		$query = $query . " WHERE status = :status";

		$initialLength = strlen($query);
		
		if (strlen($args[self::SEARCH_TEXT]) > 0) {
			$query = $query . " AND MATCH (title, description)
								AGAINST (:srchText IN BOOLEAN MODE)";
		}

		if ($args[self::SEARCH_MIN_PRICE] > 0) {
			$query = $query . " AND CAST(price AS DECIMAL(10,2)) >= :srchMinPrice";
		}

		if ($args[self::SEARCH_MAX_PRICE] < 0x7FFFFFFF) {
			$query = $query . " AND CAST(price AS DECIMAL(10,2)) <= :srchMaxPrice";
		}

		if($args[self::SEARCH_MIN_QUANTITY] > 1){
			$query = $query . " AND CAST(quantity AS UNSIGNED) >= :srchQuantity";
		}
		
		if (strlen($args[self::SEARCH_CONDITION]) > 0){
			$query = $query . " AND itemcondition = :srchCondition";
		}
		
		if( strlen($args[self::SEARCH_STATUS]) > 0){
			$query = $query . " AND type = :type";
		}
		
		if ($args[self::SEARCH_MINOR_CATEGORY_ID] > 0){
			$query = $query . " AND categoryID = :categoryID";
		} else if ($args[self::SEARCH_MAJOR_CATEGORY_ID] > 0){
			$query = $query . " AND categoryID IN(SELECT categoryID from Categories WHERE parentID = :categoryID)";
		}

		$query = $query . ' LIMIT ' . ($pageNumber - 1) * $itemsPerPage . ', ' . $itemsPerPage;

		$stmt = $this->db->prepare ( $query );
		$stmt->bindValue ( ':status', 'Active' );

		if(strlen($args[self::SEARCH_TEXT]) > 0){ 
			$stmt->bindValue ( ':srchText', $args[self::SEARCH_TEXT] );
		}
		if($args[self::SEARCH_MIN_PRICE] > 0) { 
			$stmt->bindValue ( ':srchMinPrice', floatval($args[self::SEARCH_MIN_PRICE]) );
		}
		if($args[self::SEARCH_MAX_PRICE] < 0x7FFFFFFF && $args[self::SEARCH_MAX_PRICE] > 0) { 
			$stmt->bindValue ( ':srchMaxPrice', floatval($args[self::SEARCH_MAX_PRICE]));
		}
		if($args[self::SEARCH_MIN_QUANTITY] > 1){ 
			$stmt->bindValue ( ':srchQuantity', floatval($args[self::SEARCH_MIN_QUANTITY]) );
		}
		if(strlen($args[self::SEARCH_CONDITION]) > 0){ 
			$stmt->bindValue ( ':srchCondition',  $args[self::SEARCH_CONDITION] );
		}
		if(strlen($args[self::SEARCH_STATUS]) > 0){ 
			$stmt->bindValue ( ':type', $args[self::SEARCH_STATUS] );
		}
		if($args[self::SEARCH_MINOR_CATEGORY_ID] > 0){ 
			$stmt->bindValue ( ':categoryID', $args[self::SEARCH_MINOR_CATEGORY_ID] );
		} elseif ($args[self::SEARCH_MAJOR_CATEGORY_ID] > 0){ 
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
			$item->type = $row ['type'];
			$item->created_at = $row ['created_at'];
			$item->updated_at = $row ['updated_at'];
			
			$items [] = $item;
		}
		
		return $items;
	}
}
?>