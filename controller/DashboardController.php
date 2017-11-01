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

	public function index() {
		header('Location: ' . BASE . '/Dashboard/ForSale');
	}

	public function View() {
		header('Location: ' . BASE . '/Dashboard/ForSale');
	}

	public function ForSale() {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());

			$view = new View();
			$view->SetData('forSaleItems',   $h->getUserOwnedItems($_SESSION['userID'], "ForSale"));
			$view->SetData('wantedItems',   $h->getUserOwnedItems($_SESSION['userID'],  "Wanted"));
			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('dashboardForSale');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function Wanted() {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());

			$view = new View();
			$view->SetData('forSaleItems',   $h->getUserOwnedItems($_SESSION['userID'], "ForSale"));
			$view->SetData('wantedItems',   $h->getUserOwnedItems($_SESSION['userID'],  "Wanted"));
			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('dashboardWanted');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function Messages() {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());

			$view = new View();
			$view->SetData('forSaleItems',   $h->getUserOwnedItems($_SESSION['userID'], "ForSale"));
			$view->SetData('wantedItems',   $h->getUserOwnedItems($_SESSION['userID'],  "Wanted"));
			$view->SetData('navData',  new NavData(NavData::Account));
			$view->Render('dashboardMessages');
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
