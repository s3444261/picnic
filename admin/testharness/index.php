<?php 
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

include_once 'config/config.php';

define('ATTA', 'categoryID');
define('ATTB', 'parentID');
define('ATTC', 'category');
define('ATTD', 'noteID');
define('ATTE', 'note');
define('ATTF', 'commentID');
define('ATTG', 'userID');
define('ATTH', 'comment');
define('ATTI', 'itemID');
define('ATTJ', 'title');
define('ATTK', 'description');
define('ATTL', 'quantity');
define('ATTM', 'itemcondition');
define('ATTN', 'price');
define('ATTO', 'status');
define('ATTP', 'item_noteID');
define('ATTQ', 'item_commentID');
define('ATTR', 'category_itemID');
define('ATTS', 'user_itemID');


/* 
 * TEST FUNCTIONS
 */

/*
 * The testAttribute function sets that value of each
 * attribute and checks that they have been set.
 */
function testAttributes($obj, $arr, $attr){
	$test = true;
	for($i = 0; $i < count($attr); $i++){

		// Assign each attribute a value:
		$obj->{$attr[$i]} = $arr[$attr[$i]];
		/*
		 * Uncomment this code if it is necessary to view
		 * the attributes that are being compared.
		*/
		/*
		 echo '<font color="green">' . $obj->{$attr[$i]} .
			'</font> <font color="orange">' . $arr[$attr[$i]] .
			"</font><br />";
		*/
		if($obj->{$attr[$i]} != $arr[$attr[$i]]){
			$test = false;
		}
	}
	return $test;
}

/*
 * The compareAttribute function checks to see if the
 * value of the objects attribute matches the value
 * of the array except for the last two values, being
 * created_at and updated_at.
 */
function compareAttributes($obj, $arr, $attr){
	$test = true;
	for($i = 0; $i < (count($attr) - 2); $i++){
		/*
		 * Uncomment this code if it is necessary to view
		 * the attributes that are being compared.
		 * echo '<font color="green">' . $obj->{$attr[$i]} .
			'</font> <font color="orange">' . $arr[$attr[$i]] .
			"</font><br />";
		 */

		if($obj->{$attr[$i]} != $arr[$attr[$i]]){
			$test = false;
		}
	}
	return $test;
}

$system = false;
$integration = false;
$unit = false;

$applicationStatus = '';
$systemTests = '';
$integrationTests = '';
$unitTests = '';
$systemTestResults = '';
$integrationTestResults = '';
$unitTestResults = '';

include 'admin/createDB/createdb.php';
include 'unit/test.users.php';
include 'admin/createDB/createdb.php';
include 'unit/test.note.php';
include 'admin/createDB/createdb.php';
include 'unit/test.comment.php';
include 'admin/createDB/createdb.php';
include 'unit/test.item.php';
include 'admin/createDB/createdb.php';
include 'unit/test.itemnotes.php';
include 'admin/createDB/createdb.php';
include 'unit/test.itemcomments.php';
include 'admin/createDB/createdb.php';
include 'unit/test.categoryitems.php';
include 'admin/createDB/createdb.php';
include 'unit/test.useritems.php';
include 'admin/createDB/createdb.php';
include 'unit/test.validation.php';
include 'admin/createDB/createdb.php';
include 'admin/seedDB/seed.php';

if($userTest && $noteTest && $commentTest && $itemTest && $itemNoteTest && $itemCommentTest && $categoryItemsTest && $userItemsTest && $validationTest){
	$unit = true;
}

if($system && $integration && $unit){
	$applicationStatus = '<font color="green">PASS</font>';
} else {
	$applicationStatus = '<font color="red">FAIL</font>';
}
if($system){
	$systemTests = '<font color="green">PASS</font>';
} else {
	$systemTests = '<font color="red">FAIL</font>';
}
if($integration){
	$integrationTests = '<font color="green">PASS</font>';
} else {
	$integrationTests = '<font color="red">FAIL</font>';
}
if($unit){
	$unitTests = '<font color="green">PASS</font>';
} else {
	$unitTests = '<font color="red">FAIL</font>';
}
?>

<h1>Test Harness</h1>
<div class="testHarness">
<h2>Application Status</h2>
<?php 
echo 'Application Status: ' . $applicationStatus;
?>
<h2>Application Summary</h2>
<?php 
echo 'System Tests: ' . $systemTests . '<br />';
echo 'Integration Tests: ' . $integrationTests . '<br />';
echo 'Unit Tests: ' . $unitTests . '<br />';
?>
<h2>System Test Results</h2>
<?php 
echo $systemTestResults;
?>
<h2>Integration Test Results</h2>
<?php 
echo $integrationTestResults;
?>
<h2>Unit Test Results</h2>
<?php 
echo $unitTestResults;
?>

</div>

<?php 
?>
