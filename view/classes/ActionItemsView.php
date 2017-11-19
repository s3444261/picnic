<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class ActionItemsView extends View
{
	private $_matches;

	function __construct() {
		$this->SetData('navData', new NavData(NavData::Account));
		$h = new Humphree(Picnic::getInstance());
		$allItems = $h->getUserOwnedItems($_SESSION['userID']);

		$this->_matches = [];
		foreach ($allItems as $item) {
			$matchesForThisItem = $h->getMatchedItemsFor($item['itemID']);
			foreach ($matchesForThisItem as $match) {
				$this->_matches [] = $match;
			}
		}
	}

	public function matches() : array {
		return $this->_matches;
	}

	public function totalMatches(): int {
		return count($this->_matches);
	}

	public function forSaleUrl() : string {
		return BASE . '/Dashboard/ForSale';
	}

	public function wantedUrl() : string {
		return BASE . '/Dashboard/Wanted';
	}

	public function messagesUrl() : string {
		return BASE . '/Dashboard/Messages';
	}

	public function actionItemsUrl() : string {
		return BASE . '/Dashboard/ActionItems';
	}

	public function myItemID(array $item) : string {
		return $item['myItem']['itemID'];
	}

	public function otherItemID(array $item) : string {
		return $item['otherItem']['itemID'];
	}

	public function myItemTitle(array $item) : string {
		return $item['myItem']['title'];
	}

	public function otherItemTitle(array $item) : string {
		return $item['otherItem']['title'];
	}

	public function myStatus(array $item) : string {
		return $item['myStatus'];
	}

	public function otherStatus(array $item) : string {
		return $item['otherStatus'];
	}

	public function otherItemOwnerName(array $item) : string {
		$h = new Humphree(Picnic::getInstance());
		$owner = $h->getItemOwner($item['otherItem']['itemID']);
		return $owner['user'];
	}

	public function otherItemOwnerID(array $item) : string {
		return $item['otherItem']['owningUserID'];
	}

	public function viewMyItemUrl(array $item) : string {
		return BASE . '/Item/View/' . $this->myItemID($item);
	}

	public function viewOtherItemUrl(array $item) : string {
		return BASE . '/Item/View/' . $this->otherItemID($item);
	}

	public function sendMessageUrl() : string {
		return BASE . '/Dashboard/SendMessage';
	}

	public function imageUrl(string $file) : string {
		return BASE . '/img/' . $file;
	}

	public function discardMatchUrl(array $item) : string {
		return BASE . '/Dashboard/DiscardMatch/' . $this->myItemID($item) . '/' . $this->otherItemID($item);
	}

	public function acceptMatchUrl(array $item) : string {
		return BASE . '/Dashboard/AcceptMatch/' . $this->myItemID($item) . '/' . $this->otherItemID($item);
	}

	public function getMatchToItemID(array $item): string {
		return $this->myItemID($item) . '_' . $this->otherItemID($item);
	}

}
