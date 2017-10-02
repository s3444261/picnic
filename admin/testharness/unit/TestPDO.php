<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'travis');  // this is the name that travis CI uses, so we may as well use it too
define('DB_PW', '');          // same for the password
define('DB_NAME', 'picnic_test');

class TestPDO extends PDO {
	private static $instance;
	public static function getInstance() {
		if (! isset ( self::$instance )) {

			$database = array (
				'db_host' => DB_HOST,
				'db_user' => DB_USER,
				'db_pass' => DB_PW,
				'db_name' => DB_NAME
			);

			self::$instance = new TestPDO ( 'mysql:host=' . $database ['db_host'] . ';dbname=' . $database ['db_name'], $database ['db_user'], $database ['db_pass'] );

			// Used to document errors during development.
			self::$instance->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		}
		return self::$instance;
	}

	public static function CreateTestDatabaseAndUser() {

		$rootPDO =  new PDO ( 'mysql:host=' . DB_HOST , 'root', '' );

		$query = "DROP DATABASE IF EXISTS ".DB_NAME.";";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

		$query = "DROP USER IF EXISTS ".DB_USER."@".DB_HOST.";";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

		$query = "CREATE USER '".DB_USER."'@'".DB_HOST."' IDENTIFIED BY '".DB_PW."';";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

		$query = "CREATE DATABASE picnic_test;";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

		$query = "GRANT ALL ON ".DB_NAME.".* TO '".DB_USER."'@'".DB_HOST."';";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();
		
		return true;
	}
}
