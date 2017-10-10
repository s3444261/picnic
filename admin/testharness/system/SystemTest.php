<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

/*
 * TEST SUMMARY
 * 
 * -- System.php Test Blocks: --
 * createAccount(User $user): bool
 * getUserIdByActivationCode( User $user ): int
 * activateAccount(User $user): bool
 * changePassword(User $user): bool
 * forgotPassword(User $user): User
 * addUser(User $user): bool
 * updateUser(User $user): bool
 * getUser(User $user): User
 * getUsers(int $pageNumber, int $usersPerPage): array
 * disableUser(User $user): bool
 * deleteUser(User $user): bool
 * addCategory(Category $category): bool
 * updateCategory(Category $category): bool
 * getCategory(Category $category): Category
 * getCategories(): array
 * getCategoriesIn(int $parentID): array
 * 
 * -- System.php Test SubBlocks: --
 * createAccount(User $user): bool
 * -- testcreateAccountNoUser(): void
 * -- testcreateAccountShortUser(): void
 * -- testcreateAccountExistUser(): void
 * -- testcreateAccountNoEmail(): void
 * -- testcreateAccountBadEmail(): void
 * -- testcreateAccountExistEmail(): void
 * -- testcreateAccountNoPassword(): void
 * -- testcreateAccountShortPassword(): void
 * -- testcreateAccountNoUpperPassword(): void
 * -- testcreateAccountNoLowerPassword(): void
 * -- testcreateAccountNoNumberPassword(): void
 * -- testcreateAccountSuccessful(): void
 * 
 * getUserIdByActivationCode( User $user ): int
 * -- testGetUserIdByActivationCodeInvalidCode(): void
 * -- testGetUserIdByActivationCodeValidCode(): void
 * 
 * activateAccount(User $user): bool
 * -- testActivateAccountInvalidId(): void
 * -- testActivateAccountValidId(): void
 * 
 * changePassword(User $user): bool
 * testChangePasswordNoPassword(): void
 * -- testChangePasswordShortPassword(): void
 * -- testChangePasswordNoUpperPassword(): void
 * -- testChangePasswordNoLowerPassword(): void
 * -- testChangePasswordNoNumberPassword(): void
 * -- testChangePasswordSuccessful(): void
 * 
 * forgotPassword(User $user): User
 * -- testforgotPasswordNoEmail(): void
 * -- testforgotPasswordBadEmail(): void
 * -- testforgotPasswordNotExistEmail(): void
 * -- testforgotPasswordExistEmail(): void
 * 
 * addUser(User $user): bool
 * -- testAddUserNoUser(): void
 * -- testAddUserShortUser(): void
 * -- testAddUserExistUser(): void
 * -- testAddUserNoEmail(): void
 * -- testAddUserBadEmail(): void
 * -- testAddUserExistEmail(): void
 * -- testAddUserNoPassword(): void
 * -- testAddUserShortPassword(): void
 * -- testAddUserNoUpperPassword(): void
 * -- testAddUserNoLowerPassword(): void
 * -- testAddUserNoNumberPassword(): void
 * -- testAddUserSuccessful(): void
 * 
 * updateUser(User $user): bool
 * -- testUpdateUserNoUserID(): void
 * -- testUpdateUserNoUser(): void
 * -- testUpdateUserShortUser(): void
 * -- testUpdateUserExistUser(): void
 * -- testUpdateUserNoEmail(): void
 * -- testUpdateUserBadEmail(): void
 * -- testUpdateUserExistEmail(): void
 * -- testUpdateUserNoStatus(): void
 * -- testUpdateUserSuccessful(): void
 * 
 * getUser(User $user): User
 * -- testGetUserNoUserID(): void
 * -- testGetUserInvalidUserID(): void
 * -- testGetUserValidUserID(): void
 * 
 * getUsers(int $pageNumber, int $usersPerPage): array
 * -- testGetUsersPageNumberZero(): void
 * -- testGetUsersUsersPerPageZero(): void
 * -- testGetUsersSuccess(): void
 * 
 * disableUser(User $user): bool
 * -- testDisableUserNoUserID(): void
 * -- testDisableUserInvalidUserID(): void
 * -- testDisableUserValidUserID(): void
 * 
 * deleteUser(User $user): bool
 * TO DO
 * 
 * addCategory(Category $category): bool
 * -- testAddCategoryNoParentId(): void
 * -- testAddCategoryInvalidParentId(): void
 * -- testAddCategoryNoCategory(): void
 * -- testAddCategorySuccess(): void
 * 
 * updateCategory(Category $category): bool
 * -- testUpdateCategoryNoCategoryId(): void
 * -- testUpdateCategoryInvalidCategoryId(): void
 * -- testUpdateCategoryNoParentId(): void
 * -- testUpdateCategoryInvalidParentId(): void
 * -- testUpdateCategoryNoCategory(): void
 * -- testUpdateCategoryParentId(): void
 * -- testUpdateCategoryCategory(): void
 * -- testUpdateCategoryAll(): void
 * 
 * deleteCategory(Category $category): bool
 * TO DO
 * 
 * getCategory(Category $category): Category
 * -- testGetCategoryNoCategoryId(): void
 * -- testGetCategoryInvalidCategoryId(): void
 * -- testGetCategoryValidCategoryId(): 
 * 
 * getCategories(): array
 * -- testGetCategories(): void
 * 
 * getCategoriesIn(int $parentID): array
 * 
 */
