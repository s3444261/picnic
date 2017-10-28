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
 * deleteCategory(Category $category): bool
 * countCategoryItems(Category $category): int
 * countItemComments(Item $item): int
 * countItemNotes(Item $item): int
 * getCategoryItemsByPage(Category $category, int $pageNumber, int $itemsPerPage, string $status): array
 * countUserItems(User $user): int
 * getUserItems(User $user): array
 * getItem(Item $item): Item
 * addItem(User $user, Item $item): bool
 * updateItem(Item $item): bool
 * deleteItem(Item $item): bool
 * getItemComments(Item $item): array
 * getItemComment(Comment $comment): Item
 * addItemComment(Item $item, Comment $comment): bool
 * updateItemComment(Comment $comment): bool
 * deleteItemComment(Comment $comment): bool
 * deleteItemComments(Item $item): bool
 * getItemNotes(Item $item): array
 * getItemNote(Note $note): Note
 * addItemNote(Item $item, Note $note): bool
 * updateItemNote(Note $note): bool
 * deleteItemNote(Note $note): bool
 * getItemOwner(Item $item): array
 * addSellerRating(UserRatings $sellerRating): UserRatings
 * addBuyerRating(UserRatings $buyerRating): bool
 * getUserRatings(int $userID): array
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
 * -- testChangePasswordNoPassword(): void
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
 * -- testDeleteUserUserIdEmpty(): void
 * -- testDeleteUserUserIdInvalid(): void
 * -- testDeleteUserUserIdValid(): void
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
 * -- testDeleteCategoryCategoryIdEmpty(): void
 * -- testDeleteCategoryCategoryIdInvalid(): void
 * -- testDeleteCategoryCategoryIdValid(): void
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
 * -- testGetCategoriesInParentIdInvalid(): void
 * -- testGetCategoriesInParentIdValid(): void
 *
 * deleteCategory(Category $category): bool
 * -- testDeleteCategoryCategoryIdEmpty(): void
 * -- testDeleteCategoryCategoryIdInvalid(): void
 * -- testDeleteCategoryCategoryIdValid(): void
 *
 * countCategoryItems(Category $category): int
 * -- testCountCategoryItemsCategoryIDEmpty(): void
 * -- testCountCategoryItemsCategoryIDInvalid(): void
 * -- testCountCategoryItemsCategoryIDValid(): void
 *
 * countItemComments(Item $item): int
 * -- testCountItemCommentsItemIdEmpty(): void
 * -- testCountItemCommentsItemIdInvalid(): void
 * -- testCountItemCommentsItemIdValid(): void
 *
 * countItemNotes(Item $item): int
 * -- testCountItemNotesItemIdEmpty(): void
 * -- testCountItemNotesItemIdInvalid(): void
 * -- testCountItemNotesItemIdValid(): void
 *
 * getCategoryItemsByPage(Category $category, int $pageNumber , int $itemsPerPage): array
 * -- testGetCategoryItemsByPagePageNumberZero(): void
 * -- testGetCategoryItemsByPageCategoryItemsPerPageZero(): void
 * -- testGetCategoryItemsByPageSuccess(): void
 *
 * countUserItems(User $user): int
 * -- testCountUserItemsUserIdEmpty(): void
 * -- testCountUserItemsUserIdInvalid(): void
 * -- testCountUserItemsUserIdValid(): void
 *
 * getUserItems(User $user): array
 * -- testGetUserItemsUserIdEmpty(): void
 * -- testGetUserItemsUserIdInvalid(): void
 * -- testGetUserItemsUserIdValid(): void
 *
 * getItem(Item $item): Item
 * -- testGetItemNoItemId(): void
 * -- testGetItemInvalidItemId(): void
 * -- testGetItemValidItemId():
 *
 * addItem(User $user, Item $item): bool
 * -- testAddItemUserIdEmptyItemValid(): void
 * -- testAddItemUserIdInvalidItemValid(): void
 * -- testAddItemUserIdValidItemEmpty(): void
 * -- testAddItemUserIdValidItemValid(): void
 *
 * updateItem(Item $item): bool
 * -- testUpdateItemNoItemId(): void
 * -- testUpdateItemInvalidItemId(): void
 * -- testUpdateItemEmptyItem(): void
 * -- testUpdateItemSuccess(): void
 *
 * deleteItem(Item $item): bool
 * -- testDeleteItemItemIdEmpty(): void
 * -- testDeleteItemItemIdInvalid(): void
 * -- testDeleteItemItemIdValid(): void
 *
 * getItemComments(Item $item): array
 * -- testGetItemCommentsItemIdEmpty(): void
 * -- testGetItemCommentsItemIdInvalid(): void
 * -- testGetItemCommentsItemIdValid(): void
 *
 * getItemComment(Comment $note): Comment
 * -- testGetItemCommentCommentIdEmpty(): void
 * -- testGetItemCommentCommentIdInvalid(): void
 * -- testGetItemCommentCommentIdValid(): void
 *
 * addItemComment(Item $item, Comment $note): bool
 * -- testAddItemCommentEmpty(): void
 * -- testAddItemCommentInvalidItemId(): void
 * -- testAddItemCommentInvalidCommentId(): void
 * -- testAddItemCommentExistingCommentId(): void
 * -- testAddItemCommentSuccess(): void
 *
 * updateItemComment(Comment $note): bool
 * -- testUpdateItemCommentsNoItemCommentsId(): void
 * -- testUpdateItemCommentsInvalidItemCommentsId(): void
 * -- testUpdateItemCommentsInvalidItemId(): void
 * -- testUpdateItemCommentsInvalidCommentId(): void
 * -- testUpdateItemCommentsExistingCommentId(): void
 * -- testUpdateItemCommentsSuccess(): void
 *
 * deleteItemComment(Comment $note): bool
 * -- testDeleteCommentCommentIdEmpty(): void
 * -- testDeleteCommentCommentIdInvalid(): void
 * -- testDeleteCommentCommentIdValid(): void
 *
 * deleteItemComments(Item $item): bool
 * -- testDeleteItemCommentsItemIdEmpty(): void
 * -- testDeleteItemCommentsItemIdInvalid(): void
 * -- testDeleteItemCommentsItemIdValid(): void
 *
 * getItemNotes(Item $item): array
 * -- testGetItemNotesItemIdEmpty(): void
 * -- testGetItemNotesItemIdInvalid(): void
 * -- testGetItemNotesItemIdValid(): void
 *
 * getItemNote(Note $note): Note
 * -- testGetItemNoteNoteIdEmpty(): void
 * -- testGetItemNoteNoteIdInvalid(): void
 * -- testGetItemNoteNoteIdValid(): void
 *
 * addItemNote(Item $item, Note $note): bool
 * -- testAddItemNoteEmpty(): void
 * -- testAddItemNoteInvalidItemId(): void
 * -- testAddItemNoteInvalidNoteId(): void
 * -- testAddItemNoteExistingNoteId(): void
 * -- testAddItemNoteSuccess(): void
 *
 * updateItemNote(Note $note): bool
 * -- testUpdateItemNotesNoItemNotesId(): void
 * -- testUpdateItemNotesInvalidItemNotesId(): void
 * -- testUpdateItemNotesInvalidItemId(): void
 * -- testUpdateItemNotesInvalidNoteId(): void
 * -- testUpdateItemNotesExistingNoteId(): void
 * -- testUpdateItemNotesSuccess(): void
 *
 * deleteItemNote(Note $note): bool
 * -- testDeleteNoteNoteIdEmpty(): void
 * -- testDeleteNoteNoteIdInvalid(): void
 * -- testDeleteNoteNoteIdValid(): void
 *
 * deleteItemNotes(Item $item): bool
 * -- testDeleteItemNotesItemIdEmpty(): void
 * -- testDeleteItemNotesItemIdInvalid(): void
 * -- testDeleteItemNotesItemIdValid(): void
 *
 * getItemOwner(Item $item): array
 * -- testGetItemOwnerItemIdEmpty(): void
 * -- testGetItemOwnerItemIdInvalid(): void
 * -- testGetItemOwnerItemIdValid(): void
 *
 * addSellerRating(UserRatings $sellerRating): UserRatings
 * -- testAddSellerRatingUserIdInvalid(): void
 * -- testAddSellerRatingItemIdInvalid(): void
 * -- testAddSellerRatingRatingNotSet(): void
 * -- testAddSellerRatingSuccess(): void
 *
 * addBuyerRating(UserRatings $buyerRating): bool
 * -- testAddBuyerRatingTransactionIdEmpty(): void
 * -- testAddBuyerRatingTransactionIdInvalid(): void
 * -- testAddBuyerRatingRatingInvalid(): void
 * -- testAddBuyerRatingSuccess(): void
 *
 * getUserRatings(int $userID): array
 * -- testGetUserRatingsUserIdInvalid(): void
 * -- testGetUserRatingsUserIdValid(): void
 *
 *
 */
declare ( strict_types = 1 )
	;

if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}

require_once dirname ( __FILE__ ) . '/../../testharness/unit/TestPDO.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/System.php';
require_once dirname ( __FILE__ ) . '/../../../model/Category.php';
require_once dirname ( __FILE__ ) . '/../../../model/Categories.php';
require_once dirname ( __FILE__ ) . '/../../../model/CategoryItems.php';
require_once dirname ( __FILE__ ) . '/../../../model/Comment.php';
require_once dirname ( __FILE__ ) . '/../../../model/Item.php';
require_once dirname ( __FILE__ ) . '/../../../model/ItemComments.php';
require_once dirname ( __FILE__ ) . '/../../../model/ItemNotes.php';
require_once dirname ( __FILE__ ) . '/../../../model/Note.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/UserComments.php';
require_once dirname ( __FILE__ ) . '/../../../model/UserItems.php';
require_once dirname ( __FILE__ ) . '/../../../model/UserRatings.php';
require_once dirname ( __FILE__ ) . '/../../../model/Users.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
class SystemTest extends PHPUnit\Framework\TestCase {
	
