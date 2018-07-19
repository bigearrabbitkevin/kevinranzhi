#
# Structure for table "kr_act_plan"
#

CREATE TABLE IF NOT EXISTS `kr_act_plan` (
  `id` mediumint(9) NOT NULL,
  `PM` char(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `customer` int(11) NOT NULL DEFAULT '0',
  `desc` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `project` mediumint(9) NOT NULL DEFAULT '0',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` char(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `status` enum('doing','finished','suspend') NOT NULL DEFAULT 'doing',
  `whitelist` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`PM`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for table "kr_act_plangoods"
#

CREATE TABLE IF NOT EXISTS `kr_act_plangoods` (
  `id` mediumint(9) NOT NULL,
  `plan` int(11) NOT NULL DEFAULT '0',
  `desc` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `goods` mediumint(9) NOT NULL DEFAULT '0',
  `amount` int(7) NOT NULL DEFAULT '0',
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` enum('doing','finished','suspend') NOT NULL DEFAULT 'doing',
  PRIMARY KEY (`id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for table "kr_goods"
#

CREATE TABLE  IF NOT EXISTS `kr_goods` (
  `id` mediumint(9) NOT NULL,
  `code` char(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT '0',
  `TotalPrice` double NOT NULL DEFAULT '0',
  `times` int(11) NOT NULL DEFAULT '0',
  `desc` varchar(255) NOT NULL,
  `goodslink1` varchar(255) NOT NULL,
  `goodslink2` varchar(255) NOT NULL,
  `deleted` int(1) NOT NULL,
  `status` enum('doing','finished','suspend') NOT NULL DEFAULT 'doing',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`,`code`,`type`,`unit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
