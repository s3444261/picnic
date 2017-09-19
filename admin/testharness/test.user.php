<?php

/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 * 
 * Test Data
 */
$attributes = array('userID',
		'user',
		'email',
		'password',
		'status',
		'activate',
		'created_at',
		'updated_at');

$testAtts = array('userID',
		'user',
		'email',
		'password',
		'status');

$statusValue = 'active';
$altStatusValue = 'suspended'; 
$attributeError = NULL;
$setError = NULL;
$getError = NULL;
$updateError = NULL;
$existsError = NULL;
$deleteError = NULL;
$loginError = NULL;
$logoutError = NULL;
$userTest = false;
$atts = false;
$set = false;
$get = false;
$up = false;
$exists = false;
$delete = false;
$login = false;
$logout = false;

$args1 = array();
$args1['userID'] = 1;
$args1['user'] = 'grant';
$args1['email'] = 'grant@kinkead.net';
$args1['password'] = 'TestTest88';
$args1['status'] = $statusValue;
$args1['activate'] = 'blahblahblah';
$args1['created_at'] = '2015-02-13';
$args1['updated_at'] = '2015-02-13';

$args2 = array();
$args2['user'] = 'grant';
$args2['email'] = 'grant@kinkead.net';
$args2['password'] = 'TestTest88';


$args3 = array();
$args3['userID'] = '1';
$args3['user'] = '';
$args3['email'] = '';
$args3['password'] = '';
$args3['status'] = $statusValue;
$args3['activate'] = '';

$args4 = array();
$args4['userID'] = '1';
$args4['user'] = 'grant';
$args4['email'] = 'grant@kinkead.net';
$args4['password'] = '621fc97fcde3aeb7402c6f43fe700cca5e61ab72';
$args4['status'] = $statusValue;

$args5 = $args3;
$args5['userID'] = '200';

$args6 = array();
$args6['user'] = 'connor';
$args6['email'] = 'connor@kinkead.net';
$args6['password'] = 'TooManyGames99';
$args6['status'] = $statusValue;

$args7 = array();
$args7['userID'] = '2';

/*
 * Test Attributes
 * Ensure that each of the attributes can be set and retrieved.
 * If all attributes can be set and retrieved attributes passes the test.
 */ 
$user = new User();

$atts = testAttributes($user, $args1, $attributes);
if(!$atts){
	$attributeError = "Attribute Error<br />";
	$unitTestResults = $unitTestResults . $attributeError;
}

/*
 * Operators
 * Test that all operators are functional.
 */

/*
 * Test set()
 */ 
$user = new User($args2);
$user->userID = $user->set();
if($user->userID > 0){
	$set = true;
} else {
	$set = false;
	$setError = $setError . "Set() failed to create user.<br />";
}

/*
 * If any of the set tests failed, print the error.
 */ 
if($setError){
	$unitTestResults = $unitTestResults . $setError;
}

/*
 * Test get()
 * An object is created with all attributes set to empty other than the userID which is set
 * to '1'.  The get() function now retrieves the other attributes from the database for that
 * userID. All the attributes can be compared except for activate, which is random.
 */ 

$user = new User($args3);
$user->get();
if(compareAttributes($user, $args4, $testAtts)){
	$get1 = true;
} else {
	$get1 = false;
	$getError = $getError . "Unable to retrieve matching attributes.<br />";
}

/*
 * The get() function is now tested attempting to retrieve
 * a userID that doesn't exist.  If it can't do it, the
 * test is passed.
 */ 
$user = new User($args5);

if(!$user->get()){
	$get2 = true;
} else {
	$get2 = false;
	$getError = $getError . "get() non existant ID Failed.<br />";
}

/*
 * The get() function is now tested attempting to retrieve
 * an empty userID.  If it can't do it, the test is passed.
 */ 
$user = new User();

if(!$user->get()){
	$get3 = true;
} else {
	$get3 = false;
	$getError = $getError . "get() empty ID Failed.<br />";
}

/*
 * If all three tests are passed the get is set as a pass.
 */ 
if($get1 && $get2 && $get3){
	$get = true;
} else {
	$get = false;
	$getError = $getError . "Get() Failed.<br />";
}

/*
 * If any of the get tests failed, print the error.
 */
if($getError){
	$unitTestResults = $unitTestResults . $getError;
}

/*
 * Test update()
 * The objects attributes are emptied except for the userID which
 * remains set to 1.  Each attribute is then altered and updated.
 * It is then compared with the expected arguments.  If all attributes
 * can be individually updated, then the update() test is passed.
 */

