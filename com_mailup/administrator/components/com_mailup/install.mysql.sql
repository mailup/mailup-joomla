--
-- Table structure for table `#__mailup_group`
--

CREATE TABLE IF NOT EXISTS `#__mailup_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(250) NOT NULL,
  `description` text,
  `listid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `visible` tinyint(4) NOT NULL default '0',
  `create_date` date NOT NULL,
  `update_date` date default NULL,
  PRIMARY KEY  (`id`),
  KEY `listid` (`listid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__mailup_groupsub`
--

CREATE TABLE IF NOT EXISTS `#__mailup_groupsub` (
  `groupid` int(11) unsigned NOT NULL,
  `subid` int(11) unsigned NOT NULL,
  `subdate` datetime default NULL,
  `unsubdate` datetime default NULL,
  `enabled` tinyint(1) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`groupid`,`subid`),
  KEY `listid` (`groupid`),
  KEY `subid` (`subid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__mailup_list`
--

CREATE TABLE IF NOT EXISTS `#__mailup_list` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(250) NOT NULL,
  `description` text,
  `listid` int(11) NOT NULL,
  `guid` varchar(150) NOT NULL,
  `alias` varchar(250) default NULL,
  `visible` tinyint(4) NOT NULL default '0',
  `create_date` date NOT NULL,
  `update_date` date default NULL,
  PRIMARY KEY  (`id`),
  KEY `listid` (`listid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__mailup_listsub`
--

CREATE TABLE IF NOT EXISTS `#__mailup_listsub` (
  `listid` int(11) unsigned NOT NULL,
  `subid` int(11) unsigned NOT NULL,
  `subdate` datetime default NULL,
  `unsubdate` datetime default NULL,
  `enabled` tinyint(1) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `last_update` int(11) default NULL,
  PRIMARY KEY  (`listid`,`subid`),
  KEY `listid` (`listid`),
  KEY `subid` (`subid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__mailup_subscriber`
--

CREATE TABLE IF NOT EXISTS `#__mailup_subscriber` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL default '0',
  `userid` int(11) NOT NULL,
  `code` varchar(250) NOT NULL,
  `joomla_user` tinyint(4) NOT NULL,
  `create_date` date NOT NULL,
  `last_update` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
