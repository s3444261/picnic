<?php
$hError = '';
$addUser = false;
$updateUser = false;
$getUser = false;
$getUsers = false;
$disableUser = false;
$deleteUser = false;
$addCategory = false;
$updateCategory = false;
$getCategory = false;
$getCategories = false;
$deleteCategory = false;
$getCategoryItems= false;
$countCategoryItems = false;
$countItemComments= false;
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
 * user can't be deleted unless all database dependencies have been
 * deleted.  If user is not longer there, deletion was successful.
 */
seed();

$_SESSION['user']['userID'] = 2;
$h->deleteUser();

$user = new User();
$user->userID = 2;
if($user->exists()){
	$hError = $hError . 'deleteUser Failed.<br />';
	$deleteUser = false;
} else {
	$deleteUser = true;
}

DatabaseGenerator::Generate();

// Test addCategory()
$category = new Category();
$category->parentID = 0;
$category->category = 'Category';
$category->set();

$_SESSION['category']['parentID'] = 1;
$_SESSION['category']['category'] = 'Category1';
$h->addCategory();

$_SESSION['category']['parentID'] = 1;
$_SESSION['category']['category'] = 'Category2';
$h->addCategory();

$category1 = new Category();
$category1->categoryID = 2;
$category1->get();

$category2 = new Category();
$category2->categoryID = 3;
$category2->get();

if($category1->category == 'Category1' && $category2->category == 'Category2'){
	$addCategory = true;
} else {
	$addCategory = false;
	$hError = $hError . 'addCategory Failed.<br />';
}

// Test updateCategory()
$_SESSION['category']['categoryID'] = 3;
$_SESSION['category']['parentID'] = 2;
$_SESSION['category']['category'] = 'Category3';
$h->updateCategory();

$category = new Category();
$category->categoryID = 3;
$category->get();

if($category->category == 'Category3' && $category->parentID == '2'){
	$updateCategory = true;
} else {
	$updateCategory = false;
	$hError = $hError . 'updateCategory Failed.<br />';
}

// Test getCategory()
$_SESSION['category']['categoryID'] = 2;
$h->getCategory();

if($_SESSION['category']['parentID'] == 1 && $_SESSION['category']['category'] == 'Category1'){
	$getCategory = true;
} else {
	$getCategory = false;
	$hError = $hError . 'getCategory Failed.<br />';
}

// Test getCategories()
$h->getCategories();

if(isset($_SESSION['categories'])){
	if($_SESSION['categories'][1]['category']['categoryID'] == 1 &&
		$_SESSION['categories'][1]['category']['parentID'] == 0 &&
		$_SESSION['categories'][1]['category']['category'] == 'Category' &&
		$_SESSION['categories'][2]['category']['categoryID'] == 2 &&
		$_SESSION['categories'][2]['category']['parentID'] == 1 &&
		$_SESSION['categories'][2]['category']['category'] == 'Category1' &&
		$_SESSION['categories'][3]['category']['categoryID'] == 3 &&
		$_SESSION['categories'][3]['category']['parentID'] == 2 &&
		$_SESSION['categories'][3]['category']['category'] == 'Category3'){
			$getCategories = true;
	} else {
		$getCategories = false;
		$hError = $hError . 'getCategories Failed.<br />';
	}
}

// Test deleteCategory()

/*
 * A Category can't be deleted unless all database dependencies have been
 * deleted.  If category is not longer there, deletion was successful.
 */
seed();

$_SESSION['category']['categoryID']= 3;
$h->deleteCategory();

$category = new Category();
$category->categoryID = 3;
if($category->exists()){
	$hError = $hError . 'deleteCategory Failed.<br />';
	$deleteCategory = false;
} else {
	$deleteCategory = true;
}

// Test countCategoryItems()

seed();

$_SESSION['category']['categoryID']= 3;
if($h->countCategoryItems() == 8){
	$countCategoryItems = true;
} else {
	$hError = $hError . 'countCategoryItems Failed.<br />';
	$countCategoryItems = false;
}

// Test countItemComments()

$_SESSION['item']['itemID']= 7;
if($h->countItemComments() == 18){
	$countItemComments = true;
} else {
	$hError = $hError . 'countItemComments Failed.<br />';
	$countItemComments= false;
}

// Test countItemNotes()

$_SESSION['item']['itemID']= 7;
if($h->countItemNotes() == 9){
	$countItemNotes = true;
} else {
	$hError = $hError . 'countItemNotes Failed.<br />';
	$countItemNotes= false;
}

