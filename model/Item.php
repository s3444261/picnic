<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
require_once dirname ( __FILE__ ) . '/ModelException.php';

/**
 *
 * @property integer itemID;
 * @property string title
 * @property string description
 * @property int quantity
 * @property string itemcondition
 * @property string price
 * @property string type
 * @property string created_at
 * @property string updated_at
 * @property int owningUserID
 * @property string status
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
    private $_type = '';
    private $_created_at;
    private $_updated_at;
    private $_db;
    const ERROR_ITEM_NOT_EXIST = 'Item does not exist!';
    const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';

    // Constructor
    function __construct(PDO $pdo, $args = []) {
        $this->_db = $pdo;

        foreach ( $args as $key => $val ) {
            $name = '_' . $key;
            if (isset ( $this->{$name} )) {
                $this->{$name} = $val;
            }
        }
    }

    public static function getAllItemIDs(PDO $db): array {

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
     * exception is thrown. If it does exist, it retrieves the attributes from the database.
     * The attributes are set and returned.
     *
     * @throws ModelException
     * @return Item
     */
    public function get(): Item {
        if ($this->exists ()) {
            $query = "SELECT * FROM Items WHERE itemID = :itemID";
            
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':itemID', $this->_itemID );
            $stmt->execute ();
            $row = $stmt->fetch ( PDO::FETCH_ASSOC );
            $this->_owningUserID = $row ['owningUserID'];
            $this->_title = $row ['title'];
            $this->_description = $row ['description'];
            $this->_quantity = $row ['quantity'];
            $this->_itemcondition = $row ['itemcondition'];
            $this->_price = $row ['price'];
            $this->_type = $row ['type'];
            $this->_status = $row ['status'];
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
            $v->emptyField ( $this->_title );
            $v->numberGreaterThanZero( $this->_owningUserID );

            // make sure the claimed owning user exists
            $user = new User($this->_db);
            $user->userID = $this->_owningUserID;
            $user->get();

            $query = "INSERT INTO Items
                        SET owningUserID = :owningUserID,
                            title = :title,
                            description = :description,
                            quantity = :quantity,
                            itemcondition = :itemcondition,
                            price = :price,
                            type = :type,
                            created_at = NULL";
            
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':owningUserID', $this->_owningUserID );
            $stmt->bindValue ( ':title', $this->_title );
            $stmt->bindValue ( ':description', $this->_description );
            $stmt->bindValue ( ':quantity', $this->_quantity );
            $stmt->bindValue ( ':itemcondition', $this->_itemcondition );
            $stmt->bindValue ( ':price', $this->_price );
            $stmt->bindValue ( ':type', $this->_type );
            $stmt->execute ();
            $this->_itemID = $this->_db->lastInsertId ();
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
            
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':itemID', $this->_itemID );
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
            if (strlen ( $this->_type ) < 1) {
                $this->_type = $row ['type'];
            }
            
            $query = "UPDATE Items
                        SET title = :title,
                            description = :description,
                            quantity = :quantity,
                            itemcondition = :itemcondition,
                            price = :price,
                            type = :type
                        WHERE itemID = :itemID";
            
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':itemID', $this->_itemID );
            $stmt->bindValue ( ':title', $this->_title );
            $stmt->bindValue ( ':description', $this->_description );
            $stmt->bindValue ( ':quantity', $this->_quantity );
            $stmt->bindValue ( ':itemcondition', $this->_itemcondition );
            $stmt->bindValue ( ':price', $this->_price );
            $stmt->bindValue ( ':type', $this->_type );
            $stmt->execute ();
            return true;
        } else {
            throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
        }
    }
    
    /**
     * Checks the item exists in the database.
     * If it does, it is
     * deleted and true is returned.
     *
     * @throws ModelException
     * @return bool
     */
    public function delete(): bool {
        if ($this->exists ()) {
            
            $query = "UPDATE Items 
                      SET status = :status
                      WHERE itemID = :itemID";
            
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':status', 'Deleted' );
            $stmt->bindValue ( ':itemID', $this->_itemID );
            $stmt->execute ();
            return ($stmt->rowCount() == 1);
        }

        throw new ModelException ( self::ERROR_ITEM_ID_NOT_EXIST );
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
            
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':itemID', $this->_itemID );
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
            $query = "DELETE FROM Item_matches WHERE lhsItemID = :itemID OR rhsItemID = :itemID";
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':itemID', $this->_itemID );
            $stmt->execute ();
        }
    }

    public function discardMatchWith(int $itemID) {
        $this->setStatusForMatchWith($itemID, 'Rejected');
    }

    public function acceptMatchWith(int $itemID) {
        $this->setStatusForMatchWith($itemID, 'Accepted');
    }

    private function setStatusForMatchWith(int $itemID, string $status) {
        if ($this->_itemID > 0 && $itemID > 0) {
            {
                $lower = min($this->_itemID , $itemID);
                $upper = max($this->_itemID , $itemID);

                if ($lower === $itemID) {
                    $query = "UPDATE Item_matches
                              SET rhsStatus = :status 
                                WHERE lhsItemID = :lowerItemID 
                                AND rhsItemID = :upperItemID
                                AND rhsStatus = 'none'";
                } else {
                    $query = "UPDATE Item_matches 
                              SET lhsStatus = :status 
                               WHERE lhsItemID = :lowerItemID 
                               AND rhsItemID = :upperItemID
                               AND lhsStatus = 'none'";
                }

                $stmt = $this->_db->prepare($query);
                $stmt->bindValue(':status', $status);
                $stmt->bindValue(':lowerItemID', $lower);
                $stmt->bindValue(':upperItemID', $upper);
                $stmt->execute();
            }
        }
    }

    /**
     * Adds a new match for the current item.
     *
     * @param int $itemID    The item ID for the matched item.
     */
    public function addMatchWith(int $itemID): void {
        if ($this->_itemID > 0
            && $this->_itemID != $itemID
            && !$this->isMatchedWith($itemID)) {

            $lower = min($this->_itemID , $itemID);
            $upper = max($this->_itemID , $itemID);

            $query = "INSERT INTO Item_matches (lhsItemID, rhsItemID) VALUES (:lhsItemID, :rhsItemID)";
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':lhsItemID', $lower );
            $stmt->bindValue ( ':rhsItemID', $upper );
            $stmt->execute ();
        }
    }

    public function isMatchedWith(int $itemID): bool {
        if ($this->_itemID > 0) {

            $lower = min($this->_itemID , $itemID);
            $upper = max($this->_itemID , $itemID);

            $query = "SELECT COUNT(*) as num
                      FROM Item_matches 
                      WHERE (lhsItemID = :lowerItemID) AND (rhsItemID = :upperItemID)";
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':lowerItemID', $lower );
            $stmt->bindValue ( ':upperItemID', $upper );
            $stmt->execute ();

            while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
                return ($row['num'] > 0);
            }
        }

        return false;
    }

    public function isFullyAcceptedMatchWith(int $itemID): bool {
        if ($this->_itemID > 0) {

            $lower = min($this->_itemID , $itemID);
            $upper = max($this->_itemID , $itemID);

            $query = "SELECT COUNT(*) as num FROM Item_matches 
                      WHERE (lhsItemID = :lowerItemID) 
                      AND (rhsItemID = :upperItemID)
                      AND lhsStatus = 'accepted'
                      AND rhsStatus = 'accepted'";
            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':lowerItemID', $lower );
            $stmt->bindValue ( ':upperItemID', $upper );
            $stmt->execute ();

            while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
                return ($row['num'] > 0);
            }
        }

        return false;
    }

    public static function getFullyMatchedItemId(PDO $db, int $itemID): int {
        $query = "SELECT * FROM Item_matches
                  WHERE (lhsItemID = :itemID OR rhsItemID = :itemID)
                  AND lhsStatus = 'Accepted' AND  rhsStatus = 'Accepted'";

        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':itemID', $itemID );
        $stmt->execute ();

        if ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {

            if ($row['lhsItemID'] == $itemID) {
                return $row['rhsItemID'];
            } else {
                return $row['lhsItemID'];
            }
        }

        throw new ModelException('NOo completed match found for item ID ' . $itemID);
    }

    public static function createRating(PDO $db, int $sourceItemID, int $targetItemID): string {
        $query = "INSERT INTO User_ratings
                         SET sourceItemID = :sourceItemID,
                            targetItemID = :targetItemID,
                            accessCode = :accessCode";

        $accessCode = Item::ratingAccessCode($sourceItemID, $targetItemID);
        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':sourceItemID', $sourceItemID );
        $stmt->bindValue ( ':targetItemID', $targetItemID );
        $stmt->bindValue ( ':accessCode',  $accessCode );
        $stmt->execute ();

        return $accessCode;
    }

    public function markCompleted() {
        $query = "UPDATE Items
                  SET status = :status
                  WHERE itemID = :itemID";

        $stmt = $this->_db->prepare ( $query );
        $stmt->bindValue ( ':status', 'Completed' );
        $stmt->bindValue ( ':itemID', $this->_itemID );
        $stmt->execute ();
    }

    private static function ratingAccessCode(int $itemID1, int $itemID2): string {
        date_default_timezone_set ( 'UTC' );
        return md5 ( strtotime ( "now" ) . $itemID1 . $itemID2 );
    }

    /**
     * Returns the IDs of all items that were matched with this item.
     *
     * @return array        The matched item IDs.
     */
    public function getMatches(): array {
        $items = [];

        if ($this->_itemID > 0) {
            $query = "SELECT DISTINCT * FROM Item_matches 
                      WHERE (lhsItemID = :itemID AND lhsStatus != 'Rejected')
                      OR (rhsItemID = :itemID AND rhsStatus != 'Rejected')";

            $stmt = $this->_db->prepare ( $query );
            $stmt->bindValue ( ':itemID', $this->_itemID );
            $stmt->execute ();

            while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
                $item = [];

                if ($row['rhsItemID'] == $this->_itemID) {
                    $item ['myItemID'] = $row['rhsItemID'];
                    $item ['otherItemID'] = $row['lhsItemID'];
                    $item ['myStatus'] = $row['rhsStatus'];
                    $item ['otherStatus'] = $row['lhsStatus'];
                } else {
                    $item ['myItemID'] = $row['lhsItemID'];
                    $item ['otherItemID'] = $row['rhsItemID'];
                    $item ['myStatus'] = $row['lhsStatus'];
                    $item ['otherStatus'] = $row['rhsStatus'];
                }

                $items[] = $item;
            }
        }

        return $items;
    }
}
