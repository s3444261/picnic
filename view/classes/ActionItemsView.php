<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
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
				$pair = [];
				$pair['item'] = $item;
				$pair['matchedItem'] = $match;
				$this->_matches [] = $pair;
			}
		}
	}

	public function matches() : array {
		return$this->_matches;
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
		return $item['item']['itemID'];
	}

	public function otherItemID(array $item) : string {
		return $item['matchedItem']['itemID'];
	}

	public function myItemTitle(array $item) : string {
		return $item['item']['title'];
	}

	public function otherItemTitle(array $item) : string {
		return $item['matchedItem']['title'];
	}

	public function otherItemOwnerName(array $item) : string {
		$h = new Humphree(Picnic::getInstance());
		$owner = $h->getItemOwner($item['matchedItem']['itemID']);
		return $owner['user'];
	}

	public function otherItemOwnerID(array $item) : string {
		return $item['matchedItem']['owningUserID'];
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

	public function discardMatchUrl(array $item) : string {
		return BASE . '/Dashboard/DiscardMatch/' . $this->myItemID($item) . '/' . $this->otherItemID($item);
	}

	public function getMatchToItemID(array $item) {
		return $this->myItemID($item) . '_' . $this->otherItemID($item);
	}

}
