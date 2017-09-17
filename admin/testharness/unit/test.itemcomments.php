<?php

/*
 * Test Data
 */
$atts = false;
$set = false;
$get = false;
$update = false; 
$exists = false;
$delete = false;
$countError = false;

$attributes = array(ATTQ,
					ATTI,
					ATTF);

$testAtts = array(ATTQ,
					ATTI,
					ATTF
);

$args1 = array();
$args1[ATTQ] = 1;
$args1[ATTI] = 1;
$args1[ATTF] = 1;

$args2 = array();
$args2[ATTI] = 1;
$args2[ATTF] = 1;

$args3 = array();
$args3[ATTQ] = 1;
$args3[ATTI] = '';
$args3[ATTF] = '';

$args4 = array();
$args4[ATTQ] = 1;
$args4[ATTI] = 1;
$args4[ATTF] = 1;

$args5 = array();
$args5[ATTQ] = 100;
$args5[ATTI] = '';
$args5[ATTF] = '';

$args6 = array();
$args6[ATTQ] = 1;
$args6[ATTI] = 2;
$args6[ATTF] = '';

$args7 = array();
$args7[ATTQ] = 1;
$args7[ATTI] = '';
$args7[ATTF] = 2;

$args8 = array();
$args8[ATTI] = 1;
$args8[ATTF] = 2;

$args9 = array();
$args9[ATTI] = 1;
$args9[ATTF] = 3;

/*
 * Seed Users, Item and Comment
 */
$user = new Users ();
$user->user = 'grant';
$user->email = 'grant@kinkead.net';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

for($i = 0; $i < 3; $i++){
	$item = new Item();
	$item->set();
	$item->get();
	
	$comment = new Comment();
	$comment->userID = $user->userID;
	$comment->set();
	$comment->get();
}


/*
 * Test Attributes
 * Ensure that each of the attributes can be set and retrieved.
 * If all attributes can be set and retrieved attributes passes the test.
 */ 

$attributeError = null;
$obj = new ItemComments(); 
$atts = testAttributes($obj, $args1, $attributes); 
if(!$atts){
	$attributeError = "Attribute Error<br />";
	echo $attributeError;
}

/*
 * Operators
 * Test that all operators are functional.
 */

/*
 * Test set()
 */ 
$setError = null;
$obj = new ItemComments($args2); 
$obj->{ATTQ} = $obj->set();
if($obj->{ATTQ} == 1){
	$set1 = true; 
} else {
	$set1 = false;
	$setError = $setError . "Set() failed to create object.";
}

/*
 * Test set() again entering an already existing combination of itemID & commentID.
 */ 
$obj = new ItemComments($args2);
$obj->{ATTQ} = $obj->set();
if($obj->{ATTQ} == 1){
	$set2 = true;
} else {
	$set2 = false;
	$setError = $setError . "Set() failed to create object on duplicate entry.";
}

if($set1 && $set2){
	$set = true;
} else {
	$set = false;
}

if($setError){
	echo $setError;
}


/*
 * Test get()
 * An object is created with all attributes set to empty other than the objID which is set
 * to '1'.  The get() function now retrieves the other attributes from the database for that
 * objID. All the attributes can be compared.
 */

$getError = null;
$obj = new ItemComments($args3); 
$obj->get(); 
if(compareAttributes($obj, $args4, $testAtts)){
	$get1 = true;
} else {
	$get1 = false;
	$getError = "Unable to retrieve matching attributes.<br />";
}

/*
 * The get() function is now tested attempting to retrieve
 * an objID that doesn't exist.  If it can't do it, the
 * test is passed.
 */ 

$obj = new ItemComments($args5);

if(!$obj->get()){
	$get2 = true;
} else {
	$get2 = false;
	$getError = $getError . "get() non existant ID Failed.<br />";
}

/*
 * The get() function is now tested attempting to retrieve
 * an empty objID.  If it can't do it, the test is passed.
 */ 

$obj = new ItemComments();

if(!$obj->get()){
	$get3 = true;
} else {
	$get3 = false;
	$getError = $getError . "get() empty ID Failed.<br />";
}

if($get1 && $get2 && $get3){
	$get = true;
} else {
	$get = false;
	$getError = $getError . "Get() Failed.<br />";
}

if($getError){
	echo $getError;
}

/*
 * Test update()
 * The objects attributes are emptied except for the objID which
 * remains set to 1.  Each attribute is then altered and updated.
 * It is then compared with the expected arguments.  If all attributes
 * can be individually updated, then the update() test is passed.
 */ 

$updateError = null;
$startArgs = 6;
$update = true;
for($i = 0; $i < count($testAtts) - 1; $i++){
	$args = 'args' . ($i + $startArgs);
	$obj1 = new ItemComments($$args);
	$obj2 = new ItemComments($$args);
	$obj1->update();
	$obj1->get();
	
	if($obj1->{$testAtts[$i + 1]} != $obj2->{$testAtts[$i + 1]}){
		$update = false;
		$updateError = $updateError . 'Could not update user with ' . $testAtts[$i + 1] . '.<br />';
	}
}

if($updateError){
	echo $updateError;
}

/*
 * Test exists()
 */ 

$existsError = null;
$obj = new ItemComments();
$obj->{ATTQ} = 1;
if($obj->exists()){
	$exists1 = true;
} else {
	$exists1 = false;
	$existsError = $existsError . "exists() couldn\'t find existing ID'<br />";
}

$obj->{ATTQ} = 200;
if(!$obj->exists()){
	$exists2 = true;
} else {
	$exists2 = false;
	$existsError = $existsError . "exists() found non-existant ID'<br />";
}

$obj = new ItemComments();
if(!$obj->exists()){
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

if($existsError){
	echo $existsError;
}

/*
 * Test delete()
 */ 

$obj = new ItemComments();
$obj->{ATTQ} = 200;
if(!$obj->delete()){
	$delete1 = true;
} else {
	$delete1 = false;
	$deleteError = $deleteError . "delete() deleted non existant ID'<br />";
}

$obj = new ItemComments();
if(!$obj->delete()){
	$delete2 = true;
} else {
	$delete2 = false;
	$deleteError = $deleteError . "delete() deleted empty ID'<br />";
}

$obj->{ATTQ} = 1;
if($obj->delete()){
	if(!$obj->exists()){
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

if($deleteError){
	echo $deleteError;
}

/*
 * Test count()
 */ 

$countError = null;
$obj1 = new ItemComments($args2);
$obj2 = new ItemComments($args8);
$obj3 = new ItemComments($args9);
$obj1->{ATTQ} = $obj1->set();
$obj2->{ATTQ} = $obj2->set();
$obj3->{ATTQ} = $obj3->set();

if(($obj1->count() == 3) && ($obj2->count() == 3) && ($obj2->count() == 3)){
	$count = true;
} else {
	$count = false;
	$countError = 'count() failed to return correct value.';
}

if($countError){
	echo $countError;
}

echo 'ItemComments: <font color="';

if($atts && $set && $get && $update && $exists && $delete && $count){
	echo 'green">PASS';
} else {
	echo 'red">FAIL';
}

echo '</font><br />';



?>