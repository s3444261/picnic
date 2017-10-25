<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */
if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}

/**
 * The System class transmits information from the Humphree
 * Programming Interface to the model and back to Humphree.
 */
class System {
	private $db;
	const SEARCH_STRING = 'searchString';
	const SUSPENDED = 'suspended';
	const USER_RATING_NOT_ADDED = 'The UserRating was not added!';
	const ERROR_ITEM_NOT_EXIST = 'Item does not exist!';
	const ERROR_USER_NOT_EXIST = 'User does not exist!';
	const ERROR_CATEGORY_NOT_EXIST = 'Category does not exist!';
	const ERROR_CATEGORY_ID_NOT_EXIST = 'The categoryID does not exist!';
	
	// Constructor
	function __construct(PDO $pdo) {
		$this->db = $pdo;
	}
	
	/**
	 * Creates an account for a user.
	 *
	 * @param User $user
	 *        	User Object.
	 * @return bool
	 */
	public function createAccount(User $user): bool {
		try {
			if ($user->set () > 0) {
				return true;
			} else {
				return false;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Returns a UserID based on a 32bit activation code.
	 *
	 * @param User $user
	 *        	User object.
	 * @return int
	 */
	public function getUserIdByActivationCode(User $user): int {
		try {
			return $user->getUserIdByActivationCode ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return 0;
		}
	}
	
	/**
	 * Returns the UserID for the given email address.
	 *
	 * @param User $user
	 *        	User object containing the email address.
	 * @return int The UserID if one was found, otherwise zero.
	 */
	public function getUserIdByEmailAddress(User $user): int {
		try {
			return $user->getUserIdByEmail ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return 0;
		}
	}
	
	/**
	 * Activates a user account.
	 *
	 * @param User $user
	 *        	User Object.
	 * @return bool
	 */
	public function activateAccount(User $user): bool {
		if ($user->activate ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows a password to be changed for the account.
	 *
	 * @param User $user
	 *        	User object.
	 * @return bool
	 */
	public function changePassword(User $user): bool {
		try {
			return $user->updatePassword ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Allows a user to have a new random password sent to their email address.
	 *
	 * @param User $user
	 *        	User Object.
	 * @return User
	 */
	public function forgotPassword(User $user): User {
		try {
			$user->userID = $user->getUserIdByEmail ();
			$user->password = $user->getRandomPassword ();
			$randomPassword = $user->password;
			try {
				$user->updatePassword ();
				$user->password = $randomPassword;
				return $user;
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getMessage ();
				return $user;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return $user;
		}
	}
	
	/**
	 * Allows an administrator to add a user to the system.
	 *
	 * @param User $user
	 *        	User object.
	 * @return int The new user's ID. Zero means failure.
	 */
	public function addUser(User $user): int {
		try {
			$user->set ();
			try {
				$user->get ();
				return $user->userID;
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getMessage ();
				return 0;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return 0;
		}
	}
	
	/**
	 * Allows an administrator to update a user.
	 *
	 * @param User $user
	 *        	User Object.
	 * @return bool
	 */
	public function updateUser(User $user): bool {
		try {
			$user->update ();
			return true;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
			return false;
		}
	}
	
	/**
	 * Retrieves a user based on the users ID.
	 *
	 * @param User $user
	 *        	User Object.
	 * @return User
	 */
	public function getUser(User $user): User {
		$u = new User ( $this->db );
		$u->userID = $user->userID;
		try {
			$u = $u->get ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}
		return $u;
	}
	
	/**
	 * Retrieves all users and returns them based on number of pages and number
	 * of users per page.
	 *
	 * @param int $pageNumber
	 *        	The page number.
	 * @param int $usersPerPage
	 *        	The number of users listed on the page.
	 * @return array
	 */
	public function getUsers(int $pageNumber, int $usersPerPage): array {
		$usersArray = array ();
		$users = new Users ( $this->db );
		try {
			$usersArray = $users->getUsers ( $pageNumber, $usersPerPage );
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
			return $usersArray;
		}
		return $usersArray;
	}
	
	/**
	 * Allows an administrator to suspend an account.
	 *
	 * @param User $user
	 *        	User object.
	 * @return bool
	 */
	public function disableUser(User $user): bool {
		if ($user->userID > 0) {
			$user = $this->getUser ( $user );
			$user->status = self::SUSPENDED;
			try {
				$user->update ();
				return true;
			} catch ( ModelException $e ) {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Allows an administrator to completely delete an account and all its
	 * associated entries.
	 *
	 * @param User $user
	 *        	User Object.
	 * @return bool
	 */
	public function deleteUser(User $user): bool {
		try {
			$user->exists ();
			
			// Delete any comments and notes for any items held by the user and then delete the item.
			$userItems = new UserItems ( $this->db );
			$userItems->userID = $user->userID;
			$items = $userItems->getUserItems ();
			
			foreach ( $items as $item ) {
				if ($item->itemID > 0) {
					$i = new Item ( $this->db );
					$i->itemID = $item->itemID;
					$this->deleteItem ( $i );
				}
			}
			
			// Delete any other comments made by the user
			$comments = new UserComments ( $this->db );
			$comments->userID = $user->userID;
			$userComments = $comments->getUserComments ();
			
			foreach ( $userComments as $userComment ) {
				$userComment->delete ();
			}
			
			// Finally, delete the user.
			return $user->delete ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Allows and administrator to add a Category and
	 * specify its position in the heirachy.
	 *
	 * @param Category $category
	 *        	Category Object.
	 * @return bool
	 */
	public function addCategory(Category $category): bool {
		try {
			$category->categoryID = $category->set ();
			try {
				$category->get ();
				return true;
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getMessage ();
				return false;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Allows and administrator to update a Category and
	 * its position in the heirachy.
	 *
	 * @param Category $category
	 *        	Category object.
	 * @return bool
	 */
	public function updateCategory(Category $category): bool {
		if ($category->update ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows and administrator to delete a Category and
	 * all associated database content.
	 *
	 * @param Category $category
	 *        	Category object.
	 * @return bool
	 */
	public function deleteCategory(Category $category): bool {
		if ($category->exists ()) {
			$cID = $category->categoryID;
			$pID = $cID;
			
			$i = 1;
			do {
				$pID = $cID;
				
				do {
					$cs = new Categories ( $this->db );
					$csArray = $cs->getCategoriesIn ( $pID );
					$num = count ( $csArray );
					if ($num > 0) {
						$pID = $csArray [0]->categoryID;
					}
				} while ( $num != 0 );
				
				$categoryItem = new CategoryItems ( $this->db );
				$categoryItem->categoryID = $pID;
				try {
					$itemsArray = $categoryItem->getCategoryItems ();
					foreach ( $itemsArray as $item ) {
						$ci = new CategoryItems ( $this->db );
						$ci->itemID = $item->itemID;
						$ci->deleteItem ();
						$this->deleteItem ( $item );
					}
					$c = new Category ( $this->db );
					$c->categoryID = $pID;
					$c->delete ();
				} catch ( ModelException $e ) {
					$_SESSION ['error'] = self::ERROR_CATEGORY_ID_NOT_EXIST;
				}
				$i ++;
			} while ( $pID != $cID );
			
			return true;
		} else {
			$_SESSION ['error'] = self::ERROR_CATEGORY_NOT_EXIST;
			return false;
		}
	}
	
	/**
	 * Retrieves a Category.
	 *
	 * @param Category $category
	 *        	Category object.
	 * @return Category
	 */
	public function getCategory(Category $category): Category {
		$c = new Category ( $this->db );
		$c = $category;
		try {
			$c = $c->get ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
		}
		return $c;
	}
	
	/**
	 * Retrieves all Categories.
	 *
	 * @return array
	 */
	public function getCategories(): array {
		$c = array ();
		$cat = new Categories ( $this->db );
		try {
			$c = $cat->getCategories ();
			return $c;
		} catch ( Exception $e ) {
			$_SESSION ['error'] = $e->getMessage ();
		}
	}
	
	/**
	 * Retrieves all Categories for the given parent category.
	 *
	 * @param int $parentID
	 *        	The ID of the parent category.
	 * @return array
	 */
	public function getCategoriesIn(int $parentID): array {
		$c = array ();
		$cat = new Categories ( $this->db );
		try {
			$c = $cat->getCategoriesIn ( $parentID );
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
		}
		return $c;
	}
	
	/**
	 * Counts the number of items in a category.
	 *
	 * @param Category $category
	 *        	Category object.
	 * @return int
	 */
	public function countCategoryItems(Category $category): int {
		$numCategoryItems = 0;
		$ci = new CategoryItems ( $this->db );
		$ci->categoryID = $category->categoryID;
		try {
			$numCategoryItems = $ci->count ();
			return $numCategoryItems;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return 0;
		}
	}
	
	/**
	 * Counts the number of comments for an Item.
	 *
	 * @param Item $item        	
	 * @return int
	 */
	public function countItemComments(Item $item): int {
		$ic = new ItemComments ( $this->db );
		$ic->itemID = $item->itemID;
		return $ic->count ();
	}
	
	/**
	 * Counts the number of notes for an item.
	 *
	 * @param Item $item        	
	 * @return int
	 */
	public function countItemNotes(Item $item): int {
		$in = new ItemNotes ( $this->db );
		$in->itemID = $item->itemID;
		return $in->count ();
	}
	
	/**
	 * Retrieves all items linked to a Category.
	 * 
	 * @param Category $category        	
	 * @param int $pageNumber        	
	 * @param int $itemsPerPage        	
	 * @param string $status        	
	 * @return array
	 */
	public function getCategoryItemsByPage(Category $category, int $pageNumber, int $itemsPerPage, string $status): array {
		$pn = $pageNumber;
		$ipp = $itemsPerPage;
		$categoryItemsArray = array ();
		$categoryItems = new CategoryItems ( $this->db );
		$categoryItems->categoryID = $category->categoryID;
		try {
			$categoryItemsArray = $categoryItems->getCategoryItemsByPage ( $pn, $ipp, $status );
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
		}
		return $categoryItemsArray;
	}
	
	/**
	 * Counts the number of items held by a user.
	 *
	 * @param User $user        	
	 * @return int
	 */
	public function countUserItems(User $user): int {
		$ui = new UserItems ( $this->db );
		$ui->userID = $user->userID;
		$numUserItems = $ui->count ();
		
		return $numUserItems;
	}

	/**
	 * Gets the first category associated with the given item.
	 *
	 * @param int $itemID
	 * @return array
	 */
	public function getItemCategory(int $itemID): array {
		try {
			$ic = new CategoryItems( $this->db );
			$category = $ic->getItemCategory($itemID);

			$array = [];
			$array['categoryID'] = $category->categoryID;
			$array['category'] = $category->category;
			$array['parentID'] = $category->parentID;
			return $array;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}


	}

	/**
	 * Retrieves all items owned by a user.
	 *
	 * @param int $userID
	 *        	The user whose items will be returned.
	 *
	 * @return array
	 * 			An array of Item objects.
	 */
	public function getUserOwnedItems(int $userID): array {
		try {
			$user = new User( $this->db );
			$user->userID = $userID;
			return $user->getUserOwnedItems();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}

		return [];
	}

	/**
	 * Retrieves all items linked to a user.
	 *
	 * @param User $user
	 *        	The user whose items will be returned.
	 * @param string $userRole
	 *        	The role that the user plays for the requested items.
	 * @return array An array of UserItems objects.
	 */
	public function getUserItems(User $user, string $userRole = ""): array {
		$ui = array ();
		$u = new UserItems ( $this->db );
		$u->userID = $user->userID;
		try {
			$ui = $u->getUserItems ( $userRole );
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}
		return $ui;
	}
	
	/**
	 * Retrieves an item
	 *
	 * @param Item $item        	
	 * @return Item
	 */
	public function getItem(Item $item): Item {
		$i = new Item ( $this->db );
		$i->itemID = $item->itemID;
		try {
			$i = $i->get ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}
		return $i;
	}

	/**
	 * Adds an item to the items table and then adds the id's to
	 * the UserItems table.
	 *
	 * @param Item $item
	 * @param int $categoryID
	 * @return int
	 */
	public function addItem(Item $item, int $categoryID): int {
		$i = $item;
		try {
			$i->itemID = $i->set ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}

		if ($i->itemID > 0) {
			$ci = new CategoryItems ( $this->db );
			$ci->categoryID = $categoryID;
			$ci->itemID = $i->itemID;
			try {
				$ci->category_itemID = $ci->set ();
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			if ($ci->category_itemID > 0) {
				return $i->itemID;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	public function getItemIDForUserItem(int $userItemID): int {
		$userItem = new UserItems($this->db);
		$userItem->user_itemID = $userItemID;
		$userItem->get();
		return $userItem->itemID;
	}

	/**
	 * Updates an item.
	 *
	 * @param Item $item        	
	 * @return bool
	 */
	public function updateItem(Item $item): bool {
		$i = new Item ( $this->db );
		$i = $item;
		try {
			$i->update ();
			return true;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
			return false;
		}
	}
	
	/**
	 * Deletes all references to the ItemID throughout the database and
	 * then deletes the Item.
	 *
	 * @param Item $item        	
	 * @return bool
	 */
	public function deleteItem(Item $item): bool {
		if ($item->exists ()) {
			try {
				return $item->delete();
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getMessage ();
			}
		} else {
			$_SESSION ['error'] = self::ERROR_ITEM_NOT_EXIST;
		}
		return false;
	}
	
	/**
	 * Retrieves all comments for an item and returns them as an array of
	 * Comments objects.
	 *
	 * @param Item $item        	
	 * @return array
	 */
	public function getItemComments(Item $item): array {
		$ic = new ItemComments ( $this->db );
		$ic->itemID = $item->itemID;
		$comments = array ();
		try {
			$comments = $ic->getItemComments ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
		}
		return $comments;
	}
	
	/**
	 * Retrieves an Item associated with a comment.
	 *
	 * @param Comment $comment        	
	 * @return Item
	 */
	public function getItemComment(Comment $comment): Item {
		$ic = new ItemComments ( $this->db );
		$ic->commentID = $comment->commentID;
		$item = new Item ( $this->db );
		try {
			$item->itemID = $ic->getItemComment ()->itemID;
			$item->get ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
		}
		return $item;
	}
	
	/**
	 * Adds an item and a comment to ItemComments.
	 *
	 * @param Item $item        	
	 * @param Comment $comment        	
	 * @return bool
	 */
	public function addItemComment(Item $item, Comment $comment): bool {
		$itemComment = new ItemComments ( $this->db );
		$itemComment->itemID = $item->itemID;
		try {
			$itemComment->commentID = $comment->set ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
		try {
			if ($itemComment->item_commentID = $itemComment->set () > 0) {
				return true;
			} else {
				return false;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Updates an ItemComment
	 *
	 * @param ItemComments $itemComment        	
	 * @return bool
	 */
	public function updateItemComment(Comment $comment): bool {
		try {
			$comment->update ();
			return true;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Deletes a comment from both ItemComments and Comments
	 *
	 * @param Comment $comment        	
	 * @return bool
	 */
	public function deleteItemComment(Comment $comment): bool {
		$itemComment = new ItemComments ( $this->db );
		$itemComment->commentID = $comment->commentID;
		try {
			$itemComment->deleteItemComment ();
			return true;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Deletes all comments associated with an item and deletes their respective
	 * entries in ItemComments
	 *
	 * @param Item $item        	
	 * @return bool
	 */
	public function deleteItemComments(Item $item): bool {
		$itemComment = new ItemComments ( $this->db );
		$itemComment->itemID = $item->itemID;
		try {
			$itemComment->deleteItemComments ();
			return true;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Retrieves all notes for an item and returns them as an array of
	 * Notes objects.
	 *
	 * @param Item $item        	
	 * @return array
	 */
	public function getItemNotes(Item $item): array {
		$in = new ItemNotes ( $this->db );
		$in->itemID = $item->itemID;
		$notes = array ();
		try {
			$notes = $in->getItemNotes ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
		}
		return $notes;
	}
	
	/**
	 * Retrieves an Item associated with a note.
	 *
	 * @param Note $note        	
	 * @return Item
	 */
	public function getItemNote(Note $note): Item {
		$in = new ItemNotes ( $this->db );
		$in->noteID = $note->noteID;
		$item = new Item ( $this->db );
		try {
			$item->itemID = $in->getItemNote ()->itemID;
			$item->get ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
		}
		return $item;
	}
	
	/**
	 * Adds an item and a note to ItemNotes.
	 *
	 * @param Item $item        	
	 * @param Note $note        	
	 * @return bool
	 */
	public function addItemNote(Item $item, Note $note): bool {
		$itemNote = new ItemNotes ( $this->db );
		$itemNote->itemID = $item->itemID;
		try {
			$itemNote->noteID = $note->set ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
		try {
			if ($itemNote->item_noteID = $itemNote->set () > 0) {
				return true;
			} else {
				return false;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Updates an ItemNote
	 *
	 * @param ItemNotes $itemNote        	
	 * @return bool
	 */
	public function updateItemNote(Note $note): bool {
		try {
			$note->update ();
			return true;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Deletes a note from both ItemNotes and Notes
	 *
	 * @param Note $note        	
	 * @return bool
	 */
	public function deleteItemNote(Note $note): bool {
		$itemNote = new ItemNotes ( $this->db );
		$itemNote->noteID = $note->noteID;
		try {
			$itemNote->deleteItemNote ();
			return true;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Deletes all notes associated with an item and deletes their respective
	 * entries in ItemNotes
	 *
	 * @param Item $item        	
	 * @return bool
	 */
	public function deleteItemNotes(Item $item): bool {
		$itemNote = new ItemNotes ( $this->db );
		$itemNote->itemID = $item->itemID;
		try {
			$itemNote->deleteItemNotes ();
			return true;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Retrieves everything known about a user with respect to an
	 * item and returns them as an array.
	 *
	 * @param Item $item        	
	 * @return array
	 */
	public function getItemOwner(Item $item): array {
		$owner = array ();
		
		if ($item->exists ()) {
			try {
				$userItem = new UserItems ( $this->db );
				$userItem->itemID = $item->itemID;
				$userItem = $userItem->getUserItem ();
				$user = new User ( $this->db );
				$user->userID = $userItem->userID;
				$user = $user->get ();
				$userRatings = new UserRatings ( $this->db );
				$stats = $userRatings->getStats ( $user );
				$owner ['userID'] = $user->userID;
				$owner ['user'] = $user->user;
				$owner ['email'] = $user->email;
				$owner ['relationship'] = $userItem->relationship;
				$owner ['userStatus'] = $userItem->userStatus;
				$owner ['numSellRatings'] = $stats ['numSellRatings'];
				$owner ['avgSellRating'] = $stats ['avgSellRating'];
				$owner ['numBuyRatings'] = $stats ['numBuyRatings'];
				$owner ['avgBuyRating'] = $stats ['avgBuyRating'];
				$owner ['totalNumRatings'] = $stats ['totalNumRatings'];
				$owner ['avgRating'] = $stats ['avgRating'];
				return $owner;
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getMessage ();
				return $owner;
			}
		} else {
			$_SESSION ['error'] = self::ERROR_ITEM_NOT_EXIST;
			return $owner;
		}
	}
	
	/**
	 * The addSellerRating() method adds a seller rating of a buyer for a transaction.
	 * It returns a UserRating object with the information necessary to email the buyer.
	 *
	 * @param UserRatings $sellerRating        	
	 * @return bool
	 */
	public function addSellerRating(UserRatings $sellerRating): UserRatings {
		$ur = new UserRatings ( $this->db );
		$ur->userID = $sellerRating->userID;
		$ur->itemID = $sellerRating->itemID;
		$ur->sellrating = $sellerRating->sellrating;
		try {
			return $ur->addSellerRating ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return $ur = new UserRatings ( $this->db );
		}
	}
	
	/**
	 * The addBuyerRating() method adds a buyer rating of a seller for a transaction.
	 *
	 * @param UserRatings $buyerRating        	
	 * @return bool
	 */
	public function addBuyerRating(UserRatings $buyerRating): bool {
		try {
			return $buyerRating->addBuyerRating ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * Returns a users rating stats.
	 *
	 * @param int $userID        	
	 * @return array
	 */
	public function getUserRatings($user): array {
		$ur = array ();
		$userRatings = new UserRatings ( $this->db );
		try {
			return $userRatings->getStats ( $user );
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return $ur;
		}
	}
	
	/**
	 * Searches Item Titles and returns an array of Items.
	 *
	 * @param string $searchString        	
	 * @return array
	 */
	public function search(string $searchString): array {
		$args = array ();
		$args [self::SEARCH_STRING] = $searchString;
		$items = new Items ( $this->db, $args );
		return $items->search ();
	}
}
?>