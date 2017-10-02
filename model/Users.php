<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

class Users {

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
	 * The getUsers() function retrieves all users from the database and returns
	 * them as an array of user objects.
	 */
	public function getUsers(): array {
		
		$query = "SELECT userID FROM Users ORDER BY userID";

		$stmt = $this->db ->prepare ( $query );
		$stmt->execute ();
		$objects = array ();
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$user = new User($this->db);
			$user->userID = $row['userID'];
			try {
				$user->get();
			} catch (UserException $e) {
				$_SESSION ['error'] = $e->getError ();
			}
			
			$objects [] = $user;
		}
		
		return $objects;
	}

}
?>