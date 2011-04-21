				CREATE TABLE IF NOT EXISTS `#__glossary` (
				`id` int(10) NOT NULL auto_increment,
				`tletter` char(1) NOT NULL default '',
				`tterm` varchar(40) NOT NULL default '',
				`tdefinition` text NOT NULL,
				`tname` varchar(20) NOT NULL default '',
				`tloca` varchar(60) default NULL,
				`tmail` varchar(60) default NULL,
				`tpage` varchar(150) default NULL,
				`tdate` datetime default NULL,
				`tcomment` text,
				`tedit` enum('y','n') NOT NULL default 'n',
				`teditdate` datetime default NULL,
				`published` tinyint(1) NOT NULL default 0,
				`catid` int(3) NOT NULL default '0',
				`checked_out` int(11) NOT NULL default 0,
				PRIMARY KEY  (`id`),
				UNIQUE KEY `term` (`tterm`, `catid`),
				FULLTEXT KEY `tdefinition` (`tdefinition`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `#__glossaries` (
					`id` int(10) NOT NULL auto_increment,
					`name` varchar(120) NOT NULL default '',
					`description` varchar(255) NOT NULL default '',
					`language` varchar(25) NOT NULL default '',
					`published` tinyint(1) UNSIGNED NOT NULL default 0,
					`isdefault` tinyint(1) UNSIGNED NOT NULL default 0,
					PRIMARY KEY  (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `#__glossary_aliases` (
				  `termalias` varchar(255) NOT NULL,
				  `tfirst` varchar(255) NOT NULL,
				  `termid` int(11) NOT NULL,
				  PRIMARY KEY  (`termid`,`termalias`),
				  KEY `termaliases` (`termalias`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `#__glossary_cache` (
				  `id` int(11) NOT NULL auto_increment,
				  `stamp` int(11) NOT NULL default '0',
				  `md5hash` char(32) NOT NULL,
				  `sha1hash` char(40) NOT NULL,
				  `fixup` mediumtext NOT NULL,
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `#__cmsapi_configurations` (
				  `component` varchar(100) NOT NULL,
				  `instance` int(10) NOT NULL default '0',
				  `configuration` mediumtext NOT NULL,
				  PRIMARY KEY  (`component`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
