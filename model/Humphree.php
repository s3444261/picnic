<?php
use phpDocumentor\Reflection\Types\Array_;

/**
 *
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
 * The Humphree class is the application programming interface for
 * the model.
 */
class Humphree {
	private $db;
	private $system;
	
	// Constructor
	function __construct(PDO $pdo) {
		$this->db = $pdo;
		$this->system = new System ( $pdo );
	}
	
	/**
	 * Allows a user to create their own account and join Humphree granting them access to add
	 * and update items as well as view.
	 *
	 * @param string $userName
	 *        	Provides the User Name for the account.
	 * @param string $email
	 *        	Provides an email address for the account.
	 * @param string $password
	 *        	Provides a password for the account.
	 * @return bool
	 */
	public function createAccount(string $userName, string $email, string $password): bool {
		$user = new User ( $this->db );
		$user->user = $userName;
		$user->email = $email;
		$user->password = $password;
		
		if ($this->system->createAccount ( $user )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns a UserID on receipt of an activation code.
	 *
	 * @param string $activationCode
	 *        	A 32bit string passed through a URL.
	 * @return int
	 */
	public function getUserIdByActivationCode(string $activationCode): int {
		$user = new User ( $this->db );
		$user->activate = $activationCode;
		
		return $this->system->getUserIdByActivationCode ( $user );
	}
	
	/**
	 * Returns the UserID for the given email address.
	 *
	 * @param string $emailAddress
	 *        	The email address for which to search.
	 * @return int The UserID if one was found, otherwise zero.
	 */
	public function getUserIdByEmailAddress(string $emailAddress): int {
		$user = new User ( $this->db );
		$user->email = $emailAddress;
		
		return $this->system->getUserIdByEmailAddress ( $user );
	}
	
	/**
	 * Verfies the email address of the new user and makes the account active.
	 *
	 * @param int $userID
	 *        	The userID of the account holder.
	 * @return bool
	 */
	public function activateAccount(int $userID): bool {
		$user = new User ( $this->db );
		$user->userID = $userID;
		if ($this->system->activateAccount ( $user )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows a user or administrator to change a password for an account.
	 *
	 * @param int $userID
	 *        	UserID of the account.
	 * @param string $password
	 *        	Password of the account.
	 * @return bool
	 */
	public function changePassword(int $userID, string $password): bool {
		$user = new User ( $this->db );
		$user->userID = $userID;
		$user->password = $password;
		if ($this->system->changePassword ( $user )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows a user to generate a new password which is sent to the users account via email.
	 *
	 * @param string $email
	 *        	Email address of the account.
	 * @return array
	 */
	public function forgotPassword(string $email): array {
		$user = new User ( $this->db );
		$user->email = $email;
		$user = $this->system->forgotPassword ( $user );
		$userArray = array ();
		$userArray ['userID'] = $user->userID;
		$userArray ['email'] = $user->email;
		$userArray ['password'] = $user->password;
		return $userArray;
	}
	
	/**
	 * Allows an administrator to add a user and pre-activate the account.
	 *
	 * @param string $userName
	 *        	User name of the account.
	 * @param string $email
	 *        	Email address of the account.
	 * @param string $password
	 *        	Password of the account.
	 * @return int The new user's ID if successful. Zero means failure.
	 */
	public function addUser(string $userName, string $email, string $password): int {
		$user = new User ( $this->db );
		$user->user = $userName;
		$user->email = $email;
		$user->password = $password;
		return ($this->system->addUser ( $user ));
	}
	
	/**
	 * Allows an administrator to update a users details.
	 *
	 * @param array $userArray
	 *        	An array of user attributes.
	 * @return bool
	 */
	public function updateUser(array $userArray): bool {
		$user = new User ( $this->db );
		$user->userID = $userArray ['userID'];
		$user->user = $userArray ['user'];
		$user->email = $userArray ['email'];
		$user->status = $userArray ['status'];
		if ($this->system->updateUser ( $user )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows an administrator to retrieve a user.
	 *
	 * @param int $userID
	 *        	ID of a user.
	 * @return array
	 */
	public function getUser(int $userID): array {
		$userArray = array ();
		$user = new User ( $this->db );
		$user->userID = $userID;
		$user = $this->system->getUser ( $user );
		$userArray ['userID'] = $user->userID;
		$userArray ['user'] = $user->user;
		$userArray ['email'] = $user->email;
		$userArray ['status'] = $user->status;
		$userArray ['activate'] = $user->activate;
		return $userArray;
	}
	
	/**
	 * Allows an administrator to retrieve all users and display them paginated.
	 *
	 * @param int $page
	 *        	The number of pages to be displayed
	 * @param int $usersPerPage
	 *        	The number of users to be displayed on each page.
	 * @return array
	 */
	public function getUsers(int $page, int $usersPerPage): array {
		$usersArray = array ();
		$users = $this->system->getUsers ( $page, $usersPerPage );
		
		foreach ( $users as $user ) {
			$userArray = array ();
			$userArray ['userID'] = $user->userID;
			$userArray ['user'] = $user->user;
			$userArray ['email'] = $user->email;
			$userArray ['status'] = $user->status;
			
			$usersArray [] = $userArray;
		}
		return $usersArray;
	}
	
	/**
	 * Allows an administrator to suspend a users account.
	 *
	 * @param int $userID
	 *        	The ID of the user.
	 * @return bool
	 */
	public function disableUser(int $userID): bool {
		$user = new User ( $this->db );
		$user->userID = $userID;
		if ($this->system->disableUser ( $user )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows an administrator to delete a user from the database.
	 *
	 * @param int $userID
	 *        	ID of the user.
	 * @return bool
	 */
	public function deleteUser(int $userID): bool {
		$user = new User ( $this->db );
		$user->userID = $userID;
		if ($this->system->deleteUser ( $user )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows an administrator to add a category and specify it's position in the heirachy.
	 *
	 * @param int $parentID
	 *        	The ID of the parent category.
	 * @param string $category
	 *        	The name of the category.
	 * @return bool
	 */
	public function addCategory(int $parentID, string $category): bool {
		$cat = new Category ( $this->db );
		$cat->parentID = $parentID;
		$cat->category = $category;
		if ($this->system->addCategory ( $cat )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows an administrator to update a categories name and its position in the heirachy.
	 *
	 * @param int $categoryID
	 *        	The ID of a category.
	 * @param int $parentID
	 *        	The ID of the parent category.
	 * @param string $category
	 *        	The name of the category.
	 * @return bool
	 */
	public function updateCategory(int $categoryID, int $parentID, string $category): bool {
		$cat = new Category ( $this->db );
		$cat->categoryID = $categoryID;
		$cat->parentID = $parentID;
		$cat->category = $category;
		if ($this->system->updateCategory ( $cat )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows an administrator to completely delete a category and its associated content.
	 *
	 * @param int $categoryID
	 *        	The ID of the category.
	 * @return bool
	 */
	public function deleteCategory(int $categoryID): bool {
		$category = new Category ( $this->db );
		$category->categoryID = $categoryID;
		if ($this->system->deleteCategory ( $category )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Retrieves a category and its attributes from the database.
	 *
	 * @param int $categoryID
	 *        	The ID of the category.
	 * @return array
	 */
	public function getCategory(int $categoryID): array {
		$cat = array ();
		$category = new Category ( $this->db );
		$category->categoryID = $categoryID;
		$category = $this->system->getCategory ( $category );
		$cat ['categoryID'] = $category->categoryID;
		$cat ['parentID'] = $category->parentID;
		$cat ['category'] = $category->category;
		
		return $cat;
	}
	
	/**
	 * Returns all categories.
	 *
	 * @return array
	 */
	public function getCategories(): array {
		$cats = array ();
		$categories = $this->system->getCategories ();
		
		foreach ( $categories as $category ) {
			$cat = array ();
			$cat ['categoryID'] = $category->categoryID;
			$cat ['parentID'] = $category->parentID;
			$cat ['category'] = $category->category;
			
			$cats [] = $cat;
		}
		
		return $cats;
	}
	
	/**
	 * Returns all categories belonging to a parent category.
	 *
	 * @param int $parentID
	 *        	The ID of the parent category.
	 * @return array
	 */
	public function getCategoriesIn(int $parentID): array {
		$cats = array ();
		$categories = $this->system->getCategoriesIn ( $parentID );
		
		foreach ( $categories as $category ) {
			$cat = array ();
			$cat ['categoryID'] = $category->categoryID;
			$cat ['parentID'] = $category->parentID;
			$cat ['category'] = $category->category;
			
			$cats [] = $cat;
		}
		
		return $cats;
	}
	
	/**
	 * Returns the number of items in a category.
	 *
	 * @param int $categoryID
	 *        	The ID of a category.
	 * @return int
	 */
	public function countCategoryItems(int $categoryID): int {
		$category = new Category ( $this->db );
		$category->categoryID = $categoryID;
		$numCategoryItems = $this->system->countCategoryItems ( $category );
		
		return $numCategoryItems;
	}
	
	/**
	 * Counts the number of Comments for an Item.
	 *
	 * @param int $itemID        	
	 * @return int
	 */
	public function countItemComments(int $itemID): int {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		$numItemComments = $this->system->countItemComments ( $item );
		
		return $numItemComments;
	}
	
	/**
	 * Counts the number of notes for an Item.
	 *
	 * @param int $itemID        	
	 * @return int
	 */
	public function countItemNotes(int $itemID): int {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		$numItemNotes = $this->system->countItemNotes ( $item );
		
		return $numItemNotes;
	}
	
	/**
	 * Retrieves all items for an itemID and returns them based on the status of the item, the number
	 * of items per page and the page of items requested.
	 *
	 * @param int $categoryID        	
	 * @param int $pageNumber        	
	 * @param int $itemsPerPage        	
	 * @param string $status        	
	 * @return array
	 */
	public function getCategoryItemsByPage(int $categoryID, int $pageNumber, int $itemsPerPage, string $status): array {
		$category = new Category ( $this->db );
		$category->categoryID = $categoryID;
		$categoryItems = $this->system->getCategoryItemsByPage ( $category, $pageNumber, $itemsPerPage, $status );
		$its = array ();
		
		foreach ( $categoryItems as $item ) {
			$it = array ();
			
			$it ['itemID'] = $item->itemID;
			$it ['title'] = $item->title;
			$it ['description'] = $item->description;
			$it ['quantity'] = $item->quantity;
			$it ['itemcondition'] = $item->itemcondition;
			$it ['price'] = $item->price;
			$it ['status'] = $item->status;
			$its [] = $it;
		}
		
		return $its;
	}
	
	/**
	 * Counts the number of items in a user.
	 *
	 * @param int $userID        	
	 * @return int
	 */
	public function countUserItems(int $userID): int {
		$user = new User ( $this->db );
		$user->userID = $userID;
		$numUserItems = $this->system->countUserItems ( $user );
		
		return $numUserItems;
	}

	public function getUserOwnedItems(int $userID, string $itemStatus): array {
		$user = new User ( $this->db );
		$user->userID = $userID;
		$items = $this->system->getUserOwnedItems ( $userID );
		$result = array();

		foreach ( $items as $item ) {
			if ($item->status == $itemStatus) {
				$it = array ();
				$it ['itemID'] = $item->itemID;
				$it ['owningUserID'] = $item->owningUserID;
				$it ['title'] = $item->title;
				$it ['description'] = $item->description;
				$it ['quantity'] = $item->quantity;
				$it ['itemcondition'] = $item->itemcondition;
				$it ['price'] = $item->price;
				$it ['status'] = $item->status;
				$result [] = $it;
			}
		}

		return $result;
	}

	/**
	 * Retrieves all items linked to a User.
	 *
	 * @param int $userID
	 *        	The ID of the user whose items will be returned.
	 *        	
	 * @param string $userRole
	 *        	The role that the user plays for the requested items.
	 *        	
	 * @param string $itemStatus
	 *        	The desired item status.
	 *        	
	 * @return array An array containing the results.
	 */
	public function getUserItems(int $userID, string $userRole, string $itemStatus): array {
		$user = new User ( $this->db );
		$user->userID = $userID;
		$userItems = $this->system->getUserItems ( $user, $userRole );
		$its = array ();
		
		foreach ( $userItems as $userItem ) {
			
			$it = array ();
			
			$item = new Item ( $this->db );
			$item->itemID = $userItem->itemID;
			$item = $this->system->getItem ( $item );
			
			if ($item->status == $itemStatus) {
				$it ['itemID'] = $item->itemID;
				$it ['title'] = $item->title;
				$it ['description'] = $item->description;
				$it ['quantity'] = $item->quantity;
				$it ['itemcondition'] = $item->itemcondition;
				$it ['price'] = $item->price;
				$it ['status'] = $item->status;
				
				$comments = $this->system->getItemComments ( $item );
				$cs = array ();
				
				foreach ( $comments as $comment ) {
					$c = array ();
					
					$c ['commentID'] = $comment->commentID;
					$c ['userID'] = $comment->userID;
					$user = new User ( $this->db );
					$user->userID = $comment->userID;
					$user = $this->system->getUser ( $user );
					$c ['user'] = $user->user;
					$c ['comment'] = $comment->comment;
					
					$cs [] = $c;
				}
				
				$it ['comments'] = $cs;
				
				$notes = $this->system->getItemNotes ( $item );
				$ns = array ();
				
				foreach ( $notes as $note ) {
					$n = array ();
					
					$n ['noteID'] = $note->noteID;
					$n ['note'] = $note->note;
					
					$ns [] = $n;
				}
				
				$it ['notes'] = $ns;
				
				$its [] = $it;
			}
		}
		
		return $its;
	}
	
	/**
	 * Retrieves an item.
	 *
	 * @param int $itemID        	
	 * @return array
	 */
	public function getItem(int $itemID): array {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		$item = $this->system->getItem ( $item );
		$it = array ();

		$it ['itemID'] = $item->itemID;
		$it ['owningUserID'] = $item->owningUserID;
		$it ['title'] = $item->title;
		$it ['description'] = $item->description;
		$it ['quantity'] = $item->quantity;
		$it ['itemcondition'] = $item->itemcondition;
		$it ['price'] = $item->price;
		$it ['status'] = $item->status;

		$notes = $this->system->getItemNotes ( $item );
		$ns = array ();
		
		foreach ( $notes as $note ) {
			$n = array ();
			
			$n ['noteID'] = $note->noteID;
			$n ['note'] = $note->note;
			
			$ns [] = $n;
		}
		
		$it ['notes'] = $ns;
		
		return $it;
	}
	
	/**
	 * Adds an item.
	 *
	 * @param int $userID        	
	 * @param array $item        	
	 * @param int $categoryID        	
	 * @return int
	 */
	public function addItem(int $userID, array $item, int $categoryID): int {
		$it = new Item ( $this->db );
		$it->owningUserID = $userID;
		$it->title = $item ['title'];
		$it->description = $item ['description'];
		$it->quantity = $item ['quantity'];
		$it->itemcondition = $item ['itemcondition'];
		$it->price = $item ['price'];
		$it->status = $item ['status'];

		return  $this->system->addItem ($it, $categoryID );
	}
	
	/**
	 * Updates an item.
	 *
	 * @param array $item        	
	 * @return bool
	 */
	public function updateItem(array $item): bool {
		$it = new Item ( $this->db );
		$it->itemID = $item ['itemID'];
		$it->title = $item ['title'];
		$it->description = $item ['description'];
		$it->quantity = $item ['quantity'];
		$it->itemcondition = $item ['itemcondition'];
		$it->price = $item ['price'];
		$it->status = $item ['status'];

		return ($this->system->updateItem ( $it ));
	}
	
	/**
	 * Deletes an item and all associated database content.
	 *
	 * @param int $itemID        	
	 * @return bool
	 */
	public function deleteItem(int $itemID): bool {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		if ($this->system->deleteItem ( $item )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Retrieves all comments for an item.
	 *
	 * @param int $itemID        	
	 * @return array
	 */
	public function getItemComments(int $itemID): array {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		$itemComments = $this->system->getItemComments ( $item );
		$cs = array ();
		
		foreach ( $itemComments as $itemComment ) {
			$c = array ();
			
			$c ['commentID'] = $itemComment->commentID;
			$c ['userID'] = $itemComment->userID;
			$user = new User ( $this->db );
			$user->userID = $itemComment->userID;
			$user = $this->system->getUser ( $user );
			$c ['user'] = $user->user;
			$c ['comment'] = $itemComment->comment;
			
			$cs [] = $c;
		}
		return $cs;
	}
	
	/**
	 * Retrieves an item associated with a comment.
	 *
	 * @param int $commentID        	
	 * @return array
	 */
	public function getItemComment(int $commentID): array {
		$comment = new Comment ( $this->db );
		$comment->commentID = $commentID;
		$item = $this->system->getItemComment ( $comment );
		$it = array ();
		$it ['itemID'] = $item->itemID;
		$it ['title'] = $item->title;
		$it ['description'] = $item->description;
		$it ['quantity'] = $item->quantity;
		$it ['itemcondition'] = $item->itemcondition;
		$it ['price'] = $item->price;
		$it ['status'] = $item->status;
		
		return $it;
	}

	/**
	 * Adds a comment for an item.
	 *
	 * @param int $fromUserID	The user ID who made the comment.
	 * @param int $toUserID		The user ID at which the comment is directed.
	 * @param int $itemID		The item ID to which the comment relates.
	 * @param string $comment	The test of the comment.
	 * @return bool				True if the comment was added successfully, false otherwise.
	 */
	public function addItemComment(int $fromUserID, int $toUserID, int $itemID, string $comment): bool {
		$c = new Comment ( $this->db );
		$c->itemID = $itemID;
		$c->comment = $comment;
		$c->toUserID = $toUserID;
		$c->fromUserID = $fromUserID;
		$c->status = 'unread';
		if ($this->system->addItemComment ( $c )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Updates a comment.
	 *
	 * @param array $c        	
	 * @return bool
	 */
	public function updateItemComment(array $c): bool {
		$comment = new Comment ( $this->db );
		$comment->commentID = $c ['commentID'];
		$comment->userID = $c ['userID'];
		$comment->comment = $c ['comment'];
		if ($this->system->updateItemComment ( $comment )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Deletes a comment.
	 *
	 * @param int $commentID        	
	 * @return bool
	 */
	public function deleteItemComment(int $commentID): bool {
		$comment = new Comment ( $this->db );
		$comment->commentID = $commentID;
		if ($this->system->deleteItemComment ( $comment )) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Returns all comments associated with the given user ID, where the user is the sender.
	 *
	 * @param int $userID	The user ID whose comments will be returned.
	 * @return array       	An array of associated Comment objects.
	 */
	public function getUserCommentsAsSender(int $userID): array {
		return $this->commentsToArrays($this->system->getUserCommentsAsSender($userID));
	}

	/**
	 * Returns all comments associated with the given user ID, where the user is the receiver.
	 *
	 * @param int $userID	The user ID whose comments will be returned.
	 * @return array       	An array of associated Comment objects.
	 */
	public function getUserCommentsAsReceiver(int $userID): array {
		return $this->commentsToArrays($this->system->getUserCommentsAsReceiver($userID));
	}

	/**
	 * Returns all comments associated with the given user ID, either as the sender or as the
	 * receiver.
	 *
	 * @param int $userID	The user ID whose comments will be returned.
	 * @return array       	An array of associated Comment objects.
	 */
	public function getAllUserComments(int $userID): array {
		return $this->commentsToArrays($this->system->getAllUserComments($userID));
	}

	/**
	 * Converts an array of Comment objects into a generic data array.
	 *
	 * @param array $comments	The array to be converted.
	 * @return array 			The converted array.
	 */
	private function commentsToArrays(array $comments) : array {
		$result = [];

		foreach ($comments as $comment) {
			$result[] = $this->commentToArray($comment);
		}

		return $result;
	}

	/**
	 * Converts a Comment object into a generic data array.
	 *
	 * @param Comment $comment 	The Comment object to be converted.
	 * @return array 			The converted array.
	 */
	private function commentToArray(Comment $comment) : array {
		$it = array ();
		$it ['commentID'] = $comment->commentID;
		$it ['item'] = $this->getItem($comment->itemID);
		$it ['toUser'] = $this->getuser($comment->toUserID);
		$it ['fromUser'] = $this->getuser($comment->fromUserID);
		$it ['comment'] = $comment->comment;
		$it ['status'] =  $comment->status;
		$it ['created_at'] = $comment->created_at;
		$it ['updated_at'] = $comment->updated_at;
		return $it;
	}

	/**
	 * Removes the given item form the given category.
	 *
	 * @param int $itemID		The item to be removed.
	 * @param int $categoryID	The category from which it will be removed.
	 */
	public function removeItemFromCategory(int $itemID, int $categoryID): void {
		$this->system->removeItemFromCategory($itemID, $categoryID);
	}

	/**
	 * Afds the given item to the given category.
	 *
	 * @param int $itemID		The item to be added.
	 * @param int $categoryID	The category to which it will be added.
	 */
	public function addItemToCategory(int $itemID, int $categoryID): void {
		$this->system->addItemToCategory($itemID, $categoryID);
	}

	/**
	 * Gets the first category associated with the given item.
	 *
	 * @param int $itemID
	 * @return array
	 */
	public function getItemCategory(int $itemID): array {
		return $this->system->getItemCategory($itemID);
	}

	/**
	 * Retrieves all notes for an item.
	 *
	 * @param int $itemID        	
	 * @return array
	 */
	public function getItemNotes(int $itemID): array {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		$itemNotes = $this->system->getItemNotes ( $item );
		$ns = array ();
		
		foreach ( $itemNotes as $itemNote ) {
			$n = array ();
			
			$n ['noteID'] = $itemNote->noteID;
			$n ['note'] = $itemNote->note;
			
			$ns [] = $n;
		}
		return $ns;
	}
	
	/**
	 * Retrieves an item based on a noteID
	 *
	 * @param int $noteID        	
	 * @return array
	 */
	public function getItemNote(int $noteID): array {
		$note = new Note ( $this->db );
		$note->noteID = $noteID;
		$item = $this->system->getItemNote ( $note );
		$it = array ();
		$it ['itemID'] = $item->itemID;
		$it ['title'] = $item->title;
		$it ['description'] = $item->description;
		$it ['quantity'] = $item->quantity;
		$it ['itemcondition'] = $item->itemcondition;
		$it ['price'] = $item->price;
		$it ['status'] = $item->status;
		
		return $it;
	}
	
	/**
	 * Adds a note to a respective item.
	 *
	 * @param int $itemID        	
	 * @param string $note        	
	 * @return bool
	 */
	public function addItemNote(int $itemID, string $note): bool {
		$item = new Item ( $this->db );
		$n = new Note ( $this->db );
		$item->itemID = $itemID;
		$n->note = $note;
		if ($this->system->addItemNote ( $item, $n )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Updates a note.
	 *
	 * @param int $noteID        	
	 * @param string $note        	
	 * @return bool
	 */
	public function updateItemNote(int $noteID, string $note): bool {
		$n = new Note ( $this->db );
		$n->noteID = $noteID;
		$n->note = $note;
		if ($this->system->updateItemNote ( $n )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Deletes a note from the database.
	 *
	 * @param int $noteID        	
	 * @return bool
	 */
	public function deleteItemNote(int $noteID): bool {
		$n = new Note ( $this->db );
		$n->noteID = $noteID;
		if ($this->system->deleteItemNote ( $n )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns details of the user that has posted the item.
	 *
	 * @param int $itemID        	
	 * @return array
	 */
	public function getItemOwner(int $itemID): array {
		$i = new Item ( $this->db );
		return $this->system->getItemOwner ( $i );
	}

	/**
	 * The addSellerRating() method adds a seller rating of a buyer for a transaction.
	 * The intention is that on receipt of the seller rating, the information returned
	 * will be sufficient for the relevent controller to send an email to the buyer
	 * requesting the buyer rate the seller.
	 *
	 * The email will contain a link with a transaction string that will be passed
	 * through a URL to identify the relevant transaction.
	 *
	 * @param int $userID
	 * @param int $itemID
	 * @param int $sellRating
	 * @return array
	 */
	public function addSellerRating(int $userID, int $itemID, int $sellRating): array {
		$buyerArray = [];
		$s = new UserRatings ( $this->db );
		$s->userID = $userID;
		$s->itemID = $itemID;
		$s->sellrating = $sellRating;
		$ur = $this->system->addSellerRating ( $s );
		$u = new User ( $this->db );
		$u->userID = $ur->userID;
		$i = new Item ( $this->db );
		$i->itemID = $ur->itemID;
		try {
			$u->get ();
			$i->get ();
			$buyerArray ['userID'] = $u->userID;
			$buyerArray ['user'] = $u->user;
			$buyerArray ['email'] = $u->email;
			$buyerArray ['itemID'] = $i->itemID;
			$buyerArray ['title'] = $i->title;
			$buyerArray ['transaction'] = $i->transaction;
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage ();
			return $buyerArray;
		}
	}

	/**
	 * The addBuyerRating() method adds a buyer rating of a seller for a transaction.
	 * The transaction string is received through a URL and is used to identifiy
	 * the relevant transaction.
	 *
	 * @param int $userID
	 * @param string $transaction
	 *            A transaction identifier passed through a URL.
	 * @param int $buyRating
	 * @return bool
	 */
	public function addBuyerRating(int $userID, string $transaction, int $buyRating): bool {
		if (strlen ( $transaction > 0 ) && ($buyRating > 0) && ($userID > 0)) {
			$br = new UserRatings ( $this->db );
			$br->buyrating = $buyRating;
			$br->transaction = $transaction;
			if ($this->system->addBuyerRating ( $br )) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Returns a users rating stats as an array.
	 *
	 * @param int $userID        	
	 * @return array
	 */
	public function getUserRatings(int $userID): array {
		$user = new User ( $this->db );
		$user->userID = $userID;
		return $this->system->getUserRatings ( $user );
	}
	
	/**
	 * Searches Item Titles based on search string and returns an array of Items.
	 *
	 * @param string $searchString        	
	 * @return array
	 */
	public function search(string $searchString): array {
		$items = $this->system->search ( $searchString );
		$its = array ();
		foreach ( $items as $item ) {
			$it = array ();
			$it ['itemID'] = $item->itemID;
			$it ['title'] = $item->title;
			$it ['description'] = $item->description;
			$it ['quantity'] = $item->quantity;
			$it ['itemcondition'] = $item->itemcondition;
			$it ['price'] = $item->price;
			$it ['status'] = $item->status;
			
			$its [] = $it;
		}
		return $its;
	}
	
	/**
	 * Searches Item title on an array of strings
	 * 
	 * @param array $searchArgs
	 * @return array
	 */
	public function searchArray(string $searchString): array {
		$items = $this->system->searchArray ( $searchString );
		$its = array ();
		foreach ( $items as $item ) {
			$it = array ();
			$it ['itemID'] = $item->itemID;
			$it ['title'] = $item->title;
			$it ['description'] = $item->description;
			$it ['quantity'] = $item->quantity;
			$it ['itemcondition'] = $item->itemcondition;
			$it ['price'] = $item->price;
			$it ['status'] = $item->status;
			
			$its [] = $it;
		}
		return $its;
	}
	
	/**
	 * Interim advanced search method.
	 * 
	 * @param string $srchTitle
	 * @param string $srchDescription
	 * @param string $srchPrice
	 * @param string $srchQuantity
	 * @param string $srchCondition
	 * @param string $srchStatus
	 * @return array
	 */
	public function searchAdvanced(string $searchText, string $srchMinPrice, string $srchMaxPrice, string $srchMinQuantity, string $srchCondition, string $srchStatus, int $majorCategoryID, int $minorCategoryID): array {
		$items = $this->system->searchAdvanced($searchText, $srchMinPrice, $srchMaxPrice, $srchMinQuantity, $srchCondition, $srchStatus, $majorCategoryID, $minorCategoryID);
		$its = array ();
		foreach ( $items as $item ) {
			$it = array ();
			$it ['itemID'] = $item->itemID;
			$it ['title'] = $item->title;
			$it ['description'] = $item->description;
			$it ['quantity'] = $item->quantity;
			$it ['itemcondition'] = $item->itemcondition;
			$it ['price'] = $item->price;
			$it ['status'] = $item->status;
			
			$its [] = $it;
		}
		return $its;
	}
}