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

$attributes = array(ATTF,
					ATTG,
					ATTH,
					'created_at',
					'updated_at');

$testAtts = array(ATTF,
					ATTG,
					ATTH
);

$args1 = array();
$args1[ATTF] = 1;
$args1[ATTG] = 1;
$args1[ATTH] = 'text1';
$args1['created_at'] = '2015-02-13';
$args1['updated_at'] = '2015-02-13';

$args2 = array();
$args2[ATTG] = 1;
$args2[ATTH] = 'text1';


$args3 = array();
$args3[ATTF] = 1;
$args3[ATTG] = '';
$args3[ATTH] = '';

$args4 = array();
$args4[ATTF] = 1;
$args4[ATTG] = 1;
$args4[ATTH] = 'text1';

$args5 = array();
$args5[ATTF] = 100;
$args5[ATTG] = '';
$args5[ATTH] = '';

$args6 = array();
$args6[ATTF] = 1;
$args6[ATTG] = 2;
$args6[ATTH] = '';

$args7 = array();
$args7[ATTF] = 1;
$args7[ATTG] = '';
$args7[ATTH] = 'text2';

$args8 = array();
$args8[ATTG] = 1;
$args8[ATTH] = 'text3';

/*
 * Seed Users
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

$user = new Users ();
$user->user = 'troy';
$user->email = 's3202752@student.rmit.edu.au';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

/*
 * Test Attributes
 * Ensure that each of the attributes can be set and retrieved.
 * If all attributes can be set and retrieved attributes passes the test.
 */ 

$attributeError = null;
$obj = new Comment(); 
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
$obj = new Comment($args2); 
$obj->{ATTF} = $obj->set();
if($obj->{ATTF} > 0){
	$set = true; 
} else {
	$set = false;
	$setError = $setError . "Set() failed to create object.";
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
$obj = new Comment($args3); 
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

$obj = new Comment($args5);

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

$obj = new Comment();

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
	$obj1 = new Comment($$args);
	$obj2 = new Comment($$args);
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
$obj = new Comment();
$obj->{ATTF} = 1;
if($obj->exists()){
	$exists1 = true;
} else {
	$exists1 = false;
	$existsError = $existsError . "exists() couldn\'t find existing ID'<br />";
}

$obj->{ATTF} = 200;
if(!$obj->exists()){
	$exists2 = true;
} else {
	$exists2 = false;
	$existsError = $existsError . "exists() found non-existant ID'<br />";
}

$obj = new Comment();
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

$obj = new Comment();
$obj->{ATTF} = 200;
if(!$obj->delete()){
	$delete1 = true;
} else {
	$delete1 = false;
	$deleteError = $deleteError . "delete() deleted non existant ID'<br />";
}

$obj = new Comment();
if(!$obj->delete()){
	$delete2 = true;
} else {
	$delete2 = false;
	$deleteError = $deleteError . "delete() deleted empty ID'<br />";
}

$obj->{ATTF} = 1;
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
$obj1 = new Comment($args2);
$obj2 = new Comment($args8);
$obj1->{ATTF} = $obj1->set();
$obj2->{ATTF} = $obj2->set();

if(($obj1->count() == 2) && ($obj2->count() == 2)){
	$count = true;
} else {
	$count = false;
	$countError = 'count() failed to return correct value.';
}

if($countError){
	echo $countError;
}

echo 'Comment: <font color="';

if($atts && $set && $get && $update && $exists && $delete && $count){
	echo 'green">PASS';
} else {
	echo 'red">FAIL';
}

echo '</font><br />';



?>