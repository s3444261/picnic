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
     * Displays the user administration page.
     */
    public function Users(): void {
        $this->verifyAuthorization();

        $view = new AdminUsersView();
        $view->Render('adminUsers');
    }

    /**
     * Displays the category administration page.
     */
    public function ViewCategories(): void {
        $this->verifyAuthorization();

        $view = new AdminCategoriesView();
        $view->Render('adminCategories');
    }

    /**
     * Displays the system administration page.
     */
    public function System(): void {
        $this->verifyAuthorization();

        $view = new AdminSystemView();
        $view->Render('adminSystem');
    }

    /**
     * Displays a page allowing editing of the user with the given ID.
     *
     * @param $userID
     *             The ID of the user to be edited.
     */
    public function EditUser(int $userID): void {
        $this->verifyAuthorization();
        $this->verifyValidUserId($userID);

        if (isset($_POST['update'])) {
            $this->updateUser($userID);
        } else {
            $view = new AdminEditUserView($userID);
            $view->Render('adminEditUser');
        }
    }

    /**
     * Deletes the user with the given ID.
     *
     * @param $userID
     *             The ID of the user to be deleted.
     */
    public function DeleteUser(int $userID): void {
        $this->verifyAuthorization();

        $h = new Humphree(Picnic::getInstance());
        $h->deleteUser($userID);
        header('Location: ' . BASE . '/Administration/Users');
    }

    /**
     * Blocks the user with the given ID.
     *
     * @param $userID
     *             The ID of the user to be blocked.
     */
    public function BlockUser(int $userID): void {
        $this->verifyAuthorization();

        $h = new Humphree(Picnic::getInstance());
        $h->blockUser($userID);
        header('Location: ' . BASE . '/Administration/Users');
    }

    /**
     * Unblocks the user with the given ID.
     *
     * @param $userID
     *             The ID of the user to be unblocked.
     */
    public function UnblockUser(int $userID): void {
        $this->verifyAuthorization();

        $h = new Humphree(Picnic::getInstance());
        $h->unblockUser($userID);
        header('Location: ' . BASE . '/Administration/Users');
    }

    /**
     * Displays a page for changing the given user's password.
     *
     * @param int $userID        The ID of the user whose password will be changed.
     */
    public function ChangePassword(int $userID): void {
        $this->verifyAuthorization();
        $this->verifyValidUserId($userID);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $view = new View();
            $view->SetData('navData', new NavData(NavData::Account));
            $view->SetData('userID', $userID);
            $view->Render('adminChangePassword');
        } else if (isset($_POST['update'])) {
            $this->doChangePassword($userID);
        } else {
            header('Location: ' . BASE . '/Administration/Users');
        }
    }

    /**
     * Adds a new top-level item category.
     */
    public function AddMajorCategory(): void {
        $this->verifyAuthorization();

        if (isset($_POST['category'])) {
            $h = new Humphree(Picnic::getInstance());
            $categoryName = $_POST['category'];
            $h->addCategory(Category::ROOT_CATEGORY, $categoryName);
        }
        header('Location: ' . BASE . '/Administration/ViewCategories');
    }

    /**
     * Adds a new second-level item category.
     *
     * @param int $parentCategoryID        The ID of the top-level category under
     *                                     which the new category will be created.
     */
    public function AddMinorCategory(int $parentCategoryID): void {
        $this->verifyAuthorization();

        if (isset($_POST['category'])) {
            $h = new Humphree(Picnic::getInstance());
            $categoryName = $_POST['category'];
            $h->addCategory($parentCategoryID, $categoryName);
        }
        header('Location: ' . BASE . '/Administration/ViewCategories');
    }

    /**
     * Deletes the category with the given ID.
     *
     * @param int $categoryID    The ID of the category to be deleted.
     */
    public function DeleteCategory(int $categoryID): void {
        $this->verifyAuthorization();

        $h = new Humphree(Picnic::getInstance());
        $h->deleteCategory($categoryID);
        header('Location: ' . BASE . '/Administration/ViewCategories');
    }

    /**
     * Deletes the database and recreates it with default content.
     */
    public function RebuildDatabase() {
        $this->verifyAuthorization();

        echo 'Creating database...<br />';
        flush();

        DatabaseGenerator::Generate(Picnic::getInstance());

        echo 'Populating database...<br />';
        flush();

        DatabaseGenerator::Populate(Picnic::getInstance());

        echo 'Generating full-text indexes...<br />';
        flush();

        DatabaseGenerator::CreateFullTextIndex(Picnic::getInstance());

        echo 'Matching items...<br />';
        flush();

        $h = new Humphree(Picnic::getInstance());
        $h->runMatchingForAllItems();

        echo 'Deleting unused images...<br />';
        flush();

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

    /**
     * Updates the user attributes if they are valid, then displays the main admin page.
     *
     * @param int $userID    The ID of the user to be updated.
     */
    private function updateUser(int $userID): void {
        if ($this->validatePostedUserInfo()) {
            $h = new Humphree(Picnic::getInstance());
            $user = $h->getUser($userID);
            $user['user'] = $_POST ['user'];
            $user['email'] = $_POST ['email'];
            $user['status'] = $_POST ['status'];

            try {
                $h->updateUser($user);
            } catch (ModelException $e) {
                // temporary -- to catch the case where no change was made to the data, in which
                // case update throws due to no rows being modified.
            }

            header('Location: ' . BASE . '/Administration/Users');
        } else {
            header('Location: ' . BASE . '/Administration/EditUser/' . $userID);
        }
    }

    /**
     * Updates the user password if it is valid. Displays the main user admin page
     * if successful, otherwise returns to the change password page.
     *
     * @param int $userID    The ID of the user to be updated.
     */
    private function doChangePassword(int $userID): void {
        $user = new User (Picnic::getInstance());
        $v = new Validation ();

        if (isset ($_POST ['password1']) && isset ($_POST ['password2'])) {
            try {
                $v->password($_POST ['password1']);
                $v->comparePasswords($_POST ['password1'], $_POST ['password2']);
            } catch (ValidationException $e) {
                $_SESSION ['error'] = $e->getError();
                header('Location: ' . BASE . '/Administration/ChangePassword/' . $userID);
                die();
            }

            $user->password = $_POST ['password1'];
            unset ($_POST ['password1']);
            unset ($_POST ['password2']);
            $user->userID = $userID;
            $user->updatePassword();
        }

        header('Location: ' . BASE . '/Administration/Users');
    }

    /**
     * Validates that all posted user data is valid.
     *
     * @return bool        True if the data is valid, false otherwise.
     */
    private function validatePostedUserInfo(): bool
    {
        try {
            $v = new Validation ();

            if (!isset($_POST['user'])) {
                throw new ValidationException('Name cannot be empty.');
            }

            $v->userName($_POST['user']);

            if (!isset($_POST['email'])) {
                throw new ValidationException('Email cannot be empty.');
            }

            $v->email($_POST ['email']);

            if (!isset($_POST['email'])) {
                throw new ValidationException('Email cannot be empty.');
            }

            $v->email($_POST ['email']);

            return true;
        } catch (ValidationException $e) {
            $_SESSION ['error'] = $e->getError();
            return false;
        }
    }

    /**
     * Confirms that the current user is logged in as an administrator. If they are not,
     * they are redirected to the home page.
     */
    private function verifyAuthorization(): void{
        if (!isset($_SESSION[MODULE])
            || !isset($_SESSION['userID'])
            || !isset($_SESSION['status'])
            || $_SESSION['userID'] <= 0
            || $_SESSION['status'] !== 'admin') {
            header('Location: ' . BASE . '/Home');
            die();
        }
    }

    /**
     * Confirms the a user exits with the given ID. If not, the user is redirected
     * to the admin users page.
     *
     * @param int $userID    The user ID to be tested.
     */
    private function verifyValidUserId(int $userID): void {
        $h = new Humphree(Picnic::getInstance());
        if (!$h->isValidUserID($userID)) {
            header('Location: ' . BASE . '/Administration/Users');
            die();
        }
    }
}
