<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class AdminCategoriesView extends View{

	private $_majorCategories;

	function __construct() {
		$this->SetData('navData', new NavData(NavData::Account));
		$h = new Humphree(Picnic::getInstance());
		$this->_majorCategories = $h->getCategoriesIn(Category::ROOT_CATEGORY);
	}

	public function getMajorCategories() : array {
		return $this->_majorCategories;
	}

	public function getMinorCategories(array $category) : array {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategoriesIn($this->categoryId($category));
	}

	public function categoryId(array $category): string {
		return $category['categoryID'];
	}
	
	public function categoryName(array $category): string {
		return $category['category'];
	}

	public function countCategoryItems(array $category) : int {
		$h = new Humphree(Picnic::getInstance());
		return $h->countCategoryItems($this->categoryId($category));
	}

	public function canDelete(array $category): bool {
		$h = new Humphree(Picnic::getInstance());
		return ($h->countCategoryItems($this->categoryId($category)) === 0)
			&& (count($h->getCategoriesIn($this->categoryId($category))) === 0) ;
	}

	public function addMinorCategoryUrl(array $category): string {
		return BASE . '/Administration/AddMinorCategory/' . $this->categoryId($category);
	}

	public function addMajorCategoryUrl(): string {
		return BASE . '/Administration/AddMajorCategory';
	}

	public function deleteCategoryUrl(array $category): string {
		return BASE . '/Administration/DeleteCategory/' . $this->categoryId($category);
	}

	public function usersUrl(): string {
		return BASE . '/Administration/Users';
	}

	public function categoriesUrl(): string {
		return BASE . '/Administration/ViewCategories';
	}

	public function systemUrl(): string {
		return BASE . '/Administration/System';
	}
}
