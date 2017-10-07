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

class CategoryController {

	/**
	 * Displays the category details page for the given category.
	 *
	 * @param $categoryId
	 * 			The ID of the category to be displayed.
	 */
	public function View($categoryId) {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('currentCategory', $h ->getCategory($categoryId));
		$view->SetData('subCategories', $h ->getCategoriesIn($categoryId));
		$view->SetData('items', $h ->getCategoryItems($categoryId));
		$view->Render('category');
	}
}
