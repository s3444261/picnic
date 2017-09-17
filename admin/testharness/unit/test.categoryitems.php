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

$attributes = array(ATTR,
					ATTA,
					ATTI);

$testAtts = array(ATTR,
					ATTA,
					ATTI
);

$args1 = array();
$args1[ATTR] = 2;
$args1[ATTA] = 1;
$args1[ATTI] = 1;

$args2 = array();
$args2[ATTA] = 1;
$args2[ATTI] = 1;

$args3 = array();
$args3[ATTR] = 2;
$args3[ATTA] = '';
$args3[ATTI] = '';

$args4 = array();
$args4[ATTR] = 2;
$args4[ATTA] = 1;
$args4[ATTI] = 1;

$args5 = array();
$args5[ATTR] = 100;
$args5[ATTA] = '';
$args5[ATTI] = '';

$args6 = array();
$args6[ATTR] = 2;
$args6[ATTA] = 2;
$args6[ATTI] = '';

$args7 = array();
$args7[ATTR] = 2;
$args7[ATTA] = '';
$args7[ATTI] = 2;

$args8 = array();
$args8[ATTA] = 1;
$args8[ATTI] = 2;

$args9 = array();
$args9[ATTA] = 1;
$args9[ATTI] = 3;

/*
 * Seed Category and Item
 */
$category = new Category();
$category->category = 'Category';
$category->parentID = 0;
$category->set();

$category = new Category();
$category->parentID = 1;
$category->category = 'text1';
$category->set();

for($i = 0; $i < 3; $i++){
	$item = new Item();
	$item->set();
	$item->get();
}


/*
 * Test Attributes
 * Ensure that each of the attributes can be set and retrieved.
 * If all attributes can be set and retrieved attributes passes the test.
 */ 

$attributeError = null;
$obj = new CategoryItems(); 
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
$obj = new CategoryItems($args2); 
$obj->{ATTR} = $obj->set();
if($obj->{ATTR} == 1){
	$set1 = true; 
} else {
	$set1 = false;
	$setError = $setError . "Set() failed to create object.";
}

/*
 * Test set() again entering an already existing combination of itemID & commentID.
 */ 
$obj = new CategoryItems($args2);
$obj->{ATTR} = $obj->set();
if($obj->{ATTR} == 1){
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
$obj = new CategoryItems($args3); 
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

$obj = new CategoryItems($args5);

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

$obj = new CategoryItems();

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
	$obj1 = new CategoryItems($$args);
	$obj2 = new CategoryItems($$args);
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
$obj = new CategoryItems();
$obj->{ATTR} = 1;
if($obj->exists()){
	$exists1 = true;
} else {
	$exists1 = false;
	$existsError = $existsError . "exists() couldn\'t find existing ID'<br />";
}

$obj->{ATTR} = 200;
if(!$obj->exists()){
	$exists2 = true;
} else {
	$exists2 = false;
	$existsError = $existsError . "exists() found non-existant ID'<br />";
}

$obj = new CategoryItems();
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

$obj = new CategoryItems();
$obj->{ATTR} = 200;
if(!$obj->delete()){
	$delete1 = true;
} else {
	$delete1 = false;
	$deleteError = $deleteError . "delete() deleted non existant ID'<br />";
}

$obj = new CategoryItems();
if(!$obj->delete()){
	$delete2 = true;
} else {
	$delete2 = false;
	$deleteError = $deleteError . "delete() deleted empty ID'<br />";
}

$obj->{ATTR} = 1;
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
$obj1 = new CategoryItems($args2);
$obj2 = new CategoryItems($args8);
$obj3 = new CategoryItems($args9);
$obj1->{ATTR} = $obj1->set();
$obj2->{ATTR} = $obj2->set();
$obj3->{ATTR} = $obj3->set();

if(($obj1->count() == 3) && ($obj2->count() == 3) && ($obj2->count() == 3)){
	$count = true;
} else {
	$count = false;
	$countError = 'count() failed to return correct value.';
}

if($countError){
	echo $countError;
}

echo 'CategoryItems: <font color="';

if($atts && $set && $get && $update && $exists && $delete && $count){
	echo 'green">PASS';
} else {
	echo 'red">FAIL';
}

echo '</font><br />';



?>