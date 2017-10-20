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
 * @property integer $_user_ratingID;
 * @property integer $_itemID;
 * @property integer $_sellrating;
 * @property integer $_userID;
 * @property integer $_buyrating;
 * @property string $_transaction;
 * @property string $_created_at;
 * @property string $_updated_at;
 */
class UserRatings {
	private $_user_ratingID = 0;
	private $_itemID = 0;
	private $_sellrating = 0;
	private $_userID = 0;  
	private $_buyrating = 0;
	private $_transaction = '';
	private $_created_at;
	private $_updated_at;
	private $db;
	
	/*
	 * The sellrating is the rating of a buyer made by the seller.
	 * 
	 * The userID listed above is the userID of the buyer.
	 * The userID of the seller is already tied to the item through the
	 * UserItems Table.
	 * 
	 * The buyrating is the rating of a seller made by the buyer.
	 * 
	 * The transaction string is an md5 hash used to identify the transaction.
	 * It will be received through a URL.
	 */
	
	const ERROR_USER_RATING_ID_NOT_EXIST = 'The UserRatingID does not exist!';
	const ERROR_USER_ID_NOT_EXIST = 'The UserID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_RATING_NOT_SET = 'The rating has not been set!';
	const ERROR_INCORRECT_TRANSACTION_ID = 'The TransactionID is incorrect!';

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
	 * First the existence of the UserRatings object is confirmed.  Attributes of the
	 * rating are then retrieved from the database.
	 * 
	 * @throws ModelException
	 * @return UserRatings
	 */
	public function get(): UserRatings {
		if ($this->exists ()) {
			$query = "SELECT * FROM User_ratings WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_user_ratingID = $row ['user_ratingID'];
			$this->_itemID = $row ['itemID'];
			$this->_sellrating = $row ['sellrating'];
			$this->_userID = $row ['userID'];
			$this->_buyrating = $row ['buyrating'];
			$this->_transaction = $row ['transaction'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
			return $this;
		} else {
			throw new ModelException ( self::ERROR_USER_RATING_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Inserts attributes into the database.
	 * 
	 * @return int
	 */
	public function set(): int {
		$query = "INSERT INTO User_ratings
					SET itemID = :itemID,
						sellrating = :sellrating,
						userID = :userID,
						buyrating = :buyrating,
						transaction = :transaction,
						created_at = NULL";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':itemID', $this->_itemID );
		$stmt->bindParam ( ':sellrating', $this->_sellrating );
		$stmt->bindParam ( ':userID', $this->_userID );
		$stmt->bindParam ( ':buyrating', $this->_buyrating );
		$transactionCode = $this->transactionCode();
		$stmt->bindParam ( ':transaction', $transactionCode);
		$stmt->execute ();
		$this->_user_ratingID = $this->db->lastInsertId ();
		if ($this->_user_ratingID > 0) {
			return $this->_user_ratingID;
		} else {
			return 0;
		}
	}
	
	/**
	 * Updates any attributes that have been altered.
	 * 
	 * @throws ModelException
	 * @return bool
	 */
	public function update(): bool {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM User_ratings WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			if ($this->_itemID < 1) {
				$this->_itemID = $row ['itemID'];
			}
			if ($this->_sellrating < 1) {
				$this->_sellrating = $row ['sellrating'];
			}
			if ($this->_userID < 1) {
				$this->_userID = $row ['userID'];
			}
			if ($this->_buyrating < 1) {
				$this->_buyrating = $row ['buyrating'];
			}
			if (strlen ( $this->_transaction ) != 32) {
				$this->_transaction = $row ['transaction'];
			}
			
			$query = "UPDATE User_ratings
						SET itemID = :itemID,
							sellrating = :sellrating,
							userID = :userID,
							buyrating = :buyrating,
							transaction = :transaction
						WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->bindParam ( ':sellrating', $this->_sellrating );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->bindParam ( ':buyrating', $this->_buyrating );
			$stmt->bindParam ( ':transaction', $this->_transaction );
			$stmt->execute ();
			return true;
		} else {
			throw new ModelException ( self::ERROR_USER_RATING_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Deletes UserRating based on user_ratingID.
	 * 
	 * @throws ModelException
	 * @return boolean
	 */
	public function delete(): bool {
		if ($this->exists ()) {
			
			$query = "DELETE FROM User_ratings
						WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
			$stmt->execute ();
			if (! $this->exists ()) {
				return true;
			} else {
				return false;
			}
		} else {
			throw new ModelException ( self::ERROR_USER_RATING_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Deletes a UserRating based on itemID
	 * 
	 * @throws ModelException
	 * @return bool
	 */
	public function deleteItemId(): bool {
		$i = new Item($this->db);
		$i->itemID = $this->_itemID;
		if ($i->exists ()) {
			
			$query = "DELETE FROM User_ratings
						WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			if($stmt->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		} else {
			throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Deletes a UserRating based on userID
	 *
	 * @throws ModelException
	 * @return bool
	 */
	public function deleteUserId(): bool {
		$u = new User($this->db);
		$u->userID = $this->_userID;
		if ($u->exists ()) {
			
			$query = "DELETE FROM User_ratings
						WHERE userID = :userID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->execute ();
			if($stmt->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		} else {
			throw new ModelException ( self::ERROR_USER_ID_NOT_EXIST );
		}
	}
	
	/**
	 * Confirms if UserRating exists.
	 * 
	 * @return bool
	 */
	public function exists(): bool {
		if ($this->_user_ratingID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM User_ratings WHERE user_ratingID = :user_ratingID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_ratingID', $this->_user_ratingID );
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
	 * Adds a seller rating for a buyer.
	 * 
	 * @throws ModelException
	 * @return UserRatings
	 */
	public function addSellerRating(): UserRatings {
		$u = new User($this->db);
		$u->userID = $this->_userID;
		try {
			if($u->exists()){ 
				$i = new Item($this->db);
				$i->itemID = $this->_itemID;
				if($i->exists()){ 
					if($this->_sellrating > 0 && $this->_sellrating < 6){
						$this->_user_ratingID = $this->set();
						try {
							return $this->get();
						} catch (ModelException $e) {
							throw new ModelException ( $e->getMessage() );
						}
					} else {
						throw new ModelException(self::ERROR_RATING_NOT_SET);
					}
				} else { 
					throw new ModelException(self::ERROR_ITEM_ID_NOT_EXIST);
				}
			} else { 
				throw new ModelException(self::ERROR_USER_ID_NOT_EXIST);
			}
		} catch (ModelException $e) { 
			throw new ModelException ( $e->getMessage() );
		}
	}
	
	public function addBuyerRating(): bool {
		if(strlen($this->_transaction) <= 32 && strlen($this->_transaction) > 0){
			if($this->_buyrating > 0  && $this->_buyrating < 6){
				
				$query = "UPDATE User_ratings
							SET buyrating = :buyrating,
								transaction = NULL
							WHERE transaction = :transaction";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':buyrating', $this->_buyrating );
				$stmt->bindParam ( ':transaction', $this->_transaction );
				$stmt->execute ();
				if($stmt->rowCount() > 0){
					return true;
				} else {
					throw new ModelException(self::ERROR_INCORRECT_TRANSACTION_ID);
				}
			} else {
				throw new ModelException(self::ERROR_RATING_NOT_SET);
			}
		} else {
			throw new ModelException(self::ERROR_INCORRECT_TRANSACTION_ID);
		}
	}
	
	public function getStats(User $user): array {
		try {
			if($user->exists()){ 
				
				$query = "SELECT COUNT(sellrating) AS numSellRatings,
								ROUND(AVG(sellrating),1) AS avgSellRating
							FROM User_items ui
							JOIN User_ratings ur
							ON ui.itemID = ur.itemID
							WHERE ui.userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $user->userID );
				$stmt->execute ();
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				
				$stats = array();
				$stats['numSellRatings'] = $row['numSellRatings'];
				$stats['avgSellRating'] = $row['avgSellRating'];
				
				$query = "SELECT COUNT(buyrating) AS numBuyRatings,
								ROUND(AVG(sellrating),1) AS avgBuyRating
							FROM User_ratings
							WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $user->userID);
				$stmt->execute ();
				$row = $stmt->fetch ( PDO::FETCH_ASSOC );
				
				$stats['numBuyRatings'] = $row['numBuyRatings'];
				$stats['avgBuyRating'] = $row['avgBuyRating'];
				
				$stats['totalNumRatings'] = $stats['numSellRatings'] + $stats['numBuyRatings'];
				
				if($stats['totalNumRatings'] == 0){
					$stats['avgRating'] = 0;
				} else {
					$stats['avgRating'] = round((($stats['numSellRatings']*$stats['avgSellRating']) +
							($stats['numBuyRatings']*$stats['avgBuyRating']))/
							($stats['numSellRatings'] + $stats['numBuyRatings']),1);
				}
														
				return $stats;
				
			} else {
				throw new ModelException(self::ERROR_USER_ID_NOT_EXIST);
			}
		} catch (ModelException $e) {
			throw new ModelException ( $e->getMessage() );
		}
	}
	
	/*
	 * Generate a random transaction code.
	 */
	private function transactionCode(): string {
		date_default_timezone_set ( 'UTC' );
		return md5 ( strtotime ( "now" ) . $this->_itemID . $this->_sellrating );
	}
	
	// Display Object Contents
	public function printf() {
		echo '<br /><strong>UserRatings Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_itemID) {
			echo 'itemID => ' . $this->_itemID . '<br/>';
		}
		if ($this->_sellrating) {
			echo 'sellrating => ' . $this->_sellrating . '<br/>';
		}
		if ($this->_userID) {
			echo 'userID => ' . $this->_userID . '<br/>';
		}
		if ($this->_buyrating) {
			echo 'buyrating => ' . $this->_buyrating . '<br/>';
		}
		if ($this->_transaction) {
			echo 'transaction => ' . $this->_transaction . '<br/>';
		}
	}
}
?>