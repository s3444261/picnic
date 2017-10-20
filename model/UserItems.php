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
 * @property integer $_user_itemID;
 * @property integer $_userID;
 * @property integer $_itemID;
 * @property string $_relationship;
 * @property string $_userStatus;
 * @property string $_created_at;
 * @property string $_updated_at;
 */

class UserItems {
	private $_user_itemID= 0;
	private $_userID= 0;
	private $_itemID= 0;
	private $_relationship = '';
	private $_userStatus = '';
	private $_created_at;
	private $_updated_at;
	private $db;
	
	const ERROR_USER_ITEM_ID_NOT_EXIST = 'The UserItemID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_USER_ID_NOT_EXIST = 'The UserID does not exist!';
	const ERROR_ITEM_ID_ALREADY_EXIST = 'The ItemID is already in User_items!';
	const ERROR_USER_ITEM_NOT_DELETED = 'The UserItem was not deleted!';

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
	 * Retrieves a UserItem for a user_itemID.
	 * 
	 * @throws ModelException
	 * @return UserItems
	 */
	public function get(): UserItems {
		if ($this->exists ()) {
			$query = "SELECT * FROM User_items WHERE user_itemID = :user_itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_userID = $row ['userID'];
			$this->_itemID = $row ['itemID'];
			$this->_relationship = $row ['relationship'];
			$this->_userStatus = $row ['userStatus'];
			$this->_created_at = $row ['created_at'];
			$this->_updated_at = $row ['updated_at'];
			return $this;
		} else {
			throw new ModelException ( self::ERROR_USER_ITEM_ID_NOT_EXIST);
		}
	}
	
	/*
	 * The set() function first checks to see if the object parameters already
	 * exist.  If they do the objectID is retrieved and returned.  If they 
	 * don't, they are insserted into the table and the objectID is
	 * retrieved and returned.
	 */
	public function set(): int {
		$i = new Item($this->db);
		$i->itemID = $this->_itemID;
		$u = new User($this->db);
		$u->userID = $this->_userID;
		
		if($i->exists()){
			if($u->exists()){
				if(!$this->existsItemID()){
					
					$query = "INSERT INTO User_items
								SET userID = :userID,
								itemID = :itemID,
								relationship = :relationship,
								userStatus = :userStatus";
					
					$stmt = $this->db->prepare ( $query );
					$stmt->bindParam ( ':userID', $this->_userID );
					$stmt->bindParam ( ':itemID', $this->_itemID );
					$stmt->bindParam ( ':relationship', $this->_relationship );
					$stmt->bindParam ( ':userStatus', $this->_userStatus );
					$stmt->execute ();
					$this->_user_itemID = $this->db->lastInsertId ();
					if ($this->_user_itemID > 0) {
						return $this->_user_itemID;
					} else {
						return 0;
					}
				} else {
					throw new ModelException(self::ERROR_ITEM_ID_ALREADY_EXIST);
				}
			} else {
				throw new ModelException(self::ERROR_USER_ID_NOT_EXIST);
			}
		} else {
			throw new ModelException(self::ERROR_ITEM_ID_NOT_EXIST);
		}
	}
	
