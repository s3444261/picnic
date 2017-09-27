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
		`parentID` int(11) unsigned,
		`category` varchar(90) NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`categoryID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Items` (
		`itemID` bigint(11) NOT NULL AUTO_INCREMENT,
		`title` text NOT NULL,
		`description` text NOT NULL,
		`quantity` varchar(10) NOT NULL,
		`itemcondition` varchar(10) NOT NULL,
		`price` varchar(10) NOT NULL,
		`itemStatus` varchar(10) NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`itemID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `User_items` (
		`user_itemID` int(11) NOT NULL AUTO_INCREMENT,
		`userID` int(11) NOT NULL,
		`itemID` bigint(11) NOT NULL,
		PRIMARY KEY (`user_itemID`),
		KEY `FK_User_items_Users_idx` (`userID`),
		KEY `FK_User_items_Items_idx` (`itemID`),
		CONSTRAINT `FK_User_items_Users` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_User_items_Items` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `UQ_userID_itemID` UNIQUE (`userID`, `itemID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Category_items` (
		`category_itemID` int(11) NOT NULL AUTO_INCREMENT,
		`categoryID` int(11) NOT NULL,
		`itemID` bigint(11) NOT NULL,
		PRIMARY KEY (`category_itemID`),
		KEY `FK_Category_idx` (`categoryID`),
		KEY `FK_Item_idx` (`itemID`),
		CONSTRAINT `FK_Category` FOREIGN KEY (`categoryID`) REFERENCES `Categories` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_Item` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `UQ_categoryID_itemID` UNIQUE (`categoryID`, `itemID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Comments` (
		`commentID` int(11) NOT NULL AUTO_INCREMENT,
		`userID` int(11) NOT NULL,
		`comment` text NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT '1970-01-02 00:00:01',
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`commentID`),
		KEY `FK_Comments_User_idx` (`userID`),
		CONSTRAINT `FK_Comments_User` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Item_comments` (
		`item_commentID` int(11) NOT NULL AUTO_INCREMENT,
		`itemID` bigint(11) NOT NULL,
		`commentID` int(11) NOT NULL,
		PRIMARY KEY (`item_commentID`),
		KEY `FK_Item_comments_Item_idx` (`itemID`),
		KEY `FK_Item_comments_Comment_idx` (`commentID`),
		CONSTRAINT `FK_Item_comments_Item` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_Item_comments_Comment` FOREIGN KEY (`commentID`) REFERENCES `Comments` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `UQ_itemID_commentID` UNIQUE (`itemID`, `commentID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Notes` (
		`noteID` int(11) NOT NULL AUTO_INCREMENT,
		`note` text NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT '1970-01-02 00:00:01',
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

