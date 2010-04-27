# $Id$

# 1.5 to 1.6

-- ----------------------------------------------------------------
-- jos_assets
-- ----------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `jos_assets` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- ----------------------------------------------------------------
-- jos_banners
-- ----------------------------------------------------------------

ALTER TABLE `jos_banner`
 RENAME TO `jos_banners`;

ALTER TABLE `jos_banners`
 CHANGE COLUMN `bid` `id` INTEGER NOT NULL auto_increment;
 
ALTER TABLE `jos_banner`
 CHANGE `custombannercode` `custombannercode` varchar(2048) NOT NULL;

ALTER TABLE `jos_banners`
 MODIFY COLUMN `type` INTEGER NOT NULL DEFAULT '0';

ALTER TABLE `jos_banners`
 CHANGE COLUMN `showBanner` `state` TINYINT(3) NOT NULL DEFAULT '0';

ALTER TABLE `jos_banners`
 CHANGE COLUMN `tags` `metakey` TEXT NOT NULL AFTER `state`;

ALTER TABLE `jos_banners`
 CHANGE COLUMN `date` `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `params`;

ALTER TABLE `jos_banners`
 DROP COLUMN `editor`;

ALTER TABLE `jos_banners`
 MODIFY COLUMN `catid` INTEGER UNSIGNED NOT NULL DEFAULT 0 AFTER `state`;

ALTER TABLE `jos_banners`
 MODIFY COLUMN `description` TEXT NOT NULL AFTER `catid`;

ALTER TABLE `jos_banners`
 MODIFY COLUMN `sticky` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `description`;

ALTER TABLE `jos_banners`
 MODIFY COLUMN `ordering` INTEGER NOT NULL DEFAULT 0 AFTER `sticky`;

ALTER TABLE `jos_banners`
 MODIFY COLUMN `params` TEXT NOT NULL AFTER `metakey`;

ALTER TABLE `jos_banners`
 ADD COLUMN `own_prefix` TINYINT(1) NOT NULL DEFAULT '0' AFTER `params`;

ALTER TABLE `jos_banners`
 ADD COLUMN `metakey_prefix` VARCHAR(255) NOT NULL DEFAULT '' AFTER `own_prefix`;

ALTER TABLE `jos_banners`
 ADD COLUMN `purchase_type` TINYINT NOT NULL DEFAULT '-1' AFTER `metakey_prefix`;

ALTER TABLE `jos_banners`
 ADD COLUMN `track_clicks` TINYINT NOT NULL DEFAULT '-1' AFTER `purchase_type`;

ALTER TABLE `jos_banners`
 ADD COLUMN `track_impressions` TINYINT NOT NULL DEFAULT '-1' AFTER `track_clicks`;

ALTER TABLE `jos_banners`
 ADD COLUMN `reset` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `publish_down`;

UPDATE `jos_banners`
 SET `type`=1 WHERE TRIM(`custombannercode`)!='';

UPDATE `jos_banners`
 SET `params` = concat( '"flash":{"', REPLACE( REPLACE( REPLACE( TRIM( '\n' FROM `params` ) , '=', '":"' ) , '\n', '","' ) , '\r', '' ) , '"},' ) WHERE TRIM( `params` ) != '';

UPDATE `jos_banners`
 SET `params` = '"flash":{"height":"0","width":"0"},' WHERE TRIM( `params` ) = '';

UPDATE `jos_banners`
 SET `params` = CONCAT('{"custom":{"bannercode":"',REPLACE(`custombannercode`,'"','\\"'),'"},"alt":{"alt":""},',`params`,'"image":{"url":"',`imageurl`,'"}}');

ALTER TABLE `jos_banners`
 DROP COLUMN `custombannercode`;

ALTER TABLE `jos_banners`
 DROP COLUMN `imageurl`;

ALTER TABLE `jos_banners`
 DROP INDEX `viewbanner`;

ALTER TABLE `jos_banners`
 ADD INDEX `idx_own_prefix` (`own_prefix`);

ALTER TABLE `jos_banners`
 ADD INDEX `idx_metakey_prefix` (`metakey_prefix`);

-- ----------------------------------------------------------------
-- jos_banner_clients
-- ----------------------------------------------------------------

ALTER TABLE `jos_bannerclient`
 RENAME TO `jos_banner_clients`;

ALTER TABLE `jos_banner_clients`
 CHANGE COLUMN `cid` `id` INTEGER NOT NULL auto_increment;

ALTER TABLE `jos_banner_clients`
 DROP COLUMN `editor`;

ALTER TABLE `jos_banner_clients`
 ADD COLUMN `state` TINYINT(3) NOT NULL DEFAULT '0' AFTER `extrainfo`;

ALTER TABLE `jos_banner_clients`
 ADD COLUMN `metakey` TEXT NOT NULL;

ALTER TABLE `jos_banner_clients`
 ADD COLUMN `own_prefix` TINYINT NOT NULL DEFAULT '0';

ALTER TABLE `jos_banner_clients`
 ADD COLUMN `metakey_prefix` VARCHAR(255) NOT NULL DEFAULT '';

ALTER TABLE `jos_banner_clients`
 ADD COLUMN `purchase_type` TINYINT NOT NULL DEFAULT '-1';

ALTER TABLE `jos_banner_clients`
 ADD COLUMN `track_clicks` TINYINT NOT NULL DEFAULT '-1';

ALTER TABLE `jos_banner_clients`
 ADD COLUMN `track_impressions` TINYINT NOT NULL DEFAULT '-1';

ALTER TABLE `jos_banner_clients`
 ADD INDEX `idx_own_prefix` (`own_prefix`);

ALTER TABLE `jos_banner_clients`
 ADD INDEX `idx_metakey_prefix` (`metakey_prefix`);

UPDATE `jos_banner_clients`
 SET `state`=1;

-- ----------------------------------------------------------------
-- jos_banner_tracks
-- ----------------------------------------------------------------

ALTER TABLE `jos_bannertrack`
 RENAME TO `jos_banner_tracks`;

ALTER TABLE `jos_banner_tracks`
 ADD COLUMN `count` INTEGER UNSIGNED NOT NULL DEFAULT '0';

INSERT `jos_banner_tracks`
 SELECT `track_date`,`track_type`,`banner_id`,count('*') AS `count`
 FROM `jos_banner_tracks`
 GROUP BY `track_date`,`track_type`,`banner_id`;

DELETE FROM `jos_banner_tracks`
 WHERE `count`=0;

ALTER TABLE `jos_banner_tracks`
 ADD PRIMARY KEY (`track_date`, `track_type`, `banner_id`);

ALTER TABLE `jos_banner_tracks`
 ADD INDEX `idx_track_date` (`track_date`);

ALTER TABLE `jos_banner_tracks`
 ADD INDEX `idx_track_type` (`track_type`);

ALTER TABLE `jos_banner_tracks`
 ADD INDEX `idx_banner_id` (`banner_id`);

-- ----------------------------------------------------------------
-- jos_categories
-- ----------------------------------------------------------------

ALTER TABLE `jos_categories`
 MODIFY COLUMN `description` VARCHAR(5120) NOT NULL DEFAULT '';

ALTER TABLE `jos_categories`
 MODIFY COLUMN `params` VARCHAR(2048) NOT NULL DEFAULT '';

ALTER TABLE `jos_categories`
 ADD COLUMN `lft` INTEGER NOT NULL DEFAULT 0 COMMENT 'Nested set lft.' AFTER `parent_id`;

ALTER TABLE `jos_categories`
 ADD COLUMN `rgt` INTEGER NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.' AFTER `lft`;

ALTER TABLE `jos_categories`
 ADD COLUMN `level` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `rgt`;

ALTER TABLE `jos_categories`
 ADD COLUMN `path` VARCHAR(1024) NOT NULL DEFAULT '' AFTER `level`;

ALTER TABLE `jos_categories`
 ADD COLUMN `note` VARCHAR(255) NOT NULL DEFAULT '' AFTER `alias`;

ALTER TABLE `jos_categories`
 ADD COLUMN `metadesc` VARCHAR(1024) NOT NULL COMMENT 'The meta description for the page.' AFTER `params`;

ALTER TABLE `jos_categories`
 ADD COLUMN `metakey` VARCHAR(1024) NOT NULL COMMENT 'The meta keywords for the page.' AFTER `metadesc`;

ALTER TABLE `jos_categories`
 ADD COLUMN `metadata` VARCHAR(2048) NOT NULL COMMENT 'JSON encoded metadata properties.' AFTER `metakey`;

ALTER TABLE `jos_categories`
 ADD COLUMN `created_user_id` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `metadata`;

ALTER TABLE `jos_categories`
 ADD COLUMN `created_time` TIMESTAMP NOT NULL AFTER `created_user_id`;

ALTER TABLE `jos_categories`
 ADD COLUMN `modified_user_id` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `created_time`;

ALTER TABLE `jos_categories`
 ADD COLUMN `modified_time` TIMESTAMP NOT NULL AFTER `modified_user_id`;

ALTER TABLE `jos_categories`
 ADD COLUMN `hits` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `modified_time`;

ALTER TABLE `jos_categories`
 ADD COLUMN `language` CHAR(7) NOT NULL AFTER `hits`;

ALTER TABLE `jos_categories`
 ADD INDEX idx_alias(`alias`);

ALTER TABLE `jos_categories`
 ADD INDEX idx_path(`path`);

ALTER TABLE `jos_categories`
 ADD INDEX idx_left_right(`lft`, `rgt`);

ALTER TABLE `jos_categories`
 DROP COLUMN `ordering`;

-- TODO: Merge from sections and add uncategorised nodes.

-- ----------------------------------------------------------------
-- jos_components
-- ----------------------------------------------------------------

ALTER TABLE `jos_components`
 MODIFY COLUMN `enabled` TINYINT(4) UNSIGNED NOT NULL DEFAULT 1;

UPDATE `jos_components`
 SET admin_menu_link = 'option=com_content&view=articles'
 WHERE link = 'option=com_content';

INSERT INTO `#__components` VALUES
 (null, 'Articles', '', 0, 0, 'option=com_content&view=articles', 'com_content_Articles', 'com_content', 1, '', 1, '{}', 1),
 (null, 'Categories', '', 0, 0, 'option=com_categories&view=categories&extension=com_content', 'com_content_Categories', 'com_content', 2, '', 1, '{}', 1),
 (null, 'Featured', '', 0, 0, 'option=com_content&view=featured', 'com_content_Featured', 'com_content', 3, '', 1, '{}', 1),
 (null, 'Redirects', '', 0, 0, 'option=com_redirect', 'Manage Redirects', 'com_redirect', 0, 'js/ThemeOffice/component.png', 1, '{}', 1),
 (null, 'Checkin', '', 0, 0, 'option=com_checkin', 'Checkin', 'com_checkin', 0, 'js/ThemeOffice/component.png', 1, '{}', 1);

