<?php
/* Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

$file = fopen('admin/createDB/createDb.sql', 'r');
$query = fread($file, filesize('admin/createDB/createDb.sql'));
fclose($file);

$db = Picnic::getInstance();
$stmt = $db->prepare($query);
$stmt->execute();
unset($stmt);

?>
