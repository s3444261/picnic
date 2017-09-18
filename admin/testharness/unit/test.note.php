<?php

/*
 * Test Data
 */
$noteTest = false;
$atts = false;
$set = false;
$get = false;
$update = false; 
$exists = false;
$delete = false;
$countError = false;

$attributes = array(ATTD,
					ATTE,
					'created_at',
					'updated_at');

$testAtts = array(ATTD,
					ATTE
);

$args1 = array();
$args1[ATTD] = 1;
$args1[ATTE] = 'text1';
$args1['created_at'] = '2015-02-13';
$args1['updated_at'] = '2015-02-13';

$args2 = array();
$args2[ATTD] = 1;
$args2[ATTE] = 'text1';

$args3 = array();
$args3[ATTD] = 1;
$args3[ATTE] = '';

$args4 = array();
$args4[ATTD] = 1;
$args4[ATTE] = 'text1';

$args5 = array();
$args5[ATTD] = 100;
$args5[ATTE] = '';

$args6 = array();
$args6[ATTD] = 1;
$args6[ATTE] = 'text1';

$args7 = array();
$args7[ATTD] = 1;
$args7[ATTE] = 'text2';

/*
 * Test Attributes
 * Ensure that each of the attributes can be set and retrieved.
 * If all attributes can be set and retrieved attributes passes the test.
 */ 

$attributeError = null;
$obj = new Note(); 
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
$obj = new Note($args2); 
$obj->{ATTD} = $obj->set();
if($obj->{ATTD} > 0){
	$set = true; 
} else {
	$set = false;
	$setError = $setError . "Set() failed to create object.";
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
$obj = new Note($args3); 
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

$obj = new Note($args5);

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

$obj = new Note();

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
	$obj1 = new Note($$args);
	$obj2 = new Note($$args);
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
$obj = new Note();
$obj->{ATTD} = 1;
if($obj->exists()){
	$exists1 = true;
} else {
	$exists1 = false;
	$existsError = $existsError . "exists() couldn\'t find existing ID'<br />";
}

$obj->{ATTD} = 200;
if(!$obj->exists()){
	$exists2 = true;
} else {
	$exists2 = false;
	$existsError = $existsError . "exists() found non-existant ID'<br />";
}

$obj = new Note();
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

$obj = new Note();
$obj->{ATTD} = 200;
if(!$obj->delete()){
	$delete1 = true;
} else {
	$delete1 = false;
	$deleteError = $deleteError . "delete() deleted non existant ID'<br />";
}

$obj = new Note();
if(!$obj->delete()){
	$delete2 = true;
} else {
	$delete2 = false;
	$deleteError = $deleteError . "delete() deleted empty ID'<br />";
}

$obj->{ATTD} = 1;
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

$unitTestResults = $unitTestResults . 'Note: <font color="';

if($atts && $set && $get && $update && $exists && $delete){
	$unitTestResults = $unitTestResults . 'green">PASS';
	$noteTest = true;
} else {
	$unitTestResults = $unitTestResults . 'red">FAIL';
}

$unitTestResults = $unitTestResults . '</font><br />';



?>