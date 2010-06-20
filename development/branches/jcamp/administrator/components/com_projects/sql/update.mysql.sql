ALTER TABLE  `jos_projects` ADD  `access` INT UNSIGNED NOT NULL AFTER  `xreference`;
ALTER TABLE  `jos_projects` ADD INDEX  `idx_access` (  `access` );
ALTER TABLE  `jos_project_tasks` ADD  `access` INT UNSIGNED NOT NULL AFTER  `xreference`;
ALTER TABLE  `jos_project_tasks` ADD INDEX  `idx_access` (  `access` );
ALTER TABLE  `jos_project_tasks` ADD  `catid` INT UNSIGNED NOT NULL AFTER  `id`;
ALTER TABLE  `jos_project_tasks` ADD INDEX  `idx_catid` (  `catid` );
ALTER TABLE  `jos_project_tasks` ADD  `project_id` INT UNSIGNED NOT NULL AFTER  `id`;
ALTER TABLE  `jos_project_tasks` ADD INDEX  `idx_project_id` (  `project_id` );

ALTER TABLE  `jos_project_tasks` CHANGE  `type_id`  `type` INT( 10 ) UNSIGNED NOT NULL;
ALTER TABLE  `jos_project_tasks` DROP  `is_ticket`;