/*
 * Test that a user name can be updated.
 */
$user = new User($args3);
$us = new User($args3);
$us->get();
$user->user = 'peter';
$user->update();
$user->get();
if($user->user == 'peter'){
	$update1 = true;
} else {
	$update1 = false;
	$updateError = $updateError . "Could not update user with user name.<br />";
}

/*
 * Test that an email address can be updated.
 */
$user = new User($args3);
$us = new User($args3);
$us->get();
$user->email = 'peter@kinkead.net';
$user->update();
$user->get();

if($user->email == 'peter@kinkead.net'){
	$update2 = true;
} else {
	$update2 = false;
	$updateError = $updateError . "Could not update user with email address.<br />";
}

/*
 * Test that a password can be updated.
 */
$user = new User($args3);
$us = new User($args3);
$us->get();
$user->password = 'AABBCCddeeff99';
$user->update();
$user->get();

if(strlen($user->password) == strlen($us->password) && $user->password != $us->password){
	$update3 = true;
} else {
	$update3 = false;
	$updateError = $updateError . "Did not update password.<br />";
}

/*
 * Test that status can be updated.
 */
$user = new User($args3);
$us = new User($args3);
$us->get();
$user->status = $altStatusValue;
$user->update();
$user->get();

if($user->status != $us->status && $user->status == $altStatusValue){
	$update4 = true;
} else {
	$update4 = false;
	$updateError = $updateError . "Did not update status.<br />";
}

// Return to the original setting for subsequent tests.
$user->status = $statusValue;
$user->update();

/*
 * If all tests are passed then update is set as a pass.
 */ 
if($update1 && $update2 && $update3 && $update4){
	$update = true;
} else {
	$update = false;
	$updateError = $updateError . "Update() Failed.<br />";
}

/*
 * If any of the get tests failed, print the error.
 */
if($updateError){
	$unitTestResults = $unitTestResults . $updateError;
}

/*
 * Test exists()
 */ 
$user = new User();
$user->userID = 1;
if($user->exists()){
	$exists1 = true;
} else {
	$exists1 = false;
	$existsError = $existsError . "exists() couldn\'t find existing ID'<br />";
}

$user->userID = 200;
if(!$user->exists()){
	$exists2 = true;
} else {
	$exists2 = false;
	$existsError = $existsError . "exists() found non-existant ID'<br />";
}

$user->userID = '';
if(!$user->exists()){
	$exists3 = true;
} else {
	$exists3 = false;
	$existsError = $existsError . "exists() found empty ID'<br />";
}

if($exists1 && $exists2 && $exists3){
	$exists = true;
} else {
	$exists = false;
	$existsError = $existsError . "exists() Failed'<br />";
}

/*
 * If any of the get tests failed, print the error.
 */
if($existsError){
	$unitTestResults = $unitTestResults . $existsError;
}

/*
 * Test delete()
 */ 
$user = new User();
$user->userID = 200;
if(!$user->delete()){
	$delete1 = true;
} else {
	$delete1 = false;
	$deleteError = $deleteError . "delete() deleted non existant ID'<br />";
}

$user->userID = '';
if(!$user->delete()){
	$delete2 = true;
} else {
	$delete2 = false;
	$deleteError = $deleteError . "delete() deleted empty ID'<br />";
}

$user->userID = 1;
if($user->delete()){
	if(!$user->exists()){
		$delete3 = true;
	}
} else {
	$delete3 = false;
	$deleteError = $deleteError . "delete() couldn't delete ID<br />";
}

if($delete1 && $delete2 && $delete3){
	$delete = true;
} else {
	$delete = false;
	$deleteError = $deleteError . "delete() Failed'<br />";
}

/*
 * If any of the delete tests failed, print the error.
 */
if($deleteError){
	$unitTestResults = $unitTestResults . $deleteError;
}

/*
 * Test login()
 * All tests are based on the assumption that a session has been started.
 * The login will fail if there is no session started, but this cannot be
 * tested here as any session must be started priot to any information 
 * being sent to the browser.
 */

/*
 * Test that a login fails with incorrect email.
 */
unset($_SESSION[MODULE]);
$user = new User($args6);
$user->userID = $user->set();
$user->get();
$user->email = 'blah@hotmail.com';
$user->login();

if(!isset($_SESSION[MODULE])){
	$lgi1 = true;
} else {
	$lgi1 = false;
	$loginError = $loginError . "Logged in with incorrect email.<br />";
	unset($_SESSION[MODULE]);
}

/*
 * Test that a login fails with a valid email but incorrect password.
 */ 
