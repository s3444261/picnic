<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

require_once  __DIR__ . '/../config/Picnic.php';

class HomeController {

	/**
	 * Displays the home page.
	 */
	public function index() {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('categories', $h ->getCategoriesIn(Category::ROOT_CATEGORY));

		$navData = new NavData();
		$navData->Selected = NavData::Home;
		$view->SetData('navData', $navData);

		$view->Render('home');
	}
}
