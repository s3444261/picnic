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
$countItemComments = false;
$countItemNotes = false;
$getUserItems = false;
$countUserItems = false;
$getItem = false;
$addItem = false;
$updateItem = false;
$deleteItem = false;
$getItemComments = false;
$getItemComment = false;
$addItemComment = false;
$updateItemComment = false;
$deleteItemComment = false;
$getItemNotes = false;
$getItemNote = false;
$addItemNote = false;
$updateItemNote= false;
$deleteItemNote = false;
$addSellerRating= false;
$addBuyerRating = false;

$h = new Humphree(TestPDO::getInstance());

// Test createAccount()

// Test activateAccount()

// Test changePassword()

// Test forgotPassword()

// Test addUser()
$_SESSION['user']['user'] = 'peter';
$_SESSION['user']['email'] = 'peter@gmail.com';
$_SESSION['user']['password'] = 'TestTest88';
if($h->addUser()){
	$user = new User(TestPDO::getInstance());
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
	$user = new User(TestPDO::getInstance());
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

$user = new User(TestPDO::getInstance());
$user->userID = 2;
if($user->exists()){
	$hError = $hError . 'deleteUser Failed.<br />';
	$deleteUser = false;
} else {
	$deleteUser = true;
}

DatabaseGenerator::Generate(TestPDO::getInstance());

// Test addCategory()
$category = new Category(TestPDO::getInstance());
$category->parentID = 0;
$category->category = 'Category';
$category->set();

$_SESSION['category']['parentID'] = 1;
$_SESSION['category']['category'] = 'Category1';
$h->addCategory();

$_SESSION['category']['parentID'] = 1;
$_SESSION['category']['category'] = 'Category2';
$h->addCategory();

$category1 = new Category(TestPDO::getInstance());
$category1->categoryID = 2;
$category1->get();

$category2 = new Category(TestPDO::getInstance());
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

$category = new Category(TestPDO::getInstance());
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

$category = new Category(TestPDO::getInstance());
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

// Test countCategoryItems()

$_SESSION['user']['userID']= 2;
if($h->countUserItems() == 8){
	$countUserItems = true;
} else {
	$hError = $hError . 'countUserItems Failed.<br />';
	$countUserItems = false;
}

// Test getUserItems()

$_SESSION['user']['userID']= 2;
$h->getUserItems();

$resultString = '';

$testString = '9title1description1quantity1cn1price1status11452johncomment11461petercomment11472';
$testString = $testString . 'johncomment21481petercomment21492johncomment31501petercomment31512jo';
$testString = $testString . 'hncomment41521petercomment41532johncomment51541petercomment51552joh';
$testString = $testString . 'ncomment61561petercomment61572johncomment71581petercomment71592john';
$testString = $testString . 'comment81601petercomment81612johncomment973note174note275note376note';
$testString = $testString . '477note578note679note780note810title2description2quantity2cn2price2sta';
$testString = $testString . 'tus21632johncomment11641petercomment11652johncomment21661petercomment21';
$testString = $testString . '672johncomment31681petercomment31692johncomment41701petercomment41712jo';
$testString = $testString . 'hncomment51721petercomment51732johncomment61741petercomment61752jo';
$testString = $testString . 'hncomment71761petercomment71772johncomment81781petercomment81792joh';
$testString = $testString . 'ncomment982note183note284note385note486note587note688note789note811t';
$testString = $testString . 'itle3description3quantity3cn3price3status31812johncomment11821petercom';
$testString = $testString . 'ment11832johncomment21841petercomment21852johncomment31861petercomment31';
$testString = $testString . '872johncomment41881petercomment41892johncomment51901petercomment51912j';
$testString = $testString . 'ohncomment61921petercomment61932johncomment71941petercomment71952johnc';
$testString = $testString . 'omment81961petercomment81972johncomment991note192note293note394note495n';
$testString = $testString . 'ote596note697note798note812title4description4quantity4cn4price4status4';
$testString = $testString . '1992johncomment12001petercomment12012johncomment22021petercomment22032';
$testString = $testString . 'johncomment32041petercomment32052johncomment42061petercomment42072john';
$testString = $testString . 'comment52081petercomment52092johncomment62101petercomment62112johncomm';
$testString = $testString . 'ent72121petercomment72132johncomment82141petercomment82152johncomment9';
$testString = $testString . '100note1101note2102note3103note4104note5105note6106note7107note813titl';
$testString = $testString . 'e1description1quantity1cn1price1status12172johncomment12181petercomme';
$testString = $testString . 'nt12192johncomment22201petercomment22212johncomment32221petercomment3';
$testString = $testString . '2232johncomment42241petercomment42252johncomment52261petercomment52272';
$testString = $testString . 'johncomment62281petercomment62292johncomment72301petercomment72312john';
$testString = $testString . 'comment82321petercomment82332johncomment9109note1110note2111note3112no';
$testString = $testString . 'te4113note5114note6115note7116note814title2description2quantity2cn2pri';
$testString = $testString . 'ce2status22352johncomment12361petercomment12372johncomment22381peterc';
$testString = $testString . 'omment22392johncomment32401petercomment32412johncomment42421petercomme';
$testString = $testString . 'nt42432johncomment52441petercomment52452johncomment62461petercomment';
$testString = $testString . '62472johncomment72481petercomment72492johncomment82501petercomment82';
$testString = $testString . '512johncomment9118note1119note2120note3121note4122note5123note6124note';
$testString = $testString . '7125note815title3description3quantity3cn3price3status32532johncomme';
$testString = $testString . 'nt12541petercomment12552johncomment22561petercomment22572johncomment3';
$testString = $testString . '2581petercomment32592johncomment42601petercomment42612johncomment5262';
$testString = $testString . '1petercomment52632johncomment62641petercomment62652johncomment72661';
$testString = $testString . 'petercomment72672johncomment82681petercomment82692johncomment9127not';
$testString = $testString . 'e1128note2129note3130note4131note5132note6133note7134note8';

for($i = 1; $i < $h->countUserItems(); $i++){
	$testString= $testString . $_SESSION['userItems'][$i]['item']['itemID'];
	$testString= $testString . $_SESSION['userItems'][$i]['item']['title'];
	$testString= $testString . $_SESSION['userItems'][$i]['item']['description'];
	$testString= $testString . $_SESSION['userItems'][$i]['item']['quantity'];
	$testString= $testString . $_SESSION['userItems'][$i]['item']['itemcondition'];
	$testString= $testString . $_SESSION['userItems'][$i]['item']['price'];
	$testString= $testString . $_SESSION['userItems'][$i]['item']['status'];
	
	for($j = 1; $j < $h->countItemComments(); $j++){
		$testString = $testString . $_SESSION['userItems'][$i]['item'][$j]['comment']['commentID'];
		$testString = $testString . $_SESSION['userItems'][$i]['item'][$j]['comment']['userID'];
		$testString = $testString . $_SESSION['userItems'][$i]['item'][$j]['comment']['user'];
		$testString = $testString . $_SESSION['userItems'][$i]['item'][$j]['comment']['comment'];
	}
	
	for($j = 1; $j < $h->countItemNotes(); $j++){
		$testString = $testString . $_SESSION['userItems'][$i]['item'][$j]['note']['noteID'];
		$testString = $testString . $_SESSION['userItems'][$i]['item'][$j]['note']['note'];
	}
}

if($testString == $resultString){
	$hError = $hError . 'getUserItems Failed.<br />';
	$getUserItems= false;
} else {
	$getUserItems= true;
}

// Test getItem()

$_SESSION['item']['itemID']= 4;
$h->getItem();

$resultString = '';

$testString = '4title4description4quantity4cn4price4status4551petercomment1562johncomment1571peter';
$testString = $testString . 'comment2582johncomment2591petercomment3602johncomment3611petercomment';
$testString = $testString . '4622johncomment4631petercomment5642johncomment5651petercomment6662john';
$testString = $testString . 'comment6671petercomment7682johncomment7691petercomment8702johncomment87';
$testString = $testString . '11petercomment928note129note230note331note432note533note634note735note8';

$testString= $testString . $_SESSION['item']['itemID'];
$testString= $testString . $_SESSION['item']['title'];
$testString= $testString . $_SESSION['item']['description'];
$testString= $testString . $_SESSION['item']['quantity'];
$testString= $testString . $_SESSION['item']['itemcondition'];
$testString= $testString . $_SESSION['item']['price'];
$testString= $testString . $_SESSION['item']['status'];

for($i = 1; $i < $h->countItemComments(); $i++){
	$testString = $testString . $_SESSION['item'][$i]['comment']['commentID'];
	$testString = $testString . $_SESSION['item'][$i]['comment']['userID'];
	$testString = $testString . $_SESSION['item'][$i]['comment']['user'];
	$testString = $testString . $_SESSION['item'][$i]['comment']['comment'];
}

for($i = 1; $i < $h->countItemNotes(); $i++){
	$testString = $testString . $_SESSION['item'][$i]['note']['noteID'];
	$testString = $testString . $_SESSION['item'][$i]['note']['note'];
}

if($testString == $resultString){
	$hError = $hError . 'getItem Failed.<br />';
	$getItem = false;
} else {
	$getItem = true;
}

// Test addItem()

$_SESSION['user']['userID'] = 2;
$_SESSION['item']['title'] = 'addTitle';
$_SESSION['item']['description'] = 'addDescription';
$_SESSION['item']['quantity'] = 'addQuantity';
$_SESSION['item']['itemcondition'] = 'aic';
$_SESSION['item']['price'] = 'aprice';
$_SESSION['item']['status'] = 'addStatus';

$h->addItem();

$resultString = '';
$testString = '17addTitleaddDescriptionaddQuantityaicapriceaddStatus';

$item = new Item(TestPDO::getInstance());
$item->itemID = 17;
$item->get();

$resultString = $item->itemID . $item->title . $item->description . $item->quantity;
$resultString = $resultString . $item->itemcondition . $item->price . $item->status;

if($testString == $resultString){
	$addItem = true;
} else {
	$hError = $hError . 'addItem Failed.<br />';
	$addItem = false;
}

// Test updateItem()

$_SESSION['item']['itemID'] = 17;
$_SESSION['item']['title'] = 'updateTitle';
$_SESSION['item']['description'] = 'updateDescription';
$_SESSION['item']['quantity'] = 'updateQuantity';
$_SESSION['item']['itemcondition'] = 'uic';
$_SESSION['item']['price'] = 'uprice';
$_SESSION['item']['status'] = 'updateStatus';

$h->updateItem();

$resultString = '';
$testString = '17updateTitleupdateDescriptionupdateQuantityuicupriceupdateStatus';

$item = new Item(TestPDO::getInstance());
$item->itemID = 17;
$item->get();

$resultString = $item->itemID . $item->title . $item->description . $item->quantity;
$resultString = $resultString . $item->itemcondition . $item->price . $item->status;

if($testString == $resultString){
	$updateItem = true;
} else {
	$hError = $hError . 'updateItem Failed.<br />';
	$updateItem = false;
}

// Test deleteItem()

/*
 * An Item can't be deleted unless all database dependencies have been
 * deleted.  If an item is not longer there, deletion was successful.
 */

$_SESSION['item']['itemID']= 16;
$h->deleteItem();

$item = new Item(TestPDO::getInstance());
$item->itemID = 16;
if($item->exists()){
	$hError = $hError . 'deleteItem Failed.<br />';
	$deleteItem = false;
} else {
	$deleteItem = true;
}

// Test getItemComments()

$_SESSION['item']['itemID'] = 14;

$h->getItemComments();

$resultString = '';
$testString = '2352johncomment12361petercomment12372johncomment22381petercomment22392johncommen';
$testString = $testString . 't32401petercomment32412johncomment42421petercomment42432johncommen';
$testString = $testString . 't52441petercomment52452johncomment62461petercomment62472johncommen';
$testString = $testString . 't72481petercomment72492johncomment82501petercomment82512johncommen';
$testString = $testString . 't92521petercomment9';

for($i = 1; $i <= $h->countItemComments(); $i++){
	$resultString = $resultString . $_SESSION['item'][$i]['comment']['commentID'];
	$resultString = $resultString . $_SESSION['item'][$i]['comment']['userID'];
	$resultString = $resultString . $_SESSION['item'][$i]['comment']['user'];
	$resultString = $resultString . $_SESSION['item'][$i]['comment']['comment'];
}

if($testString == $resultString){
	$getItemComments = true;
} else {
	$hError = $hError . 'getItemComments Failed.<br />';
	$getItemComments = false;
}

// Test getItemComment()

$_SESSION['comment']['commentID'] = 10;

$h->getItemComment();

$resultString = '';
$testString = '102johncomment5';

$resultString = $resultString . $_SESSION['comment']['commentID'];
$resultString = $resultString . $_SESSION['comment']['userID'];
$resultString = $resultString . $_SESSION['comment']['user'];
$resultString = $resultString . $_SESSION['comment']['comment'];


if($testString == $resultString){
	$getItemComment = true;
} else {
	$hError = $hError . 'getItemComment Failed.<br />';
	$getItemComment = false;
}

// Test addItemComment()
$_SESSION ['user'] ['userID'] = 2;
$_SESSION ['item'] ['itemID'] = 17;
$_SESSION ['comment'] ['comment'] = 'AddedComment';
$h->addItemComment();

unset ( $_SESSION ['user'] );
unset ( $_SESSION ['item'] );
unset ( $_SESSION ['comment'] );

$resultString = '';
$testString = '2892johnAddedComment';

$_SESSION['comment']['commentID'] = 289;
$h->getItemComment();

$resultString = $resultString . $_SESSION['comment']['commentID'];
$resultString = $resultString . $_SESSION['comment']['userID'];
$resultString = $resultString . $_SESSION['comment']['user'];
$resultString = $resultString . $_SESSION['comment']['comment'];
unset ( $_SESSION ['comment'] );

if($testString == $resultString){
	$addItemComment= true;
} else {
	$hError = $hError . 'getItemComment Failed.<br />';
	$addItemComment= false;
}

// Test updateItemComment()

$_SESSION ['comment'] ['commentID'] = 289;
$_SESSION ['comment'] ['userID'] = 1;
$_SESSION ['comment'] ['comment'] = 'UpdateComment';
$h->updateItemComment();

unset ( $_SESSION ['user'] );
unset ( $_SESSION ['item'] );
unset ( $_SESSION ['comment'] );

$resultString = '';
$testString = '2891peterUpdateComment';

$_SESSION['comment']['commentID'] = 289;
$h->getItemComment();

$resultString = $resultString . $_SESSION['comment']['commentID'];
$resultString = $resultString . $_SESSION['comment']['userID'];
$resultString = $resultString . $_SESSION['comment']['user'];
$resultString = $resultString . $_SESSION['comment']['comment'];
unset ( $_SESSION ['comment'] );

if($testString == $resultString){
	$updateItemComment= true;
} else {
	$hError = $hError . 'getItemComment Failed.<br />';
	$updateItemComment= false;
}

// Test deleteItemComment()

$_SESSION['comment']['commentID'] = 289;
$h->deleteItemComment();

$testCommentID = 289;

$ic = new ItemComments(TestPDO::getInstance());
$ic->commentID = $testCommentID;

try {
	$ic->getItemComment();
} catch (ItemCommentsException $e){

	if($e->getError () == 'Could not retrieve itemComment.'){
		$c = new Comment(TestPDO::getInstance());
		$c->commentID = $testCommentID;
		if(!$c->exists()){
			$deleteItemComment = true;
		} else {
			$deleteItemComment = false;
			$hError = $hError . 'deleteItemComment Failed.<br />';
		}
	} else {
		$deleteItemComment = false;
		$hError = $hError . 'deleteItemComment Failed.<br />';
	} 
} 

// Test getItemNotes()

$_SESSION['item']['itemID'] = 14;

$h->getItemNotes();

$resultString = '';
$testString = '118note1119note2120note3121note4122note5123note6124note7125note8126note9';

for($i = 1; $i <= $h->countItemNotes(); $i++){
	$resultString = $resultString . $_SESSION['item'][$i]['note']['noteID'];
	$resultString = $resultString . $_SESSION['item'][$i]['note']['note'];
}

if($testString == $resultString){
	$getItemNotes = true;
} else {
	$hError = $hError . 'getItemNotes Failed.<br />';
	$getItemNotes = false;
}

// Test getItemNote()

$_SESSION['note']['noteID'] = 10;

$h->getItemNote();

$resultString = '';
$testString = '10note1';

$resultString = $resultString . $_SESSION['note']['noteID'];
$resultString = $resultString . $_SESSION['note']['note'];

if($testString == $resultString){
	$getItemNote = true;
} else {
	$hError = $hError . 'getItemNote Failed.<br />';
	$getItemNote = false;
}

// Test addtemNote()

$_SESSION ['item'] ['itemID'] = 17;
$_SESSION ['note'] ['note'] = 'AddedNote';
$h->addItemNote();

unset ( $_SESSION ['item'] );
unset ( $_SESSION ['note'] );

$resultString = '';
$testString = '145AddedNote';

$_SESSION['note']['noteID'] = 145;
$h->getItemNote();

$resultString = $resultString . $_SESSION['note']['noteID'];
$resultString = $resultString . $_SESSION['note']['note']; 
unset ( $_SESSION ['note'] );

if($testString == $resultString){
	$addItemNote = true;
} else {
	$hError = $hError . 'getItemNote Failed.<br />';
	$addItemNote = false;
}

// Test updateItemNote()

$_SESSION ['note'] ['noteID'] = 145;
$_SESSION ['note'] ['note'] = 'UpdateNote';
$h->updateItemNote();

unset ( $_SESSION ['item'] );
unset ( $_SESSION ['note'] );

$resultString = '';
$testString = '145UpdateNote';

$_SESSION['note']['noteID'] = 145;
$h->getItemNote();

$resultString = $resultString . $_SESSION['note']['noteID'];
$resultString = $resultString . $_SESSION['note']['note'];
unset ( $_SESSION ['note'] );

if($testString == $resultString){
	$updateItemNote = true;
} else {
	$hError = $hError . 'getItemNote Failed.<br />';
	$updateItemNote = false;
}

// Test deleteItemNote()

$_SESSION['note']['noteID'] = 145;
$h->deleteItemNote();

$testNoteID = 145;

$in = new ItemNotes(TestPDO::getInstance());
$in->noteID = $testNoteID;

try {
	$in->getItemNote();
} catch (ItemNotesException $e){
	
	if($e->getError () == 'Could not retrieve itemNote.'){
		$n = new Note(TestPDO::getInstance());
		$n->noteID = $testNoteID;
		if(!$n->exists()){
			$deleteItemNote = true;
		} else {
			$deleteItemNote = false;
			$hError = $hError . 'deleteItemNote Failed.<br />';
		}
	} else {
		$deleteItemNote = false;
		$hError = $hError . 'deleteItemNote Failed.<br />';
	}
} 

// Test addSellerRating()

$_SESSION ['user_rating'] ['itemID'] = 14;
$_SESSION ['user_rating'] ['sellrating'] = 5;
$h->addSellerRating();
unset($_SESSION ['user_rating']);

$ur = new UserRatings(TestPDO::getInstance());
$ur->user_ratingID = 1;
$ur->get();

if($ur->itemID == 14 && $ur->sellrating == 5 && strlen($ur->transaction) == 32){
	$addSellerRating = true;
} else {
	$hError = $hError . 'addSellerRating Failed.<br />';
	$addSellerRating = false;
}

// Test addBuyerRating()

$_SESSION ['user_rating'] ['userID'] = 2;
$_SESSION ['user_rating'] ['buyrating'] = 4;
$_SESSION ['user_rating'] ['transaction'] = $ur->transaction;
$h->addBuyerRating();

$ur = new UserRatings(TestPDO::getInstance());
$ur->user_ratingID = 1;
$ur->get(); 

if($ur->itemID == 14 && $ur->sellrating == 5 && strlen($ur->transaction) == 32
		&& $ur->userID == 2 && $ur->buyrating == 4){
			$addBuyerRating = true;
} else {
	$hError = $hError . 'addBuyerRating Failed.<br />';
	$addBuyerRating = false;
}

// Test Search()


if($addUser && $updateUser && $getUser && $getUsers && $disableUser  && $deleteUser
		&& $addCategory && $updateCategory && $getCategory && $getCategories && $deleteCategory
		&& $getCategoryItems && $countCategoryItems && $countItemComments && $countItemNotes 
		&& $countUserItems && $getUserItems && $getItem && $addItem && $updateItem &&$deleteItem
		&& $getItemComments && $getItemComment && $addItemComment && $updateItemComment
		&& $deleteItemComment && $getItemNotes && $getItemNote && $addItemNote && $updateItemNote
		&& $deleteItemNote && $addSellerRating && $addBuyerRating){
	$humphree = true;
}

function seed() {
	DatabaseGenerator::Generate(TestPDO::getInstance());
	
	$h = new Humphree(TestPDO::getInstance());
	
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
	
	$category = new Category(TestPDO::getInstance());
	$category->parentID = 0;
	$category->category = 'Category';
	$category->set();
	
	$category = new Category(TestPDO::getInstance());
	$category->parentID = 1;
	$category->category = 'Category1';
	$category->set();
	
	$category = new Category(TestPDO::getInstance());
	$category->parentID = 1;
	$category->category = 'Category2';
	$category->set();
	
	// userID
	for($i = 1; $i < 3; $i++){
		
		// categoryID
		for($j = 2; $j < 4; $j++){
			
			// item
			for($k = 1; $k < 5; $k++){
				
				$item = new Item(TestPDO::getInstance());
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
				
				$catItems = new CategoryItems(TestPDO::getInstance());
				$catItems->categoryID = $catID;
				$catItems->itemID = $item->itemID;
				$catItems->set();
				
				$userItems = new UserItems(TestPDO::getInstance());
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
					
					$comment = new Comment(TestPDO::getInstance());
					$comment->comment = 'comment' . $l;
					$comment->userID = $i;
					$comment->commentID = $comment->set();
					
					$itemComment = new ItemComments(TestPDO::getInstance());
					$itemComment->itemID = $item->itemID;
					$itemComment->commentID = $comment->commentID;
					$itemComment->set();
					
					$comment = new Comment(TestPDO::getInstance());
					$comment->comment = 'comment' . $l;
					$comment->userID = $uID;
					$comment->commentID = $comment->set();
					
					$ItemComment = new ItemComments(TestPDO::getInstance());
					$itemComment->itemID = $item->itemID;
					$itemComment->commentID = $comment->commentID;
					$itemComment->set();
					
					$note = new Note(TestPDO::getInstance());
					$note->note = 'note' . $l;
					$note->noteID = $note->set();
					
					$itemNote = new ItemNotes(TestPDO::getInstance());
					$itemNote->itemID = $item->itemID;
					$itemNote->noteID = $note->noteID;
					$itemNote->set();
				}
			}
		}
	}
}





?>