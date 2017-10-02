<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

include __DIR__ . "/../../../../../dbPicnic.php";

class TestPDO extends PDO {
	private static $instance;
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

	public static function CreateTestDatabaseAndUser() {

		$rootPDO =  new PDO ( 'mysql:host=' . getenv("DB_HOST") , getenv("ADMIN_DB_USER"), getenv("ADMIN_DB_PW"));

		$query = "DROP DATABASE IF EXISTS ".getenv("TEST_DB_NAME").";";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

		$query = "DROP USER IF EXISTS ".getenv("TEST_DB_USER")."@".getenv("DB_HOST").";";
		$stmt = $rootPDO->prepare ( $query );
		$stmt->execute ();

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
}