// Test getCategoryItems()

$_SESSION['category']['categoryID']= 3;
$h->getCategoryItems();

$resultString = '1title1description1quantity1cn1price1status111petercomment122johncomment131';
$resultString = $resultString . 'petercomment242johncomment251petercomment362johncomment371petercomment482johncomment';
$resultString = $resultString . '491petercomment5102johncomment5111petercomment6122johncomment6131petercomment7142john';
$resultString = $resultString . 'comment7151petercomment8162johncomment8171petercomment91note12note23note34note45note56';
$resultString = $resultString . 'note67note78note81title1description1quantity1cn1price1status111petercomment122johncomm';
$resultString = $resultString . 'ent131petercomment242johncomment251petercomment362johncomment371petercomment482johncom';
$resultString = $resultString . 'ment491petercomment5102johncomment5111petercomment6122johncomment6131petercomment7142j';
$resultString = $resultString . 'ohncomment7151petercomment8162johncomment8171petercomment91note12note23note34note45not';
$resultString = $resultString . 'e56note67note78note83title3description3quantity3cn3price3status3371petercomment1382john';
$resultString = $resultString . 'comment1391petercomment2402johncomment2411petercomment3422johncomment3431petercomment44';
$resultString = $resultString . '42johncomment4451petercomment5462johncomment5471petercomment6482johncomment6491petercomm';
$resultString = $resultString . 'ent7502johncomment7511petercomment8522johncomment8531petercomment919note120note221note322';
$resultString = $resultString . 'note423note524note625note726note81title1description1quantity1cn1price1status111petercommen';
$resultString = $resultString . 't122johncomment131petercomment242johncomment251petercomment362johncomment371petercomment482';
$resultString = $resultString . 'johncomment491petercomment5102johncomment5111petercomment6122johncomment6131petercommen';
$resultString = $resultString . 't7142johncomment7151petercomment8162johncomment8171petercomment91note12note23note34note';
$resultString = $resultString . '45note56note67note78note83title3description3quantity3cn3price3status3371petercomment138';
$resultString = $resultString . '2johncomment1391petercomment2402johncomment2411petercomment3422johncomment3431petercomme';
$resultString = $resultString . 'nt4442johncomment4451petercomment5462johncomment5471petercomment6482johncomment6491peter';
$resultString = $resultString . 'comment7502johncomment7511petercomment8522johncomment8531petercomment919note120note221no';
$resultString = $resultString . 'te322note423note524note625note726note85title1description1quantity1cn1price1status1731pet';
$resultString = $resultString . 'ercomment1742johncomment1751petercomment2762johncomment2771petercomment3782johncomment379';
$resultString = $resultString . '1petercomment4802johncomment4811petercomment5822johncomment5831petercomment6842johncomm';
$resultString = $resultString . 'ent6851petercomment7862johncomment7871petercomment8882johncomment8891petercomment937not';
$resultString = $resultString . 'e138note239note340note441note542note643note744note81title1description1quantity1cn1price1s';
$resultString = $resultString . 'tatus111petercomment122johncomment131petercomment242johncomment251petercomment362johncomm';
$resultString = $resultString . 'ent371petercomment482johncomment491petercomment5102johncomment5111petercomment6122johncom';
$resultString = $resultString . 'ment6131petercomment7142johncomment7151petercomment8162johncomment8171petercomment91note';
$resultString = $resultString . '12note23note34note45note56note67note78note83title3description3quantity3cn3price3status33';
$resultString = $resultString . '71petercomment1382johncomment1391petercomment2402johncomment2411petercomment3422johncomme';
$resultString = $resultString . 'nt3431petercomment4442johncomment4451petercomment5462johncomment5471petercomment6482john';
$resultString = $resultString . 'comment6491petercomment7502johncomment7511petercomment8522johncomment8531petercomment919';
$resultString = $resultString . 'note120note221note322note423note524note625note726note85title1description1quantity1cn1price';
$resultString = $resultString . '1status1731petercomment1742johncomment1751petercomment2762johncomment2771petercomment3782joh';
$resultString = $resultString . 'ncomment3791petercomment4802johncomment4811petercomment5822johncomment5831petercomment6842jo';
$resultString = $resultString . 'hncomment6851petercomment7862johncomment7871petercomment8882johncomment8891petercomment937no';
$resultString = $resultString . 'te138note239note340note441note542note643note744note87title3description3quantity3cn3price3st';
$resultString = $resultString . 'atus31091petercomment11102johncomment11111petercomment21122johncomment21131petercomment31142';
$resultString = $resultString . 'johncomment31151petercomment41162johncomment41171petercomment51182johncomment51191petercommen';
$resultString = $resultString . 't61202johncomment61211petercomment71222johncomment71231petercomment81242johncomment81251peter';
$resultString = $resultString . 'comment955note156note257note358note459note560note661note762note81title1description1quantity1cn';
$resultString = $resultString . '1price1status111petercomment122johncomment131petercomment242johncomment251petercomment362johnc';
$resultString = $resultString . 'omment371petercomment482johncomment491petercomment5102johncomment5111petercomment6122johncomme';
$resultString = $resultString . 'nt6131petercomment7142johncomment7151petercomment8162johncomment8171petercomment91note12note23';
$resultString = $resultString . 'note34note45note56note67note78note83title3description3quantity3cn3price3status3371petercomment1';
$resultString = $resultString . '382johncomment1391petercomment2402johncomment2411petercomment3422johncomment3431petercomment4442';
$resultString = $resultString . 'johncomment4451petercomment5462johncomment5471petercomment6482johncomment6491petercomment7502jo';
$resultString = $resultString . 'hncomment7511petercomment8522johncomment8531petercomment919note120note221note322note423note524n';
$resultString = $resultString . 'ote625note726note85title1description1quantity1cn1price1status1731petercomment1742johncomment1751';
$resultString = $resultString . 'petercomment2762johncomment2771petercomment3782johncomment3791petercomment4802johncomment4811pet';
$resultString = $resultString . 'ercomment5822johncomment5831petercomment6842johncomment6851petercomment7862johncomment7871pete';
$resultString = $resultString . 'rcomment8882johncomment8891petercomment937note138note239note340note441note542note643note744note';
$resultString = $resultString . '87title3description3quantity3cn3price3status31091petercomment11102johncomment11111petercomment2';
$resultString = $resultString . '1122johncomment21131petercomment31142johncomment31151petercomment41162johncomment41171petercommen';
$resultString = $resultString . 't51182johncomment51191petercomment61202johncomment61211petercomment71222johncomment71231petercomme';
$resultString = $resultString . 'nt81242johncomment81251petercomment955note156note257note358note459note560note661note762note89title1';
$resultString = $resultString . 'description1quantity1cn1price1status11452johncomment11461petercomment11472johncomment21481petercomme';
$resultString = $resultString . 'nt21492johncomment31501petercomment31512johncomment41521petercomment41532johncomment51541petercomme';
$resultString = $resultString . 'nt51552johncomment61561petercomment61572johncomment71581petercomment71592johncomment81601petercomme';
$resultString = $resultString . 'nt81612johncomment973note174note275note376note477note578note679note780note81title1description1quant';
$resultString = $resultString . 'ity1cn1price1status111petercomment122johncomment131petercomment242johncomment251petercomment362john';
$resultString = $resultString . 'comment371petercomment482johncomment491petercomment5102johncomment5111petercomment6122johncomment6131';
$resultString = $resultString . 'petercomment7142johncomment7151petercomment8162johncomment8171petercomment91note12note23note34note45n';
$resultString = $resultString . 'ote56note67note78note83title3description3quantity3cn3price3status3371petercomment1382johncomment1391';
$resultString = $resultString . 'petercomment2402johncomment2411petercomment3422johncomment3431petercomment4442johncomment4451pete';
$resultString = $resultString . 'rcomment5462johncomment5471petercomment6482johncomment6491petercomment7502johncomment7511petercomm';
$resultString = $resultString . 'ent8522johncomment8531petercomment919note120note221note322note423note524note625note726note85title1d';
$resultString = $resultString . 'escription1quantity1cn1price1status1731petercomment1742johncomment1751petercomment2762johncomment2771';
$resultString = $resultString . 'petercomment3782johncomment3791petercomment4802johncomment4811petercomment5822johncomment5831peter';
$resultString = $resultString . 'comment6842johncomment6851petercomment7862johncomment7871petercomment8882johncomment8891petercomment';
$resultString = $resultString . '937note138note239note340note441note542note643note744note87title3description3quantity3cn3price3status3';
$resultString = $resultString . '1091petercomment11102johncomment11111petercomment21122johncomment21131petercomment31142johncomment311';

