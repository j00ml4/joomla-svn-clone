
/****** Object:  Table [dbo].[jos_weblinks]    Script Date: 10/20/2010 14:35:59 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_weblinks]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_weblinks](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[catid] [int] NOT NULL DEFAULT ((0)),
	[sid] [int] NOT NULL DEFAULT ((0)),
	[title] [nvarchar](250) NOT NULL DEFAULT (N''),
	[alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[url] [nvarchar](250) NOT NULL DEFAULT (N''),
	[description] [nvarchar](max) NOT NULL,
	[date] [datetime] NOT NULL DEFAULT (getdate()),
	[hits] [int] NOT NULL DEFAULT ((0)),
	[state] [smallint] NOT NULL DEFAULT ((0)),
	[checked_out] [int] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[ordering] [int] NOT NULL DEFAULT ((0)),
	[archived] [smallint] NOT NULL DEFAULT ((0)),
	[approved] [smallint] NOT NULL DEFAULT ((1)),
	[access] [int] NOT NULL DEFAULT ((1)),
	[params] [nvarchar](max) NOT NULL,
	[language] [nchar](7) NOT NULL DEFAULT (N''),
	[created] [datetime] NOT NULL DEFAULT (getdate()),
	[created_by] [bigint] NOT NULL DEFAULT ((0)),
	[created_by_alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[modified] [datetime] NOT NULL DEFAULT (getdate()),
	[modified_by] [bigint] NOT NULL DEFAULT ((0)),
	[metakey] [nvarchar](max) NOT NULL,
	[metadesc] [nvarchar](max) NOT NULL,
	[metadata] [nvarchar](max) NOT NULL,
	[featured] [tinyint] NOT NULL DEFAULT ((0)),
	[xreference] [nvarchar](50) NOT NULL,
	[publish_up] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_down] [datetime] NOT NULL DEFAULT (getdate()),
 CONSTRAINT [PK_jos_weblinks_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_menu]    Script Date: 10/20/2010 14:29:39 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_menu]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_menu](
	[id] [int] IDENTITY(102,1) NOT NULL,
	[menutype] [nvarchar](24) NOT NULL,
	[title] [nvarchar](255) NOT NULL,
	[alias] [nvarchar](255) NOT NULL,
	[note] [nvarchar](255) NOT NULL DEFAULT (N''),
	[path] [nvarchar](1024) NOT NULL,
	[link] [nvarchar](1024) NOT NULL,
	[type] [nvarchar](16) NOT NULL,
	[published] [smallint] NOT NULL DEFAULT ((0)),
	[parent_id] [bigint] NOT NULL DEFAULT ((1)),
	[level] [bigint] NOT NULL DEFAULT ((0)),
	[component_id] [bigint] NOT NULL DEFAULT ((0)),
	[ordering] [int] NOT NULL DEFAULT ((0)),
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[browserNav] [smallint] NOT NULL DEFAULT ((0)),
	[access] [tinyint] NOT NULL DEFAULT ((0)),
	[img] [nvarchar](255) NOT NULL,
	[template_style_id] [bigint] NOT NULL DEFAULT ((0)),
	[params] [nvarchar](max) NOT NULL,
	[lft] [int] NOT NULL DEFAULT ((0)),
	[rgt] [int] NOT NULL DEFAULT ((0)),
	[home] [tinyint] NOT NULL DEFAULT ((0)),
	[language] [nchar](7) NOT NULL DEFAULT (N''),
 CONSTRAINT [PK_jos_menu_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY],
 CONSTRAINT [jos_menu$idx_alias_parent_id] UNIQUE NONCLUSTERED 
(
	[alias] ASC,
	[parent_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END


SET IDENTITY_INSERT jos_menu  ON;

INSERT INTO jos_menu (id, menutype, title, alias, note, path, link,type, published,parent_id, level, component_id,ordering, checked_out, checked_out_time, browserNav,
  access, img, template_style_id, params, lft, rgt, home, language)
SELECT 1,'','Menu_Item_Root','root','','','','',1,0,0,0,0,0,'1900-01-01 00:00:00',0,0,'',0,'',0,217,0,'*'
UNION ALL
SELECT 2,'_adminmenu','com_banners','Banners','','Banners','index.php?option=com_banners','component',0,1,1,4,0,0,'1900-01-01 00:00:00',0,0,'class:banners',0,'',1,10,0,'*'
UNION ALL
SELECT 3,'_adminmenu','com_banners','Banners','','Banners/Banners','index.php?option=com_banners','component',0,2,2,4,0,0,'1900-01-01 00:00:00',0,0,'class:banners',0,'',2,3,0,'*'
UNION ALL
SELECT 4,'_adminmenu','com_banners_clients','Clients','','Banners/Clients','index.php?option=com_banners&view=clients','component',0,2,2,4,0,0,'1900-01-01 00:00:00',0,0,'class:banners-clients',0,'',4,5,0,'*'
UNION ALL
SELECT 5,'_adminmenu','com_banners_tracks','Tracks','','Banners/Tracks','index.php?option=com_banners&view=tracks','component',0,2,2,4,0,0,'1900-01-01 00:00:00',0,0,'class:banners-tracks',0,'',6,7,0,'*'
UNION ALL
SELECT 6,'_adminmenu','com_banners_categories','Categories','','Banners/Categories','index.php?option=com_categories&extension=com_banners','component',0,2,2,6,0,0,'1900-01-01 00:00:00',0,0,'class:banners-cat',0,'',8,9,0,'*'
UNION ALL
SELECT 7,'_adminmenu','com_contact','Contacts','','Contacts','index.php?option=com_contact','component',0,1,1,8,0,0,'1900-01-01 00:00:00',0,0,'class:contact',0,'',11,16,0,'*'
UNION ALL
SELECT 8,'_adminmenu','com_contact','Contacts','','Contacts/Contacts','index.php?option=com_contact','component',0,7,2,8,0,0,'1900-01-01 00:00:00',0,0,'class:contact',0,'',12,13,0,'*'
UNION ALL
SELECT 9,'_adminmenu','com_contact_categories','Categories','','Contacts/Categories','index.php?option=com_categories&extension=com_contact','component',0,7,2,6,0,0,'1900-01-01 00:00:00',0,0,'class:contact-cat',0,'',14,15,0,'*'
UNION ALL
SELECT 10,'_adminmenu','com_messages','Messaging','','Messaging','index.php?option=com_messages','component',0,1,1,15,0,0,'1900-01-01 00:00:00',0,0,'class:messages',0,'',17,22,0,'*'
UNION ALL
SELECT 11,'_adminmenu','com_messages_add','New Private Message','','Messaging/New Private Message','index.php?option=com_messages&task=message.add','component',0,10,2,15,0,0,'1900-01-01 00:00:00',0,0,'class:messages-add',0,'',18,19,0,'*'
UNION ALL
SELECT 12,'_adminmenu','com_messages_read','Read Private Message','','Messaging/Read Private Message','index.php?option=com_messages','component',0,10,2,15,0,0,'1900-01-01 00:00:00',0,0,'class:messages-read',0,'',20,21,0,'*'
UNION ALL
SELECT 13,'_adminmenu','com_newsfeeds','News Feeds','','News Feeds','index.php?option=com_newsfeeds','component',0,1,1,17,0,0,'1900-01-01 00:00:00',0,0,'class:newsfeeds',0,'',23,28,0,'*'
UNION ALL
SELECT 14,'_adminmenu','com_newsfeeds_feeds','Feeds','','News Feeds/Feeds','index.php?option=com_newsfeeds','component',0,13,2,17,0,0,'1900-01-01 00:00:00',0,0,'class:newsfeeds',0,'',24,25,0,'*'
UNION ALL
SELECT 15,'_adminmenu','com_newsfeeds_categories','Categories','','News Feeds/Categories','index.php?option=com_categories&extension=com_newsfeeds','component',0,13,2,6,0,0,'1900-01-01 00:00:00',0,0,'class:newsfeeds-cat',0,'',26,27,0,'*'
UNION ALL
SELECT 16,'_adminmenu','com_redirect','Redirect','','Redirect','index.php?option=com_redirect','component',0,1,1,24,0,0,'1900-01-01 00:00:00',0,0,'class:redirect',0,'',37,38,0,'*'
UNION ALL
SELECT 17,'_adminmenu','com_search','Search','','Search','index.php?option=com_search','component',0,1,1,19,0,0,'1900-01-01 00:00:00',0,0,'class:search',0,'',29,30,0,'*'
UNION ALL
SELECT 18,'_adminmenu','com_weblinks','Weblinks','','Weblinks','index.php?option=com_weblinks','component',0,1,1,21,0,0,'1900-01-01 00:00:00',0,0,'class:weblinks',0,'',31,36,0,'*'
UNION ALL
SELECT 19,'_adminmenu','com_weblinks_links','Links','','Weblinks/Links','index.php?option=com_weblinks','component',0,18,2,21,0,0,'1900-01-01 00:00:00',0,0,'class:weblinks',0,'',32,33,0,'*'
UNION ALL
SELECT 20,'_adminmenu','com_weblinks_categories','Categories','','Weblinks/Categories','index.php?option=com_categories&extension=com_weblinks','component',0,18,2,6,0,0,'1900-01-01 00:00:00',0,0,'class:weblinks-cat',0,'',34,35,0,'*'
UNION ALL
SELECT 101, 'mainmenu', 'Home', 'home', '', 'home', 'index.php?option=com_content&view=featured', 'component', 1, 1, 1, 22, 0, 0, '1900-01-01 00:00:00', 0, 1, '', 0, '{"num_leading_articles":"1","num_intro_articles":"3","num_columns":"3","num_links":"0","orderby_pri":"","orderby_sec":"front","order_date":"","multi_column_order":"1","show_pagination":"2","show_pagination_results":"1","show_noauth":"","article-allow_ratings":"","article-allow_comments":"","show_feed_link":"1","feed_summary":"","show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_readmore":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","show_page_heading":1,"page_title":"","page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 231, 232, 1,'*'

SET IDENTITY_INSERT jos_menu  OFF;
/****** Object:  Table [dbo].[jos_banner_tracks]    Script Date: 10/20/2010 14:23:38 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_banner_tracks]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_banner_tracks](
	[track_date] [datetime] NOT NULL,
	[track_type] [bigint] NOT NULL,
	[banner_id] [bigint] NOT NULL,
	[count] [bigint] NOT NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_banner_tracks_track_date] PRIMARY KEY CLUSTERED 
(
	[track_date] ASC,
	[track_type] ASC,
	[banner_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_banners]    Script Date: 10/20/2010 14:24:17 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_banners]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_banners](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[cid] [int] NOT NULL DEFAULT ((0)),
	[type] [int] NOT NULL DEFAULT ((0)),
	[name] [nvarchar](255) NOT NULL DEFAULT (N''),
	[alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[imptotal] [int] NOT NULL DEFAULT ((0)),
	[impmade] [int] NOT NULL DEFAULT ((0)),
	[clicks] [int] NOT NULL DEFAULT ((0)),
	[clickurl] [nvarchar](200) NOT NULL DEFAULT (N''),
	[state] [smallint] NOT NULL DEFAULT ((0)),
	[catid] [bigint] NOT NULL DEFAULT ((0)),
	[description] [nvarchar](max) NOT NULL,
	[custombannercode] [nvarchar](2048) NOT NULL,
	[sticky] [tinyint] NOT NULL DEFAULT ((0)),
	[ordering] [int] NOT NULL DEFAULT ((0)),
	[metakey] [nvarchar](max) NOT NULL,
	[params] [nvarchar](max) NOT NULL,
	[own_prefix] [smallint] NOT NULL DEFAULT ((0)),
	[metakey_prefix] [nvarchar](255) NOT NULL DEFAULT (N''),
	[purchase_type] [smallint] NOT NULL DEFAULT ((-1)),
	[track_clicks] [smallint] NOT NULL DEFAULT ((-1)),
	[track_impressions] [smallint] NOT NULL DEFAULT ((-1)),
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_up] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_down] [datetime] NOT NULL DEFAULT (getdate()),
	[reset] [datetime] NOT NULL DEFAULT (getdate()),
	[created] [datetime] NOT NULL DEFAULT (getdate()),
	[language] [nchar](7) NOT NULL DEFAULT (N''),
 CONSTRAINT [PK_jos_banners_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_menu_types]    Script Date: 10/20/2010 14:29:46 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_menu_types]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_menu_types](
	[id] [bigint] IDENTITY(2,1) NOT NULL,
	[menutype] [nvarchar](24) NOT NULL,
	[title] [nvarchar](48) NOT NULL,
	[description] [nvarchar](255) NOT NULL DEFAULT (N''),
 CONSTRAINT [PK_jos_menu_types_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY],
 CONSTRAINT [jos_menu_types$idx_menutype] UNIQUE NONCLUSTERED 
(
	[menutype] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_messages]    Script Date: 10/20/2010 14:29:59 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_messages]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_messages](
	[message_id] [bigint] IDENTITY(1,1) NOT NULL,
	[user_id_from] [bigint] NOT NULL DEFAULT ((0)),
	[user_id_to] [bigint] NOT NULL DEFAULT ((0)),
	[folder_id] [tinyint] NOT NULL DEFAULT ((0)),
	[date_time] [datetime] NOT NULL DEFAULT (getdate()),
	[state] [smallint] NOT NULL DEFAULT ((0)),
	[priority] [tinyint] NOT NULL DEFAULT ((0)),
	[subject] [nvarchar](255) NOT NULL DEFAULT (N''),
	[message] [nvarchar](max) NOT NULL,
 CONSTRAINT [PK_jos_messages_message_id] PRIMARY KEY CLUSTERED 
(
	[message_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_messages_cfg]    Script Date: 10/20/2010 14:30:08 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_messages_cfg]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_messages_cfg](
	[user_id] [bigint] NOT NULL DEFAULT ((0)),
	[cfg_name] [nvarchar](100) NOT NULL DEFAULT (N''),
	[cfg_value] [nvarchar](255) NOT NULL DEFAULT (N''),
 CONSTRAINT [jos_messages_cfg$idx_user_var_name] UNIQUE CLUSTERED 
(
	[user_id] ASC,
	[cfg_name] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_modules]    Script Date: 10/20/2010 14:30:34 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_modules]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_modules](
	[id] [int] IDENTITY(19,1) NOT NULL,
	[title] [nvarchar](100) NOT NULL DEFAULT (N''),
	[note] [nvarchar](255) NOT NULL DEFAULT (N''),
	[content] [nvarchar](max) NOT NULL,
	[ordering] [int] NOT NULL DEFAULT ((0)),
	[position] [nvarchar](50) NULL DEFAULT (NULL),
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_up] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_down] [datetime] NOT NULL DEFAULT (getdate()),
	[published] [smallint] NOT NULL DEFAULT ((0)),
	[module] [nvarchar](50) NULL DEFAULT (NULL),
	[access] [tinyint] NOT NULL DEFAULT ((0)),
	[showtitle] [tinyint] NOT NULL DEFAULT ((1)),
	[params] [nvarchar](max) NOT NULL DEFAULT (N''),
	[client_id] [smallint] NOT NULL DEFAULT ((0)),
	[language] [nchar](7) NOT NULL,
 CONSTRAINT [PK_jos_modules_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

SET IDENTITY_INSERT jos_modules  ON;
INSERT INTO jos_modules (id, title, note, content, ordering, position, checked_out,checked_out_time, publish_up, publish_down, published, module, access, showtitle, params,
  client_id, language)
SELECT 1, 'Main Menu', '', '', 1, 'position-7', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_menu', 1, 1, '{"menutype":"mainmenu","startLevel":"0","endLevel":"0","showAllChildren":"0","tag_id":"","class_sfx":"","window_open":"","layout":"","moduleclass_sfx":"_menu","cache":"1","cache_time":"900","cachemode":"itemid"}', 0, '*'
UNION ALL
SELECT 2, 'Login', '', '', 1, 'login', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_login', 1, 1, '', 1, '*'
UNION ALL
SELECT 3, 'Popular Articles', '', '', 3, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_popular', 3, 1, '{"count":"5","catid":"","user_id":"0","layout":"","moduleclass_sfx":"","cache":"0"}', 1, '*'
UNION ALL
SELECT 4, 'Recently Added Articles', '', '', 4, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_latest', 3, 1, '{"count":"5","ordering":"c_dsc","catid":"","user_id":"0","layout":"","moduleclass_sfx":"","cache":"0"}', 1, '*'
UNION ALL
SELECT 6, 'Unread Messages', '', '', 1, 'header', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_unread', 3, 1, '', 1, '*'
UNION ALL
SELECT 7, 'Online Users', '', '', 2, 'header', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_online', 3, 1, '', 1, '*'
UNION ALL
SELECT 8, 'Toolbar', '', '', 1, 'toolbar', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_toolbar', 3, 1, '', 1, '*'
UNION ALL
SELECT 9, 'Quick Icons', '', '', 1, 'icon', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_quickicon', 3, 1, '', 1, '*'
UNION ALL
SELECT 10, 'Logged-in Users', '', '', 2, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_logged', 3, 1, '', 1, '*'
UNION ALL
SELECT 12, 'Admin Menu', '', '', 1, 'menu', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_menu', 3, 1, '{"layout":"","moduleclass_sfx":"","shownew":"1","showhelp":"1","cache":"0"}', 1, '*'
UNION ALL
SELECT 13, 'Admin Submenu', '', '', 1, 'submenu', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_submenu', 3, 1, '', 1, '*'
UNION ALL
SELECT 14, 'User Status', '', '', 1, 'status', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_status', 3, 1, '', 1, '*'
UNION ALL
SELECT 15, 'Title', '', '', 1, 'title', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_title', 3, 1, '', 1, '*'
UNION ALL
SELECT 16, 'Login Form', '', '', 7, 'position-7', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_login', 1, 1, '{"greeting":"1","name":"0"}', 0, '*'
UNION ALL
SELECT 17, 'Breadcrumbs', '', '', 1, 'position-2', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_breadcrumbs', 1, 1, '{"moduleclass_sfx":"","showHome":"1","homeText":"Home","showComponent":"1","separator":"","cache":"1","cache_time":"900","cachemode":"itemid"}', 0, '*'
UNION ALL
SELECT 18, 'Banners', '', '', 1, 'position-5', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 0, 'mod_banners', 1, 1, '{"target":"1","count":"1","cid":"1","catid":["27"],"tag_search":"0","ordering":"0","header_text":"","footer_text":"","layout":"","moduleclass_sfx":"","cache":"1","cache_time":"900"}', 0, '*'

SET IDENTITY_INSERT jos_modules  OFF;
/****** Object:  Table [dbo].[jos_categories]    Script Date: 10/20/2010 14:24:56 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_categories]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_categories](
	[id] [int] IDENTITY(7,1) NOT NULL,
	[asset_id] [bigint] NOT NULL DEFAULT ((0)),
	[parent_id] [bigint] NOT NULL DEFAULT ((0)),
	[lft] [int] NOT NULL DEFAULT ((0)),
	[rgt] [int] NOT NULL DEFAULT ((0)),
	[level] [bigint] NOT NULL DEFAULT ((0)),
	[path] [nvarchar](255) NOT NULL DEFAULT (N''),
	[extension] [nvarchar](50) NOT NULL DEFAULT (N''),
	[title] [nvarchar](255) NOT NULL,
	[alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[note] [nvarchar](255) NOT NULL DEFAULT (N''),
	[description] [nvarchar](max) NOT NULL DEFAULT (N''),
	[published] [smallint] NOT NULL DEFAULT ((0)),
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[access] [tinyint] NOT NULL DEFAULT ((0)),
	[params] [nvarchar](2048) NOT NULL DEFAULT (N''),
	[metadesc] [nvarchar](1024) NOT NULL,
	[metakey] [nvarchar](1024) NOT NULL,
	[metadata] [nvarchar](2048) NOT NULL,
	[created_user_id] [bigint] NOT NULL DEFAULT ((0)),
	[created_time] [datetime] NOT NULL DEFAULT (getdate()),
	[modified_user_id] [bigint] NOT NULL DEFAULT ((0)),
	[modified_time] [datetime] NOT NULL DEFAULT (getdate()),
	[hits] [bigint] NOT NULL DEFAULT ((0)),
	[language] [nchar](7) NOT NULL,
 CONSTRAINT [PK_jos_categories_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

SET IDENTITY_INSERT jos_categories  ON;

INSERT INTO jos_categories (id, asset_id, parent_id, lft, rgt,level, path, extension, title, alias, note, description, published, checked_out, checked_out_time, access, params, metadesc, metakey, metadata, created_user_id,created_time, modified_user_id, modified_time, hits,language)
SELECT 1, 0, 0, 0, 11, 0, '', 'system', 'ROOT', 'root', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{}', '', '', '', 0, '2009-10-18 16:07:09', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 2, 27, 1, 1, 2, 1, 'uncategorised', 'com_content', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:26:37', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 3, 28, 1, 3, 4, 1, 'uncategorised', 'com_banners', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":"","foobar":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:27:35', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 4, 29, 1, 5, 6, 1, 'uncategorised', 'com_contact', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:27:57', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 5, 30, 1, 7, 8, 1, 'uncategorised', 'com_newsfeeds', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:28:15', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 6, 31, 1, 9, 10, 1, 'uncategorised', 'com_weblinks', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:28:33', 0, '1900-01-01 00:00:00', 0, '*'

SET IDENTITY_INSERT jos_categories  OFF;
/****** Object:  Table [dbo].[jos_modules_menu]    Script Date: 10/20/2010 14:30:39 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_modules_menu]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_modules_menu](
	[moduleid] [int] NOT NULL DEFAULT ((0)),
	[menuid] [int] NOT NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_modules_menu_moduleid] PRIMARY KEY CLUSTERED 
(
	[moduleid] ASC,
	[menuid] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

INSERT INTO jos_modules_menu (moduleid,menuid)
SELECT 1,0
UNION ALL
SELECT 2,0
UNION ALL
SELECT 3,0
UNION ALL
SELECT 4,0
UNION ALL
SELECT 6,0
UNION ALL
SELECT 7,0
UNION ALL
SELECT 8,0
UNION ALL
SELECT 9,0
UNION ALL
SELECT 10,0
UNION ALL
SELECT 12,0
UNION ALL
SELECT 13,0
UNION ALL
SELECT 14,0
UNION ALL
SELECT 15,0
UNION ALL
SELECT 16,0
UNION ALL
SELECT 17,0
UNION ALL
SELECT 18,0
/****** Object:  Table [dbo].[jos_newsfeeds]    Script Date: 10/20/2010 14:31:23 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_newsfeeds]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_newsfeeds](
	[catid] [int] NOT NULL DEFAULT ((0)),
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](100) NOT NULL DEFAULT (N''),
	[alias] [nvarchar](100) NOT NULL DEFAULT (N''),
	[link] [nvarchar](200) NOT NULL DEFAULT (N''),
	[filename] [nvarchar](200) NULL DEFAULT (NULL),
	[published] [smallint] NOT NULL DEFAULT ((0)),
	[numarticles] [bigint] NOT NULL DEFAULT ((1)),
	[cache_time] [bigint] NOT NULL DEFAULT ((3600)),
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[ordering] [int] NOT NULL DEFAULT ((0)),
	[rtl] [smallint] NOT NULL DEFAULT ((0)),
	[access] [tinyint] NOT NULL DEFAULT ((0)),
	[language] [nchar](7) NOT NULL DEFAULT (N''),
	[params] [nvarchar](max) NOT NULL,
	[created] [datetime] NOT NULL DEFAULT (getdate()),
	[created_by] [bigint] NOT NULL DEFAULT ((0)),
	[created_by_alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[modified] [datetime] NOT NULL DEFAULT (getdate()),
	[modified_by] [bigint] NOT NULL DEFAULT ((0)),
	[metakey] [nvarchar](max) NOT NULL,
	[metadesc] [nvarchar](max) NOT NULL,
	[metadata] [nvarchar](max) NOT NULL,
	[xreference] [nvarchar](50) NOT NULL,
	[publish_up] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_down] [datetime] NOT NULL DEFAULT (getdate()),
 CONSTRAINT [PK_jos_newsfeeds_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_contact_details]    Script Date: 10/20/2010 14:25:56 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_contact_details]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_contact_details](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](255) NOT NULL DEFAULT (N''),
	[alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[con_position] [nvarchar](255) NULL DEFAULT (NULL),
	[address] [nvarchar](max) NULL,
	[suburb] [nvarchar](100) NULL DEFAULT (NULL),
	[state] [nvarchar](100) NULL DEFAULT (NULL),
	[country] [nvarchar](100) NULL DEFAULT (NULL),
	[postcode] [nvarchar](100) NULL DEFAULT (NULL),
	[telephone] [nvarchar](255) NULL DEFAULT (NULL),
	[fax] [nvarchar](255) NULL DEFAULT (NULL),
	[misc] [nvarchar](max) NULL,
	[image] [nvarchar](255) NULL DEFAULT (NULL),
	[imagepos] [nvarchar](20) NULL DEFAULT (NULL),
	[email_to] [nvarchar](255) NULL DEFAULT (NULL),
	[default_con] [tinyint] NOT NULL DEFAULT ((0)),
	[published] [smallint] NOT NULL DEFAULT ((0)),
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[ordering] [int] NOT NULL DEFAULT ((0)),
	[params] [nvarchar](max) NOT NULL,
	[user_id] [int] NOT NULL DEFAULT ((0)),
	[catid] [int] NOT NULL DEFAULT ((0)),
	[access] [tinyint] NOT NULL DEFAULT ((0)),
	[mobile] [nvarchar](255) NOT NULL DEFAULT (N''),
	[webpage] [nvarchar](255) NOT NULL DEFAULT (N''),
	[sortname1] [nvarchar](255) NOT NULL,
	[sortname2] [nvarchar](255) NOT NULL,
	[sortname3] [nvarchar](255) NOT NULL,
	[language] [nchar](7) NOT NULL,
	[created] [datetime] NOT NULL DEFAULT (getdate()),
	[created_by] [bigint] NOT NULL DEFAULT ((0)),
	[created_by_alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[modified] [datetime] NOT NULL DEFAULT (getdate()),
	[modified_by] [bigint] NOT NULL DEFAULT ((0)),
	[metakey] [nvarchar](max) NOT NULL,
	[metadesc] [nvarchar](max) NOT NULL,
	[metadata] [nvarchar](max) NOT NULL,
	[featured] [tinyint] NOT NULL DEFAULT ((0)),
	[xreference] [nvarchar](50) NOT NULL,
	[publish_up] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_down] [datetime] NOT NULL DEFAULT (getdate()),
 CONSTRAINT [PK_jos_contact_details_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_redirect_links]    Script Date: 10/20/2010 14:31:36 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_redirect_links]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_redirect_links](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[old_url] [nvarchar](150) NOT NULL,
	[new_url] [nvarchar](150) NOT NULL,
	[referer] [nvarchar](150) NOT NULL,
	[comment] [nvarchar](255) NOT NULL,
	[published] [smallint] NOT NULL,
	[created_date] [datetime] NOT NULL DEFAULT (getdate()),
	[modified_date] [datetime] NOT NULL DEFAULT (getdate()),
 CONSTRAINT [PK_jos_redirect_links_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY],
 CONSTRAINT [jos_redirect_links$idx_link_old] UNIQUE NONCLUSTERED 
(
	[old_url] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_schemas]    Script Date: 10/20/2010 14:31:42 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_schemas]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_schemas](
	[extension_id] [int] NOT NULL,
	[version_id] [nvarchar](20) NOT NULL,
 CONSTRAINT [PK_jos_schemas_extension_id] PRIMARY KEY CLUSTERED 
(
	[extension_id] ASC,
	[version_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_session]    Script Date: 10/20/2010 14:32:07 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_session]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_session](
	[session_id] [nvarchar](32) NOT NULL DEFAULT (N''),
	[client_id] [tinyint] NOT NULL DEFAULT ((0)),
	[guest] [tinyint] NULL DEFAULT ((1)),
	[time] [nvarchar](14) NULL DEFAULT (N''),
	[data] [nvarchar](max) NULL DEFAULT (NULL),
	[userid] [int] NULL DEFAULT ((0)),
	[username] [nvarchar](150) NULL DEFAULT (N''),
	[usertype] [nvarchar](50) NULL DEFAULT (N''),
 CONSTRAINT [PK_jos_session_session_id] PRIMARY KEY CLUSTERED 
(
	[session_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_template_styles]    Script Date: 10/20/2010 14:32:26 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_template_styles]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_template_styles](
	[id] [bigint] IDENTITY(7,1) NOT NULL,
	[template] [nvarchar](50) NOT NULL DEFAULT (N''),
	[client_id] [tinyint] NOT NULL DEFAULT ((0)),
	[home] [tinyint] NOT NULL DEFAULT ((0)),
	[title] [nvarchar](255) NOT NULL DEFAULT (N''),
	[params] [nvarchar](2048) NOT NULL DEFAULT (N''),
 CONSTRAINT [PK_jos_template_styles_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

SET IDENTITY_INSERT jos_template_styles  ON;
INSERT INTO jos_template_styles (id, template, client_id, home, title, params) VALUES (1, 'rhuk_milkyway', '0', '0', 'Milkyway - Default', '{"colorVariation":"blue","backgroundVariation":"blue","widthStyle":"fmax"}');
INSERT INTO jos_template_styles (id, template, client_id, home, title, params) VALUES (2, 'bluestork', '1', '1', 'Bluestork - Default', '{"useRoundedCorners":"1","showSiteName":"0"}');
INSERT INTO jos_template_styles (id, template, client_id, home, title, params) VALUES (3, 'atomic', '0', '0', 'Atomic - Default', '{}');
INSERT INTO jos_template_styles (id, template, client_id, home, title, params) VALUES (4, 'beez_20', 0, 1, 'Beez2 - Default', '{"wrapperSmall":"53","wrapperLarge":"72","logo":"images\\/joomla_black.gif","sitetitle":"Joomla!","sitedescription":"Open Source Content Management Beta","navposition":"left","templatecolor":"personal","html5":"0"}');
INSERT INTO jos_template_styles (id, template, client_id, home, title, params) VALUES (5, 'hathor', '1', '0', 'Hathor - Default', '{"showSiteName":"0","highContrast":"0","boldText":"0","altMenu":"0"}');
INSERT INTO jos_template_styles (id, template, client_id, home, title, params) VALUES (6, 'beez5', 0, 0, 'Beez5 - Default-Fruit Shop', '{"wrapperSmall":"53","wrapperLarge":"72","logo":"images\\/sampledata\\/fruitshop\\/fruits.gif","sitetitle":"Matuna Market ","sitedescription":"Fruit Shop Sample Site","navposition":"left","html5":"0"}');
SET IDENTITY_INSERT jos_template_styles  OFF;
/****** Object:  Table [dbo].[jos_update_categories]    Script Date: 10/20/2010 14:32:35 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_update_categories]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_update_categories](
	[categoryid] [int] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](20) NULL DEFAULT (N''),
	[description] [nvarchar](max) NOT NULL,
	[parent] [int] NULL DEFAULT ((0)),
	[updatesite] [int] NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_update_categories_categoryid] PRIMARY KEY CLUSTERED 
(
	[categoryid] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_content]    Script Date: 10/20/2010 14:27:20 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_content]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_content](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[asset_id] [bigint] NOT NULL DEFAULT ((0)),
	[title] [nvarchar](255) NOT NULL DEFAULT (N''),
	[alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[title_alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[introtext] [nvarchar](max) NOT NULL,
	[fulltext] [nvarchar](max) NOT NULL,
	[state] [smallint] NOT NULL DEFAULT ((0)),
	[sectionid] [bigint] NOT NULL DEFAULT ((0)),
	[mask] [bigint] NOT NULL DEFAULT ((0)),
	[catid] [bigint] NOT NULL DEFAULT ((0)),
	[created] [datetime] NOT NULL DEFAULT (getdate()),
	[created_by] [bigint] NOT NULL DEFAULT ((0)),
	[created_by_alias] [nvarchar](255) NOT NULL DEFAULT (N''),
	[modified] [datetime] NOT NULL DEFAULT (getdate()),
	[modified_by] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_up] [datetime] NOT NULL DEFAULT (getdate()),
	[publish_down] [datetime] NOT NULL DEFAULT (getdate()),
	[images] [nvarchar](max) NOT NULL,
	[urls] [nvarchar](max) NOT NULL,
	[attribs] [nvarchar](max) NOT NULL,
	[version] [bigint] NOT NULL DEFAULT ((1)),
	[parentid] [bigint] NOT NULL DEFAULT ((0)),
	[ordering] [int] NOT NULL DEFAULT ((0)),
	[metakey] [nvarchar](max) NOT NULL,
	[metadesc] [nvarchar](max) NOT NULL,
	[access] [bigint] NOT NULL DEFAULT ((0)),
	[hits] [bigint] NOT NULL DEFAULT ((0)),
	[metadata] [nvarchar](max) NOT NULL,
	[featured] [tinyint] NOT NULL DEFAULT ((0)),
	[language] [nchar](7) NOT NULL,
	[xreference] [nvarchar](50) NOT NULL,
 CONSTRAINT [PK_jos_content_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_update_sites]    Script Date: 10/20/2010 14:32:44 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_update_sites]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_update_sites](
	[update_site_id] [int] IDENTITY(3,1) NOT NULL,
	[name] [nvarchar](100) NULL DEFAULT (N''),
	[type] [nvarchar](20) NULL DEFAULT (N''),
	[location] [nvarchar](max) NOT NULL,
	[enabled] [int] NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_update_sites_update_site_id] PRIMARY KEY CLUSTERED 
(
	[update_site_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END


SET IDENTITY_INSERT jos_update_sites  ON;

INSERT INTO jos_update_sites (update_site_id, name, type, location, enabled)
SELECT 1, 'Joomla Core', 'collection', 'http://update.joomla.org/core/list.xml', 1
UNION ALL
SELECT 2, 'Joomla Extension Directory', 'collection', 'http://update.joomla.org/jed/list.xml', 1

SET IDENTITY_INSERT jos_update_sites  OFF;
/****** Object:  Table [dbo].[jos_update_sites_extensions]    Script Date: 10/20/2010 14:32:48 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_update_sites_extensions]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_update_sites_extensions](
	[update_site_id] [int] NOT NULL DEFAULT ((0)),
	[extension_id] [int] NOT NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_update_sites_extensions_update_site_id] PRIMARY KEY CLUSTERED 
(
	[update_site_id] ASC,
	[extension_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

INSERT INTO jos_update_sites_extensions (update_site_id, extension_id)
SELECT 1, 700
UNION ALL
SELECT 2, 700
/****** Object:  Table [dbo].[jos_updates]    Script Date: 10/20/2010 14:33:21 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_updates]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_updates](
	[update_id] [int] IDENTITY(1,1) NOT NULL,
	[update_site_id] [int] NULL DEFAULT ((0)),
	[extension_id] [int] NULL DEFAULT ((0)),
	[categoryid] [int] NULL DEFAULT ((0)),
	[name] [nvarchar](100) NULL DEFAULT (N''),
	[description] [nvarchar](max) NOT NULL,
	[element] [nvarchar](100) NULL DEFAULT (N''),
	[type] [nvarchar](20) NULL DEFAULT (N''),
	[folder] [nvarchar](20) NULL DEFAULT (N''),
	[client_id] [smallint] NULL DEFAULT ((0)),
	[version] [nvarchar](10) NULL DEFAULT (N''),
	[data] [nvarchar](max) NOT NULL,
	[detailsurl] [nvarchar](max) NOT NULL,
 CONSTRAINT [PK_jos_updates_update_id] PRIMARY KEY CLUSTERED 
(
	[update_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_user_profiles]    Script Date: 10/20/2010 14:33:29 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_user_profiles]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_user_profiles](
	[user_id] [int] NOT NULL,
	[profile_key] [nvarchar](100) NOT NULL,
	[profile_value] [nvarchar](255) NOT NULL,
	[ordering] [int] NOT NULL DEFAULT ((0)),
 CONSTRAINT [jos_user_profiles$idx_user_id_profile_key] UNIQUE CLUSTERED 
(
	[user_id] ASC,
	[profile_key] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_user_usergroup_map]    Script Date: 10/20/2010 14:33:35 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_user_usergroup_map]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_user_usergroup_map](
	[user_id] [bigint] NOT NULL DEFAULT ((0)),
	[group_id] [bigint] NOT NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_user_usergroup_map_user_id] PRIMARY KEY CLUSTERED 
(
	[user_id] ASC,
	[group_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_content_frontpage]    Script Date: 10/20/2010 14:27:28 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_content_frontpage]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_content_frontpage](
	[content_id] [int] NOT NULL DEFAULT ((0)),
	[ordering] [int] NOT NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_content_frontpage_content_id] PRIMARY KEY CLUSTERED 
(
	[content_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_usergroups]    Script Date: 10/20/2010 14:33:50 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_usergroups]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_usergroups](
	[id] [bigint] IDENTITY(9,1) NOT NULL,
	[parent_id] [bigint] NOT NULL DEFAULT ((0)),
	[lft] [int] NOT NULL DEFAULT ((0)),
	[rgt] [int] NOT NULL DEFAULT ((0)),
	[title] [nvarchar](100) NOT NULL DEFAULT (N''),
 CONSTRAINT [PK_jos_usergroups_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY],
 CONSTRAINT [jos_usergroups$idx_usergroup_title_lookup] UNIQUE NONCLUSTERED 
(
	[title] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

SET IDENTITY_INSERT jos_usergroups  ON;
INSERT INTO jos_usergroups (id ,parent_id ,lft ,rgt ,title)
SELECT 1, 0, 1, 20, 'Public'
UNION ALL
SELECT 2, 1, 6, 17, 'Registered'
UNION ALL
SELECT 3, 2, 7, 14, 'Author'
UNION ALL
SELECT 4, 3, 8, 11, 'Editor'
UNION ALL
SELECT 5, 4, 9, 10, 'Publisher'
UNION ALL
SELECT 6, 1, 2, 5, 'Manager'
UNION ALL
SELECT 7, 6, 3, 4, 'Administrator'
UNION ALL
SELECT 8, 1, 18, 19, 'Super Users'

SET IDENTITY_INSERT jos_usergroups  OFF;
/****** Object:  Table [dbo].[jos_content_rating]    Script Date: 10/20/2010 14:27:48 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_content_rating]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_content_rating](
	[content_id] [int] NOT NULL DEFAULT ((0)),
	[rating_sum] [bigint] NOT NULL DEFAULT ((0)),
	[rating_count] [bigint] NOT NULL DEFAULT ((0)),
	[lastip] [nvarchar](50) NOT NULL DEFAULT (N''),
 CONSTRAINT [PK_jos_content_rating_content_id] PRIMARY KEY CLUSTERED 
(
	[content_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_users]    Script Date: 10/20/2010 14:34:32 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_users]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_users](
	[id] [int] IDENTITY(43,1) NOT NULL,
	[name] [nvarchar](255) NOT NULL DEFAULT (N''),
	[username] [nvarchar](150) NOT NULL DEFAULT (N''),
	[email] [nvarchar](100) NOT NULL DEFAULT (N''),
	[password] [nvarchar](100) NOT NULL DEFAULT (N''),
	[usertype] [nvarchar](25) NOT NULL DEFAULT (N''),
	[block] [smallint] NOT NULL DEFAULT ((0)),
	[sendEmail] [smallint] NULL DEFAULT ((0)),
	[registerDate] [datetime] NOT NULL DEFAULT (getdate()),
	[lastvisitDate] [datetime] NOT NULL DEFAULT (getdate()),
	[activation] [nvarchar](100) NOT NULL DEFAULT (N''),
	[params] [nvarchar](max) NOT NULL,
 CONSTRAINT [PK_jos_users_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_core_log_searches]    Script Date: 10/20/2010 14:27:58 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_core_log_searches]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_core_log_searches](
	[search_term] [nvarchar](128) NOT NULL DEFAULT (N''),
	[hits] [bigint] NOT NULL DEFAULT ((0))
) ON [PRIMARY]
END

/****** Object:  Table [dbo].[jos_extensions]    Script Date: 10/20/2010 14:28:42 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_extensions]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_extensions](
	[extension_id] [int] IDENTITY(10000,1) NOT NULL,
	[name] [nvarchar](100) NOT NULL,
	[type] [nvarchar](20) NOT NULL,
	[element] [nvarchar](100) NOT NULL,
	[folder] [nvarchar](100) NOT NULL,
	[client_id] [smallint] NOT NULL,
	[enabled] [smallint] NOT NULL DEFAULT ((1)),
	[access] [tinyint] NOT NULL DEFAULT ((1)),
	[protected] [smallint] NOT NULL DEFAULT ((0)),
	[manifest_cache] [nvarchar](max) NOT NULL,
	[params] [nvarchar](max) NOT NULL,
	[custom_data] [nvarchar](max) NOT NULL,
	[system_data] [nvarchar](max) NOT NULL,
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[ordering] [int] NULL DEFAULT ((0)),
	[state] [int] NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_extensions_extension_id] PRIMARY KEY CLUSTERED 
(
	[extension_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

SET IDENTITY_INSERT jos_extensions  ON;
INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 1, 'com_mailto', 'component', 'com_mailto', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 2, 'com_wrapper', 'component', 'com_wrapper', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 3, 'com_admin', 'component', 'com_admin', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 4, 'com_banners', 'component', 'com_banners', '', 1, 1, 1, 0, '', '{"purchase_type":"3","track_impressions":"0","track_clicks":"0","metakey_prefix":""}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 5, 'com_cache', 'component', 'com_cache', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 6, 'com_categories', 'component', 'com_categories', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 7, 'com_checkin', 'component', 'com_checkin', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 8, 'com_contact', 'component', 'com_contact', '', 1, 1, 1, 0, '', '{"show_contact_category":"hide","show_contact_list":"0","presentation_style":"sliders","show_name":"1","show_position":"1","show_email":"0","show_street_address":"1","show_suburb":"1","show_state":"1","show_postcode":"1","show_country":"1","show_telephone":"1","show_mobile":"1","show_fax":"1","show_webpage":"1","show_misc":"1","show_image":"1","image":"","allow_vcard":"0","show_articles":"0","show_profile":"0","show_links":"0","linka_name":"","linkb_name":"","linkc_name":"","linkd_name":"","linke_name":"","contact_icons":"0","icon_address":"","icon_email":"","icon_telephone":"","icon_mobile":"","icon_fax":"","icon_misc":"","show_headings":"1","show_position_headings":"1","show_email_headings":"0","show_telephone_headings":"1","show_mobile_headings":"0","show_fax_headings":"0","allow_vcard_headings":"0","show_suburb_headings":"1","show_state_headings":"1","show_country_headings":"1","show_email_form":"1","show_email_copy":"1","banned_email":"","banned_subject":"","banned_text":"","validate_session":"1","custom_reply":"0","redirect":"","show_category_crumb":"0","metakey":"","metadesc":"","robots":"","author":"","rights":"","xreference":""}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 9, 'com_cpanel', 'component', 'com_cpanel', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 10, 'com_installer', 'component', 'com_installer', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 11, 'com_languages', 'component', 'com_languages', '', 1, 1, 1, 1, '', '{"administrator":"en-GB","site":"en-GB"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 12, 'com_login', 'component', 'com_login', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 13, 'com_media', 'component', 'com_media', '', 1, 1, 0, 1, '', '{"upload_extensions":"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS","upload_maxsize":"10485760","file_path":"images","image_path":"images","restrict_uploads":"1","allowed_media_usergroup":"3","check_mime":"1","image_extensions":"bmp,gif,jpg,png","ignore_extensions":"","upload_mime":"image\\/jpeg,image\\/gif,image\\/png,image\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/powerpoint,text\\/plain,application\\/x-zip","upload_mime_illegal":"text\\/html","enable_flash":"0"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 14, 'com_menus', 'component', 'com_menus', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 15, 'com_messages', 'component', 'com_messages', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 16, 'com_modules', 'component', 'com_modules', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 17, 'com_newsfeeds', 'component', 'com_newsfeeds', '', 1, 1, 1, 0, '', '{"show_feed_image":"1","show_feed_description":"1","show_item_description":"1","feed_word_count":"0","show_headings":"1","show_name":"1","show_articles":"0","show_link":"1","show_description":"1","show_description_image":"1","display_num":"","show_pagination_limit":"1","show_pagination":"1","show_pagination_results":"1","show_cat_items":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 18, 'com_plugins', 'component', 'com_plugins', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 19, 'com_search', 'component', 'com_search', '', 1, 1, 1, 1, '', '{"enabled":"0","show_date":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 20, 'com_templates', 'component', 'com_templates', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 21, 'com_weblinks', 'component', 'com_weblinks', '', 1, 1, 1, 0, '', '{"show_comp_description":"1","comp_description":"","show_link_hits":"1","show_link_description":"1","show_other_cats":"0","show_headings":"0","show_numbers":"0","show_report":"1","count_clicks":"1","target":"0","link_icons":""}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 22, 'com_content', 'component', 'com_content', '', 1, 1, 0, 1, '', '{"show_title":"1","link_titles":"1","show_intro":"1","show_category":"1","link_category":"1","show_parent_category":"0","link_parent_category":"0","show_author":"1","link_author":"0","show_create_date":"0","show_modify_date":"0","show_publish_date":"1","show_item_navigation":"1","show_readmore":"1","show_icons":"1","show_print_icon":"1","show_email_icon":"1","show_hits":"1","num_leading_articles":"1","num_intro_articles":"4","num_columns":"2","num_links":"4","multi_column_order":"0","show_pagination":"2","show_pagination_results":"1","display_num":"10","show_headings":"1","list_show_title":"0","show_date":"hide","date_format":"","list_hits":"1","list_author":"1","filter_field":"hide","show_pagination_limit":"1","maxLevel":"1","show_category_title":"0","show_empty_categories":"0","show_description":"0","show_description_image":"0","show_cat_num_articles":"0","drill_down_layout":"0","orderby_pri":"order","orderby_sec":"rdate","show_noauth":"0","show_feed_link":"1","feed_summary":"0","filter_type":"BL","filter_tags":"","filter_attritbutes":""}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 23, 'com_config', 'component', 'com_config', '', 1, 1, 0, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 24, 'com_redirect', 'component', 'com_redirect', '', 1, 1, 0, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 25, 'com_users', 'component', 'com_users', '', 1, 1, 0, 1, '', '{"allowUserRegistration":"1","new_usertype":"2","useractivation":"1","frontend_userparams":"1","mailSubjectPrefix":"","mailBodySuffix":""}', '', '', 0, '1900-01-01 00:00:00', 0, 0

INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 100, 'PHPMailer', 'library', 'phpmailer', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 101, 'SimplePie', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 102, 'Bitfolge', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 103, 'phputf8', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0



INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 200, 'mod_articles_archive', 'module', 'mod_articles_archive', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 201, 'mod_articles_latest', 'module', 'mod_articles_latest', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 202, 'mod_articles_popular', 'module', 'mod_articles_popular', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 203, 'mod_banners', 'module', 'mod_banners', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 204, 'mod_breadcrumbs', 'module', 'mod_breadcrumbs', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 205, 'mod_custom', 'module', 'mod_custom', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 206, 'mod_feed', 'module', 'mod_feed', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 207, 'mod_footer', 'module', 'mod_footer', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 208, 'mod_login', 'module', 'mod_login', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 209, 'mod_menu', 'module', 'mod_menu', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 210, 'mod_articles_news', 'module', 'mod_articles_news', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 211, 'mod_random_image', 'module', 'mod_random_image', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 212, 'mod_related_items', 'module', 'mod_related_items', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 213, 'mod_search', 'module', 'mod_search', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 214, 'mod_stats', 'module', 'mod_stats', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 215, 'mod_syndicate', 'module', 'mod_syndicate', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 216, 'mod_users_latest', 'module', 'mod_users_latest', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 217, 'mod_weblinks', 'module', 'mod_weblinks', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 218, 'mod_whosonline', 'module', 'mod_whosonline', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 219, 'mod_wrapper', 'module', 'mod_wrapper', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 220, 'mod_articles_category', 'module', 'mod_articles_category', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 221, 'mod_articles_categories', 'module', 'mod_articles_categories', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 222, 'mod_languages', 'module', 'mod_languages', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0



INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 300, 'mod_custom', 'module', 'mod_custom', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 301, 'mod_feed', 'module', 'mod_feed', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 302, 'mod_latest', 'module', 'mod_latest', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 303, 'mod_logged', 'module', 'mod_logged', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 304, 'mod_login', 'module', 'mod_login', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 305, 'mod_menu', 'module', 'mod_menu', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 306, 'mod_online', 'module', 'mod_online', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 307, 'mod_popular', 'module', 'mod_popular', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 308, 'mod_quickicon', 'module', 'mod_quickicon', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 309, 'mod_status', 'module', 'mod_status', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 310, 'mod_submenu', 'module', 'mod_submenu', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 311, 'mod_title', 'module', 'mod_title', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 312, 'mod_toolbar', 'module', 'mod_toolbar', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 313, 'mod_unread', 'module', 'mod_unread', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0




INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 400, 'plg_authentication_gmail', 'plugin', 'gmail', 'authentication', 0, 0, 1, 0, '', '{"applysuffix":"0","suffix":"","verifypeer":"1","user_blacklist":""}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 401, 'plg_authentication_joomla', 'plugin', 'joomla', 'authentication', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 402, 'plg_authentication_ldap', 'plugin', 'ldap', 'authentication', 0, 0, 1, 0, '', '{"host":"","port":"389","use_ldapV3":"0","negotiate_tls":"0","no_referrals":"0","auth_method":"bind","base_dn":"","search_string":"","users_dn":"","username":"admin","password":"bobby7","ldap_fullname":"fullName","ldap_email":"mail","ldap_uid":"uid"}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 403, 'plg_authentication_openid', 'plugin', 'openid', 'authentication', 0, 0, 1, 0, '', '{"usermode":"2","phishing-resistant":"0","multi-factor":"0","multi-factor-physical":"0"}', '', '', 0, '1900-01-01 00:00:00', 4, 0
UNION ALL
SELECT 404, 'plg_content_emailcloak', 'plugin', 'emailcloak', 'content', 0, 1, 1, 0, '', '{"mode":"1"}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 405, 'plg_content_geshi', 'plugin', 'geshi', 'content', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 406, 'plg_content_loadmodule', 'plugin', 'loadmodule', 'content', 0, 1, 1, 0, '', '{"style":"table"}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 407, 'plg_content_pagebreak', 'plugin', 'pagebreak', 'content', 0, 1, 1, 1, '', '{"title":"1","multipage_toc":"1","showall":"1"}', '', '', 0, '1900-01-01 00:00:00', 4, 0
UNION ALL
SELECT 408, 'plg_content_pagenavigation', 'plugin', 'pagenavigation', 'content', 0, 1, 1, 1, '', '{"position":"1"}', '', '', 0, '1900-01-01 00:00:00', 5, 0
UNION ALL
SELECT 409, 'plg_content_vote', 'plugin', 'vote', 'content', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 6, 0
UNION ALL
SELECT 410, 'plg_editors_codemirror', 'plugin', 'codemirror', 'editors', 0, 1, 1, 1, '', '{"linenumbers":"0","tabmode":"indent"}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 411, 'plg_editors_none', 'plugin', 'none', 'editors', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 412, 'plg_editors_tinymce', 'plugin', 'tinymce', 'editors', 0, 1, 1, 1, '', '{"mode":"1","skin":"0","compressed":"0","cleanup_startup":"0","cleanup_save":"2","entity_encoding":"raw","lang_mode":"0","lang_code":"en","text_direction":"ltr","content_css":"1","content_css_custom":"","relative_urls":"1","newlines":"0","invalid_elements":"script,applet,iframe","extended_elements":"","toolbar":"top","toolbar_align":"left","html_height":"550","html_width":"750","element_path":"1","fonts":"1","paste":"1","searchreplace":"1","insertdate":"1","format_date":"%Y-%m-%d","inserttime":"1","format_time":"%H:%M:%S","colors":"1","table":"1","smilies":"1","media":"1","hr":"1","directionality":"1","fullscreen":"1","style":"1","layer":"1","xhtmlxtras":"1","visualchars":"1","nonbreaking":"1","template":"1","blockquote":"1","wordcount":"1","advimage":"1","advlink":"1","autosave":"1","contextmenu":"1","inlinepopups":"1","safari":"0","custom_plugin":"","custom_button":""}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 413, 'plg_editors-xtd_article', 'plugin', 'article', 'editors-xtd', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 414, 'plg_editors-xtd_image', 'plugin', 'image', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 415, 'plg_editors-xtd_pagebreak', 'plugin', 'pagebreak', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 416, 'plg_editors-xtd_readmore', 'plugin', 'readmore', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 4, 0
UNION ALL
SELECT 417, 'plg_search_categories', 'plugin', 'categories', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 418, 'plg_search_contacts', 'plugin', 'contacts', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 419, 'plg_search_content', 'plugin', 'content', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 420, 'plg_search_newsfeeds', 'plugin', 'newsfeeds', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 421, 'plg_search_weblinks', 'plugin', 'weblinks', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 422, 'plg_system_cache', 'plugin', 'cache', 'system', 0, 0, 1, 1, '', '{"browsercache":"0","cachetime":"15"}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 423, 'plg_system_debug', 'plugin', 'debug', 'system', 0, 1, 1, 0, '', '{"profile":"1","queries":"1","memory":"1","language_files":"1","language_strings":"1","strip-first":"1","strip-prefix":"","strip-suffix":""}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 424, 'plg_system_log', 'plugin', 'log', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 425, 'plg_system_redirect', 'plugin', 'redirect', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 4, 0
UNION ALL
SELECT 426, 'plg_system_remember', 'plugin', 'remember', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 5, 0
UNION ALL
SELECT 427, 'plg_system_sef', 'plugin', 'sef', 'system', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 6, 0
UNION ALL
SELECT 428, 'plg_user_contactcreator', 'plugin', 'contactcreator', 'user', 0, 0, 1, 1, '', '{"autowebpage":"","category":"26","autopublish":"0"}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 429, 'plg_user_joomla', 'plugin', 'joomla', 'user', 0, 1, 1, 0, '', '{"autoregister":"1"}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 430, 'plg_user_profile', 'plugin', 'profile', 'user', 0, 0, 1, 1, '', '{"register-require_address1":"0","register-require_address2":"0","register-require_city":"0","register-require_region":"0","register-require_country":"0","register-require_postal_code":"0","register-require_phone":"0","register-require_website":"0","profile-require_address1":"1","profile-require_address2":"1","profile-require_city":"1","profile-require_region":"1","profile-require_country":"1","profile-require_postal_code":"1","profile-require_phone":"1","profile-require_website":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 431, 'plg_extension_joomla', 'plugin', 'joomla', 'extension', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 432, 'plg_system_languagefilter', 'plugin', 'languagefilter', 'system', 0, 0, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0





INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 500, 'atomic', 'template', 'atomic', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 501, 'rhuk_milkyway', 'template', 'rhuk_milkyway', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 502, 'bluestork', 'template', 'bluestork', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 503, 'beez_20', 'template', 'beez_20', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 504, 'hathor', 'template', 'hathor', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 505, 'Beez5', 'template', 'beez5', '', 0, 1, 1, 0, 'a:11:{s:6:"legacy";b:1;s:4:"name";s:5:"Beez5";s:4:"type";s:8:"template";s:12:"creationDate";s:11:"21 May 2010";s:6:"author";s:12:"Angie Radtke";s:9:"copyright";s:72:"Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.";s:11:"authorEmail";s:23:"a.radtke@derauftritt.de";s:9:"authorUrl";s:26:"http://www.der-auftritt.de";s:7:"version";s:5:"1.6.0";s:11:"description";s:22:"A Easy Version of Beez";s:5:"group";s:0:"";}', '{"wrapperSmall":"53","wrapperLarge":"72","sitetitle":"BEEZ 2.0","sitedescription":"Your site name","navposition":"center","html5":"0"}', '', '', 0, '1900-01-01 00:00:00', 0, 0


INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 600, 'English (United Kingdom)', 'language', 'en-GB', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 601, 'English (United Kingdom)', 'language', 'en-GB', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 604, 'XXTestLang', 'language', 'xx-XX', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 605, 'XXTestLang', 'language', 'xx-XX', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0

INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 700, 'Joomla! CMS', 'file', 'joomla', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0

SET IDENTITY_INSERT jos_extensions  OFF;
/****** Object:  Table [dbo].[jos_assets]    Script Date: 10/20/2010 14:23:13 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_assets]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_assets](
	[id] [bigint] IDENTITY(32,1) NOT NULL,
	[parent_id] [int] NOT NULL DEFAULT ((0)),
	[lft] [int] NOT NULL DEFAULT ((0)),
	[rgt] [int] NOT NULL DEFAULT ((0)),
	[level] [bigint] NOT NULL,
	[name] [nvarchar](50) NOT NULL,
	[title] [nvarchar](100) NOT NULL,
	[rules] [nvarchar](max) NOT NULL,
 CONSTRAINT [PK_jos_assets_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY],
 CONSTRAINT [jos_assets$idx_asset_name] UNIQUE NONCLUSTERED 
(
	[name] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

SET IDENTITY_INSERT jos_assets  ON;

INSERT INTO jos_assets (id, parent_id, lft, rgt, level, name, title, rules)
SELECT 1,0,0,61,0,'root.1','Root Asset','{"core.login.site":{"6":1,"2":1},"core.login.admin":{"6":1},"core.admin":{"8":1},"core.manage":{"7":1},"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}'
UNION ALL
SELECT 2,1,1,2,1,'com_admin','com_admin','{}'
UNION ALL
SELECT 3,1,3,6,1,'com_banners','com_banners','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 4,1,7,8,1,'com_cache','com_cache','{"core.admin":{"7":1},"core.manage":{"7":1}}'
UNION ALL
SELECT 5,1,9,10,1,'com_checkin','com_checkin','{"core.admin":{"7":1},"core.manage":{"7":1}}'
UNION ALL
SELECT 6,1,11,12,1,'com_config','com_config','{}'
UNION ALL
SELECT 7,1,13,16,1,'com_contact','com_contact','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 8,1,17,20,1,'com_content','com_content','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":[],"core.edit":{"4":1},"core.edit.state":{"5":1}}'
UNION ALL
SELECT 9,1,21,22,1,'com_cpanel','com_cpanel','{}'
UNION ALL
SELECT 10,1,23,24,1,'com_installer','com_installer','{"core.admin":{"7":1},"core.manage":{"7":1},"core.create":[],"core.delete":[],"core.edit.state":[]}'
UNION ALL
SELECT 11,1,25,26,1,'com_languages','com_languages','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 12,1,27,28,1,'com_login','com_login','{}'
UNION ALL
SELECT 13,1,29,30,1,'com_mailto','com_mailto','{}'
UNION ALL
SELECT 14,1,31,32,1,'com_massmail','com_massmail','{}'
UNION ALL
SELECT 15,1,33,34,1,'com_media','com_media','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":{"5":1},"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 16,1,35,36,1,'com_menus','com_menus','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 17,1,37,38,1,'com_messages','com_messages','{"core.admin":{"7":1},"core.manage":{"7":1}}'
UNION ALL
SELECT 18,1,39,40,1,'com_modules','com_modules','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 19,1,41,44,1,'com_newsfeeds','com_newsfeeds','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 20,1,45,46,1,'com_plugins','com_plugins','{"core.admin":{"7":1},"core.manage":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 21,1,47,48,1,'com_redirect','com_redirect','{"core.admin":{"7":1},"core.manage":[]}'
UNION ALL
SELECT 22,1,49,50,1,'com_search','com_search','{"core.admin":{"7":1},"core.manage":{"6":1}}'
UNION ALL
SELECT 23,1,51,52,1,'com_templates','com_templates','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 24,1,53,54,1,'com_users','com_users','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 25,1,55,58,1,'com_weblinks','com_weblinks','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":[],"core.edit":{"4":1},"core.edit.state":{"5":1}}'
UNION ALL
SELECT 26,1,59,60,1,'com_wrapper','com_wrapper','{}'
UNION ALL
SELECT 27, 8, 18, 19, 2, 'com_content.category.2', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 28, 3, 4, 5, 2, 'com_banners.category.3', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 29, 7, 14, 15, 2, 'com_contact.category.4', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 30, 19, 42, 43, 2, 'com_newsfeeds.category.5', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 31, 25, 56, 57, 2, 'com_weblinks.category.6', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'

SET IDENTITY_INSERT jos_assets  OFF;
/****** Object:  Table [dbo].[jos_viewlevels]    Script Date: 10/20/2010 14:34:45 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_viewlevels]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_viewlevels](
	[id] [bigint] IDENTITY(4,1) NOT NULL,
	[title] [nvarchar](100) NOT NULL DEFAULT (N''),
	[ordering] [int] NOT NULL DEFAULT ((0)),
	[rules] [nvarchar](max) NOT NULL,
 CONSTRAINT [PK_jos_viewlevels_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY],
 CONSTRAINT [jos_viewlevels$idx_assetgroup_title_lookup] UNIQUE NONCLUSTERED 
(
	[title] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

SET IDENTITY_INSERT jos_viewlevels  ON 
INSERT INTO jos_viewlevels (id, title, ordering, rules) 
SELECT 1, 'Public', 0, '[]'
UNION ALL
SELECT 2, 'Registered', 1, '[6,2]'
UNION ALL
SELECT 3, 'Special', 2, '[6,7,8]'

SET IDENTITY_INSERT jos_viewlevels  OFF;

/****** Object:  Table [dbo].[jos_languages]    Script Date: 10/20/2010 14:28:57 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_languages]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_languages](
	[lang_id] [bigint] IDENTITY(4,1) NOT NULL,
	[lang_code] [nchar](7) NOT NULL,
	[title] [nvarchar](50) NOT NULL,
	[title_native] [nvarchar](50) NOT NULL,
	[sef] [nvarchar](50) NOT NULL,
	[image] [nvarchar](50) NOT NULL,
	[description] [nvarchar](512) NOT NULL,
	[metakey] [nvarchar](max) NOT NULL,
	[metadesc] [nvarchar](max) NOT NULL,
	[published] [int] NOT NULL DEFAULT ((0)),
 CONSTRAINT [PK_jos_languages_lang_id] PRIMARY KEY CLUSTERED 
(
	[lang_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY],
 CONSTRAINT [jos_languages$idx_sef] UNIQUE NONCLUSTERED 
(
	[sef] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

SET IDENTITY_INSERT jos_languages  ON;

INSERT INTO jos_languages (lang_id,lang_code,title,title_native,sef,image,description,metakey,metadesc,published)
SELECT 1, 'en-GB', 'English (UK)', 'English (UK)', 'en', 'en', '', '', '', 1
UNION ALL
SELECT 3, 'xx-XX', 'xx (Test)', 'xx (Test)', 'xx', 'br', '', '', '', 1

SET IDENTITY_INSERT jos_languages  OFF;
/****** Object:  Table [dbo].[jos_banner_clients]    Script Date: 10/20/2010 14:23:32 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[jos_banner_clients]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[jos_banner_clients](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](255) NOT NULL DEFAULT (N''),
	[contact] [nvarchar](255) NOT NULL DEFAULT (N''),
	[email] [nvarchar](255) NOT NULL DEFAULT (N''),
	[extrainfo] [nvarchar](max) NOT NULL,
	[state] [smallint] NOT NULL DEFAULT ((0)),
	[checked_out] [bigint] NOT NULL DEFAULT ((0)),
	[checked_out_time] [datetime] NOT NULL DEFAULT (getdate()),
	[metakey] [nvarchar](max) NOT NULL,
	[own_prefix] [smallint] NOT NULL DEFAULT ((0)),
	[metakey_prefix] [nvarchar](255) NOT NULL DEFAULT (N''),
	[purchase_type] [smallint] NOT NULL DEFAULT ((-1)),
	[track_clicks] [smallint] NOT NULL DEFAULT ((-1)),
	[track_impressions] [smallint] NOT NULL DEFAULT ((-1)),
 CONSTRAINT [PK_jos_banner_clients_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END

