<?php

/*
 * Test Data
 */
$itemTest = false;
$atts = false;
$set = false;
$get = false;
$update = false; 
$exists = false;
$delete = false;

$attributes = array(ATTI,
					ATTJ,
					ATTK,
					ATTL,
					ATTM,
					ATTN,
					ATTO,
					'created_at',
					'updated_at');

$testAtts = array(ATTI,
					ATTJ,
					ATTK,
					ATTL,
					ATTM,
					ATTN,
					ATTO
);

$args1 = array();
$args1[ATTI] = 1;
$args1[ATTJ] = 'text1';
$args1[ATTK] = 'text1';
$args1[ATTL] = 'text1';
$args1[ATTM] = 'text1';
$args1[ATTN] = 'text1';
$args1[ATTO] = 'text1';
$args1['created_at'] = '2015-02-13';
$args1['updated_at'] = '2015-02-13';

$args2 = array();
$args2[ATTJ] = 'text1';
$args2[ATTK] = 'text1';
$args2[ATTL] = 'text1';
$args2[ATTM] = 'text1';
$args2[ATTN] = 'text1';
$args2[ATTO] = 'text1';


$args3 = array();
$args3[ATTI] = 1;
$args3[ATTJ] = '';
$args3[ATTK] = '';
$args3[ATTL] = '';
$args3[ATTM] = '';
$args3[ATTN] = '';
$args3[ATTO] = '';

$args4 = array();
$args4[ATTI] = 1;
$args4[ATTJ] = 'text1';
$args4[ATTK] = 'text1';
$args4[ATTL] = 'text1';
$args4[ATTM] = 'text1';
$args4[ATTN] = 'text1';
$args4[ATTO] = 'text1';

$args5 = array();
$args5[ATTI] = 100;
$args5[ATTJ] = '';
$args5[ATTK] = '';
$args5[ATTL] = '';
$args5[ATTM] = '';
$args5[ATTN] = '';
$args5[ATTO] = '';

$args6 = array();
$args6[ATTI] = 1;
$args6[ATTJ] = 'text2';
$args6[ATTK] = '';
$args6[ATTL] = '';
$args6[ATTM] = '';
$args6[ATTN] = '';
$args6[ATTO] = '';

$args7 = array();
$args7[ATTI] = 1;
$args7[ATTJ] = '';
$args7[ATTK] = 'text2';
$args7[ATTL] = '';
$args7[ATTM] = '';
$args7[ATTN] = '';
$args7[ATTO] = '';

$args8 = array();
$args8[ATTI] = 1;
$args8[ATTJ] = '';
$args8[ATTK] = '';
$args8[ATTL] = 'text2';
$args8[ATTM] = '';
$args8[ATTN] = '';
$args8[ATTO] = '';

$args9 = array();
$args9[ATTI] = 1;
$args9[ATTJ] = '';
$args9[ATTK] = '';
$args9[ATTL] = '';
$args9[ATTM] = 'text2';
$args9[ATTN] = '';
$args9[ATTO] = '';

$args10 = array();
$args10[ATTI] = 1;
$args10[ATTJ] = '';
$args10[ATTK] = '';
$args10[ATTL] = '';
$args10[ATTM] = '';
$args10[ATTN] = 'text2';
$args10[ATTO] = '';

$args11 = array();
$args11[ATTI] = 1;
$args11[ATTJ] = '';
$args11[ATTK] = '';
$args11[ATTL] = '';
$args11[ATTM] = '';
$args11[ATTN] = '';
$args11[ATTO] = 'text2';

/*
 * Test Attributes
 * Ensure that each of the attributes can be set and retrieved.
 * If all attributes can be set and retrieved attributes passes the test.
 */ 

$attributeError = null;
$obj = new Item(); 
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
$obj = new Item($args2); 
$obj->{ATTI} = $obj->set();
if($obj->{ATTI} > 0){
	$set = true; 
} else {
	$set = false;
	$setError = $setError . "Set() failed to create object.<br />";
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
$obj = new Item($args3); 
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

$obj = new Item($args5);

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

$obj = new Item();

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
	$obj1 = new Item($$args);
	$obj2 = new Item($$args);
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
$obj = new Item();
$obj->{ATTI} = 1;
if($obj->exists()){
	$exists1 = true;
} else {
	$exists1 = false;
	$existsError = $existsError . "exists() couldn\'t find existing ID'<br />";
}

$obj->{ATTI} = 200;
if(!$obj->exists()){
	$exists2 = true;
} else {
	$exists2 = false;
	$existsError = $existsError . "exists() found non-existant ID'<br />";
}

$obj = new Item();
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

$obj = new Item();
$obj->{ATTI} = 200;
if(!$obj->delete()){
	$delete1 = true;
} else {
	$delete1 = false;
	$deleteError = $deleteError . "delete() deleted non existant ID'<br />";
}

$obj = new Item();
if(!$obj->delete()){
	$delete2 = true;
} else {
	$delete2 = false;
	$deleteError = $deleteError . "delete() deleted empty ID'<br />";
}

$obj->{ATTI} = 1;
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


$unitTestResults = $unitTestResults . 'Item: <font color="';

if($atts && $set && $get && $update && $exists && $delete){
	$unitTestResults = $unitTestResults . 'green">PASS';
	$itemTest = true;
} else {
	$unitTestResults = $unitTestResults . 'red">FAIL';
}

$unitTestResults = $unitTestResults . '</font><br />';



?>