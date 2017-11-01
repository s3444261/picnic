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

class SearchController  {

	public function Advanced() {
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$view = new View();
			$view->SetData('navData', new NavData(NavData::ViewListings));
			$view->SetData('majorCategories', $this->majorCategories());
			$view->SetData('minorCategories', $this->minorCategories());
			$view->Render('searchAdvanced');
		} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
			$h = new Humphree(Picnic::getInstance());

			$text = isset($_POST['searchText']) ? $_POST['searchText'] : '';
			$majorCategory = isset($_POST['majorCategory']) ? $_POST['majorCategory'] : -1;
			$minorCategory = isset($_POST['minorCategory']) ? $_POST['minorCategory'] : -1;
			$minPrice = ($_POST['minPrice'] !== '') ? $_POST['minPrice'] : 0;
			$maxPrice = ($_POST['maxPrice'] !== '') ? $_POST['maxPrice'] : 0x7FFFFFFF;
			$minQuantity = ($_POST['minQuantity'] !== '') ? $_POST['minQuantity'] : 1;
			$condition = isset($_POST['itemcondition']) ? $_POST['itemcondition'] : '';
			$status = isset($_POST['status']) ? $_POST['status'] : '';
			
			$results = $h->searchAdvanced($text, $minPrice, $maxPrice, $minQuantity, $condition, $status, $majorCategory, $minorCategory);
			$view = new View();
			$view->SetData('navData', new NavData(NavData::ViewListings));
			$view->SetData('results', $results);
			$view->Render('searchResults');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function Basic() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
			$h = new Humphree(Picnic::getInstance());

			$text = isset($_POST['srch-term']) ? $_POST['srch-term'] : '';

			$results = $h->search($text);
			$view = new View();
			$view->SetData('navData', new NavData(NavData::ViewListings));
			$view->SetData('results', $results);
			$view->Render('searchResults');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	private function majorCategories() {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategoriesIn(Category::ROOT_CATEGORY);
	}

	private function minorCategories() {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategories();
	}
}
