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
require_once  __DIR__ . '/../view/Pager.php';

class CategoryController {

	/**
	 * Default handler. Redirects to View.
	 */
	public function index() {
		header('Location: ' . BASE . '/Category/View');
	}

	/**
	 * Displays the category details page for the given category.
	 *
	 * @param $categoryId
	 * 			The ID of the category to be displayed.
	 */
	public function View(string $categoryId) {

		if ($categoryId == "") {
			$categoryId = Category::ROOT_CATEGORY;
		}

		// category ID must be a number, and small enough to fit into a 32 bit int.
		if (!is_numeric($categoryId) || strlen($categoryId) > 9) {
			http_response_code(400);
			echo ('<h1>Bad Request</h1>');
			die();
		}

		$h = new Humphree(Picnic::getInstance());

		// temporarily disabled paging
	//	$pagerData = Pager::ParsePagerDataFromQuery();
		$pagerData = new PagerData();
		$pagerData->pageNumber = 1;
		$pagerData->itemsPerPage = 200;
		$pagerData->totalItems = $h->countCategoryItems($categoryId);

		$view = new View();

		if ($categoryId == Category::ROOT_CATEGORY) {
			$view->SetData('currentCategoryName', "");
		} else {
			$view->SetData('currentCategoryName', $h ->getCategory($categoryId)['category']);
		}

		$subCategories = $h->getCategoriesIn($categoryId);
		if (count($subCategories) > 0) {
			$view->SetData('subCategories', $subCategories);
		}

		$items =  $h->getCategoryItemsByPage($categoryId, $pagerData->pageNumber, $pagerData->itemsPerPage, "ForSale" );
		if (count($items) > 0) {
			$view->SetData('forSaleItems', $items);
		}

		$items =  $h->getCategoryItemsByPage($categoryId, $pagerData->pageNumber, $pagerData->itemsPerPage, "Wanted" );
		if (count($items) > 0) {
			$view->SetData('wantedItems', $items);
		}

		$view->SetData('pagerData', $pagerData);
		$view->Render('category');
	}
}
