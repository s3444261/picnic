<?php

/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class Categories
{
    const ROOT_CATEGORY = 0;
    const ERROR_PARENT_ID_NOT_EXIST = 'The parentID does not exist!';

    private $_db;

    /**
     * Constructor.
     *
     * @param PDO $pdo The database to be used.
     */
    function __construct(PDO $pdo)
    {
        $this->_db = $pdo;
    }

    /**
     * Retrieves all categories and returns them as an array
     * of category objects.
     *
     * @return array    An array of Category, ordered by name.
     */
    public function getCategories(): array
    {
        $query = "SELECT * FROM Categories ORDER BY category";

        $stmt = $this->_db->prepare($query);
        $stmt->execute();

        $objects = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objects[] = $this->createCategoryFromRow($row);
        }

        return $objects;
    }

    /**
     * Retrieves all categories for the given parent category and
     * returns them as an array of category objects.
     *
     * @param int $parentID      The ID of the parent category.
     *
     * @return array            An array of Category, ordered by name.
     *
     * @throws ModelException
     */
    public function getCategoriesIn(int $parentID): array
    {
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

        $objects = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objects[] = $this->createCategoryFromRow($row);
        }

        return $objects;
    }

    /**
     * Creates a new Category object from the given database row.
     *
     * @param PDO $pdo      The database
     * @param array $row    The row from which to read.
     * @return Category     The new object.
     */
    private static function createCategoryFromRow(PDO $pdo, array $row): Category {
        $c = new Category($pdo);
        $c->categoryID = $row ['categoryID'];
        $c->parentID = $row ['parentID'];
        $c->category = $row ['category'];
        $c->created_at = $row ['created_at'];
        $c->updated_at = $row ['updated_at'];
        return $c;
    }
}
