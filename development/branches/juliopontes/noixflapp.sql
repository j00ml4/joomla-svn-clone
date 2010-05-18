# SQL Manager 2005 for MySQL 3.6.5.1
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : joomla_16


SET FOREIGN_KEY_CHECKS=0;

#
# Structure for the `bak_assets` table : 
#

DROP TABLE IF EXISTS `bak_assets`;

CREATE TABLE `bak_assets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set parent.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `level` int(10) unsigned NOT NULL COMMENT 'The cached level in the nested tree.',
  `name` varchar(50) NOT NULL COMMENT 'The unique name for the asset.\n',
  `title` varchar(100) NOT NULL COMMENT 'The descriptive title for the asset.',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_asset_name` (`name`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_banner_clients` table : 
#

DROP TABLE IF EXISTS `bak_banner_clients`;

CREATE TABLE `bak_banner_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `extrainfo` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `metakey` text NOT NULL,
  `own_prefix` tinyint(4) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_banner_tracks` table : 
#

DROP TABLE IF EXISTS `bak_banner_tracks`;

CREATE TABLE `bak_banner_tracks` (
  `track_date` date NOT NULL,
  `track_type` int(10) unsigned NOT NULL,
  `banner_id` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`track_date`,`track_type`,`banner_id`),
  KEY `idx_track_date` (`track_date`),
  KEY `idx_track_type` (`track_type`),
  KEY `idx_banner_id` (`banner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_banners` table : 
#

DROP TABLE IF EXISTS `bak_banners`;

CREATE TABLE `bak_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `imptotal` int(11) NOT NULL DEFAULT '0',
  `impmade` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `clickurl` varchar(200) NOT NULL DEFAULT '',
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `params` text NOT NULL,
  `own_prefix` tinyint(1) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reset` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`),
  KEY `idx_banner_catid` (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_categories` table : 
#

DROP TABLE IF EXISTS `bak_categories`;

CREATE TABLE `bak_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `extension` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(5120) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` varchar(2048) NOT NULL DEFAULT '',
  `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
  `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
  `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `language` varchar(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`extension`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_path` (`path`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_contact_details` table : 
#

DROP TABLE IF EXISTS `bak_contact_details`;

CREATE TABLE `bak_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `con_position` varchar(255) DEFAULT NULL,
  `address` text,
  `suburb` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `misc` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `imagepos` varchar(20) DEFAULT NULL,
  `email_to` varchar(255) DEFAULT NULL,
  `default_con` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `webpage` varchar(255) NOT NULL DEFAULT '',
  `sortname1` varchar(255) NOT NULL,
  `sortname2` varchar(255) NOT NULL,
  `sortname3` varchar(255) NOT NULL,
  `language` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_content` table : 
#

DROP TABLE IF EXISTS `bak_content`;

CREATE TABLE `bak_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `title_alias` varchar(255) NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `sectionid` int(10) unsigned NOT NULL DEFAULT '0',
  `mask` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` varchar(5120) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `language` varchar(10) NOT NULL COMMENT 'The language code for the article.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_content_frontpage` table : 
#

DROP TABLE IF EXISTS `bak_content_frontpage`;

CREATE TABLE `bak_content_frontpage` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_content_rating` table : 
#

DROP TABLE IF EXISTS `bak_content_rating`;

CREATE TABLE `bak_content_rating` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `rating_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_count` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_core_log_searches` table : 
#

DROP TABLE IF EXISTS `bak_core_log_searches`;

CREATE TABLE `bak_core_log_searches` (
  `search_term` varchar(128) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_extensions` table : 
#

DROP TABLE IF EXISTS `bak_extensions`;

CREATE TABLE `bak_extensions` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `element` varchar(100) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `client_id` tinyint(3) NOT NULL,
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `protected` tinyint(3) NOT NULL DEFAULT '0',
  `manifest_cache` text NOT NULL,
  `params` text NOT NULL,
  `custom_data` text NOT NULL,
  `system_data` text NOT NULL,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) DEFAULT '0',
  `state` int(11) DEFAULT '0',
  PRIMARY KEY (`extension_id`),
  KEY `element_clientid` (`element`,`client_id`),
  KEY `element_folder_clientid` (`element`,`folder`,`client_id`),
  KEY `extension` (`type`,`element`,`folder`,`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_languages` table : 
#

DROP TABLE IF EXISTS `bak_languages`;

CREATE TABLE `bak_languages` (
  `lang_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` char(7) NOT NULL,
  `title` varchar(50) NOT NULL,
  `title_native` varchar(50) NOT NULL,
  `description` varchar(512) NOT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_menu` table : 
#

DROP TABLE IF EXISTS `bak_menu`;

CREATE TABLE `bak_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL COMMENT 'The type of menu this item belongs to. FK to #__menu_types.menutype',
  `title` varchar(255) NOT NULL COMMENT 'The display title of the menu item.',
  `alias` varchar(255) NOT NULL COMMENT 'The SEF alias of the menu item.',
  `note` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.',
  `link` varchar(1024) NOT NULL COMMENT 'The actually link the menu item refers to.',
  `type` varchar(16) NOT NULL COMMENT 'The type of link: Component, URL, Alias, Separator',
  `published` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The published state of the menu link.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'The parent menu item in the menu tree.',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The relative level in the tree.',
  `component_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__components.id',
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT 'The relative ordering of the menu item in the tree.',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__users.id',
  `checked_out_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time the menu item was checked out.',
  `browserNav` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The click behaviour of the link.',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'The access level required to view the menu item.',
  `img` varchar(255) NOT NULL COMMENT 'The image of the menu item.',
  `template_style_id` int(10) unsigned NOT NULL DEFAULT '0',
  `params` varchar(10240) NOT NULL COMMENT 'JSON encoded data for the menu item.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `home` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicates if this menu item is the home or default page.',
  PRIMARY KEY (`id`),
  KEY `idx_componentid` (`component_id`,`menutype`,`published`,`access`),
  KEY `idx_menutype` (`menutype`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_path` (`path`(333))
) ENGINE=MyISAM AUTO_INCREMENT=410 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_menu_types` table : 
#

DROP TABLE IF EXISTS `bak_menu_types`;

CREATE TABLE `bak_menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL,
  `title` varchar(48) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_messages` table : 
#

DROP TABLE IF EXISTS `bak_messages`;

CREATE TABLE `bak_messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_from` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id_to` int(10) unsigned NOT NULL DEFAULT '0',
  `folder_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `useridto_state` (`user_id_to`,`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_messages_cfg` table : 
#

DROP TABLE IF EXISTS `bak_messages_cfg`;

CREATE TABLE `bak_messages_cfg` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cfg_name` varchar(100) NOT NULL DEFAULT '',
  `cfg_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `idx_user_var_name` (`user_id`,`cfg_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_modules` table : 
#

DROP TABLE IF EXISTS `bak_modules`;

CREATE TABLE `bak_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(50) DEFAULT NULL,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` varchar(5120) NOT NULL DEFAULT '',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `language` varchar(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_modules_menu` table : 
#

DROP TABLE IF EXISTS `bak_modules_menu`;

CREATE TABLE `bak_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_newsfeeds` table : 
#

DROP TABLE IF EXISTS `bak_newsfeeds`;

CREATE TABLE `bak_newsfeeds` (
  `catid` int(11) NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `alias` varchar(100) NOT NULL DEFAULT '',
  `link` varchar(200) NOT NULL DEFAULT '',
  `filename` varchar(200) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `numarticles` int(10) unsigned NOT NULL DEFAULT '1',
  `cache_time` int(10) unsigned NOT NULL DEFAULT '3600',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rtl` tinyint(4) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`),
  KEY `idx_access` (`access`),
  KEY `catid` (`catid`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_redirect_links` table : 
#

DROP TABLE IF EXISTS `bak_redirect_links`;

CREATE TABLE `bak_redirect_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_url` varchar(150) NOT NULL,
  `new_url` varchar(150) NOT NULL,
  `referer` varchar(150) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `created_date` int(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_link_old` (`old_url`),
  KEY `idx_link_updated` (`updated_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_schemas` table : 
#

DROP TABLE IF EXISTS `bak_schemas`;

CREATE TABLE `bak_schemas` (
  `extensionid` int(11) NOT NULL,
  `versionid` varchar(20) NOT NULL,
  PRIMARY KEY (`extensionid`,`versionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_session` table : 
#

DROP TABLE IF EXISTS `bak_session`;

CREATE TABLE `bak_session` (
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `client_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `guest` tinyint(4) unsigned DEFAULT '1',
  `time` varchar(14) DEFAULT '',
  `data` varchar(20480) DEFAULT NULL,
  `userid` int(11) DEFAULT '0',
  `username` varchar(150) DEFAULT '',
  `usertype` varchar(50) DEFAULT '',
  PRIMARY KEY (`session_id`),
  KEY `whosonline` (`guest`,`usertype`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_social_blocked_ips` table : 
#

DROP TABLE IF EXISTS `bak_social_blocked_ips`;

CREATE TABLE `bak_social_blocked_ips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_ip` int(11) NOT NULL,
  `end_ip` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`start_ip`,`end_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_social_blocked_users` table : 
#

DROP TABLE IF EXISTS `bak_social_blocked_users`;

CREATE TABLE `bak_social_blocked_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_social_comments` table : 
#

DROP TABLE IF EXISTS `bak_social_comments`;

CREATE TABLE `bak_social_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) unsigned NOT NULL COMMENT 'The comments thread id - Foreign Key',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Map to the user id',
  `context` varchar(50) NOT NULL COMMENT 'The context of the comment',
  `context_id` int(11) NOT NULL DEFAULT '0' COMMENT 'The id of the item in context',
  `trackback` int(2) NOT NULL DEFAULT '0' COMMENT 'Is the comment a trackback',
  `notify` int(2) NOT NULL DEFAULT '0' COMMENT 'Notify the user on further comments',
  `score` int(2) NOT NULL DEFAULT '0' COMMENT 'The rating score of the commentor',
  `referer` varchar(255) NOT NULL COMMENT 'The referring URL',
  `page` varchar(255) NOT NULL COMMENT 'Custom page field',
  `name` varchar(255) NOT NULL COMMENT 'Name of the commentor',
  `url` varchar(255) NOT NULL COMMENT 'Website for the commentor',
  `email` varchar(255) NOT NULL COMMENT 'Email address for the commentor',
  `subject` varchar(255) NOT NULL COMMENT 'The subject of the comment',
  `body` text NOT NULL COMMENT 'Body of the comment',
  `created_date` datetime NOT NULL COMMENT 'When the comment was created',
  `published` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Published state, allows for moderation',
  `address` varchar(50) NOT NULL COMMENT 'Address of the commentor (IP, Mac, etc)',
  `link` varchar(255) NOT NULL COMMENT 'The link to the page the comment was made on',
  PRIMARY KEY (`id`),
  KEY `idx_context` (`context`,`context_id`,`published`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_social_ratings` table : 
#

DROP TABLE IF EXISTS `bak_social_ratings`;

CREATE TABLE `bak_social_ratings` (
  `thread_id` int(11) unsigned NOT NULL,
  `context` varchar(50) NOT NULL COMMENT 'The context of the rating',
  `context_id` int(11) NOT NULL DEFAULT '0' COMMENT 'The id of the item in context',
  `referer` varchar(255) NOT NULL DEFAULT '' COMMENT 'The referring URL',
  `page` varchar(255) NOT NULL COMMENT 'Custom page field',
  `pscore_total` double NOT NULL DEFAULT '0' COMMENT 'Cummulative public score',
  `pscore_count` int(10) NOT NULL DEFAULT '0' COMMENT 'Total number of public ratings',
  `pscore` double NOT NULL DEFAULT '0' COMMENT 'Actual public score',
  `mscore_total` double NOT NULL DEFAULT '0' COMMENT 'Cummulative member score',
  `mscore_count` int(10) NOT NULL DEFAULT '0' COMMENT 'Total number of member ratings',
  `mscore` double NOT NULL DEFAULT '0' COMMENT 'Actual score',
  `used_ips` longtext COMMENT 'The ips used to vote',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`thread_id`),
  KEY `idx_updated` (`updated_date`,`pscore`),
  KEY `idx_pscore` (`pscore`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Aggregate scores for public and member ratings';

#
# Structure for the `bak_social_threads` table : 
#

DROP TABLE IF EXISTS `bak_social_threads`;

CREATE TABLE `bak_social_threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `context` varchar(50) NOT NULL COMMENT 'The context of the comment thread',
  `context_id` int(11) NOT NULL DEFAULT '0' COMMENT 'The id of the item in context',
  `page_url` varchar(255) NOT NULL COMMENT 'The URL of the page for which the thread is attached',
  `page_route` varchar(255) NOT NULL COMMENT 'The route of the page for which the thread is attached',
  `page_title` varchar(255) NOT NULL COMMENT 'The title of the page for which the thread is attached',
  `created_date` datetime NOT NULL COMMENT 'The created date for the comment thread',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thread status',
  `pings` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_context` (`context`,`context_id`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_template_styles` table : 
#

DROP TABLE IF EXISTS `bak_template_styles`;

CREATE TABLE `bak_template_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template` varchar(50) NOT NULL DEFAULT '',
  `client_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `home` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `params` varchar(2048) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_template` (`template`),
  KEY `idx_home` (`home`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_update_categories` table : 
#

DROP TABLE IF EXISTS `bak_update_categories`;

CREATE TABLE `bak_update_categories` (
  `categoryid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT '',
  `description` text NOT NULL,
  `parent` int(11) DEFAULT '0',
  `updatesite` int(11) DEFAULT '0',
  PRIMARY KEY (`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Update Categories';

#
# Structure for the `bak_update_sites` table : 
#

DROP TABLE IF EXISTS `bak_update_sites`;

CREATE TABLE `bak_update_sites` (
  `update_site_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `location` text NOT NULL,
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`update_site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Update Sites';

#
# Structure for the `bak_update_sites_extensions` table : 
#

DROP TABLE IF EXISTS `bak_update_sites_extensions`;

CREATE TABLE `bak_update_sites_extensions` (
  `update_site_id` int(11) DEFAULT '0',
  `extension_id` int(11) DEFAULT '0',
  KEY `newindex` (`update_site_id`,`extension_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Links extensions to update sites';

#
# Structure for the `bak_updates` table : 
#

DROP TABLE IF EXISTS `bak_updates`;

CREATE TABLE `bak_updates` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `update_site_id` int(11) DEFAULT '0',
  `extension_id` int(11) DEFAULT '0',
  `categoryid` int(11) DEFAULT '0',
  `name` varchar(100) DEFAULT '',
  `description` text NOT NULL,
  `element` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `folder` varchar(20) DEFAULT '',
  `client_id` tinyint(3) DEFAULT '0',
  `version` varchar(10) DEFAULT '',
  `data` text NOT NULL,
  `detailsurl` text NOT NULL,
  PRIMARY KEY (`update_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Available Updates';

#
# Structure for the `bak_user_profiles` table : 
#

DROP TABLE IF EXISTS `bak_user_profiles`;

CREATE TABLE `bak_user_profiles` (
  `user_id` int(11) NOT NULL,
  `profile_key` varchar(100) NOT NULL,
  `profile_value` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `idx_user_id_profile_key` (`user_id`,`profile_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Simple user profile storage table';

#
# Structure for the `bak_user_usergroup_map` table : 
#

DROP TABLE IF EXISTS `bak_user_usergroup_map`;

CREATE TABLE `bak_user_usergroup_map` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__users.id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__usergroups.id',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `bak_usergroups` table : 
#

DROP TABLE IF EXISTS `bak_usergroups`;

CREATE TABLE `bak_usergroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Adjacency List Reference Id',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`),
  KEY `idx_usergroup_nested_set_lookup` (`lft`,`rgt`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_users` table : 
#

DROP TABLE IF EXISTS `bak_users`;

CREATE TABLE `bak_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `usertype` varchar(25) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(4) DEFAULT '0',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usertype` (`usertype`),
  KEY `idx_name` (`name`),
  KEY `idx_block` (`block`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_viewlevels` table : 
#

DROP TABLE IF EXISTS `bak_viewlevels`;

CREATE TABLE `bak_viewlevels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `title` varchar(100) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_assetgroup_title_lookup` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Structure for the `bak_weblinks` table : 
#

DROP TABLE IF EXISTS `bak_weblinks`;

CREATE TABLE `bak_weblinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `sid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`,`state`,`archived`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_assets` table : 
#

DROP TABLE IF EXISTS `jos_assets`;

CREATE TABLE `jos_assets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set parent.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `level` int(10) unsigned NOT NULL COMMENT 'The cached level in the nested tree.',
  `name` varchar(50) NOT NULL COMMENT 'The unique name for the asset.\n',
  `title` varchar(100) NOT NULL COMMENT 'The descriptive title for the asset.',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_asset_name` (`name`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=160 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_banner_clients` table : 
#

DROP TABLE IF EXISTS `jos_banner_clients`;

CREATE TABLE `jos_banner_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `extrainfo` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `metakey` text NOT NULL,
  `own_prefix` tinyint(4) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_banner_tracks` table : 
#

DROP TABLE IF EXISTS `jos_banner_tracks`;

CREATE TABLE `jos_banner_tracks` (
  `track_date` date NOT NULL,
  `track_type` int(10) unsigned NOT NULL,
  `banner_id` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`track_date`,`track_type`,`banner_id`),
  KEY `idx_track_date` (`track_date`),
  KEY `idx_track_type` (`track_type`),
  KEY `idx_banner_id` (`banner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_banners` table : 
#

DROP TABLE IF EXISTS `jos_banners`;

CREATE TABLE `jos_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `imptotal` int(11) NOT NULL DEFAULT '0',
  `impmade` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `clickurl` varchar(200) NOT NULL DEFAULT '',
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `custombannercode` varchar(2048) NOT NULL,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `params` text NOT NULL,
  `own_prefix` tinyint(1) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reset` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`),
  KEY `idx_banner_catid` (`catid`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_categories` table : 
#

DROP TABLE IF EXISTS `jos_categories`;

CREATE TABLE `jos_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `extension` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(5120) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` varchar(2048) NOT NULL DEFAULT '',
  `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
  `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
  `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`extension`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_path` (`path`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_contact_details` table : 
#

DROP TABLE IF EXISTS `jos_contact_details`;

CREATE TABLE `jos_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `con_position` varchar(255) DEFAULT NULL,
  `address` text,
  `suburb` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `misc` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `imagepos` varchar(20) DEFAULT NULL,
  `email_to` varchar(255) DEFAULT NULL,
  `default_con` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `webpage` varchar(255) NOT NULL DEFAULT '',
  `sortname1` varchar(255) NOT NULL,
  `sortname2` varchar(255) NOT NULL,
  `sortname3` varchar(255) NOT NULL,
  `language` char(7) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_content` table : 
#

DROP TABLE IF EXISTS `jos_content`;

CREATE TABLE `jos_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `title_alias` varchar(255) NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `sectionid` int(10) unsigned NOT NULL DEFAULT '0',
  `mask` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` varchar(5120) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `language` char(7) NOT NULL COMMENT 'The language code for the article.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_content_frontpage` table : 
#

DROP TABLE IF EXISTS `jos_content_frontpage`;

CREATE TABLE `jos_content_frontpage` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_content_rating` table : 
#

DROP TABLE IF EXISTS `jos_content_rating`;

CREATE TABLE `jos_content_rating` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `rating_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_count` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_core_log_searches` table : 
#

DROP TABLE IF EXISTS `jos_core_log_searches`;

CREATE TABLE `jos_core_log_searches` (
  `search_term` varchar(128) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_extensions` table : 
#

DROP TABLE IF EXISTS `jos_extensions`;

CREATE TABLE `jos_extensions` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `element` varchar(100) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `client_id` tinyint(3) NOT NULL,
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `protected` tinyint(3) NOT NULL DEFAULT '0',
  `manifest_cache` text NOT NULL,
  `params` text NOT NULL,
  `custom_data` text NOT NULL,
  `system_data` text NOT NULL,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) DEFAULT '0',
  `state` int(11) DEFAULT '0',
  PRIMARY KEY (`extension_id`),
  KEY `element_clientid` (`element`,`client_id`),
  KEY `element_folder_clientid` (`element`,`folder`,`client_id`),
  KEY `extension` (`type`,`element`,`folder`,`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=606 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_languages` table : 
#

DROP TABLE IF EXISTS `jos_languages`;

CREATE TABLE `jos_languages` (
  `lang_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` char(7) NOT NULL,
  `title` varchar(50) NOT NULL,
  `title_native` varchar(50) NOT NULL,
  `sef` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  `description` varchar(512) NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`),
  UNIQUE KEY `idx_sef` (`sef`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_menu` table : 
#

DROP TABLE IF EXISTS `jos_menu`;

CREATE TABLE `jos_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL COMMENT 'The type of menu this item belongs to. FK to #__menu_types.menutype',
  `title` varchar(255) NOT NULL COMMENT 'The display title of the menu item.',
  `alias` varchar(255) NOT NULL COMMENT 'The SEF alias of the menu item.',
  `note` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.',
  `link` varchar(1024) NOT NULL COMMENT 'The actually link the menu item refers to.',
  `type` varchar(16) NOT NULL COMMENT 'The type of link: Component, URL, Alias, Separator',
  `published` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The published state of the menu link.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'The parent menu item in the menu tree.',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The relative level in the tree.',
  `component_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__extensions.id',
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT 'The relative ordering of the menu item in the tree.',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__users.id',
  `checked_out_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time the menu item was checked out.',
  `browserNav` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The click behaviour of the link.',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'The access level required to view the menu item.',
  `img` varchar(255) NOT NULL COMMENT 'The image of the menu item.',
  `template_style_id` int(10) unsigned NOT NULL DEFAULT '0',
  `params` varchar(10240) NOT NULL COMMENT 'JSON encoded data for the menu item.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `home` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicates if this menu item is the home or default page.',
  `language` char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_alias_parent_id` (`alias`,`parent_id`),
  KEY `idx_componentid` (`component_id`,`menutype`,`published`,`access`),
  KEY `idx_menutype` (`menutype`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_path` (`path`(333)),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=444 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_menu_types` table : 
#

DROP TABLE IF EXISTS `jos_menu_types`;

CREATE TABLE `jos_menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL,
  `title` varchar(48) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_menutype` (`menutype`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_messages` table : 
#

DROP TABLE IF EXISTS `jos_messages`;

CREATE TABLE `jos_messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_from` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id_to` int(10) unsigned NOT NULL DEFAULT '0',
  `folder_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `useridto_state` (`user_id_to`,`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_messages_cfg` table : 
#

DROP TABLE IF EXISTS `jos_messages_cfg`;

CREATE TABLE `jos_messages_cfg` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cfg_name` varchar(100) NOT NULL DEFAULT '',
  `cfg_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `idx_user_var_name` (`user_id`,`cfg_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_modules` table : 
#

DROP TABLE IF EXISTS `jos_modules`;

CREATE TABLE `jos_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(50) DEFAULT NULL,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` varchar(5120) NOT NULL DEFAULT '',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_modules_menu` table : 
#

DROP TABLE IF EXISTS `jos_modules_menu`;

CREATE TABLE `jos_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_newsfeeds` table : 
#

DROP TABLE IF EXISTS `jos_newsfeeds`;

CREATE TABLE `jos_newsfeeds` (
  `catid` int(11) NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `alias` varchar(100) NOT NULL DEFAULT '',
  `link` varchar(200) NOT NULL DEFAULT '',
  `filename` varchar(200) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `numarticles` int(10) unsigned NOT NULL DEFAULT '1',
  `cache_time` int(10) unsigned NOT NULL DEFAULT '3600',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rtl` tinyint(4) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_redirect_links` table : 
#

DROP TABLE IF EXISTS `jos_redirect_links`;

CREATE TABLE `jos_redirect_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_url` varchar(150) NOT NULL,
  `new_url` varchar(150) NOT NULL,
  `referer` varchar(150) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `created_date` int(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_link_old` (`old_url`),
  KEY `idx_link_updated` (`updated_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_schemas` table : 
#

DROP TABLE IF EXISTS `jos_schemas`;

CREATE TABLE `jos_schemas` (
  `extension_id` int(11) NOT NULL,
  `version_id` varchar(20) NOT NULL,
  PRIMARY KEY (`extension_id`,`version_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_session` table : 
#

DROP TABLE IF EXISTS `jos_session`;

CREATE TABLE `jos_session` (
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `client_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `guest` tinyint(4) unsigned DEFAULT '1',
  `time` varchar(14) DEFAULT '',
  `data` varchar(20480) DEFAULT NULL,
  `userid` int(11) DEFAULT '0',
  `username` varchar(150) DEFAULT '',
  `usertype` varchar(50) DEFAULT '',
  PRIMARY KEY (`session_id`),
  KEY `whosonline` (`guest`,`usertype`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_template_styles` table : 
#

DROP TABLE IF EXISTS `jos_template_styles`;

CREATE TABLE `jos_template_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template` varchar(50) NOT NULL DEFAULT '',
  `client_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `home` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `params` varchar(2048) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_template` (`template`),
  KEY `idx_home` (`home`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_update_categories` table : 
#

DROP TABLE IF EXISTS `jos_update_categories`;

CREATE TABLE `jos_update_categories` (
  `categoryid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT '',
  `description` text NOT NULL,
  `parent` int(11) DEFAULT '0',
  `updatesite` int(11) DEFAULT '0',
  PRIMARY KEY (`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Update Categories';

#
# Structure for the `jos_update_sites` table : 
#

DROP TABLE IF EXISTS `jos_update_sites`;

CREATE TABLE `jos_update_sites` (
  `update_site_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `location` text NOT NULL,
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`update_site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Update Sites';

#
# Structure for the `jos_update_sites_extensions` table : 
#

DROP TABLE IF EXISTS `jos_update_sites_extensions`;

CREATE TABLE `jos_update_sites_extensions` (
  `update_site_id` int(11) DEFAULT '0',
  `extension_id` int(11) DEFAULT '0',
  KEY `newindex` (`update_site_id`,`extension_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Links extensions to update sites';

#
# Structure for the `jos_updates` table : 
#

DROP TABLE IF EXISTS `jos_updates`;

CREATE TABLE `jos_updates` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `update_site_id` int(11) DEFAULT '0',
  `extension_id` int(11) DEFAULT '0',
  `categoryid` int(11) DEFAULT '0',
  `name` varchar(100) DEFAULT '',
  `description` text NOT NULL,
  `element` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `folder` varchar(20) DEFAULT '',
  `client_id` tinyint(3) DEFAULT '0',
  `version` varchar(10) DEFAULT '',
  `data` text NOT NULL,
  `detailsurl` text NOT NULL,
  PRIMARY KEY (`update_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Available Updates';

#
# Structure for the `jos_user_profiles` table : 
#

DROP TABLE IF EXISTS `jos_user_profiles`;

CREATE TABLE `jos_user_profiles` (
  `user_id` int(11) NOT NULL,
  `profile_key` varchar(100) NOT NULL,
  `profile_value` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `idx_user_id_profile_key` (`user_id`,`profile_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Simple user profile storage table';

#
# Structure for the `jos_user_usergroup_map` table : 
#

DROP TABLE IF EXISTS `jos_user_usergroup_map`;

CREATE TABLE `jos_user_usergroup_map` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__users.id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__usergroups.id',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `jos_usergroups` table : 
#

DROP TABLE IF EXISTS `jos_usergroups`;

CREATE TABLE `jos_usergroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Adjacency List Reference Id',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`),
  KEY `idx_usergroup_nested_set_lookup` (`lft`,`rgt`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_users` table : 
#

DROP TABLE IF EXISTS `jos_users`;

CREATE TABLE `jos_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `usertype` varchar(25) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(4) DEFAULT '0',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usertype` (`usertype`),
  KEY `idx_name` (`name`),
  KEY `idx_block` (`block`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_viewlevels` table : 
#

DROP TABLE IF EXISTS `jos_viewlevels`;

CREATE TABLE `jos_viewlevels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `title` varchar(100) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_assetgroup_title_lookup` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Structure for the `jos_weblinks` table : 
#

DROP TABLE IF EXISTS `jos_weblinks`;

CREATE TABLE `jos_weblinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `sid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if link is featured.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

#
# Data for the `bak_assets` table  (LIMIT 0,500)
#

INSERT INTO `bak_assets` (`id`, `parent_id`, `lft`, `rgt`, `level`, `name`, `title`, `rules`) VALUES 
  (1,0,1,140,0,'root.1','Root Asset','{\"core.login.site\":{\"6\":1,\"2\":1},\"core.login.admin\":{\"6\":1},\"core.admin\":{\"8\":1},\"core.manage\":{\"7\":1,\"10\":1},\"core.create\":{\"6\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1},\"core.edit.state\":{\"6\":1}}'),
  (2,1,2,3,1,'com_admin','com_admin','{}'),
  (3,1,4,5,1,'com_banners','com_banners','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (4,1,6,7,1,'com_cache','com_cache','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),
  (5,1,8,9,1,'com_checkin','com_checkin','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),
  (6,1,10,11,1,'com_config','com_config','{}'),
  (7,1,12,15,1,'com_contact','com_contact','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (8,1,16,33,1,'com_content','com_content','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1,\"5\":1},\"core.delete\":[],\"core.edit\":{\"4\":1},\"core.edit.state\":{\"5\":1}}'),
  (9,1,34,35,1,'com_cpanel','com_cpanel','{}'),
  (10,1,36,37,1,'com_installer','com_installer','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1},\"core.create\":[],\"core.delete\":[],\"core.edit.state\":[]}'),
  (11,1,38,39,1,'com_languages','com_languages','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (12,1,40,41,1,'com_login','com_login','{}'),
  (13,1,42,43,1,'com_mailto','com_mailto','{}'),
  (14,1,44,45,1,'com_massmail','com_massmail','{}'),
  (15,1,46,47,1,'com_media','com_media','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1,\"4\":1,\"5\":1},\"core.delete\":{\"5\":1},\"core.edit\":[],\"core.edit.state\":[]}'),
  (16,1,48,49,1,'com_menus','com_menus','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (17,1,50,51,1,'com_messages','com_messages','{}'),
  (18,1,52,53,1,'com_modules','com_modules','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (19,1,54,57,1,'com_newsfeeds','com_newsfeeds','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (20,1,58,59,1,'com_plugins','com_plugins','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (21,1,60,61,1,'com_redirect','com_redirect','{\"core.admin\":{\"7\":1},\"core.manage\":[]}'),
  (22,1,62,63,1,'com_search','com_search','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1}}'),
  (23,1,64,65,1,'com_templates','com_templates','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (24,1,66,67,1,'com_users','com_users','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (25,1,68,75,1,'com_weblinks','com_weblinks','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (26,1,76,77,1,'com_wrapper','com_wrapper','{}'),
  (27,8,17,18,2,'com_content.article.1','Joomla!',''),
  (28,1,80,81,1,'com_content.category.11','News','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (29,1,68,79,1,'com_content.category.12','Countries','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (30,29,69,78,2,'com_content.category.23','Australia',''),
  (31,30,70,71,3,'com_content.category.24','Queensland',''),
  (32,30,72,77,3,'com_content.category.25','Tasmania',''),
  (33,31,73,74,4,'com_content.article.2','Great Barrier Reef',''),
  (34,32,75,76,4,'com_content.article.3','Cradle Mountain-Lake St Clair National Park','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (35,25,55,60,2,'com_weblinks.category.20','Uncategorised Weblinks',''),
  (36,35,56,59,3,'com_weblinks.category.21','Joomla! Specific Links',''),
  (37,36,57,58,4,'com_weblinks.category.22','Other Resources',''),
  (39,7,13,14,2,'com_contact.category.26','Contacts','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (40,1,64,65,1,'com_banners.category.27','Banners',''),
  (41,19,41,42,2,'com_newsfeeds.category.28','News Feeds','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (42,60,85,86,2,'com_content.category.38','Photo Gallery','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (43,1,56,57,1,'com_content.article.56','Koala','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (44,1,58,59,1,'com_content.article.57','Wobbegone','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (45,1,60,61,1,'com_content.article.58','Phyllopteryx','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (46,1,62,63,1,'com_content.article.59','Spotted Quoll','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (47,1,64,65,1,'com_content.article.60','Pinnacles','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (48,1,66,91,1,'com_content.article.61','Ormiston Pound','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (49,1,92,93,1,'com_content.article.62','Blue Mountain Rain Forest','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (50,1,94,95,1,'com_content.article.63','Cradle Mountain','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (51,30,110,111,3,'com_content.article.9','Australian Parks','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (52,1,96,97,1,'com_content.article.64','First Blog Entry','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (53,1,98,99,1,'com_content.article.65','Second Blog Post','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (54,60,83,84,2,'com_content.category.35','Park Blog','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (55,7,15,16,2,'com_contact.category.45','Parks Site','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (56,1,114,115,1,'com_contact.category.47','Staff','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (57,1,116,117,1,'com_contact.category.48','Suppliers','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (58,1,118,119,1,'com_contact.category.49','Fruit','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (59,1,66,67,1,'com_content.article.7','Sample Sites','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (60,1,82,87,1,'com_content.category.50','Park Site','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (61,1,88,89,1,'com_content.category.32','Extensions','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}');

COMMIT;

#
# Data for the `bak_banner_clients` table  (LIMIT 0,500)
#

INSERT INTO `bak_banner_clients` (`id`, `name`, `contact`, `email`, `extrainfo`, `state`, `checked_out`, `checked_out_time`, `metakey`, `own_prefix`, `metakey_prefix`, `purchase_type`, `track_clicks`, `track_impressions`) VALUES 
  (1,'Joomla!','Administrator','email@email.com','',1,0,'0000-00-00','',0,'',-1,-1,-1);

COMMIT;

#
# Data for the `bak_banners` table  (LIMIT 0,500)
#

INSERT INTO `bak_banners` (`id`, `cid`, `type`, `name`, `alias`, `imptotal`, `impmade`, `clicks`, `clickurl`, `state`, `catid`, `description`, `sticky`, `ordering`, `metakey`, `params`, `own_prefix`, `metakey_prefix`, `purchase_type`, `track_clicks`, `track_impressions`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `reset`, `created`) VALUES 
  (1,1,0,'OSM 1','osm-1',0,43,0,'http://www.opensourcematters.org',0,27,'',0,1,'','{\"custom\":{\"bannercode\":\"\"},\"alt\":{\"alt\":\"Open Source Matters\"},\"flash\":{\"width\":\"0\",\"height\":\"0\"},\"image\":{\"url\":\"osmbanner1.png\"}}',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-10-10 13:52:59'),
  (2,1,0,'Shop 1','shop-1',0,10,0,'',1,30,'',0,1,'','{\"custom\":{\"bannercode\":\"\"},\"alt\":{\"alt\":\"\"},\"flash\":{\"width\":\"0\",\"height\":\"0\"},\"image\":{\"url\":\"http:\\/\\/localhost\\/joomla_development\\/fef8\\/images\\/banners\\/shop-ad-books.jpg\"}}',0,'',-1,0,0,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2010-01-12 00:25:24'),
  (3,1,0,'Shop 2','shop-2',0,0,0,'',0,30,'',0,2,'','{\"custom\":{\"bannercode\":\"\"},\"alt\":{\"alt\":\"Joomla! Books\"},\"flash\":{\"width\":\"0\",\"height\":\"0\"},\"image\":{\"url\":\"\\/banners\\/shop-ad.jpg\"}}',0,'',-1,0,0,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2010-01-12 00:35:30'),
  (4,1,0,'Shop 2','shop-2',0,5,1,'http://shop.joomla.org',1,30,'',0,3,'','{\"custom\":{\"bannercode\":\"\"},\"alt\":{\"alt\":\"\"},\"flash\":{\"width\":\"0\",\"height\":\"0\"},\"image\":{\"url\":\"http:\\/\\/localhost\\/joomla_development\\/fef8\\/images\\/banners\\/shop-ad.jpg\"}}',0,'',-1,0,0,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2010-01-12 00:55:54');

COMMIT;

#
# Data for the `bak_categories` table  (LIMIT 0,500)
#

INSERT INTO `bak_categories` (`id`, `asset_id`, `parent_id`, `lft`, `rgt`, `level`, `path`, `extension`, `title`, `alias`, `note`, `description`, `published`, `checked_out`, `checked_out_time`, `access`, `params`, `metadesc`, `metakey`, `metadata`, `created_user_id`, `created_time`, `modified_user_id`, `modified_time`, `hits`, `language`) VALUES 
  (1,0,0,0,67,0,'','system','ROOT','root','','',1,0,'0000-00-00',1,'{}','','','',0,'2010-03-03 19:51:41',0,'0000-00-00',0,''),
  (11,28,29,36,37,2,'sample-data-content/news','com_content','News','news','','The top articles category.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (12,29,29,28,35,2,'sample-data-content/countries','com_content','Countries','countries','','The latest news from the Joomla! Team',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (20,35,1,1,8,1,'sample-data-weblinks','com_weblinks','Sample Data Weblinks','sample-data-weblinks','','<p>The sample weblinks category.</p>',1,42,'2009-03-15 15:34:27',1,'{}','','','',0,'2010-01-24 12:23:29',0,'0000-00-00',0,'en_GB'),
  (21,36,20,2,5,2,'sample-data-weblinks/joomla-specific-links','com_weblinks','Joomla! Specific Links','joomla-specific-links','','<p>A selection of links that are all related to the Joomla! Project.</p>',1,42,'2009-03-15 15:34:27',1,'{}','','','',0,'2010-01-24 12:23:29',0,'0000-00-00',0,'en_GB'),
  (22,37,21,3,4,3,'sample-data-weblinks/joomla-specific-links/other-resources','com_weblinks','Other Resources','other-resources','','',1,0,'0000-00-00',1,'{}','','','',0,'2010-01-24 12:23:29',0,'0000-00-00',0,'en_GB'),
  (23,30,12,29,34,3,'sample-data-content/countries/australia','com_content','Australia','australia','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 12:23:33',0,'0000-00-00',0,''),
  (24,31,23,30,31,4,'sample-data-content/countries/australia/queensland','com_content','Queensland','queensland','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 12:23:33',0,'0000-00-00',0,''),
  (25,32,23,32,33,4,'sample-data-content/countries/australia/tasmania','com_content','Tasmania','tasmania','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 12:23:33',0,'0000-00-00',0,''),
  (26,38,1,49,60,1,'contacts','com_contact','Contacts','contacts','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (27,40,1,61,62,1,'banners','com_banners','Banners','banners','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (28,41,1,63,64,1,'news-feeds','com_newsfeeds','News Feeds','news-feeds','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (29,0,1,9,48,1,'sample-data-content','com_content','Sample Data-Content','sample-data-content','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (30,0,1,65,66,1,'sample-data-banners','com_banners','Sample Data-Banners','sample-data-banners','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (31,0,29,10,27,2,'sample-data-content/joomla','com_content','Joomla!','joomla','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (32,61,31,11,22,3,'sample-data-content/joomla/extensions','com_content','Extensions','extensions','','The Joomla! content management system creates webpages using extensions. There are 5 basic types of extensions: components, modules, templates, languages, and plugins. Your website includes the extensions you need to create a basic website in English, but thousands of additional extensions of all types are available. The Joomla! Extensions Directory is the largest directory of Joomla! extensions.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:36:29',0,'0000-00-00',0,''),
  (33,0,31,23,24,3,'sample-data-content/joomla/the-joomla-project','com_content','The Joomla! Project','the-joomla-project','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (34,0,31,25,26,3,'sample-data-content/joomla/the-joomla-community','com_content','The Joomla! Community','the-joomla-community','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (35,54,50,39,40,3,'sample-data-content/park-site/park-blog','com_content','Park Blog','park-blog','','Here is where I will blog all about the parks of Australia. Please comment and give me ideas of what parks you have enjoyed.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (36,0,38,42,43,4,'sample-data-content/park-site/photo-gallery/<p>scenery</p>','com_content','Scenery','scenery','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:26:43',0,'0000-00-00',0,''),
  (37,0,38,44,45,4,'sample-data-content/park-site/photo-gallery/<p>animals</p>','com_content','Animals','animals','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:26:43',0,'0000-00-00',0,''),
  (38,42,50,41,46,3,'sample-data-content/park-site/photo-gallery','com_content','Photo Gallery','photo-gallery','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (39,0,32,12,13,4,'sample-data-content/joomla/extensions/<p>components</p>','com_content','Components','components','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (40,0,32,14,15,4,'sample-data-content/joomla/extensions/<p>modules</p>','com_content','Modules','modules','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (41,0,32,16,17,4,'sample-data-content/joomla/extensions/<p>templates</p>','com_content','Templates','templates','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (42,0,32,18,19,4,'sample-data-content/joomla/extensions/<p>languages</p>','com_content','Languages','languages','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (43,0,32,20,21,4,'sample-data-content/joomla/extensions/<p>plugins</p>','com_content','Plugins','plugins','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (44,0,20,6,7,2,'sample-data-weblinks/parks-links','com_weblinks','Parks Links','parks-links','','<p>Here are links to some of my favorite parks.</p>',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:23:29',0,'0000-00-00',0,''),
  (45,55,26,50,51,2,'contacts/parks-site','com_contact','Parks Site','parks-site','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (46,0,26,52,59,2,'contacts/shop-site','com_contact','Shop Site','shop-site','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (47,56,46,53,54,3,'contacts/shop-site/staff','com_contact','Staff','staff','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (48,57,46,55,56,3,'contacts/shop-site/suppliers','com_contact','Suppliers','suppliers','','We get our fruit from the very best growers.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (49,58,46,57,58,3,'contacts/shop-site/fruit','com_contact','Fruit','fruit','','Our directory of information about different kinds of fruit.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,''),
  (50,60,29,38,47,2,'sample-data-content/park-site','com_content','Park Site','park-site','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 12:25:02',0,'0000-00-00',0,'');

COMMIT;

#
# Data for the `bak_contact_details` table  (LIMIT 0,500)
#

INSERT INTO `bak_contact_details` (`id`, `name`, `alias`, `con_position`, `address`, `suburb`, `state`, `country`, `postcode`, `telephone`, `fax`, `misc`, `image`, `imagepos`, `email_to`, `default_con`, `published`, `checked_out`, `checked_out_time`, `ordering`, `params`, `user_id`, `catid`, `access`, `mobile`, `webpage`, `sortname1`, `sortname2`, `sortname3`, `language`) VALUES 
  (1,'Contact Name','name','Position','Street Address','Suburb','State','Country','Zip Code','Telephone','Fax','<p>Information about or by the contact.</p>','powered_by.png','top','email@email.com',1,1,42,'2010-01-22 19:07:36',1,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"\",\"linka_name\":\"Twitter\",\"linka\":\"http:\\/\\/twitter.com\\/joomla\",\"linkb_name\":\"YouTube\",\"linkb\":\"http:\\/\\/www.youtube.com\\/user\\/joomla\",\"linkc_name\":\"Ustream\",\"linkc\":\"http:\\/\\/www.ustream.tv\\/joomla\",\"linkd_name\":\"FriendFeed\",\"linkd\":\"http:\\/\\/friendfeed.com\\/joomla\",\"linke_name\":\"Scribed\",\"linke\":\"http:\\/\\/www.scribd.com\\/people\\/view\\/504592-joomla\"}',0,26,1,'','','last','first','middle','en-GB'),
  (2,'Webmaster','webmaster','','','','','','','',NULL,'',NULL,NULL,'webmaster@example.com',0,1,42,'2010-01-24 00:23:23',0,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"1\",\"email_description\":\"Please send an email with any comments !\",\"show_email_copy\":\"1\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"1\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,45,1,'','','','','',''),
  (3,'Owner','owner','','','','','','','',NULL,'<p>I\'m the owner of this store.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"email_description\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,47,1,'','','','','',''),
  (4,'Buyer','buyer','','','','','','','',NULL,'<p>I am in charge of buying fruit. If you sell good fruit, contact me.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"email_description\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,26,1,'','','','','',''),
  (5,'Famous Bananas','famous-bananas','','','','','','','',NULL,'<p>This is the greatest source of bananas we have found.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"email_description\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,48,1,'','','','','',''),
  (6,'Friendly Apples','friendly-apples','','','','','','','',NULL,'<p>Friendly Apples, is a fantastic apple orchard that grows 20 kinds of apples.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"email_description\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,48,1,'','','','','','');

COMMIT;

#
# Data for the `bak_content` table  (LIMIT 0,500)
#

INSERT INTO `bak_content` (`id`, `asset_id`, `title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`, `featured`, `language`, `xreference`) VALUES 
  (1,27,'Joomla!','joomla','','<p>Congratulations, You have a Joomla! site! Joomla! makes your site easy to build a website just the way you want it and keep it simple to update and maintain. Joomla! is a flexible and powerful platform, whether you are building a small site for yourself or a huge site with hundreds of thousands of visitors. Joomla is open source, which means you can make it work just the way you want it to.</p>','',1,1,0,0,'2008-08-12 10:00:00',42,'','2010-01-12 05:19:44',42,0,'0000-00-00','2006-01-03 01:00:00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',30,0,1,'','',1,105,'{\"robots\":\"\",\"author\":\"\"}',1,'',''),
  (2,33,'Great Barrier Reef','great-barrier-reef','','<p>The Great Barrier Reef is the largest coral reef system composed of over 2,900 individual reefs[3] and 900 islands stretching for over 3,000 kilometres (1,600 mi) over an area of approximately 344,400 square kilometres (133,000 sq mi). The reef is located in the Coral Sea, off the coast of Queensland in northeast Australia.</p><p>http://en.wikipedia.org/wiki/Great_Barrier_Reef</p>','<p>The Great Barrier Reef can be seen from outer space and is the world\'s biggest single structure made by living organisms. This reef structure is composed of and built by billions of tiny organisms, known as coral polyps. The Great Barrier Reef supports a wide diversity of life, and was selected as a World Heritage Site in 1981.CNN has labelled it one of the 7 natural wonders of the world. The Queensland National Trust has named it a state icon of Queensland.</p><p>A large part of the reef is protected by the Great Barrier Reef Marine Park, which helps to limit the impact of human use, such as overfishing and tourism. Other environmental pressures to the reef and its ecosystem include water quality from runoff, climate change accompanied by mass coral bleaching, and cyclic outbreaks of the crown-of-thorns starfish.</p><p>The Great Barrier Reef has long been known to and utilised by the Aboriginal Australian and Torres Strait Islander peoples, and is an important part of local groups\' cultures and spirituality. The reef is a very popular destination for tourists, especially in the Whitsundays and Cairns regions. Tourism is also an important economic activity for the region. Fishing also occurs in the region, generating AU$ 1 billion per year.</p>',1,0,0,24,'2009-06-22 11:07:08',42,'','2009-06-22 11:14:50',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"readmore\":\"\",\"page_title\":\"\",\"layout\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (3,34,'Cradle Mountain-Lake St Clair National Park','cradle-mountain-lake-st-clair-national-park','','<p>Cradle Mountain-Lake St Clair National Park is located in the Central Highlands area of Tasmania (Australia), 165 km northwest of Hobart. The park contains many walking trails, and is where hikes along the well-known Overland Track usually begins. Major features are Cradle Mountain and Barn Bluff in the northern end, Mount Pelion East, Mount Pelion West, Mount Oakleigh and Mount Ossa in the middle and Lake St Clair in the southern end of the park. The park is part of the Tasmanian Wilderness World Heritage Area.</p><p>http://en.wikipedia.org/wiki/Cradle_Mountain-Lake_St_Clair_National_Park</p>','<h3>Access and usage fee</h3><p>Access from the south (Lake St. Clair) is usually from Derwent Bridge on the Lyell Highway. Northern access (Cradle Valley) is usually via Sheffield, Wilmot or Mole Creek. A less frequently used entrance is via the Arm River Track, from the east.</p><p>In 2005, the Tasmanian Parks & Wildlife Service introduced a booking system & fee for use of the Overland Track over peak periods. Initially the fee was 100 Australian dollars, but this was raised to 150 Australian dollars in 2007. The money that is collected is used to finance the park ranger organisation, track maintenance, building of new facilities and rental of helicopter transport to remove waste from the toilets at the huts in the park.</p>',1,0,0,25,'2009-06-22 11:17:24',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"readmore\":\"\",\"page_title\":\"\",\"layout\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (4,0,'Joomla! Beginners','joomla-beginners','','<p>If this is your first Joomla site or your first web site, you have come to the right place. Joomla will help you get your website up and running quickly and easily.</p>','<p>',1,0,0,31,'2010-01-10 01:30:47',42,'','2010-01-10 01:45:47',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"0\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',1,'',''),
  (5,0,'Upgraders','upgraders','','<p>If you are an experienced Joomla! 1.5 user, 1.6 will seem very familiar. There are new templates and improved user interfaces, but most functionality is the same. The biggest changes are improved access control (ACL), nested categories and comments.</p>','<p><br /> The new user manager which will let you manage who has access to what in your site. You can leave access groups exactly the way you had them in Joomla 1.5 or make them as complicated as you want. You can learn more about how access control works [in this article] and on the [Joomla Documentation site].<br /> <br /> In Joomla 1.5 and 1.0 content was organized into sections and categories. In 1.6 sections are gone, and you can create categories within categories, going as deep as you want. You can learn more about how categories work in 1.6 [in this article] and [on the Joomla Documentation site].<br /> <br /> Comments are now integrated into all front end components. You can control what content has comments enable, who can comment, and much more. You can learn more about comments [in this article] and [on the Joomla Documentation site].<br /> <br /> All layouts have been redesigned to improve accessibility and flexibility. If you would like to keep the 1.5 layouts, you can find them in the html folder of the MilkyWay template. Simply copy the layouts you want to the html folder of your template.<br /> <br /> Updating your site and extensions when needed is easier than ever thanks to installer improvements.<br /> <br /> To learn more about how to move a Joomla 1.5 site to a Joomla 1.6 installation [read this].</p>',1,0,0,31,'2010-01-10 01:33:34',42,'','2010-01-10 01:42:20',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"0\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',1,'',''),
  (6,0,'Designers and Developers','designers-and-developers','','<p>Joomla! 1.6 continues development of the Joomla Framework and CMS as a powerful and flexible way to bring your vision of the web to reality. With the administrator now fully MVC, the ability to control its look and the management of extensions is now complete. Languages files can now be overridden and working with multiple templates and overrides for the same views, creating the design you want is easier than it has ever been. Limiting support to PHP 5.x and above and ending legacy support for Joomla 1.0 makes Joomla lighter and faster than ever.</p>','<p>Access control lists are now incorporated using a new system developed for Joomla. The ACL system is designed with developers in mind, so it is easy to incorporate into your extensions. The new nested sets libraries allow you to incorporate infinitely deep categories but also to use nested sets in a variety of other ways.</p><p>A new forms library makes creating all kinds of user interaction simple. MooTools 1.2 provides a highly flexible javascript framework that is a major advance over MooTools 1.0.</p><p><br /> New events throughout the core make integration of your plugins where you want them a snap.</p><p>Learn about:</p><ul><li> [working with ACL] </li><li> [working with nested sets] </li><li> [integrating comments]</li><li> [using the forms library] </li><li> [working with Mootools 1.2] </li><li> [using the override system] </li><li> [Joomla! API]</li><li> [Database] </li><li> [Triggers] </li><li> [Xmlrpc] </li><li> [Installing and updating extensions]</li><li>[Setting up your development environment]</li></ul>',1,0,0,31,'2010-01-10 01:37:46',42,'','2010-01-12 05:15:00',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,0,'','',1,5,'{\"robots\":\"\",\"author\":\"\"}',1,'',''),
  (7,0,'Sample Sites','sample-sites','','<p>Your installation includes sample data, designed to show you some of the options you have for building your website. In addition to information about Joomla! there are two sample \"sites within a site\" designed to help you get started with builidng your own site.</p><p>The first site is a simple site about <a href=\"index.php?option=com_content&view=article&catid=23&id=9\">Australian Parks</a>. It shows you you an quickly and easily build a personal site with just the building blocks that are part of Joomla!. It includes a personal blog, weblinks, and a very simple image gallery.</p><p>The second site is more complex and represents what you might do if you are building a site for a small business, in this case a xxx shop.</p><p>In building either style site, or something completely different, you will probably want to add extensions and either create or purchase your own template. Many Joomla! users start off by modifying the templates that come with the core distribution so that they include special images and other design elements that relate to the site\'s focus.</p>','',1,0,0,31,'2010-01-10 01:59:13',42,'','2010-01-10 23:32:43',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',6,0,0,'','',1,18,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (8,0,'Components','components','','<p>Components are larger extensions that produce the major content for your site. Each component has one or more \"views\" that control how content is displayed.</p><p>','<p>In the Joomla! administrator there are additional extensions suce as Menus, Redirection, and the extension managers.</p>',1,0,0,32,'2010-01-10 02:07:38',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,14,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (9,0,'Australian Parks','australian-parks','','<div style=\"border: 1px solid black; background-image: url(images/sampledata/Parks/other/729px-Australia_satellite_plane.jpg);\"><div style=\"margin-top: 100px; font-family: georgia,serif; font-size: 200%; color: white; line-height: 250%; margin-left: 100px; margin-right: 100px;\"><p>Welcome!</p><p>This is a basic site about the beautiful and fascinating parks of Australia.</p><p>This site should give you some ideas about what you can do to set up a simple personal Joomla! site on a topic you are interested in.</p></div>','',1,0,0,23,'2010-01-10 05:41:55',42,'','2010-01-10 20:27:11',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,16,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (10,0,'Content','content','','<p>The content component (com_content) is what you use to write articles. It is extremely flexible and has the largest number of built in views.</p>','',1,0,0,39,'2010-01-10 12:50:35',42,'','2010-01-10 16:51:40',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (11,0,'Weblinks','weblinks','','<p>Weblinks (com_weblinks) is a component that provides a structured way to organize external links and present them in a visually attractive, consistent and informative way.','',1,0,0,39,'2010-01-10 12:58:33',42,'','2010-01-10 16:52:23',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,3,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (12,0,'News Feeds','news-feeds','','<p>News Feeds (com_newsfeeds) provides a way to organize and present news feeds. News feeds are a way that you present information from another site on your site. For example, the joomla.org website has numerous feeds that you an incorporate on your site. You an use menus to present a single feed, a list of feeds in a category, or or a list of all feed categories.</p>','',1,0,0,39,'2010-01-10 13:08:52',42,'','2010-01-10 16:51:57',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,2,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (13,0,'Contacts','contacts','','<p>The contact component provides a way to provide contact forms and information for your site or to create a most complex directory that can be used for many different purposes.','',1,0,0,39,'2010-01-10 13:19:46',42,'','2010-01-10 16:51:24',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (14,0,'Users Component','users-component','','<p>The users extension lets your site visitors register, login and logout, change their passwords and other information, and recover lost passwords.</p>','',1,0,0,39,'2010-01-10 14:00:05',42,'','2010-01-10 16:52:10',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (15,0,'Modules','modules','','<p>About modules</p>','',1,0,0,32,'2010-01-10 14:00:55',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (16,0,'Templates','templates','','<p>about templates</p>','',1,0,0,32,'2010-01-10 14:01:47',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (17,0,'Languages','languages','','<p>Joomla! installs in English, but there are translations of the interfaces, sample data and help screens are available in dozens of languages.</p><p><a href=\"http://community.joomla.org/translations.html\">Translation information</a></p><p>If there is no language pack available for your language, instructions are available for creating your own translation, which you can also contribute to the community by starting a translation team to create an accredited translation.</p><p>Translations are installed the the extensions manager in the site administrator and then managed using the language manager.</p>','',1,0,0,32,'2010-01-10 14:02:22',42,'','2010-01-11 15:29:23',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,8,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (18,0,'Plugins','plugins','','<p>Plugins are small task oriented extensions that enhance the Joomla! framework.</p><p>Some are associated with particular extensions and others, such as editors, are used across all of Joomla!. Most beginning users do not need to change any of the plugins that install with Joomla!.</p><p>','',1,0,0,32,'2010-01-10 14:03:17',42,'','2010-01-11 15:38:34',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,10,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (19,0,'Administrator Components','administrator-components','','<p>All components also are used in the administrator area of your website. In addition to the ones listed here, there are components in the administrator that do not have direct front end displays. The most important ones for most users are:</p><ul><li>Media Manager</li><li>Extensions Manager</li><li>Menu Manager</li><li>Configuration</li><li>Banners</li></ul>','<h3>Media Manager</h3><h3>Extensions Manager</h3><h3>Menu Manager</h3><h3>Configuration</h3><h3>Banners</h3>',1,0,0,39,'2010-01-10 15:28:15',42,'','2010-01-10 15:29:45',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (20,0,'Search Component','search-component','','<p>The search component proviedes basic search functionality for the information contained in your core components. Many third part extensions also can be searched by the search component.</p>','',1,0,0,39,'2010-01-10 15:45:55',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (21,0,'The Joomla! Project','the-joomla-project','','<p>All about the Joomla! Project</p>','',1,0,0,31,'2010-01-10 15:53:36',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (22,0,'The Joomla! Community','the-joomla-community','','<p>Landing Page for Joomla! Community information</p>','',1,0,0,34,'2010-01-10 16:00:04',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,2,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (23,0,'The Joomla! Project','the-joomla-project','','<p>All about the Joomla! Project</p>','',1,0,0,33,'2010-01-10 16:10:59',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (24,0,'Using Joomla!','using-joomla','','<p>All about how to work with Joomla! to create the website you want.</p>','',1,0,0,31,'2010-01-10 16:16:02',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,14,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (25,0,'Typography','typography','','<h1>H1 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h1><h2>H2 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h2><h3>H3 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h3><h4>H4 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h4><h5>H5 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h5><h6>H6 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h6><p>P The quick brown fox ran over the lazy dog. THE QUICK BROWN FOX RAN OVER THE LAZY DOG.</p><ul><li>Item</li><li>Item</li><li>Item<br /> <ul><li>Item</li><li>Item</li><li>Item<br /> <ul><li>Item</li><li>Item</li><li>Item</li></ul></li></ul></li></ul><ol><li>tem</li><li>Item</li><li>Item<br /> <ol><li>Item</li><li>Item</li><li>Item<br /><ol><li>Item</li><li>Item</li><li>Item</li></ol></li></ol> </li></ol><p>','<p>',1,0,0,41,'2010-01-10 17:12:21',42,'','2010-01-10 18:12:42',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"1\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,0,'','Typography page for Joomla! templates.',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (26,0,'Site Map','site-map','','<p>{loadposition syndicate}</p>','',1,0,0,29,'2010-01-10 19:00:52',42,'','2010-01-10 22:13:12',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',5,0,0,'','',1,40,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (27,0,'Archive Module','archive-module','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-01-12 02:32:02',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,8,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (28,0,'Latest Content Module','archive-module','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-01-11 01:05:49',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,2,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (30,0,'Most Read Content ','archive-module','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (31,0,'Who\'s Online','archive-module','','<p>{loadposition syndicate}</p><p>Shows how many logged in users and guests there are.</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-01-11 19:40:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,0,'','',1,9,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (32,0,'Newest Users','archive-module','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (33,0,'Feed Display','archive-module','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (34,0,'News Flash','news-flash','','<p>Displays a set number based on date or a random item from a category:</p><p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-01-11 03:24:18',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,0,'','',1,12,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (35,0,'Random Image','archive-module','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,3,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (36,0,'Search','search','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (37,0,'Statistics','statistics','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-01-11 20:40:14',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,5,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (38,0,'Syndicate','syndicate','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (39,0,'Wrapper','wrapper','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,14,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (40,0,'Menu','menu','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (41,0,'Banner','banner','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,12,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (42,0,'Login','login','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (43,0,'Footer','footer','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,5,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (44,0,'Custom','custom','','<p>{loadposition syndicate}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (55,0,'Related Items','related-items','','<p>{loadposition syndicate}</p>','<p></p>',1,0,0,40,'2010-01-12 04:23:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,5,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (45,0,'Modules','modules','','<p>Modules are small blocks of content that can be displayed in positions on a web page. The menus on this site are displayed in modules.</p><p>The core of Joomla! includes 17 separate modules ranging from login to search to random images.</p>','',1,0,0,40,'2010-01-11 08:16:25',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,8,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (46,0,'Templates','templates','','<p>Templates are extensions that create the look and fell of your site. Two different templates can completely transform the experience of viewing the same content.</p><p>The basic Joomla! installation includes four templates, but there are thousands of other ready made templates available or you can design your own.</p>','<p></p>',1,0,0,41,'2010-01-11 08:48:44',42,'','2010-01-11 08:51:11',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,11,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (47,0,'System','system','','<p>Default on:</p><p>Debug</p><p>Remember me</p><p>SEF</p><p>Default off:</p><p>Cache</p><p>Log</p><p>Redirect</p>','<p></p>',1,0,0,43,'2010-01-11 15:42:12',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,2,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (48,0,'Authentication','authentication','','<p>Default on:</p><p>Joomla</p><p>Default off:</p><p>Gmail</p><p>LDAP</p><p>OpenID</p>','',1,0,0,43,'2010-01-11 15:43:13',42,'','2010-01-11 15:48:18',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (49,0,'Content','content','','<p>Default on:</p><p>Email Cloaking</p><p>Load Module</p><p>Page Break</p><p>Page Navigation</p><p>Rating</p><p>Default off:</p><p>Code Highlighting (Geshi)</p>','',1,0,0,43,'2010-01-11 15:45:31',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (50,0,'Editors','editors','','<p>Default on:</p><p>CodeMirror</p><p>TinyMCE</p><p>No Editor</p><p>Default off:</p><p>None</p><p>','</p>',1,0,0,43,'2010-01-11 15:47:52',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (51,0,'Editors-xtd','editors-xtd','','<p>Default on:</p><p>Editor Button: Image</p><p>Editor Button: Readmore</p><p>Editor Button: Page Break</p><p>Editor Button: Article</p><p>Default off:<br />None</p><p></p>','<p></p>',1,0,0,43,'2010-01-11 15:57:14',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (52,0,'Search','search','','<p>The search component uses plugins to control which parts of your Joomla! site are searched.</p><p>Default On:</p><p>Content</p><p>Contacts</p><p>Weblinks</p><p>News Feeds</p><p>Categories</p><p>Default off:</p><p>Sections</p>','',1,0,0,43,'2010-01-11 17:25:41',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (53,0,'User','user','','<p>Default on:</p><p>Joomla</p><p>Profile</p><p>Default off:</p><p>Contact Creator</p>','',1,0,0,43,'2010-01-11 17:30:01',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (54,0,'What\'s New in 1.5?','whats-new-in-15','','','<p>As with previous releases, Joomla! provides a unified and easy-to-use framework for delivering content for Web sites of all kinds. To support the changing nature of the Internet and emerging Web technologies, Joomla! required substantial restructuring of its core functionality and we also used this effort to simplify many challenges within the current user interface. Joomla! 1.5 has many new features.</p><p style=\"margin-bottom: 0in;\">In Joomla! 1.5, you\'ll notice:</p>Substantially improved usability, manageability, and scalability far beyond the original Mambo foundations</p><p>Expanded accessibility to support internationalisation, double-byte characters and right-to-left support for Arabic, Farsi, and Hebrew languages among others</p></li><li>Extended integration of external applications through Web services and remote authentication such as the Lightweight Directory Access Protocol (LDAP)</p></li><li>Enhanced content delivery, template and presentation capabilities to support accessibility standards and content delivery to any destination</p></li>n<li><p >A more sustainable and flexible framework for Component and Extension developers</p></li><li><p>Backward compatibility with previous releases of Components, Templates, Modules, and other Extensions</p></li></ul>',-1,0,0,31,'2010-01-12 03:51:04',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (56,43,'Koala','koala','','<p><img src=\"images/sampledata/Parks/animals/180px-Koala-ag1.jpg\" border=\"0\" alt=\"Koala  Thumbnail\" width=\"180\" height=\"123\" style=\"vertical-align: middle; border: 0;\" /></p>','<p><img src=\"images/sampledata/Parks/animals/800px-Koala-ag1.jpg\" border=\"0\" alt=\"Koala Climbing Tree\" width=\"500\" height=\"341\" style=\"vertical-align: middle; border: 0;\" /></p><p> </p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Koala-ag1.jpg</p><p class=\"caption\">Author: Arnaud Gaillard</p><p><span class=\"caption\">License: Creative Commons Share Alike Attribution Generic 1.0</span></p>',1,0,0,37,'2010-01-23 18:18:52',42,'','2010-01-24 02:45:37',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size koala\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',9,0,0,'','',1,17,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (57,44,'Wobbegone','wobbegone','','<p><img src=\"images/sampledata/Parks/animals/180px-Wobbegong.jpg\" border=\"0\" alt=\"Wobbegone\" style=\"vertical-align: middle; border: 0;\" /></p>','<p><img src=\"images/sampledata/Parks/animals/800px-Wobbegong.jpg\" border=\"0\" style=\"vertical-align: middle; border: 0;\" /></p><p>Source: http://en.wikipedia.org/wiki/File:Wobbegong.jpg</p><p>Author: Richard Ling</p><p>Rights: GNU Free Documentation License v 1.2 or later</p>',1,0,0,37,'2010-01-23 18:30:58',42,'','2010-01-24 02:47:22',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size wobbegone\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',7,0,0,'','',1,8,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (58,45,'Phyllopteryx','phyllopteryx','','<p><img src=\"images/sampledata/Parks/animals/200px-Phyllopteryx_taeniolatus1.jpg\" border=\"0\" style=\"vertical-align: middle;\" /></p><p> </p>','<p><img src=\"images/sampledata/Parks/animals/800px-Phyllopteryx_taeniolatus1.jpg\" border=\"0\" style=\"vertical-align: middle;\" /></p><p> </p><p>Source: http://en.wikipedia.org/wiki/File:Phyllopteryx_taeniolatus1.jpg</p><p>Author: Richard Ling</p><p>License: GNU Free Documentation License v 1.2 or later</p>',1,0,0,37,'2010-01-23 19:00:03',42,'','2010-01-24 02:46:34',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"1\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size phyllopteryx\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',6,0,0,'','',1,8,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (59,46,'Spotted Quoll','spotted-quoll','','<p><img src=\"images/sampledata/Parks/animals/220px-SpottedQuoll_2005_SeanMcClean.jpg\" border=\"0\" alt=\"Spotted Quoll\" style=\"vertical-align: middle; border: 0;\" /></p>','<p><img src=\"images/sampledata/Parks/animals/789px-SpottedQuoll_2005_SeanMcClean.jpg\" border=\"0\" alt=\"Spotted Quoll\" style=\"vertical-align: middle;\" /></p><p> </p><p>Source: http://en.wikipedia.org/wiki/File:SpottedQuoll_2005_SeanMcClean.jpg</p><p>Author: Sean McClean</p><p>License: GNU Free Documentation License v 1.2 or later</p>',1,0,0,37,'2010-01-23 19:09:49',42,'','2010-01-24 02:46:56',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size spotted quoll\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',4,0,0,'','',1,11,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (60,47,'Pinnacles','pinnacles','','<p><img src=\"images/sampledata/Parks/landscape/120px-Pinnacles_Western_Australia.jpg\" border=\"0\" alt=\"Kings Canyon\" width=\"120\" height=\"90\" style=\"vertical-align: middle; border: 0;\" /></p><p> </p>','<p><img src=\"images/sampledata/Parks/landscape/800px-Pinnacles_Western_Australia.jpg\" border=\"0\" alt=\"King\'s Canyon\" width=\"500\" height=\"374\" style=\"vertical-align: middle; border: 0;\" /></p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Pinnacles_Western_Australia.jpg</p><p class=\"caption\">Author: <a class=\"new\" href=\"http://commons.wikimedia.org/w/index.php?title=User:Markdoe&action=edit&redlink=1\" title=\"User:Markdoe (page does not exist)\"></a>Martin Gloss</p><p class=\"caption\">License: GNU Free Documentation license v 1.2 or later.</p>',1,0,0,36,'2010-01-23 20:15:41',42,'','2010-01-24 04:00:13',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"1\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size Pinnacles\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',8,0,0,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (61,48,'Ormiston Pound','ormiston-pound','','<p><img src=\"images/sampledata/Parks/landscape/180px-Ormiston_Pound.JPG\" border=\"0\" alt=\"Ormiston Pound\" style=\"border: 0;\" /></p><p> </p>','<p><img src=\"images/sampledata/Parks/landscape/800px-Ormiston_Pound.JPG\" border=\"0\" alt=\"Ormiston Pound\" height=\"375\" style=\"vertical-align: middle; border: 0;\" /></p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Ormiston_Pound.JPG</p><p class=\"caption\">Author:</p><p class=\"caption\">License: GNU Free Public Documentation License</p>',1,0,0,36,'2010-01-23 20:53:40',42,'','2010-01-24 04:00:50',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"1\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full Size Ormiston Pound\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',6,0,0,'','',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (62,49,'Blue Mountain Rain Forest','blue-mountain-rain-forest','','<p><img src=\"images/sampledata/Parks/landscape/120px-Rainforest,bluemountainsNSW.jpg\" border=\"0\" alt=\"Rain Forest Blue Mountrains\" /></p>','<p><img src=\"images/sampledata/Parks/landscape/727px-Rainforest,bluemountainsNSW.jpg\" border=\"0\" alt=\"Rain Forest Blue Mountrains\" style=\"vertical-align: middle;\" /></p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Rainforest,bluemountainsNSW.jpg</p><p class=\"caption\">Author: Adam J.W.C.</p><p class=\"caption\">License: GNU Free Documentation License</p>',1,0,0,36,'2010-01-23 21:08:32',42,'','2010-01-24 03:05:52',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"1\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size Blue Mountains rainforest\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',4,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (63,50,'Cradle Mountain','cradle-mountain','','<p><img src=\"images/sampledata/Parks/landscape/250px-Cradle_Mountain_Seen_From_Barn_Bluff.jpg\" border=\"0\" alt=\"Cradle Mountain\" style=\"vertical-align: middle;\" /></p>','<p><img src=\"images/sampledata/Parks/landscape/800px-Cradle_Mountain_Seen_From_Barn_Bluff.jpg\" border=\"0\" alt=\"Cradle Mountain\" style=\"vertical-align: middle;\" /></p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Rainforest,bluemountainsNSW.jpg</p><p class=\"caption\">Author: Alan J.W.C.</p><p class=\"caption\">License: GNU Free Documentation License v . 1.2 or later</p>',1,0,0,36,'2010-01-23 21:17:34',42,'','2010-01-24 03:06:25',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size Cradle Mountrain\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',3,0,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (64,52,'First Blog Entry','first-blog-entry','','<div id=\"lipsum\"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed faucibus purus vitae diam posuere nec eleifend elit dictum. Aenean sit amet erat purus, id fermentum lorem. Integer elementum tristique lectus, non posuere quam pretium sed. Quisque scelerisque erat at urna condimentum euismod. Fusce vestibulum facilisis est, a accumsan massa aliquam in. In auctor interdum mauris a luctus. Morbi euismod tempor dapibus. Duis dapibus posuere quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In eu est nec erat sollicitudin hendrerit. Pellentesque sed turpis nunc, sit amet laoreet velit. Praesent vulputate semper nulla nec varius. Aenean aliquam, justo at blandit sodales, mauris leo viverra orci, sed sodales mauris orci vitae magna.</p>','<p>Quisque a massa sed libero tristique suscipit. Morbi tristique molestie metus, vel vehicula nisl ultrices pretium. Sed sit amet est et sapien condimentum viverra. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus viverra tortor porta orci convallis ac cursus erat sagittis. Vivamus aliquam, purus non luctus adipiscing, orci urna imperdiet eros, sed tincidunt neque sapien et leo. Cras fermentum, dolor id tempor vestibulum, neque lectus luctus mauris, nec congue tellus arcu nec augue. Nulla quis mi arcu, in bibendum quam. Sed placerat laoreet fermentum. In varius lobortis consequat. Proin vulputate felis ac arcu lacinia adipiscing. Morbi molestie, massa id sagittis luctus, sem sapien sollicitudin quam, in vehicula quam lectus quis augue. Integer orci lectus, bibendum in fringilla sit amet, rutrum eget enim. Curabitur at libero vitae lectus gravida luctus. Nam mattis, ligula sit amet vestibulum feugiat, eros sem sodales mi, nec dignissim ante elit quis nisi. Nulla nec magna ut leo convallis sagittis ac non erat. Etiam in augue nulla, sed tristique orci. Vestibulum quis eleifend sapien.</p><p>Nam ut orci vel felis feugiat posuere ut eu lorem. In risus tellus, sodales eu eleifend sed, imperdiet id nulla. Nunc at enim lacus. Etiam dignissim, arcu quis accumsan varius, dui dui faucibus erat, in molestie mauris diam ac lacus. Sed sit amet egestas nunc. Nam sollicitudin lacinia sapien, non gravida eros convallis vitae. Integer vehicula dui a elit placerat venenatis. Nullam tincidunt ligula aliquet dui interdum feugiat. Maecenas ultricies, lacus quis facilisis vehicula, lectus diam consequat nunc, euismod eleifend metus felis eu mauris. Aliquam dapibus, ipsum a dapibus commodo, dolor arcu accumsan neque, et tempor metus arcu ut massa. Curabitur non risus vitae nisl ornare pellentesque. Pellentesque nec ipsum eu dolor sodales aliquet. Vestibulum egestas scelerisque tincidunt. Integer adipiscing ultrices erat vel rhoncus.</p><p>Integer ac lectus ligula. Nam ornare nisl id magna tincidunt ultrices. Phasellus est nisi, condimentum at sollicitudin vel, consequat eu ipsum. In venenatis ipsum in ligula tincidunt bibendum id et leo. Vivamus quis purus massa. Ut enim magna, pharetra ut condimentum malesuada, auctor ut ligula. Proin mollis, urna a aliquam rutrum, risus erat cursus odio, a convallis enim lectus ut lorem. Nullam semper egestas quam non mattis. Vestibulum venenatis aliquet arcu, consectetur pretium erat pulvinar vel. Vestibulum in aliquet arcu. Ut dolor sem, pellentesque sit amet vestibulum nec, tristique in orci. Sed lacinia metus vel purus pretium sit amet commodo neque condimentum.</p><p>Aenean laoreet aliquet ullamcorper. Nunc tincidunt luctus tellus, eu lobortis sapien tincidunt sed. Donec luctus accumsan sem, at porttitor arcu vestibulum in. Sed suscipit malesuada arcu, ac porttitor orci volutpat in. Vestibulum consectetur vulputate eros ut porttitor. Aenean dictum urna quis erat rutrum nec malesuada tellus elementum. Quisque faucibus, turpis nec consectetur vulputate, mi enim semper mi, nec porttitor libero magna ut lacus. Quisque sodales, leo ut fermentum ullamcorper, tellus augue gravida magna, eget ultricies felis dolor vitae justo. Vestibulum blandit placerat neque, imperdiet ornare ipsum malesuada sed. Quisque bibendum quam porta diam molestie luctus. Sed metus lectus, ornare eu vulputate vel, eleifend facilisis augue. Maecenas eget urna velit, ac volutpat velit. Nam id bibendum ligula. Donec pellentesque, velit eu convallis sodales, nisi dui egestas nunc, et scelerisque lectus quam ut ipsum.</p></div>',1,0,0,35,'2010-01-23 22:41:36',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'',''),
  (65,53,'Second Blog Post','second-blog-post','','<div id=\"lipsum\"><p>Pellentesque bibendum metus ut dolor fermentum ut pulvinar tortor hendrerit. Nam vel odio vel diam tempus iaculis in non urna. Curabitur scelerisque, nunc id interdum vestibulum, felis elit luctus dui, ac dapibus tellus mauris tempus augue. Duis congue facilisis lobortis. Phasellus neque erat, tincidunt non lacinia sit amet, rutrum vitae nunc. Sed placerat lacinia fermentum. Integer justo sem, cursus id tristique eget, accumsan vel sapien. Curabitur ipsum neque, elementum vel vestibulum ut, lobortis a nisl. Fusce malesuada mollis purus consectetur auctor. Morbi tellus nunc, dapibus sit amet rutrum vel, laoreet quis mauris. Aenean nec sem nec purus bibendum venenatis. Mauris auctor commodo libero, in adipiscing dui adipiscing eu. Praesent eget orci ac nunc sodales varius.</p>','<p>Nam eget venenatis lorem. Vestibulum a interdum sapien. Suspendisse potenti. Quisque auctor purus nec sapien venenatis vehicula malesuada velit vehicula. Fusce vel diam dolor, quis facilisis tortor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque libero nisi, pellentesque quis cursus sit amet, vehicula vitae nisl. Curabitur nec nunc ac sem tincidunt auctor. Phasellus in mattis magna. Donec consequat orci eget tortor ultricies rutrum. Mauris luctus vulputate molestie. Proin tincidunt vehicula euismod. Nam congue leo non erat cursus a adipiscing ipsum congue. Nulla iaculis purus sit amet turpis aliquam sit amet dapibus odio tincidunt. Ut augue diam, congue ut commodo pellentesque, fermentum mattis leo. Sed iaculis urna id enim dignissim sodales at a ipsum. Quisque varius lobortis mollis. Nunc purus magna, pellentesque pellentesque convallis sed, varius id ipsum. Etiam commodo mi mollis erat scelerisque fringilla. Nullam bibendum massa sagittis diam ornare rutrum.</p><p>Praesent convallis metus ut elit faucibus tempus in quis dui. Donec fringilla imperdiet nibh, sit amet fringilla velit congue et. Quisque commodo luctus ligula, vitae porttitor eros venenatis in. Praesent aliquet commodo orci id varius. Nulla nulla nibh, varius id volutpat nec, sagittis nec eros. Cras et dui justo. Curabitur malesuada facilisis neque, sed tempus massa tincidunt ut. Sed suscipit odio in lacus auctor vehicula non ut lacus. In hac habitasse platea dictumst. Sed nulla nisi, lacinia in viverra at, blandit vel tellus. Nulla metus erat, ultrices non pretium vel, varius nec sem. Morbi sollicitudin mattis lacus quis pharetra. Donec tincidunt mollis pretium. Proin non libero justo, vitae mattis diam. Integer vel elit in enim varius posuere sed vitae magna. Duis blandit tempor elementum. Vestibulum molestie dui nisi.</p><p>Curabitur volutpat interdum lorem sed tempus. Sed placerat quam non ligula lacinia sodales. Cras ultrices justo at nisi luctus hendrerit. Quisque sit amet placerat justo. In id sapien eu neque varius pharetra sed in sapien. Etiam nisl nunc, suscipit sed gravida sed, scelerisque ut nisl. Mauris quis massa nisl, aliquet posuere ligula. Etiam eget tortor mauris. Sed pellentesque vestibulum commodo. Mauris vitae est a libero dapibus dictum fringilla vitae magna.</p><p>Nulla facilisi. Praesent eget elit et mauris gravida lobortis ac nec risus. Ut vulputate ullamcorper est, volutpat feugiat lacus convallis non. Maecenas quis sem odio, et aliquam libero. Integer vel tortor eget orci tincidunt pulvinar interdum at erat. Integer ullamcorper consequat eros a pellentesque. Cras sagittis interdum enim in malesuada. Etiam non nunc neque. Fusce non ligula at tellus porta venenatis. Praesent tortor orci, fermentum sed tincidunt vel, varius vel dui. Duis pulvinar luctus odio, eget porta justo vulputate ac. Nulla varius feugiat lorem sed tempor. Phasellus pulvinar dapibus magna eget egestas. In malesuada lectus at justo pellentesque vitae rhoncus nulla ultrices. Proin ut sem sem. Donec eu suscipit ipsum. Cras eu arcu porttitor massa feugiat aliquet at quis nisl.</p></div>',1,0,0,35,'2010-01-23 22:44:09',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'','');

COMMIT;

#
# Data for the `bak_content_frontpage` table  (LIMIT 0,500)
#

INSERT INTO `bak_content_frontpage` (`content_id`, `ordering`) VALUES 
  (1,3),
  (4,4),
  (5,2),
  (6,1);

COMMIT;

#
# Data for the `bak_extensions` table  (LIMIT 0,500)
#

INSERT INTO `bak_extensions` (`extension_id`, `name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`) VALUES 
  (1,'com_mailto','component','com_mailto','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (2,'com_wrapper','component','com_wrapper','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (3,'com_admin','component','com_admin','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (4,'com_banners','component','com_banners','',1,1,1,0,'','{\"purchase_type\":\"1\",\"track_impressions\":\"0\",\"track_clicks\":\"0\",\"metakey_prefix\":\"\"}','','',0,'0000-00-00',0,0),
  (5,'com_cache','component','com_cache','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (6,'com_categories','component','com_categories','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (7,'com_checkin','component','com_checkin','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (8,'com_contact','component','com_contact','',1,1,1,0,'','{\"show_contact_list\":\"0\",\"show_name\":\"1\",\"show_position\":\"1\",\"show_email\":\"0\",\"show_street_address\":\"1\",\"show_suburb\":\"1\",\"show_state\":\"1\",\"show_postcode\":\"1\",\"show_country\":\"1\",\"show_telephone\":\"1\",\"show_mobile\":\"1\",\"show_fax\":\"1\",\"show_webpage\":\"1\",\"show_misc\":\"1\",\"show_image\":\"1\",\"allow_vcard\":\"0\",\"show_articles\":\"1\",\"show_profile\":\"1\",\"show_links\":\"1\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"contact_icons\":\"0\",\"icon_address\":\"\",\"icon_email\":\"\",\"icon_telephone\":\"\",\"icon_mobile\":\"\",\"icon_fax\":\"\",\"icon_misc\":\"\",\"show_headings\":\"1\",\"show_position_headings\":\"1\",\"show_email_headings\":\"0\",\"show_telephone_headings\":\"1\",\"show_mobile_headings\":\"1\",\"show_fax_headings\":\"1\",\"allow_vcard_headings\":\"0\",\"show_email_form\":\"1\",\"email_description\":\"\",\"show_email_copy\":\"1\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"1\",\"custom_reply\":\"0\",\"redirect\":\"\",\"show_category_crumb\":\"0\",\"article_allow_ratings\":\"0\",\"article_allow_comments\":\"0\",\"metakey\":\"\",\"metadesc\":\"\",\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','',0,'0000-00-00',0,0),
  (9,'com_cpanel','component','com_cpanel','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (10,'com_installer','component','com_installer','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (11,'com_languages','component','com_languages','',1,1,1,0,'','{\"administrator\":\"en-GB\",\"site\":\"en-GB\"}','','',0,'0000-00-00',0,0),
  (12,'com_login','component','com_login','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (13,'com_media','component','com_media','',1,1,0,1,'','{\"upload_extensions\":\"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS\",\"upload_maxsize\":\"10000000\",\"file_path\":\"images\",\"image_path\":\"images\",\"restrict_uploads\":\"1\",\"allowed_media_usergroup\":\"3\",\"check_mime\":\"1\",\"image_extensions\":\"bmp,gif,jpg,png\",\"ignore_extensions\":\"\",\"upload_mime\":\"image\\/jpeg,image\\/gif,image\\/png,image\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/powerpoint,text\\/plain,application\\/x-zip\",\"upload_mime_illegal\":\"text\\/html\",\"enable_flash\":\"0\"}','','',0,'0000-00-00',0,0),
  (14,'com_menus','component','com_menus','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (15,'com_messages','component','com_messages','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (16,'com_modules','component','com_modules','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (17,'com_newsfeeds','component','com_newsfeeds','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (18,'com_plugins','component','com_plugins','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (19,'com_search','component','com_search','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (20,'com_templates','component','com_templates','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (21,'com_weblinks','component','com_weblinks','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (22,'English (United Kingdom)','language','en-GB','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (23,'English (United Kingdom)','language','en-GB','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (24,'XXTestLang','language','xx-XX','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (25,'Joomla! Web Application Framework','library','joomla','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (26,'PHPMailer','library','phpmailer','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (27,'XML RPC for PHP','library','phpxmlrpc','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (28,'SimplePie','library','simplepie','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (29,'mod_articles_archive','module','mod_articles_archive','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (30,'mod_articles_latest','module','mod_articles_latest','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (31,'mod_articles_popular','module','mod_articles_popular','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (32,'mod_banners','module','mod_banners','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (33,'mod_breadcrumbs','module','mod_breadcrumbs','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (34,'mod_custom','module','mod_custom','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (35,'mod_feed','module','mod_feed','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (36,'mod_footer','module','mod_footer','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (37,'mod_login','module','mod_login','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (38,'mod_menu','module','mod_menu','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (39,'mod_articles_news','module','mod_articles_news','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (40,'mod_random_image','module','mod_random_image','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (41,'mod_related_items','module','mod_related_items','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (42,'mod_search','module','mod_search','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (43,'mod_stats','module','mod_stats','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (44,'mod_syndicate','module','mod_syndicate','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (45,'mod_users_latest','module','mod_users_latest','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (46,'mod_weblinks','module','mod_weblinks','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (47,'mod_whosonline','module','mod_whosonline','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (48,'mod_wrapper','module','mod_wrapper','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (49,'mod_custom','module','mod_custom','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (50,'mod_feed','module','mod_feed','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (51,'mod_latest','module','mod_latest','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (52,'mod_logged','module','mod_logged','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (53,'mod_login','module','mod_login','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (54,'mod_menu','module','mod_menu','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (55,'mod_online','module','mod_online','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (56,'mod_popular','module','mod_popular','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (57,'mod_quickicon','module','mod_quickicon','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (58,'mod_status','module','mod_status','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (59,'mod_submenu','module','mod_submenu','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (60,'mod_title','module','mod_title','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (61,'mod_toolbar','module','mod_toolbar','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (62,'mod_unread','module','mod_unread','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (63,'plg_authentication_gmail','plugin','gmail','authentication',0,0,1,0,'','{\"applysuffix\":\"0\",\"suffix\":\"\",\"verifypeer\":\"1\",\"user_blacklist\":\"\"}','','',0,'0000-00-00',0,0),
  (64,'plg_authentication_joomla','plugin','joomla','authentication',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (65,'plg_authentication_ldap','plugin','ldap','authentication',0,0,1,0,'','{\"host\":\"\",\"port\":\"389\",\"use_ldapV3\":\"0\",\"negotiate_tls\":\"0\",\"no_referrals\":\"0\",\"auth_method\":\"bind\",\"base_dn\":\"\",\"search_string\":\"\",\"users_dn\":\"\",\"username\":\"\",\"password\":\"\",\"ldap_fullname\":\"fullName\",\"ldap_email\":\"mail\",\"ldap_uid\":\"uid\"}','','',0,'0000-00-00',0,0),
  (66,'plg_authentication_openid','plugin','openid','authentication',0,0,1,0,'','{\"usermode\":\"2\",\"phishing-resistant\":\"0\",\"multi-factor\":\"0\",\"multi-factor-physical\":\"0\"}','','',0,'0000-00-00',0,0),
  (67,'plg_content_emailcloak','plugin','emailcloak','content',0,1,1,0,'','{\"mode\":\"1\"}','','',0,'0000-00-00',0,0),
  (68,'plg_content_geshi','plugin','geshi','content',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (69,'plg_content_loadmodule','plugin','loadmodule','content',0,1,1,0,'','{\"style\":\"none\"}','','',0,'0000-00-00',0,0),
  (70,'plg_content_pagebreak','plugin','pagebreak','content',0,1,1,0,'','{\"enabled\":\"1\",\"title\":\"1\",\"multipage_toc\":\"1\",\"showall\":\"1\"}','','',0,'0000-00-00',0,0),
  (71,'plg_content_pagenavigation','plugin','pagenavigation','content',0,1,1,0,'','{\"position\":\"1\"}','','',0,'0000-00-00',0,0),
  (72,'plg_content_vote','plugin','vote','content',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (73,'plg_editors_codemirror','plugin','codemirror','editors',0,1,1,0,'','{\"linenumbers\":\"0\",\"tabmode\":\"indent\"}','','',0,'0000-00-00',0,0),
  (74,'plg_editors_none','plugin','none','editors',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (75,'plg_editors_tinymce','plugin','tinymce','editors',0,1,1,0,'','{\"mode\":\"1\",\"skin\":\"0\",\"compressed\":\"0\",\"cleanup_startup\":\"0\",\"cleanup_save\":\"2\",\"entity_encoding\":\"raw\",\"lang_mode\":\"0\",\"lang_code\":\"en\",\"text_direction\":\"ltr\",\"content_css\":\"1\",\"content_css_custom\":\"\",\"relative_urls\":\"1\",\"newlines\":\"0\",\"invalid_elements\":\"script,applet,iframe\",\"extended_elements\":\"\",\"toolbar\":\"top\",\"toolbar_align\":\"left\",\"html_height\":\"550\",\"html_width\":\"750\",\"element_path\":\"1\",\"fonts\":\"1\",\"paste\":\"1\",\"searchreplace\":\"1\",\"insertdate\":\"1\",\"format_date\":\"%Y-%m-%d\",\"inserttime\":\"1\",\"format_time\":\"%H:%M:%S\",\"colors\":\"1\",\"table\":\"1\",\"smilies\":\"1\",\"media\":\"1\",\"hr\":\"1\",\"directionality\":\"1\",\"fullscreen\":\"1\",\"style\":\"1\",\"layer\":\"1\",\"xhtmlxtras\":\"1\",\"visualchars\":\"1\",\"nonbreaking\":\"1\",\"template\":\"1\",\"blockquote\":\"1\",\"wordcount\":\"1\",\"advimage\":\"1\",\"advlink\":\"1\",\"autosave\":\"1\",\"contextmenu\":\"1\",\"inlinepopups\":\"1\",\"safari\":\"0\",\"custom_plugin\":\"\",\"custom_button\":\"\"}','','',0,'0000-00-00',0,0),
  (76,'plg_editors-xtd_article','plugin','article','editors-xtd',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (77,'plg_editors-xtd_image','plugin','image','editors-xtd',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (78,'plg_editors-xtd_pagebreak','plugin','pagebreak','editors-xtd',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (79,'plg_editors-xtd_readmore','plugin','readmore','editors-xtd',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (80,'plg_search_categories','plugin','categories','search',0,1,1,0,'','{\"search_limit\":\"50\"}','','',0,'0000-00-00',0,0),
  (81,'plg_search_contacts','plugin','contacts','search',0,1,1,0,'','{\"search_limit\":\"50\"}','','',0,'0000-00-00',0,0),
  (82,'plg_search_content','plugin','content','search',0,1,1,0,'','{\"host\":\"\",\"port\":\"389\",\"use_ldapV3\":\"0\",\"negotiate_tls\":\"0\",\"no_referrals\":\"0\",\"auth_method\":\"bind\",\"base_dn\":\"\",\"search_string\":\"\",\"users_dn\":\"\",\"username\":\"\",\"password\":\"\",\"ldap_fullname\":\"fullName\",\"ldap_email\":\"mail\",\"ldap_uid\":\"uid\"}','','',0,'0000-00-00',0,0),
  (83,'plg_search_newsfeeds','plugin','newsfeeds','search',0,1,1,0,'','{\"search_limit\":\"50\"}','','',0,'0000-00-00',0,0),
  (84,'plg_search_weblinks','plugin','weblinks','search',0,1,1,0,'','{\"search_limit\":\"50\"}','','',0,'0000-00-00',0,0),
  (85,'plg_system_cache','plugin','cache','system',0,0,1,0,'','{\"browsercache\":\"0\",\"cachetime\":\"15\"}','','',0,'0000-00-00',0,0),
  (86,'plg_system_debug','plugin','debug','system',0,1,1,0,'','{\"profile\":\"1\",\"queries\":\"1\",\"memory\":\"1\",\"language_files\":\"1\",\"language_strings\":\"1\",\"strip-first\":\"1\",\"strip-prefix\":\"\",\"strip-suffix\":\"\"}','','',0,'0000-00-00',0,0),
  (87,'plg_system_log','plugin','log','system',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (88,'plg_system_redirect','plugin','redirect','system',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (89,'plg_system_remember','plugin','remember','system',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (90,'plg_system_sef','plugin','sef','system',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (91,'plg_user_contactcreator','plugin','contactcreator','user',0,0,1,0,'','{\"autowebpage\":\"\",\"category\":\"26\",\"autopublish\":\"0\"}','','',0,'0000-00-00',0,0),
  (92,'plg_user_joomla','plugin','joomla','user',0,1,1,0,'','{\"autoregister\":\"1\"}','','',0,'0000-00-00',0,0),
  (94,'atomic','template','atomic','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (95,'rhuk_milkyway','template','rhuk_milkyway','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (96,'bluestork','template','bluestork','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (97,'com_comments','component','com_comments','',1,1,0,0,'','','','',0,'0000-00-00',0,0),
  (98,'com_config','component','com_config','',1,1,0,1,'','','','',0,'0000-00-00',0,0),
  (99,'com_content','component','com_content','',1,1,0,1,'','{\"show_category\":\"1\",\"link_category\":\"1\",\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"1\",\"show_author\":\"1\",\"show_create_date\":\"1\",\"show_modify_date\":\"1\",\"show_publish_date\":\"1\",\"show_item_navigation\":\"1\",\"show_readmore\":\"1\",\"show_icons\":\"1\",\"show_print_icon\":\"1\",\"show_email_icon\":\"1\",\"show_hits\":\"1\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"0\",\"show_pagination_results\":\"1\",\"display_num\":\"10\",\"list_type\":\"single\",\"show_headings\":\"1\",\"show_date\":\"hide\",\"date_format\":\"\",\"filter_field\":\"hide\",\"show_pagination_limit\":\"1\",\"list_hits\":\"1\",\"list_author\":\"1\",\"show_description\":\"0\",\"show_description_image\":\"0\",\"drill_down_layout\":\"0\",\"all_subcategories\":\"all\",\"empty_categories\":\"1\",\"article_count\":\"0\",\"orderby_pri\":\"alpha\",\"orderby_sec\":\"rdate\",\"order_date\":\"created\",\"show_pagination\":\"1\",\"show_noauth\":\"0\",\"show_feed_link\":\"1\",\"feed_summary\":\"0\",\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attritbutes\":\"\"}','','',0,'0000-00-00',0,0),
  (100,'com_redirect','component','com_redirect','',1,1,0,0,'','','','',0,'0000-00-00',0,0),
  (101,'com_users','component','com_users','',1,1,0,1,'','{\"allowUserRegistration\":\"1\",\"new_usertype\":\"2\",\"useractivation\":\"1\",\"frontend_userparams\":\"1\",\"mailSubjectPrefix\":\"\",\"mailBodySuffix\":\"\"}','','',0,'0000-00-00',0,0),
  (102,'plg_user_profile','plugin','profile','user',0,1,1,0,'','{\"register-require_address1\":\"0\",\"register-require_address2\":\"0\",\"register-require_city\":\"0\",\"register-require_region\":\"0\",\"register-require_country\":\"0\",\"register-require_postal_code\":\"0\",\"register-require_phone\":\"0\",\"register-require_website\":\"0\",\"profile-require_address1\":\"1\",\"profile-require_address2\":\"1\",\"profile-require_city\":\"1\",\"profile-require_region\":\"1\",\"profile-require_country\":\"1\",\"profile-require_postal_code\":\"1\",\"profile-require_phone\":\"1\",\"profile-require_website\":\"1\"}','','',0,'0000-00-00',0,0),
  (103,'XXTestLang','language','xx-XX','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (104,'mod_articles_category','module','mod_articles_category','',0,1,1,0,'','','','',0,'0000-00-00',0,0);

COMMIT;

#
# Data for the `bak_languages` table  (LIMIT 0,500)
#

INSERT INTO `bak_languages` (`lang_id`, `lang_code`, `title`, `title_native`, `description`, `published`) VALUES 
  (1,'en-GB','English (UK)','English (UK)','',1),
  (2,'en-US','English (US)','English (US)','',1),
  (3,'xx-XX','xx (Test)','xx (Test)','',1);

COMMIT;

#
# Data for the `bak_menu` table  (LIMIT 0,500)
#

INSERT INTO `bak_menu` (`id`, `menutype`, `title`, `alias`, `note`, `path`, `link`, `type`, `published`, `parent_id`, `level`, `component_id`, `ordering`, `checked_out`, `checked_out_time`, `browserNav`, `access`, `img`, `template_style_id`, `params`, `lft`, `rgt`, `home`) VALUES 
  (1,'','Menu_Item_Root','root','','','','',1,0,0,0,0,0,'0000-00-00',0,0,'',0,'',0,227,0),
  (2,'_adminmenu','com_banners','Banners','','Banners','index.php?option=com_banners','component',0,1,1,4,0,0,'0000-00-00',0,0,'class:banners',0,'',1,10,0),
  (3,'_adminmenu','com_banners','Banners','','Banners/Banners','index.php?option=com_banners','component',0,2,2,4,0,0,'0000-00-00',0,0,'class:banners',0,'',2,3,0),
  (4,'_adminmenu','com_banners_clients','Clients','','Banners/Clients','index.php?option=com_banners&view=clients','component',0,2,2,4,0,0,'0000-00-00',0,0,'class:banners-clients',0,'',4,5,0),
  (5,'_adminmenu','com_banners_tracks','Tracks','','Banners/Tracks','index.php?option=com_banners&view=tracks','component',0,2,2,4,0,0,'0000-00-00',0,0,'class:banners-tracks',0,'',6,7,0),
  (6,'_adminmenu','com_banners_categories','Categories','','Banners/Categories','index.php?option=com_categories&extension=com_banners','component',0,2,2,6,0,0,'0000-00-00',0,0,'class:banners-cat',0,'',8,9,0),
  (7,'_adminmenu','com_contact','Contacts','','Contacts','index.php?option=com_contact','component',0,1,1,8,0,0,'0000-00-00',0,0,'class:contact',0,'',11,16,0),
  (8,'_adminmenu','com_contact','Contacts','','Contacts/Contacts','index.php?option=com_contact','component',0,7,2,8,0,0,'0000-00-00',0,0,'class:contact',0,'',12,13,0),
  (9,'_adminmenu','com_contact_categories','Categories','','Contacts/Categories','index.php?option=com_categories&extension=com_contact','component',0,7,2,6,0,0,'0000-00-00',0,0,'class:contact-cat',0,'',14,15,0),
  (10,'_adminmenu','com_messages','Messaging','','Messaging','index.php?option=com_messages','component',0,1,1,15,0,0,'0000-00-00',0,0,'class:messages',0,'',17,22,0),
  (11,'_adminmenu','com_messages_add','New Private Message','','Messaging/New Private Message','index.php?option=com_messages&task=message.add','component',0,10,2,15,0,0,'0000-00-00',0,0,'class:messages-add',0,'',18,19,0),
  (12,'_adminmenu','com_messages_read','Read Private Message','','Messaging/Read Private Message','index.php?option=com_messages','component',0,10,2,15,0,0,'0000-00-00',0,0,'class:messages-read',0,'',20,21,0),
  (13,'_adminmenu','com_newsfeeds','News Feeds','','News Feeds','index.php?option=com_newsfeeds','component',0,1,1,17,0,0,'0000-00-00',0,0,'class:newsfeeds',0,'',23,28,0),
  (14,'_adminmenu','com_newsfeeds_feeds','Feeds','','News Feeds/Feeds','index.php?option=com_newsfeeds','component',0,13,2,17,0,0,'0000-00-00',0,0,'class:newsfeeds',0,'',24,25,0),
  (15,'_adminmenu','com_newsfeeds_categories','Categories','','News Feeds/Categories','index.php?option=com_categories&extension=com_newsfeeds','component',0,13,2,6,0,0,'0000-00-00',0,0,'class:newsfeeds-cat',0,'',26,27,0),
  (16,'_adminmenu','com_redirect','Redirect','','Redirect','index.php?option=com_redirect','component',0,1,1,100,0,0,'0000-00-00',0,0,'class:redirect',0,'',37,38,0),
  (17,'_adminmenu','com_search','Search','','Search','index.php?option=com_search','component',0,1,1,19,0,0,'0000-00-00',0,0,'class:search',0,'',29,30,0),
  (18,'_adminmenu','com_weblinks','Weblinks','','Weblinks','index.php?option=com_weblinks','component',0,1,1,21,0,0,'0000-00-00',0,0,'class:weblinks',0,'',31,36,0),
  (19,'_adminmenu','com_weblinks_links','Links','','Weblinks/Links','index.php?option=com_weblinks','component',0,18,2,21,0,0,'0000-00-00',0,0,'class:weblinks',0,'',32,33,0),
  (20,'_adminmenu','com_weblinks_categories','Categories','','Weblinks/Categories','index.php?option=com_categories&extension=com_weblinks','component',0,18,2,6,0,0,'0000-00-00',0,0,'class:weblinks-cat',0,'',34,35,0),
  (101,'mainmenu','Home','home','','home','index.php?option=com_content&view=frontpage','component',1,1,1,9,0,0,'0000-00-00',0,1,'',0,'{\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"multi_column_order\":\"1\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',189,190,1),
  (398,'thissite','Articles','articles','','site-map/articles','index.php?Itemid=','alias',1,294,2,0,0,0,'0000-00-00',0,1,'',0,'{\"aliasoptions\":\"272\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',216,217,0),
  (223,'thissite','Site Administrator','administrator','','administrator','administrator/','url',1,1,1,0,2,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"-1\"}',219,220,0),
  (204,'usermenu','Logout','logout','','logout','index.php?option=com_users&view=login','component',1,1,1,101,5,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',225,226,0),
  (202,'usermenu','Submit an Article','submit-an-article','','submit-an-article','index.php?option=com_content&view=form&layout=edit','component',1,1,1,99,3,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',221,222,0),
  (203,'usermenu','Submit a Web Link','submit-a-web-link','','submit-a-web-link','index.php?option=com_weblinks&view=form&layout=edit','component',1,1,1,21,4,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',223,224,0),
  (227,'aboutjoomla','Weblinks Categories','weblinks-categories','','using-joomla/extensions/components/weblinks-component/weblinks-categories','index.php?option=com_weblinks&view=categories','component',1,265,5,21,6,0,'0000-00-00',0,1,'',0,'{\"Category\":\"20\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"-1\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',85,86,0),
  (233,'thissite','Login','login','','login','index.php?option=com_users&view=login','component',1,1,1,101,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',47,48,0),
  (229,'aboutjoomla','Single Contact','single-contact','','using-joomla/extensions/components/contact-component/single-contact','index.php?option=com_contact&view=contact&id=1','component',1,270,5,8,0,0,'0000-00-00',0,1,'',0,'{\"show_category_crumb\":\"\",\"article_allow_ratings\":\"\",\"article_allow_comments\":\"\",\"show_contact_list\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_links\":\"\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',77,78,0),
  (201,'usermenu','Your Profile','your-profile','','your-profile','index.php?option=com_users&view=profile','component',1,1,1,101,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',41,42,0),
  (231,'parks','Parks in Categories','parks-in-categories','','parks-in-categories','index.php?option=com_content&view=categories','component',1,1,1,99,0,0,'0000-00-00',0,1,'',4,'{\"Category\":\"23\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',45,46,0),
  (296,'parks','Park Links','park-links','','park-links','index.php?option=com_weblinks&view=category&id=44','component',1,1,1,21,0,0,'0000-00-00',0,1,'',4,'{\"show_feed_link\":\"1\",\"image\":\"-1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',205,206,0),
  (234,'parks','Park Blog','park-blog','','park-blog','index.php?option=com_content&view=category&layout=blog&id=35','component',1,1,1,99,0,0,'0000-00-00',0,1,'',4,'{\"show_description\":\"1\",\"show_description_image\":\"1\",\"show_subcategory_content\":\"1\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',49,50,0),
  (238,'thissite','Sample Data','sample-data','','sample-data','index.php?option=com_content&view=article&id=7','component',1,1,1,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',51,52,0),
  (239,'thissite','Home','home','','home','index.php?Itemid=101','alias',1,1,1,0,0,0,'0000-00-00',0,1,'',0,'{\"aliasoptions\":\"101\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',39,40,0),
  (206,'top','Sample Sites','sample-sites','','sample-sites','index.php?option=com_content&view=article&id=7','component',1,1,1,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',57,58,0),
  (207,'top','Joomla.org','joomlaorg','','joomlaorg','http://joomla.org','url',1,1,1,0,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',67,68,0),
  (242,'parks','Write a Blog Post','write-a-blog-post','','write-a-blog-post','index.php?option=com_content&view=form&layout=edit','component',1,1,1,99,0,0,'0000-00-00',0,3,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',53,54,0),
  (243,'parks','Parks Home','parks-home','','parks-home','index.php?option=com_content&view=article&id=9','component',1,1,1,99,0,0,'0000-00-00',0,1,'',4,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_title\":\"0\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"0\",\"show_icons\":\"\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',43,44,0),
  (244,'parks','Image Gallery','image-gallery','','image-gallery','index.php?option=com_content&view=category&layout=blog&id=38','component',1,1,1,99,0,0,'0000-00-00',0,1,'',4,'{\"show_description\":\"0\",\"show_description_image\":\"0\",\"show_subcategory_content\":\"0\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',59,64,0),
  (270,'aboutjoomla','Contact  Component','contact-component','','using-joomla/extensions/components/contact-component','index.php?option=com_content&view=article&id=13','component',1,268,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',72,79,0),
  (279,'aboutjoomla','The Joomla! Community','the-joomla-community','','the-joomla-community','index.php?option=com_content&view=article&id=22','component',1,1,1,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',201,202,0),
  (249,'aboutjoomla','Submit a Weblink','submit-a-weblink','','using-joomla/extensions/components/weblinks-component/submit-a-weblink','index.php?option=com_weblinks&view=form&layout=edit','component',1,265,5,21,0,0,'0000-00-00',0,3,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',81,82,0),
  (251,'aboutjoomla','Contact Categories','contact-categories','','using-joomla/extensions/components/contact-component/contact-categories','index.php?option=com_contact&view=categories','component',1,270,5,8,0,0,'0000-00-00',0,1,'',0,'{\"Category\":\"26\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',73,74,0),
  (252,'aboutjoomla','News Feed Categories','new-feed-categories','','using-joomla/extensions/components/news-feeds-component/new-feed-categories','index.php?option=com_newsfeeds&view=categories','component',1,267,5,17,0,0,'0000-00-00',0,1,'',0,'{\"Category\":\"28\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',107,108,0),
  (253,'aboutjoomla','News Feed Category','news-feed-category','','using-joomla/extensions/components/news-feeds-component/news-feed-category','index.php?option=com_newsfeeds&view=category&id=28','component',1,267,5,17,0,0,'0000-00-00',0,1,'',0,'{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"display_num\":\"\",\"show_pagination_limit\":\"\",\"show_pagination\":\"\",\"show_pagination_results\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',111,112,0),
  (254,'aboutjoomla','Single News Feed','single-news-feed','','using-joomla/extensions/components/news-feeds-component/single-news-feed','index.php?option=com_newsfeeds&view=newsfeed&id=1','component',1,267,5,17,0,0,'0000-00-00',0,1,'',0,'{\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',109,110,0),
  (255,'aboutjoomla','Search','search','','using-joomla/extensions/components/search-component/search','index.php?option=com_search&view=search','component',1,276,5,19,0,0,'0000-00-00',0,1,'',0,'{\"search_areas\":\"1\",\"show_date\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',131,132,0),
  (256,'aboutjoomla','Archived articles','archived-articles','','using-joomla/extensions/components/content-component/archived-articles','index.php?option=com_content&view=archive','component',1,266,5,99,0,0,'0000-00-00',0,1,'',0,'{\"orderby\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',99,100,0),
  (257,'aboutjoomla','Single Article','single-article','','using-joomla/extensions/components/content-component/single-article','index.php?option=com_content&view=article&id=2','component',1,266,5,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',89,90,0),
  (278,'aboutjoomla','The Joomla! Project','the-joomla-project','','the-joomla-project','index.php?option=com_content&view=category&id=33','component',1,1,1,99,0,0,'0000-00-00',0,1,'',0,'{\"show_description\":\"\",\"show_description_image\":\"\",\"display_num\":\"\",\"show_headings\":\"\",\"show_date\":\"\",\"date_format\":\"\",\"filter_field\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"list_type\":\"\",\"show_pagination_limit\":\"\",\"list_hits\":\"\",\"list_author\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',203,204,0),
  (259,'aboutjoomla','Article Category Blog','article-category-blog','','using-joomla/extensions/components/content-component/article-category-blog','index.php?option=com_content&view=category&layout=blog&id=29','component',1,266,5,99,0,0,'0000-00-00',0,1,'',0,'{\"show_description\":\"0\",\"show_description_image\":\"0\",\"show_subcategory_content\":\"0\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',93,94,0),
  (260,'aboutjoomla','Article Category List','article-category-list','','using-joomla/extensions/components/content-component/article-category-list','index.php?option=com_content&view=category&id=24','component',1,266,5,99,0,0,'0000-00-00',0,1,'',0,'{\"show_description\":\"\",\"show_description_image\":\"\",\"display_num\":\"\",\"show_headings\":\"\",\"show_date\":\"\",\"date_format\":\"\",\"filter_field\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"list_type\":\"\",\"show_pagination_limit\":\"\",\"list_hits\":\"\",\"list_author\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',95,96,0),
  (261,'aboutjoomla','Featured articles','featured-articles','','using-joomla/extensions/components/content-component/featured-articles','index.php?option=com_content&view=category&layout=featured&id=29','component',1,266,5,99,0,0,'0000-00-00',0,1,'',0,'{\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"multi_column_order\":\"1\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',103,104,0),
  (262,'aboutjoomla','Featured Articles List','featured-articles-list','','using-joomla/extensions/components/content-component/featured-articles-list','index.php?option=com_content&view=frontpage','component',1,266,5,99,0,0,'0000-00-00',0,1,'',0,'{\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"multi_column_order\":\"1\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',97,98,0),
  (263,'aboutjoomla','Submit Article','submit-article','','using-joomla/extensions/components/content-component/submit-article','index.php?option=com_content&view=form&layout=edit','component',1,266,5,99,0,0,'0000-00-00',0,3,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',101,102,0),
  (264,'thissite','Search','search','','search','index.php?Itemid=','alias',1,1,1,0,0,0,'0000-00-00',0,1,'',0,'{\"aliasoptions\":\"255\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',65,66,0),
  (265,'aboutjoomla','Weblinks Component','weblinks-component','','using-joomla/extensions/components/weblinks-component','index.php?option=com_content&view=article&id=11','component',1,268,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',80,87,0),
  (266,'aboutjoomla','Content Component','content-component','','using-joomla/extensions/components/content-component','index.php?option=com_content&view=article&id=10','component',1,268,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',88,105,0),
  (267,'aboutjoomla','News Feeds Component','news-feeds-component','','using-joomla/extensions/components/news-feeds-component','index.php?option=com_content&view=article&id=12','component',1,268,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',106,113,0),
  (268,'aboutjoomla','Components','components','','using-joomla/extensions/components','index.php?option=com_content&view=article&id=8','component',1,277,3,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',71,134,0),
  (277,'aboutjoomla','Extensions','extensions','','using-joomla/extensions','index.php?option=com_content&view=category&id=32','component',1,280,2,99,0,0,'0000-00-00',0,1,'',0,'{\"show_description\":\"1\",\"show_description_image\":\"1\",\"display_num\":\"\",\"show_headings\":\"0\",\"show_date\":\"hide\",\"date_format\":\"\",\"filter_field\":\"hide\",\"orderby_sec\":\"\",\"order_date\":\"\",\"list_type\":\"\",\"show_pagination_limit\":\"\",\"list_hits\":\"0\",\"list_author\":\"0\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',70,197,0),
  (271,'aboutjoomla','Users Component','users-component','','using-joomla/extensions/components/users-component','index.php?option=com_content&view=article&id=14','component',1,268,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',114,127,0),
  (272,'aboutjoomla','Article Categories','article-categories','','using-joomla/extensions/components/content-component/article-categories','index.php?option=com_content&view=categories','component',1,266,5,99,0,0,'0000-00-00',0,1,'',0,'{\"Category\":\"29\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',91,92,0),
  (273,'aboutjoomla','Administrator Components','administrator-components','','using-joomla/extensions/components/administrator-components','index.php?option=com_content&view=article&id=19','component',1,268,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',128,129,0),
  (274,'aboutjoomla','Weblinks Single Category','webl-links-single-category','','using-joomla/extensions/components/weblinks-component/webl-links-single-category','index.php?option=com_weblinks&view=category&id=21','component',1,265,5,21,0,0,'0000-00-00',0,1,'',0,'{\"show_feed_link\":\"1\",\"image\":\"-1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',83,84,0),
  (275,'aboutjoomla','Contact Single Category','contact-single-category','','using-joomla/extensions/components/contact-component/contact-single-category','index.php?option=com_contact&view=category&catid=26','component',1,270,5,8,0,0,'0000-00-00',0,1,'',0,'{\"display_num\":\"20\",\"image\":\"-1\",\"image_align\":\"right\",\"show_limit\":\"0\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',75,76,0),
  (276,'aboutjoomla','Search Component','search-component','','using-joomla/extensions/components/search-component','index.php?option=com_content&view=article&id=20','component',1,268,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',130,133,0),
  (280,'aboutjoomla','Using Joomla!','using-joomla','','using-joomla','index.php?option=com_content&view=article&id=24','component',1,1,1,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',69,198,0),
  (281,'aboutjoomla','Modules','modules','','using-joomla/extensions/modules','index.php?option=com_content&view=article&id=45','component',1,277,3,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',135,168,0),
  (282,'aboutjoomla','Templates','templates','','using-joomla/extensions/templates','index.php?option=com_content&view=article&id=46','component',1,277,3,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',169,178,0),
  (283,'aboutjoomla','Languages','languages','','using-joomla/extensions/languages','index.php?option=com_content&view=article&id=17','component',1,277,3,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',179,180,0),
  (284,'aboutjoomla','Plugins','plugins','','using-joomla/extensions/plugins','index.php?option=com_content&view=article&id=18','component',1,277,3,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',181,196,0),
  (285,'aboutjoomla','Typography Atomic','typography-milky-way','','using-joomla/extensions/templates/typography-milky-way','index.php?option=com_content&view=article&id=25','component',1,282,4,99,0,0,'0000-00-00',0,1,'',3,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',172,173,0),
  (286,'aboutjoomla','Typography Milky Way','typography-milky-way','','using-joomla/extensions/templates/typography-milky-way','index.php?option=com_content&view=article&id=25','component',1,282,4,99,0,0,'0000-00-00',0,1,'',1,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"\",\"show_icons\":\"1\",\"show_print_icon\":\"1\",\"show_email_icon\":\"1\",\"show_hits\":\"0\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',170,171,0),
  (205,'top','Home','home','','home','index.php?Itemid=101','alias',1,1,1,0,0,0,'0000-00-00',0,1,'',0,'{\"aliasoptions\":\"101\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',55,56,0),
  (290,'thissite','Articles','articles','','site-map/articles','index.php?option=com_content&view=categories','component',-2,294,2,9,0,0,'0000-00-00',0,1,'',0,'{\"Category\":\"29\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',208,209,0),
  (291,'thissite','Web Links','weblinks','','site-map/weblinks','index.php?Itemid=','alias',1,294,2,0,0,0,'0000-00-00',0,1,'',0,'{\"aliasoptions\":\"227\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',210,211,0),
  (292,'thissite','Contacts','contacts','','site-map/contacts','index.php?Itemid=','alias',1,294,2,0,0,0,'0000-00-00',0,1,'',0,'{\"aliasoptions\":\"251\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',212,213,0),
  (293,'thissite','News Feeds','news-feeds','','site-map/news-feeds','index.php?Itemid=','alias',1,294,2,0,0,0,'0000-00-00',0,1,'',0,'{\"aliasoptions\":\"252\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',214,215,0),
  (294,'thissite','Site Map','site-map','','site-map','index.php?option=com_content&view=article&id=26','component',1,1,1,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',207,218,0),
  (300,'aboutjoomla','Latest Users','latest-users','','using-joomla/extensions/modules/latest-users','index.php?option=com_content&view=article&id=32','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',136,137,0),
  (301,'aboutjoomla','Who\'s Online','whos-online','','using-joomla/extensions/modules/whos-online','index.php?option=com_content&view=article&id=31','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',138,139,0),
  (302,'aboutjoomla','Most Popular','most-popular','','using-joomla/extensions/modules/most-popular','index.php?option=com_content&view=article&id=30','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',140,141,0),
  (303,'aboutjoomla','Menu','menu','','using-joomla/extensions/modules/menu','index.php?option=com_content&view=article&id=40','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',150,151,0),
  (304,'aboutjoomla','Statistics','statistics','','using-joomla/extensions/modules/statistics','index.php?option=com_content&view=article&id=37','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',154,155,0),
  (305,'aboutjoomla','Banner','banner','','using-joomla/extensions/modules/banner','index.php?option=com_content&view=article&id=41','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',156,157,0),
  (306,'aboutjoomla','Search','search','','using-joomla/extensions/modules/search','index.php?option=com_content&view=article&id=36','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',152,153,0),
  (307,'aboutjoomla','Random Image','random-image','','using-joomla/extensions/modules/random-image','index.php?option=com_content&view=article&id=35','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',146,147,0),
  (309,'aboutjoomla','News Flash','news-flash','','using-joomla/extensions/modules/news-flash','index.php?option=com_content&view=article&id=34','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',144,145,0),
  (310,'aboutjoomla','Latest Articles','latest-articles','','using-joomla/extensions/modules/latest-articles','index.php?option=com_content&view=article&id=28','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',142,143,0),
  (311,'aboutjoomla','Syndicate','syndicate','','using-joomla/extensions/modules/syndicate','index.php?option=com_content&view=article&id=38','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',148,149,0),
  (312,'aboutjoomla','Login','login','','using-joomla/extensions/modules/login','index.php?option=com_content&view=article&id=42','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',158,159,0),
  (313,'aboutjoomla','Wrapper','wrapper','','using-joomla/extensions/modules/wrapper','index.php?option=com_content&view=article&id=39','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',160,161,0),
  (314,'aboutjoomla','Home Page Milky Way','home-page-milky-way','','using-joomla/extensions/templates/home-page-milky-way','index.php?Itemid=','alias',1,282,4,0,0,0,'0000-00-00',0,1,'',1,'{\"aliasoptions\":\"101\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',174,175,0),
  (316,'aboutjoomla','Home Page Atomic','home-page-atomic','','using-joomla/extensions/templates/home-page-atomic','index.php?option=com_content&view=frontpage','component',1,282,4,99,0,0,'0000-00-00',0,1,'',3,'{\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"multi_column_order\":\"1\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',176,177,0),
  (317,'aboutjoomla','System','system','','using-joomla/extensions/plugins/system','index.php?option=com_content&view=article&id=47','component',1,284,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',182,183,0),
  (318,'aboutjoomla','Authentication','authentication','','using-joomla/extensions/plugins/authentication','index.php?option=com_content&view=article&id=48','component',1,284,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',184,185,0),
  (319,'aboutjoomla','Content','content','','using-joomla/extensions/plugins/content','index.php?option=com_content&view=article&id=49','component',1,284,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',186,187,0),
  (320,'aboutjoomla','Editors','editors','','using-joomla/extensions/plugins/editors','index.php?option=com_content&view=article&id=50','component',1,284,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',188,189,0),
  (321,'aboutjoomla','Editors Extended','editors-extended','','using-joomla/extensions/plugins/editors-extended','index.php?option=com_content&view=article&id=51','component',1,284,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',190,191,0),
  (322,'aboutjoomla','Search','search','','using-joomla/extensions/plugins/search','index.php?option=com_content&view=article&id=52','component',1,284,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',192,193,0),
  (323,'aboutjoomla','User','user','','using-joomla/extensions/plugins/user','index.php?option=com_content&view=article&id=53','component',1,284,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',194,195,0),
  (324,'aboutjoomla','Footer','footer','','using-joomla/extensions/modules/footer','index.php?option=com_content&view=article&id=43','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',162,163,0),
  (325,'aboutjoomla','Archive','archive','','using-joomla/extensions/modules/archive','index.php?option=com_content&view=article&id=27','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',164,165,0),
  (326,'aboutjoomla','Related Items','related-items','','using-joomla/extensions/modules/related-items','index.php?option=com_content&view=article&id=55','component',1,281,4,99,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',166,167,0),
  (399,'parks','Animals','animals','','image-gallery/animals','index.php?option=com_content&view=category&layout=blog&id=37','component',1,244,2,99,0,0,'0000-00-00',0,1,'',4,'{\"show_description\":\"1\",\"show_description_image\":\"0\",\"show_subcategory_content\":\"0\",\"num_leading_articles\":\"0\",\"num_intro_articles\":\"6\",\"num_columns\":\"2\",\"num_links\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',60,61,0),
  (400,'parks','Scenery','scenery','','image-gallery/scenery','index.php?option=com_content&view=category&layout=blog&id=36','component',1,244,2,99,0,0,'0000-00-00',0,1,'',4,'{\"show_description\":\"0\",\"show_description_image\":\"0\",\"show_subcategory_content\":\"0\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',62,63,0),
  (402,'aboutjoomla','Login Form','login-form','','using-joomla/extensions/components/users-component/login-form','index.php?option=com_users&view=login','component',1,271,5,101,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',115,116,0),
  (403,'aboutjoomla','User Profile','user-profile','','using-joomla/extensions/components/users-component/user-profile','index.php?option=com_users&view=profile','component',1,271,5,101,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',117,118,0),
  (404,'aboutjoomla','Edit User Profile','edit-user-profile','','using-joomla/extensions/components/users-component/edit-user-profile','index.php?option=com_users&view=profile&layout=edit','component',1,271,5,101,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',119,120,0),
  (405,'aboutjoomla','Registration Form','registration-form','','using-joomla/extensions/components/users-component/registration-form','index.php?option=com_users&view=registration','component',1,271,5,101,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',121,122,0),
  (406,'aboutjoomla','Password Reminder Request','paassword-reminder','','using-joomla/extensions/components/users-component/paassword-reminder','index.php?option=com_users&view=remind','component',1,271,5,101,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',123,124,0),
  (409,'aboutjoomla','Password Reset','password-reset','','using-joomla/extensions/components/users-component/password-reset','index.php?option=com_users&view=reset','component',1,271,5,101,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_title\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',125,126,0);

COMMIT;

#
# Data for the `bak_menu_types` table  (LIMIT 0,500)
#

INSERT INTO `bak_menu_types` (`id`, `menutype`, `title`, `description`) VALUES 
  (1,'mainmenu','Main Menu','The main menu for the site'),
  (2,'usermenu','User Menu','A Menu for logged in Users'),
  (3,'top','Top','Links for major types of users'),
  (4,'aboutjoomla','About Joomla','All about Joomla!'),
  (5,'parks','Australian Parks','Main menu for a site about Australian  parks'),
  (6,'thissite','This Site','Simple Home Menu');

COMMIT;

#
# Data for the `bak_modules` table  (LIMIT 0,500)
#

INSERT INTO `bak_modules` (`id`, `title`, `note`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `published`, `module`, `access`, `showtitle`, `params`, `client_id`, `language`) VALUES 
  (1,'Main Menu','','',1,'left',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'menutype=mainmenu\nmoduleclass_sfx=_menu\n',0,''),
  (2,'Login','','',1,'login',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_login',1,1,'',1,''),
  (3,'Popular Articles','','',3,'cpanel',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_popular',3,1,'{\"count\":\"5\",\"catid\":\"\",\"user_id\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',1,''),
  (4,'Recently Added Articles','','',4,'cpanel',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_latest',3,1,'ordering=c_dsc\nuser_id=0\ncache=0\n\n',1,''),
  (6,'Unread Messages','','',1,'header',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_unread',3,1,'',1,''),
  (7,'Online Users','','',2,'header',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_online',3,1,'',1,''),
  (8,'Toolbar','','',1,'toolbar',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_toolbar',3,1,'',1,''),
  (9,'Quick Icons','','',1,'icon',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_quickicon',3,1,'',1,''),
  (10,'Logged-in Users','','',2,'cpanel',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_logged',3,1,'',1,''),
  (12,'Admin Menu','','',1,'menu',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',3,1,'',1,''),
  (13,'Admin Submenu','','',1,'submenu',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_submenu',3,1,'',1,''),
  (14,'User Status','','',1,'status',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_status',3,1,'',1,''),
  (15,'Title','','',1,'title',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_title',3,1,'',1,''),
  (16,'User Menu','','',4,'left',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',2,1,'menutype=usermenu\nmoduleclass_sfx=_menu\ncache=1',0,''),
  (17,'Login Form','','',8,'left',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_login',1,1,'greeting=1\nname=0',0,''),
  (18,'Breadcrumbs','','',1,'breadcrumb',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_breadcrumbs',1,1,'moduleclass_sfx=\ncache=0\nshowHome=1\nhomeText=Home\nshowComponent=1\nseparator=\n\n',0,''),
  (19,'Banners','','',1,'bottom',0,'0000-00-00','0000-00-00','0000-00-00',0,'mod_banners',1,1,'{\"target\":\"1\",\"count\":\"1\",\"cid\":\"1\",\"catid\":\"27\",\"tag_search\":\"0\",\"ordering\":\"0\",\"header_text\":\"\",\"footer_text\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"0\"}',0,''),
  (20,'Top','','',1,'user3',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"top\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (22,'Australian Parks ','','',1,'left',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"parks\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (23,'About Joomla!','','',3,'left',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"aboutjoomla\",\"startLevel\":\"0\",\"endLevel\":\"2\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"_menu\",\"cache\":\"0\"}',0,''),
  (24,'Extensions','','',1,'right',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"aboutjoomla\",\"startLevel\":\"2\",\"endLevel\":\"3\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (25,'Site Map','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,0,'{\"menutype\":\"thissite\",\"startLevel\":\"1\",\"endLevel\":\"2\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (26,'This Site','','',1,'left',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"thissite\",\"startLevel\":\"0\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"_menu\",\"cache\":\"0\"}',0,''),
  (27,'Archive','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_archive',1,1,'{\"count\":\"10\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (28,'Latest Content','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_latest',1,1,'{\"count\":\"5\",\"ordering\":\"c_dsc\",\"user_id\":\"0\",\"show_front\":\"1\",\"catid\":\"11\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (29,'Most Read','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',0,'mod_articles_popular',1,1,'{\"show_front\":\"1\",\"count\":\"5\",\"catid\":\"29\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (30,'Feed Display','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_feed',1,1,'{\"rssurl\":\"http:\\/\\/community.joomla.org\\/blogs\\/community.feed?type=rss\",\"rssrtl\":\"0\",\"rsstitle\":\"1\",\"rssdesc\":\"1\",\"rssimage\":\"1\",\"rssitems\":\"3\",\"rssitemdesc\":\"1\",\"word_count\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (31,'News Flash: Latest','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_news',1,1,'{\"catid\":\"24\",\"layout\":\"\",\"image\":\"0\",\"link_titles\":\"\",\"showLastSeparator\":\"1\",\"readmore\":\"1\",\"item_title\":\"0\",\"items\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (32,'News Flash: Random','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_news',1,1,'{\"catid\":\"32\",\"layout\":\"\",\"image\":\"0\",\"link_titles\":\"\",\"showLastSeparator\":\"1\",\"readmore\":\"0\",\"item_title\":\"0\",\"items\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (33,'Random Image','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_random_image',1,1,'{\"type\":\"jpg\",\"folder\":\"sample-data\",\"link\":\"\",\"width\":\"\",\"height\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (34,'Related Items','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_related_items',1,1,'{\"showDate\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (35,'Search','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_search',1,1,'{\"width\":\"20\",\"text\":\"\",\"button\":\"\",\"button_pos\":\"right\",\"imagebutton\":\"\",\"button_text\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (36,'Statistics','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_stats',1,1,'{\"serverinfo\":\"0\",\"siteinfo\":\"0\",\"counter\":\"0\",\"increase\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (37,'Syndicate','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_syndicate',1,1,'{\"text\":\"Feed Entries\",\"format\":\"rss\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (38,'Newest Users','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_users_latest',1,1,'{\"shownumber\":\"5\",\"linknames\":\"0\",\"linktowhat\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (39,'Online Now','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_whosonline',1,1,'{\"showmode\":\"2\",\"linknames\":\"0\",\"linktowhat\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (40,'Wrapper','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_wrapper',1,1,'{\"url\":\"http:\\/\\/fsf.org\",\"scrolling\":\"auto\",\"width\":\"100%\",\"height\":\"200\",\"height_auto\":\"1\",\"add\":\"1\",\"target\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (41,'Footer','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_footer',1,1,'{\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,''),
  (42,'Views','','',1,'right',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"aboutjoomla\",\"startLevel\":\"04\",\"endLevel\":\"05\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (43,'Extension List','','',1,'right',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"aboutjoomla\",\"startLevel\":\"03\",\"endLevel\":\"04\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (44,'Login','','',0,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_login',1,1,'{\"cache\":\"1\",\"pretext\":\"\",\"posttext\":\"\",\"login\":\"\",\"logout\":\"\",\"greeting\":\"1\",\"name\":\"0\",\"usesecure\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache_time\":\"900\"}',0,''),
  (45,'Australian Parks  (2)','','',1,'syndicate',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"parks\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (46,'Australia','','',1,'right',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_random_image',1,1,'{\"type\":\"jpg\",\"folder\":\"images\\/sampledata\\/parks\\/animals\",\"link\":\"\",\"width\":\"180\",\"height\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,''),
  (47,'Latest Park Blogs','','',1,'left',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_latest',1,1,'{\"count\":\"5\",\"ordering\":\"c_dsc\",\"user_id\":\"0\",\"show_front\":\"1\",\"catid\":\"35\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'');

COMMIT;

#
# Data for the `bak_modules_menu` table  (LIMIT 0,500)
#

INSERT INTO `bak_modules_menu` (`moduleid`, `menuid`) VALUES 
  (1,101),
  (2,0),
  (3,0),
  (4,0),
  (5,0),
  (6,0),
  (7,0),
  (8,0),
  (9,0),
  (10,0),
  (11,0),
  (12,0),
  (13,0),
  (14,0),
  (15,0),
  (16,0),
  (17,101),
  (17,239),
  (18,0),
  (19,0),
  (19,105),
  (20,0),
  (22,206),
  (22,231),
  (22,234),
  (22,242),
  (22,243),
  (22,244),
  (22,296),
  (22,399),
  (22,400),
  (23,-400),
  (23,-399),
  (23,-296),
  (23,-244),
  (23,-243),
  (23,-242),
  (23,-234),
  (23,-231),
  (23,-206),
  (24,227),
  (24,229),
  (24,249),
  (24,251),
  (24,252),
  (24,253),
  (24,254),
  (24,255),
  (24,256),
  (24,257),
  (24,259),
  (24,260),
  (24,261),
  (24,262),
  (24,263),
  (24,265),
  (24,266),
  (24,267),
  (24,268),
  (24,270),
  (24,271),
  (24,272),
  (24,273),
  (24,274),
  (24,275),
  (24,276),
  (24,277),
  (24,281),
  (24,282),
  (24,283),
  (24,284),
  (24,285),
  (24,286),
  (24,300),
  (24,301),
  (24,302),
  (24,303),
  (24,304),
  (24,305),
  (24,306),
  (24,307),
  (24,309),
  (24,310),
  (24,311),
  (24,312),
  (24,313),
  (24,314),
  (24,316),
  (24,317),
  (24,318),
  (24,319),
  (24,320),
  (24,321),
  (24,322),
  (24,323),
  (24,324),
  (24,325),
  (24,326),
  (24,402),
  (24,403),
  (24,404),
  (24,405),
  (24,406),
  (24,407),
  (24,409),
  (25,294),
  (26,-400),
  (26,-399),
  (26,-296),
  (26,-244),
  (26,-243),
  (26,-242),
  (26,-234),
  (26,-231),
  (26,-206),
  (27,99),
  (27,125),
  (28,110),
  (29,-102),
  (31,109),
  (33,107),
  (34,126),
  (35,106),
  (36,104),
  (37,111),
  (38,100),
  (39,101),
  (40,113),
  (41,124),
  (42,227),
  (42,229),
  (42,249),
  (42,251),
  (42,252),
  (42,253),
  (42,254),
  (42,256),
  (42,257),
  (42,259),
  (42,260),
  (42,261),
  (42,262),
  (42,263),
  (42,265),
  (42,266),
  (42,267),
  (42,270),
  (42,271),
  (42,272),
  (42,274),
  (42,275),
  (42,402),
  (42,403),
  (42,404),
  (42,405),
  (42,406),
  (42,407),
  (42,409),
  (43,227),
  (43,229),
  (43,249),
  (43,251),
  (43,252),
  (43,253),
  (43,254),
  (43,255),
  (43,256),
  (43,257),
  (43,259),
  (43,260),
  (43,261),
  (43,262),
  (43,263),
  (43,265),
  (43,266),
  (43,267),
  (43,268),
  (43,270),
  (43,271),
  (43,272),
  (43,273),
  (43,274),
  (43,275),
  (43,276),
  (43,281),
  (43,282),
  (43,284),
  (43,285),
  (43,286),
  (43,300),
  (43,301),
  (43,302),
  (43,303),
  (43,304),
  (43,305),
  (43,306),
  (43,307),
  (43,309),
  (43,310),
  (43,311),
  (43,312),
  (43,313),
  (43,314),
  (43,316),
  (43,317),
  (43,318),
  (43,319),
  (43,320),
  (43,321),
  (43,322),
  (43,323),
  (43,324),
  (43,325),
  (43,326),
  (43,402),
  (43,403),
  (43,404),
  (43,405),
  (43,406),
  (43,407),
  (43,409),
  (44,112),
  (45,31),
  (45,34),
  (45,38),
  (45,40),
  (45,42),
  (45,43),
  (45,103),
  (46,231),
  (46,234),
  (46,242),
  (46,244),
  (46,296),
  (47,231),
  (47,234),
  (47,242),
  (47,243),
  (47,244),
  (47,296),
  (47,399),
  (47,400);

COMMIT;

#
# Data for the `bak_newsfeeds` table  (LIMIT 0,500)
#

INSERT INTO `bak_newsfeeds` (`catid`, `id`, `name`, `alias`, `link`, `filename`, `published`, `numarticles`, `cache_time`, `checked_out`, `checked_out_time`, `ordering`, `rtl`, `access`, `language`, `params`) VALUES 
  (28,1,'Joomla! Announcements','joomla-announcements','http://www.joomla.org/announcements.feed?type=rss',NULL,1,5,3600,0,'0000-00-00',1,0,1,'en_GB','{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_cat_description\":\"\",\"show_cat_items\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\"}'),
  (28,2,'New Joomla! Extensions','new-joomla-extensions','http://feeds.joomla.org/JoomlaExtensions',NULL,1,5,3600,0,'0000-00-00',1,0,1,'en_GB','{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_cat_description\":\"\",\"show_cat_items\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\"}'),
  (28,3,'Joomla! Security News','joomla-security-news','http://feeds.joomla.org/JoomlaSecurityNews',NULL,1,5,3600,0,'0000-00-00',1,0,1,'en_GB','{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_cat_description\":\"\",\"show_cat_items\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\"}'),
  (28,4,'Joomla! Connect','joomla-connect','http://feeds.joomla.org/JoomlaConnect',NULL,1,5,3600,0,'0000-00-00',1,0,1,'en_GB','{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_cat_description\":\"\",\"show_cat_items\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\"}');

COMMIT;

#
# Data for the `bak_session` table  (LIMIT 0,500)
#

INSERT INTO `bak_session` (`session_id`, `client_id`, `guest`, `time`, `data`, `userid`, `username`, `usertype`) VALUES 
  ('lgujol39gl1o71p6bfct9knl44',0,1,'1271021049','__default|a:7:{s:15:\"session.counter\";i:1;s:19:\"session.timer.start\";i:1271021048;s:18:\"session.timer.last\";i:1271021048;s:17:\"session.timer.now\";i:1271021048;s:22:\"session.client.browser\";s:88:\"Mozilla/5.0 (Windows; U; Windows NT 6.1; pt-BR; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3\";s:8:\"registry\";O:9:\"JRegistry\":1:{s:7:\"',0,'',''),
  ('9cjctu2dd6e7gkg8tt6r4fgql0',1,0,'1271021138','__default|a:8:{s:15:\"session.counter\";i:64;s:19:\"session.timer.start\";i:1271019721;s:18:\"session.timer.last\";i:1271021136;s:17:\"session.timer.now\";i:1271021138;s:22:\"session.client.browser\";s:88:\"Mozilla/5.0 (Windows; U; Windows NT 6.1; pt-BR; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3\";s:8:\"registry\";O:9:\"JRegistry\":1:{s:7:\"',42,'admin','');

COMMIT;

#
# Data for the `bak_social_comments` table  (LIMIT 0,500)
#

INSERT INTO `bak_social_comments` (`id`, `thread_id`, `user_id`, `context`, `context_id`, `trackback`, `notify`, `score`, `referer`, `page`, `name`, `url`, `email`, `subject`, `body`, `created_date`, `published`, `address`, `link`) VALUES 
  (1,1,0,'content',1,0,0,0,'','foobar','Andrew Eddie','http://www.theartofjoomla','andrew.eddie@joomla.org','I Like Joomla','I really like this Joomla! thing.','0000-00-00',0,'127.0.0.1',''),
  (2,1,0,'content',1,0,0,0,'','foobar','Andrew Eddie','http://www','andrew.eddie@joomla.org','I like chips','I like salty chips.','0000-00-00',0,'127.0.0.1','');

COMMIT;

#
# Data for the `bak_social_threads` table  (LIMIT 0,500)
#

INSERT INTO `bak_social_threads` (`id`, `context`, `context_id`, `page_url`, `page_route`, `page_title`, `created_date`, `status`, `pings`) VALUES 
  (1,'content',1,'','','','0000-00-00',0,'');

COMMIT;

#
# Data for the `bak_template_styles` table  (LIMIT 0,500)
#

INSERT INTO `bak_template_styles` (`id`, `template`, `client_id`, `home`, `title`, `params`) VALUES 
  (1,'rhuk_milkyway',0,1,'Default','{\"colorVariation\":\"blue\",\"backgroundVariation\":\"blue\",\"widthStyle\":\"fmax\"}'),
  (2,'bluestork',1,1,'Default','{\"useRoundedCorners\":\"1\",\"showSiteName\":\"0\"}'),
  (3,'atomic',0,0,'Default','{}'),
  (4,'rhuk_milkyway',0,0,'rhuk_milkyway Green','{\"colorVariation\":\"green\",\"backgroundVariation\":\"green\",\"widthStyle\":\"fmax\"}');

COMMIT;

#
# Data for the `bak_user_usergroup_map` table  (LIMIT 0,500)
#

INSERT INTO `bak_user_usergroup_map` (`user_id`, `group_id`) VALUES 
  (42,8);

COMMIT;

#
# Data for the `bak_usergroups` table  (LIMIT 0,500)
#

INSERT INTO `bak_usergroups` (`id`, `parent_id`, `lft`, `rgt`, `title`) VALUES 
  (1,0,1,20,'Public'),
  (2,1,8,19,'Registered'),
  (3,2,11,16,'Author'),
  (4,3,12,15,'Editor'),
  (5,4,13,14,'Publisher'),
  (6,1,2,7,'Manager'),
  (7,6,3,6,'Administrator'),
  (8,7,4,5,'Super Users'),
  (9,2,7,8,'Park Rangers');

COMMIT;

#
# Data for the `bak_users` table  (LIMIT 0,500)
#

INSERT INTO `bak_users` (`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `sendEmail`, `registerDate`, `lastvisitDate`, `activation`, `params`) VALUES 
  (42,'Super User','admin','julio@noix.com.br','66c2ca72b43dc7026d576445c5adf8fe:0YKNs3zZlEHMSLU50AOzEqg8TIXSKCRH','deprecated',0,1,'2010-03-03 19:52:11','2010-04-11 21:02:06','','');

COMMIT;

#
# Data for the `bak_viewlevels` table  (LIMIT 0,500)
#

INSERT INTO `bak_viewlevels` (`id`, `title`, `ordering`, `rules`) VALUES 
  (1,'Public',0,'[]'),
  (2,'Registered',1,'[2]'),
  (3,'Special',2,'[\"6\",\"7\",\"8\"]'),
  (4,'Confidential',3,'[9]');

COMMIT;

#
# Data for the `bak_weblinks` table  (LIMIT 0,500)
#

INSERT INTO `bak_weblinks` (`id`, `catid`, `sid`, `title`, `alias`, `url`, `description`, `date`, `hits`, `state`, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `access`, `params`, `language`) VALUES 
  (1,20,0,'Joomla!','joomla','http://www.joomla.org','Home of Joomla!','2005-02-14 15:19:02',3,1,0,'0000-00-00',1,0,1,1,'{\"target\":\"0\"}','en-GB'),
  (2,21,0,'php.net','php','http://www.php.net','The language that Joomla! is developed in','2004-07-07 11:33:24',6,1,0,'0000-00-00',3,0,1,1,'{}','en-GB'),
  (3,21,0,'MySQL','mysql','http://www.mysql.com','The database that Joomla! uses','2004-07-07 10:18:31',1,1,0,'0000-00-00',5,0,1,1,'{}','en-GB'),
  (4,20,0,'OpenSourceMatters','opensourcematters','http://www.opensourcematters.org','Home of OSM','2005-02-14 15:19:02',11,1,0,'0000-00-00',2,0,1,1,'{\"target\":\"0\"}','en-GB'),
  (5,21,0,'Joomla! - Forums','joomla-forums','http://forum.joomla.org','Joomla! Forums','2005-02-14 15:19:02',4,1,0,'0000-00-00',4,0,1,1,'{\"target\":\"0\"}','en-GB'),
  (6,21,0,'Ohloh Tracking of Joomla!','ohloh-tracking-of-joomla','http://www.ohloh.net/projects/20','Objective reports from Ohloh about Joomla\'s development activity. Joomla! has some star developers with serious kudos.','2007-07-19 09:28:31',1,1,0,'0000-00-00',6,0,1,1,'{\"target\":\"0\"}','en-GB'),
  (7,44,0,'Baw Baw National Park','baw-baw-national-park','http://www.parkweb.vic.gov.au/1park_display.cfm?park=44','Park of the Austalian Alps National Parks system, Baw Baw  features sub alpine vegetation, beautiful views, and opportunities for hiking, skiing and other outdoor activities.','0000-00-00',0,1,0,'0000-00-00',7,0,1,1,'{\"target\":\"0\"}',''),
  (8,44,0,'Kakadu','kakadu','http://www.environment.gov.au/parks/kakadu/index.html','Kakadu is known for both its cultural heritage and its natural features. It is one of a small number of places listed as World Heritage Places for both reasons. Extensive rock art is found there.','0000-00-00',0,1,0,'0000-00-00',8,0,1,1,'{\"target\":\"0\"}',''),
  (9,44,0,'Pulu Keeling','pulu-keeling','http://www.environment.gov.au/parks/cocos/index.html','Located on an atoll 2000 kilometers north of Perth, Pulu Keeling is Australia\'s smallest national park.','0000-00-00',0,1,0,'0000-00-00',9,0,1,1,'{\"target\":\"0\"}','');

COMMIT;

#
# Data for the `jos_assets` table  (LIMIT 0,500)
#

INSERT INTO `jos_assets` (`id`, `parent_id`, `lft`, `rgt`, `level`, `name`, `title`, `rules`) VALUES 
  (1,0,1,140,0,'root.1','Root Asset','{\"core.login.site\":{\"6\":1,\"2\":1},\"core.login.admin\":{\"6\":1},\"core.admin\":{\"8\":1},\"core.manage\":{\"7\":1,\"10\":1},\"core.create\":{\"6\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1},\"core.edit.state\":{\"6\":1}}'),
  (2,1,2,3,1,'com_admin','com_admin','{}'),
  (3,1,4,5,1,'com_banners','com_banners','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (4,1,6,7,1,'com_cache','com_cache','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),
  (5,1,8,9,1,'com_checkin','com_checkin','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),
  (6,1,10,11,1,'com_config','com_config','{}'),
  (7,1,12,15,1,'com_contact','com_contact','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (8,1,16,33,1,'com_content','com_content','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1,\"5\":1},\"core.delete\":[],\"core.edit\":{\"4\":1},\"core.edit.state\":{\"5\":1}}'),
  (9,1,34,35,1,'com_cpanel','com_cpanel','{}'),
  (10,1,36,37,1,'com_installer','com_installer','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1},\"core.create\":[],\"core.delete\":[],\"core.edit.state\":[]}'),
  (11,1,38,39,1,'com_languages','com_languages','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (12,1,40,41,1,'com_login','com_login','{}'),
  (13,1,42,43,1,'com_mailto','com_mailto','{}'),
  (14,1,44,45,1,'com_massmail','com_massmail','{}'),
  (15,1,46,47,1,'com_media','com_media','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1,\"4\":1,\"5\":1},\"core.delete\":{\"5\":1},\"core.edit\":[],\"core.edit.state\":[]}'),
  (16,1,48,49,1,'com_menus','com_menus','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (17,1,50,51,1,'com_messages','com_messages','{}'),
  (18,1,52,53,1,'com_modules','com_modules','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (19,1,54,57,1,'com_newsfeeds','com_newsfeeds','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (20,1,58,59,1,'com_plugins','com_plugins','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (21,1,60,61,1,'com_redirect','com_redirect','{\"core.admin\":{\"7\":1},\"core.manage\":[]}'),
  (22,1,62,63,1,'com_search','com_search','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1}}'),
  (23,1,64,65,1,'com_templates','com_templates','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (24,1,66,67,1,'com_users','com_users','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (25,1,68,75,1,'com_weblinks','com_weblinks','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (26,1,76,77,1,'com_wrapper','com_wrapper','{}'),
  (27,8,17,18,2,'com_content.article.1','Joomla!','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (28,1,84,85,1,'com_content.category.11','News','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (29,1,70,83,1,'com_content.category.12','Countries','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (30,29,71,82,2,'com_content.category.23','Australia',''),
  (31,30,72,73,3,'com_content.category.24','Queensland',''),
  (32,30,74,79,3,'com_content.category.25','Tasmania',''),
  (33,31,75,76,4,'com_content.article.2','Great Barrier Reef',''),
  (34,32,77,78,4,'com_content.article.3','Cradle Mountain-Lake St Clair National Park','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (35,25,59,64,2,'com_weblinks.category.20','Uncategorised Weblinks',''),
  (36,35,60,63,3,'com_weblinks.category.21','Joomla! Specific Links',''),
  (37,36,61,62,4,'com_weblinks.category.22','Other Resources',''),
  (39,7,13,14,2,'com_contact.category.26','Contacts','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (40,1,68,69,1,'com_banners.category.27','Banners',''),
  (41,19,45,46,2,'com_newsfeeds.category.28','News Feeds','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (42,60,91,92,2,'com_content.category.38','Photo Gallery','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (43,1,60,61,1,'com_content.article.56','Koala','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (44,1,62,63,1,'com_content.article.57','Wobbegone','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (45,1,64,65,1,'com_content.article.58','Phyllopteryx','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (46,1,66,67,1,'com_content.article.59','Spotted Quoll','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (47,1,68,69,1,'com_content.article.60','Pinnacles','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (48,1,226,271,1,'com_content.article.61','Ormiston Pound','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (49,1,162,163,1,'com_content.article.62','Blue Mountain Rain Forest','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (50,1,164,165,1,'com_content.article.63','Cradle Mountain','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (51,30,80,81,3,'com_content.article.9','Australian Parks','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (52,54,88,89,3,'com_content.article.64','First Blog Entry','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (53,1,166,167,1,'com_content.article.65','Second Blog Post','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (54,60,87,90,2,'com_content.category.35','Park Blog','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (55,7,15,16,2,'com_contact.category.45','Parks Site','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (56,1,180,181,1,'com_contact.category.47','Staff','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (57,1,182,183,1,'com_contact.category.48','Suppliers','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (58,1,184,269,1,'com_contact.category.49','Fruit','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (59,1,226,227,1,'com_content.article.7','Sample Sites','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (60,1,86,93,1,'com_content.category.50','Park Site','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (61,1,94,159,1,'com_content.category.32','Extensions','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (62,152,116,131,3,'com_content.article.66','Articles Modules','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (63,152,132,133,3,'com_content.article.67','User Modules','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (64,152,134,135,3,'com_content.article.68','Display Modules','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (65,152,136,137,3,'com_content.article.69','Utility Modules','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (66,152,138,139,3,'com_content.article.70','Menus','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (67,1,290,291,1,'com_content.article.71','Custom HTML','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (68,1,292,293,1,'com_content.article.72','Weblinks Module','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (69,1,294,295,1,'com_content.article.73','Breadcrumbs','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (70,1,296,297,1,'com_content.article.37','Statistics','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (71,1,298,299,1,'com_content.article.38','Syndicate','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (72,1,300,301,1,'com_content.article.26','Site Map','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (73,152,142,143,3,'com_content.article.27','Archive Module','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (74,1,302,303,1,'com_content.article.41','Banner','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (75,1,304,305,1,'com_content.article.33','Feed Display','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (76,152,140,141,3,'com_content.article.43','Footer','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (77,1,306,307,1,'com_content.article.28','Latest Articles Module','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (78,1,308,309,1,'com_content.article.42','Login','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (79,1,310,311,1,'com_content.article.40','Menu','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (80,1,312,313,1,'com_content.article.45','Modules','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (81,1,314,315,1,'com_content.article.30','Most Popular Articles','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (82,1,316,317,1,'com_content.article.32','Newest Users','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (83,1,318,319,1,'com_content.article.34','News Flash','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (84,1,320,321,1,'com_content.article.35','Random Image','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (85,1,322,323,1,'com_content.article.55','Related Items','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (86,1,324,325,1,'com_content.article.36','Search','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (87,58,185,186,2,'com_contact.category.51','A','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (88,58,187,188,2,'com_contact.category.52','B','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (89,58,189,190,2,'com_contact.category.53','C','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (90,58,191,192,2,'com_contact.category.54','D','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (91,58,193,194,2,'com_contact.category.55','E','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (92,58,195,196,2,'com_contact.category.56','F','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (93,58,197,202,2,'com_contact.category.57','G','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (94,58,203,204,2,'com_contact.category.58','H','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (95,58,205,206,2,'com_contact.category.59','J','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (96,58,207,208,2,'com_contact.category.60','K','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (97,58,209,242,2,'com_contact.category.61','L','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (98,58,243,244,2,'com_contact.category.62','M','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (99,58,245,246,2,'com_contact.category.63','O','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (100,58,247,248,2,'com_contact.category.64','P','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (101,58,249,250,2,'com_contact.category.65','Q','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (102,58,251,252,2,'com_contact.category.66','R','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (103,58,253,254,2,'com_contact.category.67','S','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (104,58,255,256,2,'com_contact.category.68','T','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (105,58,257,258,2,'com_contact.category.69','U','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (106,58,259,260,2,'com_contact.category.70','V','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (107,58,261,262,2,'com_contact.category.71','W','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (108,58,263,264,2,'com_contact.category.72','X','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (109,58,265,266,2,'com_contact.category.73','Y','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (110,58,267,268,2,'com_contact.category.74','Z','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (111,1,116,127,3,'com_content.category.75','Fruit Shop Site','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (112,111,117,118,4,'com_content.article.79','Fruit Shop','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (113,111,119,124,4,'com_content.category.76','Growers','{\"core.create\":[],\"core.delete\":[],\"core.edit\":{\"10\":1},\"core.edit.state\":[]}'),
  (114,1,128,129,3,'com_content.article.6','Professionals','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (115,113,120,121,5,'com_content.article.80','Happy Orange Orchard','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (116,113,122,123,5,'com_content.article.81','Wonderful Watermelon','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (117,111,125,126,4,'com_content.article.82','Directions','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (147,61,101,112,2,'com_content.category.41','Templates','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (119,147,104,105,3,'com_content.article.76','Atomic',''),
  (120,1,210,211,1,'com_content.article.48','Authentication',''),
  (121,61,95,96,2,'com_content.article.8','Components','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (122,154,152,153,3,'com_content.article.13','Contact','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (123,1,212,213,1,'com_content.article.10','Content','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (124,1,214,215,1,'com_content.article.49','Content',''),
  (125,1,216,217,1,'com_content.article.50','Editors',''),
  (126,1,218,219,1,'com_content.article.51','Editors-xtd',''),
  (127,1,220,221,1,'com_content.article.4','Joomla! Beginners',''),
  (128,1,222,223,1,'com_content.article.5','Upgraders',''),
  (129,1,224,225,1,'com_content.article.22','The Joomla! Community','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (130,1,228,229,1,'com_content.article.11','Weblinks','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (131,1,230,231,1,'com_content.article.12','News Feeds','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (132,154,156,157,3,'com_content.article.14','Users','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (133,61,97,98,2,'com_content.article.15','Modules','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (148,147,102,103,3,'com_content.article.74','Beez 2','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (136,1,232,233,1,'com_content.article.52','Search','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (137,1,234,235,1,'com_content.article.23','The Joomla! Project','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (138,147,110,111,3,'com_content.article.25','Typography','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (139,1,236,237,1,'com_content.article.47','System','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (154,61,149,158,2,'com_content.category.39','Components','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (141,61,99,100,2,'com_content.article.17','Languages','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (142,154,154,155,3,'com_content.article.20','Search','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (143,1,238,239,1,'com_content.article.24','Using Joomla!','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (144,153,146,147,3,'com_content.article.53','User','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (145,1,240,241,1,'com_content.article.54','What\'s New in 1.5?','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (146,154,150,151,3,'com_content.article.83','Administrator Components','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (149,147,106,107,3,'com_content.article.78','Milky Way',''),
  (150,147,108,109,3,'com_content.article.75','Template 2',''),
  (151,61,113,114,2,'com_content.category.42','Languages','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (152,61,115,144,2,'com_content.category.40','Modules','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (153,61,145,148,2,'com_content.category.43','Plugins','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (155,8,33,34,2,'com_content.category.77','ccc','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (156,8,35,36,2,'com_content.category.29','Sample Data-Content','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (157,1,198,199,1,'com_content.article.84','Getting help','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (158,1,200,201,1,'com_content.article.85','Getting started','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
  (159,152,144,145,3,'com_content.article.86','Article Categories','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}');

COMMIT;

#
# Data for the `jos_banner_clients` table  (LIMIT 0,500)
#

INSERT INTO `jos_banner_clients` (`id`, `name`, `contact`, `email`, `extrainfo`, `state`, `checked_out`, `checked_out_time`, `metakey`, `own_prefix`, `metakey_prefix`, `purchase_type`, `track_clicks`, `track_impressions`) VALUES 
  (1,'Joomla!','Administrator','email@email.com','',1,0,'0000-00-00','',0,'',-1,-1,-1);

COMMIT;

#
# Data for the `jos_banners` table  (LIMIT 0,500)
#

INSERT INTO `jos_banners` (`id`, `cid`, `type`, `name`, `alias`, `imptotal`, `impmade`, `clicks`, `clickurl`, `state`, `catid`, `description`, `custombannercode`, `sticky`, `ordering`, `metakey`, `params`, `own_prefix`, `metakey_prefix`, `purchase_type`, `track_clicks`, `track_impressions`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `reset`, `created`, `language`) VALUES 
  (1,1,0,'OSM 1','osm-1',0,43,0,'http://www.opensourcematters.org',-2,27,'','',0,0,'','{\"alt\":\"Open Source Matters\",\"imageurl\":\"images/banners/osmbanner1.png\"}',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-10-10 13:52:59','en-GB'),
  (2,1,0,'Shop 1','shop-1',0,15,0,'',-2,30,'','',0,0,'','{\"imageurl\":\"images/banners/shop-ad-books.jpg\"}',0,'',-1,0,0,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2010-01-12 00:25:24','en-GB'),
  (3,1,0,'Shop 2','shop-2',0,0,0,'',-2,30,'','',0,0,'','{\"alt\":\"Joomla! Books\",\"imageurl\":\"images/banners/shop-ad.jpg\"}',0,'',-1,0,0,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2010-01-12 00:35:30','en-GB'),
  (4,1,0,'Shop 2','shop-2',0,6,1,'http://shop.joomla.org',-2,30,'','',0,0,'','{}',0,'',-1,0,0,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2010-01-12 00:55:54','en-GB'),
  (5,1,1,'Joomlabootcamp Manila PH - Jun 2008','joomlabootcamp-manila-ph-jun-2008',0,151,0,'http://www.flickr.com/photos/joomlatools/2682259339/',2,30,'','<img src=\"http://farm4.static.flickr.com/3013/2682259339_602bb012d7_m.jpg\" alt=\"Joomlabootcamp Manila PH - Jun 2008\" />',0,2,'joomla,joomlatools,joomlacamp,joomlabootcamp,manila,philippines,asti,johanjanssens','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2008-06-16 09:44:24',''),
  (6,1,1,'Joomladay 2008 Utrecht Wilco Jansen','joomladay-2008-utrecht-wilco-jansen',0,74,0,'http://www.flickr.com/photos/25382231@N07/2391550429/',2,30,'','<img src=\"http://farm3.static.flickr.com/2015/2391550429_3b2675fe77_m.jpg\" alt=\"Joomladay 2008 Utrecht Wilco Jansen\" />',0,3,'joomladay,2008,utrecht,holland,joomla','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2008-04-06 04:35:54',''),
  (7,1,1,'Amy Stephen','amy-stephen',0,162,0,'http://www.flickr.com/photos/hagengraf/430850297/',2,30,'','<img src=\"http://farm1.static.flickr.com/177/430850297_40e5caf810_m.jpg\" alt=\"Amy Stephen\" />',0,1,'oscms2007,sanfrancisco,california,yahoo,joomla','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2007-03-23 21:06:57',''),
  (8,1,1,'Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009','ryan-oziimek-e-julio-pontes-noix-joomla-day-brasil-2009',0,85,0,'http://www.flickr.com/photos/marciookabe/3919730061/',2,30,'','<img src=\"http://farm3.static.flickr.com/2588/3919730061_9cbc82ed4f_m.jpg\" alt=\"Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009\" />',0,4,'jdbr09,jdaybr09,joomla,day,brasil,2009,oficinas','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-12 17:57:53',''),
  (9,1,1,'Joomladay NL 2009','joomladay-nl-2009',0,22,0,'http://www.flickr.com/photos/nooku/3647314709/',-2,30,'Johan Janssens explaining RESTfull URL structures used by the Nooku Framework. Yes ... boats is the new theme.','<img src=\"http://farm4.static.flickr.com/3353/3647314709_7efc8ee05f_m.jpg\" alt=\"Joomladay NL 2009\" />',0,0,'nooku,joomla,joomladay,nieuwegein,jd09nl,johanjanssens','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-06-12 00:00:37',''),
  (10,1,1,'Amy Stephen','amy-stephen',0,162,0,'http://www.flickr.com/photos/hagengraf/430850297/',1,30,'','<img src=\"http://farm1.static.flickr.com/177/430850297_40e5caf810_m.jpg\" alt=\"Amy Stephen\" />',0,5,'oscms2007,sanfrancisco,california,yahoo,joomla','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2007-03-23 21:06:57',''),
  (11,1,1,'Joomladay 2008 Utrecht Wilco Jansen','joomladay-2008-utrecht-wilco-jansen',0,28,0,'http://www.flickr.com/photos/25382231@N07/2392372478/',1,30,'','<img src=\"http://farm3.static.flickr.com/2282/2392372478_8bf78da0d1_m.jpg\" alt=\"Joomladay 2008 Utrecht Wilco Jansen\" />',0,8,'joomladay,2008,utrecht,holland,joomla','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2008-04-06 04:30:31',''),
  (12,1,1,'Malcolm Tredinnick, Andrew Eddie, Tim Lucas','malcolm-tredinnick-andrew-eddie-tim-lucas',0,21,0,'http://www.flickr.com/photos/paulmccarthy/2889338845/',1,30,'Framework panel: \nPython\nPHP/Joomla\nRuby on Rails.\nJava panelist was absent.','<img src=\"http://farm4.static.flickr.com/3275/2889338845_02112c5567_m.jpg\" alt=\"Malcolm Tredinnick, Andrew Eddie, Tim Lucas\" />',0,10,'wds08,web,directions,south,sydney,australia,september,2008','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2008-09-26 11:48:31',''),
  (13,1,1,'Louis hops into the limo','louis-hops-into-the-limo',0,72,0,'http://www.flickr.com/photos/picnet/329739384/',1,30,'','<img src=\"http://farm1.static.flickr.com/123/329739384_8e3eb6cf13_m.jpg\" alt=\"Louis hops into the limo\" />',0,9,'joomla,google','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2006-12-21 22:32:17',''),
  (14,1,1,'Joomla!Day DE 2006','joomladay-de-2006',0,600,0,'http://www.flickr.com/photos/joomla/319511843/',1,30,'Joomla!Day Germany, Bonn, 2006','<img src=\"http://farm1.static.flickr.com/141/319511843_53ee550d61_m.jpg\" alt=\"Joomla!Day DE 2006\" />',0,7,'joomla,joomladay,joomladay2006,joomladayde2006','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2006-09-30 17:38:43',''),
  (15,1,1,'Nooku Code Jam Gent BE - May 2009','nooku-code-jam-gent-be-may-2009',0,38,0,'http://www.flickr.com/photos/nooku/3611768883/',1,30,'','<img src=\"http://farm3.static.flickr.com/2482/3611768883_d78189f78d_m.jpg\" alt=\"Nooku Code Jam Gent BE - May 2009\" />',0,11,'nooku,nookuframework,php,cms,joomla,gent,belgium,delius,johanjanssens,ncj09gent','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-05-15 00:00:01',''),
  (16,1,1,'Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009','ryan-oziimek-e-julio-pontes-noix-joomla-day-brasil-2009',0,28,0,'http://www.flickr.com/photos/marciookabe/3920511354/',-2,30,'','<img src=\"http://farm3.static.flickr.com/2529/3920511354_e353fe58ca_m.jpg\" alt=\"Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009\" />',0,0,'jdbr09,jdaybr09,joomla,day,brasil,2009,oficinas','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-12 17:57:40',''),
  (17,1,1,'Joomla! Day Germany 2009 in Bad Nauheim','joomla-day-germany-2009-in-bad-nauheim',0,21,0,'http://www.flickr.com/photos/achimfischer/3992677006/',1,30,'','<img src=\"http://farm3.static.flickr.com/2435/3992677006_1839a03aea_m.jpg\" alt=\"Joomla! Day Germany 2009 in Bad Nauheim\" />',0,6,'raw,blog,joomla,joomladay','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-26 00:10:00',''),
  (18,1,1,'Joomla!Day DE 2006','joomladay-de-2006',0,601,0,'http://www.flickr.com/photos/joomla/319511843/',-2,30,'Joomla!Day Germany, Bonn, 2006','<img src=\"http://farm1.static.flickr.com/141/319511843_53ee550d61_m.jpg\" alt=\"Joomla!Day DE 2006\" />',0,0,'joomla,joomladay,joomladay2006,joomladayde2006','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2006-09-30 17:38:43',''),
  (19,1,1,'Nooku Code Jam Gent BE - May 2009','nooku-code-jam-gent-be-may-2009',0,34,0,'http://www.flickr.com/photos/nooku/3611768883/',-2,30,'','<img src=\"http://farm3.static.flickr.com/2482/3611768883_d78189f78d_m.jpg\" alt=\"Nooku Code Jam Gent BE - May 2009\" />',0,0,'nooku,nookuframework,php,cms,joomla,gent,belgium,delius,johanjanssens,ncj09gent','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-05-15 00:00:01',''),
  (20,1,1,'Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009','ryan-oziimek-e-julio-pontes-noix-joomla-day-brasil-2009',0,29,0,'http://www.flickr.com/photos/marciookabe/3920511354/',-2,30,'','<img src=\"http://farm3.static.flickr.com/2529/3920511354_e353fe58ca_m.jpg\" alt=\"Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009\" />',0,0,'jdbr09,jdaybr09,joomla,day,brasil,2009,oficinas','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-12 17:57:40',''),
  (21,1,1,'Joomla! Day Germany 2009 in Bad Nauheim','joomla-day-germany-2009-in-bad-nauheim',0,22,0,'http://www.flickr.com/photos/achimfischer/3992677006/',-2,30,'','<img src=\"http://farm3.static.flickr.com/2435/3992677006_1839a03aea_m.jpg\" alt=\"Joomla! Day Germany 2009 in Bad Nauheim\" />',0,0,'raw,blog,joomla,joomladay','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-26 00:10:00',''),
  (22,1,1,'Joomla!Day DE 2006','Joomla!Day DE 2006',0,600,0,'http://www.flickr.com/photos/joomla/319511843/',0,30,'Joomla!Day Germany, Bonn, 2006','<img src=\"http://farm1.static.flickr.com/141/319511843_53ee550d61_m.jpg\" alt=\"Joomla!Day DE 2006\" />',0,0,'joomla,joomladay,joomladay2006,joomladayde2006','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2006-09-30 17:38:43',''),
  (23,1,1,'Nooku Code Jam Gent BE - May 2009','Nooku Code Jam Gent BE - May 2009',0,35,0,'http://www.flickr.com/photos/nooku/3611768883/',0,30,'','<img src=\"http://farm3.static.flickr.com/2482/3611768883_d78189f78d_m.jpg\" alt=\"Nooku Code Jam Gent BE - May 2009\" />',0,0,'nooku,nookuframework,php,cms,joomla,gent,belgium,delius,johanjanssens,ncj09gent','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-05-15 00:00:01',''),
  (24,1,1,'Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009','Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009',0,29,0,'http://www.flickr.com/photos/marciookabe/3920511354/',0,30,'','<img src=\"http://farm3.static.flickr.com/2529/3920511354_e353fe58ca_m.jpg\" alt=\"Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009\" />',0,0,'jdbr09,jdaybr09,joomla,day,brasil,2009,oficinas','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-12 17:57:40',''),
  (25,1,1,'Joomla! Day Germany 2009 in Bad Nauheim','Joomla! Day Germany 2009 in Bad Nauheim',0,22,0,'http://www.flickr.com/photos/achimfischer/3992677006/',0,30,'','<img src=\"http://farm3.static.flickr.com/2435/3992677006_1839a03aea_m.jpg\" alt=\"Joomla! Day Germany 2009 in Bad Nauheim\" />',0,0,'raw,blog,joomla,joomladay','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-26 00:10:00',''),
  (26,1,1,'Joomla!Day DE 2006','Joomla!Day DE 2006',0,600,0,'http://www.flickr.com/photos/joomla/319511843/',0,30,'Joomla!Day Germany, Bonn, 2006','<img src=\"http://farm1.static.flickr.com/141/319511843_53ee550d61_m.jpg\" alt=\"Joomla!Day DE 2006\" />',0,0,'joomla,joomladay,joomladay2006,joomladayde2006','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2006-09-30 17:38:43',''),
  (27,1,1,'Nooku Code Jam Gent BE - May 2009','Nooku Code Jam Gent BE - May 2009',0,35,0,'http://www.flickr.com/photos/nooku/3611768883/',0,30,'','<img src=\"http://farm3.static.flickr.com/2482/3611768883_d78189f78d_m.jpg\" alt=\"Nooku Code Jam Gent BE - May 2009\" />',0,0,'nooku,nookuframework,php,cms,joomla,gent,belgium,delius,johanjanssens,ncj09gent','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-05-15 00:00:01',''),
  (28,1,1,'Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009','Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009',0,29,0,'http://www.flickr.com/photos/marciookabe/3920511354/',0,30,'','<img src=\"http://farm3.static.flickr.com/2529/3920511354_e353fe58ca_m.jpg\" alt=\"Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009\" />',0,0,'jdbr09,jdaybr09,joomla,day,brasil,2009,oficinas','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-12 17:57:40',''),
  (29,1,1,'Joomla! Day Germany 2009 in Bad Nauheim','Joomla! Day Germany 2009 in Bad Nauheim',0,22,0,'http://www.flickr.com/photos/achimfischer/3992677006/',0,30,'','<img src=\"http://farm3.static.flickr.com/2435/3992677006_1839a03aea_m.jpg\" alt=\"Joomla! Day Germany 2009 in Bad Nauheim\" />',0,0,'raw,blog,joomla,joomladay','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-26 00:10:00',''),
  (30,1,1,'Joomla!Day DE 2006','Joomla!Day DE 2006',0,600,0,'http://www.flickr.com/photos/joomla/319511843/',0,30,'Joomla!Day Germany, Bonn, 2006','<img src=\"http://farm1.static.flickr.com/141/319511843_53ee550d61_m.jpg\" alt=\"Joomla!Day DE 2006\" />',0,0,'joomla,joomladay,joomladay2006,joomladayde2006','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2006-09-30 17:38:43',''),
  (31,1,1,'Nooku Code Jam Gent BE - May 2009','Nooku Code Jam Gent BE - May 2009',0,35,0,'http://www.flickr.com/photos/nooku/3611768883/',0,30,'','<img src=\"http://farm3.static.flickr.com/2482/3611768883_d78189f78d_m.jpg\" alt=\"Nooku Code Jam Gent BE - May 2009\" />',0,0,'nooku,nookuframework,php,cms,joomla,gent,belgium,delius,johanjanssens,ncj09gent','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-05-15 00:00:01',''),
  (32,1,1,'Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009','Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009',0,29,0,'http://www.flickr.com/photos/marciookabe/3920511354/',0,30,'','<img src=\"http://farm3.static.flickr.com/2529/3920511354_e353fe58ca_m.jpg\" alt=\"Ryan Oziimek e Júlio Pontes (NOIX) - Joomla! Day Brasil 2009\" />',0,0,'jdbr09,jdaybr09,joomla,day,brasil,2009,oficinas','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-12 17:57:40',''),
  (33,1,1,'Joomla! Day Germany 2009 in Bad Nauheim','Joomla! Day Germany 2009 in Bad Nauheim',0,22,0,'http://www.flickr.com/photos/achimfischer/3992677006/',0,30,'','<img src=\"http://farm3.static.flickr.com/2435/3992677006_1839a03aea_m.jpg\" alt=\"Joomla! Day Germany 2009 in Bad Nauheim\" />',0,0,'raw,blog,joomla,joomladay','',0,'',-1,-1,-1,0,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','2009-09-26 00:10:00','');

COMMIT;

#
# Data for the `jos_categories` table  (LIMIT 0,500)
#

INSERT INTO `jos_categories` (`id`, `asset_id`, `parent_id`, `lft`, `rgt`, `level`, `path`, `extension`, `title`, `alias`, `note`, `description`, `published`, `checked_out`, `checked_out_time`, `access`, `params`, `metadesc`, `metakey`, `metadata`, `created_user_id`, `created_time`, `modified_user_id`, `modified_time`, `hits`, `language`) VALUES 
  (1,0,0,0,119,0,'','system','ROOT','root','','',1,0,'0000-00-00',1,'{}','','','',0,'2010-05-09 18:51:39',0,'0000-00-00',0,'*'),
  (11,28,29,36,37,2,'sample-data-content/news','com_content','News','news','','The top articles category.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:25:26',0,'0000-00-00',0,'en-GB'),
  (12,29,29,28,35,2,'sample-data-content/countries','com_content','Countries','countries','','The latest news from the Joomla! Team',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:25:26',0,'0000-00-00',0,'en-GB'),
  (20,35,1,1,8,1,'sample-data-weblinks','com_weblinks','Sample Data Weblinks','sample-data-weblinks','','<p>The sample weblinks category.</p>',1,0,'0000-00-00',1,'{}','','','',0,'2010-03-26 10:22:23',0,'0000-00-00',0,'en-GB'),
  (21,36,20,2,5,2,'sample-data-weblinks/joomla-specific-links','com_weblinks','Joomla! Specific Links','joomla-specific-links','','<p>A selection of links that are all related to the Joomla! Project.</p>',1,0,'0000-00-00',1,'{}','','','',0,'2010-03-26 10:22:23',0,'0000-00-00',0,'en-GB'),
  (22,37,21,3,4,3,'sample-data-weblinks/joomla-specific-links/other-resources','com_weblinks','Other Resources','other-resources','','',1,0,'0000-00-00',1,'{}','','','',0,'2010-01-24 11:23:53',0,'0000-00-00',0,'en-GB'),
  (23,30,12,29,34,3,'sample-data-content/countries/australia','com_content','Australia','australia','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 11:23:57',0,'0000-00-00',0,'*'),
  (24,31,23,30,31,4,'sample-data-content/countries/australia/queensland','com_content','Queensland','queensland','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 11:23:57',0,'0000-00-00',0,'*'),
  (25,32,23,32,33,4,'sample-data-content/countries/australia/tasmania','com_content','Tasmania','tasmania','','',1,0,'0000-00-00',1,'','','','',0,'2010-01-24 11:23:57',0,'0000-00-00',0,'*'),
  (26,38,1,53,112,1,'contacts','com_contact','Contacts','contacts','','',1,0,'0000-00-00',1,'','','','',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (27,40,1,113,114,1,'banners','com_banners','Banners','banners','','',1,0,'0000-00-00',1,'','','','',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (28,41,1,115,116,1,'news-feeds','com_newsfeeds','News Feeds','news-feeds','','',1,0,'0000-00-00',1,'','','','',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (29,156,1,9,52,1,'sample-data-content','com_content','Sample Data-Content','sample-data-content','','',1,0,'0000-00-00',1,'{\"target\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-04-30 20:34:29',0,'0000-00-00',0,'*'),
  (30,0,1,117,118,1,'sample-data-banners','com_banners','Sample Data-Banners','sample-data-banners','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (31,0,29,10,27,2,'sample-data-content/joomla','com_content','Joomla!','joomla','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:23:53',0,'0000-00-00',0,'*'),
  (32,61,31,11,22,3,'sample-data-content/joomla/extensions','com_content','Extensions','extensions','','<p>The Joomla! content management system creates webpages using extensions. There are 5 basic types of extensions: components, modules, templates, languages, and plugins. Your website includes the extensions you need to create a basic website in English, but thousands of additional extensions of all types are available. The Joomla! Extensions Directory is the largest directory of Joomla! extensions.</p>',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-05-01 12:45:46',0,'0000-00-00',0,'*'),
  (33,0,31,23,24,3,'sample-data-content/joomla/the-joomla-project','com_content','The Joomla! Project','the-joomla-project','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:23:53',0,'0000-00-00',0,'*'),
  (34,0,31,25,26,3,'sample-data-content/joomla/the-joomla-community','com_content','The Joomla! Community','the-joomla-community','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:23:53',0,'0000-00-00',0,'*'),
  (35,54,50,39,40,3,'sample-data-content/park-site/park-blog','com_content','Park Blog','park-blog','','Here is where I will blog all about the parks of Australia. Please comment and give me ideas of what parks you have enjoyed.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:25:26',0,'0000-00-00',0,'*'),
  (36,0,38,42,43,4,'sample-data-content/park-site/photo-gallery/scenery','com_content','Scenery','scenery','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-03-22 00:01:10',0,'0000-00-00',0,'*'),
  (37,0,38,44,45,4,'sample-data-content/park-site/photo-gallery/animals','com_content','Animals','animals','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-03-22 00:01:10',0,'0000-00-00',0,'*'),
  (38,42,50,41,46,3,'sample-data-content/park-site/photo-gallery','com_content','Photo Gallery','photo-gallery','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:25:26',0,'0000-00-00',0,'*'),
  (39,154,32,12,13,4,'sample-data-content/joomla/extensions/components','com_content','Components','components','','<p>Components are larger extensions that produce the major content for your site. Each component has one or more \"views\" that control how content is displayed.In the Joomla! administrator there are additional extensions suce as Menus, Redirection, and the extension managers.</p>',1,0,'0000-00-00',1,'{\"target\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-04-30 20:41:19',0,'0000-00-00',0,'*'),
  (40,152,32,14,15,4,'sample-data-content/joomla/extensions/modules','com_content','Modules','modules','','Modules are small blocks of content that can be displayed in positions on a web page. The menus on this site are displayed in modules. The core of Joomla! includes 17 separate modules ranging from login to search to random images. Each module has a name that starts mod_ but when it displays it has a title. In the descriptions in this section, the titles are the same as the names.',1,0,'0000-00-00',1,'{\"target\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-04-30 18:58:30',0,'0000-00-00',0,'*'),
  (41,147,32,16,17,4,'sample-data-content/joomla/extensions/templates','com_content','Templates','templates','','Templates give your site its look and feel. They determine layout, colors, type faces, graphics and other aspects of design that make your site unique.\r\nYour installation of Joomla comes prepackaged with four templates.',1,0,'0000-00-00',1,'{\"target\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-04-30 16:13:12',0,'0000-00-00',0,'*'),
  (42,151,32,18,19,4,'sample-data-content/joomla/extensions/languages','com_content','Languages','languages','','\r\nJoomla! installs in English, but there are translations of the interfaces, sample data and help screens are available in dozens of languages.\r\nTranslation information\r\nIf there is no language pack available for your language, instructions are available for creating your own translation, which you can also contribute to the community by starting a translation team to create an accredited translation.\r\nTranslations are installed the the extensions manager in the site administrator and then managed using the language manager.\r\n\r\n',1,0,'0000-00-00',1,'{\"target\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-04-30 19:44:04',0,'0000-00-00',0,'*'),
  (43,153,32,20,21,4,'sample-data-content/joomla/extensions/plugins','com_content','Plugins','plugins','','Plugins are small task oriented extensions that enhance the Joomla! framework.\r\nSome are associated with particular extensions and others, such as editors, are used across all of Joomla!. Most beginning users do not need to change any of the plugins that install with Joomla!.\r\n ',1,0,'0000-00-00',1,'{\"target\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-04-30 19:43:02',0,'0000-00-00',0,'*'),
  (44,0,20,6,7,2,'sample-data-weblinks/parks-links','com_weblinks','Parks Links','parks-links','','<p>Here are links to some of my favorite parks.</p>',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:23:53',0,'0000-00-00',0,'*'),
  (45,55,26,54,55,2,'contacts/parks-site','com_contact','Parks Site','parks-site','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (46,0,26,56,111,2,'contacts/shop-site','com_contact','Shop Site','shop-site','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (47,56,46,57,58,3,'contacts/shop-site/staff','com_contact','Staff','staff','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (48,57,46,59,60,3,'contacts/shop-site/suppliers','com_contact','Suppliers','suppliers','','We get our fruit from the very best growers.',-2,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-03-26 10:29:33',0,'0000-00-00',0,'*'),
  (49,58,46,61,110,3,'contacts/shop-site/fruit','com_contact','Fruit','fruit','','Our directory of information about different kinds of fruit.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (50,60,29,38,47,2,'sample-data-content/park-site','com_content','Park Site','park-site','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',0,'2010-01-24 11:25:26',0,'0000-00-00',0,'*'),
  (51,87,49,62,63,4,'contacts/shop-site/fruit/a','com_contact','A','a','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (52,88,49,64,65,4,'contacts/shop-site/fruit/b','com_contact','B','b','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (53,89,49,66,67,4,'contacts/shop-site/fruit/c','com_contact','C','c','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (54,90,49,68,69,4,'contacts/shop-site/fruit/d','com_contact','D','d','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (55,91,49,70,71,4,'contacts/shop-site/fruit/e','com_contact','E','e','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (56,92,49,72,73,4,'contacts/shop-site/fruit/f','com_contact','F','f','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (57,93,49,74,75,4,'contacts/shop-site/fruit/g','com_contact','G','g','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (58,94,49,76,77,4,'contacts/shop-site/fruit/h','com_contact','H','h','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (59,95,49,78,79,4,'contacts/shop-site/fruit/j','com_contact','J','j','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (60,96,49,80,81,4,'contacts/shop-site/fruit/k','com_contact','K','k','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (61,97,49,82,83,4,'contacts/shop-site/fruit/l','com_contact','L','l','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (62,98,49,84,85,4,'contacts/shop-site/fruit/m','com_contact','M','m','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (63,99,49,86,87,4,'contacts/shop-site/fruit/o','com_contact','O','o','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (64,100,49,88,89,4,'contacts/shop-site/fruit/p','com_contact','P','p','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (65,101,49,90,91,4,'contacts/shop-site/fruit/q','com_contact','Q','q','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (66,102,49,92,93,4,'contacts/shop-site/fruit/r','com_contact','R','r','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (67,103,49,94,95,4,'contacts/shop-site/fruit/s','com_contact','S','s','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (68,104,49,96,97,4,'contacts/shop-site/fruit/t','com_contact','T','t','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (69,105,49,98,99,4,'contacts/shop-site/fruit/u','com_contact','U','u','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (70,106,49,100,101,4,'contacts/shop-site/fruit/v','com_contact','V','v','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (71,107,49,102,103,4,'contacts/shop-site/fruit/w','com_contact','W','w','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (72,108,49,104,105,4,'contacts/shop-site/fruit/x','com_contact','X','x','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (73,109,49,106,107,4,'contacts/shop-site/fruit/y','com_contact','Y','y','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (74,110,49,108,109,4,'contacts/shop-site/fruit/z','com_contact','Z','z','','',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (75,111,29,48,51,2,'sample-data-content/fruit-shop-site','com_content','Fruit Shop Site','fruit-shop-site','','This category will hold all articles and article categories for the shop sample site.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-22 00:36:41',0,'0000-00-00',0,'*'),
  (76,113,75,49,50,3,'sample-data-content/fruit-shop-site/growers','com_content','Growers','growers','','Each supplier will have a page that can be edited. To see this in action you will need to create users who are suppliers and assign them as authors to the suppliers articles.',1,0,'0000-00-00',1,'{\"target\":\"\",\"image\":\"-1\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\",\"rights\":\"\"}',0,'2010-03-26 13:06:45',0,'0000-00-00',0,'*');

COMMIT;

#
# Data for the `jos_contact_details` table  (LIMIT 0,500)
#

INSERT INTO `jos_contact_details` (`id`, `name`, `alias`, `con_position`, `address`, `suburb`, `state`, `country`, `postcode`, `telephone`, `fax`, `misc`, `image`, `imagepos`, `email_to`, `default_con`, `published`, `checked_out`, `checked_out_time`, `ordering`, `params`, `user_id`, `catid`, `access`, `mobile`, `webpage`, `sortname1`, `sortname2`, `sortname3`, `language`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `metakey`, `metadesc`, `metadata`, `featured`, `xreference`, `publish_up`, `publish_down`) VALUES 
  (1,'Contact Name','name','Position','Street Address','Suburb','State','Country','Zip Code','Telephone','Fax','<p>Information about or by the contact.</p>','powered_by.png','top','email@email.com',1,1,0,'0000-00-00',1,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"\",\"linka_name\":\"Twitter\",\"linka\":\"http:\\/\\/twitter.com\\/joomla\",\"linkb_name\":\"YouTube\",\"linkb\":\"http:\\/\\/www.youtube.com\\/user\\/joomla\",\"linkc_name\":\"Ustream\",\"linkc\":\"http:\\/\\/www.ustream.tv\\/joomla\",\"linkd_name\":\"FriendFeed\",\"linkd\":\"http:\\/\\/friendfeed.com\\/joomla\",\"linke_name\":\"Scribed\",\"linke\":\"http:\\/\\/www.scribd.com\\/people\\/view\\/504592-joomla\"}',0,26,1,'','','last','first','middle','en-GB','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (2,'Webmaster','webmaster','','','','','','','',NULL,'',NULL,NULL,'webmaster@example.com',0,1,0,'0000-00-00',0,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"1\",\"email_description\":\"Please send an email with any comments !\",\"show_email_copy\":\"1\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"1\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,45,1,'','','','','','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (3,'Owner','owner','','','','','','','',NULL,'<p>I\'m the owner of this store.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"email_description\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,47,1,'','','','','','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (4,'Buyer','buyer','','','','','','','',NULL,'<p>I am in charge of buying fruit. If you sell good fruit, contact me.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_contact_category\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,47,1,'','','','','','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (5,'Bananas','-bananas','','','','','','','',NULL,'<p>Bananas are a great source of potassium.</p>\r\n<p> </p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_contact_category\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,52,1,'','http://en.wikipedia.org/wiki/Banana','','','','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (6,'Apples','-apples','','','','','','','',NULL,'<img src=\"images/sampledata/fruitshop/apple.jpg\" alt=\"alt\" /><p>Applies are a versatile fruit, used for eating, cooking, and preserving.</p>\r\n<p>There are more that 7500 different kinds of apples grown around the world.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_contact_category\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,51,1,'','http://en.wikipedia.org/wiki/Apple','','','','*','0000-00-00',0,'','2010-05-01 01:50:41',42,'','','{\"robots\":\"\",\"rights\":\"\"}',0,'','0000-00-00','0000-00-00'),
  (8,'Shop Address','shop-address','','','Our City','Our Province','Our Country','','555-555-5555',NULL,'<p>Here are directions for how to get to our shop.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_contact_category\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,46,1,'','','','','','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (7,'Tamarind','tamarind','','','','','','','',NULL,'<p>Tamarinds are a versatile fruit used around the world. In its young form it is used in hot sauces; ripened it is the basis for many freshing drinks.</p>',NULL,NULL,'',0,1,0,'0000-00-00',0,'{\"show_contact_category\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_profile\":\"\",\"show_links\":\"0\",\"linka_name\":\"\",\"linka\":\"\",\"linkb_name\":\"\",\"linkb\":\"\",\"linkc_name\":\"\",\"linkc\":\"\",\"linkd_name\":\"\",\"linkd\":\"\",\"linke_name\":\"\",\"linke\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\"}',0,68,1,'','http://en.wikipedia.org/wiki/Tamarind','','','','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00');

COMMIT;

#
# Data for the `jos_content` table  (LIMIT 0,500)
#

INSERT INTO `jos_content` (`id`, `asset_id`, `title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`, `featured`, `language`, `xreference`) VALUES 
  (1,27,'Joomla!','joomla','','<p>Congratulations, You have a Joomla! site! Joomla! makes your site easy to build a website just the way you want it and keep it simple to update and maintain. Joomla! is a flexible and powerful platform, whether you are building a small site for yourself or a huge site with hundreds of thousands of visitors. Joomla is open source, which means you can make it work just the way you want it to.</p>','',1,1,0,0,'2008-08-12 10:00:00',42,'Joomla!','2010-05-01 10:35:38',42,0,'0000-00-00','2006-01-03 01:00:00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',31,0,1,'','',1,105,'{\"robots\":\"\",\"author\":\"\"}',1,'*',''),
  (2,33,'Great Barrier Reef','great-barrier-reef','','<p>The Great Barrier Reef is the largest coral reef system composed of over 2,900 individual reefs[3] and 900 islands stretching for over 3,000 kilometres (1,600 mi) over an area of approximately 344,400 square kilometres (133,000 sq mi). The reef is located in the Coral Sea, off the coast of Queensland in northeast Australia.</p><p>http://en.wikipedia.org/wiki/Great_Barrier_Reef</p>','<p>The Great Barrier Reef can be seen from outer space and is the world\'s biggest single structure made by living organisms. This reef structure is composed of and built by billions of tiny organisms, known as coral polyps. The Great Barrier Reef supports a wide diversity of life, and was selected as a World Heritage Site in 1981.CNN has labelled it one of the 7 natural wonders of the world. The Queensland National Trust has named it a state icon of Queensland.</p><p>A large part of the reef is protected by the Great Barrier Reef Marine Park, which helps to limit the impact of human use, such as overfishing and tourism. Other environmental pressures to the reef and its ecosystem include water quality from runoff, climate change accompanied by mass coral bleaching, and cyclic outbreaks of the crown-of-thorns starfish.</p><p>The Great Barrier Reef has long been known to and utilised by the Aboriginal Australian and Torres Strait Islander peoples, and is an important part of local groups\' cultures and spirituality. The reef is a very popular destination for tourists, especially in the Whitsundays and Cairns regions. Tourism is also an important economic activity for the region. Fishing also occurs in the region, generating AU$ 1 billion per year.</p>',1,0,0,24,'2009-06-22 11:07:08',42,'','2010-04-25 21:48:59',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"readmore\":\"\",\"page_title\":\"\",\"layout\":\"\"}',1,0,1,'','',1,2,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (3,34,'Cradle Mountain-Lake St Clair National Park','cradle-mountain-lake-st-clair-national-park','','<p>Cradle Mountain-Lake St Clair National Park is located in the Central Highlands area of Tasmania (Australia), 165 km northwest of Hobart. The park contains many walking trails, and is where hikes along the well-known Overland Track usually begins. Major features are Cradle Mountain and Barn Bluff in the northern end, Mount Pelion East, Mount Pelion West, Mount Oakleigh and Mount Ossa in the middle and Lake St Clair in the southern end of the park. The park is part of the Tasmanian Wilderness World Heritage Area.</p><p>http://en.wikipedia.org/wiki/Cradle_Mountain-Lake_St_Clair_National_Park</p>','<h3>Access and usage fee</h3><p>Access from the south (Lake St. Clair) is usually from Derwent Bridge on the Lyell Highway. Northern access (Cradle Valley) is usually via Sheffield, Wilmot or Mole Creek. A less frequently used entrance is via the Arm River Track, from the east.</p><p>In 2005, the Tasmanian Parks & Wildlife Service introduced a booking system & fee for use of the Overland Track over peak periods. Initially the fee was 100 Australian dollars, but this was raised to 150 Australian dollars in 2007. The money that is collected is used to finance the park ranger organisation, track maintenance, building of new facilities and rental of helicopter transport to remove waste from the toilets at the huts in the park.</p>',1,0,0,25,'2009-06-22 11:17:24',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"readmore\":\"\",\"page_title\":\"\",\"layout\":\"\"}',1,0,1,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (4,127,'Joomla! Beginners','joomla-beginners','','<p>If this is your first Joomla site or your first web site, you have come to the right place. Joomla will help you get your website up and running quickly and easily.</p>','<p>',1,0,0,31,'2010-01-10 01:30:47',42,'','2010-05-01 10:35:38',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"0\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,3,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',1,'*',''),
  (5,128,'Upgraders','upgraders','','<p>If you are an experienced Joomla! 1.5 user, 1.6 will seem very familiar. There are new templates and improved user interfaces, but most functionality is the same. The biggest changes are improved access control (ACL), nested categories and comments.</p>','<p><br /> The new user manager which will let you manage who has access to what in your site. You can leave access groups exactly the way you had them in Joomla 1.5 or make them as complicated as you want. You can learn more about how access control works [in this article] and on the [Joomla Documentation site].<br /> <br /> In Joomla 1.5 and 1.0 content was organized into sections and categories. In 1.6 sections are gone, and you can create categories within categories, going as deep as you want. You can learn more about how categories work in 1.6 [in this article] and [on the Joomla Documentation site].<br /> <br /> Comments are now integrated into all front end components. You can control what content has comments enable, who can comment, and much more. You can learn more about comments [in this article] and [on the Joomla Documentation site].<br /> <br /> All layouts have been redesigned to improve accessibility and flexibility. If you would like to keep the 1.5 layouts, you can find them in the html folder of the MilkyWay template. Simply copy the layouts you want to the html folder of your template.<br /> <br /> Updating your site and extensions when needed is easier than ever thanks to installer improvements.<br /> <br /> To learn more about how to move a Joomla 1.5 site to a Joomla 1.6 installation [read this].</p>',1,0,0,31,'2010-01-10 01:33:34',42,'','2010-04-25 21:50:05',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"0\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,2,'','',1,3,'{\"robots\":\"\",\"author\":\"\"}',1,'*',''),
  (6,114,'Professionals','professionals','','<p>Joomla! 1.6 continues development of the Joomla Framework and CMS as a powerful and flexible way to bring your vision of the web to reality. With the administrator now fully MVC, the ability to control its look and the management of extensions is now complete.</p>\r\n','\r\n<p> </p>\r\n<p>Languages files can now be overridden and working with multiple templates and overrides for the same views, creating the design you want is easier than it has ever been. Limiting support to PHP 5.x and above and ending legacy support for Joomla 1.0 makes Joomla lighter and faster than ever.</p>\r\n<p>Access control lists are now incorporated using a new system developed for Joomla. The ACL system is designed with developers in mind, so it is easy to incorporate into your extensions. The new nested sets libraries allow you to incorporate infinitely deep categories but also to use nested sets in a variety of other ways.</p>\r\n<p>A new forms library makes creating all kinds of user interaction simple. MooTools 1.2 provides a highly flexible javascript framework that is a major advance over MooTools 1.0.</p>\r\n<p><br /> New events throughout the core make integration of your plugins where you want them a snap.</p>\r\n<p>Learn about:</p>\r\n<ul>\r\n<li> [working with ACL] </li>\r\n<li> [working with nested sets] </li>\r\n<li> [integrating comments]</li>\r\n<li> [using the forms library] </li>\r\n<li> [working with Mootools 1.2] </li>\r\n<li> [using the override system] </li>\r\n<li> [Joomla! API]</li>\r\n<li> [Database] </li>\r\n<li> [Triggers] </li>\r\n<li> [Xmlrpc] </li>\r\n<li> [Installing and updating extensions]</li>\r\n<li>[Setting up your development environment]</li>\r\n</ul>',1,0,0,31,'2010-01-10 01:37:46',42,'','2010-05-01 10:34:01',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',7,0,1,'','',1,8,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',1,'*',''),
  (7,59,'Sample Sites','sample-sites','','<p>Your installation includes sample data, designed to show you some of the options you have for building your website. In addition to information about Joomla! there are two sample \"sites within a site\" designed to help you get started with builidng your own site.</p>\r\n<p>The first site is a simple site about <a href=\"index.php?option=com_content&view=article&catid=23&id=9\">Australian Parks</a>. It shows you you an quickly and easily build a personal site with just the building blocks that are part of Joomla!. It includes a personal blog, weblinks, and a very simple image gallery.</p>\r\n<p>The second site is slightly more complex and represents what you might do if you are building a site for a small business, in this case a <a href=\"index.php/welcome.html\">fruit shop</a>.</p>\r\n<p>In building either style site, or something completely different, you will probably want to add <a href=\"http://extensions.joomla.org\">extensions</a> and either create or purchase your own template. Many Joomla! users start off by modifying the <a href=\"http://docs.joomla.org/How_do_you_modify_a_template%3F\">templates</a> that come with the core distribution so that they include special images and other design elements that relate to their site\'s focus.</p>','',1,0,0,31,'2010-01-10 01:59:13',42,'','2010-04-26 04:21:39',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',8,0,4,'','',1,50,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (8,121,'Components','components','','<p>Components are larger extensions that produce the major content for your site. Each component has one or more \"views\" that control how content is displayed.</p><p>','<p>In the Joomla! administrator there are additional extensions suce as Menus, Redirection, and the extension managers.</p>',1,0,0,32,'2010-01-10 02:07:38',42,'','2010-05-01 01:24:30',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,1,'','',1,38,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (9,51,'Australian Parks','australian-parks','','<div style=\"border: 1px solid black; background-image: url(images/sampledata/Parks/other/729px-Australia_satellite_plane.jpg);\">\r\n<div style=\"margin-top: 100px; font-family: georgia,serif; font-size: 200%; color: white; line-height: 250%; margin-left: 100px; margin-right: 100px;\">\r\n<p>Welcome!</p>\r\n<p>This is a basic site about the beautiful and fascinating parks of Australia.</p>\r\n<p>This site should give you some ideas about what you can do to set up a simple personal Joomla! site on a topic you are interested in.</p>\r\n</div>\r\n</div>','',1,0,0,23,'2010-01-10 05:41:55',42,'Park Fan','2010-04-30 22:36:32',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,1,'','',1,22,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (10,123,'Content','content','','<p>The content component (com_content) is what you use to write articles. It is extremely flexible and has the largest number of built in views.</p>','',1,0,0,39,'2010-01-10 12:50:35',42,'Joomla!','2010-04-30 22:38:03',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,1,'','',1,15,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (11,130,'Weblinks','weblinks','','<p>Weblinks (com_weblinks) is a component that provides a structured way to organize external links and present them in a visually attractive, consistent and informative way.</p>','',1,0,0,39,'2010-01-10 12:58:33',42,'','2010-04-26 05:19:12',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,2,'','',1,9,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (12,131,'News Feeds','news-feeds','','<p>News Feeds (com_newsfeeds) provides a way to organize and present news feeds. News feeds are a way that you present information from another site on your site. For example, the joomla.org website has numerous feeds that you an incorporate on your site. You an use menus to present a single feed, a list of feeds in a category, or or a list of all feed categories.</p>','',1,0,0,39,'2010-01-10 13:08:52',42,'Joomla!','2010-04-30 22:38:36',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',5,0,3,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (13,122,'Contact','contacts','','<p />The contact component provides a way to provide contact forms and information for your site or to create a most complex directory that can be used for many different purposes.','',1,0,0,39,'2010-01-10 13:19:46',42,'','2010-05-01 19:35:46',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,4,'','',1,17,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (14,132,'Users','users-component','','<p>The users extension lets your site visitors register, login and logout, change their passwords and other information, and recover lost passwords.</p>','',1,0,0,39,'2010-01-10 14:00:05',42,'','2010-05-01 19:36:08',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,5,'','',1,3,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (15,133,'Modules','modules','','<p>About modules</p>','',0,0,0,32,'2010-01-10 14:00:55',42,'Joomla!','2010-04-30 22:39:10',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,2,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (17,141,'Languages','languages','','<p>Joomla! installs in English, but there are translations of the interfaces, sample data and help screens are available in dozens of languages.</p>\r\n<p><a href=\"http://community.joomla.org/translations.html\">Translation information</a></p>\r\n<p>If there is no language pack available for your language, instructions are available for creating your own translation, which you can also contribute to the community by starting a translation team to create an accredited translation.</p>\r\n<p>Translations are installed the the extensions manager in the site administrator and then managed using the language manager.</p>','',1,0,0,32,'2010-01-10 14:02:22',42,'Joomla!','2010-04-30 22:39:37',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,4,'','',1,20,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (84,157,'Getting help','getting-help','','<p>There are lots of places you can get help with Joomla!. </p>\r\n<p>In many places you will see the help icon [image]. Click on this for more information about the options and functions of items on your screen.</p>\r\n\r\n<ul>\r\n<li><a href=\"http://forum.joomla.org\">Support Forums</a></li>\r\n<li><a href=\"http://docs.joomla.org\">Doumentation</a></li>\r\n<li><a href=\"http://resoures.joomla.org\">Professionals</a></li>\r\n<li /><a href=\"http://shop.joomla.org/amazoncom-bookstores.html\">Books</a></ul>\r\n</ul>','',1,0,0,31,'2010-05-01 10:54:28',42,'Joomla!','2010-05-01 11:27:52',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,0,'','',1,6,'',0,'*',''),
  (85,158,'Getting started','getting-started','','<p>All about the basics</p>\r\n<p>What is a CMS</p>\r\n<p>Site and Administrator</p>\r\n<p>Logging in</p>\r\n<p>Creating an article</p>\r\n<p>Learn more</p>','',1,0,0,31,'2010-05-01 11:10:43',42,'Joomla!','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,0,'','',1,4,'',0,'*',''),
  (20,142,'Search','search-component','','<p>The search component proviedes basic search functionality for the information contained in your core components. Many third part extensions also can be searched by the search component.</p>','',1,0,0,39,'2010-01-10 15:45:55',42,'joomla!','2010-05-01 19:35:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',5,0,6,'','',1,8,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (22,129,'The Joomla! Community','the-joomla-community','','<p>Joomla! means All Together, and it is a community of people all working and having fun together that makes Joomla! possible. Thousands of people each year participate in the Joomla! community, and we hope you will be one of them.</p>\r\n<p>People with all kinds of skills, of all skill levels and from around the world are welcome to join in. Participate in the <a href=\"http://joomla.org\">Joomla.org</a> family of websites (the<a href=\"http://forum.joomla.org\"> forum </a>is a great place to start). Come to a <a href=\"http://community.joomla.org/events.html\">Joomla! event</a>. Join or start a <a href=\"http://community.joomla.org/user-groups.html\">Joomla! Users Group</a>. Whether you are a developer, site administrator, designer, end user or fan, there are ways for you to participate and contribute.</p>\r\n<p> </p>','',1,0,0,34,'2010-01-10 16:00:04',42,'','2010-04-26 04:13:54',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,0,'','',1,9,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (23,137,'The Joomla! Project','the-joomla-project','','<p>The Joomla! Project consists of all of the people who make and support the Joomla! Web Platform and Content Management System.  <span style=\"color: #2c2c2c; font-family: Arial, Helvetica, sans-serif; line-height: normal;\">Our mission is to provide a flexible platform for digital publishing and collaboration. </span></p>\r\n<p><span style=\"color: #2c2c2c; font-family: Arial, Helvetica, sans-serif; line-height: normal;\">The core values are:</span></p>\r\n<p><span style=\"color: #2c2c2c; font-family: Arial, Helvetica, sans-serif; line-height: normal;\"> </span></p>\r\n<ul>\r\n<li>Freedom</li>\r\n<li>Equality</li>\r\n<li>Trust</li>\r\n<li>Community</li>\r\n<li>Collaboration</li>\r\n<li>Usability</li>\r\n</ul>\r\n<div>\r\n<div style=\"font-size: 1em; color: #2c2c2c;\">In our vision, we see:</div>\r\n<ul>\r\n<li>People publishing and collaborating in their communities and around the world</li>\r\n<li>Software that is free, secure, and high-quality</li>\r\n<li>A community that is enjoyable and rewarding to participate in</li>\r\n<li>People around the world using their preferred languages</li>\r\n<li>A project that acts autonomously</li>\r\n<li>A project that is socially responsible</li>\r\n<li>A project dedicated to maintaining the trust of its users</li>\r\n</ul>\r\n</div>\r\n<div>There are millions of users around the world and thousands of people who contribute to the project. The work in three main groups: the Production Working Group, responsible for everything that goes into software and documentation; the Community Working Group, responsible for creating a nurturing the community; and Open Source Matters, the non profit organization responsible for managing legal, financial and organizational issues.</div>\r\n<p> </p>','',1,0,0,33,'2010-01-10 16:10:59',42,'Joomla!','2010-04-26 05:57:07',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,0,'','',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (24,143,'Using Joomla!','using-joomla','','<p>All about how to work with Joomla! to create the website you want.</p>','',1,0,0,31,'2010-01-10 16:16:02',42,'','2010-04-26 05:25:51',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,5,'','',1,44,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (25,138,'Typography','typography','','<h1>H1 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h1>\r\n<h2>H2 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h2>\r\n<h3>H3 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h3>\r\n<h4>H4 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h4>\r\n<h5>H5 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h5>\r\n<h6>H6 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmonpqrstuvwzyz</h6>\r\n<p>P The quick brown fox ran over the lazy dog. THE QUICK BROWN FOX RAN OVER THE LAZY DOG.</p>\r\n<ul>\r\n<li>Item</li>\r\n<li>Item</li>\r\n<li>Item<br /> \r\n<ul>\r\n<li>Item</li>\r\n<li>Item</li>\r\n<li>Item<br /> \r\n<ul>\r\n<li>Item</li>\r\n<li>Item</li>\r\n<li>Item</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n<ol>\r\n<li>tem</li>\r\n<li>Item</li>\r\n<li>Item<br /> <ol>\r\n<li>Item</li>\r\n<li>Item</li>\r\n<li>Item<br /><ol>\r\n<li>Item</li>\r\n<li>Item</li>\r\n<li>Item</li>\r\n</ol></li>\r\n</ol> </li>\r\n</ol>\r\n<p> </p>\r\n','\r\n<p> </p>',1,0,0,41,'2010-01-10 17:12:21',42,'','2010-04-30 21:16:49',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"1\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,5,'','Typography page for Joomla! templates.',1,15,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (26,72,'Site Map','site-map','','<p>{loadposition sitemapload}</p>\r\n','',1,0,0,29,'2010-01-10 19:00:52',42,'','2010-03-06 17:10:12',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',15,0,0,'','',1,73,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (27,73,'Archive Module','archive-module','','<p>{loadposition archiveload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-05-01 00:11:51',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,11,'','',1,16,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (28,77,'Latest Articles Module','latest-articles-module','','<p>{loadposition articleslatestload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-14 17:10:10',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,15,'modules','',1,18,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (30,81,'Most Popular Articles','archive-module','','<p>{loadposition articlespopularload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-14 17:10:44',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,16,'modules','',1,42,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (31,94,'Who\'s Online','archive-module','','<p>{loadposition whosonlineload}</p>\r\n<p>Shows how many logged in users and guests there are.</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-14 20:22:34',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',6,0,17,'','',1,14,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (32,82,'Newest Users','newest-users','','<p>{loadposition userslatestload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-14 20:34:08',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',5,0,18,'','',1,10,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (33,75,'Feed Display','archive-module','','<p>{loadposition feeddisplayload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,19,'','',1,2,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (34,83,'News Flash','news-flash','','<p>Displays a set number based on date or a random item from a category:</p>\r\n<p>{loadposition newsflashload}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-04-26 05:20:30',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',6,0,20,'','',1,38,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (35,84,'Random Image','random-image','','<p>{loadposition randomimageload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-14 18:56:48',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,21,'','',1,13,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (36,86,'Search','search','','<p>{loadposition searchload}</p>','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-04-26 05:23:07',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,22,'','',1,10,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (37,70,'Statistics','statistics','','<p>{loadposition statisticsload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-14 18:44:04',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',5,0,23,'','',1,16,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (38,71,'Syndicate','syndicate','','<p>The syndicate module allows you to display a link that allows users to take a feed from your site. It will only display on pages for which feeds are possible. That means it will not display on single article, contact or weblinks pages, such as this one.</p>\r\n<p>[screen shot]</p>\r\n<p>{loadposition syndicateload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-14 14:19:54',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,24,'','',1,10,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (39,93,'Wrapper','wrapper','','<p>{loadposition wrapperload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-14 20:15:36',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,25,'','',1,17,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (40,79,'Menu','menu','','<p>{loadposition menuload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-06 17:10:31',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,26,'','',1,10,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (41,74,'Banner','banner','','<p>{loadposition bannersload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,14,'','',1,21,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (42,78,'Login','login','','<p>{loadposition loginload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-03-05 17:19:42',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,13,'','',1,16,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (43,76,'Footer','footer','','<p>{loadposition footerload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','2010-05-01 00:11:43',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,10,'','',1,7,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (55,85,'Related Items','related-items','','<p>{loadposition relateditemsload}</p>\r\n','\r\n<p> </p>',1,0,0,40,'2010-01-12 04:23:22',42,'','2010-03-14 17:09:41',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,6,'modules','',1,28,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (45,80,'Modules','modules','','<p>Modules are small blocks of content that can be displayed in positions on a web page. The menus on this site are displayed in modules.</p>\r\n<p>The core of Joomla! includes 17 separate modules ranging from login to search to random images.</p>\r\n<p>Each module has a name that starts mod_ but when it displays it has a title. In the descriptions in this section, the titles are the same as the names.</p>','',0,0,0,40,'2010-01-11 08:16:25',42,'','2010-04-26 05:21:21',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,7,'','',1,40,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (47,139,'System','system','','<p>Default on:</p>\r\n<p>Debug</p>\r\n<p>Remember me</p>\r\n<p>SEF</p>\r\n<p>Default off:</p>\r\n<p>Cache</p>\r\n<p>Log</p>\r\n<p>Redirect</p>\r\n','\r\n<p> </p>',1,0,0,43,'2010-01-11 15:42:12',42,'','2010-04-26 05:24:50',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,1,'','',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (48,120,'Authentication','authentication','','<p>Default on:</p><p>Joomla</p><p>Default off:</p><p>Gmail</p><p>LDAP</p><p>OpenID</p>','',1,0,0,43,'2010-01-11 15:43:13',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,2,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (49,124,'Content','content','','<p>Default on:</p><p>Email Cloaking</p><p>Load Module</p><p>Page Break</p><p>Page Navigation</p><p>Rating</p><p>Default off:</p><p>Code Highlighting (Geshi)</p>','',1,0,0,43,'2010-01-11 15:45:31',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,3,'','',1,5,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (50,125,'Editors','editors','','<p>Default on:</p><p>CodeMirror</p><p>TinyMCE</p><p>No Editor</p><p>Default off:</p><p>None</p><p>','</p>',1,0,0,43,'2010-01-11 15:47:52',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,4,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (51,126,'Editors-xtd','editors-xtd','','<p>Default on:</p><p>Editor Button: Image</p><p>Editor Button: Readmore</p><p>Editor Button: Page Break</p><p>Editor Button: Article</p><p>Default off:<br />None</p><p></p>','<p></p>',1,0,0,43,'2010-01-11 15:57:14',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,5,'','',1,3,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (52,136,'Search','search','','<p>The search component uses plugins to control which parts of your Joomla! site are searched.</p>\r\n<p>Default On:</p>\r\n<p>Content</p>\r\n<p>Contacts</p>\r\n<p>Weblinks</p>\r\n<p>News Feeds</p>\r\n<p>Categories</p>\r\n<p>Default off:</p>\r\n<p>Sections</p>','',1,0,0,43,'2010-01-11 17:25:41',42,'','2010-04-26 05:23:02',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,6,'','',1,3,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (53,144,'User','user','','<p>Default on:</p>\r\n<p>Joomla</p>\r\n\r\n<h3>Default off:</h3>\r\n<p>Two new plugins are available in 1.6 but are disabled by default.\r\n<p>Contact Creator</p>\r\n<p>Creates a new linked contact record for each new user created. </p>\r\n<p>Profile</p>\r\n<p>This example profile plugin allows you to insert additional fields into user registration and profile display. This is intended as an example of the types of extensions to the profile you might want to create. </p>','',1,0,0,43,'2010-01-11 17:30:01',42,'','2010-05-01 20:27:47',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,7,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (54,145,'What\'s New in 1.5?','whats-new-in-15','','','\r\n<p>As with previous releases, Joomla! provides a unified and easy-to-use framework for delivering content for Web sites of all kinds. To support the changing nature of the Internet and emerging Web technologies, Joomla! required substantial restructuring of its core functionality and we also used this effort to simplify many challenges within the current user interface. Joomla! 1.5 has many new features.</p>\r\n<p style=\"margin-bottom: 0in;\">In Joomla! 1.5, you\'ll notice:</p>\r\n<p>Substantially improved usability, manageability, and scalability far beyond the original Mambo foundations</p>\r\n<p> </p>\r\n<p>Expanded accessibility to support internationalisation, double-byte characters and right-to-left support for Arabic, Farsi, and Hebrew languages among others</p>\r\n<li>Extended integration of external applications through Web services and remote authentication such as the Lightweight Directory Access Protocol (LDAP)\r\n<p> </p>\r\n</li>\r\n<li>Enhanced content delivery, template and presentation capabilities to support accessibility standards and content delivery to any destination\r\n<p> </p>\r\n</li>\r\n<p>n</p>\r\n<li>\r\n<p>A more sustainable and flexible framework for Component and Extension developers</p>\r\n</li>\r\n<li>\r\n<p>Backward compatibility with previous releases of Components, Templates, Modules, and other Extensions</p>\r\n</li>',2,0,0,31,'2010-01-12 03:51:04',42,'','2010-04-26 05:28:18',43,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,6,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (56,43,'Koala','koala','','<p><img src=\"images/sampledata/Parks/animals/180px-Koala-ag1.jpg\" border=\"0\" alt=\"Koala  Thumbnail\" width=\"180\" height=\"123\" style=\"vertical-align: middle; border: 0;\" /></p>\r\n','\r\n<p><img src=\"images/sampledata/Parks/animals/800px-Koala-ag1.jpg\" border=\"0\" alt=\"Koala Climbing Tree\" width=\"500\" height=\"341\" style=\"vertical-align: middle; border: 0;\" /></p>\r\n<p> </p>\r\n<p> </p>\r\n<p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Koala-ag1.jpg</p>\r\n<p class=\"caption\">Author: Arnaud Gaillard</p>\r\n<p><span class=\"caption\">License: Creative Commons Share Alike Attribution Generic 1.0</span></p>',1,0,0,37,'2010-01-23 18:18:52',42,'Park Fan','2010-04-30 22:36:42',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"Full size koala\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',10,0,1,'','',1,17,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (57,44,'Wobbegone','wobbegone','','<p><img src=\"images/sampledata/Parks/animals/180px-Wobbegong.jpg\" border=\"0\" alt=\"Wobbegone\" style=\"vertical-align: middle; border: 0;\" /></p>\r\n','\r\n<p><img src=\"images/sampledata/Parks/animals/800px-Wobbegong.jpg\" border=\"0\" style=\"vertical-align: middle; border: 0;\" /></p>\r\n<p>Source: http://en.wikipedia.org/wiki/File:Wobbegong.jpg</p>\r\n<p>Author: Richard Ling</p>\r\n<p>Rights: GNU Free Documentation License v 1.2 or later</p>',1,0,0,37,'2010-01-23 18:30:58',42,'Park Fan','2010-04-30 22:36:54',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"Full size wobbegone\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',8,0,2,'','',1,9,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (58,45,'Phyllopteryx','phyllopteryx','','<p><img src=\"images/sampledata/Parks/animals/200px-Phyllopteryx_taeniolatus1.jpg\" border=\"0\" style=\"vertical-align: middle;\" /></p>\r\n<p> </p>\r\n','\r\n<p><img src=\"images/sampledata/Parks/animals/800px-Phyllopteryx_taeniolatus1.jpg\" border=\"0\" style=\"vertical-align: middle;\" /></p>\r\n<p> </p>\r\n<p>Source: http://en.wikipedia.org/wiki/File:Phyllopteryx_taeniolatus1.jpg</p>\r\n<p>Author: Richard Ling</p>\r\n<p>License: GNU Free Documentation License v 1.2 or later</p>',1,0,0,37,'2010-01-23 19:00:03',42,'Park Fan','2010-04-30 22:37:11',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"1\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"Full size phyllopteryx\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',8,0,3,'','',1,8,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (59,46,'Spotted Quoll','spotted-quoll','','<p><img src=\"images/sampledata/Parks/animals/220px-SpottedQuoll_2005_SeanMcClean.jpg\" border=\"0\" alt=\"Spotted Quoll\" style=\"vertical-align: middle; border: 0;\" /></p>\r\n','\r\n<p><img src=\"images/sampledata/Parks/animals/789px-SpottedQuoll_2005_SeanMcClean.jpg\" border=\"0\" alt=\"Spotted Quoll\" style=\"vertical-align: middle;\" /></p>\r\n<p> </p>\r\n<p>Source: http://en.wikipedia.org/wiki/File:SpottedQuoll_2005_SeanMcClean.jpg</p>\r\n<p>Author: Sean McClean</p>\r\n<p>License: GNU Free Documentation License v 1.2 or later</p>',1,0,0,37,'2010-01-23 19:09:49',42,'Park Fan','2010-04-30 22:37:22',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"Full size spotted quoll\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',5,0,4,'','',1,12,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (60,47,'Pinnacles','pinnacles','','<p><img src=\"images/sampledata/Parks/landscape/120px-Pinnacles_Western_Australia.jpg\" border=\"0\" alt=\"Kings Canyon\" width=\"120\" height=\"90\" style=\"vertical-align: middle; border: 0;\" /></p><p> </p>','<p><img src=\"images/sampledata/Parks/landscape/800px-Pinnacles_Western_Australia.jpg\" border=\"0\" alt=\"King\'s Canyon\" width=\"500\" height=\"374\" style=\"vertical-align: middle; border: 0;\" /></p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Pinnacles_Western_Australia.jpg</p><p class=\"caption\">Author: <a class=\"new\" href=\"http://commons.wikimedia.org/w/index.php?title=User:Markdoe&action=edit&redlink=1\" title=\"User:Markdoe (page does not exist)\"></a>Martin Gloss</p><p class=\"caption\">License: GNU Free Documentation license v 1.2 or later.</p>',1,0,0,36,'2010-01-23 20:15:41',42,'','2010-01-24 04:00:13',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"1\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size Pinnacles\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',8,0,1,'','',1,4,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (61,48,'Ormiston Pound','ormiston-pound','','<p><img src=\"images/sampledata/Parks/landscape/180px-Ormiston_Pound.JPG\" border=\"0\" alt=\"Ormiston Pound\" style=\"border: 0;\" /></p><p> </p>','<p><img src=\"images/sampledata/Parks/landscape/800px-Ormiston_Pound.JPG\" border=\"0\" alt=\"Ormiston Pound\" height=\"375\" style=\"vertical-align: middle; border: 0;\" /></p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Ormiston_Pound.JPG</p><p class=\"caption\">Author:</p><p class=\"caption\">License: GNU Free Public Documentation License</p>',1,0,0,36,'2010-01-23 20:53:40',42,'','2010-01-24 04:00:50',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"1\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full Size Ormiston Pound\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',6,0,2,'','',1,6,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (62,49,'Blue Mountain Rain Forest','blue-mountain-rain-forest','','<p><img src=\"images/sampledata/Parks/landscape/120px-Rainforest,bluemountainsNSW.jpg\" border=\"0\" alt=\"Rain Forest Blue Mountrains\" /></p>','<p><img src=\"images/sampledata/Parks/landscape/727px-Rainforest,bluemountainsNSW.jpg\" border=\"0\" alt=\"Rain Forest Blue Mountrains\" style=\"vertical-align: middle;\" /></p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Rainforest,bluemountainsNSW.jpg</p><p class=\"caption\">Author: Adam J.W.C.</p><p class=\"caption\">License: GNU Free Documentation License</p>',1,0,0,36,'2010-01-23 21:08:32',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"1\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size Blue Mountains rainforest\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',4,0,3,'','',1,2,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (63,50,'Cradle Mountain','cradle-mountain','','<p><img src=\"images/sampledata/Parks/landscape/250px-Cradle_Mountain_Seen_From_Barn_Bluff.jpg\" border=\"0\" alt=\"Cradle Mountain\" style=\"vertical-align: middle;\" /></p>','<p><img src=\"images/sampledata/Parks/landscape/800px-Cradle_Mountain_Seen_From_Barn_Bluff.jpg\" border=\"0\" alt=\"Cradle Mountain\" style=\"vertical-align: middle;\" /></p><p> </p><p class=\"caption\">Source: http://commons.wikimedia.org/wiki/File:Rainforest,bluemountainsNSW.jpg</p><p class=\"caption\">Author: Alan J.W.C.</p><p class=\"caption\">License: GNU Free Documentation License v . 1.2 or later</p>',1,0,0,36,'2010-01-23 21:17:34',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_readmore\":\"1\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"page_title\":\"\",\"alternative_readmore\":\"Full size Cradle Mountrain\",\"layout\":\"\",\"article-allow_ratings\":\"1\",\"article-allow_comments\":\"1\"}',3,0,4,'','',1,3,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (64,52,'First Blog Entry','first-blog-entry','','<div id=\"lipsum\"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed faucibus purus vitae diam posuere nec eleifend elit dictum. Aenean sit amet erat purus, id fermentum lorem. Integer elementum tristique lectus, non posuere quam pretium sed. Quisque scelerisque erat at urna condimentum euismod. Fusce vestibulum facilisis est, a accumsan massa aliquam in. In auctor interdum mauris a luctus. Morbi euismod tempor dapibus. Duis dapibus posuere quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In eu est nec erat sollicitudin hendrerit. Pellentesque sed turpis nunc, sit amet laoreet velit. Praesent vulputate semper nulla nec varius. Aenean aliquam, justo at blandit sodales, mauris leo viverra orci, sed sodales mauris orci vitae magna.</p>','<p>Quisque a massa sed libero tristique suscipit. Morbi tristique molestie metus, vel vehicula nisl ultrices pretium. Sed sit amet est et sapien condimentum viverra. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus viverra tortor porta orci convallis ac cursus erat sagittis. Vivamus aliquam, purus non luctus adipiscing, orci urna imperdiet eros, sed tincidunt neque sapien et leo. Cras fermentum, dolor id tempor vestibulum, neque lectus luctus mauris, nec congue tellus arcu nec augue. Nulla quis mi arcu, in bibendum quam. Sed placerat laoreet fermentum. In varius lobortis consequat. Proin vulputate felis ac arcu lacinia adipiscing. Morbi molestie, massa id sagittis luctus, sem sapien sollicitudin quam, in vehicula quam lectus quis augue. Integer orci lectus, bibendum in fringilla sit amet, rutrum eget enim. Curabitur at libero vitae lectus gravida luctus. Nam mattis, ligula sit amet vestibulum feugiat, eros sem sodales mi, nec dignissim ante elit quis nisi. Nulla nec magna ut leo convallis sagittis ac non erat. Etiam in augue nulla, sed tristique orci. Vestibulum quis eleifend sapien.</p><p>Nam ut orci vel felis feugiat posuere ut eu lorem. In risus tellus, sodales eu eleifend sed, imperdiet id nulla. Nunc at enim lacus. Etiam dignissim, arcu quis accumsan varius, dui dui faucibus erat, in molestie mauris diam ac lacus. Sed sit amet egestas nunc. Nam sollicitudin lacinia sapien, non gravida eros convallis vitae. Integer vehicula dui a elit placerat venenatis. Nullam tincidunt ligula aliquet dui interdum feugiat. Maecenas ultricies, lacus quis facilisis vehicula, lectus diam consequat nunc, euismod eleifend metus felis eu mauris. Aliquam dapibus, ipsum a dapibus commodo, dolor arcu accumsan neque, et tempor metus arcu ut massa. Curabitur non risus vitae nisl ornare pellentesque. Pellentesque nec ipsum eu dolor sodales aliquet. Vestibulum egestas scelerisque tincidunt. Integer adipiscing ultrices erat vel rhoncus.</p><p>Integer ac lectus ligula. Nam ornare nisl id magna tincidunt ultrices. Phasellus est nisi, condimentum at sollicitudin vel, consequat eu ipsum. In venenatis ipsum in ligula tincidunt bibendum id et leo. Vivamus quis purus massa. Ut enim magna, pharetra ut condimentum malesuada, auctor ut ligula. Proin mollis, urna a aliquam rutrum, risus erat cursus odio, a convallis enim lectus ut lorem. Nullam semper egestas quam non mattis. Vestibulum venenatis aliquet arcu, consectetur pretium erat pulvinar vel. Vestibulum in aliquet arcu. Ut dolor sem, pellentesque sit amet vestibulum nec, tristique in orci. Sed lacinia metus vel purus pretium sit amet commodo neque condimentum.</p><p>Aenean laoreet aliquet ullamcorper. Nunc tincidunt luctus tellus, eu lobortis sapien tincidunt sed. Donec luctus accumsan sem, at porttitor arcu vestibulum in. Sed suscipit malesuada arcu, ac porttitor orci volutpat in. Vestibulum consectetur vulputate eros ut porttitor. Aenean dictum urna quis erat rutrum nec malesuada tellus elementum. Quisque faucibus, turpis nec consectetur vulputate, mi enim semper mi, nec porttitor libero magna ut lacus. Quisque sodales, leo ut fermentum ullamcorper, tellus augue gravida magna, eget ultricies felis dolor vitae justo. Vestibulum blandit placerat neque, imperdiet ornare ipsum malesuada sed. Quisque bibendum quam porta diam molestie luctus. Sed metus lectus, ornare eu vulputate vel, eleifend facilisis augue. Maecenas eget urna velit, ac volutpat velit. Nam id bibendum ligula. Donec pellentesque, velit eu convallis sodales, nisi dui egestas nunc, et scelerisque lectus quam ut ipsum.</p></div>',1,0,0,35,'2010-01-23 22:41:36',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,1,'','',1,0,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (65,53,'Second Blog Post','second-blog-post','','<div id=\"lipsum\"><p>Pellentesque bibendum metus ut dolor fermentum ut pulvinar tortor hendrerit. Nam vel odio vel diam tempus iaculis in non urna. Curabitur scelerisque, nunc id interdum vestibulum, felis elit luctus dui, ac dapibus tellus mauris tempus augue. Duis congue facilisis lobortis. Phasellus neque erat, tincidunt non lacinia sit amet, rutrum vitae nunc. Sed placerat lacinia fermentum. Integer justo sem, cursus id tristique eget, accumsan vel sapien. Curabitur ipsum neque, elementum vel vestibulum ut, lobortis a nisl. Fusce malesuada mollis purus consectetur auctor. Morbi tellus nunc, dapibus sit amet rutrum vel, laoreet quis mauris. Aenean nec sem nec purus bibendum venenatis. Mauris auctor commodo libero, in adipiscing dui adipiscing eu. Praesent eget orci ac nunc sodales varius.</p>','<p>Nam eget venenatis lorem. Vestibulum a interdum sapien. Suspendisse potenti. Quisque auctor purus nec sapien venenatis vehicula malesuada velit vehicula. Fusce vel diam dolor, quis facilisis tortor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque libero nisi, pellentesque quis cursus sit amet, vehicula vitae nisl. Curabitur nec nunc ac sem tincidunt auctor. Phasellus in mattis magna. Donec consequat orci eget tortor ultricies rutrum. Mauris luctus vulputate molestie. Proin tincidunt vehicula euismod. Nam congue leo non erat cursus a adipiscing ipsum congue. Nulla iaculis purus sit amet turpis aliquam sit amet dapibus odio tincidunt. Ut augue diam, congue ut commodo pellentesque, fermentum mattis leo. Sed iaculis urna id enim dignissim sodales at a ipsum. Quisque varius lobortis mollis. Nunc purus magna, pellentesque pellentesque convallis sed, varius id ipsum. Etiam commodo mi mollis erat scelerisque fringilla. Nullam bibendum massa sagittis diam ornare rutrum.</p><p>Praesent convallis metus ut elit faucibus tempus in quis dui. Donec fringilla imperdiet nibh, sit amet fringilla velit congue et. Quisque commodo luctus ligula, vitae porttitor eros venenatis in. Praesent aliquet commodo orci id varius. Nulla nulla nibh, varius id volutpat nec, sagittis nec eros. Cras et dui justo. Curabitur malesuada facilisis neque, sed tempus massa tincidunt ut. Sed suscipit odio in lacus auctor vehicula non ut lacus. In hac habitasse platea dictumst. Sed nulla nisi, lacinia in viverra at, blandit vel tellus. Nulla metus erat, ultrices non pretium vel, varius nec sem. Morbi sollicitudin mattis lacus quis pharetra. Donec tincidunt mollis pretium. Proin non libero justo, vitae mattis diam. Integer vel elit in enim varius posuere sed vitae magna. Duis blandit tempor elementum. Vestibulum molestie dui nisi.</p><p>Curabitur volutpat interdum lorem sed tempus. Sed placerat quam non ligula lacinia sodales. Cras ultrices justo at nisi luctus hendrerit. Quisque sit amet placerat justo. In id sapien eu neque varius pharetra sed in sapien. Etiam nisl nunc, suscipit sed gravida sed, scelerisque ut nisl. Mauris quis massa nisl, aliquet posuere ligula. Etiam eget tortor mauris. Sed pellentesque vestibulum commodo. Mauris vitae est a libero dapibus dictum fringilla vitae magna.</p><p>Nulla facilisi. Praesent eget elit et mauris gravida lobortis ac nec risus. Ut vulputate ullamcorper est, volutpat feugiat lacus convallis non. Maecenas quis sem odio, et aliquam libero. Integer vel tortor eget orci tincidunt pulvinar interdum at erat. Integer ullamcorper consequat eros a pellentesque. Cras sagittis interdum enim in malesuada. Etiam non nunc neque. Fusce non ligula at tellus porta venenatis. Praesent tortor orci, fermentum sed tincidunt vel, varius vel dui. Duis pulvinar luctus odio, eget porta justo vulputate ac. Nulla varius feugiat lorem sed tempor. Phasellus pulvinar dapibus magna eget egestas. In malesuada lectus at justo pellentesque vitae rhoncus nulla ultrices. Proin ut sem sem. Donec eu suscipit ipsum. Cras eu arcu porttitor massa feugiat aliquet at quis nisl.</p></div>',1,0,0,35,'2010-01-23 22:44:09',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',1,0,2,'','',1,3,'{\"robots\":\"\",\"author\":\"\"}',0,'*',''),
  (66,62,'Articles Modules','content-modules','','<p>Content modules display article and other information from the content component.</p>\r\n','',1,0,0,40,'2010-03-04 00:05:49',42,'','2010-05-01 00:11:19',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,1,'','',1,33,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (67,63,'User Modules','user-modules','','<p>User modules interact with the user system, allowing users to login, showing who is logged in, and showing the most recently registered users.</p>\r\n','\r\n<p> </p>',1,0,0,40,'2010-03-04 01:25:09',42,'','2010-05-01 00:11:19',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,2,'','',1,13,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (68,64,'Display Modules','display-modules','','<p>These modules display information from other components.</p>\r\n','\r\n<p> </p>',1,0,0,40,'2010-03-04 01:56:54',42,'','2010-05-01 00:11:19',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,3,'','',1,5,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (69,65,'Utility Modules','utility-modules','','<p>Utility modules provide useful functionality such as search,  syndication, and statistics.</p>\r\n','\r\n<p> </p>',1,0,0,40,'2010-03-04 03:24:05',42,'','2010-05-01 00:11:20',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,4,'','',1,13,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (70,66,'Menus','menus','','<p>Menus provide navigation and structure to your site. <br /><br /></p>\r\n','',1,0,0,40,'2010-03-04 03:36:41',42,'','2010-05-01 00:11:20',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,5,'','',1,11,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (71,67,'Custom HTML','custom-html','','<p>{loadposition customload}</p>\r\n','\r\n<p> </p>',1,0,0,40,'2010-03-04 03:46:39',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,8,'','',1,5,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (72,68,'Weblinks Module','weblinks-module','','<p>This module displays the list of weblinks in a category.</p>\r\n<p>{loadposition weblinksload}</p>\r\n','',1,0,0,40,'2010-03-04 03:49:23',42,'','2010-03-14 20:15:50',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,9,'','',1,9,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (73,69,'Breadcrumbs','breadcrumbs','','<p>Breadcrumbs provide a pathway for users to navigate through the site.</p>\r\n<p>{loadposition breadcrumbsload}</p>\r\n','\r\n<p> </p>',1,0,0,40,'2010-03-04 03:54:50',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,12,'','',1,11,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (74,87,'Beez 2','template-1','','<p>This is a template for a personal site.</p>\r\n<p>It features ....</p>\r\n<p>[screen shot]</p>\r\n<p> </p>','',1,0,0,41,'2010-03-14 15:09:03',42,'','2010-04-30 21:16:49',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,1,'','',1,9,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (75,88,'Template 2','template-2','','<p>This is a template for a business site.</p>\r\n<p>It features ..</p>\r\n<p>[screen shot]</p>\r\n','',1,0,0,41,'2010-03-14 15:09:55',42,'','2010-04-30 21:16:49',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,2,'','',1,6,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (76,89,'Atomic','atomic','','<p>Atomic is a minimal template designed to be a skeleton for making your own template and to learn about Joomla! templating.</p>\r\n<p>[screen shot]</p>\r\n','',1,0,0,41,'2010-03-14 15:13:33',42,'','2010-04-30 21:16:49',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,3,'','',1,10,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (77,90,'Syndicate','syndicate','','<p>The syndicate module allows you to display a link that allows users to take a feed from your site. It will only display on pages for which feeds are possible. That means it will not display on single article, contact or weblinks pages, such as this one.</p>\r\n<p>[screen shot]</p>\r\n<p>{loadposition syndicateload}</p>\r\n','',1,0,0,40,'2010-01-11 01:05:22',42,'','0000-00-00',0,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,27,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (78,91,'Milky Way','milky-way','','<p>Milky Way is the default template for Joomla! 1.5. You can find all of the old layouts in the _html folder if you need them for your upgraded sites.</p>\r\n<p><img src=\"templates/rhuk_milkyway/template_thumbnail.png\" border=\"0\" alt=\"Milky Way Image\" width=\"206\" height=\"150\" style=\"vertical-align: middle;\" /></p>\r\n','',1,0,0,41,'2010-03-14 15:17:02',42,'','2010-04-30 21:16:49',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,4,'','',1,8,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (79,112,'Fruit Shop','fruit-shop','','<h1>Welcome to the Fruit Shop</h1>\r\n<p style=\"padding-left: 30px;\"><em><br /></em></p>\r\n<p>We sell fruits from around the world. Please use our website to learn more about our business. We hope you will come to our shop and buy some fruit.</p>\r\n<p style=\"padding-left: 30px;\"><em><span style=\"font-style: normal;\"><em>This mini site will show you how you might want to set up a site for a business, in this case one selling fruit. It shows how to use access controls to manage your site content. If you were building a real site, you would might want to extend it with e-commerce, a catalog, mailing lists or other enhancements, many of which are available through the</em><a href=\"http://extensions.joomla.org\" style=\"color: #1b57b1; text-decoration: none; font-weight: normal;\"><em> Joomla! Extensions Directory</em></a><em>.</em></span></em></p>\r\n<p style=\"padding-left: 30px;\"><em><span style=\"font-style: normal;\"><em>To understand this site you will probably want to make one user with groups set to customer and one with group set to grower. By logging in with different privileges  you can see how access control works.</em></span></em></p>\r\n<p style=\"padding-left: 30px;\"><em><span style=\"font-style: normal;\"><em><br /></em></span></em></p>\r\n','',1,0,0,75,'2010-03-22 00:32:45',42,'','2010-04-25 21:48:59',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,1,'','',1,46,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (80,115,'Happy Orange Orchard','happy-orange-orchard','','<p>At our orchard we grow the world\'s best oranges as well as other citrus fruit such as lemons and grapefruit. Our family has been tending this orchard for generations.</p>\r\n','',1,0,0,76,'2010-03-26 14:34:21',42,'','2010-04-25 21:48:59',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,1,'','',1,11,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (81,116,'Wonderful Watermelon','wonderful-watermelon','','<p>Watermelon is a wonderful and healthy treat. We grow the world\'s sweetest watermelon. We have the largest watermelon patch in our country.</p>\r\n','',1,0,0,76,'2010-03-26 14:44:26',44,'','2010-03-26 15:34:22',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',3,0,2,'','',1,4,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (82,117,'Directions','directions','','<p>Here\'s how to find our shop.</p>\r\n<p> </p>\r\n<p>By car</p>\r\n<p>By foot</p>\r\n<p>By bus</p>\r\n<p> </p>\r\n','',1,0,0,75,'2010-03-26 16:26:52',42,'','2010-04-25 21:48:58',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',2,0,2,'','',1,6,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}',0,'*',''),
  (83,146,'Administrator Components','administrator-components','','<p>All components also are used in the administrator area of your website. In addition to the ones listed here, there are components in the administrator that do not have direct front end displays. The most important ones for most users are:</p>\r\n<ul>\r\n<li>Media Manager</li>\r\n<li>Extensions Manager</li>\r\n<li>Menu Manager</li>\r\n<li>Configuration</li>\r\n<li>Banners</li>\r\n</ul>\r\n','\r\n<h3>Media Manager</h3>\r\n<h3>Extensions Manager</h3>\r\n<h3>Menu Manager</h3>\r\n<h3>Configuration</h3>\r\n<h3>Banners</h3>',1,0,0,39,'2010-01-10 15:28:15',42,'Joomla!','2010-05-01 01:17:46',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',4,0,7,'','',1,12,'',0,'*',''),
  (86,159,'Article Categories','article-categories-module','','<p>{loadposition articlescategoriesload}</p>','',1,0,0,40,'2010-05-03 00:06:44',42,'Joomla!','2010-05-03 00:25:16',42,0,'0000-00-00','0000-00-00','0000-00-00','','','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_readmore\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"page_title\":\"\",\"alternative_readmore\":\"\",\"layout\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\"}',7,0,8,'','',1,5,'',0,'*','');

COMMIT;

#
# Data for the `jos_content_frontpage` table  (LIMIT 0,500)
#

INSERT INTO `jos_content_frontpage` (`content_id`, `ordering`) VALUES 
  (1,1),
  (4,2),
  (5,3),
  (6,4);

COMMIT;

#
# Data for the `jos_extensions` table  (LIMIT 0,500)
#

INSERT INTO `jos_extensions` (`extension_id`, `name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`) VALUES 
  (1,'com_mailto','component','com_mailto','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (2,'com_wrapper','component','com_wrapper','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (3,'com_admin','component','com_admin','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (4,'com_banners','component','com_banners','',1,1,1,0,'','{\"purchase_type\":\"1\",\"track_impressions\":\"0\",\"track_clicks\":\"0\",\"metakey_prefix\":\"\"}','','',0,'0000-00-00',0,0),
  (5,'com_cache','component','com_cache','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (6,'com_categories','component','com_categories','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (7,'com_checkin','component','com_checkin','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (8,'com_contact','component','com_contact','',1,1,1,0,'','{\"show_contact_list\":\"0\",\"show_name\":\"1\",\"show_position\":\"1\",\"show_email\":\"0\",\"show_street_address\":\"1\",\"show_suburb\":\"1\",\"show_state\":\"1\",\"show_postcode\":\"1\",\"show_country\":\"1\",\"show_telephone\":\"1\",\"show_mobile\":\"1\",\"show_fax\":\"1\",\"show_webpage\":\"1\",\"show_misc\":\"1\",\"show_image\":\"1\",\"allow_vcard\":\"0\",\"show_articles\":\"1\",\"show_profile\":\"1\",\"show_links\":\"1\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"contact_icons\":\"0\",\"icon_address\":\"\",\"icon_email\":\"\",\"icon_telephone\":\"\",\"icon_mobile\":\"\",\"icon_fax\":\"\",\"icon_misc\":\"\",\"show_headings\":\"1\",\"show_position_headings\":\"1\",\"show_email_headings\":\"0\",\"show_telephone_headings\":\"1\",\"show_mobile_headings\":\"1\",\"show_fax_headings\":\"1\",\"allow_vcard_headings\":\"0\",\"show_email_form\":\"1\",\"email_description\":\"\",\"show_email_copy\":\"1\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"1\",\"custom_reply\":\"0\",\"redirect\":\"\",\"show_category_crumb\":\"0\",\"article_allow_ratings\":\"0\",\"article_allow_comments\":\"0\",\"metakey\":\"\",\"metadesc\":\"\",\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','',0,'0000-00-00',0,0),
  (9,'com_cpanel','component','com_cpanel','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (10,'com_installer','component','com_installer','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (11,'com_languages','component','com_languages','',1,1,1,0,'','{\"administrator\":\"en-GB\",\"site\":\"en-GB\"}','','',0,'0000-00-00',0,0),
  (12,'com_login','component','com_login','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (13,'com_media','component','com_media','',1,1,0,1,'','{\"upload_extensions\":\"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS\",\"upload_maxsize\":\"10000000\",\"file_path\":\"images\",\"image_path\":\"images\",\"restrict_uploads\":\"1\",\"allowed_media_usergroup\":\"3\",\"check_mime\":\"1\",\"image_extensions\":\"bmp,gif,jpg,png\",\"ignore_extensions\":\"\",\"upload_mime\":\"image\\/jpeg,image\\/gif,image\\/png,image\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/powerpoint,text\\/plain,application\\/x-zip\",\"upload_mime_illegal\":\"text\\/html\",\"enable_flash\":\"0\"}','','',0,'0000-00-00',0,0),
  (14,'com_menus','component','com_menus','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (15,'com_messages','component','com_messages','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (16,'com_modules','component','com_modules','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (17,'com_newsfeeds','component','com_newsfeeds','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (18,'com_plugins','component','com_plugins','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (19,'com_search','component','com_search','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (20,'com_templates','component','com_templates','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (21,'com_weblinks','component','com_weblinks','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (22,'com_content','component','com_content','',1,1,0,0,'','','','',0,'0000-00-00',0,0),
  (23,'com_config','component','com_config','',1,1,0,1,'','','','',0,'0000-00-00',0,0),
  (24,'com_redirect','component','com_redirect','',1,1,0,0,'','','','',0,'0000-00-00',0,0),
  (25,'com_users','component','com_users','',1,1,0,1,'','{\"allowUserRegistration\":\"1\",\"new_usertype\":\"2\",\"useractivation\":\"1\",\"frontend_userparams\":\"1\",\"mailSubjectPrefix\":\"\",\"mailBodySuffix\":\"\"}','','',0,'0000-00-00',0,0),
  (100,'Joomla! Web Application Framework','library','joomla','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (101,'PHPMailer','library','phpmailer','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (102,'SimplePie','library','simplepie','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (103,'Bitfolge','library','simplepie','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (104,'phputf8','library','simplepie','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (200,'mod_articles_archive','module','mod_articles_archive','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (201,'mod_articles_latest','module','mod_articles_latest','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (202,'mod_articles_popular','module','mod_articles_popular','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (203,'mod_banners','module','mod_banners','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (204,'mod_breadcrumbs','module','mod_breadcrumbs','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (205,'mod_custom','module','mod_custom','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (206,'mod_feed','module','mod_feed','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (207,'mod_footer','module','mod_footer','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (208,'mod_login','module','mod_login','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (209,'mod_menu','module','mod_menu','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (210,'mod_articles_news','module','mod_articles_news','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (211,'mod_random_image','module','mod_random_image','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (212,'mod_related_items','module','mod_related_items','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (213,'mod_search','module','mod_search','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (214,'mod_stats','module','mod_stats','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (215,'mod_syndicate','module','mod_syndicate','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (216,'mod_users_latest','module','mod_users_latest','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (217,'mod_weblinks','module','mod_weblinks','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (218,'mod_whosonline','module','mod_whosonline','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (219,'mod_wrapper','module','mod_wrapper','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (220,'mod_articles_category','module','mod_articles_category','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (221,'mod_articles_categories','module','mod_articles_categories','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (300,'mod_custom','module','mod_custom','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (301,'mod_feed','module','mod_feed','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (302,'mod_latest','module','mod_latest','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (303,'mod_logged','module','mod_logged','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (304,'mod_login','module','mod_login','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (305,'mod_menu','module','mod_menu','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (306,'mod_online','module','mod_online','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (307,'mod_popular','module','mod_popular','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (308,'mod_quickicon','module','mod_quickicon','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (309,'mod_status','module','mod_status','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (310,'mod_submenu','module','mod_submenu','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (311,'mod_title','module','mod_title','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (312,'mod_toolbar','module','mod_toolbar','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (313,'mod_unread','module','mod_unread','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (400,'plg_authentication_gmail','plugin','gmail','authentication',0,0,1,0,'','{\"applysuffix\":\"0\",\"suffix\":\"\",\"verifypeer\":\"1\",\"user_blacklist\":\"\"}','','',0,'0000-00-00',1,0),
  (401,'plg_authentication_joomla','plugin','joomla','authentication',0,1,1,0,'','{}','','',0,'0000-00-00',0,0),
  (402,'plg_authentication_ldap','plugin','ldap','authentication',0,0,1,0,'','{\"host\":\"\",\"port\":\"389\",\"use_ldapV3\":\"0\",\"negotiate_tls\":\"0\",\"no_referrals\":\"0\",\"auth_method\":\"bind\",\"base_dn\":\"\",\"search_string\":\"\",\"users_dn\":\"\",\"username\":\"admin\",\"password\":\"bobby7\",\"ldap_fullname\":\"fullName\",\"ldap_email\":\"mail\",\"ldap_uid\":\"uid\"}','','',0,'0000-00-00',3,0),
  (403,'plg_authentication_openid','plugin','openid','authentication',0,0,1,0,'','{\"usermode\":\"2\",\"phishing-resistant\":\"0\",\"multi-factor\":\"0\",\"multi-factor-physical\":\"0\"}','','',0,'0000-00-00',4,0),
  (404,'plg_content_emailcloak','plugin','emailcloak','content',0,1,1,0,'','{\"mode\":\"1\"}','','',0,'0000-00-00',1,0),
  (405,'plg_content_geshi','plugin','geshi','content',0,1,1,0,'','{}','','',0,'0000-00-00',2,0),
  (406,'plg_content_loadmodule','plugin','loadmodule','content',0,1,1,0,'','{\"style\":\"table\"}','','',0,'0000-00-00',3,0),
  (407,'plg_content_pagebreak','plugin','pagebreak','content',0,1,1,0,'','{\"title\":\"1\",\"multipage_toc\":\"1\",\"showall\":\"1\"}','','',0,'0000-00-00',4,0),
  (408,'plg_content_pagenavigation','plugin','pagenavigation','content',0,1,1,0,'','{\"position\":\"1\"}','','',0,'0000-00-00',5,0),
  (409,'plg_content_vote','plugin','vote','content',0,1,1,0,'','{}','','',0,'0000-00-00',6,0),
  (410,'plg_editors_codemirror','plugin','codemirror','editors',0,1,1,0,'','{\"linenumbers\":\"0\",\"tabmode\":\"indent\"}','','',0,'0000-00-00',1,0),
  (411,'plg_editors_none','plugin','none','editors',0,1,1,0,'','{}','','',0,'0000-00-00',2,0),
  (412,'plg_editors_tinymce','plugin','tinymce','editors',0,1,1,0,'','{\"mode\":\"1\",\"skin\":\"0\",\"compressed\":\"0\",\"cleanup_startup\":\"0\",\"cleanup_save\":\"2\",\"entity_encoding\":\"raw\",\"lang_mode\":\"0\",\"lang_code\":\"en\",\"text_direction\":\"ltr\",\"content_css\":\"1\",\"content_css_custom\":\"\",\"relative_urls\":\"1\",\"newlines\":\"0\",\"invalid_elements\":\"script,applet,iframe\",\"extended_elements\":\"\",\"toolbar\":\"top\",\"toolbar_align\":\"left\",\"html_height\":\"550\",\"html_width\":\"750\",\"element_path\":\"1\",\"fonts\":\"1\",\"paste\":\"1\",\"searchreplace\":\"1\",\"insertdate\":\"1\",\"format_date\":\"%Y-%m-%d\",\"inserttime\":\"1\",\"format_time\":\"%H:%M:%S\",\"colors\":\"1\",\"table\":\"1\",\"smilies\":\"1\",\"media\":\"1\",\"hr\":\"1\",\"directionality\":\"1\",\"fullscreen\":\"1\",\"style\":\"1\",\"layer\":\"1\",\"xhtmlxtras\":\"1\",\"visualchars\":\"1\",\"nonbreaking\":\"1\",\"template\":\"1\",\"blockquote\":\"1\",\"wordcount\":\"1\",\"advimage\":\"1\",\"advlink\":\"1\",\"autosave\":\"1\",\"contextmenu\":\"1\",\"inlinepopups\":\"1\",\"safari\":\"0\",\"custom_plugin\":\"\",\"custom_button\":\"\"}','','',0,'0000-00-00',3,0),
  (413,'plg_editors-xtd_article','plugin','article','editors-xtd',0,1,1,0,'','{}','','',0,'0000-00-00',1,0),
  (414,'plg_editors-xtd_image','plugin','image','editors-xtd',0,1,1,0,'','{}','','',0,'0000-00-00',2,0),
  (415,'plg_editors-xtd_pagebreak','plugin','pagebreak','editors-xtd',0,1,1,0,'','{}','','',0,'0000-00-00',3,0),
  (416,'plg_editors-xtd_readmore','plugin','readmore','editors-xtd',0,1,1,0,'','{}','','',0,'0000-00-00',4,0),
  (417,'plg_search_categories','plugin','categories','search',0,1,1,0,'','{\"search_limit\":\"50\",\"search_content\":\"0\",\"search_uncategorised\":\"0\",\"search_archived\":\"0\"}','','',0,'0000-00-00',2,0),
  (418,'plg_search_contacts','plugin','contacts','search',0,1,1,0,'','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_uncategorised\":\"0\",\"search_archived\":\"0\"}','','',0,'0000-00-00',4,0),
  (419,'plg_search_content','plugin','content','search',0,1,1,0,'','{\"search_limit\":\"50\",\"search_content\":\"0\",\"search_uncategorised\":\"0\",\"search_archived\":\"0\"}','','',0,'0000-00-00',5,0),
  (420,'plg_search_newsfeeds','plugin','newsfeeds','search',0,1,1,0,'','{\"search_limit\":\"50\",\"search_content\":\"0\",\"search_uncategorised\":\"0\",\"search_archived\":\"0\"}','','',0,'0000-00-00',3,0),
  (421,'plg_search_weblinks','plugin','weblinks','search',0,1,1,0,'','{\"search_limit\":\"50\",\"search_content\":\"0\",\"search_uncategorised\":\"0\",\"search_archived\":\"0\"}','','',0,'0000-00-00',1,0),
  (422,'plg_system_cache','plugin','cache','system',0,0,1,0,'','{\"browsercache\":\"0\",\"cachetime\":\"15\"}','','',0,'0000-00-00',1,0),
  (423,'plg_system_debug','plugin','debug','system',0,1,1,0,'','{\"profile\":\"1\",\"queries\":\"1\",\"memory\":\"1\",\"language_files\":\"1\",\"language_strings\":\"1\",\"strip-first\":\"1\",\"strip-prefix\":\"\",\"strip-suffix\":\"\"}','','',0,'0000-00-00',2,0),
  (424,'plg_system_log','plugin','log','system',0,1,1,0,'','{}','','',0,'0000-00-00',3,0),
  (425,'plg_system_redirect','plugin','redirect','system',0,1,1,0,'','{}','','',0,'0000-00-00',4,0),
  (426,'plg_system_remember','plugin','remember','system',0,1,1,0,'','{}','','',0,'0000-00-00',5,0),
  (427,'plg_system_sef','plugin','sef','system',0,1,1,0,'','{}','','',0,'0000-00-00',6,0),
  (428,'plg_user_contactcreator','plugin','contactcreator','user',0,0,1,0,'','{\"autowebpage\":\"\",\"category\":\"26\",\"autopublish\":\"0\"}','','',0,'0000-00-00',1,0),
  (429,'plg_user_joomla','plugin','joomla','user',0,1,1,0,'','{\"autoregister\":\"1\"}','','',0,'0000-00-00',2,0),
  (430,'plg_user_profile','plugin','profile','user',0,1,1,0,'','{\"register-require_address1\":\"0\",\"register-require_address2\":\"0\",\"register-require_city\":\"0\",\"register-require_region\":\"0\",\"register-require_country\":\"0\",\"register-require_postal_code\":\"0\",\"register-require_phone\":\"0\",\"register-require_website\":\"0\",\"profile-require_address1\":\"1\",\"profile-require_address2\":\"1\",\"profile-require_city\":\"1\",\"profile-require_region\":\"1\",\"profile-require_country\":\"1\",\"profile-require_postal_code\":\"1\",\"profile-require_phone\":\"1\",\"profile-require_website\":\"1\"}','','',0,'0000-00-00',0,0),
  (431,'plg_extension_joomla','plugin','joomla','extension',0,1,1,0,'','{}','','',0,'0000-00-00',1,0),
  (500,'atomic','template','atomic','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (501,'rhuk_milkyway','template','rhuk_milkyway','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (502,'bluestork','template','bluestork','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (503,'beez_20','template','beez_20','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (504,'hathor','template','hathor','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (600,'English (United Kingdom)','language','en-GB','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (601,'English (United Kingdom)','language','en-GB','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (602,'English (United States)','language','en-US','',0,1,1,0,'','','','',0,'0000-00-00',0,0),
  (603,'English (United States)','language','en-US','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (604,'XXTestLang','language','xx-XX','',1,1,1,0,'','','','',0,'0000-00-00',0,0),
  (605,'XXTestLang','language','xx-XX','',0,1,1,0,'','','','',0,'0000-00-00',0,0);

COMMIT;

#
# Data for the `jos_languages` table  (LIMIT 0,500)
#

INSERT INTO `jos_languages` (`lang_id`, `lang_code`, `title`, `title_native`, `sef`, `image`, `description`, `metakey`, `metadesc`, `published`) VALUES 
  (1,'en-GB','English (UK)','English (UK)','en','en','','','',1),
  (2,'en-US','English (US)','English (US)','us','en','','','',0),
  (3,'xx-XX','xx (Test)','xx (Test)','xx','br','','','',1);

COMMIT;

#
# Data for the `jos_menu` table  (LIMIT 0,500)
#

INSERT INTO `jos_menu` (`id`, `menutype`, `title`, `alias`, `note`, `path`, `link`, `type`, `published`, `parent_id`, `level`, `component_id`, `ordering`, `checked_out`, `checked_out_time`, `browserNav`, `access`, `img`, `template_style_id`, `params`, `lft`, `rgt`, `home`, `language`) VALUES 
  (1,'','Menu_Item_Root','root','','','','',1,0,0,0,0,0,'0000-00-00',0,0,'',0,'',0,275,0,'*'),
  (2,'_adminmenu','com_banners','Banners','','Banners','index.php?option=com_banners','component',0,1,1,4,0,0,'0000-00-00',0,0,'class:banners',0,'',1,10,0,'*'),
  (3,'_adminmenu','com_banners','Banners','','Banners/Banners','index.php?option=com_banners','component',0,2,2,4,0,0,'0000-00-00',0,0,'class:banners',0,'',2,3,0,'*'),
  (4,'_adminmenu','com_banners_clients','Clients','','Banners/Clients','index.php?option=com_banners&view=clients','component',0,2,2,4,0,0,'0000-00-00',0,0,'class:banners-clients',0,'',4,5,0,'*'),
  (5,'_adminmenu','com_banners_tracks','Tracks','','Banners/Tracks','index.php?option=com_banners&view=tracks','component',0,2,2,4,0,0,'0000-00-00',0,0,'class:banners-tracks',0,'',6,7,0,'*'),
  (6,'_adminmenu','com_banners_categories','Categories','','Banners/Categories','index.php?option=com_categories&extension=com_banners','component',0,2,2,6,0,0,'0000-00-00',0,0,'class:banners-cat',0,'',8,9,0,'*'),
  (7,'_adminmenu','com_contact','Contacts','','Contacts','index.php?option=com_contact','component',0,1,1,8,0,0,'0000-00-00',0,0,'class:contact',0,'',11,16,0,'*'),
  (8,'_adminmenu','com_contact','Contacts','','Contacts/Contacts','index.php?option=com_contact','component',0,7,2,8,0,0,'0000-00-00',0,0,'class:contact',0,'',12,13,0,'*'),
  (9,'_adminmenu','com_contact_categories','Categories','','Contacts/Categories','index.php?option=com_categories&extension=com_contact','component',0,7,2,6,0,0,'0000-00-00',0,0,'class:contact-cat',0,'',14,15,0,'*'),
  (10,'_adminmenu','com_messages','Messaging','','Messaging','index.php?option=com_messages','component',0,1,1,15,0,0,'0000-00-00',0,0,'class:messages',0,'',17,22,0,'*'),
  (11,'_adminmenu','com_messages_add','New Private Message','','Messaging/New Private Message','index.php?option=com_messages&task=message.add','component',0,10,2,15,0,0,'0000-00-00',0,0,'class:messages-add',0,'',18,19,0,'*'),
  (12,'_adminmenu','com_messages_read','Read Private Message','','Messaging/Read Private Message','index.php?option=com_messages','component',0,10,2,15,0,0,'0000-00-00',0,0,'class:messages-read',0,'',20,21,0,'*'),
  (13,'_adminmenu','com_newsfeeds','News Feeds','','News Feeds','index.php?option=com_newsfeeds','component',0,1,1,17,0,0,'0000-00-00',0,0,'class:newsfeeds',0,'',23,28,0,'*'),
  (14,'_adminmenu','com_newsfeeds_feeds','Feeds','','News Feeds/Feeds','index.php?option=com_newsfeeds','component',0,13,2,17,0,0,'0000-00-00',0,0,'class:newsfeeds',0,'',24,25,0,'*'),
  (15,'_adminmenu','com_newsfeeds_categories','Categories','','News Feeds/Categories','index.php?option=com_categories&extension=com_newsfeeds','component',0,13,2,6,0,0,'0000-00-00',0,0,'class:newsfeeds-cat',0,'',26,27,0,'*'),
  (16,'_adminmenu','com_redirect','Redirect','','Redirect','index.php?option=com_redirect','component',0,1,1,100,0,0,'0000-00-00',0,0,'class:redirect',0,'',37,38,0,'*'),
  (17,'_adminmenu','com_search','Search','','Search','index.php?option=com_search','component',0,1,1,19,0,0,'0000-00-00',0,0,'class:search',0,'',29,30,0,'*'),
  (18,'_adminmenu','com_weblinks','Weblinks','','Weblinks','index.php?option=com_weblinks','component',0,1,1,21,0,0,'0000-00-00',0,0,'class:weblinks',0,'',31,36,0,'*'),
  (19,'_adminmenu','com_weblinks_links','Links','','Weblinks/Links','index.php?option=com_weblinks','component',0,18,2,21,0,0,'0000-00-00',0,0,'class:weblinks',0,'',32,33,0,'*'),
  (20,'_adminmenu','com_weblinks_categories','Categories','','Weblinks/Categories','index.php?option=com_categories&extension=com_weblinks','component',0,18,2,6,0,0,'0000-00-00',0,0,'class:weblinks-cat',0,'',34,35,0,'*'),
  (440,'mainmenu','News Feeds','news-feeds','','site-map/news-feeds','index.php?option=com_newsfeeds&view=categories&id=0','component',1,294,2,17,0,0,'0000-00-00',0,1,'',0,'{\"categories_description\":\"\",\"maxLevel\":\"-1\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"show_headings\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_character_count\":\"0\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',244,245,0,'*'),
  (223,'mainmenu','Site Administrator','administrator','','administrator','administrator/','url',1,1,1,0,3,0,'0000-00-00',1,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',263,264,0,'*'),
  (204,'usermenu','Logout','logout','','logout','index.php?option=com_users&view=login','component',1,1,1,25,5,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',273,274,0,'*'),
  (202,'usermenu','Submit an Article','submit-an-article','','submit-an-article','index.php?option=com_content&view=form&layout=edit','component',1,1,1,22,3,0,'0000-00-00',0,3,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',265,266,0,'*'),
  (203,'usermenu','Submit a Web Link','submit-a-web-link','','submit-a-web-link','index.php?option=com_weblinks&view=form&layout=edit','component',1,1,1,21,4,0,'0000-00-00',0,3,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',269,270,0,'*'),
  (227,'aboutjoomla','Weblinks Categories','weblinks-categories','','using-joomla/extensions/components/weblinks-component/weblinks-categories','index.php?option=com_weblinks&view=categories','component',1,265,5,21,6,0,'0000-00-00',0,1,'',0,'{\"Category\":\"20\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"-1\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',93,94,0,'*'),
  (233,'mainmenu','Login','login','','login','index.php?option=com_users&view=login','component',1,1,1,25,2,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',261,262,0,'*'),
  (229,'aboutjoomla','Single Contact','single-contact','','using-joomla/extensions/components/contact-component/single-contact','index.php?option=com_contact&view=contact&id=1','component',1,270,5,8,0,0,'0000-00-00',0,1,'',0,'{\"show_category_crumb\":\"\",\"article_allow_ratings\":\"\",\"article_allow_comments\":\"\",\"show_contact_list\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_links\":\"\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',85,86,0,'*'),
  (201,'usermenu','Your Profile','your-profile','','your-profile','index.php?option=com_users&view=profile','component',1,1,1,25,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',39,40,0,'*'),
  (231,'parks','Parks in Places','parks-in-places','','parks-in-places','index.php?option=com_content&view=categories&id=23','component',1,1,1,22,0,0,'0000-00-00',0,1,'',4,'{\"categories_description\":\"\",\"maxLevel\":\"-1\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"category_layout\":\"\",\"show_headings\":\"\",\"show_date\":\"\",\"date_format\":\"\",\"filter_field\":\"\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',43,44,0,'*'),
  (296,'parks','Park Links','park-links','','park-links','index.php?option=com_weblinks&view=category&id=44','component',1,1,1,21,0,0,'0000-00-00',0,1,'',4,'{\"maxLevel\":\"-1\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"show_headings\":\"\",\"orderby_pri\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',231,232,0,'*'),
  (234,'parks','Park Blog','park-blog','','park-blog','index.php?option=com_content&view=category&layout=blog&id=35','component',1,1,1,22,0,0,'0000-00-00',0,1,'',4,'{\"show_description\":\"1\",\"show_description_image\":\"1\",\"show_subcategory_content\":\"1\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',45,46,0,'*'),
  (238,'mainmenu','Sample Sites','sample-sites','','sample-sites','index.php?option=com_content&view=article&id=7','component',1,1,1,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',257,258,0,'*'),
  (207,'top','Joomla.org','joomlaorg','','joomlaorg','http://joomla.org','url',1,1,1,0,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',57,58,0,'*'),
  (242,'parks','Write a Blog Post','write-a-blog-post','','write-a-blog-post','index.php?option=com_content&view=form&layout=edit','component',1,1,1,22,0,0,'0000-00-00',0,3,'',4,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',47,48,0,'*'),
  (243,'parks','Parks Home','parks-home','','parks-home','index.php?option=com_content&view=article&id=9','component',1,1,1,22,0,0,'0000-00-00',0,1,'',4,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"0\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"link_author\":\"\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"0\",\"show_icons\":\"\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',41,42,0,'*'),
  (244,'parks','Image Gallery','image-gallery','','image-gallery','index.php?option=com_content&view=categories&id=38','component',1,1,1,22,0,0,'0000-00-00',0,1,'',4,'{\"categories_description\":\"\",\"maxLevel\":\"-1\",\"show_empty_categories\":\"\",\"show_description\":\"0\",\"show_description_image\":\"0\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"category_layout\":\"\",\"show_headings\":\"\",\"show_date\":\"\",\"date_format\":\"\",\"filter_field\":\"\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"2\",\"show_noauth\":\"\",\"show_title\":\"0\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"1\",\"link_category\":\"1\",\"show_parent_category\":\"0\",\"link_parent_category\":\"0\",\"show_author\":\"0\",\"link_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"1\",\"show_readmore\":\"1\",\"show_icons\":\"0\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',51,56,0,'*'),
  (270,'aboutjoomla','Contact  Component','contact-component','','using-joomla/extensions/components/contact-component','index.php?option=com_content&view=article&id=13','component',1,268,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',80,87,0,'*'),
  (279,'aboutjoomla','The Joomla! Community','the-joomla-community','','the-joomla-community','index.php?option=com_content&view=article&id=22','component',1,1,1,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":0,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',227,228,0,'*'),
  (249,'aboutjoomla','Submit a Weblink','submit-a-weblink','','using-joomla/extensions/components/weblinks-component/submit-a-weblink','index.php?option=com_weblinks&view=form&layout=edit','component',1,265,5,21,0,0,'0000-00-00',0,3,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',89,90,0,'*'),
  (251,'aboutjoomla','Contact Categories','contact-categories','','using-joomla/extensions/components/contact-component/contact-categories','index.php?option=com_contact&view=categories','component',1,270,5,8,0,0,'0000-00-00',0,1,'',0,'{\"Category\":\"26\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',81,82,0,'*'),
  (252,'aboutjoomla','News Feed Categories','new-feed-categories','','using-joomla/extensions/components/news-feeds-component/new-feed-categories','index.php?option=com_newsfeeds&view=categories','component',1,267,5,17,0,0,'0000-00-00',0,1,'',0,'{\"Category\":\"28\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',97,98,0,'*'),
  (253,'aboutjoomla','News Feed Category','news-feed-category','','using-joomla/extensions/components/news-feeds-component/news-feed-category','index.php?option=com_newsfeeds&view=category&id=28','component',1,267,5,17,0,0,'0000-00-00',0,1,'',0,'{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"display_num\":\"\",\"show_pagination_limit\":\"\",\"show_pagination\":\"\",\"show_pagination_results\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',101,102,0,'*'),
  (254,'aboutjoomla','Single News Feed','single-news-feed','','using-joomla/extensions/components/news-feeds-component/single-news-feed','index.php?option=com_newsfeeds&view=newsfeed&id=1','component',1,267,5,17,0,0,'0000-00-00',0,1,'',0,'{\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',99,100,0,'*'),
  (255,'aboutjoomla','Search','search','','using-joomla/extensions/components/search-component/search','index.php?option=com_search&view=search','component',1,276,5,19,0,0,'0000-00-00',0,1,'',0,'{\"search_areas\":\"1\",\"show_date\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',119,120,0,'*'),
  (256,'aboutjoomla','Archived articles','archived-articles','','using-joomla/extensions/components/content-component/archived-articles','index.php?option=com_content&view=archive','component',1,266,5,22,0,0,'0000-00-00',0,1,'',0,'{\"orderby\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',75,76,0,'*'),
  (257,'aboutjoomla','Single Article','single-article','','using-joomla/extensions/components/content-component/single-article','index.php?option=com_content&view=article&id=2','component',1,266,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',65,66,0,'*'),
  (278,'aboutjoomla','The Joomla! Project','the-joomla-project','','the-joomla-project','index.php?option=com_content&view=article&id=23','component',1,1,1,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":0,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',229,230,0,'*'),
  (259,'aboutjoomla','Article Category Blog','article-category-blog','','using-joomla/extensions/components/content-component/article-category-blog','index.php?option=com_content&view=category&layout=blog&id=31','component',1,266,5,22,0,0,'0000-00-00',0,1,'',0,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"0\",\"show_description_image\":\"0\",\"show_category_title\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"2\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',69,70,0,'*'),
  (260,'aboutjoomla','Article Category List','article-category-list','','using-joomla/extensions/components/content-component/article-category-list','index.php?option=com_content&view=category&id=31','component',1,266,5,22,0,0,'0000-00-00',0,1,'',0,'{\"maxLevel\":\"\",\"show_category_title\":\"\",\"page_subheading\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"show_headings\":\"\",\"list_show_title\":\"\",\"list_show_date\":\"\",\"date_format\":\"\",\"list_show_hits\":\"\",\"list_show_author\":\"\",\"filter_field\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_pagination_limit\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',71,72,0,'*'),
  (262,'aboutjoomla','Featured Articles','featured-articles-','','using-joomla/extensions/components/content-component/featured-articles-','index.php?option=com_content&view=featured','component',1,266,5,22,0,0,'0000-00-00',0,1,'',0,'{\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"order_date\":\"\",\"multi_column_order\":\"1\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',73,74,0,'*'),
  (263,'aboutjoomla','Submit Article','submit-article','','using-joomla/extensions/components/content-component/submit-article','index.php?option=com_content&view=form&layout=edit','component',1,266,5,22,0,0,'0000-00-00',0,3,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',77,78,0,'*'),
  (265,'aboutjoomla','Weblinks Component','weblinks-component','','using-joomla/extensions/components/weblinks-component','index.php?option=com_content&view=article&id=11','component',1,268,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',88,95,0,'*'),
  (266,'aboutjoomla','Content Component','content-component','','using-joomla/extensions/components/content-component','index.php?option=com_content&view=article&id=10','component',1,268,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',64,79,0,'*'),
  (267,'aboutjoomla','News Feeds Component','news-feeds-component','','using-joomla/extensions/components/news-feeds-component','index.php?option=com_content&view=article&id=12','component',1,268,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',96,103,0,'*'),
  (268,'aboutjoomla','Components','components','','using-joomla/extensions/components','index.php?option=com_content&view=category&layout=blog&id=39','component',1,277,3,22,0,0,'0000-00-00',0,1,'',0,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"1\",\"show_description_image\":\"\",\"show_category_title\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"0\",\"num_intro_articles\":\"7\",\"num_columns\":\"1\",\"num_links\":\"0\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"order\",\"order_date\":\"\",\"show_pagination\":\"0\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"\",\"show_parent_category\":\"0\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"link_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"0\",\"show_readmore\":\"\",\"show_icons\":\"0\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',63,124,0,'*'),
  (277,'aboutjoomla','Extensions','extensions','','using-joomla/extensions','index.php?option=com_content&view=categories&id=32','component',1,280,2,22,0,0,'0000-00-00',0,1,'',0,'{\"categories_description\":\"\",\"maxLevel\":\"-1\",\"show_empty_categories\":\"1\",\"show_description\":\"1\",\"show_description_image\":\"1\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"category_layout\":\"\",\"show_headings\":\"0\",\"show_date\":\"hide\",\"date_format\":\"\",\"filter_field\":\"hide\",\"num_leading_articles\":\"0\",\"num_intro_articles\":\"5\",\"num_columns\":\"1\",\"num_links\":\"0\",\"multi_column_order\":\"\",\"orderby_pri\":\"order\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',62,223,0,'*'),
  (271,'aboutjoomla','Users Component','users-component','','using-joomla/extensions/components/users-component','index.php?option=com_content&view=article&id=14','component',1,268,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',104,117,0,'*'),
  (272,'aboutjoomla','Article Categories','article-categories','','using-joomla/extensions/components/content-component/article-categories','index.php?option=com_content&view=categories','component',1,266,5,22,0,0,'0000-00-00',0,1,'',0,'{\"Category\":\"29\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',67,68,0,'*'),
  (273,'aboutjoomla','Administrator Components','administrator-components','','using-joomla/extensions/components/administrator-components','index.php?option=com_content&view=article&id=83','component',1,268,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',122,123,0,'*'),
  (274,'aboutjoomla','Weblinks Single Category','webl-links-single-category','','using-joomla/extensions/components/weblinks-component/webl-links-single-category','index.php?option=com_weblinks&view=category&id=21','component',1,265,5,21,0,0,'0000-00-00',0,1,'',0,'{\"show_feed_link\":\"1\",\"image\":\"-1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',91,92,0,'*'),
  (275,'aboutjoomla','Contact Single Category','contact-single-category','','using-joomla/extensions/components/contact-component/contact-single-category','index.php?option=com_contact&view=category&catid=26','component',1,270,5,8,0,0,'0000-00-00',0,1,'',0,'{\"display_num\":\"20\",\"image\":\"-1\",\"image_align\":\"right\",\"show_limit\":\"0\",\"show_feed_link\":\"1\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',83,84,0,'*'),
  (276,'aboutjoomla','Search Component','search-component','','using-joomla/extensions/components/search-component','index.php?option=com_content&view=article&id=20','component',1,268,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',118,121,0,'*'),
  (280,'aboutjoomla','Using Joomla!','using-joomla','','using-joomla','index.php?option=com_content&view=article&id=24','component',1,1,1,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',59,226,0,'*'),
  (281,'aboutjoomla','Modules','modules','','using-joomla/extensions/modules','index.php?option=com_content&view=category&layout=blog&id=40','component',1,277,3,22,0,0,'0000-00-00',0,1,'',0,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"1\",\"show_description_image\":\"1\",\"show_category_title\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"0\",\"num_intro_articles\":\"5\",\"num_columns\":\"1\",\"num_links\":\"0\",\"multi_column_order\":\"\",\"orderby_pri\":\"order\",\"orderby_sec\":\"order\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_title\":\"1\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"0\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"link_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"0\",\"show_readmore\":\"0\",\"show_icons\":\"0\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',125,178,0,'*'),
  (282,'aboutjoomla','Templates','templates','','using-joomla/extensions/templates','index.php?option=com_content&view=category&layout=blog&id=41','component',1,277,3,22,0,0,'0000-00-00',0,1,'',0,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"1\",\"show_description_image\":\"\",\"show_category_title\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"0\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"0\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"order\",\"order_date\":\"\",\"show_pagination\":\"0\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',179,204,0,'*'),
  (283,'aboutjoomla','Languages','languages','','using-joomla/extensions/languages','index.php?option=com_content&view=article&id=17','component',1,277,3,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',205,206,0,'*'),
  (284,'aboutjoomla','Plugins','plugins','','using-joomla/extensions/plugins','index.php?option=com_content&view=category&layout=blog&id=43','component',1,277,3,22,0,0,'0000-00-00',0,1,'',0,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"1\",\"show_description_image\":\"\",\"show_category_title\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"0\",\"num_intro_articles\":\"7\",\"num_columns\":\"1\",\"num_links\":\"0\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"order\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"0\",\"link_parent_category\":\"0\",\"show_author\":\"0\",\"link_author\":\"\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"0\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',207,222,0,'*'),
  (285,'aboutjoomla','Typography Atomic','typography-atomic','','using-joomla/extensions/templates/atomic/typography-atomic','index.php?option=com_content&view=article&id=25','component',1,422,5,22,0,0,'0000-00-00',0,1,'',3,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',199,200,0,'*'),
  (286,'aboutjoomla','Typography Milky Way','typography-milky-way','','using-joomla/extensions/templates/milky-way/typography-milky-way','index.php?option=com_content&view=article&id=25','component',1,421,5,22,0,0,'0000-00-00',0,1,'',1,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"\",\"show_icons\":\"1\",\"show_print_icon\":\"1\",\"show_email_icon\":\"1\",\"show_hits\":\"0\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',193,194,0,'*'),
  (205,'top','Home','home','','home','index.php?Itemid=101','alias',1,1,1,0,0,0,'0000-00-00',0,1,'',0,'{\"aliasoptions\":\"435\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',49,50,0,'*'),
  (290,'mainmenu','Articles','articles','','site-map/articles','index.php?option=com_content&view=categories&id=0','component',1,294,2,22,0,0,'0000-00-00',0,1,'',0,'{\"categories_description\":\"\",\"maxLevel\":\"-1\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"category_layout\":\"\",\"show_headings\":\"\",\"show_date\":\"\",\"date_format\":\"\",\"filter_field\":\"\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',238,239,0,'*'),
  (294,'mainmenu','Site Map','site-map','','site-map','index.php?option=com_content&view=article&id=26','component',1,1,1,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',237,246,0,'*'),
  (300,'aboutjoomla','Latest Users','latest-users','','using-joomla/extensions/modules/user-modules/latest-users','index.php?option=com_content&view=article&id=32','component',1,412,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',141,142,0,'*'),
  (301,'aboutjoomla','Who\'s Online','whos-online','','using-joomla/extensions/modules/user-modules/whos-online','index.php?option=com_content&view=article&id=31','component',1,412,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',143,144,0,'*'),
  (302,'aboutjoomla','Most Popular','most-popular','','using-joomla/extensions/modules/content-modules/most-popular','index.php?option=com_content&view=article&id=30','component',1,411,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',127,128,0,'*'),
  (303,'aboutjoomla','Menu','menu','','using-joomla/extensions/modules/menu-modules/menu','index.php?option=com_content&view=article&id=40','component',1,415,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',175,176,0,'*'),
  (304,'aboutjoomla','Statistics','statistics','','using-joomla/extensions/modules/utility-modules/statistics','index.php?option=com_content&view=article&id=37','component',1,414,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',167,168,0,'*'),
  (305,'aboutjoomla','Banner','banner','','using-joomla/extensions/modules/display-modules/banner','index.php?option=com_content&view=article&id=41','component',1,413,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',151,152,0,'*'),
  (306,'aboutjoomla','Search','search','','using-joomla/extensions/modules/utility-modules/search','index.php?option=com_content&view=article&id=36','component',1,414,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',169,170,0,'*'),
  (307,'aboutjoomla','Random Image','random-image','','using-joomla/extensions/modules/display-modules/random-image','index.php?option=com_content&view=article&id=35','component',1,413,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',149,150,0,'*'),
  (309,'aboutjoomla','News Flash','news-flash','','using-joomla/extensions/modules/content-modules/news-flash','index.php?option=com_content&view=article&id=34','component',1,411,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',129,130,0,'*'),
  (310,'aboutjoomla','Latest Articles','latest-articles','','using-joomla/extensions/modules/content-modules/latest-articles','index.php?option=com_content&view=article&id=28','component',1,411,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',131,132,0,'*'),
  (311,'aboutjoomla','Syndicate','syndicate','','using-joomla/extensions/modules/utility-modules/syndicate','index.php?option=com_content&view=article&id=38','component',1,414,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',165,166,0,'*'),
  (312,'aboutjoomla','Login','login','','using-joomla/extensions/modules/user-modules/login','index.php?option=com_content&view=article&id=42','component',1,412,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',145,146,0,'*'),
  (313,'aboutjoomla','Wrapper','wrapper','','using-joomla/extensions/modules/display-modules/wrapper','index.php?option=com_content&view=article&id=39','component',1,413,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',157,158,0,'*'),
  (314,'aboutjoomla','Home Page Milky Way','home-page-milky-way','','using-joomla/extensions/templates/milky-way/home-page-milky-way','index.php?Itemid=','alias',1,421,5,0,0,0,'0000-00-00',0,1,'',1,'{\"aliasoptions\":\"101\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\"}',195,196,0,'*'),
  (316,'aboutjoomla','Home Page Atomic','home-page-atomic','','using-joomla/extensions/templates/atomic/home-page-atomic','index.php?option=com_content&view=featured','component',1,422,5,22,0,0,'0000-00-00',0,1,'',3,'{\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"order_date\":\"\",\"multi_column_order\":\"1\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',201,202,0,'*'),
  (317,'aboutjoomla','System','system','','using-joomla/extensions/plugins/system','index.php?option=com_content&view=article&id=47','component',1,284,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',208,209,0,'*'),
  (318,'aboutjoomla','Authentication','authentication','','using-joomla/extensions/plugins/authentication','index.php?option=com_content&view=article&id=48','component',1,284,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',210,211,0,'*'),
  (319,'aboutjoomla','Content','content','','using-joomla/extensions/plugins/content','index.php?option=com_content&view=article&id=49','component',1,284,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',212,213,0,'*'),
  (320,'aboutjoomla','Editors','editors','','using-joomla/extensions/plugins/editors','index.php?option=com_content&view=article&id=50','component',1,284,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',214,215,0,'*'),
  (321,'aboutjoomla','Editors Extended','editors-extended','','using-joomla/extensions/plugins/editors-extended','index.php?option=com_content&view=article&id=51','component',1,284,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',216,217,0,'*'),
  (322,'aboutjoomla','Search','search','','using-joomla/extensions/plugins/search','index.php?option=com_content&view=article&id=52','component',1,284,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',218,219,0,'*'),
  (323,'aboutjoomla','User','user','','using-joomla/extensions/plugins/user','index.php?option=com_content&view=article&id=53','component',1,284,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',220,221,0,'*'),
  (324,'aboutjoomla','Footer','footer','','using-joomla/extensions/modules/display-modules/footer','index.php?option=com_content&view=article&id=43','component',1,413,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',155,156,0,'*'),
  (325,'aboutjoomla','Archive','archive','','using-joomla/extensions/modules/content-modules/archive','index.php?option=com_content&view=article&id=27','component',1,411,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',133,134,0,'*'),
  (326,'aboutjoomla','Related Items','related-items','','using-joomla/extensions/modules/content-modules/related-items','index.php?option=com_content&view=article&id=55','component',1,411,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',135,136,0,'*'),
  (399,'parks','Animals','animals','','image-gallery/animals','index.php?option=com_content&view=category&layout=blog&id=37','component',1,244,2,22,0,0,'0000-00-00',0,1,'',4,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"1\",\"show_description_image\":\"0\",\"show_category_title\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"0\",\"num_intro_articles\":\"6\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"2\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"1\",\"link_category\":\"1\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"link_author\":\"\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"1\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',52,53,0,'*'),
  (400,'parks','Scenery','scenery','','image-gallery/scenery','index.php?option=com_content&view=category&layout=blog&id=36','component',1,244,2,22,0,0,'0000-00-00',0,1,'',4,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"0\",\"show_description_image\":\"0\",\"show_category_title\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"0\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"2\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"0\",\"show_category\":\"1\",\"link_category\":\"\",\"show_parent_category\":\"0\",\"link_parent_category\":\"0\",\"show_author\":\"0\",\"link_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"1\",\"show_readmore\":\"1\",\"show_icons\":\"0\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',54,55,0,'*'),
  (402,'aboutjoomla','Login Form','login-form','','using-joomla/extensions/components/users-component/login-form','index.php?option=com_users&view=login','component',1,271,5,25,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',105,106,0,'*'),
  (403,'aboutjoomla','User Profile','user-profile','','using-joomla/extensions/components/users-component/user-profile','index.php?option=com_users&view=profile','component',1,271,5,25,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',107,108,0,'*'),
  (404,'aboutjoomla','Edit User Profile','edit-user-profile','','using-joomla/extensions/components/users-component/edit-user-profile','index.php?option=com_users&view=profile&layout=edit','component',1,271,5,25,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',109,110,0,'*'),
  (405,'aboutjoomla','Registration Form','registration-form','','using-joomla/extensions/components/users-component/registration-form','index.php?option=com_users&view=registration','component',1,271,5,25,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',111,112,0,'*'),
  (406,'aboutjoomla','Username Reminder Request','username-reminder','','using-joomla/extensions/components/users-component/username-reminder','index.php?option=com_users&view=remind','component',1,271,5,25,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',113,114,0,'*'),
  (409,'aboutjoomla','Password Reset','password-reset','','using-joomla/extensions/components/users-component/password-reset','index.php?option=com_users&view=reset','component',1,271,5,25,0,0,'0000-00-00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',115,116,0,'*'),
  (410,'aboutjoomla','Feed Display','feed-display','','using-joomla/extensions/modules/display-modules/feed-display','index.php?option=com_content&view=article&id=33','component',1,413,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',153,154,0,'*'),
  (411,'aboutjoomla','Content Modules','content-modules','','using-joomla/extensions/modules/content-modules','index.php?option=com_content&view=article&id=66','component',1,281,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',126,139,0,'*'),
  (412,'aboutjoomla','User Modules','user-modules','','using-joomla/extensions/modules/user-modules','index.php?option=com_content&view=article&id=67','component',1,281,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',140,147,0,'*'),
  (413,'aboutjoomla','Display Modules','display-modules','','using-joomla/extensions/modules/display-modules','index.php?option=com_content&view=article&id=68','component',1,281,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',148,163,0,'*'),
  (414,'aboutjoomla','Utility Modules','utility-modules','','using-joomla/extensions/modules/utility-modules','index.php?option=com_content&view=article&id=69','component',1,281,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',164,173,0,'*'),
  (415,'aboutjoomla','Menu Modules','menu-modules','','using-joomla/extensions/modules/menu-modules','index.php?option=com_content&view=article&id=70','component',1,281,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',174,177,0,'*'),
  (416,'aboutjoomla','Breadcrumbs','breadcrumbs','','using-joomla/extensions/modules/utility-modules/breadcrumbs','index.php?option=com_content&view=article&id=73','component',1,414,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',171,172,0,'*'),
  (417,'aboutjoomla','Weblinks','weblinks','','using-joomla/extensions/modules/display-modules/weblinks','index.php?option=com_content&view=article&id=72','component',1,413,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',159,160,0,'*'),
  (418,'aboutjoomla','Custom HTML','custom-html','','using-joomla/extensions/modules/display-modules/custom-html','index.php?option=com_content&view=article&id=71','component',1,413,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',161,162,0,'*'),
  (419,'aboutjoomla','Beez 2','beez-2','','using-joomla/extensions/templates/beez-2','index.php?option=com_content&view=article&id=74','component',1,282,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',180,185,0,'*'),
  (420,'aboutjoomla','Template 2','template-2','','using-joomla/extensions/templates/template-2','index.php?option=com_content&view=article&id=75','component',1,282,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',186,191,0,'*'),
  (421,'aboutjoomla','Milky Way','milky-way','','using-joomla/extensions/templates/milky-way','index.php?option=com_content&view=article&id=78','component',1,282,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',192,197,0,'*'),
  (422,'aboutjoomla','Atomic','atomic','','using-joomla/extensions/templates/atomic','index.php?option=com_content&view=article&id=76','component',1,282,4,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',198,203,0,'*'),
  (423,'aboutjoomla','Typography Beez','typography-beez','','using-joomla/extensions/templates/beez-2/typography-beez','index.php?option=com_content&view=article&id=25','component',1,419,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',181,182,0,'*'),
  (424,'aboutjoomla','Home Page Beez','home-page-beez','','using-joomla/extensions/templates/beez-2/home-page-beez','index.php?option=com_content&view=featured','component',1,419,5,22,0,0,'0000-00-00',0,1,'',3,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"1\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"order_date\":\"\",\"show_pagination\":\"2\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',183,184,0,'*'),
  (425,'aboutjoomla','Typography Template 2','typography-template-2','','using-joomla/extensions/templates/template-2/typography-template-2','index.php?option=com_content&view=article&id=25','component',1,420,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"metadata\":{\"robots\":\"\",\"rights\":\"\"},\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',187,188,0,'*'),
  (426,'aboutjoomla','Home Page Template 2','home-page-template-2','','using-joomla/extensions/templates/template-2/home-page-template-2','index.php?option=com_content&view=featured','component',1,420,5,22,0,0,'0000-00-00',0,1,'',3,'{\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"order_date\":\"\",\"multi_column_order\":\"1\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',189,190,0,'*'),
  (430,'fruitshop','Contact us','contact-us','','contact-us','index.php?option=com_contact&view=category&catid=47&id=26','component',1,1,1,8,0,0,'0000-00-00',0,1,'',100,'{\"maxLevel\":\"-1\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"20\",\"show_headings\":\"\",\"filter_field\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_links\":\"1\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',251,252,0,'*'),
  (427,'fruitshop','Fruit encyclopedia','fruit-encyclopedia','','fruit-encyclopedia','index.php?option=com_contact&view=categories&id=49','component',1,1,1,8,0,0,'0000-00-00',0,1,'',100,'{\"categories_description\":\"\",\"maxLevel\":\"-1\",\"show_empty_categories\":\"1\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"1\",\"display_num\":\"\",\"show_headings\":\"\",\"filter_field\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_links\":\"1\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',247,248,0,'*'),
  (429,'fruitshop','Welcome','welcome','Fruit store front page','welcome','index.php?option=com_content&view=article&id=79','component',-2,1,1,22,0,0,'0000-00-00',0,1,'',5,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"0\",\"link_titles\":\"0\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"0\",\"link_author\":\"\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"0\",\"show_icons\":\"0\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":0,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',233,234,0,'*'),
  (431,'fruitshop','Growers','growers','','growers','index.php?option=com_content&view=category&id=76','component',1,1,1,22,0,0,'0000-00-00',0,1,'',100,'{\"maxLevel\":\"\",\"show_category_title\":\"\",\"page_subheading\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"show_headings\":\"\",\"list_show_title\":\"\",\"list_show_date\":\"\",\"date_format\":\"\",\"list_show_hits\":\"\",\"list_show_author\":\"\",\"filter_field\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_pagination_limit\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_readmore\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',249,250,0,'*'),
  (432,'fruitshop','Login ','login-','','login-','index.php?option=com_users&view=login','component',1,1,1,25,0,0,'0000-00-00',0,1,'',100,'{\"login_redirect_url\":\"\",\"logindescription_show\":\"1\",\"login_description\":\"\",\"login_image\":\"\",\"logout_redirect_url\":\"\",\"logoutdescription_show\":\"1\",\"logout_description\":\"\",\"logout_image\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',253,254,0,'*'),
  (433,'fruitshop','Directions','directions','','directions','index.php?option=com_content&view=article&id=82','component',1,1,1,22,0,0,'0000-00-00',0,1,'',100,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',255,256,0,'*'),
  (435,'mainmenu','Home','homepage','','homepage','index.php?option=com_content&view=featured','component',1,1,1,22,1,0,'0000-00-00',0,1,'',0,'{\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"3\",\"num_columns\":\"3\",\"num_links\":\"0\",\"multi_column_order\":\"1\",\"orderby_pri\":\"\",\"orderby_sec\":\"front\",\"order_date\":\"\",\"show_pagination\":\"2\",\"show_noauth\":\"\",\"show_title\":\"1\",\"link_titles\":\"0\",\"show_intro\":\"1\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"0\",\"show_author\":\"0\",\"link_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"0\",\"show_readmore\":\"1\",\"show_icons\":\"0\",\"show_print_icon\":\"0\",\"show_email_icon\":\"0\",\"show_hits\":\"0\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"1\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',259,260,1,'*'),
  (436,'aboutjoomla','Getting help','getting-help','','using-joomla/getting-help','index.php?option=com_content&view=article&id=84','component',1,280,2,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',224,225,0,'*'),
  (437,'aboutjoomla','Getting started','getting-started','','using-joomla/getting-started','index.php?option=com_content&view=article&id=85','component',1,280,2,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',60,61,0,'*'),
  (438,'mainmenu','Weblinks','weblinks','','site-map/weblinks','index.php?option=com_weblinks&view=categories&id=0','component',1,294,2,21,0,0,'0000-00-00',0,1,'',0,'{\"categories_description\":\"\",\"maxLevel\":\"-1\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"show_headings\":\"\",\"orderby_pri\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',240,241,0,'*'),
  (439,'mainmenu','Contacts','contacts','','site-map/contacts','index.php?option=com_contact&view=categories&id=0','component',1,294,2,8,0,0,'0000-00-00',0,1,'',0,'{\"categories_description\":\"\",\"maxLevel\":\"-1\",\"show_empty_categories\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"show_cat_num_articles\":\"\",\"display_num\":\"\",\"show_headings\":\"\",\"filter_field\":\"\",\"show_pagination\":\"\",\"show_noauth\":\"\",\"show_name\":\"\",\"show_position\":\"\",\"show_email\":\"\",\"show_street_address\":\"\",\"show_suburb\":\"\",\"show_state\":\"\",\"show_postcode\":\"\",\"show_country\":\"\",\"show_telephone\":\"\",\"show_mobile\":\"\",\"show_fax\":\"\",\"show_webpage\":\"\",\"show_misc\":\"\",\"show_image\":\"\",\"allow_vcard\":\"\",\"show_articles\":\"\",\"show_links\":\"1\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"show_email_form\":\"\",\"show_email_copy\":\"\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"\",\"custom_reply\":\"\",\"redirect\":\"\",\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',242,243,0,'*'),
  (443,'aboutjoomla','Article Categories','article-categories-view','','using-joomla/extensions/modules/content-modules/article-categories-view','index.php?option=com_content&view=article&id=86','component',1,411,5,22,0,0,'0000-00-00',0,1,'',0,'{\"article-allow_ratings\":\"\",\"article-allow_comments\":\"\",\"show_noauth\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"robots\":\"\",\"rights\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"show_page_heading\":1,\"page_title\":\"\",\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"secure\":0}',137,138,0,'*');

COMMIT;

#
# Data for the `jos_menu_types` table  (LIMIT 0,500)
#

INSERT INTO `jos_menu_types` (`id`, `menutype`, `title`, `description`) VALUES 
  (2,'usermenu','User Menu','A Menu for logged in Users'),
  (3,'top','Top','Links for major types of users'),
  (4,'aboutjoomla','About Joomla','All about Joomla!'),
  (5,'parks','Australian Parks','Main menu for a site about Australian  parks'),
  (6,'mainmenu','Main Menu','Simple Home Menu'),
  (7,'fruitshop','Fruit Shop','Menu for the sample shop site.');

COMMIT;

#
# Data for the `jos_modules` table  (LIMIT 0,500)
#

INSERT INTO `jos_modules` (`id`, `title`, `note`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `published`, `module`, `access`, `showtitle`, `params`, `client_id`, `language`) VALUES 
  (1,'Main Menu','','',1,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"mainmenu\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"_menu\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
  (2,'Login','','',1,'login',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_login',1,1,'',1,'*'),
  (3,'Popular Articles','','',3,'cpanel',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_popular',3,1,'{\"count\":\"5\",\"catid\":\"\",\"user_id\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',1,'*'),
  (4,'Recently Added Articles','','',4,'cpanel',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_latest',3,1,'{\"count\":\"5\",\"ordering\":\"c_dsc\",\"catid\":\"\",\"user_id\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',1,'*'),
  (6,'Unread Messages','','',1,'header',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_unread',3,1,'',1,'*'),
  (7,'Online Users','','',2,'header',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_online',3,1,'',1,'*'),
  (8,'Toolbar','','',1,'toolbar',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_toolbar',3,1,'',1,'*'),
  (9,'Quick Icons','','',1,'icon',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_quickicon',3,1,'',1,'*'),
  (10,'Logged-in Users','','',2,'cpanel',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_logged',3,1,'',1,'*'),
  (12,'Admin Menu','','',1,'menu',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',3,1,'',1,'*'),
  (13,'Admin Submenu','','',1,'submenu',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_submenu',3,1,'',1,'*'),
  (14,'User Status','','',1,'status',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_status',3,1,'',1,'*'),
  (15,'Title','','',1,'title',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_title',3,1,'',1,'*'),
  (16,'User Menu','','',2,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',2,1,'{\"menutype\":\"usermenu\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"_menu\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
  (17,'Login Form','','',8,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_login',1,1,'{\"greeting\":\"1\",\"name\":\"0\"}',0,'*'),
  (18,'Breadcrumbs','','',1,'position-2',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_breadcrumbs',1,1,'{\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"showHome\":\"1\",\"homeText\":\"Home\",\"showComponent\":\"1\",\"separator\":\"\"}',0,'*'),
  (19,'Banners','','',1,'position-5',0,'0000-00-00','0000-00-00','0000-00-00',0,'mod_banners',1,1,'{\"target\":\"1\",\"count\":\"1\",\"cid\":\"1\",\"catid\":\"27\",\"tag_search\":\"0\",\"ordering\":\"0\",\"header_text\":\"\",\"footer_text\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"0\"}',0,'*'),
  (20,'Top','','',1,'position-1',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"top\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (22,'Australian Parks ','','',3,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"parks\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (23,'About Joomla!','','',4,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"aboutjoomla\",\"startLevel\":\"0\",\"endLevel\":\"2\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"_menu\",\"cache\":\"0\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
  (24,'Extensions','','',9,'position-3',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"aboutjoomla\",\"startLevel\":\"3\",\"endLevel\":\"6\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
  (25,'Site Map','','',1,'sitemapload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,0,'{\"menutype\":\"thissite\",\"startLevel\":\"1\",\"endLevel\":\"2\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"sitemap\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
  (26,'This Site','','',5,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"mainmenu\",\"startLevel\":\"0\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"_menu\",\"cache\":\"0\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
  (27,'Mod_Archive','','',1,'archiveload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_archive',1,1,'{\"count\":\"10\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
  (28,'Mod_Articles_Latest','','',1,'articleslatestload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_latest',1,1,'{\"count\":\"5\",\"ordering\":\"c_dsc\",\"user_id\":\"0\",\"show_front\":\"1\",\"catid\":\"40\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (29,'Mod_Articles_Popular','','',1,'articlespopularload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_popular',1,1,'{\"show_front\":\"1\",\"count\":\"5\",\"catid\":\"40\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (30,'Mod_Feed','','',1,'feeddisplayload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_feed',1,1,'{\"rssurl\":\"http:\\/\\/community.joomla.org\\/blogs\\/community.feed?type=rss\",\"rssrtl\":\"0\",\"rsstitle\":\"1\",\"rssdesc\":\"1\",\"rssimage\":\"1\",\"rssitems\":\"3\",\"rssitemdesc\":\"1\",\"word_count\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (31,'News Flash: Latest','','',1,'newsflashload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_news',1,1,'{\"catid\":\"40\",\"image\":\"0\",\"item_title\":\"0\",\"link_titles\":\"\",\"showLastSeparator\":\"1\",\"readmore\":\"1\",\"count\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (32,'News Flash: Random','','',1,'newsflashload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_news',1,1,'{\"catid\":\"32\",\"image\":\"0\",\"item_title\":\"0\",\"link_titles\":\"\",\"showLastSeparator\":\"1\",\"readmore\":\"0\",\"count\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (33,'Mod_Random_Image','','',1,'randomimageload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_random_image',1,1,'{\"type\":\"jpg\",\"folder\":\"images\\/sampledata\\/parks\\/animals\",\"link\":\"\",\"width\":\"180\",\"height\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (34,'Mod_Related_Items','','',1,'relateditemsload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_related_items',1,1,'{\"showDate\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (35,'Mod_Search','','',1,'searchload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_search',1,1,'{\"width\":\"20\",\"text\":\"\",\"button\":\"\",\"button_pos\":\"right\",\"imagebutton\":\"\",\"button_text\":\"\",\"set_itemid\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (36,'Mod_Stats','','',1,'statisticsload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_stats',1,1,'{\"serverinfo\":\"1\",\"siteinfo\":\"1\",\"counter\":\"1\",\"increase\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (37,'Mod_Syndicate','','',1,'syndicateload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_syndicate',1,1,'{\"text\":\"Feed Entries\",\"format\":\"rss\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (38,'Mod_Users_Latest','','',1,'userslatestload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_users_latest',1,1,'{\"shownumber\":\"5\",\"linknames\":\"0\",\"linktowhat\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (39,'Mod_Whoisonline','','',1,'whosonlineload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_whosonline',1,1,'{\"showmode\":\"2\",\"linknames\":\"0\",\"linktowhat\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (40,'Mod_Wrapper','','',1,'wrapperload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_wrapper',1,1,'{\"url\":\"http:\\/\\/fsf.org\",\"add\":\"1\",\"scrolling\":\"auto\",\"width\":\"100%\",\"height\":\"200\",\"height_auto\":\"1\",\"target\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (41,'Mod_Footer','','',1,'footerload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_footer',1,1,'{\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (44,'Mod_Login','','',1,'loginload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_login',1,1,'{\"pretext\":\"\",\"posttext\":\"\",\"login\":\"280\",\"logout\":\"280\",\"greeting\":\"1\",\"name\":\"0\",\"usesecure\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (45,'Mod_Menu','','',1,'menuload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"thissite\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"maxdepth\":\"10\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (47,'Latest Park Blogs','','',6,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_latest',1,1,'{\"count\":\"5\",\"ordering\":\"c_dsc\",\"user_id\":\"0\",\"show_front\":\"1\",\"catid\":\"35\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (48,'Mod_Custom','','<p>This is a custom html module. That means you can enter whatever content you want.</p>',1,'customload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_custom',1,1,'{\"prepare_content\":\"1\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (49,'mod_Weblinks','','',1,'weblinksload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_weblinks',1,1,'{\"catid\":\"21\",\"count\":\"5\",\"ordering\":\"title\",\"direction\":\"asc\",\"target\":\"3\",\"description\":\"0\",\"hits\":\"0\",\"count_clicks\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (52,'Mod_Breadcrumb','','',1,'breadcrumbsload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_breadcrumbs',1,1,'{\"showHome\":\"1\",\"homeText\":\"Home\",\"showLast\":\"1\",\"separator\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',0,'*'),
  (61,'Articles Categories','','',1,'articlescategoriesload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_articles_categories',1,1,'{\"parent\":\"29\",\"show_description\":\"0\",\"show_children\":\"0\",\"maxlevel\":\"0\",\"count\":\"0\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"owncache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (56,'Mod_Banners','','',0,'bannersload',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_banners',1,1,'{\"target\":\"1\",\"count\":\"1\",\"cid\":\"1\",\"catid\":\"30\",\"tag_search\":\"0\",\"ordering\":\"0\",\"header_text\":\"\",\"footer_text\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (57,'Fruit Shop','','',7,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,1,'{\"menutype\":\"fruitshop\",\"startLevel\":\"0\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
  (58,'Special!','','<p>This week we have a special, half price on delicious oranges!</p>\r\n<p><em>This module can only be seen by people in the customers group or higher.</em></p>',7,'position-3',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_custom',4,1,'{\"prepare_content\":\"1\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
  (60,'Examples','','',1,'position-5',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_menu',1,0,'{\"menutype\":\"aboutjoomla\",\"startLevel\":\"5\",\"endLevel\":\"6\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
  (62,'Switcher','','',1,'position-7',0,'0000-00-00','0000-00-00','0000-00-00',0,'mod_languages',1,0,'{\"header_text\":\"\",\"footer_text\":\"\",\"image\":\"1\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
  (63,'Banner Flickr','','',1,'position-5',0,'0000-00-00','0000-00-00','0000-00-00',1,'mod_banners',1,1,'{\"target\":\"1\",\"count\":\"1\",\"cid\":\"0\",\"catid\":\"30\",\"tag_search\":\"0\",\"ordering\":\"random\",\"header_text\":\"\",\"footer_text\":\"\",\"layout\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*');

COMMIT;

#
# Data for the `jos_modules_menu` table  (LIMIT 0,500)
#

INSERT INTO `jos_modules_menu` (`moduleid`, `menuid`) VALUES 
  (1,101),
  (2,0),
  (3,0),
  (4,0),
  (5,0),
  (6,0),
  (7,0),
  (8,0),
  (9,0),
  (10,0),
  (11,0),
  (12,0),
  (13,0),
  (14,0),
  (15,0),
  (16,-433),
  (16,-432),
  (16,-431),
  (16,-430),
  (16,-429),
  (16,-427),
  (16,-400),
  (16,-399),
  (16,-296),
  (16,-244),
  (16,-243),
  (16,-242),
  (16,-234),
  (16,-231),
  (17,205),
  (17,435),
  (18,0),
  (19,0),
  (20,0),
  (22,206),
  (22,231),
  (22,234),
  (22,242),
  (22,243),
  (22,244),
  (22,296),
  (22,399),
  (22,400),
  (23,-434),
  (23,-433),
  (23,-432),
  (23,-431),
  (23,-430),
  (23,-427),
  (23,-400),
  (23,-399),
  (23,-296),
  (23,-244),
  (23,-243),
  (23,-242),
  (23,-234),
  (23,-231),
  (23,-206),
  (24,227),
  (24,229),
  (24,249),
  (24,251),
  (24,252),
  (24,253),
  (24,254),
  (24,255),
  (24,256),
  (24,257),
  (24,259),
  (24,260),
  (24,262),
  (24,263),
  (24,265),
  (24,266),
  (24,267),
  (24,268),
  (24,270),
  (24,271),
  (24,272),
  (24,273),
  (24,274),
  (24,275),
  (24,276),
  (24,277),
  (24,281),
  (24,282),
  (24,283),
  (24,284),
  (24,285),
  (24,286),
  (24,300),
  (24,301),
  (24,302),
  (24,303),
  (24,304),
  (24,305),
  (24,306),
  (24,307),
  (24,309),
  (24,310),
  (24,311),
  (24,312),
  (24,313),
  (24,314),
  (24,316),
  (24,317),
  (24,318),
  (24,319),
  (24,320),
  (24,321),
  (24,322),
  (24,323),
  (24,324),
  (24,325),
  (24,326),
  (24,402),
  (24,403),
  (24,404),
  (24,405),
  (24,406),
  (24,409),
  (24,410),
  (24,411),
  (24,412),
  (24,413),
  (24,414),
  (24,415),
  (24,416),
  (24,417),
  (24,418),
  (24,419),
  (24,420),
  (24,421),
  (24,422),
  (24,423),
  (24,424),
  (24,425),
  (24,426),
  (25,294),
  (26,-434),
  (26,-433),
  (26,-432),
  (26,-431),
  (26,-430),
  (26,-427),
  (26,-400),
  (26,-399),
  (26,-296),
  (26,-244),
  (26,-243),
  (26,-242),
  (26,-234),
  (26,-231),
  (26,-206),
  (26,-205),
  (27,325),
  (28,310),
  (29,302),
  (30,410),
  (31,309),
  (32,309),
  (33,307),
  (34,326),
  (35,306),
  (36,304),
  (37,311),
  (38,300),
  (39,101),
  (39,301),
  (40,313),
  (41,324),
  (44,312),
  (45,303),
  (47,231),
  (47,234),
  (47,242),
  (47,243),
  (47,244),
  (47,296),
  (47,399),
  (47,400),
  (48,418),
  (49,417),
  (52,294),
  (52,416),
  (56,0),
  (57,206),
  (57,427),
  (57,430),
  (57,431),
  (57,432),
  (57,433),
  (57,434),
  (58,427),
  (58,429),
  (58,430),
  (58,431),
  (58,432),
  (58,433),
  (60,227),
  (60,229),
  (60,249),
  (60,251),
  (60,252),
  (60,253),
  (60,254),
  (60,255),
  (60,256),
  (60,257),
  (60,259),
  (60,260),
  (60,262),
  (60,263),
  (60,265),
  (60,266),
  (60,267),
  (60,270),
  (60,271),
  (60,272),
  (60,273),
  (60,274),
  (60,275),
  (60,276),
  (60,281),
  (60,282),
  (60,283),
  (60,284),
  (60,285),
  (60,286),
  (60,300),
  (60,301),
  (60,302),
  (60,303),
  (60,304),
  (60,305),
  (60,306),
  (60,307),
  (60,309),
  (60,310),
  (60,311),
  (60,312),
  (60,313),
  (60,314),
  (60,316),
  (60,317),
  (60,318),
  (60,319),
  (60,320),
  (60,321),
  (60,322),
  (60,323),
  (60,324),
  (60,325),
  (60,326),
  (60,402),
  (60,403),
  (60,404),
  (60,405),
  (60,406),
  (60,409),
  (60,410),
  (60,411),
  (60,412),
  (60,413),
  (60,414),
  (60,415),
  (60,416),
  (60,417),
  (60,418),
  (60,419),
  (60,420),
  (60,421),
  (60,422),
  (60,423),
  (60,424),
  (60,425),
  (60,426),
  (61,443),
  (62,0),
  (63,0);

COMMIT;

#
# Data for the `jos_newsfeeds` table  (LIMIT 0,500)
#

INSERT INTO `jos_newsfeeds` (`catid`, `id`, `name`, `alias`, `link`, `filename`, `published`, `numarticles`, `cache_time`, `checked_out`, `checked_out_time`, `ordering`, `rtl`, `access`, `language`, `params`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `metakey`, `metadesc`, `metadata`, `xreference`, `publish_up`, `publish_down`) VALUES 
  (28,1,'Joomla! Announcements','joomla-announcements','http://www.joomla.org/announcements.feed?type=rss',NULL,1,5,3600,0,'0000-00-00',1,0,1,'en-GB','{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_cat_description\":\"\",\"show_cat_items\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\"}','0000-00-00',0,'','0000-00-00',0,'','','','','0000-00-00','0000-00-00'),
  (28,2,'New Joomla! Extensions','new-joomla-extensions','http://feeds.joomla.org/JoomlaExtensions',NULL,1,5,3600,0,'0000-00-00',1,0,1,'en-GB','{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_cat_description\":\"\",\"show_cat_items\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\"}','0000-00-00',0,'','0000-00-00',0,'','','','','0000-00-00','0000-00-00'),
  (28,3,'Joomla! Security News','joomla-security-news','http://feeds.joomla.org/JoomlaSecurityNews',NULL,1,5,3600,0,'0000-00-00',1,0,1,'en-GB','{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_cat_description\":\"\",\"show_cat_items\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\"}','0000-00-00',0,'','0000-00-00',0,'','','','','0000-00-00','0000-00-00'),
  (28,4,'Joomla! Connect','joomla-connect','http://feeds.joomla.org/JoomlaConnect',NULL,1,5,3600,0,'0000-00-00',1,0,1,'en-GB','{\"show_headings\":\"\",\"show_name\":\"\",\"show_articles\":\"\",\"show_link\":\"\",\"show_cat_description\":\"\",\"show_cat_items\":\"\",\"show_feed_image\":\"\",\"show_feed_description\":\"\",\"show_item_description\":\"\",\"feed_word_count\":\"0\"}','0000-00-00',0,'','0000-00-00',0,'','','','','0000-00-00','0000-00-00');

COMMIT;

#
# Data for the `jos_session` table  (LIMIT 0,500)
#

INSERT INTO `jos_session` (`session_id`, `client_id`, `guest`, `time`, `data`, `userid`, `username`, `usertype`) VALUES 
  ('17cb3032f7e51d1a5eac026842debce2',0,1,'1273787627','__default|a:7:{s:15:\"session.counter\";i:3;s:19:\"session.timer.start\";i:1273787603;s:18:\"session.timer.last\";i:1273787615;s:17:\"session.timer.now\";i:1273787627;s:22:\"session.client.browser\";s:88:\"Mozilla/5.0 (Windows; U; Windows NT 6.1; pt-BR; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3\";s:8:\"registry\";O:9:\"JRegistry\":1:{s:7:\"',0,'','');

COMMIT;

#
# Data for the `jos_template_styles` table  (LIMIT 0,500)
#

INSERT INTO `jos_template_styles` (`id`, `template`, `client_id`, `home`, `title`, `params`) VALUES 
  (1,'rhuk_milkyway',0,1,'Milkyway - Default','{\"colorVariation\":\"blue\",\"backgroundVariation\":\"blue\",\"widthStyle\":\"fmax\"}'),
  (2,'bluestork',1,1,'Bluestork - Default','{\"useRoundedCorners\":\"1\",\"showSiteName\":\"0\"}'),
  (3,'atomic',0,0,'Atomic - Default','{}'),
  (4,'beez_20',0,0,'Beez2 - Default','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"logo\":\"-1\",\"navposition\":\"left\",\"templatecolor\":\"nature\",\"html5\":\"0\"}'),
  (5,'hathor',1,0,'Hathor - Default','{\"showSiteName\":\"0\",\"highContrast\":\"0\",\"boldText\":\"0\",\"altMenu\":\"0\"}'),
  (100,'rhuk_milkyway',0,0,'rhuk_milkyway Green','{\"colorVariation\":\"green\",\"backgroundVariation\":\"green\",\"widthStyle\":\"fmax\"}'),
  (111,'rhuk_milkyway',0,0,'rhuk_milkyway Red','{\"colorVariation\":\"orange\",\"backgroundVariation\":\"orange\",\"widthStyle\":\"small\"}'),
  (113,'beez_20',0,0,'Default Nature','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"logo\":\"-1\",\"navposition\":\"left\",\"templatecolor\":\"nature\",\"html5\":\"0\"}');

COMMIT;

#
# Data for the `jos_user_usergroup_map` table  (LIMIT 0,500)
#

INSERT INTO `jos_user_usergroup_map` (`user_id`, `group_id`) VALUES 
  (42,8);

COMMIT;

#
# Data for the `jos_usergroups` table  (LIMIT 0,500)
#

INSERT INTO `jos_usergroups` (`id`, `parent_id`, `lft`, `rgt`, `title`) VALUES 
  (1,0,1,20,'Public'),
  (2,1,8,19,'Registered'),
  (3,2,11,16,'Author'),
  (4,3,12,15,'Editor'),
  (5,4,13,14,'Publisher'),
  (6,1,2,7,'Manager'),
  (7,6,3,6,'Administrator'),
  (8,7,4,5,'Super Users'),
  (12,2,17,18,'Customer Group'),
  (10,3,14,15,'Shop Suppliers');

COMMIT;

#
# Data for the `jos_users` table  (LIMIT 0,500)
#

INSERT INTO `jos_users` (`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `sendEmail`, `registerDate`, `lastvisitDate`, `activation`, `params`) VALUES 
  (42,'Super User','admin','julio@noix.com.br','81800ac41a40d4337e9467bcc91ff22a:3xpUH7iLkwxq6C2XOzRI39RyWugMQYh6','deprecated',0,1,'2010-05-09 18:52:04','2010-05-10 21:36:00','','');

COMMIT;

#
# Data for the `jos_viewlevels` table  (LIMIT 0,500)
#

INSERT INTO `jos_viewlevels` (`id`, `title`, `ordering`, `rules`) VALUES 
  (1,'Public',0,'[]'),
  (2,'Registered',1,'[6,2]'),
  (3,'Special',2,'[6,7,8]'),
  (4,'Customer Access Level',3,'[6,3,12]');

COMMIT;

#
# Data for the `jos_weblinks` table  (LIMIT 0,500)
#

INSERT INTO `jos_weblinks` (`id`, `catid`, `sid`, `title`, `alias`, `url`, `description`, `date`, `hits`, `state`, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `access`, `params`, `language`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `metakey`, `metadesc`, `metadata`, `featured`, `xreference`, `publish_up`, `publish_down`) VALUES 
  (1,20,0,'Joomla!','joomla','http://www.joomla.org','Home of Joomla!','2005-02-14 15:19:02',3,1,0,'0000-00-00',1,0,1,1,'{\"target\":\"0\"}','en-GB','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (2,21,0,'php.net','php','http://www.php.net','The language that Joomla! is developed in','2004-07-07 11:33:24',6,1,0,'0000-00-00',3,0,1,1,'{}','en-GB','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (3,21,0,'MySQL','mysql','http://www.mysql.com','The database that Joomla! uses','2004-07-07 10:18:31',1,1,0,'0000-00-00',5,0,1,1,'{}','en-GB','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (4,20,0,'OpenSourceMatters','opensourcematters','http://www.opensourcematters.org','Home of OSM','2005-02-14 15:19:02',11,1,0,'0000-00-00',2,0,1,1,'{\"target\":\"0\"}','en-GB','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (5,21,0,'Joomla! - Forums','joomla-forums','http://forum.joomla.org','Joomla! Forums','2005-02-14 15:19:02',4,1,0,'0000-00-00',4,0,1,1,'{\"target\":\"0\"}','en-GB','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (6,21,0,'Ohloh Tracking of Joomla!','ohloh-tracking-of-joomla','http://www.ohloh.net/projects/20','Objective reports from Ohloh about Joomla\'s development activity. Joomla! has some star developers with serious kudos.','2007-07-19 09:28:31',1,1,0,'0000-00-00',6,0,1,1,'{\"target\":\"0\"}','en-GB','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (7,44,0,'Baw Baw National Park','baw-baw-national-park','http://www.parkweb.vic.gov.au/1park_display.cfm?park=44','Park of the Austalian Alps National Parks system, Baw Baw  features sub alpine vegetation, beautiful views, and opportunities for hiking, skiing and other outdoor activities.','0000-00-00',0,1,0,'0000-00-00',7,0,1,1,'{\"target\":\"0\"}','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (8,44,0,'Kakadu','kakadu','http://www.environment.gov.au/parks/kakadu/index.html','Kakadu is known for both its cultural heritage and its natural features. It is one of a small number of places listed as World Heritage Places for both reasons. Extensive rock art is found there.','0000-00-00',0,1,0,'0000-00-00',8,0,1,1,'{\"target\":\"0\"}','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00'),
  (9,44,0,'Pulu Keeling','pulu-keeling','http://www.environment.gov.au/parks/cocos/index.html','Located on an atoll 2000 kilometers north of Perth, Pulu Keeling is Australia\'s smallest national park.','0000-00-00',0,1,0,'0000-00-00',9,0,1,1,'{\"target\":\"0\"}','*','0000-00-00',0,'','0000-00-00',0,'','','',0,'','0000-00-00','0000-00-00');

COMMIT;

