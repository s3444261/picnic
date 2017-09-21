<?php
$hError = '';
$addUser = false;
$updateUser = false;
$getUser = false;
$getUsers = false;
$disableUser = false;
$h = new Humphree();

// Test createAccount()

// Test activateAccount()

// Test changePassword()

// Test forgotPassword()

// Test addUser()
$_SESSION['user']['user'] = 'peter';
$_SESSION['user']['email'] = 'peter@gmail.com';
$_SESSION['user']['password'] = 'TestTest88';
if($h->addUser()){
	$user = new User();
	$user->userID = 1;
	$user->get();
	if($user->user == 'peter'
			&& $user->email == 'peter@gmail.com'
			&& $user->password == '613b59f86203d9e986e08514d175a7d690f3b8f9'
			&& $user->status == 'active'
			&& is_null($user->activate)){
		$addUser = true;
	} else {
		$hError = $hError . 'addUser did not add all attributes.<br />';
		$hError = $hError . 'addUser Failed.<br />';
	}
	
} else {
	$hError = $hError . 'addUser Failed.<br />';
}

// Test updateUser()
$_SESSION['user']['userID'] = 1;
$_SESSION['user']['user'] = 'john';
$_SESSION['user']['email'] = 'john@gmail.com';
$_SESSION['user']['password'] = 'TestTest99';
$_SESSION['user']['status'] = 'admin';
$_SESSION['user']['activate'] = 'blahblahblah';
if($h->updateUser()){
	$user = new User();
	$user->userID = 1;
	$user->get();
	if($user->user == 'john'
			&& $user->email == 'john@gmail.com'
			&& $user->password == 'bf6600ded782b3df6d880a9e7aa7bf8136e099d4'
			&& $user->status == 'admin'
			&& is_null($user->activate)){
		$updateUser = true;
	} else {
		$hError = $hError . 'updateUser did not update all attributes.<br />';
		$hError = $hError . 'updateUser Failed.<br />';
	}
	
} else {
	$hError = $hError . 'updateUser Failed.<br />';
}

// Test getUser()
unset($_SESSION['user']);
$_SESSION['user']['userID'] = 1;
if($h->getUser()){
	if($_SESSION['user']['user'] == 'john'
			&& $_SESSION['user']['email'] == 'john@gmail.com'
			&& $_SESSION['user']['status'] == 'admin'
			&& is_null($_SESSION['user']['activate'])){
				$getUser = true;
	} else {
		$hError = $hError . 'getUser did not get all attributes.<br />';
		$hError = $hError . 'getUser Failed.<br />';
	}
	
} else {
	$hError = $hError . 'getUser Failed.<br />';
}

// Test getUsers()
unset($_SESSION['user']);
$_SESSION['user']['user'] = 'peter';
$_SESSION['user']['email'] = 'peter@gmail.com';
$_SESSION['user']['password'] = 'TestTest88';
$h->addUser();
unset($_SESSION['user']);
$_SESSION['user']['user'] = 'mary';
$_SESSION['user']['email'] = 'mary@gmail.com';
$_SESSION['user']['password'] = 'TestTest66';
$h->addUser();
unset($_SESSION['user']);
$h->getUsers();
if($_SESSION['users']){ 
	if($_SESSION['users'][1]['user']['userID'] == '1'
		&& $_SESSION['users'][1]['user']['user'] == 'john'
		&& $_SESSION['users'][1]['user']['email'] == 'john@gmail.com'
		&& $_SESSION['users'][1]['user']['status'] == 'admin'
		&& is_null($_SESSION['users'][1]['user']['activate'])){
			$getUser1 = true;
	} else {
		$hError = $hError . 'getUsers did not retrieve 1st user.<br />';
		$getUser1 = false;
	}
	if($_SESSION['users'][2]['user']['userID'] == '2'
		&& $_SESSION['users'][2]['user']['user'] == 'peter'
		&& $_SESSION['users'][2]['user']['email'] == 'peter@gmail.com'
		&& $_SESSION['users'][2]['user']['status'] == 'active'
		&& is_null($_SESSION['users'][2]['user']['activate'])){
			$getUser2 = true;
	} else {
		$hError = $hError . 'getUsers did not retrieve 2nd user.<br />';
		$getUser2 = false;
	}
	if($_SESSION['users'][3]['user']['userID'] == '3'
			&& $_SESSION['users'][3]['user']['user'] == 'mary'
			&& $_SESSION['users'][3]['user']['email'] == 'mary@gmail.com'
			&& $_SESSION['users'][3]['user']['status'] == 'active'
			&& is_null($_SESSION['users'][3]['user']['activate'])){
				$getUser3 = true;
	} else {
		$hError = $hError . 'getUsers did not retrieve 3rd user.<br />';
		$getUser3 = false;
	}
	if($getUser1 && $getUser2 && $getUser3){
		$getUsers = true;
	} else {
		$hError = $hError . 'getUsers Failed.<br />';
	}
} else {
	$hError = $hError . 'getUsers Failed.<br />';
}

// Test disableUsers()
$_SESSION['user']['userID'] = 3;
$h->disableUser();
$h->getUser();
if($_SESSION['user']['status'] == 'suspended'){
	$disableUser = true;
} else {
	$hError = $hError . 'getUsers Failed.<br />';
	$disableUser = false;
}

// Test deleteUser()
/*
$category = new Category();
$category->parentID = 0;
$category->category = 'Category';
$category->set();

$category = new Category();
$category->parentID = 1;
$category->category = 'Category1';
$category->set();

$category = new Category();
$category->parentID = 1;
$category->category = 'Category2';
$category->set();

// userID
for($i = 1; $i < 3; $i++){
	
	// categoryID
	for($j = 2; $j < 4; $j++){
		
		// item
		for($k = 1; $k < 5; $k++){
			
			$item = new Item();
			$item->title = 'title' . $k;
			$item->description = 'description' . $k;
			$item->quantity = 'quantity' . $k;
			$item->itemcondition = 'itemcondition' . $k;
			$item->price = 'price' . $k;
			$item->status = 'status' . $k;
			$item->itemID = $item->set();	
			
			$category = new Category();
			$category->categoryID = $j;
			$category->itemID = $item->itemID;
			
			$useritems = new UserItems();
			$useritems->userID = $i;
			$useritems->itemID = $item->itemID;
			
			// Comment
			for($l = 1; $l < 10; $l ++){
				
				$comment = new Comment();
			}
		}
	}
	
}
*/
// Test addCategory()

// Test updateCategory()

// Test deleteCategory()

// Test getCategory()

// Test getCategories()

// Test getCategoryItems()

// Test getItem()

// Test updateItem()

// Test deleteItem()

// Test getItemComments()

// Test getItemComment()

// Test addtemComment()

// Test updateItemComment()

// Test deleteItemComment()

// Test getItemNotes()

// Test getItemNote()

// Test addtemNote()

// Test updateItemNote()

// Test deleteItemNote()

// Test Search()


if($addUser && $updateUser && $getUser && $getUsers && $disableUser){
	$humphree = true;
}





?>