<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class AdminUsersView extends View {

	private $_users;
	private $_pagerData;

	function __construct() {
		$this->SetData('navData', new NavData(NavData::Account));

		$h = new Humphree(Picnic::getInstance());
		$this->_pagerData = Pager::ParsePagerDataFromQuery();
		$this->_pagerData->totalItems = $h->countUsers();

		$this->_users = $h->getUsers($this->_pagerData->pageNumber, $this->_pagerData->itemsPerPage);
	}

	public function users() : array {
		return $this->_users;
	}

	public function userIsBlocked(array $user): bool {
		return $user['blocked'] != 0;
	}

	public function userStatus(array $user): string {
		if ($this->userIsBlocked($user)) {
			return 'BLOCKED';
		} else {
			return $user['status'];
		}
	}

	public function userID(array $user): int {
		return $user['userID'];
	}

	public function userName(array $user): string {
		return $user['user'];
	}

	public function userEmail(array $user): string {
		return $user['email'];
	}

	public function deleteDialogId(array $user): string {
		return 'deleteDialog' . $this->userID($user);
	}

	public function blockDialogId(array $user): string {
		return 'blockDialog' . $this->userID($user);
	}

	public function unblockDialogId(array $user): string {
		return 'unblockDialog' . $this->userID($user);
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

	public function deleteUserUrl(array $user): string {
		return BASE . '/Administration/DeleteUser/' . $this->userID($user);
	}

	public function blockUserUrl(array $user): string {
		return BASE . '/Administration/BlockUser/' . $this->userID($user);
	}

	public function unblockUserUrl(array $user): string {
		return BASE . '/Administration/UnblockUser/' . $this->userID($user);
	}

	public function editUserUrl(array $user): string {
		return BASE . '/Administration/EditUser/' . $this->userID($user);
	}

	public function changeUserPasswordUrl(array $user): string {
		return BASE . '/Administration/ChangePassword/' . $this->userID($user);
	}

	public function renderPager(string $style, bool $includeMessage): string {
		return Pager::Render($style, $this->_pagerData, $includeMessage);
	}
}