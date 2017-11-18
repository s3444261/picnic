<?php

require_once  __DIR__ . '/../admin/createDB/DatabaseGenerator.php';
require_once  __DIR__ . '/../config/Picnic.php';

/**
 * Controller for administration-related functions.
 *
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class AdministrationController {

	/**
	 * Displays the main user administration page.
	 */
	public function Users(): void {
		if ($this->auth())  {
			$h = new Humphree(Picnic::getInstance());

			$pagerData = Pager::ParsePagerDataFromQuery();
			$pagerData->totalItems = sizeof($h->getUsers(1, 1000000)); // temporary, until we get a countUsers() method.

			$view = new View();
			$view->SetData('navData', new NavData(NavData::Account));
			$view->SetData('users', $h->getUsers($pagerData->pageNumber, $pagerData->itemsPerPage));
			$view->SetData('pagerData', $pagerData);
			$view->Render('administration');
		} else {
			$this->handleUnauthorised();
		}
	}

	/**
	 * Displays a page for changing the given user's password.
	 *
	 * @param int $userID		The ID of the user whose password will be changed.
	 */
	public function ChangePassword(int $userID) {
		if ($this->auth()) {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				$view = new View();
				$view->SetData('navData', new NavData(NavData::Account));
				$view->SetData('userID', $userID);
				$view->Render('adminChangePassword');
				return;
			} else if (isset ($_POST ['update'])) {
				$this->doChangePassword($userID);
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	/**
	 * Displays a page allowing editing of the user with the given ID.
	 *
	 * @param $userID
	 * 			The ID of the user to be edited.
	 */
	public function EditUser(int $userID): void {
		if ($this->auth()) {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				$user = new User (Picnic::getInstance());
				$user->userID = $userID;

				if ($user->get()) {

					$userData = [
						'userID' => $user->userID,
						'user' => $user->user,
						'email' => $user->email,
						'status' => $user->status
					];

					$view = new View();
					$view->SetData('navData', new NavData(NavData::Account));
					$view->SetData('user', $userData);
					$view->Render('adminEditUser');
					return;
				}
			} else if (isset ($_POST ['update'])) {
				$this->updateUser($userID);
				return;
			}
		}

		header('Location: ' . BASE . '/Home');
	}

	/**
	 * Deletes the user with the given ID.
	 *
	 * @param $userID
	 * 			The ID of the user to be deleted.
	 */
	public function DeleteUser(int $userID): void {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());
			$h->deleteUser($userID);
			header('Location: ' . BASE . '/Administration/Users');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Blocks the user with the given ID.
	 *
	 * @param $userID
	 * 			The ID of the user to be blocked.
	 */
	public function BlockUser(int $userID): void {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());
			if (!$h->blockUser($userID)) {
				$_SESSION['error'] = 'Failed to block the user.';
			}
			header('Location: ' . BASE . '/Administration/Users');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Unblocks the user with the given ID.
	 *
	 * @param $userID
	 * 			The ID of the user to be unblocked.
	 */
	public function UnblockUser(int $userID): void {
		if ($this->auth()) {
			$h = new Humphree(Picnic::getInstance());
			if (!$h->unblockUser($userID)) {
				$_SESSION['error'] = 'Failed to unblock the user.';
			}

			header('Location: ' . BASE . '/Administration/Users');
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	public function ViewCategories(): void {
		$view = new AdminCategoriesView();
		$view->Render('adminCategories');
		return;
	}

	public function AddMajorCategory(): void {
		$h = new Humphree(Picnic::getInstance());
		$categoryName = $_POST['category'];
		$h->addCategory(Category::ROOT_CATEGORY, $categoryName);
		header('Location: ' . BASE . '/Administration/ViewCategories');
	}

	public function AddMinorCategory(int $parentCategoryID): void {
		$h = new Humphree(Picnic::getInstance());
		$categoryName = $_POST['category'];
		$h->addCategory($parentCategoryID, $categoryName);
		header('Location: ' . BASE . '/Administration/ViewCategories');
	}

	public function DeleteCategory(int $categoryID): void {
		$h = new Humphree(Picnic::getInstance());
		$h->deleteCategory($categoryID);
		header('Location: ' . BASE . '/Administration/ViewCategories');
	}

	public function System() {
		$view = new AdminSystemView();
		$view->Render('adminSystem');
		return;
	}

	public function RebuildDatabase()
	{
		DatabaseGenerator::Generate(Picnic::getInstance());
		DatabaseGenerator::Populate(Picnic::getInstance());

		$h = new Humphree(Picnic::getInstance());
		$h->runMatchingForAllItems();

		for ($i = 32591; $i < 33000; ++$i) {
			$paddedItemId = str_pad($i, 4, '0', STR_PAD_LEFT);
			$subDir = substr($paddedItemId, 0 , strlen($paddedItemId) - 3);

			$fullPath = ItemController::IMAGE_DIRECTORY . $subDir . "/" .$i . ".jpg";
			$thumbPath = ItemController::THUMB_DIRECTORY . $subDir . "/" .$i . ".jpg";

			if (file_exists($fullPath)) {
				unlink($fullPath);
			}

			if (file_exists($thumbPath)) {
				unlink($thumbPath);
			}

		}

		$view = new View();
		$view->SetData('navData',  new NavData(NavData::Account));
		$view->Render('createDB');
	}

	private function auth(){
		return isset($_SESSION[MODULE])
			&& isset($_SESSION['userID'])
			&& isset($_SESSION['status'])
			&& $_SESSION['userID'] > 0
			&& $_SESSION['status'] === 'admin';
	}

	/**
	 * Updates the user attributes if they are valid, then displays the main admin page.
	 * @param int $userID
	 */
	private function updateUser(int $userID): void {
		if ($this->auth()) {
			$user = new User (Picnic::getInstance());
			$v = new Validation ();

			// Process on submission of user.
			if (isset ($_POST ['user'])) {

				// Validate the user.
				try {
					$v->userName($_POST ['user']);
				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
				}

				if (isset ($_SESSION ['error'])) {
					unset ($_POST ['user']);
					header('Location: Administration');
				} else {
					$user->user = $_POST ['user'];
					unset ($_POST ['user']);

					// Validate the email.
					try {
						$v->email($_POST ['email']);
					} catch (ValidationException $e) {
						$_SESSION ['error'] = $e->getError();
					}

					if (isset ($_SESSION ['error'])) {
						unset ($_POST ['email']);
						header('Location: Administration');
					} else {
						$user->email = $_POST ['email'];
						unset ($_POST ['email']);
						$user->status = $_POST ['status'];
						unset ($_POST ['status']);
						$user->userID = $userID;
						try {
							$user->update();
						} catch (ModelException $e) {
							// temporary -- to catch the case where no change was made to the data, in which
							// case update throws due to now rows being modified.
						}


						header('Location: ' . BASE . '/Administration/Users');
					}
				}
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	/**
	 * Updates the user password if it is valid, then displays the main admin page.
	 * @param int $userID
	 */
	private function doChangePassword(int $userID): void {
		if ($this->auth()) {
			$user = new User (Picnic::getInstance());
			$v = new Validation ();

			if (isset ($_POST ['password1']) && isset ($_POST ['password2'])) {

				// Validate the password.
				try {
					$v->password($_POST ['password1']);
				} catch (ValidationException $e) {
					$_SESSION ['error'] = $e->getError();
				}

				if (isset ($_SESSION ['error'])) {
					unset ($_POST ['password1']);
					unset ($_POST ['password2']);
					header('Location: ' . BASE . '/Administration/ChangePassword/' . $userID);
				} else {

					// Compare passwords.
					try {
						$v->comparePasswords($_POST ['password1'], $_POST ['password2']);
					} catch (ValidationException $e) {
						$_SESSION ['error'] = $e->getError();
					}

					if (isset ($_SESSION ['error'])) {
						unset ($_POST ['password1']);
						unset ($_POST ['password2']);
						header('Location: Administration');
					} else {
						$user->password = $_POST ['password1'];
						unset ($_POST ['password1']);
						unset ($_POST ['password2']);
						$user->userID = $userID;
						$user->updatePassword();

						header('Location: ' . BASE . '/Administration/Users');
					}
				}
			}
		} else {
			header('Location: ' . BASE . '/Home');
		}
	}

	private function handleUnauthorised(): void {
		header('Location: ' . BASE . '/Home');
	}
}