unset($_SESSION[MODULE]);
$user = new User($args6);
$user->password = 'TooManygames99';
$user->login();

if(!isset($_SESSION[MODULE])){
	$lgi2 = true;
} else {
	$lgi2 = false;
	$loginError = $loginError . "Logged in with an incorrect password.<br />";
	unset($_SESSION[MODULE]);
}

/*
 * Test that a login fails if the user is not activated.
 */ 
unset($_SESSION[MODULE]);
$user = new User($args6);
$user->login();
if(!isset($_SESSION[MODULE])){
	$lgi3 = true;
} else {
	$lgi3 = false;
	$loginError = $loginError . "Logged in with user not activated.<br />";
	unset($_SESSION[MODULE]);
}

/*
 * Test that a login fails if the status of the account is not active.
 * First we set activate to NULL and then status to 'suspended'. Next
 * we attempt to login.
 */ 
unset($_SESSION[MODULE]);
$us = new User($args7);
$us->get();
$us->activate();
$us->status = $altStatusValue;
$us->update();

$user = new User($args6);
$user->login();
if(!isset($_SESSION[MODULE])){
	$lgi4 = true;
} else {
	$lgi4 = false;
	$loginError = $loginError . "Logged in with an account that is activated but suspended.<br />";
	unset($_SESSION[MODULE]);
}

/*
 * Test that a login succeeds with a valid email and password when the account
 * is both active and activated.
 */ 
unset($_SESSION[MODULE]);
$us = new User($args7);
$us->get();
$us->status = $statusValue;
$us->update();

$user = new User($args6);
$user->login();
if($_SESSION[MODULE] = true
    && $_SESSION['userID'] = $args7['userID']
    && $_SESSION['user'] = $args6['user']
    && $_SESSION['email'] = $args6['email']
    && $_SESSION['status'] = $args6['status']){
	$lgi5 = true;
} else {
	$lgi5 = false;
	$loginError = $loginError . "Failed to login with correct attributes.<br />";
}

/*
 * If all tests are passed then login is set as a pass.
 */
if($lgi1 && $lgi2 && $lgi3 && $lgi4 && $lgi5){
	$login = true;
} else {
	$login = false;
	$loginError = $loginError . "Login() Failed<br />";
}

/*
 * If any of the login tests failed, print the error.
 */
if($loginError){
	$unitTestResults = $unitTestResults . $loginError;
}

/*
 * Test Logout()
 */
$user = new User($args7);
$user->get();
$user->logout();

if(!$_SESSION){
	$lgo = true;
} else {
	$lgo = false;
	$logoutError = $logoutError . "Failed to Logout.<br />";
}

if($lgo){
	$logout = true;
} else {
	$logout = false;
	$logoutError = $logoutError . "Logout() Failed.<br />";
}

/*
 * If any of the logout tests failed, print the error.
 */ 
if($logoutError){
	$unitTestResults = $unitTestResults . $logoutError;
}

/*
 * Test checkPassword()
 */

/*
 * Test check that the correct userID is set for a correct password.
 */ 
$checkPasswordError = null;
$user = new User($args7);
$user->get(); 
$user->password = $args6['password'];
$user->update();
$compareID = $user->userID;
$user = new User();
$user->email = $args6['email'];
$user->password = $args6['password']; 
$user->checkPassword();

if($compareID == $user->userID){
	$cp1 = true;
} else {
	$cp1 = false;
	$checkPasswordError = "Failed to match a correct Password.<br />";
}

/*
 * Test check that the correct userID is set for a correct password.
 */ 
$user = new User();
$user->email = $args6['email'];
$user->password = 'TestTest99';
$user->checkPassword();

if($compareID != $user->userID){
	$cp2 = true;
} else {
	$cp2 = false;
	$checkPasswordError = $checkPasswordError . "Matched an incorrect Password.<br />";
}

if($checkPasswordError){
	$unitTestResults = $unitTestResults . $checkPasswordError;
}

/*
 * If all tests are passed then checkPassword is set as a pass.
 */ 
if($cp1 && $cp2){
	$checkPassword = true;
} else {
	$checkPassword = false;
	$checkPasswordError = $checkPasswordError . "checkPassword() Failed<br />";
}


$unitTestResults = $unitTestResults . 'User: <font color="';

if($atts && $set && $get && $update && $exists 
		&& $delete && $login && $logout && $checkPassword){
			$unitTestResults = $unitTestResults . 'green">PASS';
			$userTest = true;
} else {
	$unitTestResults = $unitTestResults . 'red">FAIL';
}

$unitTestResults = $unitTestResults . '</font><br />';



?>