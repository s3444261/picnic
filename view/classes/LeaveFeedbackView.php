<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class LeaveFeedbackView extends View {
	private $_myItem;
	private $_otherItem;
	private $_ratingUser;
	private $_accessCode;

	function __construct(string $accessCode) {
		$this->SetData('navData', new NavData(NavData::Account));

		$h = new Humphree(Picnic::getInstance());

		$rating = $h->getRatingInfoForCode($accessCode);
		$this->_accessCode = $accessCode;
		$this->_myItem = $h->getItem($rating['sourceItemID']);
		$this->_otherItem = $h->getItem($rating['targetItemID']);
		$this->_ratingUser = $h->getUser($this->_otherItem['owningUserID']);
	}

	public function hasError() {
		return (isset($_SESSION['error']) && $_SESSION['error'] !== '');
	}

	public function error() {
		if ($this->hasError()) {
			return $_SESSION['error'];
		}

		return '';
	}

	public function ratingUserName(): string {
		return $this->_ratingUser['user'];
	}

	public function myItemTitle(): string {
		return $this->_myItem['title'];
	}

	public function otherItemTitle(): string {
		return $this->_otherItem['title'];
	}

	public function myItemId(): string {
		return $this->_myItem['itemID'];
	}

	public function otherItemId(): string {
		return $this->_otherItem['itemID'];
	}

	public function myItemUrl(): string {
		return BASE . '/Item/View/' . $this->myItemID();
	}

	public function otherItemUrl(): string {
		return BASE . '/Item/View/' . $this->otherItemID();
	}

	public function postUrl(): string {
		return BASE . '/Dashboard/LeaveFeedback/' . $this->_accessCode;
	}
}