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
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function Results() {
		if (isset($_REQUEST['searchBasic'])) {
			$text = isset($_REQUEST['srch-term']) ? $_REQUEST['srch-term'] : '';
			$h = new Humphree(Picnic::getInstance());
			$pagerData = Pager::ParsePagerDataFromQuery();
			$pagerData->totalItems = sizeof( $h->search($text, 1, 1000000));
			$results = $h->search($text, $pagerData->pageNumber, $pagerData->itemsPerPage);
			$view = new View();
			$view->SetData('navData', new NavData(NavData::ViewListings));
			$view->SetData('results', $results);
			$view->SetData('pagerData', $pagerData);
			$view->Render('searchResults');
		} else if (isset($_REQUEST['searchAdvanced'])) {
			$text = isset($_REQUEST['srch-term']) ? $_REQUEST['srch-term'] : '';
			$majorCategory = isset($_REQUEST['majorCategory']) ? $_REQUEST['majorCategory'] : -1;
			$minorCategory = isset($_REQUEST['minorCategory']) ? $_REQUEST['minorCategory'] : -1;
			$minPrice = (isset($_REQUEST['minPrice']) && $_REQUEST['minPrice'] !== '') ? $_REQUEST['minPrice'] : 0;
			$maxPrice = (isset($_REQUEST['maxPrice']) && $_REQUEST['maxPrice'] !== '') ? $_REQUEST['maxPrice'] : 0x7FFFFFFF;
			$minQuantity = (isset($_REQUEST['minQuantity']) && $_REQUEST['minQuantity'] !== '') ? $_REQUEST['minQuantity'] : 1;
			$condition = isset($_REQUEST['itemcondition']) ? $_REQUEST['itemcondition'] : '';
			$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
			$h = new Humphree(Picnic::getInstance());
			$pagerData = Pager::ParsePagerDataFromQuery();
			$pagerData->totalItems = sizeof( $h->searchAdvanced($text, $minPrice, $maxPrice, $minQuantity, $condition, $status, $majorCategory, $minorCategory, 1, 1000000));
			$results = $h->searchAdvanced($text, $minPrice, $maxPrice, $minQuantity, $condition, $status, $majorCategory, $minorCategory, $pagerData->pageNumber, $pagerData->itemsPerPage);
			$view = new View();
			$view->SetData('navData', new NavData(NavData::ViewListings));
			$view->SetData('results', $results);
			$view->SetData('pagerData', $pagerData);
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
