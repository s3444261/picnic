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

define('ATTA', 'itemID');
define('ATTB', 'userID');
define('ATTC', 'categoryID');
define('ATTD', 'title');
define('ATTE', 'description');
define('ATTF', 'quantity');
define('ATTG', 'condition');
define('ATTH', 'price');
define('ATTI', 'status');
define('ATTJ', 'title');


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

?>

<h1>Test Harness</h1>
<div class="testHarness">
<h2>Create Database</h2>
<?php 
include 'admin/createDB/createdb.php';
?>
<h2>Unit Tests</h2>

<h3>Test Users</h3>
<?php 
include 'unit/test.users.php';
?>
<h3>Test Item</h3>
<?php 
//include 'unit/test.item.php';
?>
<h3>Test Validation</h3>
<?php 
include 'unit/test.validation.php';
?>
<h2>Create &amp; Seed Database</h2>
<?php 
include 'admin/createDB/createdb.php';
include 'admin/seedDB/seed.php';

?>
</div>

<?php 
?>
