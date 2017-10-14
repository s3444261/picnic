<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

class PrivacyPolicyController {

	// Displays the Privacy Policy Page.
	public function index()
	{
		$view = new View();

		$navData = new NavData();
		$navData->Selected = NavData::Home;
		$view->SetData('navData', $navData);

		$view->Render('privacyPolicy');
	}
}