declare(strict_types=1);

if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}

require_once dirname(__FILE__) . '/../../testharness/unit/TestPDO.php';
require_once dirname(__FILE__) . '/../../createDB/DatabaseGenerator.php';
require_once dirname(__FILE__) . '/../../../model/System.php';
require_once dirname(__FILE__) . '/../../../model/Category.php';
require_once dirname(__FILE__) . '/../../../model/Categories.php';
require_once dirname(__FILE__) . '/../../../model/CategoryItems.php';
require_once dirname(__FILE__) . '/../../../model/Comment.php';
require_once dirname(__FILE__) . '/../../../model/Comments.php';
require_once dirname(__FILE__) . '/../../../model/Item.php';
require_once dirname(__FILE__) . '/../../../model/ItemComments.php';
require_once dirname(__FILE__) . '/../../../model/ItemNotes.php';
require_once dirname(__FILE__) . '/../../../model/Note.php';
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/UserItems.php';
require_once dirname(__FILE__) . '/../../../model/UserRatings.php';
require_once dirname(__FILE__) . '/../../../model/Users.php';
require_once dirname(__FILE__) . '/../../../model/Validation.php';
require_once dirname(__FILE__) . '/../../../model/CategoryException.php';
require_once dirname(__FILE__) . '/../../../model/CategoriesException.php';
require_once dirname(__FILE__) . '/../../../model/CommentException.php';
require_once dirname(__FILE__) . '/../../../model/ItemException.php';
require_once dirname(__FILE__) . '/../../../model/ItemCommentsException.php';
require_once dirname(__FILE__) . '/../../../model/ItemNotesException.php';
require_once dirname(__FILE__) . '/../../../model/NoteException.php';
require_once dirname(__FILE__) . '/../../../model/UserException.php';
require_once dirname(__FILE__) . '/../../../model/UsersException.php';
require_once dirname(__FILE__) . '/../../../model/UserRatingsException.php';
require_once dirname(__FILE__) . '/../../../model/ValidationException.php';

class SystemTest extends PHPUnit\Framework\TestCase {
	
	// User Parameters
	const USER_ID       = 'userID';
	const USER 			= 'user';
	const EMAIL         = 'email';
	const PASSWORD 		= 'password';
	const STATUS 		= 'status';
	const ACTIVATE 		= 'activate';
	
