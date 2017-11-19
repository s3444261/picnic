<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class AdminEditUserView extends View{

	private $_userID;
	private $_user;

	function __construct(int $userID) {
		$this->SetData('navData', new NavData(NavData::Account));

		$h = new Humphree(Picnic::getInstance());
		$this->_user = $h->getUser($userID);
		$this->_userID = $userID;
	}

	public function hasError(): bool {
		return isset($_SESSION['error']);
	}

	public function errorMessage(): string {
		return $this->hasError()
			? $_SESSION['error']
			: '';
	}

	public function isStandardUser(): bool {
		return isset($this->_user['status'])
			&& $this->_user['status'] === 'user';
	}

	public function isAdminUser(): bool {
		return isset($this->_user['status'])
			&& $this->_user['status'] === 'admin';
	}

	public function userName(): string {
		return isset($this->_user['user'])
			? $this->_user['user']
			: '';
	}

	public function userEmail(): string {
		return isset($this->_user['email'])
			? $this->_user['email']
			: '';
	}

	public function submitUrl(): string {
		return BASE . '/Administration/EditUser/' . $this->userID();
	}

	private function userID(): int {
		return $this->_userID;
	}
}