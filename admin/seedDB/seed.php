<?php
/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

$user = new User ();
$user->user = 'grant';
$user->email = 'grant@kinkead.net';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$user = new User ();
$user->user = 'troy';
$user->email = 's3202752@student.rmit.edu.au';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$user = new User ();
$user->user = 'diane';
$user->email = 's3387562@student.rmit.edu.au';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$user = new User ();
$user->user = 'allen';
$user->email = 's3492264@student.rmit.edu.au';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$user = new User ();
$user->user = 'edwan';
$user->email = 'edwanhp@gmail.com';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$category = new Category();
$category->parentID = 0;
$category->category = 'Category';
$category->set();

?>