	// User Data
	const USER_ID_1 = 1;
	const USER_ONE = 'peter';
	const EMAIL_ADDRESS_ONE = 'peter@gmail.com';
	const PASSWORD_ONE = 'TestTest88';
	const USER_ID_2 = 2;
	const USER_TWO = 'mary';
	const EMAIL_ADDRESS_TWO = 'mary@gmail.com';
	const PASSWORD_TWO = 'TestTest77';
	const USER_ID_3 = 3;
	const USER_THREE = 'adrian';
	const EMAIL_ADDRESS_THREE = 'adrian@gmail.com';
	const PASSWORD_THREE = 'TestTest66';
	const USER_ADD = 'brian';
	const EMAIL_ADD = 'brian@gmail.com';
	const PASSWORD_ADD = 'TestTest22';
	const USER_SHORT = 'bri';
	const EMAIL_BAD = 'brian.gmail.com';
	const PASSWORD_SHORT = 'Test88';
	const PASSWORD_NO_UPPER = 'testtest88';
	const PASSWORD_NO_LOWER = 'TESTTEST88';
	const PASSWORD_NO_NUMBER = 'TestTestTest';
	const FAILED_TO_ADD_USER = 'Failed to add User.';
	const ERROR_USER_NOT_EXIST = 'User does not exist!';
	const ERROR_USER_ID_EMPTY = 'Input is required!';
	const ERROR_USER_EMPTY = 'Username Error: Input is required!';
	const ERROR_USER_SHORT = 'Username Error: Input must be atleast 4 characters in length!';
	const ERROR_USER_DUPLICATE = 'This user name is not available!';
	const ERROR_EMAIL_EMPTY = 'Email Error: Input is required!';
	const ERROR_EMAIL_INVALID = 'Email Error: Email address must be valid!';
	const ERROR_EMAIL_DUPLICATE = 'This email address is not available!';
	const ERROR_PASSWORD_EMPTY = 'Password Error: Input is required!';
	const ERROR_PASSWORD_INVALID = 'Password Error: Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!';
	const INVALID_ID = 200;
	const INVALID_ACTIVATION_CODE = 'ef4flslerlwldxl234lsdl3w';
	const ERROR_ACTIVATION_CODE = 'Failed to retrieve UserID!';
	const ERROR_ACTIVATION_CODE_SHORT = 'Activation code must the 32 characters in length!';
	const EMAIL_NOT_EXIST = 'bandicoot@gumtree.net';
	const STATUS_ADD = 'suspended';
	const STATUS_ACTIVE = 'active';
	const STATUS_SUSPENDED = 'suspended';
	const PAGE_NUMBER = 10;
	const USERS_PER_PAGE = 20;
	const PAGE_NUMBER_ZERO = 0;
	const USERS_PER_PAGE_ZERO = 0;
	const ERROR_ZERO = 'Number must be greater than zero!';
	
	const CATEGORY_ID   = 'categoryID';
	const PARENT_ID     = 'parentID';
	const CATEGORY_NAME = 'category';
	
	const CATEGORY_ID_1 = 1;
	const PARENT_ID_0 = 0;
	const CATEGORY_1 = 'Category';
	const CATEGORY_ID_2 = 2;
	const PARENT_ID_1 = 1;
	const PARENT_ID_2 = 2;
	const CATEGORY_2 = 'Category2';
	const CATEGORY_ID_3 = 3;
	const CATEGORY_3 = 'Category3';
	const PARENT_ID_3 = 3;
	const CATEGORY_ID_4 = 4;
	const CATEGORY_4 = 'Category4';
	const CATEGORY_ID_INVALID = 400;
	const PARENT_ID_INVALID = 300;
	const ERROR_CATEGORY_NOT_EXIST = 'The category does not exist!';
	const ERROR_CATEGORY_NOT_CREATED = 'The category was not created!';
	const ERROR_CATEGORY_NOT_UPDATED = 'The category was not updated!';
	const ERROR_PARENT_ID_NONE = 'Input is required!';
	const ERROR_PARENT_ID_NOT_EXIST = 'The parent category does not exist!';
	const ERROR_PARENT_ID_INVALID = 'Input is required!';
	const ERROR_CATEGORY_NONE = 'Input is required!';
	
