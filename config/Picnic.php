<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 *
 * Picnic Class
 * 
 * The Picnic Class extends PDO and provides a singleton instance
 * connection to the picnic database by accessing the constants
 * in db.php.
 */
include '../../dbPicnic.php';

class Picnic extends PDO {
	private static $instance;
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			
			$database = array (
					'db_host' => DB_HOST,
					'db_user' => DB_USER,
					'db_pass' => DB_PW,
					'db_name' => DB_NAME 
			);
			
			self::$instance = new Picnic ( 'mysql:host=' . $database ['db_host'] . ';dbname=' . $database ['db_name'], $database ['db_user'], $database ['db_pass'] );
			
			// Used to document errors during development.
			self::$instance->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		}
		return self::$instance;
	}
}
?>