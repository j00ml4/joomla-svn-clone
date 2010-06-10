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
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `url` text collate utf8_unicode_ci NOT NULL,
  `media` text collate utf8_unicode_ci NOT NULL,
  `thumb_url` text collate utf8_unicode_ci NOT NULL,
  `title` text collate utf8_unicode_ci NOT NULL,
  `alias` varchar(50) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  `rank` int(10) unsigned NOT NULL,
  `votes` int(11) NOT NULL,
  `added` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `catid` (`catid`,`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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