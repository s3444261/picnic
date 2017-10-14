<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */

class UserComments {
	
	private $_userID = '';
	private $db;

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
	
	/*
	 * Count number of occurences of comment for a user.
	 */
	public function count() {
		$query = "SELECT COUNT(*) AS numComments FROM UserComments WHERE userID = :userID";
		
		$stmt = $this->db->prepare ( $query );
		$stmt->bindParam ( ':userID', $this->_userID );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ['numComments'];
	}
	
	/*
	 * The getUserComments() method retrieves all comments made by a user and returns them
	 * as an array of comments.
	 */
	public function getUserComments(): array {
		
			$query = "SELECT * FROM UserComments WHERE userID = :userID"; 

			$stmt = $this->db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->execute (); 
			$objects = array();
			while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
				$comment = new Comment($this->db);
				$comment->commentID = $row ['commentID'];
				$comment->userID = $row ['userID'];
				$comment->comment = $row ['comment'];
				$comment->created_at = $row ['created_at'];
				$comment->updated_at = $row ['updated_at'];
				
				$objects[] = $comment;
			} 
			
			return $objects;
	}
}
?>