<?php

/*
 * Test addUser
 */

$adduserTest = false;
$obj = false;
$obj1 = false;
$obj2 = false;
$acct = false;

/*
 * Test for object
 */
$objectError = null;
unset ( $_SESSION ['error'] );
$item = new Item();
$user = new Users();
$user->user = 'testtest';
$user->email = 'test@gmail.com';
$user->password = 'TestTest88';
$sys = new System();
if($sys->addUser($item)){
	$obj1 = false;
	$objectError = $objectError . 'Passed a variable other than a User Object.<br />';
} else {
	$obj1 = true;
}
if($sys->addUser($user)){
	$obj2 = true;
} else {
	if(isset($_SESSION ['error'])){
		$obj2 = false;
		$objectError = $objectError . $_SESSION ['error'] . '<br />';
	}
}

if($obj1 && $obj2){
	$obj = true;
} else {
	$obj = false;
	$systemTestResults = $systemTestResults . $objectError;
}

/*
 * Note:  Validation of user, email and password not tested as the Validation Class has
 * already been tested and passed.
 */

/*
 * Test for user being successfully added to the database.
 */
$user->get();
if($user->userID == 1 && $user->user == 'testtest' && $user->email == 'test@gmail.com'
		&& $user->password == '613b59f86203d9e986e08514d175a7d690f3b8f9'
		&& $user->status = 'active' && is_null($user->activate)){
	$acct = true;
} else {
	$acct = false;
	$systemTestResults = $systemTestResults . 'User not added to the database.<br />';
}


$systemTestResults = $systemTestResults . 'addUser(): <font color="';

if($obj && $acct){
	$systemTestResults = $systemTestResults . 'green">PASS';
	$adduserTest = true;
} else {
	$systemTestResults = $systemTestResults . 'red">FAIL';
}

$systemTestResults = $systemTestResults . '</font><br />';



?>