<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

require_once  __DIR__ . '/../admin/createDB/DatabaseGenerator.php';
require_once  __DIR__ . '/../config/Picnic.php';

class CreateDBController {
	public function index()
	{
		DatabaseGenerator::Generate(Picnic::getInstance());
		DatabaseGenerator::Populate(Picnic::getInstance());

		$view = new View();

		$navData = new NavData();
		$navData->Selected = NavData::Account;
		$view->SetData('navData', $navData);

		$view->Render('createDB');
	}
}
