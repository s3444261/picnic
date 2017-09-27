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
	public static function RunScript($path) : void
	{
		$file = fopen(dirname(__FILE__).$path, 'r');
		$query = fread($file, filesize(dirname(__FILE__).$path));
		fclose($file);

		$db = Picnic::getInstance();
		$stmt = $db->prepare($query);
		$stmt->execute();
	}

	public static function Generate(): void
	{
		DatabaseGenerator::RunScript('./createDb.sql');
	}

	public static function Populate(): void
	{
		DatabaseGenerator::RunScript('./insertUsers.sql');
		DatabaseGenerator::RunScript('./insertCategories.sql');
		DatabaseGenerator::RunScript('./insertItems1.sql');
		DatabaseGenerator::RunScript('./insertItems2.sql');
		DatabaseGenerator::RunScript('./insertItems3.sql');
		DatabaseGenerator::RunScript('./insertItems4.sql');
		DatabaseGenerator::RunScript('./insertItems5.sql');
		DatabaseGenerator::RunScript('./insertItems6.sql');
		DatabaseGenerator::RunScript('./insertItems7.sql');
		DatabaseGenerator::RunScript('./insertItems8.sql');
		DatabaseGenerator::RunScript('./insertItems9.sql');
		DatabaseGenerator::RunScript('./insertItems10.sql');
		DatabaseGenerator::RunScript('./insertItems11.sql');
		DatabaseGenerator::RunScript('./insertItems12.sql');
		DatabaseGenerator::RunScript('./insertItems13.sql');
		DatabaseGenerator::RunScript('./insertItems14.sql');
		DatabaseGenerator::RunScript('./insertItems15.sql');
		DatabaseGenerator::RunScript('./insertItems16.sql');
		DatabaseGenerator::RunScript('./insertItems17.sql');
		DatabaseGenerator::RunScript('./insertItemCategories.sql');
		DatabaseGenerator::RunScript('./insertItemUsers.sql');
		DatabaseGenerator::RunScript('./insertItemUsers2.sql');
	}
}