	const ROOT_CATEGORY = 0;
	const ROOT_CATEGORY_NAME = 'Category';
	const PARENT_ID_NOT_EXIST = 'The parentID does not exist!';
	
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);
		
		// Insert a root category
		$root = new Category($pdo);
		$root->{self::PARENT_ID} = self::PARENT_ID_0;
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		try {
			$root->set();
		} catch (CategoryException $e) {}
		
		// Insert additional categories
		$c = new Category($pdo);
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		try {
			$c->set();
		} catch (CategoryException $e) {}
		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		try {
			$c->set();
		} catch (CategoryException $e) {}
		
		$args1 = [
				self::USER 			=> self::USER_ONE,
				self::EMAIL 		=> self::EMAIL_ADDRESS_ONE,
				self::PASSWORD		=> self::PASSWORD_ONE
		];
		
		$args2 = [
				self::USER 			=> self::USER_TWO,
				self::EMAIL 		=> self::EMAIL_ADDRESS_TWO,
				self::PASSWORD		=> self::PASSWORD_TWO
		];
		
		$args3 = [
				self::USER 			=> self::USER_THREE,
				self::EMAIL 		=> self::EMAIL_ADDRESS_THREE,
				self::PASSWORD		=> self::PASSWORD_THREE
		];
		
		// Populate the Users table.
		
		for($i = 1; $i < 4; $i++){
			${'u' . $i} = new User($pdo, ${'args' . $i});
			try {
				${'u' . $i}->set();
			} catch (UserException $e) { }
			try {
				${'u' . $i}->get();
			} catch (UserException $e) { }
			${'u' . $i}->activate();
		}	
	}
	
	protected function populateCategories(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);
		
		// Insert a root category
		$root = new Category($pdo);
		$root->{self::PARENT_ID} = self::ROOT_CATEGORY;
		$root->{self::CATEGORY_NAME} = self::ROOT_CATEGORY_NAME;
		try {
			$root->set();
		} catch (CategoryException $e) {}
		
		// Insert additional categories
		$j = 1;
		for($i = 1; $i <= 99; $i++){
			if($i % 3 == 0){
				$j++;
			}
			$c = new Category($pdo);
			$c->{self::PARENT_ID} = $j;
			$c->{self::CATEGORY_NAME} = 'cat' . $i;
			try {
				$c->set();
			} catch (CategoryException $e) {}
		}
	}
	
	protected function populateUsers(): void {
		
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);
		
		// Insert a root category
		$root = new Category($pdo);
		$root->{self::PARENT_ID} = self::PARENT_ID_0;
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		try {
			$root->set();
		} catch (CategoryException $e) {}
		
		// Insert additional categories
		$c = new Category($pdo);
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		try {
			$c->set();
		} catch (CategoryException $e) {}
		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		try {
			$c->set();
		} catch (CategoryException $e) {}
		
		// Populate the Users table.
		for($i = 1; $i <= 400; $i++){
			${'u' . $i} = new User($pdo, [self::USER => 'user' . $i, self::EMAIL => 'email' . $i . '@gmail.com', self::PASSWORD => 'PassWord' . $i]);
			try {
				${'u' . $i}->set();
			} catch (UserException $e) {}
			try {
				${'u' . $i}->get();
			} catch (UserException $e) { }
			try {
				${'u' . $i}->activate();
			} catch (UserException $e) {}
		}
	}
	
	protected function populateAll(): void {
		
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);
		
		// Populate the Categories Table
		
		// Insert a root category
		$root = new Category($pdo);
		$root->{self::PARENT_ID} = self::PARENT_ID_0;
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		try {
			$root->set();
		} catch (CategoryException $e) {}
		
		// Insert additional categories
		$c = new Category($pdo);
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		try {
			$c->set();
		} catch (CategoryException $e) {}
		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		try {
			$c->set();
		} catch (CategoryException $e) {}
		
		// Populate the Users table.
		
		for($i = 1; $i <= 3; $i++){
			${'u' . $i} = new User($pdo, [self::USER => 'user' . $i, self::EMAIL => 'email' . $i . '@gmail.com', self::PASSWORD => 'PassWord' . $i]);
			try {
				${'u' . $i}->set();
			} catch (UserException $e) {}
			try {
				${'u' . $i}->get();
			} catch (UserException $e) { }
			try {
				${'u' . $i}->activate();
			} catch (UserException $e) {}
		}
	}
	
	/*
	 * createAccount() Tests
	 */
	
	public function testcreateAccountNoUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => NULL, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountShortUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_SHORT, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_SHORT, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountExistUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_TWO, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_DUPLICATE, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountNoEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => NULL, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountBadEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_BAD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_INVALID, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountExistEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADDRESS_TWO, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_DUPLICATE, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountNoPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => NULL]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountShortPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_SHORT]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountNoUpperPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_UPPER]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountNoLowerPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_LOWER]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountNoNumberPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_NUMBER]);
		$this->assertFalse($system->createAccount($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testcreateAccountSuccessful(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertTrue($system->createAccount($sut));
	}
	
	/*
	 * getUserIdByActivationCode() Test
	 */
	
	public function testGetUserIdByActivationCodeInvalidCode(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->set();
			$sut->userID = $sut->get();
		} catch (UserException $e) {}
		$sut->activate = self::INVALID_ACTIVATION_CODE;
		$system->getUserIdByActivationCode($sut);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_ACTIVATION_CODE_SHORT, $_SESSION['error']);
		} else {
			$this->assertFalse(true);
		}
	}
	
	public function testGetUserIdByActivationCodeValidCode(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->set();
			$sut->userID = $sut->get();
		} catch (UserException $e) {}
		$this->assertGreaterThan(0, $system->getUserIdByActivationCode($sut));
	}
	
	/*
	 * activateAccount() Test
	 */
	
	public function testActivateAccountInvalidId(): void{
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->userID = $sut->set();
		} catch (UserException $e) {
			$this->assertFalse(true);
		}
		try {
			$sut->get();
		} catch (UserException $e) {
			$this->assertFalse(true);
		}
		$sut->userID = self::INVALID_ID;
		$this->expectExceptionMessage(self::ERROR_USER_NOT_EXIST);
		$system->activateAccount($sut);
	}
	
	public function testActivateAccountValidId(): void{
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->userID = $sut->set();
		} catch (UserException $e) {
			$this->assertFalse(true);
		}
		try {
			$sut->get();
		} catch (UserException $e) {
			$this->assertFalse(true);
		}
		$this->assertTrue($system->activateAccount($sut));
	}
		
	/*
	 * Test changePassword()
	 */
	
	public function testChangePasswordNoPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => NULL]);
		$this->assertFalse($system->changePassword($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testChangePasswordShortPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_SHORT]);
		$this->assertFalse($system->changePassword($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testChangePasswordNoUpperPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_NO_UPPER]);
		$this->assertFalse($system->changePassword($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testChangePasswordNoLowerPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_NO_LOWER]);
		$this->assertFalse($system->changePassword($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testChangePasswordNoNumberPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_NO_NUMBER]);
		$this->assertFalse($system->changePassword($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testChangePasswordSuccessful(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->get();
		} catch (UserException $e) {}
		$oldPassword = $sut->password;
		$sut->password = self::PASSWORD_ADD;
		$this->assertTrue($system->changePassword($sut));
		try {
			$sut->get();
		} catch (UserException $e) {}
		$newPassword = $sut->get()->password;
		if(strlen($oldPassword) > 0 && strlen($newPassword) > 0 && $oldPassword != $newPassword){
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
	}
	
	/*
	 * Test forgotPassword()
	 */
	
	public function testforgotPasswordNoEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::EMAIL => NULL]);
		$sut = $system->forgotPassword($sut);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testforgotPasswordBadEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::EMAIL => self::EMAIL_BAD]);
		$sut = $system->forgotPassword($sut);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_INVALID, $_SESSION['error']);
		}
	}
	
	public function testforgotPasswordNotExistEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::EMAIL => self::EMAIL_BAD]);
		$sut = $system->forgotPassword($sut);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_INVALID, $_SESSION['error']);
		}
	}
	
	public function testforgotPasswordExistEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::EMAIL => self::EMAIL_ADDRESS_TWO]);
		$sut = $system->forgotPassword($sut); 
		$this->assertEquals(2, $sut->userID);
		$this->assertEquals(self::EMAIL_ADDRESS_TWO, $sut->email); 
		$this->assertEquals(10, strlen($sut->password)); 
	}
	
	
	/*
	 * AddUser() Tests
	 */
	
	public function testAddUserNoUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => NULL, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testAddUserShortUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_SHORT, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_SHORT, $_SESSION['error']);
		}
	}
	
	public function testAddUserExistUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_TWO, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_DUPLICATE, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => NULL, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testAddUserBadEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_BAD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_INVALID, $_SESSION['error']);
		}
	}
	
	public function testAddUserExistEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADDRESS_TWO, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_DUPLICATE, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => NULL]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testAddUserShortPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_SHORT]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoUpperPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_UPPER]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoLowerPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_LOWER]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoNumberPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_NUMBER]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PASSWORD_INVALID, $_SESSION['error']);
		}
	}
	
	public function testAddUserSuccessful(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertTrue($system->addUser($sut));
	}
	
	/*
	 * UpdateUser() Tests
	 */
	
	public function testUpdateUserNoUserID(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->assertFalse($system->updateUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_ID_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testUpdateUserNoUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => NULL, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->assertFalse($system->updateUser($sut)); 
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_EMPTY, $_SESSION['error']);
		}
		
	}
	
	public function testUpdateUserShortUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_SHORT, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->assertFalse($system->updateUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_SHORT, $_SESSION['error']);
		}
	}
	
	public function testUpdateUserExistUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_TWO, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->assertFalse($system->updateUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_DUPLICATE, $_SESSION['error']);
		}
	}
	
	public function testUpdateUserNoEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => NULL, self::STATUS => self::STATUS_ADD]);
		$this->assertFalse($system->updateUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_EMPTY, $_SESSION['error']);
		}
	}
	
	public function testUpdateUserBadEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_BAD, self::STATUS => self::STATUS_ADD]);
		$this->assertFalse($system->updateUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_INVALID, $_SESSION['error']);
		}
	}
	
	public function testUpdateUserExistEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADDRESS_TWO, self::STATUS => self::STATUS_ADD]);
		$this->assertFalse($system->updateUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_EMAIL_DUPLICATE, $_SESSION['error']);
		}
	}
	
	public function testUpdateUserNoStatus(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->assertTrue($system->updateUser($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertEquals(self::STATUS_ADD, $sut->status);
	}
	
	public function testUpdateUserSuccessful(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->assertTrue($system->updateUser($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertEquals(self::USER_ADD, $sut->user);
		$this->assertEquals(self::EMAIL_ADD, $sut->email);
		$this->assertEquals(self::STATUS_ADD, $sut->status);
	}
	
	/*
	 * getUser(User $user): User
	 */
	
	public function testGetUserNoUserID(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo);
		$sut = $system->getUser($sut);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_ID_EMPTY, $_SESSION['error']);
		} else {
			$this->assertTrue(false);
		}
	}
	
	public function testGetUserInvalidUserID(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo);
		$sut->userID = self::INVALID_ID;
		$sut = $system->getUser($sut);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_USER_NOT_EXIST, $_SESSION['error']);
		} else {
			$this->assertTrue(false);
		}
	}
	
	public function testGetUserValidUserID(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo);
		$sut->userID = self::USER_ID_1;
		$sut = $system->getUser($sut);
		$this->assertEquals(self::USER_ONE, $sut->user);
		$this->assertEquals(self::EMAIL_ADDRESS_ONE, $sut->email);
		$this->assertEquals(self::STATUS_ACTIVE, $sut->status);
	}
	
	/*
	 * getUsers(int $pageNumber, int $usersPerPage): array
	 */
	public function testGetUsersPageNumberZero(): void {
		$this->populateUsers();
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$system->getUsers(self::PAGE_NUMBER_ZERO, self::USERS_PER_PAGE);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_ZERO, $_SESSION['error']);
		}
	}
	
	public function testGetUsersUsersPerPageZero(): void {
		$this->populateUsers();
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$system->getUsers(self::PAGE_NUMBER, self::USERS_PER_PAGE_ZERO);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_ZERO, $_SESSION['error']);
		}
	}
	
	public function testGetUsersSuccess(): void {
		$this->populateUsers();
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		try {
		 	$users = $system->getUsers(self::PAGE_NUMBER, self::USERS_PER_PAGE);
		 	
		 	$pn = self::PAGE_NUMBER;
		 	$upp = self::USERS_PER_PAGE;
		 	
		 	$start = ($pn - 1)*$upp + 1; 
		 	
		 	foreach($users as $user){
		 		$this->assertEquals($start, $user->userID);
		 		$this->assertEquals('user' . $start, $user->user);
		 		$this->assertEquals('email' . $start . '@gmail.com', $user->email);
		 		$start++;
		 	}
		 	
		} catch (UsersException $e) {}	 
	}
	
	/*
	 * disableUser(User $user): bool
	 */
	
	public function testDisableUserNoUserID(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo);
		if($system->disableUser($sut)){
			$this->assertTrue(false);
		} else {
			$this->assertTrue(true);
		}
	}
	
	public function testDisableUserInvalidUserID(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo);
		$sut->userID = self::INVALID_ID;
		if($system->disableUser($sut)){
			$this->assertTrue(false);
		} else {
			$this->assertTrue(true);
		}
	}
	
	public function testDisableUserValidUserID(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo);
		$sut->userID = self::USER_ID_1;
		
		if($system->disableUser($sut)){
			$sut = $system->getUser($sut);
			$this->assertEquals(self::STATUS_SUSPENDED, $sut->status);
		} else {
			$this->assertTrue(false);
		} 
	}
	
    /*
	 * deleteUser(User $user): bool
	 * Will require a large number of tests once validation of all
	 * other classes has been completed.
	 */
	
    /*
     * addCategory(Category $category): bool
     */
	public function testAddCategoryNoParentId(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertFalse($system->addCategory($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PARENT_ID_NONE, $_SESSION['error']);
		}
	}
	
	public function testAddCategoryInvalidParentId(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::PARENT_ID => self::PARENT_ID_INVALID, self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertFalse($system->addCategory($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_PARENT_ID_NOT_EXIST, $_SESSION['error']);
		}
	}
	
	public function testAddCategoryNoCategory(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::PARENT_ID => self::PARENT_ID_1]);
		$this->assertFalse($system->addCategory($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_CATEGORY_NONE, $_SESSION['error']);
		}
	}
	
	public function testAddCategorySuccess(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::PARENT_ID => self::PARENT_ID_1, self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertTrue($system->addCategory($sut));
	}
	
	/*
	 * updateCategory(Category $category): bool
	 */
	
	public function testUpdateCategoryNoCategoryId(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::PARENT_ID => self::PARENT_ID_1, self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertFalse($system->updateCategory($sut));
	}
	
	public function testUpdateCategoryInvalidCategoryId(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_INVALID, self::PARENT_ID => self::PARENT_ID_1, self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertFalse($system->updateCategory($sut));
	}
	
	public function testUpdateCategoryNoParentId(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_3, self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertTrue($system->updateCategory($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertSame('1', $sut->parentID);
		$this->assertSame(self::CATEGORY_4, $sut->category);
	}
	
	public function testUpdateCategoryInvalidParentId(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_3, self::PARENT_ID => self::PARENT_ID_INVALID, self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertTrue($system->updateCategory($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertSame('1', $sut->parentID);
		$this->assertSame(self::CATEGORY_4, $sut->category);
	}
	
	public function testUpdateCategoryNoCategory(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_3, self::PARENT_ID => self::PARENT_ID_2]);
		$this->assertTrue($system->updateCategory($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertSame('2', $sut->parentID);
		$this->assertSame(self::CATEGORY_3, $sut->category);
	}
	
	public function testUpdateCategoryParentId(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_3, self::PARENT_ID => self::PARENT_ID_2, self::CATEGORY_NAME => self::CATEGORY_3]);
		$this->assertTrue($system->updateCategory($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertSame('2', $sut->parentID);
		$this->assertSame(self::CATEGORY_3, $sut->category);
	}
	
	public function testUpdateCategoryCategory(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_3, self::PARENT_ID => self::PARENT_ID_1, self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertTrue($system->updateCategory($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertSame('1', $sut->parentID);
		$this->assertSame(self::CATEGORY_4, $sut->category);
	}
	
	public function testUpdateCategoryAll(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_3, self::PARENT_ID => self::PARENT_ID_2, self::CATEGORY_NAME => self::CATEGORY_4]);
		$this->assertTrue($system->updateCategory($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertSame('2', $sut->parentID);
		$this->assertSame(self::CATEGORY_4, $sut->category);
	}
	
	/*
	 * deleteCategory(Category $category): bool
	 * TO DO
	 */
	
	/*
	 * getCategory(Category $category): Category
	 */
	
	public function testGetCategoryNoCategoryId(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo);
		$category = $system->getCategory($sut);
		$this->assertSame(0, $category->categoryID);
		$this->assertSame(0, $category->parentID);
		$this->assertEmpty($category->category);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_CATEGORY_NOT_EXIST, $_SESSION['error']);
		}
	}
	
	public function testGetCategoryInvalidCategoryId(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_INVALID]);
		$category = $system->getCategory($sut);
		$this->assertSame('400', $category->categoryID);
		$this->assertSame(0, $category->parentID);
		$this->assertEmpty($category->category);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::ERROR_CATEGORY_NOT_EXIST, $_SESSION['error']);
		}
	}
	
	public function testGetCategoryValidCategoryId(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new Category($pdo, [self::CATEGORY_ID => self::CATEGORY_ID_3]);
		$category = $system->getCategory($sut);
		$this->assertSame('3', $category->categoryID);
		$this->assertSame('1', $category->parentID);
		$this->assertSame(self::CATEGORY_3, $category->category);
	}
	
	/*
	 * getCategories(): array
	 */
	
	public function testGetCategories(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$this->populateCategories();
		$cats = $system->getCategories();
		$i = 1;
		$j = 0;
		foreach($cats as $cat){
			if($i == 1){
				$this->assertEquals($i, $cat->categoryID);
				$this->assertEquals($j, $cat->parentID);
				$this->assertEquals('Category', $cat->category);
				$j++;
			} else {
				$number = $i - 1;
				$this->assertEquals($i, $cat->categoryID);
				$this->assertEquals($j, $cat->parentID);
				$this->assertSame('cat' . $number, $cat->category);
			}
			if($i % 3 == 0){
				$j++;
			}
			$i++;
		}
	}
	
	/*
	 * getCategoriesIn(int $parentID): array
	 */
	public function testGetCategoriesInParentIdInvalid(): void {
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$cats = $system->getCategoriesIn(self::INVALID_ID);
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::PARENT_ID_NOT_EXIST, $_SESSION['error']);
		}
	}
	
	public function testGetCategoriesInParentIdValid(): void {
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$this->populateCategories();
		$cats = $system->getCategoriesIn(4);
		$i = 10;
		foreach($cats as $cat){
			$number = $i - 1;
			$this->assertEquals($i, $cat->categoryID);
			$this->assertEquals(4, $cat->parentID);
			$this->assertSame('cat' . $number, $cat->category);
			$i++;
		}
	}
	
	
}
