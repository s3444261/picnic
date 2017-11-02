/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

DROP TABLE IF EXISTS `Item_notes`;
DROP TABLE IF EXISTS `Notes`;
DROP TABLE IF EXISTS `Item_comments`;
DROP TABLE IF EXISTS `Comments`;
DROP TABLE IF EXISTS `Category_items`;
DROP TABLE IF EXISTS `User_ratings`;
DROP TABLE IF EXISTS `User_items`;
DROP TABLE IF EXISTS `Items`;
DROP TABLE IF EXISTS `Users`;
DROP TABLE IF EXISTS `Categories`;

CREATE TABLE `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(45) NOT NULL,
  `email` varchar(90) NOT NULL,
  `password` varchar(45) NULL,
  `status` varchar(45) NOT NULL,
  `activate` varchar(32) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `user_UNIQUE` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Categories` (
		`categoryID` int(11) NOT NULL AUTO_INCREMENT,
		`parentID` int(11),
		`category` varchar(90) NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`categoryID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE Categories
		ADD CONSTRAINT FK_Categories_Categories
		FOREIGN KEY (`parentID`)
		REFERENCES `Categories` (`categoryID`)
		ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `Items` (
		`itemID` bigint(11) NOT NULL AUTO_INCREMENT,
  	`owningUserID` int(11) NOT NULL,
		`title` text NOT NULL,
		`description` text NOT NULL,
		`quantity` varchar(45) NOT NULL,
		`itemcondition` varchar(45) NOT NULL,
		`price` varchar(45) NOT NULL,
		`itemStatus` varchar(45) NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`itemID`),
  	CONSTRAINT `FK_Items_Users` FOREIGN KEY (`owningUserID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `User_items` (
		`user_itemID` int(11) NOT NULL AUTO_INCREMENT,
		`userID` int(11) NOT NULL,
		`itemID` bigint(11) NOT NULL,
    `relationship` varchar(45) NOT NULL,
    `userStatus` varchar(45) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`user_itemID`),
		KEY `FK_User_items_Users_idx` (`userID`),
		KEY `FK_User_items_Items_idx` (`itemID`),
		CONSTRAINT `FK_User_items_Users` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_User_items_Items` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `UQ_userID_itemID` UNIQUE (`userID`, `itemID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `User_ratings` (
		`user_ratingID` int(11) NOT NULL AUTO_INCREMENT,
    `itemID` bigint(11) NOT NULL,
    `sellrating` int(11),
		`userID` int(11),
		`buyrating` bigint(11) NOT NULL,
    `transaction` varchar(32) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`user_ratingID`),
		KEY `FK_User_ratings_Items_idx` (`itemID`),
		KEY `FK_User_ratings_Users_idx` (`userID`),
		CONSTRAINT `FK_User_ratings_Items` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_User_ratings_Users` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `UQ_itemID` UNIQUE (`itemID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Category_items` (
	`itemID` bigint(11) NOT NULL,
	`categoryID` int(11) NOT NULL,
	PRIMARY KEY (`itemID`,`categoryID`),
	UNIQUE KEY `UQ_categoryID_itemID` (`itemID`,`categoryID`),
	KEY `FK_Item_idx` (`itemID`),
	KEY `FK_Category_idx` (`categoryID`),
	CONSTRAINT `FK_Item` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_Category` FOREIGN KEY (`categoryID`) REFERENCES `Categories` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Comments` (
	`commentID` int(11) NOT NULL AUTO_INCREMENT,
	`toUserID` int(11) NOT NULL,
	`fromUserID` int(11) NOT NULL,
	`itemID` bigint(11) NOT NULL,
	`comment` text NOT NULL,
	`status` varchar(16) NOT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`commentID`),
	KEY `FK_Comments_ToUser_idx` (`toUserID`),
	KEY `FK_Comments_FromUser_idx` (`fromUserID`),
  CONSTRAINT `FK_Comments_Item` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_Comments_ToUser` FOREIGN KEY (`toUserID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_Comments_FromUser` FOREIGN KEY (`fromUserID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Notes` (
		`noteID` int(11) NOT NULL AUTO_INCREMENT,
		`note` text NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`noteID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Item_notes` (
		`item_noteID` int(11) NOT NULL AUTO_INCREMENT,
		`itemID` bigint(11) NOT NULL,
		`noteID` int(11) NOT NULL,
		PRIMARY KEY (`item_noteID`),
		KEY `FK_Item_notes_Item_idx` (`itemID`),
		KEY `FK_Item_notes_Note_idx` (`noteID`),
		CONSTRAINT `FK_Item_notes_Item` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_Item_notes_Note` FOREIGN KEY (`noteID`) REFERENCES `Notes` (`noteID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `UQ_itemID_noteID` UNIQUE (`itemID`, `noteID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
