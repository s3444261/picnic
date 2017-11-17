/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - s3418650@student.rmit.edu.au
 */

DROP TABLE IF EXISTS `Item_notes`;
DROP TABLE IF EXISTS `Notes`;
DROP TABLE IF EXISTS `Comments`;
DROP TABLE IF EXISTS `Category_items`;
DROP TABLE IF EXISTS `User_ratings`;
DROP TABLE IF EXISTS `Item_matches`;
DROP TABLE IF EXISTS `Items`;
DROP TABLE IF EXISTS `Categories`;
DROP TABLE IF EXISTS `Users`;

CREATE TABLE `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(45) NOT NULL,
  `email` varchar(90) NOT NULL,
  `password` varchar(45) NULL,
  `status` varchar(45) NOT NULL,
  `activate` varchar(32) DEFAULT NULL,
	`blocked` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `user_UNIQUE` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Categories` (
		`categoryID` int(11) NOT NULL AUTO_INCREMENT,
		`parentID` int(11),
		`category` varchar(90) NOT NULL,
		`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`categoryID`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE Categories
		ADD CONSTRAINT FK_Categories_Categories
		FOREIGN KEY (`parentID`)
		REFERENCES `Categories` (`categoryID`)
		ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `Items` (
		`itemID` bigint(11) NOT NULL AUTO_INCREMENT,
	  `type` ENUM('ForSale', 'Wanted') NOT NULL,
  	`owningUserID` int(11) NOT NULL,
		`title` text NOT NULL,
		`description` text NOT NULL,
		`quantity` varchar(12) NOT NULL,
		`itemcondition` ENUM('New', 'Used') NOT NULL,
		`price` varchar(16) NOT NULL,
		`status` ENUM('Active', 'Deleted', 'Completed') NOT NULL DEFAULT 'Active',
		`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  CHECK (quantity > 0),
		PRIMARY KEY (`itemID`),
  	CONSTRAINT `FK_Items_Users` FOREIGN KEY (`owningUserID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;
		
ALTER TABLE Items ADD FULLTEXT(title, description);

CREATE TABLE `Item_matches` (
		`lhsItemID` bigint(11) NOT NULL,
		`rhsItemID` bigint(11) NOT NULL,
		`lhsStatus` ENUM('None', 'Accepted', 'Rejected') NOT NULL DEFAULT 'None',
	  `rhsStatus` ENUM('None', 'Accepted', 'Rejected') NOT NULL DEFAULT 'None',
		PRIMARY KEY (`lhsItemID`,	`rhsItemID`),
		KEY `FK_Item_matches_Items_idx` (`lhsItemID`),
		KEY `FK_Item_matches_Items_idx2` (`rhsItemID`),
		CHECK (lhsItemID < rhsItemID),
		CONSTRAINT `FK_Item_matches_Items` FOREIGN KEY (`lhsItemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `FK_Item_matches_Items2` FOREIGN KEY (`rhsItemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `User_ratings` (
    `sourceItemID` bigint(11) NOT NULL,
	  `targetItemID` bigint(11) NOT NULL,
    `rating` int(11) NULL,
	  `accessCode` varchar(32),
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `rating_left_at` timestamp NULL,
		PRIMARY KEY ( `sourceItemID`,  `targetItemID`),
	  CHECK (rating > 0),
	  CHECK (rating <= 5),
		KEY `FK_User_ratings_Items_idx` (`sourceItemID`),
	  KEY `FK_User_ratings_Items_idx2`(`targetItemID`),
		CONSTRAINT `FK_User_ratings_Item_matches` FOREIGN KEY (`sourceItemID`, `targetItemID`) REFERENCES `Item_matches` (`lhsItemID`, `rhsItemID`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Category_items` (
	`itemID` bigint(11) NOT NULL,
	`categoryID` int(11) NOT NULL,
	PRIMARY KEY (`itemID`,`categoryID`),
	UNIQUE KEY `UQ_categoryID_itemID` (`itemID`,`categoryID`),
	KEY `FK_Item_idx` (`itemID`),
	KEY `FK_Category_idx` (`categoryID`),
	CONSTRAINT `FK_Item` FOREIGN KEY (`itemID`) REFERENCES `Items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_Category` FOREIGN KEY (`categoryID`) REFERENCES `Categories` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
