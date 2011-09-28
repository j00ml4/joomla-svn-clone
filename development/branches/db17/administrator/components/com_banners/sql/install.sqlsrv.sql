CREATE TABLE #__banners(
	[id] [int] IDENTITY(1,1) NOT NULL,
	[cid] [int] NOT NULL,
	[type] [int] NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[alias] [nvarchar](255) NOT NULL,
	[imptotal] [int] NOT NULL,
	[impmade] [int] NOT NULL,
	[clicks] [int] NOT NULL,
	[clickurl] [nvarchar](200) NOT NULL,
	[state] [smallint] NOT NULL,
	[catid] [bigint] NOT NULL,
	[description] [nvarchar](max) NOT NULL,
	[custombannercode] [nvarchar](2048) NOT NULL,
	[sticky] [tinyint] NOT NULL,
	[ordering] [int] NOT NULL,
	[metakey] [nvarchar](max) NOT NULL,
	[params] [nvarchar](max) NOT NULL,
	[own_prefix] [smallint] NOT NULL,
	[metakey_prefix] [nvarchar](255) NOT NULL,
	[purchase_type] [smallint] NOT NULL,
	[track_clicks] [smallint] NOT NULL,
	[track_impressions] [smallint] NOT NULL,
	[checked_out] [bigint] NOT NULL,
	[checked_out_time] [datetime2](0) NOT NULL,
	[publish_up] [datetime2](0) NOT NULL,
	[publish_down] [datetime2](0) NOT NULL,
	[reset] [datetime2](0) NOT NULL,
	[created] [datetime2](0) NOT NULL,
	[language] [nchar](7) NOT NULL,
 CONSTRAINT [PK_#__banners_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF)
)

CREATE TABLE #__banners(
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[contact] [nvarchar](255) NOT NULL,
	[email] [nvarchar](255) NOT NULL,
	[extrainfo] [nvarchar](max) NOT NULL,
	[state] [smallint] NOT NULL,
	[checked_out] [bigint] NOT NULL,
	[checked_out_time] [datetime2](0) NOT NULL,
	[metakey] [nvarchar](max) NOT NULL,
	[own_prefix] [smallint] NOT NULL,
	[metakey_prefix] [nvarchar](255) NOT NULL,
	[purchase_type] [smallint] NOT NULL,
	[track_clicks] [smallint] NOT NULL,
	[track_impressions] [smallint] NOT NULL,
 CONSTRAINT [PK_#__banner_clients_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF)
)

GO
CREATE TABLE [dbo].[sharp$__banner_clients](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[contact] [nvarchar](255) NOT NULL,
	[email] [nvarchar](255) NOT NULL,
	[extrainfo] [nvarchar](max) NOT NULL,
	[state] [smallint] NOT NULL,
	[checked_out] [bigint] NOT NULL,
	[checked_out_time] [datetime2](0) NOT NULL,
	[metakey] [nvarchar](max) NOT NULL,
	[own_prefix] [smallint] NOT NULL,
	[metakey_prefix] [nvarchar](255) NOT NULL,
	[purchase_type] [smallint] NOT NULL,
	[track_clicks] [smallint] NOT NULL,
	[track_impressions] [smallint] NOT NULL,
 CONSTRAINT [PK_#__banner_clients_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF)
)
GO