<?php
/* Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

class DatabaseGenerator
{
	public static function RunScript(PDO $pdo, string $path) : void
	{
		$file = fopen($path, 'r');
		$query = fread($file, filesize($path));
		fclose($file);

		$stmt = $pdo->prepare($query);
		$stmt->execute();
	}

	public static function Generate(PDO $pdo): void
	{
		DatabaseGenerator::RunScript($pdo, __DIR__.'/createDb.sql');
	}

	public static function Populate(PDO $pdo): void
	{
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertUsers.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertCategories.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems1.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems2.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems3.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems4.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems5.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems6.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems7.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems8.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems9.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems10.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems11.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems12.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems13.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems14.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems15.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems16.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItems17.sql');
		DatabaseGenerator::RunScript($pdo,__DIR__.'/insertItemCategories.sql');
	}
}
