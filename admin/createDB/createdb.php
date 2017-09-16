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

$query = "DROP TABLE IF EXISTS `Notes`";
$stmt = $db->prepare($query);
$stmt->execute();

$query = "DROP TABLE IF EXISTS `Comments`";
$stmt = $db->prepare($query);
$stmt->execute();

$query = "DROP TABLE IF EXISTS `Item_users`";
$stmt = $db->prepare($query);
$stmt->execute();

$query = "DROP TABLE IF EXISTS `Items`";
$stmt = $db->prepare($query);
$stmt->execute();

$query = "DROP TABLE IF EXISTS `Users`";
$stmt = $db->prepare($query);
$stmt->execute();

$query = "DROP TABLE IF EXISTS `Categories`";
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
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `user_UNIQUE` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

$stmt = $db->prepare($query);
$stmt->execute();
echo 'Users Table created.<br />';

$query = "CREATE TABLE `Categories` (
		`categoryID` int(11) NOT NULL AUTO_INCREMENT,
		`parentID` int(11) unsigned NOT NULL,
		`category` varchar(90) NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT '1970-01-02 00:00:01',
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`categoryID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$stmt = $db->prepare($query);
$stmt->execute();
echo 'Categories Table created.<br />';

$query = "CREATE TABLE `Items` (
		`itemID` int(11) NOT NULL AUTO_INCREMENT,
		`userID` int(11) NOT NULL,
		`categoryID` int(11) NOT NULL,
		`title` varchar(90) NOT NULL,
		`description` text NOT NULL,
		`quantity` varchar(10) NOT NULL,
		`itemcondition` varchar(10) NOT NULL,
		`price` varchar(10) NOT NULL,
		`status` varchar(10) NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT '1970-01-02 00:00:01',
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`itemID`),
		KEY `FK _Items_Users_idx` (`userID`),
		KEY `FK_Items_Categories_idx` (`categoryID`),
		CONSTRAINT `FK _Items_Users` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_Items_Categories` FOREIGN KEY (`categoryID`) REFERENCES `Categories` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$stmt = $db->prepare($query);
$stmt->execute();
echo 'Items Table created.<br />';

$query = "CREATE TABLE `Item_users` (
		`item_usersID` int(11) NOT NULL AUTO_INCREMENT,
		`itemID` int(11) NOT NULL,
		`userID` int(11) NOT NULL,
		PRIMARY KEY (`item_usersID`),
		KEY `FK_Item_users_Users_idx` (`userID`),
		KEY `FK_Item_users_Items_idx` (`itemID`),
		CONSTRAINT `FK_Item_users_Users` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_Item_users_Items` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `UQ_itemID_userID` UNIQUE (`itemID`, `userID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$stmt = $db->prepare($query);
$stmt->execute();
echo 'Item_users Table created.<br />';

$query = "CREATE TABLE `Comments` (
		`commentID` int(11) NOT NULL AUTO_INCREMENT,
		`itemID` int(11) NOT NULL,
		`toID` int(11) NOT NULL,
		`fromID` int(11) NOT NULL,
		`comment` text NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT '1970-01-02 00:00:01',
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`commentID`),
		KEY `FK _Comments_Items_idx` (`itemID`),
		KEY `FK_Comments_To_User_idx` (`toID`),
		KEY `FK_Comments_From_User_idx` (`fromID`),
		CONSTRAINT `FK _Comments_Items` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_Comments_To_User` FOREIGN KEY (`toID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_Comments_From_User` FOREIGN KEY (`fromID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$stmt = $db->prepare($query);
$stmt->execute();
echo 'Comments Table created.<br />';

$query = "CREATE TABLE `Notes` (
		`noteID` int(11) NOT NULL AUTO_INCREMENT,
		`itemID` int(11) NOT NULL,
		`note` text NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT '1970-01-02 00:00:01',
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`noteID`),
		KEY `FK _Notes_Items_idx` (`itemID`),
		CONSTRAINT `FK _Notes_Items` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$stmt = $db->prepare($query);
$stmt->execute();
echo 'Notes Table created.<br />';
		

?>
