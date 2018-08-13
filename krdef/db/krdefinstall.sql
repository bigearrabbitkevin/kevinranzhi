
-- ----------------------------
-- Table structure for eps_book
-- ----------------------------
CREATE TABLE IF NOT EXISTS `eps_book` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `articleID` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `keywords` varchar(150) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `type` enum('book','chapter','article') NOT NULL,
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(60) NOT NULL,
  `editor` varchar(60) NOT NULL,
  `addedDate` datetime NOT NULL,
  `editedDate` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'normal',
  `views` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL,
  `lang` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `lang` (`lang`),
  KEY `order` (`order`),
  KEY `parent` (`parent`),
  KEY `status` (`status`),
  KEY `addedDate` (`addedDate`),
  KEY `path` (`path`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for kv_remote
-- ----------------------------
CREATE TABLE IF NOT EXISTS `kv_remote` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `type1` varchar(10) DEFAULT '',
  `type2` varchar(10) DEFAULT '',
  `realname` varchar(20) DEFAULT '',
  `macname` varchar(30) DEFAULT '',
  `ip` varchar(30) DEFAULT '',
  `mactype` varchar(10) DEFAULT '',
  `macaddress` varchar(30) DEFAULT '',
  `order` tinyint(1) DEFAULT '0',
  `status` enum('off','on','wakeup') DEFAULT 'off',
  `deleted` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