UPDATE `jos_components` AS a
 LEFT JOIN `jos_components` AS b ON b.link='option=com_content'
 SET a.parent = b.id
 WHERE a.link = ''
  AND a.option = 'com_content';


-- ----------------------------------------------------------------
-- jos_contact_details
-- ----------------------------------------------------------------
 ALTER TABLE `jos_contact_details`
  ADD COLUMN `sortname1` varchar(255) NOT NULL,
  ADD COLUMN `sortname2` varchar(255) NOT NULL,
  ADD COLUMN `sortname3` varchar(255) NOT NULL,
  ADD COLUMN `language` varchar(10) NOT NULL,
  ADD COLUMN  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  ADD COLUMN   `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  ADD COLUMN   `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN   `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  ADD COLUMN   `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  ADD COLUMN   `metakey` text NOT NULL,
  ADD COLUMN   `metadesc` text NOT NULL,
  ADD COLUMN   `metadata` text NOT NULL,
  ADD COLUMN   `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  ADD COLUMN   `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  ADD COLUMN   `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  ADD COLUMN   `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  CHANGE `published` `published` tinyint(1) NOT NULL DEFAULT '0',
  DROP INDEX `catid`,
  ADD  KEY `idx_access` (`access`),
  ADD  KEY `idx_checkout` (`checked_out`),
  ADD  KEY `idx_published` (`published`),
  ADD  KEY `idx_catid` (`catid`),
  ADD  KEY `idx_createdby` (`created_by`),
  ADD  KEY `idx_featured_catid` (`featured`,`catid`),
  ADD  KEY `idx_language` (`language`),
  ADD  KEY `idx_xreference` (`xreference`);
