<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class DatabaseGenerator
{
	public static function RunScript(PDO $pdo, string $path) : void {
		$file = fopen($path, 'r');
		$query = fread($file, filesize($path));
		fclose($file);

		$stmt = $pdo->prepare($query);
		$stmt->execute();
	}

	public static function Generate(PDO $pdo): void {
		self::RunScript($pdo, __DIR__.'/createDb.sql');
	}

	public static function Populate(PDO $pdo): void {
		self::RunScript($pdo,__DIR__.'/insertUsers.sql');
		self::RunScript($pdo,__DIR__.'/insertCategories.sql');
		self::RunScript($pdo,__DIR__.'/insertItems1.sql');
		self::RunScript($pdo,__DIR__.'/insertItems2.sql');
		self::RunScript($pdo,__DIR__.'/insertItems3.sql');
		self::RunScript($pdo,__DIR__.'/insertItems4.sql');
		self::RunScript($pdo,__DIR__.'/insertItems5.sql');
		self::RunScript($pdo,__DIR__.'/insertItems6.sql');
		self::RunScript($pdo,__DIR__.'/insertItems7.sql');
		self::RunScript($pdo,__DIR__.'/insertItems8.sql');
		self::RunScript($pdo,__DIR__.'/insertItems9.sql');
		self::RunScript($pdo,__DIR__.'/insertItems10.sql');
		self::RunScript($pdo,__DIR__.'/insertItems11.sql');
		self::RunScript($pdo,__DIR__.'/insertItems12.sql');
		self::RunScript($pdo,__DIR__.'/insertItems13.sql');
		self::RunScript($pdo,__DIR__.'/insertItems14.sql');
		self::RunScript($pdo,__DIR__.'/insertItems15.sql');
		self::RunScript($pdo,__DIR__.'/insertItems16.sql');
		self::RunScript($pdo,__DIR__.'/insertItems17.sql');
		self::RunScript($pdo,__DIR__.'/insertItemCategories.sql');
	}

	public static function CreateFullTextIndex(PDO $pdo): void {
		self::RunScript($pdo,__DIR__.'/createFullTextIndex.sql');
	}
}
