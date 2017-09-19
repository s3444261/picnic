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
	
	/*
	 * The createAccount() function allows a user to create their
	 * own account and join Humphree granting them access to add
	 * and update items as well as view.
	 */
	public function createAccount() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->user = $_SESSION['user']['user'];
			$user->email = $_SESSION['user']['email'];
			$user->password = $_SESSION['user']['password'];
			unset($_SESSION['user']);
			$system = new System();
			if($system->createAccount($user)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The activateAccount() fucntion verfies the email address
	 * of the new user and makes the account active.
	 */
	public function activateAccount() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->userID = $_SESSION['user']['userID'];
			unset($_SESSION['user']);
			$system = new System();
			if($system->activateAccount($user)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The changePassword() function allows a user or administrator to 
	 * change a password for an account.
	 */
	public function changePassword() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->userID = $_SESSION['user']['userID'];
			$user->password = $_SESSION['user']['password'];
			unset($_SESSION['user']);
			$system = new System();
			if($system->changePassword($user)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The forgotPassword() function allows a user to generate a new password
	 * which is sent to the users account via email.
	 */
	public function forgotPassword() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->email = $_SESSION['user']['email'];
			unset($_SESSION['user']);
			$system = new System();
			if($system->forgotPassword($user)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The addUser() function allows an administrator to add a user and
	 * pre-activate the account.
	 */
	public function addUser() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->user = $_SESSION['user']['user'];
			$user->email = $_SESSION['user']['email'];
			$user->password = $_SESSION['user']['password'];
			unset($_SESSION['user']);
			$system = new System();
			if($system->addUser($user)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The updateUser() function allows an administrator to update a user.
	 */
	public function updateUser() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->userID = $_SESSION['user']['userID'];
			$user->user = $_SESSION['user']['user'];
			$user->email = $_SESSION['user']['email'];
			$user->password = $_SESSION['user']['password'];
			$user->status = $_SESSION['user']['status'];
			$user->activate = $_SESSION['user']['activate'];
			unset($_SESSION['user']);
			$system = new System();
			if($system->updateUser($user)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The getUser() function allows an administrator to retrieve a user.
	 */
	public function getUser() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->userID = $_SESSION['user']['userID'];
			$system = new System();
			$user = $system->getUser($user);
			$_SESSION['user']['userID'] = $user->userID;
			$_SESSION['user']['user'] = $user->user;
			$_SESSION['user']['email'] = $user->email;
			$_SESSION['user']['status'] = $user->status;
			$_SESSION['user']['activate'] = $user->activate;
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getUsers() function allows an administrator to retrieve all users.
	 */
	public function getUsers() {
		// TO DO
		$system = new System();
		$users = $system->getUsers();
		$i = 1;
		foreach($users as $user){
			$_SESSION['users'][$i]['user']['userID'] = $user->userID;
			$_SESSION['users'][$i]['user']['user'] = $user->user;
			$_SESSION['users'][$i]['user']['email'] = $user->email;
			$_SESSION['users'][$i]['user']['status'] = $user->status;
			$i++;
		}
		return true;
	}
	
	/*
	 * The disableUser() function allows an administrator to disable a users
	 * account.
	 */
	public function disableUser() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->userID = $_SESSION['user']['userID'];
			unset($_SESSION['user']);
			$system = new System();
			if($system->disableUser($user)){
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
	public function deleteUser() {
		// TO DO
		if(isset($_SESSION['user'])){
			$user = new User();
			$user->userID = $_SESSION['user']['userID'];
			unset($_SESSION['user']);
			$system = new System();
			if($system->deleteUser($user)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The addCategory() function allows and administrator to add a Category and
	 * specify its position in the heirachy.
	 */
	public function addCategory() {
		// TO DO
		if(isset($_SESSION['category'])){
			$category = new Category();
			$category->parentID = $_SESSION['category']['parentID'];
			$category->category = $_SESSION['category']['category'];
			unset($_SESSION['category']);
			$system = new System();
			if($system->addCategory($category)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The updateCategory() function allows and administrator to update a Category and
	 * its position in the heirachy.
	 */
	public function updateCategory() {
		// TO DO
		if(isset($_SESSION['category'])){
			$category = new Category();
			$category->categoryID = $_SESSION['category']['categoryID'];
			$category->parentID = $_SESSION['category']['parentID'];
			$category->category = $_SESSION['category']['category'];
			unset($_SESSION['category']);
			$system = new System();
			if($system->updateCategory($category)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The deleteCategory() function allows and administrator to delete a Category and
	 * all associated database content.
	 */
	public function deleteCategory() {
		// TO DO
		if(isset($_SESSION['category'])){
			$category = new Category();
			$category->categoryID = $_SESSION['category']['categoryID'];
			unset($_SESSION['category']);
			$system = new System();
			if($system->deleteCategory($category)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The getCategory() function retrieves a Category.
	 */
	public function getCategory() {
		// TO DO
		if(isset($_SESSION['category'])){
			$category = new Category();
			$category->categoryID = $_SESSION['category']['categoryID'];
			$system = new System();
			$category = $system->getCategory($category);
			$_SESSION['category']['categoryID'] = $category->categoryID;
			$_SESSION['category']['parentID'] = $category->parentID;
			$_SESSION['category']['category'] = $category->category;
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getCategories() function retrieves all Categories.
	 */
	public function getCategories() {
		// TO DO
		$system = new System();
		$categories = $system->getCategories();
		$i = 1;
		foreach($categories as $category){
			$_SESSION['categories'][$i]['category']['categoryID'] = $category->categoryID;
			$_SESSION['categories'][$i]['category']['parentID'] = $category->parentID;
			$_SESSION['categories'][$i]['category']['category'] = $category->category;
			$i++;
		}
		if(isset($_SESSION['categories'])){
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getCategoryItems() function retrieves all items linked to a Category.
	 */
	public function getCategoryItems() {
		// TO DO
		if(isset($_SESSION['category'])){
			$category = new Category();
			$category->categoryID = $_SESSION['category']['categoryID'];
			$system = new System();
			$categoryItems = $system->getCategoryItems($category);
			$i = 1;
			foreach($categoryItems as $item){
				$_SESSION['categoryItems'][$i]['item']['itemID'] = $item->itemID;
				$_SESSION['categoryItems'][$i]['item']['title'] = $item->title;
				$_SESSION['categoryItems'][$i]['item']['description'] = $item->description;
				$_SESSION['categoryItems'][$i]['item']['quantity'] = $item->quantity;
				$_SESSION['categoryItems'][$i]['item']['itemcondition'] = $item->itemcondition;
				$_SESSION['categoryItems'][$i]['item']['price'] = $item->price;
				$_SESSION['categoryItems'][$i]['item']['status'] = $item->status;
				$comments = new ItemComments();
				$comments->itemID = $item->itemID;
				$itemComments = $comments->getComments();
				$j = 1;
				foreach($itemComments as $itemComment){
					$_SESSION['categoryItems'][$i]['item'][$j]['comment']['commentID'] = $itemComment->commentID;
					$_SESSION['categoryItems'][$i]['item'][$j]['comment']['userID'] = $itemComment->userID;
					$user = new User();
					$user->userID = $itemComment->userID;
					$user->get();
					$_SESSION['categoryItems'][$i]['item'][$j]['comment']['user'] = $user->user;
					$_SESSION['categoryItems'][$i]['item'][$j]['comment']['comment'] = $itemComment->comment;
					$j++;
				}
				$notes = new ItemNotes();
				$notes->itemID = $item->itemID;
				$itemNots = $notes->getNotes();
				$j = 1;
				foreach($itemNotes as $itemNote){
					$_SESSION['categoryItems'][$i]['item'][$j]['note']['noteID'] = $itemNote->noteID;
					$_SESSION['categoryItems'][$i]['item'][$j]['note']['note'] = $itemNote->note;
					$j++;
				}
				$i++;
			}
			if(isset($_SESSION['categories'])){
				return true;
			} else {
				return false;
			}
		}
	}
	
	/*
	 * The getItem() function retrieves an item.
	 */
	public function getItem() {
		// TO DO
		if(isset($_SESSION['item'])){
			$item = new Item();
			$item->itemID = $_SESSION['item']['itemID'];
			$system = new System();
			$item = $system->getItem($item);
			$_SESSION['item']['itemID'] = $item->itemID;
			$_SESSION['item']['title'] = $item->title;
			$_SESSION['item']['description'] = $item->description;
			$_SESSION['item']['quantity'] = $item->quantity;
			$_SESSION['item']['itemcondition'] = $item->itemcondition;
			$_SESSION['item']['price'] = $item->price;
			$_SESSION['item']['status'] = $item->status;
			$itemComments = $system->getItemComments($item);
			$i = 1;
			foreach($itemComments as $itemComment){
				$_SESSION['item'][$i]['comment']['commentID'] = $itemComment->commentID;
				$_SESSION['item'][$i]['comment']['userID'] = $itemComment->userID;
				$user = new User();
				$user->userID = $itemComment->userID;
				$user->get();
				$_SESSION['item'][$i]['comment']['user'] = $user->user;
				$_SESSION['item'][$i]['comment']['comment'] = $itemComment->comment;
				$i++;
			}
			$itemNotes = $system->getItemNotes($item);
			$j = 1;
			foreach($itemNotes as $itemNote){
				$_SESSION['item'][$j]['note']['noteID'] = $itemNote->noteID;
				$_SESSION['item'][$j]['note']['note'] = $itemNote->note;
				$j++;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The addItem() function adds an item.
	 */
	public function addItem() {
		// TO DO
		if(isset($_SESSION['user']) && isset($_SESSION['item'])){
			$user = new User();
			$user->userID = $_SESSION['user']['userID'];
			$item = new Item();
			$item->title = $_SESSION['item']['title'];
			$item->description = $_SESSION['item']['description'];
			$item->quantity = $_SESSION['item']['quantity'];
			$item->itemcondition = $_SESSION['item']['itemcondition'];
			$item->price = $_SESSION['item']['price'];
			$item->status = $_SESSION['item']['status'];
			unset($_SESSION['user']);
			unset($_SESSION['item']);
			$system = new System();
			if($system->addItem($user, $item)){
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
	public function updateItem() {
		// TO DO
		if(isset($_SESSION['item'])){
			$item = new Item();
			$item->itemID = $_SESSION['item']['itemID'];
			$item->title = $_SESSION['item']['title'];
			$item->description = $_SESSION['item']['description'];
			$item->quantity = $_SESSION['item']['quantity'];
			$item->itemcondition = $_SESSION['item']['itemcondition'];
			$item->price = $_SESSION['item']['price'];
			$item->status = $_SESSION['item']['status'];
			unset($_SESSION['item']);
			$system = new System();
			if($system->updateItem($item)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The deleteItem() function deletes an item and all associated database content.
	 */
	public function deleteItem() {
		// TO DO
		if(isset($_SESSION['user']) && isset($_SESSION['item'])){
			$user = new User();
			$user->userID = $_SESSION['user']['userID'];
			$item = new Item();
			$item->itemID = $_SESSION['item']['itemID'];
			unset($_SESSION['user']);
			unset($_SESSION['item']);
			$system = new System();
			if($system->deleteItem($user, $item)){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The getItemComments() function retrieves all comments for an item.
	 */
	public function getItemComments() {
		// TO DO
		if(isset($_SESSION['item'])){
			$item = new Item();
			$item->itemID = $_SESSION['item']['itemID'];
			$system = new System();
			$itemComments = $system->getItemComments($item);
			$i = 1;
			foreach($itemComments as $itemComment){
				$_SESSION['item'][$i]['comment']['commentID'] = $itemComment->commentID;
				$_SESSION['item'][$i]['comment']['userID'] = $itemComment->userID;
				$user = new User();
				$user->userID = $itemComment->userID;
				$user->get();
				$_SESSION['item'][$i]['comment']['user'] = $user->user;
				$_SESSION['item'][$i]['comment']['comment'] = $itemComment->comment;
				$i++;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getItemComment() function retrieves a comment.
	 */
	public function getItemComment() {
		// TO DO
		if(isset($_SESSION['comment'])){
			$comment = new Comment();
			$comment->commentID = $_SESSION['comment']['commentID'];
			$system = new System();
			$itemComment = $system->getItemComment($comment);
			$_SESSION['comment']['userID'] = $itemComment->userID;
			$user = new User();
			$user->userID = $itemComment->userID;
			$user->get();
			$_SESSION['comment']['user'] = $user->user;
			$_SESSION['comment']['comment'] = $itemComment->comment;
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The addItemComment() function adds an itemComment.
	 */
	public function addItemComment() {
		// TO DO
		if(isset($_SESSION['user']) && isset($_SESSION['item']) && isset($_SESSION['comment'])){
			$user = new User();
			$item = new Item();
			$comment = new Comment();
			$user->userID = $_SESSION['user']['userID'];
			$item->itemID = $_SESSION['item']['itemID'];
			$comment->comment = $_SESSION['comment']['comment'];
			$system = new System();
			if($system->addItemComment($user, $item, $comment)){
				unset($_SESSION['user']);
				unset($_SESSION['item']);
				unset($_SESSION['comment']);
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
	public function updateItemComment() {
		// TO DO
		if(isset($_SESSION['comment'])){
			$comment = new Comment();
			$comment->commentID = $_SESSION['comment']['commentID'];
			$comment->userID = $_SESSION['comment']['userID'];
			$comment->comment = $_SESSION['comment']['comment'];
			$system = new System();
			if($system->updateItemComment($comment)){
				unset($_SESSION['comment']);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}			
	}
	
	/*
	 * The deleteItemComment() function deletes an itemComment and all associated database content.
	 */
	public function deleteItemComment() {
		// TO DO
		if(isset($_SESSION['item']) && isset($_SESSION['comment'])){
			$item = new Item();
			$item->itemID = $_SESSION['item']['itemID'];
			$comment = new Comment();
			$comment->commentID = $_SESSION['comment']['commentID'];
			$system = new System();
			if($system->deleteItemComment($item, $comment)){
				unset($_SESSION['item']);
				unset($_SESSION['comment']);
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
	public function getItemNotes() {
		// TO DO
		if(isset($_SESSION['item'])){
			$item = new Item();
			$item->itemID = $_SESSION['item']['itemID'];
			$system = new System();
			$itemNotes = $system->getItemNotes($item);
			$i = 1;
			foreach($itemNotes as $itemNote){
				$_SESSION['item'][$i]['note']['noteID'] = $itemNote->noteID;
				$_SESSION['item'][$i]['note']['note'] = $itemNote->note;
				$i++;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getItemNote() function retrieves a note for an item.
	 */
	public function getItemNote() {
		// TO DO
		if(isset($_SESSION['note'])){
			$note = new Note();
			$note->noteID = $_SESSION['note']['noteID'];
			$system = new System();
			$itemNote = $system->getItemNote($note);
			$_SESSION['note']['note'] = $itemNote->note;
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The addItemNote() function adds an itemNote.
	 */
	public function addItemNote() {
		// TO DO
		if(isset($_SESSION['item']) && isset($_SESSION['note'])){
			$item = new Item();
			$note = new Note();
			$item->itemID = $_SESSION['item']['itemID'];
			$note->note = $_SESSION['note']['note'];
			$system = new System();
			if($system->addItemNote($item, $note)){
				unset($_SESSION['item']);
				unset($_SESSION['note']);
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
	public function updateItemNote() {
		// TO DO
		if(isset($_SESSION['note'])){
			$note = new Note();
			$note->noteID = $_SESSION['note']['noteID'];
			$note->note = $_SESSION['note']['note'];
			$system = new System();
			if($system->updateItemNote($note)){
				unset($_SESSION['note']);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * The deleteItemNote() function deletes an itemNote and all associated database content.
	 */
	public function deleteItemNote() {
		// TO DO
		if(isset($_SESSION['item']) && isset($_SESSION['note'])){
			$item = new Item();
			$item->itemID = $_SESSION['item']['itemID'];
			$note = new Note();
			$note->noteID = $_SESSION['note']['noteID'];
			$system = new System();
			if($system->deleteItemNote($item, $note)){
				unset($_SESSION['item']);
				unset($_SESSION['note']);
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
	public function search() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->search();
	}
	

}
?>