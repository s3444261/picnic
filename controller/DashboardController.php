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

class DashboardController {

	public function index()
	{
		header('Location: ' . BASE . '/Dashboard/View');
	}

	public function View() {
		if ($this->auth()) {

			$h = new Humphree(Picnic::getInstance());

			$view = new View();
			$view->SetData('forSaleItems',   $h->getUserItems($_SESSION['userID'], 'owner', "ForSale"));
			$view->SetData('wantedItems',   $h->getUserItems($_SESSION['userID'], 'owner', "Wanted"));
			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('dashboard');

		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	private function auth(){
		if(isset($_SESSION[MODULE]) && isset($_SESSION['userID'])){
			if($_SESSION['userID'] > 0){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
