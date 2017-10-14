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
 *
 */
class System {
	
	private $db;
	
	const SUSPENDED = 'suspended';
	
	// Constructor
	function __construct(PDO $pdo) {
		$this->db = $pdo;
	}
	
	/**
	 * Creates an account for a user.
	 * 
	 * @param User $user	User Object.
	 * @return bool
	 */
	public function createAccount(User $user): bool {
		try {
			if($user->set () > 0){
				return true;
			} else {
				return false;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage();
			return false;
		}
	}
	
	/**
	 * Returns a UserID based on a 32bit activation code.
	 * 
	 * @param User $user	User object.
	 * @return int
	 */
	public function getUserIdByActivationCode( User $user ): int{
		try {
			return $user->getUserIdByActivationCode();
		} catch (ModelException $e) {
			$_SESSION ['error'] = $e->getMessage();
			return 0;
		}
	}
	
	/**
	 * Activates a user account.
	 * 
	 * @param User $user	User Object.
	 * @return bool
	 */
	public function activateAccount(User $user): bool {
		if($user->activate()){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Allows a password to be changed for the account.
	 * 
	 * @param User $user	User object.
	 * @return bool
	 */
	public function changePassword(User $user): bool {
		try {
			return $user->updatePassword();
		} catch (ModelException $e) {
			$_SESSION ['error'] = $e->getMessage();
			return false;
		}
	}
	
	/**
	 * Allows a user to have a new random password sent to their email address.
	 * 
	 * @param User $user	User Object.
	 * @return User
	 */
	public function forgotPassword(User $user): User {
		try {
			$user->userID = $user->getUserIdByEmail();
			$user->password = $user->getRandomPassword(); 
			$randomPassword = $user->password;
			try {
				$user->updatePassword(); 
				$user->password = $randomPassword;
				return $user;
			} catch (ModelException $e) {
				$_SESSION ['error'] = $e->getMessage();
				return $user;
			}
		} catch (ModelException $e) {
			$_SESSION ['error'] = $e->getMessage();
			return $user;
		}
	}
	
	/**
	 * Allows an administrator to add a user to the system.
	 * 
	 * @param User $user	User object.
	 * @return bool
	 */
	public function addUser(User $user): bool {
		
		try {
			$user->set ();
			try {
				$user->get ();
				return true;
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getMessage(); 
				return false;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage(); 
			return false;
		}
	}
	
	/**
	 * Allows an administrator to update a user.
	 * 
	 * @param User $user	User Object.
	 * @return bool
	 */
	public function updateUser(User $user): bool {
		try {
			$user->update();
			return true;
		} catch (ModelException $e) {
			$_SESSION ['error'] = $e->getError ();
			return false;
		}
	}
	
	/**
	 * Retrieves a user based on the users ID.
	 * 
	 * @param User $user	User Object.
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
	 * @param int $pageNumber		The page number.
	 * @param int $usersPerPage		The number of users listed on the page.
	 * @return array
	 */
	public function getUsers(int $pageNumber, int $usersPerPage): array {
		$usersArray = array();
		$users = new Users ( $this->db );
		try {
			$usersArray = $users->getUsers ($pageNumber, $usersPerPage);
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
			return $usersArray;
		}
		return $usersArray;
	}
	
	/**
	 * Allows an administrator to suspend an account.
	 * 
	 * @param User $user	User object.
	 * @return bool
	 */
	public function disableUser(User $user): bool {
		if ($user->userID > 0) {
			$user = $this->getUser($user);
			$user->status = self::SUSPENDED;
			try { 
				$user->update();
				return true;
			} catch (ModelException $e) {
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
	 * @param User $user	User Object.
	 * @return bool
	 */
	public function deleteUser(User $user): bool {
		if ($user->userID > 0) {
			
			// Delete any comments and notes for any items held by the user and then delete the item.
			$userItems = new UserItems ( $this->db );
			$userItems->userID = $user->userID;
			$items = $userItems->getUserItems ();
			
			foreach ( $items as $item ) {
				if ($item->itemID > 0) {
					$itemComments = new ItemComments ( $this->db );
					$itemComments->itemID = $item->itemID;
					$itComments = $itemComments->getComments ();
					
					foreach ( $itComments as $itComment ) {
						
						$comment = new Comment ( $this->db );
						$comment->commentID = $itComment->commentID;
						$comment->delete ();
						$itComment->delete ();
					}
					
					$itemNotes = new ItemNotes ( $this->db );
					$itemNotes->itemID = $item->itemID;
					$itNotes = $itemNotes->getNotes ();
					
					foreach ( $itNotes as $itNote ) {
						$note = new Note ( $this->db );
						$note->noteID = $itNote->noteID;
						$note->delete ();
						$itNote->delete ();
					}
					
					$item->delete ();
				}
			}
			
			// Delete any other comments made by the user
			$comments = new Comments ( $this->db );
			$comments->userID = $user->userID;
			$userComments = $comments->getUserComments ();
			
			foreach ( $userComments as $userComment ) {
				$userComment->delete ();
			}
			
			// Finally, delete the user.
			if ($user->delete ()) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * Allows and administrator to add a Category and
	 * specify its position in the heirachy.
	 * 
	 * @param Category $category	Category Object.
	 * @return bool
	 */
	public function addCategory(Category $category): bool {
		try {
			$category->categoryID = $category->set ();
			try {
				$category->get ();
				return true;
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getMessage();
				return false;
			}
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage();
			return false;
		}
	}
	
	/**
	 * Allows and administrator to update a Category and
	 * its position in the heirachy.
	 * 
	 * @param Category $category	Category object.
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
	 * @param Category $category	Category object.
	 * @return bool
	 */
	public function deleteCategory(Category $category): bool {
		if ($category->categoryID > 0) {
			
			// Delete any comments and notes for any items held by the category and then delete the item.
			$categoryItems = new CategoryItems ( $this->db );
			$categoryItems->categoryID = $category->categoryID;
			$items = $categoryItems->getCategoryItems ();
			
			foreach ( $items as $item ) {
				if ($item->itemID > 0) {
					$itemComments = new ItemComments ( $this->db );
					$itemComments->itemID = $item->itemID;
					$itComments = $itemComments->getComments ();
					
					foreach ( $itComments as $itComment ) {
						
						$comment = new Comment ( $this->db );
						$comment->commentID = $itComment->commentID;
						$comment->delete ();
						$itComment->delete ();
					}
					
					$itemNotes = new ItemNotes ( $this->db );
					$itemNotes->itemID = $item->itemID;
					$itNotes = $itemNotes->getNotes ();
					
					foreach ( $itNotes as $itNote ) {
						$note = new Note ( $this->db );
						$note->noteID = $itNote->noteID;
						$note->delete ();
						$itNote->delete ();
					}
					
					$item->delete ();
				}
			}
			
			// Finally, delete the category.
			if ($category->delete ()) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * Retrieves a Category.
	 * 
	 * @param Category $category	Category object.
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
		$cat = new Categories( $this->db );
		try {
			$c = $cat->getCategories ();
			return $c;
		} catch (Exception $e) {
			$_SESSION ['error'] = $e->getMessage ();
		}
	}
	
	/**
	 * Retrieves all Categories for the given parent category.
	 * 
	 * @param int $parentID		The ID of the parent category.
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
	 * @param Category $category	Category object.
	 * @return int
	 */
	public function countCategoryItems(Category $category): int {
		$numCategoryItems = 0;
		$ci = new CategoryItems ( $this->db );
		$ci->categoryID = $category->categoryID;
		try {
			$numCategoryItems = $ci->count ();
			return $numCategoryItems;
		} catch (ModelException $e) {
			$_SESSION ['error'] = $e->getMessage ();
			return 0;
		}
	}
	
	/*
	 * The countItemComments() method counts the number of comments for an item.
	 */
	public function countItemComments(Item $item): int {
		$numItemComments = 0;
		$ci = new ItemComments ( $this->db );
		$ci->itemID = $item->itemID;
		$numItemComments = $ci->count ();
		
		return $numItemComments;
	}
	
	/*
	 * The countItemNotes() method counts the number of notes for an item.
	 */
	public function countItemNotes(Item $item): int {
		$numItemNotes = 0;
		$ci = new ItemNotes ( $this->db );
		$ci->itemID = $item->itemID;
		$numItemNotes = $ci->count ();
		
		return $numItemNotes;
	}
	
	/**
	 * Retrieves all items linked to a Category.
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
		$categoryItems = new CategoryItems ( $this->db);
		$categoryItems->categoryID = $category->categoryID;
		try {
			$categoryItemsArray= $categoryItems->getCategoryItemsByPage($pn, $ipp, $status);
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getMessage (); 
		}
		return $categoryItemsArray;
	}
	
	/*
	 * The countUserItems() method counts the number of items in a user.
	 */
	public function countUserItems(User $user): int {
		$numUserItems = 0;
		$ui = new UserItems ( $this->db );
		$ui->userID = $user->userID;
		$numUserItems = $ui->count ();
		
		return $numUserItems;
	}
	
	/*
	 * The getUserItems() function retrieves all items linked to a User.
	 */
	public function getUserItems(User $user): array {
		$ui = array ();
		$u = new UserItems ( $this->db );
		$u->userID = $user->userID;
		$ui = $u->getUserItems ();
		return $ui;
	}
	
	/*
	 * The getItem() function retrieves an item.
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
	
	/*
	 * The addItem() function adds an item.
	 */
	public function addItem(User $user, Item $item): bool {
		$i = new Item ( $this->db );
		$ui = new UserItems ( $this->db );
		$i = $item;
		$i->itemID = $i->set ();
		if ($i->itemID > 1) {
			$ui->userID = $user->userID;
			$ui->itemID = $i->itemID;
			$ui->user_itemID = $ui->set ();
			if ($ui->user_itemID > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The updateItem() function updates an item.
	 */
	public function updateItem(Item $item): bool {
		$i = new Item ( $this->db );
		$i = $item;
		if ($i->update ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The deleteItem() function deletes an item and all associated database content.
	 */
	public function deleteItem(Item $item): bool {
		if ($item->itemID > 0) {
			
			$itemComments = new ItemComments ( $this->db );
			$itemComments->itemID = $item->itemID;
			$itComments = $itemComments->getComments ();
			
			foreach ( $itComments as $itComment ) {
				$comment = new Comment ( $this->db );
				$comment->commentID = $itComment->commentID;
				$comment->delete ();
				$itComment->delete ();
			}
			
			$itemNotes = new ItemNotes ( $this->db );
			$itemNotes->itemID = $item->itemID;
			$itNotes = $itemNotes->getNotes ();
			
			foreach ( $itNotes as $itNote ) {
				$note = new Note ( $this->db );
				$note->noteID = $itNote->noteID;
				$note->delete ();
				$itNote->delete ();
			}
			
			// Finally, delete the item.
			if ($item->delete ()) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/*
	 * The getItemComments() function retrieves all comments for an item.
	 */
	public function getItemComments(Item $item): array {
		$ic = new ItemComments ( $this->db );
		$ic->itemID = $item->itemID;
		$icids = $ic->getComments ();
		$comments = array ();
		foreach ( $icids as $icid ) {
			$comment = new Comment ( $this->db );
			$comment->commentID = $icid->commentID;
			$comment->get ();
			$comments [] = $comment;
		}
		return $comments;
	}
	
	/*
	 * The getItemComment() function retrieves an itemComment.
	 */
	public function getItemComment(Comment $comment): Comment {
		$c = new Comment ( $this->db );
		$c->commentID = $comment->commentID;
		try {
			$c = $c->get ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}
		return $c;
	}
	
	/*
	 * The addItemComment() function adds an itemComment.
	 */
	public function addItemComment(User $user, Item $item, Comment $comment): bool {
		$c = new Comment ( $this->db );
		$c->userID = $user->userID;
		$c->comment = $comment->comment;
		$c->commentID = $c->set ();
		if ($c->commentID > 0) {
			$ic = new ItemComments ( $this->db );
			$ic->itemID = $item->itemID;
			$ic->commentID = $c->commentID;
			$ic->item_commentID = $ic->set ();
			if ($ic->item_commentID > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The updateItemComment() function updates an itemComment.
	 */
	public function updateItemComment(Comment $comment): bool {
		$c = new Comment ( $this->db );
		$c->commentID = $comment->commentID;
		$c->userID = $comment->userID;
		$c->comment = $comment->comment;
		if ($c->update ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The deleteItemComment() function deletes an itemComment and all associated database content.
	 */
	public function deleteItemComment(Comment $comment): bool {
		$c = new Comment ( $this->db );
		$c->commentID = $comment->commentID;
		$ic = new ItemComments ( $this->db );
		$ic->commentID = $comment->commentID;
		if ($ic->deleteComment ()) {
			if ($c->delete ()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The getItemNotes() retrieves all notes for an item.
	 */
	public function getItemNotes(Item $item): array {
		$in = new ItemNotes ( $this->db );
		$in->itemID = $item->itemID;
		$inids = $in->getNotes ();
		$notes = array ();
		foreach ( $inids as $inid ) {
			$note = new Note ( $this->db );
			$note->noteID = $inid->noteID;
			try {
				$note->get ();
			} catch ( ModelException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			$notes [] = $note;
		}
		return $notes;
	}
	
	/*
	 * The getItemNote() retrieves a note.
	 */
	public function getItemNote(Note $note): Note {
		$n = new Note ( $this->db );
		$n->noteID = $note->noteID;
		try {
			$n->get ();
		} catch ( ModelException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}
		return $n;
	}
	
	/*
	 * The addItemNote() function adds an itemNote.
	 */
	public function addItemNote(Item $item, Note $note): bool {
		$n = new Note ( $this->db );
		$n->note = $note->note;
		$n->noteID = $n->set ();
		if ($n->noteID > 0) {
			$ic = new ItemNotes ( $this->db );
			$ic->itemID = $item->itemID;
			$ic->noteID = $n->noteID;
			$ic->item_noteID = $ic->set ();
			if ($ic->item_noteID > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The updateItemNote() function updates an itemNote.
	 */
	public function updateItemNote(Note $note): bool {
		$n = new Note ( $this->db );
		$n->noteID = $note->noteID;
		$n->note = $note->note;
		if ($n->update ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The deleteItemNote() function deletes an itemNote and all associated database content.
	 */
	public function deleteItemNote(Note $note) {
		$n = new Note ( $this->db );
		$n->noteID = $note->noteID;
		$in = new ItemNotes ( $this->db );
		$in->noteID = $note->noteID;
		if ($in->deleteNote ()) {
			if ($n->delete ()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The addSellerRating() method adds a seller rating of a buyer for a transaction.
	 */
	public function addSellerRating(UserRatings $sellerRating): bool {
		$ur = new UserRatings ( $this->db );
		$ur->itemID = $sellerRating->itemID;
		$ur->sellrating = $sellerRating->sellrating;
		$ur->user_ratingID = $ur->set ();
		if ($ur->user_ratingID > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The addBuyerRating() method adds a buyer rating of a seller for a transaction.
	 */
	public function addBuyerRating(UserRatings $buyerRating): bool {
		$ur = new UserRatings ( $this->db );
		$ur->userID = $buyerRating->userID;
		$ur->buyrating = $buyerRating->buyrating;
		$ur->transaction = $buyerRating->transaction;
		if ($ur->updateTransaction ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The search() function searches the database and returns matches.
	 */
	public function search() {
		// TO DO
	}
}
?>