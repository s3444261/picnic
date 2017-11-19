<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
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

	public function isLoggedInUser(): bool {
		return isset($_SESSION['userID']);
	}

	public function majorCategories(): array {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategoriesIn(Category::ROOT_CATEGORY);
	}

	public function minorCategories(): array {
		$h = new Humphree(Picnic::getInstance());
		return $h->getCategories();
	}

	public function matchedItems(): array {
		$h = new Humphree(Picnic::getInstance());
		return $h->getMatchedItemsFor($this->itemID());
	}

	public function itemID(): int {
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

	public function itemOwnerRatingCount():int {
		$h = new Humphree(Picnic::getInstance());
		return $h->getUserRatingCount($this->owningUserId());
	}

	public function itemOwnerRating():int {
		$h = new Humphree(Picnic::getInstance());
		return $h->getUserRating($this->owningUserId());
	}

	public function currentUserIsItemOwner(): bool {
		return $this->isLoggedInUser()
			&& $this->owningUserId() === $_SESSION['userID'];
	}

	private function getFullyMatchedItem(): array {
		$h = new Humphree(Picnic::getInstance());
		$otherItemID = $h->getFullyMatchedItemId($this->itemID());
		return $h->getItem($otherItemID);
	}

	public function fullyMatchedItemTitle(): string {
		$otherItem = $this->getFullyMatchedItem();
		return $otherItem['title'];
	}

	public function fullyMatchedItemUrl(): string {
		$otherItem = $this->getFullyMatchedItem();
		return BASE . '/Item/View/' . $otherItem['itemID'];
	}

	public function currentUserIsFullyMatchedItemOwner(): bool {
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

	public function hasInfoMessage(): bool {
		return (isset($this->data['info']) && $this->data['info'] !== '');
	}

	public function infoMessage(): string {
		if (isset($this->data['info']) && $this->data['info'] !== '') {
			return $this->data['info'];
		}

		return '';
	}

	public function hasError(): bool {
		return (isset($_SESSION['error']) && $_SESSION['error'] !== '');
	}

	public function error(): string {
		if (isset($_SESSION['error']) && $_SESSION['error'] !== '') {
			return $_SESSION['error'];
		}

		return '';
	}

	public function isItemForSale(): bool {
		return $this->itemType() === 'ForSale';
	}

	public function isItemWanted(): bool {
		return $this->itemType()=== 'Wanted';
	}

	public function isMajorCategorySelected(): bool {
		return $this->getItemAttribute('majorCategory') !== -1;
	}

	public function itemType(): string {
		return $this-> getItemAttribute('type');
	}

	public function itemTitle(): string {
		return $this-> getItemAttribute('title');
	}

	public function itemDescription(): string {
		return $this-> getItemAttribute('description');
	}

	public function itemQuantity(): int {
		return $this->getItemAttribute('quantity');
	}

	public function isMinorCategorySelected(): bool {
		return $this->getItemAttribute('category') !== -1;
	}

	public function itemPrice(): float {
		return $this->getItemAttribute('price');
	}

	public function owningUserId(): int {
		return $this->getItemAttribute('owningUserID');
	}

	public function itemCondition(): string {
		return $this->getItemAttribute('itemcondition');
	}

	public function isItemNew(): bool {
		return $this->itemCondition()=== 'New';
	}

	public function isItemUsed(): bool {
		return $this->itemCondition()=== 'Used';
	}

	public function isSelectedMajorCategory($categoryID): bool {
		return $this->getItemAttribute('majorCategory') === $categoryID;
	}

	public function isSelectedMinorCategory($categoryID): bool {
		return $this->getItemAttribute('category') === $categoryID;
	}

	public function selectedMinorCategory(): int {
		return $this->getItemAttribute('category');
	}

	public function selectedMajorCategory(): int {
		return $this->getItemAttribute('majorCategory');
	}

	public function selectedMinorCategoryName(): string {
		$h = new Humphree(Picnic::getInstance());
		$category = $h->getCategory($this->selectedMinorCategory());
		return $category['category'];
	}

	public function selectedMajorCategoryName(): string {
		$h = new Humphree(Picnic::getInstance());
		$category = $h->getCategory($this->selectedMajorCategory());
		return $category['category'];
	}

	private function getItemAttribute(string $name): {
		if (isset($_SESSION['itemAdd']) && isset($_SESSION['itemAdd'][$name])) {
			return $_SESSION['itemAdd'][$name];
		}

		if (isset($this->data['item']) && isset($this->data['item'][$name])) {
			return $this->data['item'][$name];
		}

		return null;
	}
}
