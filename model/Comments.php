<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}

class Comments {
	
	private $_userID = '';
	
	// Constructor
	function __construct($args = array()) {
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
	 * The getUserComments() method retrieves all comments made by a user and returns them
	 * as an array of comments.
	 */
	public function getUserComments() {
		
			$query = "SELECT * FROM Comments WHERE userID = :userID";
			
			$db = Picnic::getInstance ();
			$stmt = $db->prepare ( $query );
			$stmt->bindParam ( ':userID', $this->_userID );
			$stmt->execute ();
			$objects = array();
			while($row = $stmt->fetch ( PDO::FETCH_ASSOC )){
				$comment = new Comment();
				$comment->commentID = $row ['commentID'];
				$comment->_userID = $row ['userID'];
				$comment->_comment = $row ['comment'];
				$comment->_created_at = $row ['created_at'];
				$comment->_updated_at = $row ['updated_at'];
				
				$objects[] = $comment;
			}
			
			return $object;
	}
}
?>