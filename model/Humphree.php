<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}
class Humphree {
	private $db;
	private $system;
	
	// Constructor
	function __construct(PDO $pdo) {
		$this->db = $pdo;
		$this->system = new System ( $pdo );
	}
	
	/*
	 * The createAccount() function allows a user to create their
	 * own account and join Humphree granting them access to add
	 * and update items as well as view.
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
	
	public function getUserIdByActivationCode(string $activationCode): int {
		$user = new User ( $this->db );
		$user->activate = $activationCode;
		
		return $this->system->getUserIdByActivationCode( $user );
	}
	
	/*
	 * The activateAccount() fucntion verfies the email address
	 * of the new user and makes the account active.
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
	
	/*
	 * The changePassword() function allows a user or administrator to
	 * change a password for an account.
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
	
	/*
	 * The forgotPassword() function allows a user to generate a new password
	 * which is sent to the users account via email.
	 */
	public function forgotPassword(string $email): array {
		$user = new User ( $this->db );
		$user->email = $email;
		$user = $this->system->forgotPassword ( $user );
		$userArray = array();
		$userArray['userID'] = $user->userID;
		$userArray['email'] = $user->email;
		$userArray['password'] = $user->password;
		return $userArray;
	}
	
	/*
	 * The addUser() function allows an administrator to add a user and
	 * pre-activate the account.
	 */
	public function addUser(string $userName, string $email, string $password): bool {
		$user = new User ( $this->db );
		$user->user = $userName;
		$user->email = $email;
		$user->password = $password;
		if ($this->system->addUser ( $user )) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The updateUser() function allows an administrator to update a user.
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
	
	/*
	 * The getUser() function allows an administrator to retrieve a user.
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
	
	/*
	 * The getUsers() function allows an administrator to retrieve all users.
	 */
	public function getUsers($page, $itemsPerPage): array {
		$usersArray = array ();
		$users = $this->system->getUsers ($page, $itemsPerPage);
		
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
	
	/*
	 * The disableUser() function allows an administrator to disable a users
	 * account.
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
	
	/*
	 * The deleteUser() function allows an administrator to completely delete
	 * an account and all associated database entries.
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
	
	/*
	 * The addCategory() function allows and administrator to add a Category and
	 * specify its position in the heirachy.
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
	
	/*
	 * The updateCategory() function allows and administrator to update a Category and
	 * its position in the heirachy.
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
	
	/*
	 * The deleteCategory() function allows and administrator to delete a Category and
	 * all associated database content.
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
	
	/*
	 * The getCategory() function retrieves a Category.
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
	
	/*
	 * The getCategories() function retrieves all Categories.
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
	
	/*
	 * The getCategoriesIn() method retrieves all Categories in the given parent category.
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
	
	/*
	 * The countCategoryItems() method counts the number of items in a category.
	 */
	public function countCategoryItems(int $categoryID): int {
		$category = new Category ( $this->db );
		$category->categoryID = $categoryID;
		$numCategoryItems = $this->system->countCategoryItems ( $category );
		
		return $numCategoryItems;
	}
	
	/*
	 * The countItemComments() method counts the number of items in a category.
	 */
	public function countItemComments(int $itemID): int {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		$numItemComments = $this->system->countItemComments ( $item );
		
		return $numItemComments;
	}
	
	/*
	 * The countItemNotes() method counts the number of items in a category.
	 */
	public function countItemNotes(int $itemID): int {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		$numItemNotes = $this->system->countItemNotes ( $item );
		
		return $numItemNotes;
	}
	
	/*
	 * The getCategoryItems() function retrieves all items linked to a Category.
	 */
	public function getCategoryItemsByPage(int $categoryID, int $pageNumber , int $itemsPerPage): array {
		$category = new Category ( $this->db );
		$category->categoryID = $categoryID;
		$categoryItems = $this->system->getCategoryItemsByPage( $category, $pageNumber , $itemsPerPage);
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
		
		return $its;
	}
	
	/*
	 * The countUserItems() method counts the number of items in a user.
	 */
	public function countUserItems(int $userID): int {
		$user = new User ( $this->db );
		$user->userID = $userID;
		$numUserItems = $this->system->countUserItems ( $user );
		
		return $numUserItems;
	}
	
	/*
	 * The getUserItems() function retrieves all items linked to a User.
	 */
	public function getUserItems(int $userID): array {
		$user = new User ( $this->db );
		$user->userID = $userID;
		$userItems = $this->system->getUserItems ( $user );
		$its = array ();
		
		foreach ( $userItems as $item ) {
			$it = array ();
			
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
		
		return $its;
	}
	
	/*
	 * The getItem() function retrieves an item.
	 */
	public function getItem($itemID): array {
		$item = new Item ( $this->db );
		$item->itemID = $itemID;
		$item = $this->system->getItem ( $item );
		$it = array ();
		
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
		
		return $it;
	}
	
	/*
	 * The addItem() function adds an item.
	 */
	public function addItem(int $userID, array $item): bool {
		$user = new User ( $this->db );
		$user->userID = $userID;
		$it = new Item ( $this->db );
		$it->title = $item ['title'];
		$it->description = $item ['description'];
		$it->quantity = $item ['quantity'];
		$it->itemcondition = $item ['itemcondition'];
		$it->price = $item ['price'];
		$it->status = $item ['status'];
		if ($this->system->addItem ( $user, $it )) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The updateItem() function updates an item.
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
		if ($this->system->updateItem ( $it )) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The deleteItem() function deletes an item and all associated database content.
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
	
	/*
	 * The getItemComments() function retrieves all comments for an item.
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
	
	/*
	 * The getItemComment() function retrieves a comment.
	 */
	public function getItemComment(int $commentID): array {
		$c = array ();
		
		$comment = new Comment ( $this->db );
		$comment->commentID = $commentID;
		$itemComment = $this->system->getItemComment ( $comment );
		$c ['userID'] = $itemComment->userID;
		$user = new User ( $this->db );
		$user->userID = $itemComment->userID;
		$user = $this->system->getUser ( $user );
		$c ['user'] = $user->user;
		$c ['comment'] = $itemComment->comment;

		return $c;
	}
	
	/*
	 * The addItemComment() function adds an itemComment.
	 */
	public function addItemComment(int $userID, int $itemID, string $comment): bool {
		$user = new User ( $this->db );
		$item = new Item ( $this->db );
		$c = new Comment ( $this->db );
		$user->userID = $userID;
		$item->itemID = $itemID;
		$c->comment = $comment;
		if ($this->system->addItemComment ( $user, $item, $c )) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The updateItemComment() function updates an itemComment.
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
	
	/*
	 * The deleteItemComment() function deletes an itemComment and all associated database content.
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
	
	/*
	 * The getItemNotes() retrieves all notes for an item.
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
	
	/*
	 * The getItemNote() function retrieves a note for an item.
	 */
	public function getItemNote(int $noteID): array {
		$n = array ();
		
		$note = new Note ( $this->db );
		$note->noteID = $noteID;
		$itemNote = $this->system->getItemNote ( $note );
		$n ['noteID'] = $itemNote->noteID;
		$n ['note'] = $itemNote->note;
		
		return $n;
	}
	
	/*
	 * The addItemNote() function adds an itemNote.
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
	
	/*
	 * The updateItemNote() function updates an itemNote.
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
	
	/*
	 * The deleteItemNote() function deletes an itemNote and all associated database content.
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
	
	/*
	 * The addSellerRating() method adds a seller rating of a buyer for a transaction.
	 */
	public function addSellerRating(int $itemID, int $sellRating): bool {
		if (($itemID > 0) && ($sellRating > 0)) {
			$s = new UserRatings ( $this->db );
			$s->itemID = $itemID;
			$s->sellrating = $sellRating;
			if ($this->system->addSellerRating ( $s )) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The addBuyerRating() method adds a buyer rating of a seller for a transaction.
	 */
	public function addBuyerRating(string $transaction, int $buyRating, int $userID): bool {
		if (strlen ( $transaction > 0 ) && ($buyRating > 0) && ($userID > 0)) {
			$br = new UserRatings ( $this->db );
			$br->userID = $userID;
			$br->buyrating = $buyRating;
			$br->transaction = $transaction;
			unset ( $_SESSION ['user_rating'] );
			if ($this->system->addBuyerRating ( $br )) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The search() function searches the database and returns matches.
	 */
	public function search(): bool {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$this->system->search ();

		return true;
	}
}