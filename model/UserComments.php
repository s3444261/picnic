<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class UserComments {
    private $_userID = '';
    private $_db;
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
     * Retrieves all comments made by a user and returns them
     * as an array of comments.
     *
     * @return array
     */
    public function getUserComments(): array {
        $query = "SELECT * 
                  FROM Comments 
                  WHERE toUserID = :userID OR fromUserID = :userID";
        
        $stmt = $this->_db->prepare ( $query );
        $stmt->bindValue ( ':userID', $this->_userID );
        $stmt->execute ();
        $objects = [];
        while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
            $comment = new Comment ( $this->_db );
            $comment->commentID = $row ['commentID'];
            $comment->get();
            $objects [] = $comment;
        }
        
        return $objects;
    }
}