-- ----------------------------------------------------------------
-- jos_content
-- ----------------------------------------------------------------

ALTER TABLE `jos_content`
 ADD COLUMN `asset_id` INTEGER UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to the jos_assets table.' AFTER `id`;

ALTER TABLE `jos_content`
 ADD COLUMN `featured` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Set if article is featured.' AFTER `metadata`;

ALTER TABLE `jos_content`
 ADD INDEX idx_featured_catid(`featured`, `catid`);

ALTER TABLE `jos_content`
 ADD COLUMN `language` CHAR(7) NOT NULL COMMENT 'The language code for the article.' AFTER `featured`;

ALTER TABLE `jos_content`
 ADD COLUMN `xreference` VARCHAR(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.' AFTER `language`;

ALTER TABLE `jos_content`
 ADD INDEX idx_language(`language`);

ALTER TABLE `jos_content`
 ADD INDEX idx_xreference(`xreference`);

UPDATE `jos_content` AS a
 SET a.featured = 1
 WHERE a.id IN (
 	SELECT f.content_id
 	FROM `jos_content_frontpage` AS f
 );

 ALTER TABLE `jos_content` CHANGE `attribs` `attribs` VARCHAR( 5120 ) NOT NULL;

-- ----------------------------------------------------------------
-- jos_extensions (new) and migration
-- ----------------------------------------------------------------

CREATE TABLE  `#__extensions` (
  `extension_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `type` varchar(20) NOT NULL default '',
  `element` varchar(100) NOT NULL default '',
  `folder` varchar(100) NOT NULL default '',
  `client_id` tinyint(3) NOT NULL default '0',
  `enabled` tinyint(3) NOT NULL default '1',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `protected` tinyint(3) NOT NULL default '0',
  `manifestcache` text NOT NULL,
  `params` text NOT NULL,
  `custom_data` text NOT NULL,
  `system_data` text NOT NULL,
  `checked_out` int(10) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) default '0',
  `state` int(11) NOT NULL default '0',
  PRIMARY KEY  (`extension_id`),
  KEY `type_element` (`type`,`element`),
  KEY `element_clientid` (`element`,`client_id`),
  KEY `element_folder_clientid` (`element`,`folder`,`client_id`),
  KEY `element_folder` (`element`,`folder`),
  KEY `extension` (`type`,`element`,`folder`,`client_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

TRUNCATE TABLE #__extensions;
INSERT INTO #__extensions SELECT
     0,							# extension id (regenerate)
     name,						# name
     'plugin',					# type
     element,					# element
     folder,                    # folder
     client_id,                 # client_id
     published,                 # enabled
     access,                    # access
     iscore,                    # protected
     '',                        # manifest_cache
     params,                    # params
     '',                        # data
     checked_out,            	# checked_out
     checked_out_time,         	# checked_out_time
     ordering                   # ordering
     FROM #__plugins;         	# #__extensions replaces the old #__plugins table

 INSERT INTO #__extensions SELECT
     0,                         # extension id (regenerate)
     name,						# name
     'component',				# type
     `option`,					# element
     '',                        # folder
     0,                         # client id (unused for components)
     enabled,                   # enabled
     0,                         # access
     iscore,                    # protected
     '',                        # manifest cache
     params,                    # params
     '',                        # data
     '0',                       # checked_out
     '0000-00-00 00:00:00',     # checked_out_time
     0                          # ordering
     FROM #__components        # #__extensions replaces #__components for install uninstall
                                # component menu selection still utilises the #__components table
     WHERE parent = 0;          # only get top level entries

 INSERT INTO #__extensions SELECT DISTINCT
     0,                         # extension id (regenerate)
     module,                    # name
     'module',                  # type
     `module`,                  # element
     '',                        # folder
     client_id,                 # client id
     1,                         # enabled (module instances may be enabled/disabled in #__modules)
     0,                         # access (module instance access controlled in #__modules)
     iscore,                    # protected
     '',                        # manifest cache
     '',                        # params (module instance params controlled in #__modules)
     '',                        # data
     '0',                       # checked_out (module instance, see #__modules)
     '0000-00-00 00:00:00',     # checked_out_time (module instance, see #__modules)
     0                          # ordering (module instance, see #__modules)
     FROM #__modules			# #__extensions provides the install/uninstall control for modules
     WHERE id IN (SELECT id FROM #__modules GROUP BY module ORDER BY id)

-- rename mod_newsflash to mod_articles_news
UPDATE `#__extensions` SET `name` = 'mod_articles_news', `element` = 'mod_articles_news' WHERE `name` = 'mod_newsflash'

-- New extensions
INSERT INTO `#__extensions` VALUES(0, 'plg_editors_codemirror', 'plugin', 'codemirror', 'editors', 1, 0, 1, 1, '', 'linenumbers=0\n\n', '', '', 0, '0000-00-00 00:00:00', 7, 0);

-- ----------------------------------------------------------------
-- jos_languages (new)
-- ----------------------------------------------------------------

CREATE TABLE `jos_languages` (
  `lang_id` int(11) unsigned NOT NULL auto_increment,
  `lang_code` char(7) NOT NULL,
  `title` varchar(50) NOT NULL,
  `title_native` varchar(50) NOT NULL,
  `description` varchar(512) NOT NULL,
  `published` int(11) NOT NULL default '0',
  PRIMARY KEY  (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

INSERT INTO `jos_languages` (`lang_id`,`lang_code`,`title`,`title_native`,`description`,`published`)
VALUES
	(1,'en_GB','English (UK)','English (UK)','',1),
	(2,'en_US','English (US)','English (US)','',1);

-- ----------------------------------------------------------------
-- jos_menu
-- ----------------------------------------------------------------

ALTER TABLE `jos_menu`
 DROP COLUMN `sublevel`,
 DROP COLUMN `pollid`,
 DROP COLUMN `utaccess`;

ALTER TABLE `jos_menu`
 MODIFY COLUMN `menutype` VARCHAR(24) NOT NULL COMMENT 'The type of menu this item belongs to. FK to jos_menu_types.menutype';

ALTER TABLE `jos_menu`
 CHANGE COLUMN `name` `title` VARCHAR(255) NOT NULL COMMENT 'The display title of the menu item.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `alias` VARCHAR(255) NOT NULL COMMENT 'The SEF alias of the menu item.';

ALTER TABLE `jos_menu`
 ADD COLUMN `note` VARCHAR(255) NOT NULL DEFAULT '' AFTER `alias`;

ALTER TABLE `jos_menu`
 MODIFY COLUMN `link` VARCHAR(1024) NOT NULL COMMENT 'The actually link the menu item refers to.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `type` VARCHAR(16) NOT NULL COMMENT 'The type of link: Component, URL, Alias, Separator';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `published` TINYINT NOT NULL DEFAULT 0 COMMENT 'The published state of the menu link.';

ALTER TABLE `jos_menu`
 CHANGE COLUMN `parent` `parent_id` INTEGER UNSIGNED NOT NULL DEFAULT 0 COMMENT 'The parent menu item in the menu tree.';

ALTER TABLE `jos_menu`
 ADD COLUMN `level` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'The relative level in the tree.' AFTER `parent_id`;

ALTER TABLE `jos_menu`
 CHANGE COLUMN `componentid` `component_id` INTEGER UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to jos_components.id';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `ordering` INTEGER NOT NULL DEFAULT 0 COMMENT 'The relative ordering of the menu item in the tree.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `checked_out` INTEGER UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to jos_users.id';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `checked_out_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time the menu item was checked out.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `browserNav` TINYINT NOT NULL DEFAULT 0 COMMENT 'The click behaviour of the link.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `access` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'The access level required to view the menu item.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `params` VARCHAR(10240) NOT NULL COMMENT 'JSON encoded data for the menu item.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `lft` INTEGER NOT NULL DEFAULT 0 COMMENT 'Nested set lft.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `rgt` INTEGER NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.';

ALTER TABLE `jos_menu`
 MODIFY COLUMN `home` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Indicates if this menu item is the home or default page.';

ALTER TABLE `jos_menu`
 ADD COLUMN `path` VARCHAR(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.' AFTER `alias`;

ALTER TABLE `jos_menu`
 ADD COLUMN `template_style_id` int(11) UNSIGNED NOT NULL DEFAULT '0';

INSERT INTO `jos_menu` VALUES
 (0, '', 'Menu_Item_Root', 'root', '', '', '', 1, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, '', 0, 37, 0);

-- TODO: Need to devise how to shift the parent_id's of the existing menus to relate to the new root.
-- UPDATE `jos_menu`
--  SET `parent_id` = (SELECT `id` FROM `jos_menu` WHERE `alias` = 'root')
--  WHERE `alias` != 'root';

-- ----------------------------------------------------------------
-- jos_menu_types
-- ----------------------------------------------------------------

ALTER TABLE `jos_menu_types`
 MODIFY COLUMN `menutype` VARCHAR(24) NOT NULL,
 MODIFY COLUMN `title` VARCHAR(48) NOT NULL,
 DROP INDEX `menutype`;

-- ----------------------------------------------------------------
-- jos_messages
-- ----------------------------------------------------------------

ALTER TABLE `jos_messages`
 CHANGE `subject` `subject` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `jos_messages`
 CHANGE `state` `state` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `jos_messages`
 CHANGE `priority` `priority` tinyint(1) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `jos_messages`
 CHANGE `folder_id` `folder_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0';

-- ----------------------------------------------------------------
-- jos_modules
-- ----------------------------------------------------------------

ALTER TABLE `jos_modules`
 DROP `numnews`;

ALTER TABLE `jos_modules`
 DROP `control`;

ALTER TABLE `jos_modules`
 DROP `iscore`;

ALTER TABLE `jos_modules`
 ADD COLUMN `note` VARCHAR(255) NOT NULL DEFAULT '' AFTER `title`;

ALTER TABLE `jos_modules`
 ADD COLUMN `language` CHAR(7) NOT NULL AFTER `client_id`;

ALTER TABLE `jos_modules`
 ADD INDEX `idx_language` (`language`);

ALTER TABLE `jos_modules`
 CHANGE `title` `title` varchar(100) NOT NULL DEFAULT '';

ALTER TABLE `jos_modules`
 CHANGE `params` `params` varchar(5120) NOT NULL DEFAULT '';

ALTER TABLE `jos_modules`
 ADD COLUMN `publish_up` datetime NOT NULL default '0000-00-00 00:00:00' AFTER `checked_out_time`;

ALTER TABLE `jos_modules`
 ADD COLUMN `publish_down` datetime NOT NULL default '0000-00-00 00:00:00' AFTER `publish_up`;

UPDATE `#__modules`
 SET `menutype` = 'mod_menu'
 WHERE `menutype` = 'mod_mainmenu';

-- ----------------------------------------------------------------
-- jos_newsfeeds
-- ----------------------------------------------------------------

ALTER TABLE `jos_newsfeeds`
 CHANGE `id` `id` integer(11) UNSIGNED NOT NULL auto_increment;

ALTER TABLE `jos_newsfeeds`
 CHANGE `name` `name` varchar(100) NOT NULL DEFAULT '';

ALTER TABLE `jos_newsfeeds`
 CHANGE `alias` `alias` varchar(100) NOT NULL DEFAULT '';

ALTER TABLE `jos_newsfeeds`
 CHANGE `link` `link` varchar(200) NOT NULL DEFAULT '';

ALTER TABLE `jos_newsfeeds`
 CHANGE `checked_out` `checked_out` integer(10) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `jos_newsfeeds`
 ADD `access` tinyint UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `jos_newsfeeds`
 ADD `language` char(7) NOT NULL DEFAULT '';

ALTER TABLE `jos_newsfeeds`
ADD `params` TEXT NOT NULL;

ALTER TABLE `jos_newsfeeds`
 ADD COLUMN   `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 ADD COLUMN   `created_by` int(10) unsigned NOT NULL DEFAULT '0',
 ADD COLUMN   `created_by_alias` varchar(255) NOT NULL DEFAULT '',
 ADD COLUMN   `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 ADD COLUMN   `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
 ADD COLUMN   `metakey` text NOT NULL,
 ADD COLUMN   `metadesc` text NOT NULL,
 ADD COLUMN   `metadata` text NOT NULL,
 ADD COLUMN   `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
 ADD COLUMN   `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 ADD COLUMN   `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 DROP INDEX `catid`,
 DROP INDEX `published`,
 ADD KEY `idx_access` (`access`),
 ADD KEY `idx_checkout` (`checked_out`),
 ADD KEY `idx_state` (`published`),
 ADD KEY `idx_catid` (`catid`),
 ADD KEY `idx_createdby` (`created_by`),
 ADD KEY `idx_language` (`language`),
 ADD KEY `idx_xreference` (`xreference`);

-- ----------------------------------------------------------------
-- jos_plugins
-- ----------------------------------------------------------------

INSERT INTO `#__plugins` VALUES (NULL, 'Editor - CodeMirror', 'codemirror', 'editors', 1, 0, 1, 1, 0, 0, '0000-00-00 00:00:00', 'linenumbers=0\n\n');

-- ----------------------------------------------------------------
-- jos_schemas
-- ----------------------------------------------------------------

CREATE TABLE `#__schemas` (
  `extensionid` int(11) NOT NULL,
  `versionid` varchar(20) NOT NULL,
  PRIMARY KEY (`extensionid`, `versionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------------------------------------------
-- jos_session
-- ----------------------------------------------------------------

ALTER TABLE `jos_session`
 MODIFY COLUMN `session_id` VARCHAR(32);

ALTER TABLE `jos_session`
 MODIFY COLUMN `guest` TINYINT UNSIGNED DEFAULT 1;

ALTER TABLE `jos_session`
 MODIFY COLUMN `client_id` TINYINT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE `jos_session`
 MODIFY COLUMN `data` VARCHAR(20480);

--
-- Table structure for table `jos_social_comments`
--

CREATE TABLE IF NOT EXISTS `jos_social_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to #__social_content for the comment.',
  `component` varchar(50) NOT NULL COMMENT 'The name of the component associated with the comment.',
  `context` varchar(255) NOT NULL COMMENT 'The context of the comment.',
  `created_date` datetime NOT NULL COMMENT 'The created date for the comment.',
  `modified_date` datetime NOT NULL COMMENT 'The modified date for the comment.',
  `state` int(11) NOT NULL default '0' COMMENT 'The state of the comment.',
  `language` char(7) NOT NULL DEFAULT '' COMMENT 'The language of the comment.',
  `trackback` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Is the comment a trackback?',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__users for the comment submitter.',
  `user_ip` int(20) NOT NULL COMMENT 'IP address of the comment submitter.',
  `user_name` varchar(255) NOT NULL COMMENT 'Name of the comment submitter.',
  `user_link` varchar(255) NOT NULL COMMENT 'Website for the comment submitter.',
  `user_email` varchar(255) NOT NULL COMMENT 'Email address for the comment submitter.',
  `user_notify` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Notify the user on replies.',
  `subject` varchar(255) NOT NULL COMMENT 'The subject of the comment.',
  `body` varchar(5120) NOT NULL COMMENT 'Body of the comment.',
  `score` double NOT NULL DEFAULT '0' COMMENT 'The rating score chosen by the comment submitter.',
  `score_like` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The number of people who like this comment.',
  `score_dislike` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The number of people who dislike this comment.',
  PRIMARY KEY (`id`),
  KEY `idx_context` (`context`,`state`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jos_social_ratings`
--

CREATE TABLE IF NOT EXISTS `jos_social_ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to #__social_content for the rating.',
  `component` varchar(50) NOT NULL COMMENT 'The name of the component associated with the rating.',
  `context` varchar(255) NOT NULL COMMENT 'The context of the rating.',
  `created_date` datetime NOT NULL COMMENT 'The created date for the comment.',
  `modified_date` datetime NOT NULL COMMENT 'The modified date for the comment.',
  `state` int(11) NOT NULL default '0' COMMENT 'The state of the comment.',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__users for the rating submitter.',
  `user_ip` int(20) NOT NULL COMMENT 'IP address of the rating submitter.',
  `score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The rating score chosen by the rating submitter.',
  PRIMARY KEY  (`id`),
  KEY `idx_updated` (`modified_date`,`score`),
  KEY `idx_score` (`score`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jos_social_content`
--

CREATE TABLE IF NOT EXISTS `jos_social_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `component` varchar(50) NOT NULL COMMENT 'The name of the component associated with the content item.',
  `context` varchar(255) NOT NULL COMMENT 'The context of the content item.',
  `page_route` varchar(255) NOT NULL COMMENT 'The route of the page for which the content item is attached.',
  `page_title` varchar(255) NOT NULL COMMENT 'The title of the page for which the content item is attached.',
  `created_date` datetime NOT NULL COMMENT 'The created date for the content item.',
  `state` int(11) NOT NULL default '0' COMMENT 'The state of the content item.',
  `rating_score` double NOT NULL default '0' COMMENT 'The rating score for the content item.',
  `rating_total` double NOT NULL default '0' COMMENT 'The sum of all accumulated ratings for the content item.',
  `rating_count` int(10) unsigned NOT NULL default '0' COMMENT 'Total number of ratings for the content item.',
  `comment_count` int(10) unsigned NOT NULL default '0' COMMENT 'Total number of comments for the content item.',
  `pings`  mediumtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_context` (`context`,`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------------------------------------------
-- Table structure for table `jos_social_blocked_ips`
-- ----------------------------------------------------------------

CREATE TABLE `jos_social_blocked_ips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_ip` int(11) NOT NULL,
  `end_ip` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`start_ip`, `end_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------------------------------------------
-- Table structure for table `jos_social_blocked_users`
-- ----------------------------------------------------------------

CREATE TABLE `jos_social_blocked_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------------------------------------------
-- jos_template_styles
-- ----------------------------------------------------------------

RENAME TABLE `jos_menu_template` TO `jos_template_styles`;

ALTER TABLE `jos_template_styles`
 CHANGE `id` `id` int(11) UNSIGNED NOT NULL auto_increment;

ALTER TABLE `jos_template_styles`
 CHANGE `template` `template` varchar(50) NOT NULL DEFAULT '';

ALTER TABLE `jos_template_styles`
 CHANGE `client_id` `client_id` tinyint(1) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `jos_template_styles`
 CHANGE `home` `home` tinyint(1) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `jos_template_styles`
 CHANGE `params` `params` varchar(2048) NOT NULL DEFAULT '';

ALTER TABLE `jos_template_styles`
 ADD INDEX `idx_template` (`template`);

ALTER TABLE `jos_template_styles`
 ADD INDEX `idx_home` (`home`);

-- ----------------------------------------------------------------
-- jos_updates (new)
-- ----------------------------------------------------------------

CREATE TABLE  `#__updates` (
  `update_id` int(11) NOT NULL auto_increment,
  `update_site_id` int(11) default '0',
  `extension_id` int(11) default '0',
  `categoryid` int(11) default '0',
  `name` varchar(100) default '',
  `description` text,
  `element` varchar(100) default '',
  `type` varchar(20) default '',
  `folder` varchar(20) default '',
  `client_id` tinyint(3) default '0',
  `version` varchar(10) default '',
  `data` text,
  `detailsurl` text,
  PRIMARY KEY  (`update_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Available Updates';

-- ----------------------------------------------------------------
-- jos_update_sites (new)
-- ----------------------------------------------------------------

CREATE TABLE  `#__update_sites` (
  `update_site_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default '',
  `type` varchar(20) default '',
  `location` text,
  `enabled` int(11) default '0',
  PRIMARY KEY  (`update_site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Update Sites';

-- ----------------------------------------------------------------
-- jos_update_sites_extensions (new)
-- ----------------------------------------------------------------

CREATE TABLE `#__update_sites_extensions` (
  `update_site_id` INT DEFAULT 0,
  `extension_id` INT DEFAULT 0,
  INDEX `newindex`(`update_site_id`, `extension_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COMMENT = 'Links extensions to update sites';

-- ----------------------------------------------------------------
-- jos_update_categories (new)
-- ----------------------------------------------------------------

CREATE TABLE  `#__update_categories` (
  `categoryid` int(11) NOT NULL auto_increment,
  `name` varchar(20) default '',
  `description` text,
  `parent` int(11) default '0',
  `updatesite` int(11) default '0',
  PRIMARY KEY  (`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Update Categories';

-- ----------------------------------------------------------------
-- jos_weblinks
-- ----------------------------------------------------------------
ALTER TABLE `jos_weblinks`
 CHANGE COLUMN `published` `state` tinyint (1) NOT NULL DEFAULT '0';

ALTER TABLE `jos_weblinks`
 ADD COLUMN `access` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `approved`;

ALTER TABLE `jos_weblinks`
 ADD `language` char(7) NOT NULL DEFAULT '';

ALTER TABLE `jos_weblinks`
 ADD COLUMN   `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 ADD COLUMN   `created_by` int(10) unsigned NOT NULL DEFAULT '0',
 ADD COLUMN   `created_by_alias` varchar(255) NOT NULL DEFAULT '',
 ADD COLUMN   `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 ADD COLUMN   `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
 ADD COLUMN   `metakey` text NOT NULL,
 ADD COLUMN   `metadesc` text NOT NULL,
 ADD COLUMN   `metadata` text NOT NULL,
 ADD COLUMN   `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if link is featured.',
 ADD COLUMN   `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
 ADD COLUMN   `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 ADD COLUMN   `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 DROP  KEY `catid`,
 ADD   KEY `idx_access` (`access`),
 ADD   KEY `idx_checkout` (`checked_out`),
 ADD   KEY `idx_state` (`state`),
 ADD   KEY `idx_catid` (`catid`),
 ADD   KEY `idx_createdby` (`created_by`),
 ADD   KEY `idx_featured_catid` (`featured`,`catid`),
 ADD   KEY `idx_language` (`language`),
 ADD   KEY `idx_xreference` (`xreference`);
-- ----------------------------------------------------------------
-- Reconfigure the admin module permissions
-- ----------------------------------------------------------------

UPDATE `#__categories`
 SET access = access + 1;

UPDATE `#__contact_details`
 SET access = access + 1;

UPDATE `#__content`
 SET access = access + 1;

UPDATE `#__menu`
 SET access = access + 1;

UPDATE `#__modules`
 SET access = access + 1;

UPDATE `#__plugins`
 SET access = access + 1;

UPDATE `#__sections`
 SET access = access + 1;

-- ----------------------------------------------------------------
-- Table drops.
-- ----------------------------------------------------------------

DROP TABLE `#__groups`;

-- Note, devise the migration
DROP TABLE `#__core_acl_aro`;
DROP TABLE `#__core_acl_aro_map`;
DROP TABLE `#__core_acl_aro_groups`;
DROP TABLE `#__core_acl_groups_aro_map`;
DROP TABLE `#__core_acl_aro_sections`;


-- ----------------------------------------------------------------
-- Add an entry to the extensions (for the app)
-- Add an entry to the schema table (for this migration)
-- ----------------------------------------------------------------
INSERT INTO #__extensions (name, type, element, protected) VALUES ('Joomla! CMS', 'package', 'joomla', 1);
INSERT INTO #__schema VALUES(LAST_INSERT_ID()), '20090622');

-- Parameter conversions todo

# com_content show_vote -> article-allow_ratings

DROP TABLE `#__core_log_items`;
DROP TABLE `#__stats_agents`;


