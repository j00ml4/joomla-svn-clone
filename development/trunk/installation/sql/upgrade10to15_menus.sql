# $Id: upgrade10to15.sql 3796 2006-06-02 05:42:53Z eddieajau $

# Joomla 1.0 to Joomla 1.5

####
#### WARNING! WARNING!  Work in progress.  Requires adult supervision!
#### DO NOT RUN THIS SCRIPT
####

####
#### Manual conversion requires MySQL 4.1 or higher to support sub-queries
####

## Migrating the menu configuration...hang on!

# Component

UPDATE `jos_menu`
  SET
    `type` = 'component'
  WHERE
    `type` = 'components';

UPDATE `jos_menu`
  SET
    `control` = 'view_name='
  WHERE
    `link` LIKE '%option=com_frontpage%';

# Component Item Link (now called a Menu Item Link)

UPDATE `jos_menu`
  SET
	`link` = SUBSTRING(link,LOCATE('Itemid=',link)+7),
    `params` = CONCAT_WS( '', params, '\nmenu_item=', `link` ),
    `type` = 'menulink'
  WHERE
    `type` = 'component_item_link';

# Contact Table

UPDATE `jos_menu`
  SET
    `control` = 'view_name=category\ntemplate_name=table',
    `params` = CONCAT_WS( '', params, '\ncategory_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_contact' AND `parent` = 0)
  WHERE
    `type` = 'contact_category_table';

# Contact Item Link

UPDATE `jos_menu`
  SET
    `control` = 'view_name=contact\ntemplate_name=default',
    `params` = CONCAT_WS( '', params, '\ncontact_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_contact' AND `parent` = 0)
  WHERE
    `type` = 'contact_item_link';

# Content Archive Category

UPDATE `jos_menu`
  SET
    `control` = 'view_name=archive\nmodel_name=category',
    `params` = CONCAT_WS( '', params, '\ncategory_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_content' AND `parent` = 0)
  WHERE
    `type` = 'content_archive_category';

# Content Archive Section

UPDATE `jos_menu`
  SET
    `control` = 'view_name=archive\nmodel_name=section',
    `params` = CONCAT_WS( '', params, '\nsection_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_content' AND `parent` = 0)
  WHERE
    `type` = 'content_archive_category';

# Content Blog Category

UPDATE `jos_menu`
  SET
    `control` = 'view_name=blog\nmodel_name=category',
    `params` = CONCAT_WS( '', params, '\ncategory_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_content' AND `parent` = 0)
  WHERE
    `type` = 'content_blog_category';

# Content Blog Section

UPDATE `jos_menu`
  SET
    `control` = 'view_name=section\ntemplate_name=blog',
    `params` = CONCAT_WS( '', params, '\nsection_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_content' AND `parent` = 0)
  WHERE
    `type` = 'content_blog_section';

# Content Category

UPDATE `jos_menu`
  SET
    `control` = 'view_name=category\ntemplate_name=table',
    `params` = CONCAT_WS( '', params, '\ncategory_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_content' AND `parent` = 0)
  WHERE
    `type` = 'content_category';

# Content Item Link

UPDATE `jos_menu`
  SET
    `control` = 'view_name=article',
    `params` = CONCAT_WS( '', params, '\narticle_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_content' AND `parent` = 0)
  WHERE
    `type` = 'content_item_link';

# Content Section

UPDATE `jos_menu`
  SET
    `control` = 'view_name=section\ntemplate_name=list',
    `params` = CONCAT_WS( '', params, '\nsection_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_content' AND `parent` = 0)
  WHERE
    `type` = 'content_section';

# Content Typed

UPDATE `jos_menu`
  SET
    `control` = 'view_name=article',
    `params` = CONCAT_WS( '', params, '\narticle_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_content' AND `parent` = 0)
  WHERE
    `type` = 'content_typed';

# Newsfeed Category Table

UPDATE `jos_menu`
  SET
    `control` = 'task=category',
    `params` = CONCAT_WS( '', params, '\ncategory_id=', componentid ),
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_newsfeeds' AND `parent` = 0)
  WHERE
    `type` = 'newsfeed_category_table';

UPDATE `jos_menu`
  SET
    `control` = 'task=category'
  WHERE
    `type` = 'component' AND link LIKE '%option=com_newsfeeds%';

# Newsfeed Link

UPDATE `jos_menu`
  SET
    `control` = 'task=view',
    `params` = CONCAT_WS( '', params, '\nfeed_id=', componentid ),
    `type` = 'component'
  WHERE
    `type` = 'newsfeed_link';

# Submit Content

# Weblink Category Table

UPDATE `jos_menu`
  SET
    `type` = 'component',
    `params` = CONCAT_WS( '', params, '\ncategory_id=', componentid ),
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_weblinks' AND `parent` = 0)
  WHERE
    `type` = 'weblink_category_table';

# Wrapper

UPDATE `jos_menu`
  SET
    `type` = 'component',
    `componentid` = (SELECT `id` FROM `jos_components` WHERE `option`='com_wrapper')
  WHERE
    `type` = 'wrapper';

# Finish off

UPDATE `jos_menu`
  SET
    `link` = SUBSTRING(`link`,1,LOCATE('&',`link`)-1)
  WHERE
    `type` = 'component' AND LOCATE('&',`link`) > 0;