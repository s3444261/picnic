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
		$this->system = new System($pdo);
	}

	/*
	 * The createAccount() function allows a user to create their
	 * own account and join Humphree granting them access to add
	 * and update items as well as view.
	 */
	public function createAccount(): bool {
		// TO DO
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->user = $_SESSION ['user'] ['user'];
			$user->email = $_SESSION ['user'] ['email'];
			$user->password = $_SESSION ['user'] ['password'];
			unset ( $_SESSION ['user'] );

			if ($this->system->createAccount ( $user )) {
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
	public function activateAccount(): bool {
		// TO DO
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			unset ( $_SESSION ['user'] );
			if ($this->system->activateAccount ( $user )) {
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
	public function changePassword(): bool {
		// TO DO
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			$user->password = $_SESSION ['user'] ['password'];
			unset ( $_SESSION ['user'] );
			if ($this->system->changePassword ( $user )) {
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
	public function forgotPassword(): bool {
		// TO DO
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->email = $_SESSION ['user'] ['email'];
			unset ( $_SESSION ['user'] );
			if ($this->system->forgotPassword ( $user )) {
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
	public function addUser(): bool {
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->user = $_SESSION ['user'] ['user'];
			$user->email = $_SESSION ['user'] ['email'];
			$user->password = $_SESSION ['user'] ['password'];
			unset ( $_SESSION ['user'] );
			if ($this->system->addUser ( $user )) {
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
	public function updateUser(): bool {
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			$user->user = $_SESSION ['user'] ['user'];
			$user->email = $_SESSION ['user'] ['email'];
			$user->password = $_SESSION ['user'] ['password'];
			$user->status = $_SESSION ['user'] ['status'];
			$user->activate = $_SESSION ['user'] ['activate'];
			unset ( $_SESSION ['user'] );
			if ($this->system->updateUser ( $user )) {
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
	public function getUser(): bool {
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			$user = $this->system->getUser ( $user );
			$_SESSION ['user'] ['userID'] = $user->userID;
			$_SESSION ['user'] ['user'] = $user->user;
			$_SESSION ['user'] ['email'] = $user->email;
			$_SESSION ['user'] ['status'] = $user->status;
			$_SESSION ['user'] ['activate'] = $user->activate;
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getUsers() function allows an administrator to retrieve all users.
	 */
	public function getUsers(): bool {
		$users = $this->system->getUsers ();
		$i = 1;
		foreach ( $users as $user ) {
			$_SESSION ['users'] [$i] ['user'] ['userID'] = $user->userID;
			$_SESSION ['users'] [$i] ['user'] ['user'] = $user->user;
			$_SESSION ['users'] [$i] ['user'] ['email'] = $user->email;
			$_SESSION ['users'] [$i] ['user'] ['status'] = $user->status;
			$_SESSION ['users'] [$i] ['user'] ['activate'] = $user->activate;
			$i ++;
		}
		return true;
	}
	
	/*
	 * The disableUser() function allows an administrator to disable a users
	 * account.
	 */
	public function disableUser(): bool {
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			if ($this->system->disableUser ( $user )) {
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
	public function deleteUser(): bool {
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			unset ( $_SESSION ['user'] );
			if ($this->system->deleteUser ( $user )) {
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
	public function addCategory(): bool {
		if (isset ( $_SESSION ['category'] )) {
			$category = new Category ($this->db);
			$category->parentID = $_SESSION ['category'] ['parentID'];
			$category->category = $_SESSION ['category'] ['category'];
			unset ( $_SESSION ['category'] );
			if ($this->system->addCategory ( $category )) {
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
	public function updateCategory(): bool {
		if (isset ( $_SESSION ['category'] )) {
			$category = new Category ($this->db);
			$category->categoryID = $_SESSION ['category'] ['categoryID'];
			$category->parentID = $_SESSION ['category'] ['parentID'];
			$category->category = $_SESSION ['category'] ['category'];
			unset ( $_SESSION ['category'] );
			if ($this->system->updateCategory ( $category )) {
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
	public function deleteCategory(): bool {
		if (isset ( $_SESSION ['category'] )) {
			$category = new Category ($this->db);
			$category->categoryID = $_SESSION ['category'] ['categoryID'];
			unset ( $_SESSION ['category'] );
			if ($this->system->deleteCategory ( $category )) {
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
	public function getCategoryById($id): Category {
		$category = new Category ($this->db);
		$category->categoryID = $id;
		$category = $this->system->getCategory ( $category );
		return $category;
	}

	/*
 * The getCategory() function retrieves a Category.
 */
	public function getCategory(): bool {
		if (isset ( $_SESSION ['category'] )) {
			$category = new Category ($this->db);
			$category->categoryID = $_SESSION ['category'] ['categoryID'];
			$category = $this->system->getCategory ( $category );
			$_SESSION ['category'] ['categoryID'] = $category->categoryID;
			$_SESSION ['category'] ['parentID'] = $category->parentID;
			$_SESSION ['category'] ['category'] = $category->category;
			return true;
		} else {
			return false;
		}
	}

	/*
	 * The getCategories() function retrieves all Categories.
	 */
	public function getCategories(): bool {
		$categories = $this->system->getCategories ();
		$i = 1;
		foreach ( $categories as $category ) {
			$_SESSION ['categories'] [$i] ['category'] ['categoryID'] = $category->categoryID;
			$_SESSION ['categories'] [$i] ['category'] ['parentID'] = $category->parentID;
			$_SESSION ['categories'] [$i] ['category'] ['category'] = $category->category;
			$i ++;
		}
		if (isset ( $_SESSION ['categories'] )) {
			return true;
		} else {
			return false;
		}
	}

	/*
 * The getCategories() function retrieves all Categories in the given parent category.
 */
	public function getCategoriesIn(int $parentCategoryId): array {
		return $this->system->getCategoriesIn ($parentCategoryId);
	}
	
	/*
	 * The countCategoryItems() method counts the number of items in a category.
	 */
	public function countCategoryItems(): int {
		$numCategoryItems = 0;
		
		if (isset ( $_SESSION ['category'] )) {
			$category = new Category ($this->db);
			$category->categoryID = $_SESSION ['category'] ['categoryID'];
			$numCategoryItems = $this->system->countCategoryItems ( $category );
		}
		return $numCategoryItems;
	}
	
	/*
	 * The countItemComments() method counts the number of items in a category.
	 */
	public function countItemComments(): int {
		$numItemComments = 0;
		
		if (isset ( $_SESSION ['item'] )) {
			$item = new Item ($this->db);
			$item->itemID = $_SESSION ['item'] ['itemID'];
			$numItemComments = $this->system->countItemComments ( $item );
		}
		return $numItemComments;
	}
	
	/*
	 * The countItemNotes() method counts the number of items in a category.
	 */
	public function countItemNotes(): int {
		$numItemNotes = 0;
		
		if (isset ( $_SESSION ['item'] )) {
			$item = new Item ($this->db);
			$item->itemID = $_SESSION ['item'] ['itemID'];
			$numItemNotes = $this->system->countItemNotes ( $item );
		}
		return $numItemNotes;
	}
	
	/*
	 * The getCategoryItems() function retrieves all items linked to a Category.
	 */
	public function getCategoryItems(): bool {
		if (isset ( $_SESSION ['category'] )) {
			$category = new Category ($this->db);
			$category->categoryID = $_SESSION ['category'] ['categoryID'];
			$categoryItems = $this->system->getCategoryItems ( $category );
			
			$i = 1;
			foreach ( $categoryItems as $item ) {
				$_SESSION ['categoryItems'] [$i] ['item'] ['itemID'] = $item->itemID;
				$_SESSION ['categoryItems'] [$i] ['item'] ['title'] = $item->title;
				$_SESSION ['categoryItems'] [$i] ['item'] ['description'] = $item->description;
				$_SESSION ['categoryItems'] [$i] ['item'] ['quantity'] = $item->quantity;
				$_SESSION ['categoryItems'] [$i] ['item'] ['itemcondition'] = $item->itemcondition;
				$_SESSION ['categoryItems'] [$i] ['item'] ['price'] = $item->price;
				$_SESSION ['categoryItems'] [$i] ['item'] ['status'] = $item->status;
				
				$comments = $this->system->getItemComments ( $item );
				$j = 1;
				foreach ( $comments as $comment ) {
					$_SESSION ['categoryItems'] [$i] ['item'] [$j] ['comment'] ['commentID'] = $comment->commentID;
					$_SESSION ['categoryItems'] [$i] ['item'] [$j] ['comment'] ['userID'] = $comment->userID;
					$user = new User ($this->db);
					$user->userID = $comment->userID;
					$user = $this->system->getUser ( $user );
					$_SESSION ['categoryItems'] [$i] ['item'] [$j] ['comment'] ['user'] = $user->user;
					$_SESSION ['categoryItems'] [$i] ['item'] [$j] ['comment'] ['comment'] = $comment->comment;
					$j ++;
				}
				
				$notes = $this->system->getItemNotes ( $item );
				$j = 1;
				foreach ( $notes as $note ) {
					$_SESSION ['categoryItems'] [$i] ['item'] [$j] ['note'] ['noteID'] = $note->noteID;
					$_SESSION ['categoryItems'] [$i] ['item'] [$j] ['note'] ['note'] = $note->note;
					$j ++;
				}
				$i ++;
			}
			
			if (isset ( $_SESSION ['categoryItems'] )) {
				return true;
			} else {
				return false;
			}
		}
	}

	/*
	 * The getCategoryItems() function retrieves all items linked to the given category.
	 */
	public function getCategoryItemsFor($categoryId): array {
		return $this->system->getCategoryItemsFor ( $categoryId );
	}

	/*
	 * The countUserItems() method counts the number of items in a user.
	 */
	public function countUserItems(): int {
		$numUserItems = 0;
		
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			$numUserItems = $this->system->countUserItems ( $user );
		}
		return $numUserItems;
	}
	
	/*
	 * The getUserItems() function retrieves all items linked to a User.
	 */
	public function getUserItems(): bool {
		if (isset ( $_SESSION ['user'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			$userItems = $this->system->getUserItems ( $user );
			
			$i = 1;
			foreach ( $userItems as $item ) {
				$_SESSION ['userItems'] [$i] ['item'] ['itemID'] = $item->itemID;
				$_SESSION ['userItems'] [$i] ['item'] ['title'] = $item->title;
				$_SESSION ['userItems'] [$i] ['item'] ['description'] = $item->description;
				$_SESSION ['userItems'] [$i] ['item'] ['quantity'] = $item->quantity;
				$_SESSION ['userItems'] [$i] ['item'] ['itemcondition'] = $item->itemcondition;
				$_SESSION ['userItems'] [$i] ['item'] ['price'] = $item->price;
				$_SESSION ['userItems'] [$i] ['item'] ['status'] = $item->status;
				
				$comments = $this->system->getItemComments ( $item );
				$j = 1;
				foreach ( $comments as $comment ) {
					$_SESSION ['userItems'] [$i] ['item'] [$j] ['comment'] ['commentID'] = $comment->commentID;
					$_SESSION ['userItems'] [$i] ['item'] [$j] ['comment'] ['userID'] = $comment->userID;
					$user = new User ($this->db);
					$user->userID = $comment->userID;
					$user = $this->system->getUser ( $user );
					$_SESSION ['userItems'] [$i] ['item'] [$j] ['comment'] ['user'] = $user->user;
					$_SESSION ['userItems'] [$i] ['item'] [$j] ['comment'] ['comment'] = $comment->comment;
					$j ++;
				}
				
				$notes = $this->system->getItemNotes ( $item );
				$j = 1;
				foreach ( $notes as $note ) {
					$_SESSION ['userItems'] [$i] ['item'] [$j] ['note'] ['noteID'] = $note->noteID;
					$_SESSION ['userItems'] [$i] ['item'] [$j] ['note'] ['note'] = $note->note;
					$j ++;
				}
				$i ++;
			}
			
			if (isset ( $_SESSION ['userItems'] )) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/*
	 * The getItem() function retrieves an item.
	 */
	public function getItem(): bool {
		if (isset ( $_SESSION ['item'] )) {
			$item = new Item ($this->db);
			$item->itemID = $_SESSION ['item'] ['itemID'];
			$item = $this->system->getItem ( $item );
			$_SESSION ['item'] ['itemID'] = $item->itemID;
			$_SESSION ['item'] ['title'] = $item->title;
			$_SESSION ['item'] ['description'] = $item->description;
			$_SESSION ['item'] ['quantity'] = $item->quantity;
			$_SESSION ['item'] ['itemcondition'] = $item->itemcondition;
			$_SESSION ['item'] ['price'] = $item->price;
			$_SESSION ['item'] ['status'] = $item->status;
			$itemComments = $this->system->getItemComments ( $item );
			$i = 1;
			foreach ( $itemComments as $itemComment ) {
				$_SESSION ['item'] [$i] ['comment'] ['commentID'] = $itemComment->commentID;
				$_SESSION ['item'] [$i] ['comment'] ['userID'] = $itemComment->userID;
				$user = new User ($this->db);
				$user->userID = $itemComment->userID;
				$user->get ();
				$_SESSION ['item'] [$i] ['comment'] ['user'] = $user->user;
				$_SESSION ['item'] [$i] ['comment'] ['comment'] = $itemComment->comment;
				$i ++;
			}
			$itemNotes = $this->system->getItemNotes ( $item );
			$j = 1;
			foreach ( $itemNotes as $itemNote ) {
				$_SESSION ['item'] [$j] ['note'] ['noteID'] = $itemNote->noteID;
				$_SESSION ['item'] [$j] ['note'] ['note'] = $itemNote->note;
				$j ++;
			}
			return true;
		} else {
			return false;
		}
	}

	/*
	 * The getItem() function retrieves an item.
	 */
	public function getItemById($id): Item {
		return $this->system->getItemById ( $id );
	}

	/*
	 * The addItem() function adds an item.
	 */
	public function addItem(): bool {
		if (isset ( $_SESSION ['user'] ) && isset ( $_SESSION ['item'] )) {
			$user = new User ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			$item = new Item ($this->db);
			$item->title = $_SESSION ['item'] ['title'];
			$item->description = $_SESSION ['item'] ['description'];
			$item->quantity = $_SESSION ['item'] ['quantity'];
			$item->itemcondition = $_SESSION ['item'] ['itemcondition'];
			$item->price = $_SESSION ['item'] ['price'];
			$item->status = $_SESSION ['item'] ['status'];
			unset ( $_SESSION ['user'] );
			unset ( $_SESSION ['item'] );
			if ($this->system->addItem ( $user, $item )) {
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
	public function updateItem(): bool {
		if (isset ( $_SESSION ['item'] )) {
			$item = new Item ($this->db);
			$item->itemID = $_SESSION ['item'] ['itemID'];
			$item->title = $_SESSION ['item'] ['title'];
			$item->description = $_SESSION ['item'] ['description'];
			$item->quantity = $_SESSION ['item'] ['quantity'];
			$item->itemcondition = $_SESSION ['item'] ['itemcondition'];
			$item->price = $_SESSION ['item'] ['price'];
			$item->status = $_SESSION ['item'] ['status'];
			unset ( $_SESSION ['item'] );
			if ($this->system->updateItem ( $item )) {
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
	public function deleteItem(): bool {
		if (isset ( $_SESSION ['item'] )) {
			$item = new Item ($this->db);
			$item->itemID = $_SESSION ['item'] ['itemID'];
			unset ( $_SESSION ['item'] );
			if ($this->system->deleteItem ( $item )) {
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
	public function getItemComments(): bool {
		if (isset ( $_SESSION ['item'] )) {
			$item = new Item ($this->db);
			$item->itemID = $_SESSION ['item'] ['itemID'];
			$itemComments = $this->system->getItemComments ( $item );
			$i = 1;
			foreach ( $itemComments as $itemComment ) {
				$_SESSION ['item'] [$i] ['comment'] ['commentID'] = $itemComment->commentID;
				$_SESSION ['item'] [$i] ['comment'] ['userID'] = $itemComment->userID;
				$user = new User ($this->db);
				$user->userID = $itemComment->userID;
				$user->get ();
				$_SESSION ['item'] [$i] ['comment'] ['user'] = $user->user;
				$_SESSION ['item'] [$i] ['comment'] ['comment'] = $itemComment->comment;
				$i ++;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getItemComment() function retrieves a comment.
	 */
	public function getItemComment(): bool {
		// TO DO
		if (isset ( $_SESSION ['comment'] )) {
			$comment = new Comment ($this->db);
			$comment->commentID = $_SESSION ['comment'] ['commentID'];
			$itemComment = $this->system->getItemComment ( $comment );
			$_SESSION ['comment'] ['userID'] = $itemComment->userID;
			$user = new User ($this->db);
			$user->userID = $itemComment->userID;
			$user->get ();
			$_SESSION ['comment'] ['user'] = $user->user;
			$_SESSION ['comment'] ['comment'] = $itemComment->comment;
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The addItemComment() function adds an itemComment.
	 */
	public function addItemComment(): bool {
		if (isset ( $_SESSION ['user'] ) && isset ( $_SESSION ['item'] ) && isset ( $_SESSION ['comment'] )) {
			$user = new User ($this->db);
			$item = new Item ($this->db);
			$comment = new Comment ($this->db);
			$user->userID = $_SESSION ['user'] ['userID'];
			$item->itemID = $_SESSION ['item'] ['itemID'];
			$comment->comment = $_SESSION ['comment'] ['comment'];
			if ($this->system->addItemComment ( $user, $item, $comment )) {
				unset ( $_SESSION ['user'] );
				unset ( $_SESSION ['item'] );
				unset ( $_SESSION ['comment'] );
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
	public function updateItemComment(): bool {
		if (isset ( $_SESSION ['comment'] )) {
			$comment = new Comment ($this->db);
			$comment->commentID = $_SESSION ['comment'] ['commentID'];
			$comment->userID = $_SESSION ['comment'] ['userID'];
			$comment->comment = $_SESSION ['comment'] ['comment'];
			if ($this->system->updateItemComment ( $comment )) {
				unset ( $_SESSION ['comment'] );
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
	public function deleteItemComment(): bool {
		if (isset ( $_SESSION ['comment'] )) {
			$comment = new Comment ($this->db);
			$comment->commentID = $_SESSION ['comment'] ['commentID'];
			if ($this->system->deleteItemComment ( $comment )) {
				unset ( $_SESSION ['comment'] );
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
	public function getItemNotes(): bool {
		if (isset ( $_SESSION ['item'] )) {
			$item = new Item ($this->db);
			$item->itemID = $_SESSION ['item'] ['itemID'];
			$itemNotes = $this->system->getItemNotes ( $item );
			$i = 1;
			foreach ( $itemNotes as $itemNote ) {
				$_SESSION ['item'] [$i] ['note'] ['noteID'] = $itemNote->noteID;
				$_SESSION ['item'] [$i] ['note'] ['note'] = $itemNote->note;
				$i ++;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The getItemNote() function retrieves a note for an item.
	 */
	public function getItemNote(): bool {
		if (isset ( $_SESSION ['note'] )) {
			$note = new Note ($this->db);
			$note->noteID = $_SESSION ['note'] ['noteID'];
			$itemNote = $this->system->getItemNote ( $note );
			$_SESSION ['note'] ['note'] = $itemNote->note;
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * The addItemNote() function adds an itemNote.
	 */
	public function addItemNote(): bool {
		if (isset ( $_SESSION ['item'] ) && isset ( $_SESSION ['note'] )) {
			$item = new Item ($this->db);
			$note = new Note ($this->db);
			$item->itemID = $_SESSION ['item'] ['itemID'];
			$note->note = $_SESSION ['note'] ['note'];
			if ($this->system->addItemNote ( $item, $note )) {
				unset ( $_SESSION ['item'] );
				unset ( $_SESSION ['note'] );
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
	public function updateItemNote(): bool {
		if (isset ( $_SESSION ['note'] )) {
			$note = new Note ($this->db);
			$note->noteID = $_SESSION ['note'] ['noteID'];
			$note->note = $_SESSION ['note'] ['note'];
			if ($this->system->updateItemNote ( $note )) {
				unset ( $_SESSION ['note'] );
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
	public function deleteItemNote(): bool {
		if (isset ( $_SESSION ['note'] )) {
			$note = new Note ($this->db);
			$note->noteID = $_SESSION ['note'] ['noteID'];
			if ($this->system->deleteItemNote ( $note )) {
				unset ( $_SESSION ['note'] );
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
	public function addSellerRating(): bool {
		if (isset ( $_SESSION ['user_rating'] )) {
			if (($_SESSION ['user_rating'] ['itemID'] > 0) && ($_SESSION ['user_rating'] ['sellrating'] > 0)) {
				$sellerRating = new UserRatings ($this->db);
				$sellerRating->itemID = $_SESSION ['user_rating'] ['itemID'];
				$sellerRating->sellrating = $_SESSION ['user_rating'] ['sellrating'];
				unset ( $_SESSION ['user_rating'] );
				if ($this->system->addSellerRating ( $sellerRating )) {
					return true;
				} else {
					return false;
				}
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
	public function addBuyerRating(): bool {
		if (isset ( $_SESSION ['user_rating'] )) {
			if (strlen ( $_SESSION ['user_rating'] ['transaction'] > 0 ) && ($_SESSION ['user_rating'] ['userID'] > 0) && ($_SESSION ['user_rating'] ['buyrating'] > 0)) {
				$buyerRating = new UserRatings ($this->db);
				$buyerRating->userID = $_SESSION ['user_rating'] ['userID'];
				$buyerRating->buyrating = $_SESSION ['user_rating'] ['buyrating'];
				$buyerRating->transaction = $_SESSION ['user_rating'] ['transaction'];
				unset ( $_SESSION ['user_rating'] );
				if ($this->system->addBuyerRating ( $buyerRating )) {
					return true;
				} else {
					return false;
				}
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
	}
}
?>