<?php
use phpDocumentor\Reflection\Types\Array_;

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
	 * 			The email address for which to search.
	 * @return int
	 * 			The UserID if one was found, otherwise zero.
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
	 * @return int
	 *          The new user's ID if successful. Zero means failure.
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
	 * @param int $parentID		The ID of the parent category.
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
	 * @param int $categoryID	The ID of a category.
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
	
	/**
	 * Retrieves all items linked to a User.
	 * 
	 * @param int $userID
	 * @return array
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
	
	/**
	 * Adds an item.
	 * 
	 * @param int $userID
	 * @param array $item
	 * @param int $categoryID
	 * @return bool
	 */
	public function addItem(int $userID, array $item, int $categoryID): bool {
		$user = new User ( $this->db );
		$user->userID = $userID;
		$category = new Category($this->db);
		$category->categoryID = $categoryID;
		$it = new Item ( $this->db );
		$it->title = $item ['title'];
		$it->description = $item ['description'];
		$it->quantity = $item ['quantity'];
		$it->itemcondition = $item ['itemcondition'];
		$it->price = $item ['price'];
		$it->status = $item ['status'];
		if ($this->system->addItem ( $user, $it, $category ) > 0) {
			return true;
		} else {
			return false;
		}
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
		if ($this->system->updateItem ( $it )) {
			return true;
		} else {
			return false;
		}
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
		$comment = new Comment($pdo);
		$comment->commentID = $commentID;
		$item = $this->system->getItemComment ( $comment );
		$it = array();
		$it['itemID'] = $item->itemID;
		$it['title'] = $item->title;
		$it['description'] = $item->description;
		$it['quantity'] = $item->quantity;
		$it['itemcondition'] = $item->itemcondition;
		$it['price'] = $item->price;
		$it['status'] = $item->status;
		
		return $it;
	}
	
	/**
	 * Adds a comment for an item.
	 * 
	 * @param int $userID
	 * @param int $itemID
	 * @param string $comment
	 * @return bool
	 */
	public function addItemComment(int $userID, int $itemID, string $comment): bool {
		$item = new Item ( $this->db );
		$c = new Comment ( $this->db );
		$item->itemID = $itemID;
		$c->comment = $comment;
		$c->userID = $userID;
		if ($this->system->addItemNote ( $item, $c )) {
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
		$note = new Note($pdo);
		$note->noteID = $noteID;
		$item = $this->system->getItemNote ( $note );
		$it = array();
		$it['itemID'] = $item->itemID;
		$it['title'] = $item->title;
		$it['description'] = $item->description;
		$it['quantity'] = $item->quantity;
		$it['itemcondition'] = $item->itemcondition;
		$it['price'] = $item->price;
		$it['status'] = $item->status;
		
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
	 * The addSellerRating() method adds a seller rating of a buyer for a transaction.
	 * The intention is that on receipt of the seller rating, the information returned
	 * will be sufficient for the relevent controller to send an email to the buyer 
	 * requesting the buyer rate the seller.
	 * 
	 * The email will contain a link with a transaction string that will be passed
	 * through a URL to identify the relevant transaction.
	 * 
	 * @param int $itemID
	 * @param int $sellRating
	 * @return array
	 */
	public function addSellerRating(int $userID, int $itemID, int $sellRating): array {
		$buyerArray = new Array_();
		$s = new UserRatings ( $this->db );
		$s->userID = $userID;
		$s->itemID = $itemID;
		$s->sellrating = $sellRating;
		$ur = $this->system->addSellerRating($s);
		$u = new User($this->db);
		$u->userID = $ur->userID;
		$i = new Item($this->db);
		$i->itemID = $ur->itemID;
		try {
			$u->get();
			$i->get();
			$buyerArray['userID'] = $u->userID;
			$buyerArray['user'] = $u->user;
			$buyerArray['email'] = $u->email;
			$buyerArray['itemID'] = $i->itemID;
			$buyerArray['title'] = $i->title;
			$buyerArray['transaction'] = $i->transaction;
		} catch (ModelException $e) {
			$_SESSION['error'] = $e->getMessage();
			return $buyerArray;
		}
	}
		
	
	/**
	 * The addBuyerRating() method adds a buyer rating of a seller for a transaction.
	 * The transaction string is received through a URL and is used to identifiy
	 * the relevant transaction.
	 * 
	 * @param string $transaction	A transaction identifier passed through a URL.
	 * @param int $buyRating
	 * @return bool
	 */
	public function addBuyerRating(string $transaction, int $buyRating): bool {
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
		return $this->system->getUserRatings($userID);
	}
	
	/**
	 * The search() function searches the database and returns matches.
	 */
	public function search(): bool {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$this->system->search ();
		
		return true;
	}
}