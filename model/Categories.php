<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class Categories {

    private $_db;

    const ROOT_CATEGORY = 0;
    const ERROR_PARENT_ID_NOT_EXIST = 'The parentID does not exist!';
    
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
    
    public function &__get($name) {
        $name = '_' . $name;
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $name = '_' . $name;
        $this->$name = $value;
    }
    
    /**
     * Retrieves all categories and returns them as an array
     * of category objects.
     * 
     * @return array
     */
    public function getCategories(): array {
        
        $query = "SELECT * FROM Categories";

        $stmt = $this->_db->prepare ( $query );
        $stmt->execute ();
        $objects = [];
        while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
            
            $object = new Category($this->_db);
            $object->categoryID = $row ['categoryID'];
            $object->parentID = $row ['parentID'];
            $object->category = $row ['category'];
            $object->created_at = $row ['created_at'];
            $object->updated_at = $row ['updated_at'];
            
            $objects[] = $object;
        } 
        return $objects;
    }

    /**
     * Retrieves all categories for the given parent category and
     * returns them as an array of category objects.
     *
     * @param int $parentID The ID of the parent category.
     * @return array
     * @throws ModelException
     */
    public function getCategoriesIn(int $parentID): array {
        $v = new Validation();
        $objects = [];
        
        try {
            $v->emptyField($parentID);
            $v->number($parentID);

            // If we get passed ROOT_CATEGORY, it means we want categories that do not
            // have a parent. The following check doesn't apply in that case, so we only
            // do it if we have an actual parent ID.
            if ($parentID != self::ROOT_CATEGORY) {
                $c = new Category($this->_db);
                $c->categoryID = $parentID;

                if (!$c->exists()) {
                    throw new ModelException(self::ERROR_PARENT_ID_NOT_EXIST);
                }
            }

            if ($parentID == self::ROOT_CATEGORY) {
                // The database uses NULL to indicate no parent, so we have a special
                // case to handle here.
                $query = "SELECT * FROM Categories WHERE parentID IS NULL ORDER BY category";
                $stmt = $this->_db->prepare($query);
            } else {
                $query = "SELECT * FROM Categories WHERE parentID = :parentID ORDER BY category";
                $stmt = $this->_db->prepare($query);
                $stmt->bindValue(':parentID', $parentID);
            }

            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $object = new Category($this->_db);
                $object->categoryID = $row ['categoryID'];
                $object->parentID = $row ['parentID'];
                $object->category = $row ['category'];
                $object->created_at = $row ['created_at'];
                $object->updated_at = $row ['updated_at'];

                $objects[] = $object;
            }
        } catch (ValidationException $e) {
            throw new ModelException($e->getMessage());
        }
        return $objects;
    }
}
