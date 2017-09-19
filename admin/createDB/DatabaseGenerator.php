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
	public static function Generate(): void
	{
		$file = fopen(dirname(__FILE__).'/createDb.sql', 'r');
		$query = fread($file, filesize(dirname(__FILE__).'/createDb.sql'));
		fclose($file);

		$db = Picnic::getInstance();
		$stmt = $db->prepare($query);
		$stmt->execute();
	}
}
