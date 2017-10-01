<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

include __DIR__ . '/../admin/createDB/DatabaseGenerator.php';

class CreateDBController {
	public function index()
	{
		DatabaseGenerator::Generate();
		DatabaseGenerator::Populate();

		include __DIR__ . '/../view/layout/createDB.php';
	}
}
