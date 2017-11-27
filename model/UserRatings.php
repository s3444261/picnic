<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

/**
 * Class UserRatings
 *
 * Provides methods for manipulating user ratings.
 */
class UserRatings {

    /**
     * Gets a value indicating whether any ratings have been left for the given user.
     *
     * @param PDO $db                The database to be queried.
     * @param int $userID            The ID of the user to be queried.
     * @return bool                    True if any ratings have been left, false otherwise.
     */
    public static function getUserHasRating(PDO $db, int $userID): bool {
        return (UserRatings::getUserRatingCount($db, $userID) > 0);
    }

    /**
     * Gets the number of ratings that have been left for the requested user.
     *
     * @param PDO $db                The database to be queried.
     * @param int $userID            The ID of the user to be queried.
     * @return int                    The number of ratings that have been left.
     */
    public static function getUserRatingCount(PDO $db, int $userID): int {
        $query = "SELECT COUNT(*) as numRows 
                  FROM User_ratings AS r 
                  JOIN Items AS i ON (r.targetItemID = i.itemID)
                  WHERE i.owningUserID = :userID
                  AND r.rating IS NOT NULL";

        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':userID', $userID );
        $stmt->execute ();
        $row = $stmt->fetch ( PDO::FETCH_ASSOC );
        return $row ['numRows'];
    }

    /**
     * Gets the aggregated rating for the given user, providing an overall rating
     * score calculated from all left ratings.
     *
     * @param PDO $db                The database to be queried.
     * @param int $userID            The ID of the user to be queried.
     * @return int                    The aggregate rating score.
     */
    public static function getUserRating(PDO $db, int $userID): int {
        $query = "SELECT AVG(r.rating) as rating 
                  FROM User_ratings AS r 
                  JOIN Items AS i ON (r.targetItemID = i.itemID) 
                  WHERE i.owningUserID = :userID
                  AND r.rating IS NOT NULL";

        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':userID', $userID );
        $stmt->execute ();
        $row = $stmt->fetch ( PDO::FETCH_ASSOC );
        return $row ['rating'];
    }

    /**
     * Stores a rating for a user, based on the given unique access code.
     *
     * @param PDO $db                The database to be queried.
     * @param string $accessCode    A unique access code representing the transaction.
     * @param int $rating            The rating score to be stored.
     */
    public static function leaveRatingForCode(PDO $db, string $accessCode, int $rating): void {
        $query = "UPDATE User_ratings 
                  SET rating = :rating, rating_left_at = CURRENT_TIMESTAMP 
                  WHERE accessCode = :accessCode";

        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':accessCode', $accessCode );
        $stmt->bindValue ( ':rating', $rating );
        $stmt->execute ();
    }

    /**
     * Gets a value indicating whether a rating has been stored for the given access code.
     *
     * @param PDO $db                The database to be queried.
     * @param string $accessCode    A unique access code representing the transaction.
     * @return bool                    True if a rating has been stored, false otherwise.
     */
    public static function isRatingLeftForCode(PDO $db, string $accessCode): bool {
        $query = "SELECT rating 
                  FROM User_ratings 
                  WHERE accessCode = :accessCode";

        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':accessCode', $accessCode );
        $stmt->execute ();
        $row = $stmt->fetch ( PDO::FETCH_ASSOC );

        return $row['rating'] !== null;
    }

    /**
     * Gets an array containing the items details associated with the given access code.
     *
     * @param PDO $db                The database to be queried.
     * @param string $accessCode    A unique access code representing the transaction.
     * @return array                An array containing information on the transaction.
     */
    public static function getRatingInfoForCode(PDO $db, string $accessCode): array {
        $query = "SELECT * 
                  FROM User_ratings 
                  WHERE accessCode = :accessCode";

        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':accessCode', $accessCode );
        $stmt->execute ();
        $row = $stmt->fetch ( PDO::FETCH_ASSOC );

        $result = [];
        $result['sourceItemID'] = $row ['sourceItemID'];
        $result['targetItemID'] = $row ['targetItemID'];

        return $result;
    }

    /**
     * Gets a value indicating whether the given access code is valid.
     *
     * @param PDO $db                The database to be queried
     * @param string $accessCode    The access code to be tested.
     * @return bool                    True if the code is valid, false otherwise.
     */
    public static function isValidRatingCode(PDO $db, string $accessCode): bool {
        $query = "SELECT COUNT(*) as numRows
                  FROM User_ratings 
                  WHERE accessCode = :accessCode";

        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':accessCode', $accessCode );
        $stmt->execute ();
        $row = $stmt->fetch ( PDO::FETCH_ASSOC );

        return $row['numRows'] != 0;
    }

    /**
     * Gets a value indicating whether the given access code belongs to the given user.
     *
     * @param PDO $db                The database to be queried.
     * @param string $accessCode    The access code to be tested.
     * @param int $userID            The user to be tested..
     * @return bool                    True if the access code belongs to the user, false otherwise.
     */
    public static function feedbackCodeBelongsToUser(PDO $db, string $accessCode, int $userID): bool {
        $query = "SELECT sourceItemID 
                  FROM User_ratings 
                  WHERE accessCode = :accessCode";

        $stmt = $db->prepare ( $query );
        $stmt->bindValue ( ':accessCode', $accessCode );
        $stmt->execute ();

        if ($row = $stmt->fetch ( PDO::FETCH_ASSOC )) {
            $item = new Item($db);
            $item->itemID = $row['sourceItemID'];
            $item->get();

            return $item->owningUserID == $userID;
        }

        return false;
    }
}
