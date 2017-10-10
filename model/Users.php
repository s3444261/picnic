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
	 * Retrieves all users that haven't been deleted.
	 */
	public function getUsers(int $pageNumber, int $usersPerPage): array {
		
		$v = new Validation();
		$users = array ();
		
		try {
			$v->number($pageNumber);
			$v->number($usersPerPage);
			$v->numberGreaterThanZero($pageNumber);
			$v->numberGreaterThanZero($usersPerPage);
			
			$pn = $pageNumber;
			$upp = $usersPerPage;
			
			$pn = ($pn - 1)*$upp;
			
			$query = "SELECT userID, user, email, status
					FROM Users
					WHERE status != 'deleted'
					LIMIT " . $pn . "," . $upp;
			
			$stmt = $this->db->prepare ( $query );
			$stmt->execute ();
						
			while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				
				$user = new User($this->db);
				$user->userID = $row['userID'];
				$user->user = $row['user'];
				$user->email = $row['email'];
				$user->status = $row['status'];
				
				$users [] = $user;
			}
		} catch (ValidationException $e) { 
			throw new UsersException($e->getMessage());
		}
		return $users;
	}
}
?>