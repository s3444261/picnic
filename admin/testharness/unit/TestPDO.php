<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

include __DIR__ . "/../../../../../dbPicnic.php";

class TestPDO extends PDO {
	private static $instance;
	
	/**
	 * Singleton
	 * 
	 * @return TestPDO
	 */
	public static function getInstance() {
		if (! isset ( self::$instance )) {

			$database = array (
				'db_host' => getenv("DB_HOST"),
				'db_user' => getenv("TEST_DB_USER"),
				'db_pass' => getenv("TEST_DB_PW"),
				'db_name' => getenv("TEST_DB_NAME")
			);

			self::$instance = new TestPDO ( 'mysql:host=' . $database ['db_host'] . ';dbname=' . $database ['db_name'], $database ['db_user'], $database ['db_pass'] );

			// Used to document errors during development.
			self::$instance->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		}
		return self::$instance;
	}

	/**
	 * Creates the Test Database and a User.
	 * 
	 * @return boolean
	 */
	public static function CreateTestDatabaseAndUser() {

		TestPDO::CleanUp();

		$rootPDO =  new PDO ( 'mysql:host=' . getenv("DB_HOST") , getenv("ADMIN_DB_USER"), getenv("ADMIN_DB_PW"));

		$query = "CREATE USER '".getenv("TEST_DB_USER")."'@'".getenv("DB_HOST")."' IDENTIFIED BY '".getenv("TEST_DB_PW")."';";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

		$query = "CREATE DATABASE ".getenv("TEST_DB_NAME").";";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

		$query = "GRANT ALL ON ".getenv("TEST_DB_NAME").".* TO '".getenv("TEST_DB_USER")."'@'".getenv("DB_HOST")."';";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();
		
		return true;
	}

	/**
	 * Deletes the Test Database
	 */
	public static function CleanUp() {
		$rootPDO =  new PDO ( 'mysql:host=' . getenv("DB_HOST") , getenv("ADMIN_DB_USER"), getenv("ADMIN_DB_PW"));

		$query = "DROP DATABASE IF EXISTS ".getenv("TEST_DB_NAME").";";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

		$query = "DROP USER IF EXISTS ".getenv("TEST_DB_USER")."@".getenv("DB_HOST").";";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();
	}
}
