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
include 'DatabaseGenerator.php';
DatabaseGenerator::Generate();
include 'admin/seedDB/seed.php';
?>

<h1>CreateDB</h1>
<div class="testHarness">
    <h2>Database Creation</h2>
</div>