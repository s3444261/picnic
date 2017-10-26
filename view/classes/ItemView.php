<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */

/**
 * Class ItemEditView
 *
 * View class for editing an existing item.
 */
class ItemView extends View
{
	function __construct() {
		$this->SetData('navData', new NavData(NavData::ViewListings));
	}

	public function majorCategories() {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategoriesIn(Category::ROOT_CATEGORY);
	}

	public function minorCategories() {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategories();
	}

	public function itemID() {
		return $this-> getItemAttribute('itemID');
	}

	public function hasError() {
		return (isset($_SESSION['error']) && $_SESSION['error'] !== '');
	}

	public function error() {
		if (isset($_SESSION['error']) && $_SESSION['error'] !== '') {
			return $_SESSION['error'];
		}

		return '';
	}

	public function isItemForSale() {
		return $this->itemStatus() === 'ForSale';
	}

	public function isItemWanted() {
		return $this->itemStatus()=== 'Wanted';
	}

	public function isMajorCategorySelected() {
		return $this->getItemAttribute('majorCategory') !== -1;
	}

	public function itemStatus() {
		return $this-> getItemAttribute('status');
	}

	public function itemTitle() {
		return $this-> getItemAttribute('title');
	}

	public function itemDescription() {
		return $this-> getItemAttribute('description');
	}

	public function itemQuantity() {
		return $this->getItemAttribute('quantity');
	}

	public function isMinorCategorySelected() {
		return $this->getItemAttribute('category') !== -1;
	}

	public function itemPrice() {
		return $this->getItemAttribute('price');
	}

	public function itemCondition() {
		return $this->getItemAttribute('itemcondition');
	}

	public function isItemNew() {
		return $this->itemCondition()=== 'New';
	}

	public function isItemUsed() {
		return $this->itemCondition()=== 'Used';
	}

	public function isSelectedMajorCategory($categoryID) {
		return $this->getItemAttribute('majorCategory') === $categoryID;
	}

	public function isSelectedMinorCategory($categoryID) {
		return $this->getItemAttribute('category') === $categoryID;
	}

	public function selectedMinorCategory() {
		return $this->getItemAttribute('category');
	}

	public function selectedMajorCategory() {
		return $this->getItemAttribute('majorCategory');
	}

	public function selectedMinorCategoryName() {
		$h = new Humphree(Picnic::getInstance());
		$category = $h->getCategory($this->selectedMinorCategory());
		return $category['category'];
	}

	public function selectedMajorCategoryName() {
		$h = new Humphree(Picnic::getInstance());
		$category = $h->getCategory($this->selectedMajorCategory());
		return $category['category'];
	}

	private function getItemAttribute($name) {
		if (isset($_SESSION['itemAdd']) && isset($_SESSION['itemAdd'][$name])) {
			return $_SESSION['itemAdd'][$name];
		}

		return null;
	}
}
