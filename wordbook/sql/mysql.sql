# phpMyAdmin SQL Dump
# version 2.5.3
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Dec 12, 2003 at 06:03 AM
# Server version: 3.23.56
# PHP Version: 4.3.3
# 
# Database : `xoopsv3a`
# 

# --------------------------------------------------------

#
# Table structure for table `wbcategories`
#

CREATE TABLE `wbcategories` (	
	`categoryID` tinyint(4) NOT NULL auto_increment,
	`name` varchar(100) NOT NULL default '',
	`description` text NOT NULL,
	`total` int(11) NOT NULL default '0',
	`weight` int(11) NOT NULL default '1',
	PRIMARY KEY  (`categoryID`),
	UNIQUE KEY columnID (`categoryID`)
) ENGINE=MyISAM COMMENT='WordBook by hsalazar';	

#
# Dumping data for table `wbcategories`
#

# --------------------------------------------------------

#
# Table structure for table `wbentries`
#

CREATE TABLE `wbentries` (	
	`entryID` int(8) NOT NULL auto_increment,
	`categoryID` tinyint(4) NOT NULL default '0',
	`term` varchar(255) NOT NULL default '0',
	`init` varchar(1) NOT NULL default '0',
	`definition` text NOT NULL,
	`ref` text NOT NULL,
	`url` varchar(255) NOT NULL default '0',
	`uid` int(6) default '1',
	`submit` int(1) NOT NULL default '0',
	`datesub` int(11) NOT NULL default '1033141070',
	`counter` int(8) unsigned NOT NULL default '0',
	`html` int(11) NOT NULL default '0',
	`smiley` int(11) NOT NULL default '0',
	`xcodes` int(11) NOT NULL default '0',
	`breaks` int(11) NOT NULL default '1',
	`block` int(11) NOT NULL default '0',
	`offline` int(11) NOT NULL default '0',
	`notifypub` int(11) NOT NULL default '0',
	`request` int(11) NOT NULL default '0',
	`comments` int(11) unsigned NOT NULL default '0',
	PRIMARY KEY  (`entryID`),
	UNIQUE KEY entryID (`entryID`),
	FULLTEXT KEY definition (`definition`)
) ENGINE=MyISAM COMMENT='WordBook by hsalazar';	

#
# Dumping data for table `wbentries`
#