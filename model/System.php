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
				} catch (UserException $e) {
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
				if($user->update ()){
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
	public function getUser($user): User{
		$u = new User();
		$u->userID = $user->userID;
		try {
			$u = $u->get();
		} catch ( UserException $e ) {
			$_SESSION ['error'] = $e->getError ();
		}		
		return $u;
	}
	
	/*
	 * The getUsers() function allows an administrator to retrieve all users.
	 */
	public function getUsers(): array {
		$users = new Users();
		$usersArray = $users->getUsers(); 
		return $usersArray;
	}
	
	/*
	 * The disableUser() function allows an administrator to disable a users
	 * account.
	 */
	public function disableUser($user): bool {
		if($user->userID > 0){
			$user->status = self::SUSPENDED;
			if($user->update()){
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
		if($user->userID > 0){
			
			// Delete any comments and notes for any items held by the user and then delete the item.
			$userItems = new UserItems();
			$userItems->userID = $user->userID;
			$items = $userItems->getUserItems();
			
			foreach ($items as $item){ 
				if($item->itemID > 0){ 
					$itemComments = new ItemComments();
					$itemComments->itemID = $item->itemID;
					$itComments = $itemComments->getComments(); 
					
					foreach ($itComments as $itComment){
						
						$comment = new Comment();
						$comment->commentID = $itComment->commentID;
						$comment->delete();
						$itComment->delete();
						
					}

					$itemNotes = new ItemNotes();
					$itemNotes->itemID = $item->itemID;
					$itNotes = $itemNotes->getNotes();
					
					foreach ($itNotes as $itNote){
						$note = new Note();
						$note->noteID = $itNote->noteID;
						$note->delete();
						$itNote->delete();
					}
					
					$item->delete();
				}
			} 
			
			// Delete any other comments made by the user
			$comments = new Comments();
			$comments->userID = $user->userID;
			$userComments = $comments->getUserComments(); 
			
			foreach ($userComments as $userComment){
				$userComment->delete();
			} 
			
			// Finally, delete the user.
			if($user->delete()){
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
	public function addCategory($category) {
		// TO DO
	}
	
	/*
	 * The updateCategory() function allows and administrator to update a Category and
	 * its position in the heirachy.
	 */
	public function updateCategory($category) {
		// TO DO
	}
	
	/*
	 * The deleteCategory() function allows and administrator to delete a Category and
	 * all associated database content.
	 */
	public function deleteCategory($category) {
		// TO DO
	}
	
	/*
	 * The getCategory() function retrieves a Category.
	 */
	public function getCategory($category) {
		// TO DO
	}
	
	/*
	 * The getCategories() function retrieves all Categories.
	 */
	public function getCategories() {
		// TO DO
	}
	
	/*
	 * The getCategoryItems() function retrieves all items linked to a Category.
	 */
	public function getCategoryItems($category) {
		// TO DO
	}
	
	/*
	 * The getItem() function retrieves an item.
	 */
	public function getItem($item) {
		// TO DO
	}
	
	/*
	 * The addItem() function adds an item.
	 */
	public function addItem($user, $item) {
		// TO DO
	}
	
	/*
	 * The updateItem() function updates an item.
	 */
	public function updateItem($item) {
		// TO DO
	}
	
	/*
	 * The deleteItem() function deletes an item and all associated database content.
	 */
	public function deleteItem($user, $item) {
		// TO DO
	}
	
	/*
	 * The getItemComments() function retrieves all comments for an item.
	 */
	public function getItemComments($item) {
		// TO DO
	}
	
	/*
	 * The getItemComment() function retrieves an itemComment.
	 */
	public function getItemComment($comment) {
		// TO DO
	}
	
	/*
	 * The addItemComment() function adds an itemComment.
	 */
	public function addItemComment($user, $item, $comment) {
		// TO DO
	}
	
	/*
	 * The updateItemComment() function updates an itemComment.
	 */
	public function updateItemComment($comment) {
		// TO DO
	}
	
	/*
	 * The deleteItemComment() function deletes an itemComment and all associated database content.
	 */
	public function deleteItemComment($item, $comment) {
		// TO DO
	}
	
	/*
	 * The getItemNotes() retrieves all notes for an item.
	 */
	public function getItemNotes($item) {
		// TO DO
	}
	
	/*
	 * The getItemNote() retrieves a note.
	 */
	public function getItemNote($note) {
		// TO DO
	}
	
	/*
	 * The addItemNote() function adds an itemNote.
	 */
	public function addItemNote($item, $note) {
		// TO DO
	}
	
	/*
	 * The updateItemNote() function updates an itemNote.
	 */
	public function updateItemNote($note) {
		// TO DO
	}
	
	/*
	 * The deleteItemNote() function deletes an itemNote and all associated database content.
	 */
	public function deleteItemNote($item, $note) {
		// TO DO
	}
	
	/*
	 * The search() function searches the database and returns matches.
	 */
	public function search() {
		// TO DO
	}
}
?>