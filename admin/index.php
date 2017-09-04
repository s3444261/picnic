<?php 
include_once 'config/config.php';

/*
 * TEST FUNCTIONS
 */

/*
 * The testAttribute function sets that value of each
 * attribute and checks that they have been set.
 */
function testAttributes($obj, $arr, $attr){
	$test = true;
	
	for($i = 1; $i < count($attr); $i++){
		 
		// Assign each attribute a value:
		  $obj->{$attr[$i]} = $arr[$attr[$i]];
		/*
		 * Uncomment this code if it is necessary to view
		 * the attributes that are being compared.
		*/
		
		/*echo '<font color="green">' . $obj->{$attr[$i]} .
			'</font> <font color="orange">' . $arr[$attr[$i]] .
			"</font><br />";  */
		
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

<h3>Create Database</h3>
<?php 
include 'admin/createdb/createdb.php';
?>

<h2>Unit Tests</h2>

<h3>Test Validation</h3>
<?php 
include 'unit/test.validation.php';
?>

<h3>Test Users</h3>
<?php 
include 'unit/test.users.php';
?>
</div>

<?php 
?>