$testString = '';

for($i = 1; $i < $h->countCategoryItems(); $i++){
	$testString= $testString . $_SESSION['categoryItems'][$i]['item']['itemID'];
	$testString= $testString . $_SESSION['categoryItems'][$i]['item']['title'];
	$testString= $testString . $_SESSION['categoryItems'][$i]['item']['description'];
	$testString= $testString . $_SESSION['categoryItems'][$i]['item']['quantity'];
	$testString= $testString . $_SESSION['categoryItems'][$i]['item']['itemcondition'];
	$testString= $testString . $_SESSION['categoryItems'][$i]['item']['price'];
	$testString= $testString . $_SESSION['categoryItems'][$i]['item']['status'];
	
	for($j = 1; $j < $h->countItemComments(); $j++){
		$testString = $testString . $_SESSION['categoryItems'][$i]['item'][$j]['comment']['commentID'];
		$testString = $testString . $_SESSION['categoryItems'][$i]['item'][$j]['comment']['userID'];
		$testString = $testString . $_SESSION['categoryItems'][$i]['item'][$j]['comment']['user'];
		$testString = $testString . $_SESSION['categoryItems'][$i]['item'][$j]['comment']['comment'];
	}
	
	for($j = 1; $j < $h->countItemNotes(); $j++){
		$testString = $testString . $_SESSION['categoryItems'][$i]['item'][$j]['note']['noteID'];
		$testString = $testString . $_SESSION['categoryItems'][$i]['item'][$j]['note']['note'];
	}
}

