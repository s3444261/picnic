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
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->createAccount();
	}
	
	/*
	 * The activateAccount() fucntion verfies the email address
	 * of the new user and makes the account active.
	 */
	public function activateAccount() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->activateAccount();
	}
	
	/*
	 * The changePassword() function allows a user or administrator to 
	 * change a password for an account.
	 */
	public function changePassword() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->changePassword();
	}
	
	/*
	 * The forgotPassword() function allows a user to generate a new password
	 * which is sent to the users account via email.
	 */
	public function forgotPassword() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->forgotPassword();
	}
	
	/*
	 * The addUser() function allows an administrator to add a user and
	 * pre-activate the account.
	 */
	public function addUser() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->addUser();
	}
	
	/*
	 * The updateUser() function allows an administrator to update a user.
	 */
	public function updateUser() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->updateUser();
	}
	
	/*
	 * The getUser() function allows an administrator to retrieve a user.
	 */
	public function getUser() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->getUser();
	}
	
	/*
	 * The getUsers() function allows an administrator to retrieve all users.
	 */
	public function getUsers() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->getUsers();
	}
	
	/*
	 * The disableUser() function allows an administrator to disable a users
	 * account.
	 */
	public function disableUser() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->disableUser();
	}
	
	/*
	 * The deleteUser() function allows an administrator to completely delete
	 * an account and all associated database entries.
	 */
	public function deleteUser() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->deleteUser();
	}
	
	/*
	 * The addCategory() function allows and administrator to add a Category and
	 * specify its position in the heirachy.
	 */
	public function addCategory() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->addCategory();
	}
	
	/*
	 * The updateCategory() function allows and administrator to update a Category and
	 * its position in the heirachy.
	 */
	public function updateCategory() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->updateCategory();
	}
	
	/*
	 * The deleteCategory() function allows and administrator to delete a Category and
	 * all associated database content.
	 */
	public function deleteCategory() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->deleteCategory();
	}
	
	/*
	 * The getCategory() function retrieves a Category.
	 */
	public function getCategory() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->getCategory();
	}
	
	/*
	 * The getCategories() function retrieves all Categories.
	 */
	public function getCategories() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->getCategories();
	}
	
	/*
	 * The getCategoryItems() function retrieves all items linked to a Category.
	 */
	public function getCategoryItems() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->getCategoryItems();
	}
	
	/*
	 * The getItem() function retrieves an item.
	 */
	public function getItem() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->getItem();
	}
	
	/*
	 * The addItem() function adds an item.
	 */
	public function addItem() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->addItem();
	}
	
	/*
	 * The updateItem() function updates an item.
	 */
	public function updateItem() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->updateItem();
	}
	
	/*
	 * The deleteItem() function deletes an item and all associated database content.
	 */
	public function deleteItem() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->deleteItem();
	}
	
	/*
	 * The getItemComment() function retrieves an itemComment.
	 */
	public function getItemComment() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->getItemComment();
	}
	
	/*
	 * The addItemComment() function adds an itemComment.
	 */
	public function addItemComment() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->addItemComment();
	}
	
	/*
	 * The updateItemComment() function updates an itemComment.
	 */
	public function updateItemComment() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->updateItemComment();
	}
	
	/*
	 * The deleteItemComment() function deletes an itemComment and all associated database content.
	 */
	public function deleteItemComment() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->deleteItemComment();
	}
	
	/*
	 * The getItemNote() function retrieves an itemNote.
	 */
	public function getItemNote() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->getItemNote();
	}
	
	/*
	 * The addItemNote() function adds an itemNote.
	 */
	public function addItemNote() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->addItemNote();
	}
	
	/*
	 * The updateItemNote() function updates an itemNote.
	 */
	public function updateItemNote() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->updateItemNote();
	}
	
	/*
	 * The deleteItemNote() function deletes an itemNote and all associated database content.
	 */
	public function deleteItemNote() {
		// TO DO
		// Convert arrays to objects if necessary and call equivalent System Function
		$system = new System();
		$system->deleteItemNote();
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