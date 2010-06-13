ALTER TABLE  `jos_projects` ADD  `access` INT UNSIGNED NOT NULL AFTER  `xreference`;
ALTER TABLE  `jos_projects` ADD INDEX  `idx_access` (  `access` );