	/**
	 * Updates UserItems.
	 * 
	 * @return bool
	 */
	public function update(): bool {
		if ($this->exists ()) {
			
			$query = "SELECT * FROM User_items WHERE user_itemID = :user_itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			
			$u = new User($this->db);
			$u->userID = $this->_userID;
			$i = new Item($this->db);
			$i->itemID = $this->_itemID;
			
			if (!$u->exists()) {
				$this->_userID = $row ['userID'];
			}
			if (!$i->exists()) {
				$this->_itemID = $row ['itemID'];
			} elseif($this->existsItemID()){
				$this->_itemID = $row ['itemID'];
			}
			if (strlen ( $this->_relationship ) < 1) {
				$this->_relationship = $row ['relationship'];
			}
			if (strlen ( $this->_userStatus ) < 1) {
				$this->_userStatus = $row ['userStatus'];
			}
			
			$query = "UPDATE User_items
						SET userID = :userID,
							itemID = :itemID,
							relationship = :relationship,
							userStatus = :userStatus
						WHERE user_itemID = :user_itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->bindParam ( ':relationship', $this->_relationship );
			$stmt->bindParam ( ':userStatus', $this->_userStatus );
			$stmt->execute ();
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Deletes the UserItem from the database.
	 * 
	 * @return boolean
	 */
	public function delete(): bool {
		$v = new Validation ();
		
		try {
			$v->emptyField($this->_user_itemID);
			
			if ($this->exists ()) {
				$query = "DELETE FROM User_items
						WHERE user_itemID = :user_itemID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
				$stmt->execute ();
				if (! $this->exists ()) {
					return true;
				} else {
					return false;
				}
			} else {
				throw new ModelException(self::ERROR_USER_ITEM_ID_NOT_EXIST);
			}
		} catch (ValidationException $e) {
			throw new ModelException($e->getMessage());
		}
	}
	
	/**
	 * Confirms the existence of the UserItem in the database.
	 * 
	 * @return bool
	 */
	public function exists(): bool {
		if ($this->_user_itemID > 0) {
			$query = "SELECT COUNT(*) AS numRows FROM User_items WHERE user_itemID = :user_itemID";

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':user_itemID', $this->_user_itemID );
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
	 * Confirms the existence of itemID in UserItems.
	 *
	 * @return bool
	 */
	public function existsItemID(): bool {
		$i = new Item($this->db);
		$i->itemID = $this->_itemID;
		if($i->exists()){
			$query = "SELECT COUNT(*) AS numRows FROM User_items WHERE itemID = :itemID";
			
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
	 * Confirms the existence of userID in UserItems.
	 *
	 * @return bool
	 */
	public function existsUserID(): bool {
		$u = new User($this->db);
		$u->userID = $this->_userID;
		try {
			if($u->exists()){ 
				$query = "SELECT COUNT(*) AS numRows FROM User_items WHERE userID = :userID";
				
				$stmt = $this->db->prepare ( $query );
				$stmt->bindParam ( ':userID', $this->_userID );
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
		} catch (ModelException $e) {
			throw new ModelException($e->getMessage());
		}
		
	}
	
	/**
	 * Counst the number of items for a user.
	 * 
	 * @return int
	 */
	public function count(): int {
		$query = "SELECT COUNT(*) as num
							FROM User_items
							WHERE userID = :userID";

		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':userID', $this->_userID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['num'];
	}

	/**
	 * Retrieves all itemID's for a user and returns them as an
	 * array of userItem Objects.
	 *
	 * @param string $userRole
	 * 				The role that the user plays for the requested items.
	 * @return array
	 * 				An array of UserItems objects.
	 * @throws ModelException
	 */
	public function getUserItems(string $userRole = ""): array {
		
		$objects = array();
		$i = new User($this->db);
		$i->userID = $this->_userID;
		
		if($i->exists()){
			if ($userRole == "") {
				$query = "SELECT * FROM User_items WHERE userID = :userID";
			} else {
				$query = "SELECT * FROM User_items 
				      WHERE userID = :userID 
					  AND relationship = :userRole";
			}

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );

			if ($userRole != "") {
				$stmt->bindParam ( ':userRole', $userRole );
			}

			$stmt->execute ();
			while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
				$userItem = new UserItems($this->db);
				$userItem->user_itemID = $row ['user_itemID'];
				$userItem->userID = $row ['userID'];
				$userItem->itemID = $row ['itemID'];
				$userItem->relationship = $row ['relationship'];
				$userItem->userStatus = $row ['userStatus'];
				
				$objects[] = $userItem;
			}
		} else {
			throw new ModelException(self::ERROR_USER_ID_NOT_EXIST);
		}
		return $objects;
	}
	
	/**
	 * Retrieves an UserID for an item and returns it as a userItem
	 * Object.
	 *
	 * @return UserItems
	 */
	public function getUserItem(): UserItems {
		$i = new Item($this->db);
		$i->itemID = $this->_itemID;
		
		if($i->exists()){
			$query = "SELECT * FROM User_items WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			$row = $stmt->fetch ( PDO::FETCH_ASSOC );
			$this->_user_itemID = $row ['user_itemID'];
			$this->_userID = $row ['userID'];
			$this->_relationship = $row ['relationship'];
			$this->_userStatus = $row ['userStatus'];
		} else {
			throw new ModelException(self::ERROR_ITEM_ID_NOT_EXIST);
		}
		return $this;
	}
	
	/**
	 * Deletes an UserItem based on a itemID.
	 *
	 * @return boolean
	 */
	public function deleteUserItem(): bool {
		
		$c = new Item($this->db);
		$c->itemID = $this->_itemID;
		
		if($c->exists()){
			
			$query = "DELETE FROM User_items
					WHERE itemID = :itemID";
			
			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':itemID', $this->_itemID );
			$stmt->execute ();
			if($stmt->rowCount() > 0){
				if($c->delete()){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			throw new ModelException(self::ERROR_ITEM_ID_NOT_EXIST);
		}
	}
	
	/**
	 * Deletes an UserItem and all associated items based on an userID.
	 *
	 * @return boolean
	 */
	public function deleteUserItems(): bool {
		
		if($this->existsUserID()){
			
			$userItems = $this->getUserItems();
			
			foreach($userItems as $userItem){
				$item = new Item($this->db);
				$item->itemID = $userItem->itemID;
				if($userItem->delete()){
					try {
						$item->delete();
					} catch (ModelException $e) {
						throw new ModelException($e->getMessage());
					}
				} else {
					throw new ModelException(self::ERROR_USER_ITEM_NOT_DELETED);
				}
			}
			return true;
		} else {
			throw new ModelException(self::ERROR_USER_ID_NOT_EXIST);
		}
	}
	
	// Display Object Contents
	public function printf(): string {
		echo '<br /><strong>UserItem Object:</strong><br />';
		
		if ($this->_id) {
			echo 'id => ' . $this->_id . '<br/>';
		}
		if ($this->_userID) {
			echo 'userID => ' . $this->_userID . '<br/>';
		}
		if ($this->_itemID) {
			echo 'itemID => ' . $this->_itemID . '<br/>';
		}
		if ($this->_relationship) {
			echo 'relationship => ' . $this->_relationship . '<br/>';
		}
		if ($this->_userStatus) {
			echo 'userStatus => ' . $this->_userStatus . '<br/>';
		}
	}
}
?>