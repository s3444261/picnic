<?php

/*
 * Test Data
 */
$itemNoteTest = false;
$atts = false;
$set = false;
$get = false;
$update = false; 
$exists = false;
$delete = false;
$countError = false;

$attributes = array(ATTP,
					ATTI,
					ATTD);

$testAtts = array(ATTP,
					ATTI,
					ATTD
);

$args1 = array();
$args1[ATTP] = 1;
$args1[ATTI] = 1;
$args1[ATTD] = 1;

$args2 = array();
$args2[ATTI] = 1;
$args2[ATTD] = 1;

$args3 = array();
$args3[ATTP] = 1;
$args3[ATTI] = '';
$args3[ATTD] = '';

$args4 = array();
$args4[ATTP] = 1;
$args4[ATTI] = 1;
$args4[ATTD] = 1;

$args5 = array();
$args5[ATTP] = 100;
$args5[ATTI] = '';
$args5[ATTD] = '';

$args6 = array();
$args6[ATTP] = 1;
$args6[ATTI] = 2;
$args6[ATTD] = '';

$args7 = array();
$args7[ATTP] = 1;
$args7[ATTI] = '';
$args7[ATTD] = 2;

$args8 = array();
$args8[ATTI] = 1;
$args8[ATTD] = 2;

$args9 = array();
$args9[ATTI] = 1;
$args9[ATTD] = 3;

/*
 * Seed Item and Note
 */
for($i = 0; $i < 3; $i++){
	$item = new Item();
	$item->set();
	$item->get();
	
	$note = new Note();
	$note->set();
	$note->get();
}


/*
 * Test Attributes
 * Ensure that each of the attributes can be set and retrieved.
 * If all attributes can be set and retrieved attributes passes the test.
 */ 

$attributeError = null;
$obj = new ItemNotes(); 
$atts = testAttributes($obj, $args1, $attributes); 
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
$setError = null;
$obj = new ItemNotes($args2); 
$obj->{ATTP} = $obj->set();
if($obj->{ATTP} == 1){
	$set1 = true; 
} else {
	$set1 = false;
	$setError = $setError . "Set() failed to create object.<br />";
}

/*
 * Test set() again entering an already existing combination of itemID & noteID.
 */ 
$obj = new ItemNotes($args2);
$obj->{ATTP} = $obj->set();
if($obj->{ATTP} == 1){
	$set2 = true;
} else {
	$set2 = false;
	$setError = $setError . "Set() failed to create object on duplicate entry.<br />";
}

if($set1 && $set2){
	$set = true;
} else {
	$set = false;
}

if($setError){
	$unitTestResults = $unitTestResults . $setError;
}


/*
 * Test get()
 * An object is created with all attributes set to empty other than the objID which is set
 * to '1'.  The get() function now retrieves the other attributes from the database for that
 * objID. All the attributes can be compared.
 */

$getError = null;
$obj = new ItemNotes($args3); 
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

$obj = new ItemNotes($args5);

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

$obj = new ItemNotes();

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
	$unitTestResults = $unitTestResults . $getError;
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
	$obj1 = new ItemNotes($$args);
	$obj2 = new ItemNotes($$args);
	$obj1->update();
	$obj1->get();
	
	if($obj1->{$testAtts[$i + 1]} != $obj2->{$testAtts[$i + 1]}){
		$update = false;
		$updateError = $updateError . 'Could not update user with ' . $testAtts[$i + 1] . '.<br />';
	}
}

if($updateError){
	$unitTestResults = $unitTestResults . $updateError;
}

/*
 * Test exists()
 */ 

$existsError = null;
$obj = new ItemNotes();
$obj->{ATTP} = 1;
if($obj->exists()){
	$exists1 = true;
} else {
	$exists1 = false;
	$existsError = $existsError . "exists() couldn\'t find existing ID'<br />";
}

$obj->{ATTP} = 200;
if(!$obj->exists()){
	$exists2 = true;
} else {
	$exists2 = false;
	$existsError = $existsError . "exists() found non-existant ID'<br />";
}

$obj = new ItemNotes();
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
	$unitTestResults = $unitTestResults . $existsError;
}

/*
 * Test delete()
 */ 

$obj = new ItemNotes();
$obj->{ATTP} = 200;
if(!$obj->delete()){
	$delete1 = true;
} else {
	$delete1 = false;
	$deleteError = $deleteError . "delete() deleted non existant ID'<br />";
}

$obj = new ItemNotes();
if(!$obj->delete()){
	$delete2 = true;
} else {
	$delete2 = false;
	$deleteError = $deleteError . "delete() deleted empty ID'<br />";
}

$obj->{ATTP} = 1;
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
	$unitTestResults = $unitTestResults . $deleteError;
}

/*
 * Test count()
 */ 

$countError = null;
$obj1 = new ItemNotes($args2);
$obj2 = new ItemNotes($args8);
$obj3 = new ItemNotes($args9);
$obj1->{ATTP} = $obj1->set();
$obj2->{ATTP} = $obj2->set();
$obj3->{ATTP} = $obj3->set();

if(($obj1->count() == 3) && ($obj2->count() == 3) && ($obj2->count() == 3)){
	$count = true;
} else {
	$count = false;
	$countError = 'count() failed to return correct value.';
}

if($countError){
	$unitTestResults = $unitTestResults . $countError;
}

$unitTestResults = $unitTestResults . 'ItemNotes: <font color="';

if($atts && $set && $get && $update && $exists && $delete && $count){
	$unitTestResults = $unitTestResults . 'green">PASS';
	$itemNoteTest = true;
} else {
	$unitTestResults = $unitTestResults . 'red">FAIL';
}

$unitTestResults = $unitTestResults . '</font><br />';



?>