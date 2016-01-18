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
  `categoryID`  TINYINT(4)   NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100) NOT NULL DEFAULT '',
  `description` TEXT         NOT NULL,
  `total`       INT(11)      NOT NULL DEFAULT '0',
  `weight`      INT(11)      NOT NULL DEFAULT '1',
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY columnID (`categoryID`)
)
  ENGINE = MyISAM
  COMMENT = 'WordBook by hsalazar';

#
# Dumping data for table `wbcategories`
#

# --------------------------------------------------------

#
# Table structure for table `wbentries`
#

CREATE TABLE `wbentries` (
  `entryID`    INT(8)           NOT NULL AUTO_INCREMENT,
  `categoryID` TINYINT(4)       NOT NULL DEFAULT '0',
  `term`       VARCHAR(255)     NOT NULL DEFAULT '0',
  `init`       VARCHAR(1)       NOT NULL DEFAULT '0',
  `definition` TEXT             NOT NULL,
  `ref`        TEXT             NOT NULL,
  `url`        VARCHAR(255)     NOT NULL DEFAULT '0',
  `uid`        INT(6)                    DEFAULT '1',
  `submit`     INT(1)           NOT NULL DEFAULT '0',
  `datesub`    INT(11)          NOT NULL DEFAULT '1033141070',
  `counter`    INT(8) UNSIGNED  NOT NULL DEFAULT '0',
  `html`       INT(11)          NOT NULL DEFAULT '0',
  `smiley`     INT(11)          NOT NULL DEFAULT '0',
  `xcodes`     INT(11)          NOT NULL DEFAULT '0',
  `breaks`     INT(11)          NOT NULL DEFAULT '1',
  `block`      INT(11)          NOT NULL DEFAULT '0',
  `offline`    INT(11)          NOT NULL DEFAULT '0',
  `notifypub`  INT(11)          NOT NULL DEFAULT '0',
  `request`    INT(11)          NOT NULL DEFAULT '0',
  `comments`   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`entryID`),
  UNIQUE KEY entryID (`entryID`),
  FULLTEXT KEY definition (`definition`)
)
  ENGINE = MyISAM
  COMMENT = 'WordBook by hsalazar';

#
# Dumping data for table `wbentries`
#
