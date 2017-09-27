<?php 
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

$humphree = false;

$humphreeStatus = '';

include __DIR__ . '/../createDB/DatabaseGenerator.php';

// System Tests
DatabaseGenerator::Generate();
include 'system/test.humphree.php';

if($humphree){
	$humphreeStatus = '<font color="green">PASS</font>';
} else {
	$humphreeStatus = '<font color="red">FAIL</font>';
}

?>

<h1>Test Harness</h1>
<div class="testHarness">
    <h2>Humphree Status</h2>
    <?php
    echo 'Humphree Status: ' . $humphreeStatus . '<br />';
    echo $hError;
    ?>

</div>


