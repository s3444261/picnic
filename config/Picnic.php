<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

/**
 * The Picnic Class extends PDO and provides a singleton instance
 * connection to the picnic database.
 */
include '../../dbPicnic.php';

class Picnic extends PDO {
	private static $instance;
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			
			$database = array (
				'db_host' => getenv("DB_HOST"),
				'db_user' => getenv("DB_USER"),
				'db_pass' => getenv("DB_PW"),
				'db_name' => getenv("DB_NAME")
			);
			
			self::$instance = new Picnic ( 'mysql:host=' . $database ['db_host'] . ';dbname=' . $database ['db_name'], $database ['db_user'], $database ['db_pass'] );
			
			// Used to document errors during development.
			self::$instance->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		}
		return self::$instance;
	}
}

