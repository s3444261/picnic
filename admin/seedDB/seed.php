<?php
/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

$user = new Users ();
$user->user = 'grant';
$user->email = 'grant@kinkead.net';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$user = new Users ();
$user->user = 'troy';
$user->email = 's3202752@student.rmit.edu.au';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$user = new Users ();
$user->user = 'diane';
$user->email = 's3387562@student.rmit.edu.au';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$user = new Users ();
$user->user = 'allen';
$user->email = 's3492264@student.rmit.edu.au';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

$user = new Users ();
$user->user = 'edwan';
$user->email = 'edwanhp@gmail.com';
$user->password = 'TestTest88';
$user->set ();
$user->get ();
$user->activate ();
$user->status = 'admin';
$user->password = '';  // So the current password is not updated.
$user->update ();

?>