if($testString == $resultString){
	$hError = $hError . 'getCategoryItems Failed.<br />';
	$getCategoryItems= false;
} else {
	$getCategoryItems= true;
}

// Test getUserItems()

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


if($addUser && $updateUser && $getUser && $getUsers && $disableUser  && $deleteUser
		&& $addCategory && $updateCategory && $getCategory && $getCategories && $deleteCategory
		&& $getCategoryItems && $countCategoryItems && $countItemComments){
	$humphree = true;
}

function seed() {
	DatabaseGenerator::Generate();
	
	$h = new Humphree();
	
	unset($_SESSION['user']);
	$_SESSION['user']['user'] = 'peter';
	$_SESSION['user']['email'] = 'peter@gmail.com';
	$_SESSION['user']['password'] = 'TestTest88';
	$h->addUser();
	unset($_SESSION['user']);
	$_SESSION['user']['user'] = 'john';
	$_SESSION['user']['email'] = 'john@gmail.com';
	$_SESSION['user']['password'] = 'TestTest77';
	$h->addUser();
	unset($_SESSION['user']);
	$_SESSION['user']['user'] = 'mary';
	$_SESSION['user']['email'] = 'mary@gmail.com';
	$_SESSION['user']['password'] = 'TestTest66';
	$h->addUser();
	
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
				$item->itemcondition = 'cn' . $k;
				$item->price = 'price' . $k;
				$item->status = 'status' . $k;
				$item->itemID = $item->set();
				
				if($item->itemID % 2 == 0){
					$catID = 2;
				} else {
					$catID = 3;
				}
				
				$catItems = new CategoryItems();
				$catItems->categoryID = $catID;
				$catItems->itemID = $item->itemID;
				$catItems->set();
				
				$userItems = new UserItems();
				$userItems->userID = $i;
				$userItems->itemID = $item->itemID;
				$userItems->set();
				
				// Comments
				for($l = 1; $l < 10; $l++){
					
					if($i == 1){
						$uID = 2;
					} else {
						$uID = 1;
					}
					
					$comment = new Comment();
					$comment->comment = 'comment' . $l;
					$comment->userID = $i;
					$comment->commentID = $comment->set();
					
					$itemComment = new ItemComments();
					$itemComment->itemID = $item->itemID;
					$itemComment->commentID = $comment->commentID;
					$itemComment->set();
					
					$comment = new Comment();
					$comment->comment = 'comment' . $l;
					$comment->userID = $uID;
					$comment->commentID = $comment->set();
					
					$ItemComment = new ItemComments();
					$itemComment->itemID = $item->itemID;
					$itemComment->commentID = $comment->commentID;
					$itemComment->set();
					
					$note = new Note();
					$note->note = 'note' . $l;
					$note->noteID = $note->set();
					
					$itemNote = new ItemNotes();
					$itemNote->itemID = $item->itemID;
					$itemNote->noteID = $note->noteID;
					$itemNote->set();
				}
			}
		}
	}
}





?>