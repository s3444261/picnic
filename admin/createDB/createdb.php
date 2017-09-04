<?php
/* Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

// Call a singleton instance of the Driver class.

$driver = Driver::getInstance ();

$db = Picnic::getInstance();

$query = "DROP TABLE IF EXISTS `Users`";
$stmt = $db->prepare($query);
$stmt->execute();

$query = "CREATE TABLE `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(45) NOT NULL,
  `email` varchar(90) NOT NULL,
  `password` varchar(45) NULL,
  `status` varchar(45) NOT NULL,
  `activate` varchar(32) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '1970-01-02 00:00:01',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

$stmt = $db->prepare($query);
$stmt->execute();
echo 'Users Table created.<br />';



?>
