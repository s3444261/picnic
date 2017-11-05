<?php
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
 * @property integer $_itemID;
 * @property integer $_owningUserID ;
 * @property string $_title;
 * @property string $_description;
 * @property string $_quantity;
 * @property string $_itemcondition;
 * @property string $_price;
 * @property string $_status;
 * @property string $_created_at;
 * @property string $_updated_at;
 */
class Item {
	private $_itemID = 0;
	private $_owningUserID = 0;
	private $_title = '';
	private $_description = '';
	private $_quantity = '';
	private $_itemcondition = '';
	private $_price = '';
	private $_status = '';
	private $_created_at;
	private $_updated_at;
	private $db;
	const ERROR_ITEM_NOT_EXIST = 'Item does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	
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

	public static function getAllItemIDs($db): array {

		$query = "SELECT itemID FROM Items";

		$stmt = $db->prepare ( $query );
		$stmt->execute ();

		$items = [];
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$items [] = $row['itemID'];
		}

		return $items;
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
	 * First confirms the item object exists in the database.
	 * If it doesn't, an
	 * exception is thrown. If it does exsit, it retrieves the attributes from the database.
	 * The attributes are set and returnes+.
	 *
	 * @throws ModelException
	 * @return Item
	 */
	public function get(): Item {
		if ($this->exists ()) {
			$query = "SELECT * FROM Items WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_owningUserID = $row ['owningUserID'];
			$this->_title = $row ['title'];
			$this->_description = $row ['description'];
			$this->_quantity = $row ['quantity'];
			$this->_itemcondition = $row ['itemcondition'];
			$this->_price = $row ['price'];
			$this->_status = $row ['itemStatus'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
			return $this;
		} else {
			throw new ModelException ( self::ERROR_ITEM_NOT_EXIST );
		}
	}
	
	/**
	 * Checks that at a bare minimum the title has been completed.
	 * If so, inserts the item into the database. If not, throws an
	 * exception.
	 *
	 * @throws ModelException
	 * @return int
	 */
	public function set(): int {
		$v = new Validation ();
		try {
			$v->emptyField ( $this->title );
			$v->numberGreaterThanZero( $this->owningUserID );

			// make sure the claimed owning user exists
			$user = new User($this->db);
			$user->userID = $this->owningUserID;
			$user->get();

			$query = "INSERT INTO Items
						SET owningUserID = :owningUserID,
							title = :title,
							description = :description,
							quantity = :quantity,
							itemcondition = :itemcondition,
							price = :price,
							itemStatus = :status,
							created_at = NULL";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':owningUserID', $this->_owningUserID );
			$stmt->bindParam ( ':title', $this->_title );
			$stmt->bindParam ( ':description', $this->_description );
			$stmt->bindParam ( ':quantity', $this->_quantity );
			$stmt->bindParam ( ':itemcondition', $this->_itemcondition );
			$stmt->bindParam ( ':price', $this->_price );
			$stmt->bindParam ( ':status', $this->_status );
			$stmt->execute ();
			$this->_itemID = $this->db->lastInsertId ();
			if ($this->_itemID > 0) {
				return $this->_itemID;
			} else {
				return 0;
			}
		} catch ( ValidationException $e ) {
			throw new ModelException ( $e->getMessage () );
		}
	}
	
	/**
	 * Confirms the item exists in the database.
	 * If it does, any modified
	 * attributes are updated.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function update(): bool {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM Items WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if (strlen ( $this->_title ) < 1) {
				$this->_title = $row ['title'];
			}
			if (strlen ( $this->_description ) < 1) {
				$this->_description = $row ['description'];
			}
			if (strlen ( $this->_quantity ) < 1) {
				$this->_quantity = $row ['quantity'];
			}
			if (strlen ( $this->_itemcondition ) < 1) {
				$this->_itemcondition = $row ['itemcondition'];
			}
			if (strlen ( $this->_price ) < 1) {
				$this->_price = $row ['price'];
			}
			if (strlen ( $this->_status ) < 1) {
				$this->_status = $row ['title'];
			}
			
			$query = "UPDATE Items
						SET title = :title,
							description = :description,
							quantity = :quantity,
							itemcondition = :itemcondition,
							price = :price,
							itemStatus = :status
						WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->bindParam ( ':title', $this->_title );
			$stmt->bindParam ( ':description', $this->_description );
			$stmt->bindParam ( ':quantity', $this->_quantity );
			$stmt->bindParam ( ':itemcondition', $this->_itemcondition );
			$stmt->bindParam ( ':price', $this->_price );
			$stmt->bindParam ( ':status', $this->_status );
			$stmt->execute ();
			return true;
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Checks the item exsits in the database.
	 * If it does, it is
	 * deleted and true is returned.
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function delete(): bool {
		if ($this->exists ()) {
			
			$query = "DELETE FROM Items
						WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			if (! $this->exists ()) {
				return true;
			} else {
				return false;
			}
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Checks to see if the itemID exists in the database, if it does
	 * true is returned.
	 *
	 * @return bool
	 */
	public function exists(): bool {
		if ($this->_itemID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM Items WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
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


	/**
	 * Removes all matches for the current item.
	 */
	public function removeAllMatches(): void {
		if ($this->_itemID > 0) {

			// remove matches in both directions.
			$query = "DELETE FROM Item_matches WHERE baseItemID = :itemID OR matchingItemID = :itemID";
			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->execute ();
		}
	}

	/**
	 * Adds a new match for the current item.
	 *
	 * @param int $matchingItemID	The item ID for the matched item.
	 */
	public function addMatch(int $matchingItemID): void {
		if ($this->_itemID > 0) {

			// add matches in both directions.
			$query1 = "REPLACE INTO Item_matches (baseItemID, matchingItemID, status) VALUES (:baseItemID, :matchingItemID, :status)";
			$stmt1 = $this->db->prepare ( $query1 );
			$stmt1->bindValue ( ':baseItemID', $this->_itemID );
			$stmt1->bindValue ( ':matchingItemID', $matchingItemID );
			$stmt1->bindValue ( ':status', 'none' );
			$stmt1->execute ();

			$query2 = "REPLACE INTO Item_matches (baseItemID, matchingItemID, status) VALUES (:matchingItemID, :baseItemID, :status)";
			$stmt2 = $this->db->prepare ( $query2 );
			$stmt2->bindValue ( ':baseItemID', $this->_itemID );
			$stmt2->bindValue ( ':matchingItemID', $matchingItemID );
			$stmt1->bindValue ( ':status', 'none' );
			$stmt2->execute ();
		}
	}

	/**
	 * Returns the IDs of all items that were matched with this item.
	 *
	 * @return array		The matched item IDs.
	 */
	public function getMatches(): array {
		$items = array();

		if ($this->_itemID > 0) {
			$query = "SELECT * FROM Item_matches WHERE baseItemID = :itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindValue ( ':itemID', $this->_itemID );
			$stmt->execute ();

			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$items [] = $row['matchingItemID'];
			}
		}

		return $items;
	}

	/**
	 * Display Object Contents
	 */
	public function printf() {
		echo '<br /><strong>Item Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_owningUserID) {
			echo 'owningUserID => ' . $this->_owningUserID . '<br/>';
		}
		if ($this->_title) {
			echo 'title => ' . $this->_title . '<br/>';
		}
		if ($this->_description) {
			echo 'description => ' . $this->_description . '<br/>';
		}
		if ($this->_quantity) {
			echo 'quantity => ' . $this->_quantity . '<br/>';
		}
		if ($this->_itemcondition) {
			echo 'itemcondition => ' . $this->_itemcondition . '<br/>';
		}
		if ($this->_price) {
			echo 'price => ' . $this->_price . '<br/>';
		}
		if ($this->_status) {
			echo 'status => ' . $this->_status . '<br/>';
		}
	}
}
?>