<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */

/**
 * Class ItemView
 *
 * View class for editing an existing item.
 */
class ItemView extends View
{
	function __construct() {
		$this->SetData('navData', new NavData(NavData::ViewListings));
	}

	public function isLoggedInUser() {
		return isset($_SESSION['userID']);
	}

	public function majorCategories() {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategoriesIn(Category::ROOT_CATEGORY);
	}

	public function minorCategories() {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategories();
	}

	public function matchedItems() {
		$h = new Humphree(Picnic::getInstance());
		return $h->getMatchedItemsFor($this->itemID());
	}

	public function itemID() {
		return $this-> getItemAttribute('itemID');
	}

	public function itemOwnerName(): string {
		$h = new Humphree(Picnic::getInstance());
		$user =  $h->getUser($this->owningUserId());
		return $user['user'];
	}

	public function itemOwnerHasRating():int {
		$h = new Humphree(Picnic::getInstance());
		return $h->getUserHasRating($this->owningUserId());
	}

	public function itemOwnerRating():int {
		$h = new Humphree(Picnic::getInstance());
		return $h->getUserRating($this->owningUserId());
	}

	public function currentUserIsItemOwner() {
		return $this->isLoggedInUser()
			&& $this->owningUserId() === $_SESSION['userID'];
	}

	private function getFullyMatchedItem() {
		$h = new Humphree(Picnic::getInstance());
		$otherItemID = $h->getFullyMatchedItemId($this->itemID());
		return $h->getItem($otherItemID);
	}

	public function fullyMatchedItemTitle() {
		$otherItem = $this->getFullyMatchedItem();
		return $otherItem['title'];
	}

	public function fullyMatchedItemUrl() {
		$otherItem = $this->getFullyMatchedItem();
		return BASE . '/Item/View/' . $otherItem['itemID'];
	}
	public function currentUserIsFullyMatchedItemOwner() {
		if (!$this->isLoggedInUser()) {
			return false;
		}

		$otherItem = $this->getFullyMatchedItem();
		$otherItemOwnerID = $otherItem['owningUserID'];
		return $otherItemOwnerID === $_SESSION['userID'];
	}

	public function itemIsVisibleToCurrentUser(): bool {
		if ($this->itemIsDeleted()) {
			return false;
		}

		if ($this->itemIsActive()) {
			return true;
		}

		if ($this->itemIsCompleted()) {
			if ($this->currentUserIsItemOwner() || $this->currentUserIsFullyMatchedItemOwner() ) {
				return true;
			}
		}

		return false;
	}

	public function itemIsActive(): bool {
		return ( $this-> getItemAttribute('status') === 'Active');
	}

	private function itemIsDeleted(): bool {
   		return ( $this-> getItemAttribute('status') === 'Deleted');
	}

	public function itemIsCompleted(): bool {
		return ( $this-> getItemAttribute('status') === 'Completed');
	}


	public function hasInfoMessage() {
		return (isset($this->data['info']) && $this->data['info'] !== '');
	}

	public function infoMessage() {
		if (isset($this->data['info']) && $this->data['info'] !== '') {
			return $this->data['info'];
		}

		return '';
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
		return $this->itemType() === 'ForSale';
	}

	public function isItemWanted() {
		return $this->itemType()=== 'Wanted';
	}

	public function isMajorCategorySelected() {
		return $this->getItemAttribute('majorCategory') !== -1;
	}

	public function itemType() {
		return $this-> getItemAttribute('type');
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

	public function owningUserId() {
		return $this->getItemAttribute('owningUserID');
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

		if (isset($this->data['item']) && isset($this->data['item'][$name])) {
			return $this->data['item'][$name];
		}

		return null;
	}
}
