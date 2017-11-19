<?php

require_once  __DIR__ . '/../config/Picnic.php';

/**
 * Controller for search-related functions.
 *
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class SearchController  {

	/**
	 * Displays the advanced search page.
	 */
	public function Advanced() {
		$view = new View();
		$view->SetData('navData', new NavData(NavData::ViewListings));
		$view->SetData('majorCategories', $this->getMajorCategories());
		$view->SetData('minorCategories', $this->getAllCategories());
		$view->Render('searchAdvanced');
	}

	/**
	 * Generates and displays a page of search results.
	 */
	public function Results() {
		if (isset($_REQUEST['searchBasic'])) {
			$this->doBasicSearch();
		} else if (isset($_REQUEST['searchAdvanced'])) {
			$this->doAdvancedSearch();
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Gets an array containing information on each top-level category.
	 *
	 * @return array	The category data.
	 */
	private function getMajorCategories(): array {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategoriesIn(Category::ROOT_CATEGORY);
	}

	/**
	 * Gets an array containing information on all categories
	 *
	 * @return array	The category data.
	 */
	private function getAllCategories(): array {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategories();
	}

	/**
	 * Performs a basic string-based search.
	 */
	private function doBasicSearch(): void {
		$text = $this->getSearchAttribute('srch-term', '');

		$h = new Humphree(Picnic::getInstance());
		$pagerData = Pager::ParsePagerDataFromQuery();
		$pagerData->totalItems = sizeof($h->search($text, 1, 1000000));
		$results = $h->search($text, $pagerData->pageNumber, $pagerData->itemsPerPage);

		$view = new View();
		$view->SetData('navData', new NavData(NavData::ViewListings));
		$view->SetData('results', $results);
		$view->SetData('pagerData', $pagerData);
		$view->Render('searchResults');
	}

	/**
	 * Performs an advanced attribute-based search.
	 */
	private function doAdvancedSearch(): void {
		$text 			= $this->getSearchAttribute('srch-term', '');
		$majorCategory 	= $this->getSearchAttribute('majorCategory', -1);
		$minorCategory 	= $this->getSearchAttribute('minorCategory', -1);
		$minPrice 		= $this->getSearchAttribute('minPrice', 0);
		$maxPrice 		= $this->getSearchAttribute('maxPrice',0x7FFFFFFF);
		$minQuantity 	= $this->getSearchAttribute('minQuantity', 1);
		$condition 		= $this->getSearchAttribute('itemcondition', '');
		$status 		= $this->getSearchAttribute('status', '');

		$h = new Humphree(Picnic::getInstance());
		$pagerData = Pager::ParsePagerDataFromQuery();

		$pagerData->totalItems = $h->countAdvancedSearchResults(
			$text,
			$minPrice,
			$maxPrice,
			$minQuantity,
			$condition,
			$status,
			$majorCategory,
			$minorCategory);

		$results = $h->searchAdvanced(
			$text,
			$minPrice,
			$maxPrice,
			$minQuantity,
			$condition,
			$status,
			$majorCategory,
			$minorCategory,
			$pagerData->pageNumber,
			$pagerData->itemsPerPage);

		$view = new View();
		$view->SetData('navData', new NavData(NavData::ViewListings));
		$view->SetData('results', $results);
		$view->SetData('pagerData', $pagerData);
		$view->Render('searchResults');
	}

	/**
	 * Attempts to get the value of the named search attribute from the request data.
	 *
	 * @param string $attributeName		The name of the desired attribute.
	 * @param mixed $defaultValue		A value to be returned if the attribute is not found.
	 *
	 * @return mixed					The effective attribute value, to be used in the search.
	 */
	private function getSearchAttribute(string $attributeName, mixed $defaultValue): mixed {
		return (isset($_REQUEST[$attributeName])&& $_REQUEST[$attributeName] !== '')
			? $_REQUEST[$attributeName]
			: $defaultValue;
	}
}
