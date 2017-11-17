<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - s3418650@student.rmit.edu.au
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
 * search(string $searchString): array
 * -- testSearchNegativeResult(): void
 * -- testSearchTitlePositiveResult(): void
 * -- testSearchDescriptionPositiveResult(): void
 *
 * searchArray(string $searchString): array
 * -- testSearchArrayTitleNegativeResult(): void
 * -- testSearchArrayAndWord(): void
 * -- testSearchArrayNotWord(): void
 * -- testSearchArrayOrWord(): void
 * -- testSearchArrayAndWordAndWord(): void
 * -- testSearchArrayNotWordAndWord(): void
 * -- testSearchArrayOrWordAndWord(): void
 * -- testSearchArrayAndWordNotWord(): void
 * -- testSearchArrayNotWordNotWord(): void
 * -- testSearchArrayOrWordNotWord(): void
 * -- testSearchArrayAndWordOrWord(): void
 * -- testSearchArrayNotWordOrWord(): void
 * -- testSearchArrayOrWordOrWord(): void
 * -- testSearchArrayAndWordAndWordAndWord(): void
 * -- testSearchArrayAndWordTildaWord(): void
 * -- testSearchArrayAndWordAndGreaterWordLessWord(): void
 *
 * searchAdvanced(string $searchText, string $srchMinPrice, string $srchMaxPrice, string $srchMinQuantity, string $srchCondition, string $srchStatus, int $majorCategoryID, int $minorCategoryID): array 
 * -- testSearchAdvancedTitleNegativeResult(): void
 * -- testSearchAdvancedAndWord(): void
 * -- testSearchAdvancedNotWord(): void
 * -- testSearchAdvancedOrWord(): void
 * -- testSearchAdvancedAndWordAndWord(): void
 * -- testSearchAdvancedNotWordAndWord(): void
 * -- testSearchAdvancedOrWordAndWord(): void
 * -- testSearchAdvancedAndWordNotWord(): void
 * -- testSearchAdvancedNotWordNotWord(): void
 * -- testSearchAdvancedOrWordNotWord(): void
 * -- testSearchAdvancedAndWordOrWord(): void
 * -- testSearchAdvancedNotWordOrWord(): void
 * -- testSearchAdvancedOrWordOrWord(): void
 * -- testSearchAdvancedAndWordAndWordAndWord(): void
 * -- testSearchAdvancedAndWordTildaWord(): void
 * -- testSearchAdvancedAndWordAndGreaterWordLessWord(): void
 * -- testSearchAdvancedMinorCategoryIDNotExist(): void
 * -- testSearchAdvancedMinorCategoryIDExist(): void
 * -- testSearchAdvancedMajorCategoryIDNotExist(): void
 * -- testSearchAdvancedMajorCategoryIDExist(): void
 * -- testSearchAdvancedMinPrice(): void
 * -- testSearchAdvancedMaxPrice(): void
 * -- testSearchAdvancedMinPriceMaxPrice(): void
 * -- testSearchAdvancedMinQuantity(): void
 * -- testSearchAdvancedCondition(): void
 * -- testSearchAdvancedStatus(): void
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
require_once dirname ( __FILE__ ) . '/../../../model/Items.php';
require_once dirname ( __FILE__ ) . '/../../../model/ItemNotes.php';
require_once dirname ( __FILE__ ) . '/../../../model/Note.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/UserComments.php';
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
	const TYPE = 'type';
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
	const ERROR_USER_ID_NOT_EXIST = 'Invalid userID!';
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
	const STATUS_SUSPENDED = 1;
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
	const CONDITION_1 = 'New';
	const PRICE_1 = 'price1';
	const STATUS_1 = 'Active';
	const ITEM_ID_2 = 2;
	const TITLE_2 = 'title2';
	const DESCRIPTION_2 = 'description2';
	const QUANTITY_2 = 'quantity2';
	const CONDITION_2 = 'New';
	const PRICE_2 = 'price2';
	const STATUS_2 = 'Active';
	const ITEM_ID_3 = 3;
	const TITLE_3 = 'title3';
	const DESCRIPTION_3 = 'description3';
	const QUANTITY_3 = 'quantity3';
	const CONDITION_3 = 'Used';
	const PRICE_3 = 'price3';
	const STATUS_3 = 'Active';
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
	
	// Search Constants
	const STRING_NOT_EXIST = 'Hammer';
	const TITLE_EXIST = 'Word3 word4';
	const DESCRIPTION_EXIST = 'word7 word8';
	const AND_WORD = '+word12';
	const NOT_WORD = '-word12';
	const OR_WORD = 'word12';
	const AND_WORD_AND_WORD = '+word12 +word13';
	const NOT_WORD_AND_WORD = '-word12 +word13';
	const OR_WORD_AND_WORD = 'word12 +word13';
	const AND_WORD_NOT_WORD = '+word12 -word13';
	const NOT_WORD_NOT_WORD = '-word12 -word13';
	const OR_WORD_NOT_WORD = 'word12 -word13';
	const AND_WORD_OR_WORD = '+word12 word13';
	const NOT_WORD_OR_WORD = '-word12 word13';
	const OR_WORD_OR_WORD = 'word12 word13';
	const AND_WORD_AND_WORD_AND_WORD = '+word12 +word13 +word14';
	const AND_WORD_TILDA_WORD = '+word12 ~word13';
	const AND_WORD_AND_GREATER_WORD_LESS_WORD = 'word12 +(>word13 <word14)';
	const SEARCH_INVALID_ID = 5000;
	const SEARCH_VALID_ID = 10;
	
	const SEARCH_TEXT = 'srchText';
	const SEARCH_MINOR_CATEGORY_ID = 'srchMinorCategory';
	const SEARCH_MAJOR_CATEGORY_ID = 'srchMajorCategory';
	const SEARCH_MIN_PRICE = 'srchMinPrice';
	const SEARCH_MAX_PRICE = 'srchMaxPrice';
	const SEARCH_MIN_QUANTITY = 'srchQuantity';
	const SEARCH_CONDITION = 'srchCondition';
	const SEARCH_STATUS = 'srchStatus';
	
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
		if ($system->blockUser ( $sut )) {
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
		if ($system->blockUser ( $sut )) {
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
		
		if ($system->blockUser ( $sut )) {
			$sut = $system->getUser ( $sut );
			$this->assertEquals ( self::STATUS_SUSPENDED, $sut->blocked );
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
		$this->assertSame ( 400, $category->categoryID );
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
		$this->assertSame ( 3, $category->categoryID );
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

		// sorted by name.
		$this->assertEquals ( 11, $cats[0]->categoryID );
		$this->assertEquals ( 4, $cats[0]->parentID );
		$this->assertSame ( 'cat10', $cats[0]->category );

		$this->assertEquals ( 12, $cats[1]->categoryID );
		$this->assertEquals ( 4, $cats[1]->parentID );
		$this->assertSame ( 'cat11', $cats[1]->category );

		$this->assertEquals ( 10, $cats[2]->categoryID );
		$this->assertEquals ( 4, $cats[2]->parentID );
		$this->assertSame ( 'cat9', $cats[2]->category );
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
		$system->getCategoryItemsByPage ( $c, self::ITEMS_PAGE_NUMBER_ZERO, self::ITEMS_PER_PAGE, 'ForSale' );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ZERO, $_SESSION ['error'] );
	}
	public function testGetCategoryItemsByPageItemsPerPageZero(): void {
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$c = new Category ( $pdo );
		$c->categoryID = self::CATEGORY_ID_3;
		$system->getCategoryItemsByPage ( $c, self::ITEMS_PAGE_NUMBER, self::ITEMS_PER_PAGE_ZERO, 'ForSale' );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ZERO, $_SESSION ['error'] );
	}

	public function testGetCategoryItemsByPageSuccess(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateCategoryItems ();
		$c = new Category ( $pdo );
		$c->categoryID = self::CATEGORY_ID_3;
		$ci = $system->getCategoryItemsByPage ( $c, self::ITEMS_PAGE_NUMBER, self::ITEMS_PER_PAGE, 'ForSale' );
		$start = 47;
		foreach ( $ci as $item ) {
			$this->assertEquals ( $start, $item->itemID );
			$start ++;
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
		$this->assertSame ( 4000, $item->itemID );
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
		$this->assertSame ( 2, $item->itemID );
		$this->assertSame ( self::TITLE_2, $item->title );
		$this->assertSame ( self::DESCRIPTION_2, $item->description );
		$this->assertSame ( self::QUANTITY_2, $item->quantity );
		$this->assertSame ( self::CONDITION_2, $item->itemcondition );
		$this->assertSame ( 'Wanted', $item->type );
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
		$i->itemcondition = 'New';
		$i->type = 'ForSale';
		$i->title = self::TITLE_16;

		$itemID = $system->addItem ( $i, self::CATEGORY_ID_2 );
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
		$comment = new Comment ( $pdo );
		$comment->toUserID = self::USER_ID_1;
		$comment->fromUserID = self::USER_ID_2;
		$comment->itemID = self::ITEM_ID_1;
		$comment->status = 'unread';
		$system->addItemComment ( $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_COMMENT_EMPTY, $_SESSION ['error'] );
	}
	public function testAddItemCommentInvalidItemId(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->comment = self::COMMENT_NEW;
		$comment->toUserID = self::USER_ID_1;
		$comment->fromUserID = self::USER_ID_1;
		$comment->itemID =  self::INVALID_ID;
		$comment->status = 'unread';
		$system->addItemComment ( $comment );

		$this->assertTrue( isset ( $_SESSION ['error'] ));
		$this->assertEquals ( self::ERROR_ITEM_ID_NOT_EXIST, $_SESSION ['error'] );
	}
	public function testAddItemCommentSuccess(): void {
		$this->populateItemComments ();
		unset ( $_SESSION ['error'] );
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$comment = new Comment ( $pdo );
		$comment->toUserID = self::USER_ID_1;
		$comment->fromUserID = self::USER_ID_1;
		$comment->itemID = self::ITEM_ID_3;
		$comment->comment = self::COMMENT_NEW;
		$comment->status = 'unread';

		$this->assertTrue ( $system->addItemComment ( $comment ) );
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
	}

	
	/**
	 * Does not return anything.
	 */
	public function testSearchNegativeResult(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->search(self::STRING_NOT_EXIST);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns anything from either title or description that has
	 * either Word3 or word4.  Case insensitive.
	 */
	public function testSearchTitlePositiveResult(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->search(self::TITLE_EXIST);
		$this->assertEquals(15, count($array));
	}
	
	/**
	 * Returns anything from either title or description that has
	 * either Word7 or word8.  Case insensitive.
	 */
	public function testSearchDescriptionPositiveResult(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->search(self::DESCRIPTION_EXIST);
		$this->assertEquals(35, count($array));
	}
	
	/*
	 * searchArray(array $searchArray): array
	 */
	/**
	 * Returns nothing as string does not exist.
	 */
	public function testSearchArrayTitleNegativeResult(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::STRING_NOT_EXIST);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 40 rows.  Each row has word12 (case insensitive)
	 * somewhere in either the title or description.
	 */
	public function testSearchArrayAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::AND_WORD);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * This does not return any rows as the actual result contains
	 * more than half the database.
	 */
	public function testSearchArrayNotWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::NOT_WORD);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 40 rows.  Each row has word12 (case insensitive)
	 * somewhere in either the title or description.
	 */
	public function testSearchArrayOrWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::OR_WORD);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 35 rows.  Each row has both word12 (case insensitive)
	 * and word 13 somewhere in either the title or description.
	 */
	public function testSearchArrayAndWordAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::AND_WORD_AND_WORD);
		$this->assertEquals(35, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain Word13 only.
	 */
	public function testSearchArrayNotWordAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::NOT_WORD_AND_WORD);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 40 Rows. Each row has either both word12 and
	 * word13 in either the title or description, or has just
	 * word13 in either the title or description.
	 */
	public function testSearchArrayOrWordAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::OR_WORD_AND_WORD);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain word12 only.
	 */
	public function testSearchArrayAndWordNotWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::AND_WORD_NOT_WORD);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 0 rows as in actual fact more than half the
	 * database would be returned in this instance.
	 */
	public function testSearchArrayNotWordNotWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::NOT_WORD_NOT_WORD);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain word12 only.
	 */
	public function testSearchArrayOrWordNotWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::OR_WORD_NOT_WORD);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 40 Rows.  All rows contain word12.  35 rows
	 * contain word12 and word13.  5 rows contain only
	 * word12.  The rows that contain only word12 were expected
	 * to rate lower than those rows that also contain word13,
	 * however the opposite seems to be the case.
	 */
	public function testSearchArrayAndWordOrWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::AND_WORD_OR_WORD);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 5 rows.  These rows only contain word13.
	 */
	public function testSearchArrayNotWordOrWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::NOT_WORD_OR_WORD);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 45 Rows.  These rows contain either word12 only,
	 * word13 only, or both word12 and word13.
	 */
	public function testSearchArrayOrWordOrWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::OR_WORD_OR_WORD);
		$this->assertEquals(45, count($array));
	}
	
	/**
	 * Returns 30 Rows. Each row contains all word12, word13 and
	 * word14 across the title and description tables.
	 */
	public function testSearchArrayAndWordAndWordAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::AND_WORD_AND_WORD_AND_WORD);
		$this->assertEquals(30, count($array));
	}
	
	/**
	 * Returns 40 rows.  The first 5 rows contain word12 only and rank
	 * higher than the rows that also contain word13.
	 */
	public function testSearchArrayAndWordTildaWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::AND_WORD_TILDA_WORD);
		$this->assertEquals(40, count($array));
		$this->assertEquals(16, $array[0]->itemID);
		$this->assertEquals(17, $array[1]->itemID);
		$this->assertEquals(18, $array[2]->itemID);
		$this->assertEquals(19, $array[3]->itemID);
		$this->assertEquals(20, $array[4]->itemID);
	}
	
	/**
	 * Returns 45 rows.  Rows that don't contain word14 rank higher than those that do.
	 */
	public function testSearchArrayAndWordAndGreaterWordLessWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$array = $system->searchArray(self::AND_WORD_AND_GREATER_WORD_LESS_WORD);
		$this->assertEquals(45, count($array));
		$this->assertEquals(21, $array[0]->itemID);
		$this->assertEquals(22, $array[1]->itemID);
		$this->assertEquals(23, $array[2]->itemID);
		$this->assertEquals(24, $array[3]->itemID);
		$this->assertEquals(25, $array[4]->itemID);
	}
	
	/*
	 * searchAdvanced(array $args): array
	 */
	/**
	 * Returns nothing as string does not exist.
	 */
	public function testSearchAdvancedTitleNegativeResult(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::STRING_NOT_EXIST;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 40 rows.  Each row has word12 (case insensitive)
	 * somewhere in either the title or description.
	 */
	public function testSearchAdvancedAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::AND_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * This does not return any rows as the actual result contains
	 * more than half the database.
	 */
	public function testSearchAdvancedNotWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::NOT_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 40 rows.  Each row has word12 (case insensitive)
	 * somewhere in either the title or description.
	 */
	public function testSearchAdvancedOrWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::OR_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 35 rows.  Each row has both word12 (case insensitive)
	 * and word 13 somewhere in either the title or description.
	 */
	public function testSearchAdvancedAndWordAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::AND_WORD_AND_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(35, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain Word13 only.
	 */
	public function testSearchAdvancedNotWordAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::NOT_WORD_AND_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 40 Rows. Each row has either both word12 and
	 * word13 in either the title or description, or has just
	 * word13 in either the title or description.
	 */
	public function testSearchAdvancedOrWordAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::OR_WORD_AND_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain word12 only.
	 */
	public function testSearchAdvancedAndWordNotWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::AND_WORD_NOT_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 0 rows as in actual fact more than half the
	 * database would be returned in this instance.
	 */
	public function testSearchAdvancedNotWordNotWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::NOT_WORD_NOT_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain word12 only.
	 */
	public function testSearchAdvancedOrWordNotWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::OR_WORD_NOT_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 40 Rows.  All rows contain word12.  35 rows
	 * contain word12 and word13.  5 rows contain only
	 * word12.  The rows that contain only word12 were expected
	 * to rate lower than those rows that also contain word13,
	 * however the opposite seems to be the case.
	 */
	public function testSearchAdvancedAndWordOrWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::AND_WORD_OR_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 5 rows.  These rows only contain word13.
	 */
	public function testSearchAdvancedNotWordOrWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::NOT_WORD_OR_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 45 Rows.  These rows contain either word12 only,
	 * word13 only, or both word12 and word13.
	 */
	public function testSearchAdvancedOrWordOrWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::OR_WORD_OR_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(45, count($array));
	}
	
	/**
	 * Returns 30 Rows. Each row contains all word12, word13 and
	 * word14 across the title and description tables.
	 */
	public function testSearchAdvancedAndWordAndWordAndWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::AND_WORD_AND_WORD_AND_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(30, count($array));
	}
	
	/**
	 * Returns 40 rows.  The first 5 rows contain word12 only and rank
	 * higher than the rows that also contain word13.
	 */
	public function testSearchAdvancedAndWordTildaWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::AND_WORD_TILDA_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(40, count($array));
		$this->assertEquals(16, $array[0]->itemID);
		$this->assertEquals(17, $array[1]->itemID);
		$this->assertEquals(18, $array[2]->itemID);
		$this->assertEquals(19, $array[3]->itemID);
		$this->assertEquals(20, $array[4]->itemID);
	}
	
	/**
	 * Returns 45 rows.  Rows that don't contain word14 rank higher than those that do.
	 */
	public function testSearchAdvancedAndWordAndGreaterWordLessWord(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = self::AND_WORD_AND_GREATER_WORD_LESS_WORD;
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(45, count($array));
		$this->assertEquals(21, $array[0]->itemID);
		$this->assertEquals(22, $array[1]->itemID);
		$this->assertEquals(23, $array[2]->itemID);
		$this->assertEquals(24, $array[3]->itemID);
		$this->assertEquals(25, $array[4]->itemID);
	}
	
	/**
	 * Returns 0 rows.
	 */
	public function testSearchAdvancedMinorCategoryIDNotExist(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = self::SEARCH_INVALID_ID;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 5 rows.
	 */
	public function testSearchAdvancedMinorCategoryIDExist(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = self::SEARCH_VALID_ID;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 0 rows.
	 */
	public function testSearchAdvancedMajorCategoryIDNotExist(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = 0;
		$majorCategoryID = self::SEARCH_INVALID_ID;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 15 rows.
	 */
	public function testSearchAdvancedMajorCategoryIDExist(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = 0;
		$majorCategoryID = self::SEARCH_VALID_ID;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(15, count($array));
	}
	
	/**
	 * Returns 160 rows.
	 */
	public function testSearchAdvancedMinPrice(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '70.00';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(160, count($array));
	}
	
	/**
	 * Returns 15 rows.
	 */
	public function testSearchAdvancedMaxPrice(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '5';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(15, count($array));
	}
	
	/**
	 * Returns 9 rows.
	 */
	public function testSearchAdvancedMinPriceMaxPrice(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '3.20';
		$srchMaxPrice = '5.00';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(9, count($array));
	}
	
	/**
	 * Returns 55 rows.
	 */
	public function testSearchAdvancedMinQuantity(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '50';
		$srchCondition = '';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(55, count($array));
	}
	
	/**
	 * Returns 250 rows.
	 */
	public function testSearchAdvancedCondition(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = 'Used';
		$srchStatus = '';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(250, count($array));
	}
	
	/**
	 * Returns 165 rows.
	 */
	public function testSearchAdvancedStatus(): void {
		$pdo = TestPDO::getInstance ();
		$system = new System ( $pdo );
		$this->populateSearch();
		$searchText = '';
		$minorCategoryID = 0;
		$majorCategoryID = 0;
		$srchMinPrice = '0';
		$srchMaxPrice = '999999999999999';
		$srchMinQuantity = '1';
		$srchCondition = '';
		$srchStatus = 'ForSale';
		$array = $system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$this->assertEquals(165, count($array));
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
			'email' => 'user@gmail.com',
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
			    self::TYPE => 'Wanted',
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
				self::TYPE => 'Wanted',
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
				self::TYPE => 'Wanted',
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
					self::CONDITION => 'New',
				    self::TYPE => 'ForSale',
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
					self::ITEM_ID => $i
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
				$item->itemcondition = 'New';
				$item->type = 'ForSale';
				$item->title = 'title' . $k;
				$item->set ();

				for($m = 1; $m <= 5; $m ++) {
					$note = new Note ( $pdo );
					$note->note = 'note' . $l;
					$note->set ();

					$comment = new Comment ( $pdo );
					$comment->toUserID = $user->userID;
					$comment->fromUserID = $user->userID;
					$comment->itemID = $item->itemID;
					$comment->comment = 'comment' . $l;
					$comment->status = 'status' . $l;
					$comment->set ();

					$itemNote = new ItemNotes ( $pdo );
					$itemNote->itemID = $item->itemID;
					$itemNote->noteID = $note->noteID;
					$itemNote->set ();

					$l ++;
				}

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
				$item->itemcondition = 'Used';
				$item->type = 'ForSale';
				$item->title = 'title' . $l;
				$item->set ();

				$categoryItem = new CategoryItems ( $pdo );
				$categoryItem->categoryID = 2;
				$categoryItem->itemID = $item->itemID;
				$categoryItem->set ();

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
			$item->itemcondition = 'Used';
			$item->type = 'Wanted';
			$item->title = 'title' . $i;
			$item->set ();

			for($j = 1; $j <= 5; $j ++) {
				$comment = new Comment ( $pdo );
				$comment->toUserID = $user->userID;
				$comment->fromUserID = $user->userID;
				$comment->itemID = $item->itemID;
				$comment->comment = 'comment' . $l;
				$comment->status = 'status' . $l;
				$comment->set ();

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
			$item->itemcondition = 'Used';
			$item->type = 'Wanted';
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
				$item->type = 'ForSale';
				$item->itemcondition = 'Used';
				$item->title = 'title' . $l;
				$item->set ();

				$l ++;
			}
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
				$item->type = 'ForSale';
				$item->itemcondition = 'New';
				$item->title = 'title' . $k;
				$item->set ();

				for($m = 1; $m <= 5; $m ++) {
					$note = new Note ( $pdo );
					$note->note = 'note' . $l;
					$note->set ();

					$comment = new Comment ( $pdo );
					$comment->toUserID = $user->userID;
					$comment->fromUserID = $user->userID;
					$comment->itemID = $item->itemID;
					$comment->comment = 'comment' . $l;
					$comment->status = 'status' . $l;
					$comment->set ();

					$itemNote = new ItemNotes ( $pdo );
					$itemNote->itemID = $item->itemID;
					$itemNote->noteID = $note->noteID;
					$itemNote->set ();

					$l ++;
				}

				$categoryItem = new CategoryItems ( $pdo );
				$categoryItem->categoryID = $i + 1;
				$categoryItem->itemID = $item->itemID;
				$categoryItem->set ();

				$k ++;
			}
			
			$l ++;
		}
	}
	
	protected function populateSearch(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Populate the Category Table
		$j = 1;
		for($i = 1; $i <= 101; $i ++) {
			if($i == 1){
				$c = new Category ( $pdo );
				$c->category = 'category1';
				try {
					$c->set ();
				} catch (ModelException $e) {
					$this->assertEquals('Exception', $e->getMessage());
				}
				
			} else {
				$c = new Category ( $pdo );
				$c->parentID = $j;
				$c->category = 'category' . $i;
				try {
					$c->set ();
				} catch (ModelException $e) {
					$this->assertEquals('Exception', $e->getMessage());
				}
				
				if ($i % 3 == 0) {
					$j ++;
				}
			}
		}
		
		// Populate User Table
		
		$user = new User ( $pdo, [
				'user' => 'user',
				'email' => 'user@gmail.com',
				'password' => 'TestTest88'
		] );
		
		try {
			$user->userID = $user->set ();
		} catch (ModelException $e) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		// Populate the Item and Category_items Tables
		
		for($i = 2; $i <= 101; $i ++) {
			for($j = 1; $j <= 5; $j++){
				$item = new Item($pdo);
				$item->owningUserID = 1;
				$item->title = 'Word' . $i . ' word' . ($i + 1) . ' word' . ($i + 2) . ' word' . ($i + 3);
				$item->description = 'Word' . ($i + 4) . ' word' . ($i + 5) . ' word' . ($i + 6) . ' word' . ($i + 7);
				$item->price = $i . '.' . $j . '0';
				$item->quantity = ceil($i/$j);
				if($i % 2 == 0){
					$item->itemcondition = 'New';
				} else {
					$item->itemcondition = 'Used';
				}
				if($i % 3 == 0){
					$item->type = 'ForSale';
				} else {
					$item->type = 'Wanted';
				}
				try {
					$categoryItems = new CategoryItems($pdo);
					$categoryItems->itemID= $item->set();
					$categoryItems->categoryID = $i;
					$categoryItems->set();
				} catch (ModelException $e) {
					$this->assertEquals('Exception', $e->getMessage());
				}
			}
		}
	}
}
