-- 2018-7-12

-- ----------------------------
-- Table structure for zt_dept
-- ----------------------------
CREATE TABLE IF NOT EXISTS  `zt_dept` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(32) NOT NULL DEFAULT '',
  `name` char(60) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `position` char(30) NOT NULL DEFAULT '',
  `function` char(255) NOT NULL DEFAULT '',
  `manager` char(30) NOT NULL DEFAULT '',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `email` char(90) NOT NULL DEFAULT '',
  `code` varchar(45) NOT NULL,
  `group` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `dept` (`parent`,`path`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for kv_ldapuser
-- ----------------------------
CREATE TABLE IF NOT EXISTS `kv_ldapuser` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(100) DEFAULT NULL,
  `remote` char(30) DEFAULT NULL,
  `local` char(30) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `kv_lapuser_domain_remote` (`domain`,`remote`),
  KEY `kv_lapuser_remote` (`remote`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