	// User Parameters
	const USER_ID = 'userID';
	const USER = 'user';
	const EMAIL = 'email';
	const PASSWORD = 'password';
	const STATUS = 'status';
	const ACTIVATE = 'activate';
	
	// Category Parameters
	const CATEGORY_ID = 'categoryID';
	const PARENT_ID = 'parentID';
	const CATEGORY_NAME = 'category';
	
	// Item Parameters
	const ITEM_ID = 'itemID';
	const OWNING_USER_ID = 'owningUserID';
	const TITLE = 'title';
	const DESCRIPTION = 'description';
	const QUANTITY = 'quantity';
	const CONDITION = 'itemcondition';
	const PRICE = 'price';
	const ITEM_STATUS = 'status';
	
	// User_item Parameters
	const USER_ITEM_ID = 'user_itemID';
	const RELATIONSHIP = 'relationship';
	const USERSTATUS = 'userStatus';
	
	// Item_comment Parameters
	const ITEM_COMMENT_ID = 'item_commentID';
	const COMMENT_ID = 'commentID';
	
	// Item_note Parameters
	const ITEM_NOTE_ID = 'item_noteID';
	const NOTE_ID = 'noteID';
	
	// User_ratings Parameters
	const USER_RATING_ID = 'user_ratingID';
	const SELLRATING = 'sellrating';
	const BUYRATING = 'buyrating';
	const TRANSACTION = 'transaction';
	
	// Error Messages
	const ERROR_ACTIVATION_CODE = 'Failed to retrieve UserID!';
	const ERROR_ACTIVATION_CODE_SHORT = 'Activation code must be 32 characters in length!';
	const ERROR_CATEGORY_ID_NOT_EXIST = 'The categoryID does not exist!';
	const ERROR_CATEGORY_NONE = 'Input is required!';
	const ERROR_CATEGORY_NOT_CREATED = 'The category was not created!';
	const ERROR_CATEGORY_NOT_EXIST = 'Category does not exist!';
	const ERROR_CATEGORY_NOT_UPDATED = 'The category was not updated!';
	const ERROR_COMMENT_EMPTY = 'Input is required!';
	const ERROR_COMMENT_ID_ALREADY_EXIST = 'The CommentID is already in Item_comments!';
	const ERROR_COMMENT_ID_NOT_EXIST = 'The CommentID does not exist!';
	const ERROR_EMAIL_DUPLICATE = 'This email address is not available!';
	const ERROR_EMAIL_EMPTY = 'Email Error: Input is required!';
	const ERROR_EMAIL_INVALID = 'Email Error: Email address must be valid!';
	const ERROR_INCORRECT_TRANSACTION_ID = 'The TransactionID is incorrect!';
	const ERROR_ITEM_COMMENT_ID_NOT_EXIST = 'The ItemCommentID does not exist!';
	const ERROR_ITEM_EMPTY = 'Input is required!';
	const ERROR_ITEM_ID_ALREADY_EXIST = 'The ItemID is already in User_items!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_ITEM_NOT_EXIST = 'Item does not exist!';
	const ERROR_ITEM_NOTE_ID_NOT_EXIST = 'The ItemNoteID does not exist!';
	const ERROR_NOTE_EMPTY = 'Input is required!';
	const ERROR_NOTE_ID_ALREADY_EXIST = 'The NoteID is already in Item_notes!';
	const ERROR_NOTE_ID_NOT_EXIST = 'The NoteID does not exist!';
	const ERROR_NUMBER_IS_ZERO = 'Number must be greater than zero!';
	const ERROR_PARENT_CATEGORY_NOT_EXIST = 'The parent category does not exist!';
	const ERROR_PARENT_ID_INVALID = 'Input is required!';
	const ERROR_PARENT_ID_NOT_EXIST = 'The parentID does not exist!';
	const ERROR_PASSWORD_EMPTY = 'Password Error: Input is required!';
	const ERROR_PASSWORD_INVALID = 'Password Error: At least one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!';
	const ERROR_USER_ADD_FAILED = 'Failed to add User.';
	const ERROR_USER_EMPTY = 'Username Error: Input is required!';
	const ERROR_USER_DUPLICATE = 'This user name is not available!';
	const ERROR_USER_ID_EMPTY = 'Input is required!';
	const ERROR_USER_ID_NOT_EXIST = 'The UserID does not exist!';
	const ERROR_USER_ID_NOT_INT = 'UserID must be an integer!';
	const ERROR_USER_ITEM_ID_NOT_EXIST = 'The UserItemID does not exist!';
	const ERROR_USER_NOT_EXIST = 'User does not exist!';
	const ERROR_USER_RATING_ID_NOT_EXIST = 'The UserRatingID does not exist!';
	const ERROR_USER_RATING_NOT_SET = 'The rating has not been set!';
	const ERROR_USER_SHORT = 'Username Error: Input must be at least 4 characters in length!';
	const ERROR_ZERO = 'Number must be greater than zero!';
	
	// Test Data
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
	const INVALID_ID = 2000;
	const INVALID_ACTIVATION_CODE = 'ef4flslerlwldxl234lsdl3w';
	const EMAIL_NOT_EXIST = 'bandicoot@gumtree.net';
	const STATUS_ADD = 'suspended';
	const STATUS_ACTIVE = 'active';
	const STATUS_SUSPENDED = 'suspended';
	const PAGE_NUMBER = 10;
	const USERS_PER_PAGE = 20;
	const PAGE_NUMBER_ZERO = 0;
	const USERS_PER_PAGE_ZERO = 0;
	const CATEGORY_ID_1 = 1;
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
	const ROOT_CATEGORY_NAME = 'Category';
	const ITEM_ID_1 = 1;
	const TITLE_1 = 'title1';
	const DESCRIPTION_1 = 'description1';
	const QUANTITY_1 = 'quantity1';
	const CONDITION_1 = 'condition1';
	const PRICE_1 = 'price1';
	const STATUS_1 = 'active';
	const ITEM_ID_2 = 2;
	const TITLE_2 = 'title2';
	const DESCRIPTION_2 = 'description2';
	const QUANTITY_2 = 'quantity2';
	const CONDITION_2 = 'condition2';
	const PRICE_2 = 'price2';
	const STATUS_2 = 'active';
	const ITEM_ID_3 = 3;
	const TITLE_3 = 'title3';
	const DESCRIPTION_3 = 'description3';
	const QUANTITY_3 = 'quantity3';
	const CONDITION_3 = 'condition3';
	const PRICE_3 = 'price3';
	const STATUS_3 = 'active';
	const ITEM_ID_16 = 16;
	const TITLE_16 = 'title16';
	const ITEM_ID_INVALID = 4000;
	const ITEMS_PAGE_NUMBER = 3;
	const ITEMS_PER_PAGE = 6;
	const ITEMS_PAGE_NUMBER_ZERO = 0;
	const ITEMS_PER_PAGE_ZERO = 0;
	const ITEM_NOTE_ID_1 = 1;
	const NOTE_ID_1 = 1;
	const ITEM_NOTE_ID_2 = 2;
	const NOTE_ID_2 = 2;
	const NOTE_ID_15 = 15;
	const NOTE_NEW = 'New Note';
	const ITEM_COMMENT_ID_1 = 1;
	const COMMENT_ID_1 = 1;
	const ITEM_COMMENT_ID_2 = 2;
	const COMMENT_ID_2 = 2;
	const COMMENT_ID_15 = 15;
	const COMMENT_NEW = 'New Note';
	const USER_ITEM_ID_1 = 1;
	const USER_ITEM_ID_15 = 15;
	const RELATIONSHIP_1 = 'Relationship1';
	const RELATIONSHIP_2 = 'Relationship2';
	const USERSTATUS_1 = 'UserStatus1';
	const USERSTATUS_2 = 'UserStatus2';
	const USER_RATING_ID_1 = 1;
	const SELLRATING_1 = 3;
	const BUYRATING_1 = 4;
	const USER_RATING_ID_2 = 2;
	const SELLRATING_2 = 4;
	const BUYRATING_2 = 3;
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		$root->set ();

		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		$c->set ();

		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		$c->set ();

		$args1 = [
				self::USER => self::USER_ONE,
				self::EMAIL => self::EMAIL_ADDRESS_ONE,
				self::PASSWORD => self::PASSWORD_ONE
		];

		$args2 = [
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::PASSWORD => self::PASSWORD_TWO
		];

		$args3 = [
				self::USER => self::USER_THREE,
				self::EMAIL => self::EMAIL_ADDRESS_THREE,
				self::PASSWORD => self::PASSWORD_THREE
		];

		// Populate the Users table.
		
