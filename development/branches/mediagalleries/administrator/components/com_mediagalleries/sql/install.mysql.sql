-- phpMyAdmin SQL Dump
-- version 2.11.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 26, 2008 at 02:40 AM
-- Server version: 5.0.45
-- PHP Version: 5.2.4

-- --------------------------------------------------------

--
-- Table structure for table `#__mediagalleries`
--

CREATE TABLE IF NOT EXISTS `#__mediagalleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `thumb_url` text COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created_by_alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(10) unsigned NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `language` char(7) COLLATE utf8_unicode_ci NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL,
  `xreference` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `access` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `catid` (`catid`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Table structure for table `#__mediagalleries_channels`
--

CREATE TABLE IF NOT EXISTS `#__mediagalleries_channels` (
  `userid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `rank` int(10) unsigned NOT NULL,
  `votes` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`userid`),
  KEY `catid` (`catid`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__mediagalleries_channels_friends`
--

CREATE TABLE IF NOT EXISTS `#__mediagalleries_channels_friends` (
  `channel_id` int(11) NOT NULL,
  `channel2_id` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY  (`channel_id`,`channel2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__mediagalleries_channels_media`
--

CREATE TABLE IF NOT EXISTS `#__mediagalleries_channels_media` (
  `channel_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY  (`channel_id`,`media_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__mediagalleries_channels_media_type`
--

CREATE TABLE IF NOT EXISTS `#__mediagalleries_channels_media_type` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__mediagalleries_channels_users`
--

CREATE TABLE IF NOT EXISTS `#__mediagalleries_channels_users` (
  `channel_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY  (`channel_id`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
-- UPGRADE
-- --------------------------------------------------------