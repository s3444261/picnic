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

		$h = new Humphree(Picnic::getInstance());
		$h->runMatchingForAllItems();

		for ($i = 32591; $i < 33000; ++$i) {
			$paddedItemId = str_pad($i, 4, '0', STR_PAD_LEFT);
			$subDir = substr($paddedItemId, 0 , strlen($paddedItemId) - 3);

			$fullPath = ItemController::IMAGE_DIRECTORY . $subDir . "/" .$i . ".jpg";
			$thumbPath = ItemController::THUMB_DIRECTORY . $subDir . "/" .$i . ".jpg";

			if (file_exists($fullPath)) {
				unlink($fullPath);
			}

			if (file_exists($thumbPath)) {
				unlink($thumbPath);
			}

		}

		$view = new View();
		$view->SetData('navData',  new NavData(NavData::Account));
		$view->Render('createDB');
	}
}