		for($i = 1; $i < 4; $i ++) {
			${'u' . $i} = new User ( $pdo, ${'args' . $i} );
			try {
				${'u' . $i}->set ();
			} catch ( ModelException $e ) {
				$this->assertEquals('Exception', $e->getMessage());
			}
			try {
				${'u' . $i}->get ();
			} catch ( ModelException $e ) {
				$this->assertEquals('Exception', $e->getMessage());
			}
			${'u' . $i}->activate ();
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	
	/*
	 * createAccount() Tests
	 */
	public function testcreateAccountNoUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => NULL,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_EMPTY, $_SESSION ['error'] );
	}
	public function testcreateAccountShortUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_SHORT,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_SHORT, $_SESSION ['error'] );
	}
	public function testcreateAccountExistUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_DUPLICATE, $_SESSION ['error'] );
	}
	public function testcreateAccountNoEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => NULL,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_EMPTY, $_SESSION ['error'] );
	}
	public function testcreateAccountBadEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_BAD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_INVALID, $_SESSION ['error'] );
	}
	public function testcreateAccountExistEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_DUPLICATE, $_SESSION ['error'] );
	}
	public function testcreateAccountNoPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => NULL 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_EMPTY, $_SESSION ['error'] );
	}
	public function testcreateAccountShortPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_SHORT 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testcreateAccountNoUpperPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_UPPER 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testcreateAccountNoLowerPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_LOWER 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testcreateAccountNoNumberPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_NUMBER 
		] );

		$this->assertFalse ( $system->createAccount ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testcreateAccountSuccessful(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		$this->assertTrue ( $system->createAccount ( $sut ) );
	}
	
	/*
	 * getUserIdByActivationCode() Test
	 */
	public function testGetUserIdByActivationCodeInvalidCode(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$sut->set ();
		$sut->userID = $sut->get ();

		$sut->activate = self::INVALID_ACTIVATION_CODE;
		$system->getUserIdByActivationCode ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ACTIVATION_CODE_SHORT, $_SESSION ['error'] );
	}
	public function testGetUserIdByActivationCodeValidCode(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$sut->set ();
		$sut->userID = $sut->get ();

		$this->assertGreaterThan ( 0, $system->getUserIdByActivationCode ( $sut ) );
	}
	
	/*
	 * activateAccount() Test
	 */
	public function testActivateAccountInvalidId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$sut->userID = $sut->set ();
		$sut->get ();

		$sut->userID = self::INVALID_ID;
		$this->expectExceptionMessage ( self::ERROR_USER_NOT_EXIST );
		$system->activateAccount ( $sut );
	}
	public function testActivateAccountValidId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$sut->userID = $sut->set ();
		$sut->get ();

		$this->assertTrue ( $system->activateAccount ( $sut ) );
	}
	
	/*
	 * Test changePassword()
	 */
	public function testChangePasswordNoPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => NULL 
		] );

		$this->assertFalse ( $system->changePassword ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_EMPTY, $_SESSION ['error'] );
	}
	public function testChangePasswordShortPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_SHORT 
		] );

		$this->assertFalse ( $system->changePassword ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testChangePasswordNoUpperPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_NO_UPPER 
		] );

		$this->assertFalse ( $system->changePassword ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testChangePasswordNoLowerPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_NO_LOWER 
		] );

		$this->assertFalse ( $system->changePassword ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}

	public function testChangePasswordNoNumberPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_NO_NUMBER 
		] );

		$this->assertFalse ( $system->changePassword ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}

	public function testChangePasswordSuccessful(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$sut->get ();

		$oldPassword = $sut->password;
		$sut->password = self::PASSWORD_ADD;
		$this->assertTrue ( $system->changePassword ( $sut ) );
		$sut->get ();

		$newPassword = $sut->get ()->password;
		if (strlen ( $oldPassword ) > 0 && strlen ( $newPassword ) > 0 && $oldPassword != $newPassword) {
			$this->assertTrue ( true );
		} else {
			$this->assertTrue ( false );
		}
	}
	
	/*
	 * Test forgotPassword()
	 */
	public function testforgotPasswordNoEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::EMAIL => NULL 
		] );
		$system->forgotPassword ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_EMPTY, $_SESSION ['error'] );
	}
	public function testforgotPasswordBadEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::EMAIL => self::EMAIL_BAD 
		] );
		$system->forgotPassword ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_INVALID, $_SESSION ['error'] );
	}
	public function testforgotPasswordNotExistEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::EMAIL => self::EMAIL_BAD 
		] );
		$system->forgotPassword ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_INVALID, $_SESSION ['error'] );
	}
	public function testforgotPasswordExistEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::EMAIL => self::EMAIL_ADDRESS_TWO 
		] );
		$sut = $system->forgotPassword ( $sut );
		$this->assertEquals ( 2, $sut->userID );
		$this->assertEquals ( self::EMAIL_ADDRESS_TWO, $sut->email );
		$this->assertEquals ( 10, strlen ( $sut->password ) );
	}
	
	/*
	 * AddUser() Tests
	 */
	public function testAddUserNoUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => NULL,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_EMPTY, $_SESSION ['error'] );
	}
	public function testAddUserShortUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_SHORT,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_SHORT, $_SESSION ['error'] );
	}
	public function testAddUserExistUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_DUPLICATE, $_SESSION ['error'] );
	}

	public function testAddUserNoEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => NULL,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_EMPTY, $_SESSION ['error'] );
	}
	public function testAddUserBadEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_BAD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_INVALID, $_SESSION ['error'] );
	}
	public function testAddUserExistEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_DUPLICATE, $_SESSION ['error'] );
	}
	public function testAddUserNoPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => NULL 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_EMPTY, $_SESSION ['error'] );
	}

	public function testAddUserShortPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_SHORT 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}

	public function testAddUserNoUpperPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_UPPER 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testAddUserNoLowerPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_LOWER 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testAddUserNoNumberPassword(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_NUMBER 
		] );

		$this->assertEquals ( 0, $system->addUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PASSWORD_INVALID, $_SESSION ['error'] );
	}
	public function testAddUserSuccessful(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		$this->assertNotEquals ( 0, $system->addUser ( $sut ) );
	}
	
	/*
	 * UpdateUser() Tests
	 */
	public function testUpdateUserNoUserID(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );

		$this->assertFalse ( $system->updateUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_ID_EMPTY, $_SESSION ['error'] );
	}
	public function testUpdateUserNoUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => NULL,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );

		$this->assertFalse ( $system->updateUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_EMPTY, $_SESSION ['error'] );
	}

	public function testUpdateUserShortUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_SHORT,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );

		$this->assertFalse ( $system->updateUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_SHORT, $_SESSION ['error'] );
	}

	public function testUpdateUserExistUser(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );

		$this->assertFalse ( $system->updateUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_DUPLICATE, $_SESSION ['error'] );
	}
	public function testUpdateUserNoEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => NULL,
				self::STATUS => self::STATUS_ADD 
		] );

		$this->assertFalse ( $system->updateUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_EMPTY, $_SESSION ['error'] );
	}

	public function testUpdateUserBadEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_BAD,
				self::STATUS => self::STATUS_ADD 
		] );

		$this->assertFalse ( $system->updateUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_INVALID, $_SESSION ['error'] );
	}

	public function testUpdateUserExistEmail(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::STATUS => self::STATUS_ADD 
		] );

		$this->assertFalse ( $system->updateUser ( $sut ) );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_EMAIL_DUPLICATE, $_SESSION ['error'] );
	}
	public function testUpdateUserNoStatus(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->assertTrue ( $system->updateUser ( $sut ) );
		$sut->get ();

		$this->assertEquals ( self::STATUS_ADD, $sut->status );
	}
	public function testUpdateUserSuccessful(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->assertTrue ( $system->updateUser ( $sut ) );
		$sut->get ();

		$this->assertEquals ( self::USER_ADD, $sut->user );
		$this->assertEquals ( self::EMAIL_ADD, $sut->email );
		$this->assertEquals ( self::STATUS_ADD, $sut->status );
	}
	
	/*
	 * getUser(User $user): User
	 */
	public function testGetUserNoUserID(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo );
		$system->getUser ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_ID_EMPTY, $_SESSION ['error'] );
	}

	public function testGetUserInvalidUserID(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo );
		$sut->userID = self::INVALID_ID;
		$system->getUser ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testGetUserValidUserID(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo );
		$sut->userID = self::USER_ID_1;
		$sut = $system->getUser ( $sut );
		$this->assertEquals ( self::USER_ONE, $sut->user );
		$this->assertEquals ( self::EMAIL_ADDRESS_ONE, $sut->email );
		$this->assertEquals ( self::STATUS_ACTIVE, $sut->status );
	}
	
	/*
	 * getUsers(int $pageNumber, int $usersPerPage): array
	 */
	public function testGetUsersPageNumberZero(): void {
		$this->populateUsers ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$system->getUsers ( self::PAGE_NUMBER_ZERO, self::USERS_PER_PAGE );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ZERO, $_SESSION ['error'] );
	}
	public function testGetUsersUsersPerPageZero(): void {
		$this->populateUsers ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$system->getUsers ( self::PAGE_NUMBER, self::USERS_PER_PAGE_ZERO );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ZERO, $_SESSION ['error'] );
	}
	public function testGetUsersSuccess(): void {
		$this->populateUsers ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$users = $system->getUsers ( self::PAGE_NUMBER, self::USERS_PER_PAGE );

		$pn = self::PAGE_NUMBER;
		$upp = self::USERS_PER_PAGE;

		$start = ($pn - 1) * $upp + 1;

		foreach ( $users as $user ) {
			$this->assertEquals ( $start, $user->userID );
			$this->assertEquals ( 'user' . $start, $user->user );
			$this->assertEquals ( 'email' . $start . '@gmail.com', $user->email );
			$start ++;
		}
	}
	
	/*
	 * disableUser(User $user): bool
	 */
	public function testDisableUserNoUserID(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo );
		if ($system->disableUser ( $sut )) {
			$this->assertTrue ( false );
		} else {
			$this->assertTrue ( true );
		}
	}
	public function testDisableUserInvalidUserID(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo );
		$sut->userID = self::INVALID_ID;
		if ($system->disableUser ( $sut )) {
			$this->assertTrue ( false );
		} else {
			$this->assertTrue ( true );
		}
	}
	public function testDisableUserValidUserID(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new User ( $pdo );
		$sut->userID = self::USER_ID_1;
		
		if ($system->disableUser ( $sut )) {
			$sut = $system->getUser ( $sut );
			$this->assertEquals ( self::STATUS_SUSPENDED, $sut->status );
		} else {
			$this->assertTrue ( false );
		}
	}
	
	/*
	 * deleteUser(User $user): bool
	 * Will require a large number of tests once validation of all
	 * other classes has been completed.
	 */
	public function testDeleteUserUserIdEmpty(): void {
		unset ( $_SESSION ['error'] );
		$this->populateAll ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$u = new User ( $pdo );
		$system->deleteUser ( $u );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_ID_EMPTY, $_SESSION ['error'] );
	}
	public function testDeleteUserUserIdInvalid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateAll ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$u = new User ( $pdo );
		$u->userID = self::INVALID_ID;
		$system->deleteUser ( $u );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testDeleteUserUserIdValid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateAll ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$u = new User ( $pdo );
		$u->userID = self::USER_ID_2;
		$this->assertTrue ( $system->deleteUser ( $u ) );
	}
	
	/*
	 * addCategory(Category $category): bool
	 */
	public function testAddCategoryNoParentId(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );

		$this->assertTrue ( $system->addCategory ( $sut ) );
	}
	public function testAddCategoryInvalidParentId(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::PARENT_ID => self::PARENT_ID_INVALID,
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );
		$this->assertFalse ( $system->addCategory ( $sut ) );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PARENT_CATEGORY_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testAddCategoryNoCategory(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::PARENT_ID => self::PARENT_ID_1 
		] );
		$this->assertFalse ( $system->addCategory ( $sut ) );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_CATEGORY_NONE, $_SESSION ['error'] );
	}
	public function testAddCategorySuccess(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::PARENT_ID => self::PARENT_ID_1,
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );
		$this->assertTrue ( $system->addCategory ( $sut ) );
	}
	
	/*
	 * updateCategory(Category $category): bool
	 */
	public function testUpdateCategoryNoCategoryId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::PARENT_ID => self::PARENT_ID_1,
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );
		$this->assertFalse ( $system->updateCategory ( $sut ) );
	}
	public function testUpdateCategoryInvalidCategoryId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_INVALID,
				self::PARENT_ID => self::PARENT_ID_1,
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );
		$this->assertFalse ( $system->updateCategory ( $sut ) );
	}
	public function testUpdateCategoryNoParentId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_3,
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );
		$this->assertTrue ( $system->updateCategory ( $sut ) );
		$sut->get ();

		$this->assertSame ( '1', $sut->parentID );
		$this->assertSame ( self::CATEGORY_4, $sut->category );
	}
	public function testUpdateCategoryInvalidParentId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_3,
				self::PARENT_ID => self::PARENT_ID_INVALID,
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );
		$this->assertTrue ( $system->updateCategory ( $sut ) );
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_NOT_EXIST );
		$sut->get();
	}
	public function testUpdateCategoryNoCategory(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_3,
				self::PARENT_ID => self::PARENT_ID_2 
		] );
		$this->assertTrue ( $system->updateCategory ( $sut ) );
		$sut->get ();

		$this->assertSame ( '2', $sut->parentID );
		$this->assertSame ( self::CATEGORY_3, $sut->category );
	}
	public function testUpdateCategoryParentId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_3,
				self::PARENT_ID => self::PARENT_ID_2,
				self::CATEGORY_NAME => self::CATEGORY_3 
		] );
		$this->assertTrue ( $system->updateCategory ( $sut ) );
		$sut->get ();

		$this->assertSame ( '2', $sut->parentID );
		$this->assertSame ( self::CATEGORY_3, $sut->category );
	}
	public function testUpdateCategoryCategory(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_3,
				self::PARENT_ID => self::PARENT_ID_1,
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );
		$this->assertTrue ( $system->updateCategory ( $sut ) );
		$sut->get ();

		$this->assertSame ( '1', $sut->parentID );
		$this->assertSame ( self::CATEGORY_4, $sut->category );
	}
	public function testUpdateCategoryAll(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_3,
				self::PARENT_ID => self::PARENT_ID_2,
				self::CATEGORY_NAME => self::CATEGORY_4 
		] );

		$this->assertTrue ( $system->updateCategory ( $sut ) );
		$sut->get ();

		$this->assertSame ( '2', $sut->parentID );
		$this->assertSame ( self::CATEGORY_4, $sut->category );
	}
	
	/*
	 * deleteCategory(Category $category): bool
	 */
	public function testDeleteCategoryCategoryIdEmpty(): void {
		unset ( $_SESSION ['error'] );
		$this->populateAll ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$c = new Category ( $pdo );
		$system->deleteCategory ( $c );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_CATEGORY_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testDeleteCategoryCategoryIdInvalid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateAll ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$c = new Category ( $pdo );
		$c->categoryID = self::INVALID_ID;
		$system->deleteCategory ( $c );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_CATEGORY_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testDeleteCategoryCategoryIdValid(): void {
		unset ( $_SESSION ['error'] );
		if ($this->populateDeleteCategory ()) {
			$pdo = TestPDO::getInstance ();
			$system = new System ( $pdo );
			$c = new Category ( $pdo );
			$c->categoryID = 2;
			$system->deleteCategory ( $c );
		}
		$this->assertFalse ( $c->exists () );
	}
	
	/*
	 * getCategory(Category $category): Category
	 */
	public function testGetCategoryNoCategoryId(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo );
		$category = $system->getCategory ( $sut );
		$this->assertSame ( 0, $category->categoryID );
		$this->assertSame ( null, $category->parentID );
		$this->assertEmpty ( $category->category );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_CATEGORY_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetCategoryInvalidCategoryId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_INVALID 
		] );
		$category = $system->getCategory ( $sut );
		$this->assertSame ( '400', $category->categoryID );
		$this->assertSame ( null, $category->parentID );
		$this->assertEmpty ( $category->category );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_CATEGORY_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetCategoryValidCategoryId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Category ( $pdo, [ 
				self::CATEGORY_ID => self::CATEGORY_ID_3 
		] );
		$category = $system->getCategory ( $sut );
		$this->assertSame ( '3', $category->categoryID );
		$this->assertSame ( '1', $category->parentID );
		$this->assertSame ( self::CATEGORY_3, $category->category );
	}
	
	/*
	 * getCategories(): array
	 */
	public function testGetCategories(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateCategories ();
		$cats = $system->getCategories ();
		$i = 1;
		$j = 0;
		foreach ( $cats as $cat ) {
			if ($i == 1) {
				$this->assertEquals ( $i, $cat->categoryID );
				$this->assertEquals ( $j, $cat->parentID );
				$this->assertEquals ( 'Category', $cat->category );
				$j ++;
			} else {
				$number = $i - 1;
				$this->assertEquals ( $i, $cat->categoryID );
				$this->assertEquals ( $j, $cat->parentID );
				$this->assertSame ( 'cat' . $number, $cat->category );
			}
			if ($i % 3 == 0) {
				$j ++;
			}
			$i ++;
		}
	}
	
	/*
	 * getCategoriesIn(int $parentID): array
	 */
	public function testGetCategoriesInParentIdInvalid(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$system->getCategoriesIn ( self::INVALID_ID );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_PARENT_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetCategoriesInParentIdValid(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateCategories ();
		$cats = $system->getCategoriesIn ( 4 );
		$i = 10;
		foreach ( $cats as $cat ) {
			$number = $i - 1;
			$this->assertEquals ( $i, $cat->categoryID );
			$this->assertEquals ( 4, $cat->parentID );
			$this->assertSame ( 'cat' . $number, $cat->category );
			$i ++;
		}
	}
	
	/*
	 * countCategoryItems(Category $category): int
	 */
	public function testCountCategoryItemsCategoryIDEmpty(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$category = new Category ( $pdo );

		$this->assertEquals(0, $system->countCategoryItems ( $category ));
	}
	public function testCountCategoryItemsCategoryIDInvalid(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$category = new Category ( $pdo );
		$category->categoryID = self::INVALID_ID;

		$this->assertEquals(0, $system->countCategoryItems ( $category ));
	}
	public function testCountCategoryItemsCategoryIDValid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateCategoryItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$category = new Category ( $pdo );
		$category->categoryID = self::CATEGORY_ID_3;
		$this->assertEquals ( 34, $system->countCategoryItems ( $category ) );
	}
	
	/*
	 * countItemComments(Item $item): int
	 */
	public function testCountItemCommentsItemIdEmpty(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateItemComments ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$numItems = $system->countItemComments ( $item );
		$this->assertEquals ( 0, $numItems );
	}
	public function testCountItemCommentsItemIdInvalid(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateItemComments ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::INVALID_ID;
		$numItems = $system->countItemComments ( $item );
		$this->assertEquals ( 0, $numItems );
	}
	public function testCountItemCommentsItemIdValid(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateItemComments ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::ITEM_ID_2;
		$numItems = $system->countItemComments ( $item );
		$this->assertEquals ( 5, $numItems );
	}
	
	/*
	 * countItemNotes(Item $item): int
	 */
	public function testCountItemNotesItemIdEmpty(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateItemNotes ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$numItems = $system->countItemNotes ( $item );
		$this->assertEquals ( 0, $numItems );
	}
	public function testCountItemNotesItemIdInvalid(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateItemNotes ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::INVALID_ID;
		$numItems = $system->countItemNotes ( $item );
		$this->assertEquals ( 0, $numItems );
	}
	public function testCountItemNotesItemIdValid(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateItemNotes ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::ITEM_ID_2;
		$numItems = $system->countItemNotes ( $item );
		$this->assertEquals ( 5, $numItems );
	}
	
	/*
	 * getCategoryItemsByPage(Category $category, int $pageNumber, int $itemsPerPage, string $status): array
	 */
	public function testGetCategoryItemsByPagePageNumberZero(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$c = new Category ( $pdo );
		$c->categoryID = self::CATEGORY_ID_3;
		$system->getCategoryItemsByPage ( $c, self::ITEMS_PAGE_NUMBER_ZERO, self::ITEMS_PER_PAGE, '' );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ZERO, $_SESSION ['error'] );
	}
	public function testGetCategoryItemsByPageItemsPerPageZero(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$c = new Category ( $pdo );
		$c->categoryID = self::CATEGORY_ID_3;
		$system->getCategoryItemsByPage ( $c, self::ITEMS_PAGE_NUMBER, self::ITEMS_PER_PAGE_ZERO, '' );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ZERO, $_SESSION ['error'] );
	}

	public function testGetCategoryItemsByPageSuccess(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateCategoryItems ();
		$c = new Category ( $pdo );
		$c->categoryID = self::CATEGORY_ID_3;
		$ci = $system->getCategoryItemsByPage ( $c, self::ITEMS_PAGE_NUMBER, self::ITEMS_PER_PAGE, '' );
		$start = 47;
		foreach ( $ci as $item ) {
			$this->assertEquals ( $start, $item->itemID );
			$start ++;
		}
	}
	
	/*
	 * countUserItems(User $user): int
	 */
	public function testCountUserItemsUserIdEmpty(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateUserItems ();
		$ui = new UserItems ( $pdo );
		$this->assertEquals ( 0, $ui->count () );
	}
	public function testCountUserItemsUserIdInvalid(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateUserItems ();
		$ui = new UserItems ( $pdo );
		$ui->userID = self::INVALID_ID;
		$this->assertEquals ( 0, $ui->count () );
	}
	public function testCountUserItemsUserIdValid(): void {
		$pdo = TestPDO::getInstance ();
		$this->populateUserItems ();
		$ui = new UserItems ( $pdo );
		$ui->userID = self::USER_ID_1;
		$this->assertEquals ( 5, $ui->count () );
	}
	
	/*
	 * getUserItems(User $user): array
	 */
	public function testGetUserItemsUserIdEmpty(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateUserItems ();
		$u = new User ( $pdo );
		$system->getUserItems ( $u );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_ID_EMPTY, $_SESSION ['error'] );
	}

	public function testGetUserItemsUserIdInvalid(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateUserItems ();
		$u = new User ( $pdo );
		$u->userID = self::INVALID_ID;
		$system->getUserItems ( $u );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetUserItemsUserIdValid(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateUserItems ();
		$u = new User ( $pdo );
		$u->userID = self::USER_ID_2;
		$sut = $system->getUserItems ( $u );
		$i = 6;
		foreach ( $sut as $obj ) {
			$this->assertEquals ( $i, $obj->user_itemID );
			$this->assertEquals ( 2, $obj->userID );
			$this->assertEquals ( $i, $obj->itemID );
			$i ++;
		}
	}
	
	/*
	 * getItem(Item $item): Item
	 */
	public function testGetItemNoItemId(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Item ( $pdo );
		$item = $system->getItem ( $sut );
		$this->assertSame ( 0, $item->itemID );
		$this->assertEmpty ( $item->title );
		$this->assertEmpty ( $item->description );
		$this->assertEmpty ( $item->quantity );
		$this->assertEmpty ( $item->itemcondition );
		$this->assertEmpty ( $item->price );
		$this->assertEmpty ( $item->status );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetItemInvalidItemId(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = new Item ( $pdo, [ 
				self::ITEM_ID => self::ITEM_ID_INVALID 
		] );
		$item = $system->getItem ( $sut );
		$this->assertSame ( '4000', $item->itemID );
		$this->assertEmpty ( $item->title );
		$this->assertEmpty ( $item->description );
		$this->assertEmpty ( $item->quantity );
		$this->assertEmpty ( $item->itemcondition );
		$this->assertEmpty ( $item->price );
		$this->assertEmpty ( $item->status );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testGetItemValidItemId(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateItems ();
		$sut = new Item ( $pdo, [ 
				self::ITEM_ID => self::ITEM_ID_2 
		] );
		$item = $system->getItem ( $sut );
		$this->assertSame ( '2', $item->itemID );
		$this->assertSame ( self::TITLE_2, $item->title );
		$this->assertSame ( self::DESCRIPTION_2, $item->description );
		$this->assertSame ( self::QUANTITY_2, $item->quantity );
		$this->assertSame ( self::CONDITION_2, $item->itemcondition );
		$this->assertSame ( self::PRICE_2, $item->price );
		$this->assertSame ( self::STATUS_2, $item->status );
	}
	
	/*
	 * addItem(User $user, Item $item): bool
	 */
	public function testAddItemUserIdEmptyItemValid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateUserItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->title = self::TITLE_16;
		$system->addItem ( $i, intval(self::CATEGORY_ID_1 ));

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_NUMBER_IS_ZERO, $_SESSION ['error'] );
	}
	public function testAddItemUserIdInvalidItemValid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateUserItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->owningUserID = self::INVALID_ID;
		$i->title = self::TITLE_16;
		$system->addItem ( $i, intval(self::CATEGORY_ID_1 ));

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testAddItemUserIdValidItemEmpty(): void {
		unset ( $_SESSION ['error'] );
		$this->populateUserItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$u = new User ( $pdo );
		$u->userID = self::USER_ID_1;
		$i = new Item ( $pdo );
		$i->owningUserID = self::USER_ID_1;
		$system->addItem ( $i, intval(self::CATEGORY_ID_1 ));

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_EMPTY, $_SESSION ['error'] );
	}

	public function testAddItemUserIdValidItemValid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateUserItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->owningUserID = self::USER_ID_1;
		$i->title = self::TITLE_16;

		$itemID = $system->addItem ( $i, self::CATEGORY_ID_1 );
		$this->assertEquals ( self::ITEM_ID_16, $itemID );

		$i->itemID = self::ITEM_ID_16;
		$i->get ();

		$this->assertEquals ( self::TITLE_16, $i->title );
	}
	
	/*
	 * updateItem(Item $item): bool
	 */
	public function testUpdateItemNoItemId(): void {
		unset ( $_SESSION ['error'] );
		$this->populateUserItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->title = self::TITLE_16;
		$system->updateItem ( $i );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testUpdateItemInvalidItemId(): void {
		unset ( $_SESSION ['error'] );
		$this->populateUserItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->itemID = self::INVALID_ID;
		$i->title = self::TITLE_16;
		$system->updateItem ( $i );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testUpdateItemEmptyItem(): void {
		unset ( $_SESSION ['error'] );
		$this->populateUserItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$system->updateItem ( $i );
		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testUpdateItemSuccess(): void {
		unset ( $_SESSION ['error'] );
		$this->populateUserItems ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->itemID = self::ITEM_ID_1;
		$i->title = self::TITLE_16;
		$system->updateItem ( $i );
		$i->itemID = self::ITEM_ID_1;
		$i->get ();

		$this->assertEquals ( self::ITEM_ID_1, $i->itemID );
		$this->assertEquals ( self::TITLE_16, $i->title );
	}
	
	/*
	 * deleteItem(Item $item): bool
	 */
	public function testDeleteItemItemIdEmpty(): void {
		unset ( $_SESSION ['error'] );
		$this->populateAll ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$system->deleteItem ( $i );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testDeleteItemItemIdInvalid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateAll ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->itemID = self::INVALID_ID;
		$system->deleteItem ( $i );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testDeleteItemItemIdValid(): void {
		unset ( $_SESSION ['error'] );
		$this->populateAll ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->itemID = self::ITEM_ID_2;
		$this->assertTrue ( $system->deleteItem ( $i ) );
	}
	
	/*
	 * getItemComments(Item $item): array
	 */
	public function testGetItemCommentsItemIdEmpty(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$system->getItemComments ( $item );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetItemCommentsItemIdInvalid(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::INVALID_ID;
		$system->getItemComments ( $item );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetItemCommentsItemIdValid(): void {
		$this->populateItemComments ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::ITEM_ID_2;
		$comments = $system->getItemComments ( $item );
		$i = 6;
		foreach ( $comments as $comment ) {
			$this->assertEquals ( $i, $comment->item_commentID );
			$this->assertEquals ( 2, $comment->itemID );
			$this->assertEquals ( $i, $comment->commentID );
			$i ++;
		}
	}
	
	/*
	 * getItemComment(Comment $comment): Comment
	 */
	public function testGetItemCommentCommentIdEmpty(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$system->getItemComment ( $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_COMMENT_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetItemCommentCommentIdInvalid(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->commentID = self::INVALID_ID;
		$system->getItemComment ( $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_COMMENT_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetItemCommentCommentIdValid(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->commentID = self::COMMENT_ID_2;
		$item = $system->getItemComment ( $comment );

		$this->assertEquals ( self::ITEM_ID_1, $item->itemID );
		$this->assertEquals ( 'title1', $item->title );
	}
	
	/*
	 * addItemComment(Item $item, Comment $comment): bool
	 */
	public function testAddItemCommentEmpty(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->userID = self::USER_ID_1;
		$system->addItemComment ( $item, $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_COMMENT_EMPTY, $_SESSION ['error'] );
	}
	public function testAddItemCommentInvalidItemId(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::INVALID_ID;
		$comment = new Comment ( $pdo );
		$comment->comment = self::COMMENT_NEW;
		$comment->userID = self::USER_ID_1;
		$system->addItemComment ( $item, $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testAddItemCommentSuccess(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::ITEM_ID_3;
		$comment = new Comment ( $pdo );
		$comment->userID = self::USER_ID_1;
		$comment->comment = self::COMMENT_NEW;

		$this->assertTrue ( $system->addItemComment ( $item, $comment ) );
	}
	
	/*
	 * updateItemComment(Comment $comment): bool
	 */
	public function testUpdateItemCommentsNoCommentsId(): void {
		$this->populateItemComments ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$system->updateItemComment ( $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_COMMENT_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testUpdateItemCommentsInvalidCommentId(): void {
		$this->populateItemComments ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->commentID = self::INVALID_ID;
		$system->updateItemComment ( $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_COMMENT_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testUpdateItemCommentsSuccess(): void {
		$this->populateItemComments ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->commentID = self::COMMENT_ID_1;
		$comment->comment = self::COMMENT_NEW;
		$this->assertTrue ( $system->updateItemComment ( $comment ) );
	}
	
	/*
	 * deleteItemComment(Comment $comment): bool
	 */
	public function testDeleteItemCommentCommentIdEmpty(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$system->deleteItemComment ( $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_COMMENT_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testDeleteItemCommentCommentIdInvalid(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->commentID = self::INVALID_ID;
		$system->deleteItemComment ( $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_COMMENT_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testDeleteItemCommentCommentIdValid(): void {
		$this->populateItemComments ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->commentID = self::COMMENT_ID_1;
		$this->assertTrue ( $system->deleteItemComment ( $comment ) );
	}
	
	/*
	 * deleteItemComments(Item $item): bool
	 */
	public function testDeleteItemCommentsItemIdEmpty(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$system->deleteItemComments ( $item );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testDeleteItemCommentsItemIdInvalid(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::INVALID_ID;
		$system->deleteItemComments ( $item );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testDeleteItemCommentsItemIdValid(): void {
		$this->populateItemComments ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::ITEM_ID_1;

		$this->assertTrue ( $system->deleteItemComments ( $item ) );
	}
	
	/*
	 * getItemNotes(Item $item): array
	 */
	public function testGetItemNotesItemIdEmpty(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$system->getItemNotes ( $item );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testGetItemNotesItemIdInvalid(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::INVALID_ID;
		$system->getItemNotes ( $item );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testGetItemNotesItemIdValid(): void {
		$this->populateItemNotes ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::ITEM_ID_2;
		$notes = $system->getItemNotes ( $item );
		$i = 6;
		foreach ( $notes as $note ) {
			$this->assertEquals ( $i, $note->item_noteID );
			$this->assertEquals ( 2, $note->itemID );
			$this->assertEquals ( $i, $note->noteID );
			$i ++;
		}
	}
	
	/*
	 * getItemNote(Note $note): Note
	 */
	public function testGetItemNoteNoteIdEmpty(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$system->getItemNote ( $note );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_NOTE_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testGetItemNoteNoteIdInvalid(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$note->noteID = self::INVALID_ID;
		$system->getItemNote ( $note );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_NOTE_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testGetItemNoteNoteIdValid(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$note->noteID = self::NOTE_ID_2;
		$item = $system->getItemNote ( $note );

		$this->assertEquals ( self::ITEM_ID_1, $item->itemID );
		$this->assertEquals ( 'title1', $item->title );
	}
	
	/*
	 * addItemNote(Item $item, Note $note): bool
	 */
	public function testAddItemNoteEmpty(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$note = new Note ( $pdo );
		$system->addItemNote ( $item, $note );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_NOTE_EMPTY, $_SESSION ['error'] );
	}

	public function testAddItemNoteInvalidItemId(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::INVALID_ID;
		$note = new Note ( $pdo );
		$note->note = self::NOTE_NEW;
		$system->addItemNote ( $item, $note );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testAddItemNoteSuccess(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::ITEM_ID_3;
		$note = new Note ( $pdo );
		$note->note = self::NOTE_NEW;

		$this->assertTrue ( $system->addItemNote ( $item, $note ) );
	}
	
	/*
	 * updateItemNote(Note $note): bool
	 */
	public function testUpdateItemNotesNoNotesId(): void {
		$this->populateItemNotes ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$system->updateItemNote ( $note );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_NOTE_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testUpdateItemNotesInvalidNoteId(): void {
		$this->populateItemNotes ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$note->noteID = self::INVALID_ID;
		$system->updateItemNote ( $note );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_NOTE_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testUpdateItemNotesSuccess(): void {
		$this->populateItemNotes ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$note->noteID = self::NOTE_ID_1;
		$note->note = self::NOTE_NEW;

		$this->assertTrue ( $system->updateItemNote ( $note ) );
	}
	
	/*
	 * deleteItemNote(Note $note): bool
	 */
	public function testDeleteItemNoteNoteIdEmpty(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$system->deleteItemNote ( $note );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_NOTE_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testDeleteItemNoteNoteIdInvalid(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$note->noteID = self::INVALID_ID;
		$system->deleteItemNote ( $note );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_NOTE_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testDeleteItemNoteNoteIdValid(): void {
		$this->populateItemNotes ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$note = new Note ( $pdo );
		$note->noteID = self::NOTE_ID_1;
		$this->assertTrue ( $system->deleteItemNote ( $note ) );
	}
	
	/*
	 * deleteItemNotes(Item $item): bool
	 */
	public function testDeleteItemNotesItemIdEmpty(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$system->deleteItemNotes ( $item );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testDeleteItemNotesItemIdInvalid(): void {
		$this->populateItemNotes ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::INVALID_ID;
		$system->deleteItemNotes ( $item );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testDeleteItemNotesItemIdValid(): void {
		$this->populateItemNotes ();
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$item = new Item ( $pdo );
		$item->itemID = self::ITEM_ID_1;

		$this->assertTrue ( $system->deleteItemNotes ( $item ) );
	}
	
	/*
	 * getItemOwner(Item $item): array
	 */
	public function testGetItemOwnerItemIdEmpty(): void {
		$this->populateAdditionalUserRatings ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$system->getItemOwner ( $i );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetItemOwnerItemIdInvalid(): void {
		$this->populateAdditionalUserRatings ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->itemID = self::INVALID_ID;
		$system->getItemOwner ( $i );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetItemOwnerItemIdValid(): void {
		$this->populateAdditionalUserRatings ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$i = new Item ( $pdo );
		$i->itemID = self::ITEM_ID_1;
		$sut = $system->getItemOwner ( $i );

		$this->assertEquals ( self::USER_ID_1, $sut ['userID'] );
		$this->assertEquals ( 'user1', $sut ['user'] );
		$this->assertEquals ( 'user1@gmail.com', $sut ['email'] );
		$this->assertEquals ( 'relationship11', $sut ['relationship'] );
		$this->assertEquals ( 'userStatus11', $sut ['userStatus'] );
		$this->assertEquals ( 5, $sut ['numSellRatings'] );
		$this->assertEquals ( 3.0, $sut ['avgSellRating'] );
		$this->assertEquals ( 4, $sut ['numBuyRatings'] );
		$this->assertEquals ( 2.5, $sut ['avgBuyRating'] );
		$this->assertEquals ( 9, $sut ['totalNumRatings'] );
		$this->assertEquals ( 2.8, $sut ['avgRating'] );
	}
	
	/*
	 * addSellerRating(UserRatings $sellerRating): UserRatings
	 */
	public function testAddSellerRatingUserIdInvalid(): void {
		$this->populateUserRatings ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$sut = new System ( $pdo );
		$ur = new UserRatings ( $pdo );
		$ur->userID = self::INVALID_ID;
		$ur->itemID = self::ITEM_ID_2;
		$ur->sellrating = self::SELLRATING_2;
		$sut->addSellerRating ( $ur );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testAddSellerRatingItemIdInvalid(): void {
		$this->populateUserRatings ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$sut = new System ( $pdo );
		$ur = new UserRatings ( $pdo );
		$ur->userID = self::USER_ID_2;
		$ur->itemID = self::INVALID_ID;
		$ur->sellrating = self::SELLRATING_2;
		$sut->addSellerRating ( $ur );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}

	public function testAddSellerRatingRatingNotSet(): void {
		$this->populateUserRatings ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$sut = new System ( $pdo );
		$ur = new UserRatings ( $pdo );
		$ur->userID = self::USER_ID_2;
		$ur->itemID = self::ITEM_ID_2;
		$sut->addSellerRating ( $ur );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_RATING_NOT_SET, $_SESSION ['error'] );
	}

	public function testAddSellerRatingSuccess(): void {
		$this->populateUserRatings ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$sut = new System ( $pdo );
		$ur = new UserRatings ( $pdo );
		$ur->userID = self::USER_ID_2;
		$ur->itemID = self::ITEM_ID_2;
		$ur->sellrating = self::SELLRATING_2;
		$sut = $sut->addSellerRating ( $ur );
		$this->assertEquals ( self::USER_RATING_ID_2, $sut->user_ratingID );
		$this->assertEquals ( self::ITEM_ID_2, $sut->itemID );
		$this->assertEquals ( self::SELLRATING_2, $sut->sellrating );
		$this->assertEquals ( self::USER_ID_2, $sut->userID );
	}
	
	/*
	 * addBuyerRating(UserRatings $buyerRating): bool
	 */
	public function testAddBuyerRatingTransactionIdEmpty(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = $this->addSellerRating ();
		$sut->transaction = '';
		$sut->buyrating = self::BUYRATING_2;
		$system->addBuyerRating ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_INCORRECT_TRANSACTION_ID, $_SESSION ['error'] );
	}

	public function testAddBuyerRatingTransactionIdInvalid(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = $this->addSellerRating ();
		$sut->transaction = self::INVALID_ID;
		$sut->buyrating = self::BUYRATING_2;
		$system->addBuyerRating ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_INCORRECT_TRANSACTION_ID, $_SESSION ['error'] );
	}

	public function testAddBuyerRatingRatingInvalid(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = $this->addSellerRating ();
		$sut->buyrating = self::INVALID_ID;
		$system->addBuyerRating ( $sut );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_RATING_NOT_SET, $_SESSION ['error'] );
	}
	public function testAddBuyerRatingSuccess(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$sut = $this->addSellerRating ();
		$sut->buyrating = self::BUYRATING_2;
		$system->addBuyerRating ( $sut );
		$ur = new UserRatings ( $pdo );
		$ur->user_ratingID = $sut->user_ratingID;
		$ur->get ();

		$this->assertEquals ( self::BUYRATING_2, $ur->buyrating );
		$this->assertNull ( $ur->transaction );
	}
	
	/*
	 * getUserRatings(int $userID): array
	 */
	public function testGetUserRatingsUserIdInvalid(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateAdditionalUserRatings ();
		$u = new User ( $pdo );
		$u->userID = self::INVALID_ID;
		$system->getUserRatings ( $u );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_USER_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testGetUserRatingsUserIdValid(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateAdditionalUserRatings ();
		$u = new User ( $pdo );
		$u->userID = self::USER_ID_1;
		$stats = $system->getUserRatings ( $u );
		$this->assertEquals ( 5, $stats ['numSellRatings'] );
		$this->assertEquals ( 3.0, $stats ['avgSellRating'] );
		$this->assertEquals ( 4, $stats ['numBuyRatings'] );
		$this->assertEquals ( 2.5, $stats ['avgBuyRating'] );
		$this->assertEquals ( 9, $stats ['totalNumRatings'] );
		$this->assertEquals ( 2.8, $stats ['avgRating'] );
	}
	
	/*
	 *
	 *
	 * ADDITIONAL DATABASE POPULATION METHODS FOR TESTS *
	 *
	 *
	 *
	 */
	protected function populateCategories(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::ROOT_CATEGORY_NAME;
		$root->set ();

		// Insert additional categories
		$j = 1;
		for($i = 1; $i <= 99; $i ++) {
			if ($i % 3 == 0) {
				$j ++;
			}

			$c = new Category ( $pdo );
			$c->{self::PARENT_ID} = $j;
			$c->{self::CATEGORY_NAME} = 'cat' . $i;
			$c->set ();
		}
	}
	protected function populateItems(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );

		$user = new User ( $pdo, [
			'user' => 'user',
			'email' => 'user@gmai.com',
			'password' => 'TestTest88'
		] );

		$user->set ();

		// Insert items.
		$args = [ 
				self::ITEM_ID => self::ITEM_ID_1,
				self::OWNING_USER_ID => $user->userID,
				self::TITLE => self::TITLE_1,
				self::DESCRIPTION => self::DESCRIPTION_1,
				self::QUANTITY => self::QUANTITY_1,
				self::CONDITION => self::CONDITION_1,
				self::PRICE => self::PRICE_1,
				self::ITEM_STATUS => self::STATUS_1 
		];
		
		$item = new Item ( $pdo, $args );
		$item->set ();

		$args2 = [ 
				self::ITEM_ID => self::ITEM_ID_2,
			    self::OWNING_USER_ID => $user->userID,
				self::TITLE => self::TITLE_2,
				self::DESCRIPTION => self::DESCRIPTION_2,
				self::QUANTITY => self::QUANTITY_2,
				self::CONDITION => self::CONDITION_2,
				self::PRICE => self::PRICE_2,
				self::ITEM_STATUS => self::STATUS_2 
		];
		
		$item = new Item ( $pdo, $args2 );
		$item->set ();

		$args3 = [ 
				self::ITEM_ID => self::ITEM_ID_3,
			    self::OWNING_USER_ID => $user->userID,
				self::TITLE => self::TITLE_3,
				self::DESCRIPTION => self::DESCRIPTION_3,
				self::QUANTITY => self::QUANTITY_3,
				self::CONDITION => self::CONDITION_3,
				self::PRICE => self::PRICE_3,
				self::ITEM_STATUS => self::STATUS_3 
		];
		
		$item = new Item ( $pdo, $args3 );
		$item->set ();
	}
	protected function populateCategoryItems(): void {
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Populate the Category Table
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		$root->set ();
		
		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		$c->set ();

		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		$c->set ();

		$c->{self::PARENT_ID} = self::PARENT_ID_2;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_4;
		$c->set ();

		$user = new User($pdo);
		$user->user = "f sfsd fsd f";
		$user->email = "test@test.com";
		$user->password = "fRRR44@fff";
		$user->status = "good";
		$userID = $user->set();

		// Populate the Items Table
		for($i = 1; $i <= 100; $i ++) {
			$item = new Item ( $pdo, [
					self::OWNING_USER_ID => $userID,
					self::TITLE => 'title' . $i,
					self::DESCRIPTION => 'description' . $i,
					self::QUANTITY => 'quantity' . $i,
					self::CONDITION => 'condition' . $i,
					self::PRICE => 'price' . $i,
					self::STATUS => '' 
			] );

			$item->set ();
		}
		
		// Populate the CategoryItems Table
		$j = 2;
		for($i = 1; $i <= 100; $i ++) {
			$ci = new CategoryItems ( $pdo, [ 
					self::CATEGORY_ID => $j,
					SELF::ITEM_ID => $i 
			] );

			$ci->set ();

			if ($i % 34 == 0) {
				$j ++;
			}
		}
	}
	protected function populateDeleteCategory(): bool {
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Populate the Category Table
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		$root->set ();

		// Insert additional categories
		$j = 1;
		for($i = 2; $i <= 101; $i ++) {
			$c = new Category ( $pdo );
			$c->parentID = $j;
			$c->category = 'category' . $i;
			$c->set ();

			if ($i % 3 == 0) {
				$j ++;
			}
		}
		
		$l = 1;
		$k = 1;
		$n = 1;
		for($i = 1; $i <= 100; $i ++) {
			$user = new User ( $pdo );
			$user->user = 'user' . $i;
			$user->email = 'user' . $i . '@gmail.com';
			$user->password = 'PassWord' . $i . $i;
			$user->set ();

			for($j = 1; $j <= 5; $j ++) {
				$item = new Item ( $pdo );
				$item->owningUserID = $user->userID;
				$item->title = 'title' . $k;
				$item->set ();

				$userRating = new UserRatings ( $pdo );
				$userRating->itemID = $item->itemID;
				$userRating->sellrating = 5;
				$userRating->userID = $user->userID;
				$userRating->buyrating = 4;
				$userRating->set ();

				for($m = 1; $m <= 5; $m ++) {
					$note = new Note ( $pdo );
					$note->note = 'note' . $l;
					$note->set ();

					$comment = new Comment ( $pdo );
					$comment->userID = $user->userID;
					$comment->comment = 'comment' . $l;
					$comment->set ();

					$itemNote = new ItemNotes ( $pdo );
					$itemNote->itemID = $item->itemID;
					$itemNote->noteID = $note->noteID;
					$itemNote->set ();

					$itemComment = new ItemComments ( $pdo );
					$itemComment->itemID = $item->itemID;
					$itemComment->commentID = $comment->commentID;
					$itemComment->set ();

					$l ++;
				}

				$userItem = new UserItems ( $pdo );
				$userItem->userID = $user->userID;
				$userItem->itemID = $item->itemID;
				$userItem->relationship = 'relationship' . $i . $l;
				$userItem->userStatus = 'userStatus' . $i . $l;
				$userItem->set ();

				if ($j % 5 == 0) {
					$n ++;
				}

				$categoryItem = new CategoryItems ( $pdo );
				$categoryItem->categoryID = $n;
				$categoryItem->itemID = $item->itemID;
				$categoryItem->set ();

				$k ++;
			}
			
			$l ++;
		}
		return true;
	}

	protected function populateUsers(): void {
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		$root->set ();

		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		$c->set ();

		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		$c->set ();

		// Populate the Users table.
		for($i = 1; $i <= 400; $i ++) {
			${'u' . $i} = new User ( $pdo, [ 
					self::USER => 'user' . $i,
					self::EMAIL => 'email' . $i . '@gmail.com',
					self::PASSWORD => 'PassWord' . $i 
			] );

			${'u' . $i}->set ();
			${'u' . $i}->get ();
			${'u' . $i}->activate ();
		}
	}
	protected function populateUserItems(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		$root->set ();

		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		$c->set ();

		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		$c->set ();

		$l = 1;
		for($i = 1; $i <= 3; $i ++) {
			$user = new User ( $pdo );
			$user->user = 'user' . $i;
			$user->email = 'user' . $i . '@gmail.com';
			$user->password = 'PassWord' . $i . $i;
			$user->set ();

			for($j = 1; $j <= 5; $j ++) {
				$item = new Item ( $pdo );
				$item->owningUserID = $user->userID;
				$item->title = 'title' . $l;
				$item->set ();

				$userItem = new UserItems ( $pdo );
				$userItem->userID = $user->userID;
				$userItem->itemID = $item->itemID;
				$userItem->relationship = 'relationship' . $i . $l;
				$userItem->userStatus = 'userStatus' . $i . $l;

				if ($userItem->userID == 3 && $userItem->itemID == 15) {
					// Don't set.
				} else {
					$userItem->set ();
				}

				$l ++;
			}
		}
	}
	protected function populateItemComments(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		$user = new User ( $pdo, [ 
				'user' => 'user',
				'email' => 'user@gmai.com',
				'password' => 'TestTest88' 
		] );
		try {
			$user->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		$l = 1;
		for($i = 1; $i <= 3; $i ++) {
			$item = new Item ( $pdo );
			$item->owningUserID = $user->userID;
			$item->title = 'title' . $i;
			$item->set ();

			for($j = 1; $j <= 5; $j ++) {
				$comment = new Comment ( $pdo );
				$comment->userID = self::USER_ID_1;
				$comment->comment = 'comment' . $l;
				$comment->set ();

				$itemComment = new ItemComments ( $pdo );
				$itemComment->itemID = $i;
				$itemComment->commentID = $l;

				if ($itemComment->itemID == 3 && $itemComment->commentID == 15) {
					// Don't set.
				} else {
					$itemComment->set ();
				}

				$l ++;
			}
		}
	}
	protected function populateItemNotes(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );

		$user = new User ( $pdo, [
			'user' => 'user',
			'email' => 'user@gmai.com',
			'password' => 'TestTest88'
		] );

		$user->set ();

		$l = 1;
		for($i = 1; $i <= 3; $i ++) {
			$item = new Item ( $pdo );
			$item->owningUserID = $user->userID;
			$item->title = 'title' . $i;
			$item->set ();

			for($j = 1; $j <= 5; $j ++) {
				$note = new Note ( $pdo );
				$note->note = 'note' . $l;
				$note->set ();

				$itemNote = new ItemNotes ( $pdo );
				$itemNote->itemID = $i;
				$itemNote->noteID = $l;

				if ($itemNote->itemID == 3 && $itemNote->noteID == 15) {
					// Don't set.
				} else {
					$itemNote->set ();
				}

				$l ++;
			}
		}
	}
	protected function populateUserRatings(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );

		$u1 = new User ( $pdo, [
				self::USER => self::USER_ONE,
				self::EMAIL => self::EMAIL_ADDRESS_ONE,
				self::PASSWORD => self::PASSWORD_ONE
		] );

		$u1->set ();

		$u2 = new User ( $pdo, [
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::PASSWORD => self::PASSWORD_TWO
		] );

		$u2->set ();

		$i1 = new Item ( $pdo, [
				self::OWNING_USER_ID => $u1->userID,
				self::TITLE => self::TITLE_1
		] );

		$i1->set ();

		$i2 = new Item ( $pdo, [
				self::OWNING_USER_ID => $u1->userID,
				self::TITLE => self::TITLE_2
		] );

		$i2->set ();

		$ui = new UserItems ( $pdo, [
				self::USER_ID => self::USER_ID_1,
				self::ITEM_ID => self::ITEM_ID_2
		] );

		$ui->set ();

		$ur = new UserRatings ( $pdo, [
				self::ITEM_ID => self::ITEM_ID_1,
				self::SELLRATING => self::SELLRATING_1,
				self::USER_ID => self::USER_ID_1,
				self::BUYRATING => self::BUYRATING_1
		] );

		$ur->set ();
	}
	protected function addSellerRating(): UserRatings {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );

		$u1 = new User ( $pdo, [
				self::USER => self::USER_ONE,
				self::EMAIL => self::EMAIL_ADDRESS_ONE,
				self::PASSWORD => self::PASSWORD_ONE
		] );

		$u1->set ();

		$u2 = new User ( $pdo, [
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::PASSWORD => self::PASSWORD_TWO
		] );

		$u2->set ();

		$i1 = new Item ( $pdo, [
				self::OWNING_USER_ID => $u1->userID,
				self::TITLE => self::TITLE_1
		] );

		$i1->set ();

		$i2 = new Item ( $pdo, [
				self::OWNING_USER_ID => $u1->userID,
				self::TITLE => self::TITLE_2
		] );
		$i2->set ();

		$ui = new UserItems ( $pdo, [
				self::USER_ID => self::USER_ID_1,
				self::ITEM_ID => self::ITEM_ID_1
		] );

		$ui->set ();

		$ui = new UserItems ( $pdo, [
				self::USER_ID => self::USER_ID_1,
				self::ITEM_ID => self::ITEM_ID_2
		] );

		$ui->set ();

		$ur = new UserRatings ( $pdo, [
				self::ITEM_ID => self::ITEM_ID_1,
				self::SELLRATING => self::SELLRATING_1,
				self::USER_ID => self::USER_ID_1,
				self::BUYRATING => self::BUYRATING_1
		] );

		$ur->set ();

		$sut = new UserRatings ( $pdo );
		$sut->userID = self::USER_ID_2;
		$sut->itemID = self::ITEM_ID_2;
		$sut->sellrating = self::SELLRATING_2;
		$sut->addSellerRating ();

		$sut = new UserRatings ( $pdo );
		$sut->user_ratingID = self::USER_RATING_ID_2;

		return $sut->get ();
	}

	protected function populateAdditionalUserRatings(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		$root->set ();

		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		$c->set ();

		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		$c->set ();

		$l = 1;
		for($i = 1; $i <= 3; $i ++) {
			$user = new User ( $pdo );
			$user->user = 'user' . $i;
			$user->email = 'user' . $i . '@gmail.com';
			$user->password = 'PassWord' . $i . $i;
			$user->set ();

			for($j = 1; $j <= 5; $j ++) {
				$item = new Item ( $pdo );
				$item->owningUserID = $user->userID;
				$item->title = 'title' . $l;
				$item->set ();

				$userItem = new UserItems ( $pdo );
				$userItem->userID = $i;
				$userItem->itemID = $l;
				$userItem->relationship = 'relationship' . $i . $l;
				$userItem->userStatus = 'userStatus' . $i . $l;

				if ($userItem->userID == 3 && $userItem->itemID == 15) {
					// Don't set.
				} else {
					$userItem->set ();
				}

				$l ++;
			}
		}
		
		$k = 1;
		$l = 5;
		
		for($i = 1; $i <= 14; $i ++) {
			if ($i > 0 && $i < 6) {
				$j = 2;
			} elseif ($i > 5 && $i < 11) {
				$j = 3;
			} else {
				$j = 1;
			}
			
			$ur = new UserRatings ( $pdo, [ 
					self::ITEM_ID => $i,
					self::SELLRATING => $k,
					self::USER_ID => $j,
					self::BUYRATING => $l 
			] );

			$ur->set ();

			if ($k == 5) {
				$k = 0;
			}
			$k ++;
			if ($l == 1) {
				$l = 6;
			}
			$l --;
		}
	}
	protected function populateAll(): void {
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Populate the Categories Table
		
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		$root->set ();
		
		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		$c->set ();

		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		$c->set ();

		$c->{self::PARENT_ID} = self::PARENT_ID_2;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_4;
		$c->set ();
		
		$l = 1;
		$k = 1;

		for($i = 1; $i <= 3; $i ++) {
			$user = new User ( $pdo );
			$user->user = 'user' . $i;
			$user->email = 'user' . $i . '@gmail.com';
			$user->password = 'PassWord' . $i . $i;
			$user->set ();

			for($j = 1; $j <= 5; $j ++) {
				$item = new Item ( $pdo );
				$item->owningUserID = $user->userID;
				$item->title = 'title' . $k;
				$item->set ();

				$userRating = new UserRatings ( $pdo );
				$userRating->itemID = $k;
				$userRating->sellrating = 5;
				$userRating->userID = $i;
				$userRating->buyrating = 4;
				$userRating->set ();

				for($m = 1; $m <= 5; $m ++) {
					$note = new Note ( $pdo );
					$note->note = 'note' . $l;
					$note->set ();

					$comment = new Comment ( $pdo );
					$comment->userID = $user->userID;
					$comment->comment = 'comment' . $l;
					$comment->set ();

					$itemNote = new ItemNotes ( $pdo );
					$itemNote->itemID = $item->itemID;
					$itemNote->noteID = $note->noteID;
					$itemNote->set ();

					$itemComment = new ItemComments ( $pdo );
					$itemComment->itemID =$item->itemID;
					$itemComment->commentID = $comment->commentID;
					$itemComment->set ();

					$l ++;
				}

				$userItem = new UserItems ( $pdo );
				$userItem->userID = $user->userID;
				$userItem->itemID = $item->itemID;
				$userItem->relationship = 'relationship' . $i . $l;
				$userItem->userStatus = 'userStatus' . $i . $l;
				$userItem->set ();

				$categoryItem = new CategoryItems ( $pdo );
				$categoryItem->categoryID = $i + 1;
				$categoryItem->itemID = $item->itemID;
				$categoryItem->set ();

				$k ++;
			}
			
			$l ++;
		}
	}
}
