
ALTER TABLE `#__mediagalleries` 
ADD `created` datetime NOT NULL,
ADD `created_by` int(10) unsigned NOT NULL,
ADD `created_by_alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
ADD `modified` datetime NOT NULL,
ADD `modified_by` int(10) unsigned NOT NULL,
ADD `state` tinyint(1) NOT NULL DEFAULT '1',
ADD `language` char(7) COLLATE utf8_unicode_ci NOT NULL,
ADD `featured` tinyint(3) unsigned NOT NULL,
ADD `xreference` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
ADD `params` text COLLATE utf8_unicode_ci NOT NULL,
ADD `access` int(10) unsigned NOT NULL;
