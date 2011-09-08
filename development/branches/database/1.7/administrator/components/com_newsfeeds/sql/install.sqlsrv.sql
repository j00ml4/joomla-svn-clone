CREATE TABLE #__newsfeeds`(
	[catid] [int] NOT NULL,
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](100) NOT NULL,
	[alias] [nvarchar](100) NOT NULL,
	[link] [nvarchar](200) NOT NULL,
	[filename] [nvarchar](200) NULL,
	[published] [smallint] NOT NULL,
	[numarticles] [bigint] NOT NULL,
	[cache_time] [bigint] NOT NULL,
	[checked_out] [bigint] NOT NULL,
	[checked_out_time] [datetime2](0) NOT NULL,
	[ordering] [int] NOT NULL,
	[rtl] [smallint] NOT NULL,
	[access] [tinyint] NOT NULL,
	[language] [nchar](7) NOT NULL,
	[params] [nvarchar](max) NOT NULL,
	[created] [datetime2](0) NOT NULL,
	[created_by] [bigint] NOT NULL,
	[created_by_alias] [nvarchar](255) NOT NULL,
	[modified] [datetime2](0) NOT NULL,
	[modified_by] [bigint] NOT NULL,
	[metakey] [nvarchar](max) NOT NULL,
	[metadesc] [nvarchar](max) NOT NULL,
	[metadata] [nvarchar](max) NOT NULL,
	[xreference] [nvarchar](50) NOT NULL,
	[publish_up] [datetime2](0) NOT NULL,
	[publish_down] [datetime2](0) NOT NULL,
 CONSTRAINT [PK_#__newsfeeds_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF)
)