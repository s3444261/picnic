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
class System {
	const SUSPENDED = 'suspended';
	private $db;

	// Constructor
	function __construct(PDO $pdo) {
		$this->db = $pdo;
	}

	/*
	 * The createAccount() function allows a user to create their
	 * own account and join Humphree granting them access to add
	 * and update items as well as view.
	 */
	public function createAccount($user) {
		// TO DO
	}
	
	/*
	 * The activateAccount() fucntion verfies the email address
	 * of the new user and makes the account active.
	 */
	public function activateAccount($user) {
		// TO DO
	}
	
	/*
	 * The changePassword() function allows a user or administrator to
	 * change a password for an account.
	 */
	public function changePassword($user) {
		// TO DO
	}
	
	/*
	 * The forgotPassword() function allows a user to generate a new password
	 * which is sent to the users account via email.
	 */
	public function forgotPassword($user) {
		// TO DO
	}
	
	/*
	 * The addUser() function allows an administrator to add a user and
	 * pre-activate the account.
	 */
	public function addUser($user): bool {
		unset ( $_SESSION ['error'] );
		
		if (get_class ( $user ) == 'User') {
			
			$validate = new Validation ();
			
			// Validate the username.
			try {
				$validate->userName ( $user->user );
			} catch ( ValidationException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			
			// Validate the email name.
			try {
				$validate->email ( $user->email );
			} catch ( ValidationException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			
			// Validate the password
			try {
				$validate->password ( $user->password );
			} catch ( ValidationException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			
			if (isset ( $_SESSION ['error'] )) {
				return false;
			} else {
				$user->set ();
				try {
					$user->get ();
					if ($user->userID > 0) {
						if ($user->activate ()) {
							return true;
						} else {
							$_SESSION ['error'] = 'Failed to activate account.';
							return false;
						}
					} else {
						$_SESSION ['error'] = 'Failed to add user.';
						return false;
					}
				} catch ( UserException $e ) {
					$_SESSION ['error'] = $e->getError ();
				}
			}
		} else {
			$_SESSION ['error'] = 'Not a User Object.';
		}
	}
	
	/*
	 * The updateUser() function allows an administrator to update a user.
	 */
	public function updateUser($user) {
		unset ( $_SESSION ['error'] );
		
		if (get_class ( $user ) == 'User') {
			
			$validate = new Validation ();
			
			// Validate the username.
			try {
				$validate->userName ( $user->user );
			} catch ( ValidationException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			
			// Validate the email name.
			try {
				$validate->email ( $user->email );
			} catch ( ValidationException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			
			// Validate the password
			try {
				$validate->password ( $user->password );
			} catch ( ValidationException $e ) {
				$_SESSION ['error'] = $e->getError ();
			}
			
			if (isset ( $_SESSION ['error'] )) {
				return false;
			} else {
				if ($user->update ()) {
					return true;
				} else {
					$_SESSION ['error'] = 'User not updated.';
					return false;
				}
			}
		} else {
			$_SESSION ['error'] = 'Not a User Object.';
		}
	}
	
	/*
	 * The getUser() function allows an administrator to retrieve a user.
	 */
	public function getUser($user): User {
		$u = new User ($this->db);
		$u->userID = $user->userID;
		try {
			$u = $u->get ();
		} catch ( UserException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}
		return $u;
	}
	
	/*
	 * The getUsers() function allows an administrator to retrieve all users.
	 */
	public function getUsers(): array {
		$users = new Users ($this->db);
		$usersArray = $users->getUsers ();
		return $usersArray;
	}
	
	/*
	 * The disableUser() function allows an administrator to disable a users
	 * account.
	 */
	public function disableUser($user): bool {
		if ($user->userID > 0) {
			$user->status = self::SUSPENDED;
			if ($user->update ()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The deleteUser() function allows an administrator to completely delete
	 * an account and all associated database entries.
	 */
	public function deleteUser($user) {
		if ($user->userID > 0) {
			
			// Delete any comments and notes for any items held by the user and then delete the item.
			$userItems = new UserItems ($this->db);
			$userItems->userID = $user->userID;
			$items = $userItems->getUserItems ();
			
			foreach ( $items as $item ) {
				if ($item->itemID > 0) {
					$itemComments = new ItemComments ($this->db);
					$itemComments->itemID = $item->itemID;
					$itComments = $itemComments->getComments ();
					
					foreach ( $itComments as $itComment ) {
						
						$comment = new Comment ($this->db);
						$comment->commentID = $itComment->commentID;
						$comment->delete ();
						$itComment->delete ();
					}
					
					$itemNotes = new ItemNotes ($this->db);
					$itemNotes->itemID = $item->itemID;
					$itNotes = $itemNotes->getNotes ();
					
					foreach ( $itNotes as $itNote ) {
						$note = new Note ($this->db);
						$note->noteID = $itNote->noteID;
						$note->delete ();
						$itNote->delete ();
					}
					
					$item->delete ();
				}
			}
			
			// Delete any other comments made by the user
			$comments = new Comments ($this->db);
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
	
	/*
	 * The addCategory() function allows and administrator to add a Category and
	 * specify its position in the heirachy.
	 */
	public function addCategory($category): bool {
		$cat = new Category ($this->db);
		$cat = $category;
		$cat->categoryID = $cat->set ();
		if ($cat->categoryID > 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The updateCategory() function allows and administrator to update a Category and
	 * its position in the heirachy.
	 */
	public function updateCategory($category): bool {
		$cat = new Category ($this->db);
		$cat = $category;
		if ($cat->update ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getCategory() function retrieves a Category.
	 */
	public function getCategory($category): Category {
		$c = new Category ($this->db);
		$c = $category;
		try {
			$c = $c->get ();
		} catch ( CategoryException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}
		return $c;
	}
	
	/*
	 * The getCategories() function retrieves all Categories.
	 */
	public function getCategories(): array {
		$c = array ();
		$cat = new Category ($this->db);
		$c = $cat->getCategories ();
		return $c;
	}
	
	/*
	 * The deleteCategory() function allows and administrator to delete a Category and
	 * all associated database content.
	 */
	public function deleteCategory($category): bool {
		if ($category->categoryID > 0) {
			
			// Delete any comments and notes for any items held by the category and then delete the item.
			$categoryItems = new CategoryItems ($this->db);
			$categoryItems->categoryID = $category->categoryID;
			$items = $categoryItems->getCategoryItems ();
			
			foreach ( $items as $item ) {
				if ($item->itemID > 0) {
					$itemComments = new ItemComments ($this->db);
					$itemComments->itemID = $item->itemID;
					$itComments = $itemComments->getComments ();
					
					foreach ( $itComments as $itComment ) {
						
						$comment = new Comment ($this->db);
						$comment->commentID = $itComment->commentID;
						$comment->delete ();
						$itComment->delete ();
					}
					
					$itemNotes = new ItemNotes ($this->db);
					$itemNotes->itemID = $item->itemID;
					$itNotes = $itemNotes->getNotes ();
					
					foreach ( $itNotes as $itNote ) {
						$note = new Note ($this->db);
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
	
	/*
	 * The countCategoryItems() method counts the number of items in a category.
	 */
	public function countCategoryItems($category): int {
		$numCategoryItems = 0;
		$ci = new CategoryItems ($this->db);
		$ci->categoryID = $category->categoryID;
		$numCategoryItems = $ci->count ();
		
		return $numCategoryItems;
	}
	
	/*
	 * The countItemComments() method counts the number of comments for an item.
	 */
	public function countItemComments($item): int {
		$numItemComments = 0;
		$ci = new ItemComments ($this->db);
		$ci->itemID = $item->itemID;
		$numItemComments = $ci->count ();
		
		return $numItemComments;
	}
	
	/*
	 * The countItemNotes() method counts the number of notes for an item.
	 */
	public function countItemNotes($item): int {
		$numItemNotes = 0;
		$ci = new ItemNotes ($this->db);
		$ci->itemID = $item->itemID;
		$numItemNotes = $ci->count ();
		
		return $numItemNotes;
	}
	
	/*
	 * The getCategoryItems() function retrieves all items linked to a Category.
	 */
	public function getCategoryItems($category): array {
		$ci = array ();
		$c = new CategoryItems ($this->db);
		$c->categoryID = $category->categoryID;
		$ci = $c->getCategoryItems ();
		return $ci;
	}
	
	/*
	 * The countUserItems() method counts the number of items in a user.
	 */
	public function countUserItems($user): int {
		$numUserItems = 0;
		$ui = new UserItems ($this->db);
		$ui->userID = $user->userID;
		$numUserItems = $ui->count ();
		
		return $numUserItems;
	}
	
	/*
	 * The getUserItems() function retrieves all items linked to a User.
	 */
	public function getUserItems($user): array {
		$ui = array ();
		$u = new UserItems ($this->db);
		$u->userID = $user->userID;
		$ui = $u->getUserItems ();
		return $ui;
	}
	
	/*
	 * The getItem() function retrieves an item.
	 */
	public function getItem($item): Item {
		$i = new Item ($this->db);
		$i->itemID = $item->itemID;
		try {
			$i = $i->get ();
		} catch ( ItemException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}
		return $i;
	}
	
	/*
	 * The addItem() function adds an item.
	 */
	public function addItem($user, $item): bool {
		$i = new Item ($this->db);
		$ui = new UserItems ($this->db);
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
	public function updateItem($item): bool {
		$i = new Item ($this->db);
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
	public function deleteItem($item): bool {
		if ($item->itemID > 0) {
			
			$itemComments = new ItemComments ($this->db);
			$itemComments->itemID = $item->itemID;
			$itComments = $itemComments->getComments ();
			
			foreach ( $itComments as $itComment ) {
				$comment = new Comment ($this->db);
				$comment->commentID = $itComment->commentID;
				$comment->delete ();
				$itComment->delete ();
			}
			
			$itemNotes = new ItemNotes ($this->db);
			$itemNotes->itemID = $item->itemID;
			$itNotes = $itemNotes->getNotes ();
			
			foreach ( $itNotes as $itNote ) {
				$note = new Note ($this->db);
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
	public function getItemComments($item): array {
		$ic = new ItemComments ($this->db);
		$ic->itemID = $item->itemID;
		$icids = $ic->getComments ();
		$comments = array ();
		foreach ( $icids as $icid ) {
			$comment = new Comment ($this->db);
			$comment->commentID = $icid->commentID;
			$comment->get ();
			$comments [] = $comment;
		}
		return $comments;
	}
	
	/*
	 * The getItemComment() function retrieves an itemComment.
	 */
	public function getItemComment($comment): Comment {
		$c = new Comment ($this->db);
		$c->commentID = $comment->commentID;
		$c->get ();
		return $c;
	}
	
	/*
	 * The addItemComment() function adds an itemComment.
	 */
	public function addItemComment($user, $item, $comment): bool {
		$c = new Comment ($this->db);
		$c->userID = $user->userID;
		$c->comment = $comment->comment;
		$c->commentID = $c->set ();
		if ($c->commentID > 0) {
			$ic = new ItemComments ($this->db);
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
	public function updateItemComment($comment): bool {
		$c = new Comment ($this->db);
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
	public function deleteItemComment($comment): bool {
		$c = new Comment ($this->db);
		$c->commentID = $comment->commentID;
		$ic = new ItemComments ($this->db);
		$ic->commentID = $comment->commentID;
		if ($ic->deleteComment()) {
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
	public function getItemNotes($item): array {
		$in = new ItemNotes ($this->db);
		$in->itemID = $item->itemID;
		$inids = $in->getNotes ();
		$notes = array ();
		foreach ( $inids as $inid ) {
			$note = new Note ($this->db);
			$note->noteID = $inid->noteID;
			$note->get ();
			$notes [] = $note;
		}
		return $notes;
	}
	
	/*
	 * The getItemNote() retrieves a note.
	 */
	public function getItemNote($note): Note {
		$n = new Note ($this->db);
		$n->noteID = $note->noteID;
		$n->get ();
		return $n;
	}
	
	/*
	 * The addItemNote() function adds an itemNote.
	 */
	public function addItemNote($item, $note): bool {
		$n = new Note ($this->db);
		$n->note = $note->note;
		$n->noteID = $n->set ();
		if ($n->noteID > 0) {
			$ic = new ItemNotes ($this->db);
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
	public function updateItemNote($note): bool {
		$n = new Note ($this->db);
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
	public function deleteItemNote($note) {
		$n = new Note ($this->db);
		$n->noteID = $note->noteID;
		$in = new ItemNotes ($this->db);
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
	public function addSellerRating($sellerRating): bool {
		
		$ur = new UserRatings ($this->db);
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
	public function addBuyerRating($buyerRating): bool {
		$ur = new UserRatings ($this->db);
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