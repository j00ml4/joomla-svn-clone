# $Id$

#
# Dumping data for table `#__banner`
#

INSERT INTO `#__banner` VALUES (1, 1, 'banner', 'OSM 1', 0, 42, 0, 'osmbanner1.png', 'http://www.opensourcematters.org', '2004-07-07 15:31:29', 1, 0, '2000-00-00 00:00:00', NULL, NULL);
INSERT INTO `#__banner` VALUES (2, 1, 'banner', 'OSM 2', 0, 48, 0, 'osmbanner2.png', 'http://www.opensourcematters.org', '2004-07-07 15:31:29', 1, 0, '2000-00-00 00:00:00', NULL, NULL);

#
# Dumping data for table `#__bannerclient`
#

INSERT INTO `#__bannerclient` VALUES (1, 'Open Source Matters', 'Administrator', 'admin@opensourcematters.org', '', 0, '00:00:00', NULL);

#
# Dumping data for table `#__categories`
#

INSERT INTO `#__categories` VALUES (1, 0, 'Latest', 'Latest News', 'taking_notes.jpg', '1', 'left', 'The latest news from the Joomla! Team', 1, 0, '0000-00-00 00:00:00', '', 0, 0, 1, '');
INSERT INTO `#__categories` VALUES (2, 0, 'Joomla!', 'Joomla!', 'clock.jpg', 'com_weblinks', 'left', 'A selection of links that are all related to the Joomla! Project.', 1, 0, '0000-00-00 00:00:00', NULL, 0, 0, 0, '');
INSERT INTO `#__categories` VALUES (3, 0, 'Newsflash', 'Newsflash', '', '2', 'left', '', 1, 0, '0000-00-00 00:00:00', '', 0, 0, 0, '');
INSERT INTO `#__categories` VALUES (4, 0, 'Joomla!', 'Joomla!', '', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 2, 0, 0, '');
INSERT INTO `#__categories` VALUES (5, 0, 'Business: general', 'Business: general', '', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, '');
INSERT INTO `#__categories` VALUES (7, 0, 'Examples', 'Example FAQs', 'key.jpg', '3', 'left', 'Here you will find an example set of FAQs.', 1, 0, '0000-00-00 00:00:00', NULL, 0, 0, 2, '');
INSERT INTO `#__categories` VALUES (9, 0, 'Finance', 'Finance', '', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 5, 0, 0, '');
INSERT INTO `#__categories` VALUES (10, 0, 'Linux', 'Linux', '', 'com_newsfeeds', 'left', '<br />\r\n', 1, 0, '0000-00-00 00:00:00', NULL, 6, 0, 0, '');
INSERT INTO `#__categories` VALUES (11, 0, 'Internet', 'Internet', '', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 7, 0, 0, '');
INSERT INTO `#__categories` VALUES (12, 0, 'Contacts', 'Contacts', '', 'com_contact_details', 'left', 'Contact Details for this website', 1, 0, '0000-00-00 00:00:00', NULL, 0, 0, 0, '');


#
# Dumping data for table `#__contact_details`
#

INSERT INTO `#__contact_details` VALUES (1, 'Name', 'Position', 'Street', 'Suburb', 'State', 'Country', 'Zip Code', 'Telephone', 'Fax', 'Miscellanous info', 'asterisk.png', 'top', 'email@email.com', 1, 1, 0, '0000-00-00 00:00:00', 1, '', 0, 12, 0);

#
# Dumping data for table `#__content`
#

INSERT INTO `#__content` VALUES (1, 'Welcome to Joomla!', 'Welcome', 'If you''ve read anything at all about Content Management Systems (CMS), you''ll probably know at least three things: CMS are the most exciting way to do business, CMS can be really, I mean <i>really</i>, complicated and lastly Portals are absolutely, outrageously, often <i>unaffordably</i> expensive. <br /><br />{mosimage}Joomla! is set to change all that ... Joomla! is different from the normal models for portal software. For a start, it''s not complicated. Joomla! has been developed for the masses. It''s licensed under the GNU/GPL license, easy to install and administer and reliable. Joomla! doesn''t even require the user or administrator of the system to know HTML to operate it once it''s up and running.', '<h4><font color="#ff6600">Joomla! features:</font></h4>\r\n<ul>\r\n<li>Completely database driven site engines </li>\r\n<li>News, products or services sections fully editable and manageable</li> \r\n<li>Topics sections can be added to by contributing authors </li>\r\n<li>Fully customisable layouts including left, center and right menu boxes </li>\r\n<li>Browser upload of images to your own library for use anywhere in the site </li>\r\n<li>Dynamic Forum/Poll/Voting booth for on-the-spot results </li>\r\n<li>Runs on Linux, FreeBSD, MacOSX server, Solaris and AIX \r\n</li></ul>\r\n<h4>Extensive Administration:</h4>\r\n<ul>\r\n<li>Change order of objects including news, FAQs, articles etc. </li>\r\n<li>Random Newsflash generator </li>\r\n<li>Remote author submission module for News, Articles, FAQs and Links </li>\r\n<li>Object hierarchy - as many sections, departments, divisions and pages as you want </li>\r\n<li>Image library - store all your PNGs, PDFs, DOCs, XLSs, GIFs and JPEGs online for easy use </li>\r\n<li>Automatic Path-Finder. Place a picture and let Joomla! fix the link </li>\r\n<li>News feed manager. Choose from over 360 news feeds from around the world </li>\r\n<li>Archive manager. Put your old articles into cold storage rather than throw them out </li>\r\n<li>Email-a-friend and Print-format for every story and article </li>\r\n<li>In-line Text editor similar to Word Pad </li>\r\n<li>User editable look and feel </li>\r\n<li>Polls/Surveys - Now put a different one on each page </li>\r\n<li>Custom Page Modules. Download custom page modules to spice up your site </li>\r\n<li>Template Manager. Download templates and implement them in seconds </li>\r\n<li>Layout preview. See how it looks before going live </li>\r\n<li>Banner manager. Make money out of your site</li></ul>', 1, 1, 0, 1, '2004-06-12 11:54:06', 62, 'Web Master', '2004-06-12 12:33:27', 62, 0, '0000-00-00 00:00:00', '2004-01-01 00:00:00', '0000-00-00 00:00:00', 'asterisk.png|left|Joomla! Logo|1|Example Caption|bottom|center|120', '', '', 1, 0, 1, '', '', 0, 0);
INSERT INTO `#__content` VALUES (2, 'Newsflash 1', '', 'Joomla! 1.0 - ''Experience the Freedom''!. It has never been easier to create\r\nyour own dynamic site. Manage all your content from the best CMS admin\r\ninterface.', '', 1, 2, 1, 3, '2004-08-09 08:30:34', 62, '', '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', '2004-08-09 00:00:00', '0000-00-00 00:00:00', '', '', '', 1, 0, 1, '', '', 0, 0);
INSERT INTO `#__content` VALUES (3, 'Newsflash 2', '', 'Yesterday all servers in the U.S. went out on strike in a bid to get more RAM and better CPUs. A spokes person said that the need for better RAM was due to some fool increasing the front-side bus speed. In future, busses will be told to slow down in residential motherboards.', '', 1, 2, 1, 3, '2004-08-09 08:30:34', 62, '', '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', '2004-08-09 00:00:00', '0000-00-00 00:00:00', '', '', '', 1, 0, 2, '', '', 0, 0);
INSERT INTO `#__content` VALUES (4, 'Newsflash 3', '', 'Aoccdrnig to a rscheearch at an Elingsh uinervtisy, it deosn''t mttaer in waht oredr the ltteers in a wrod are, the olny iprmoetnt tihng is taht frist and lsat ltteer is at the rghit pclae. The rset can be a toatl mses and you can sitll raed it wouthit porbelm. Tihs is bcuseae we do not raed ervey lteter by itslef but the wrod as a wlohe.', '', 1, 2, 1, 3, '2004-08-09 08:30:34', 62, '', '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', '2004-08-09 00:00:00', '0000-00-00 00:00:00', '', '', '', 1, 0, 3, '', '', 0, 1);
INSERT INTO `#__content` VALUES (5, 'Joomla! License Guidelines', '', '<p>This website is powered by <a href="http://www.joomla.org/">Joomla!</a>  The software and default templates on which it runs are Copyright 2005 <a href="http://www.opensourcematters.org/">Open Source Matters</a>.  All other content and data, including data entered into this website and templates added after installation, are copyrighted by their respective copyright owners.</p><p>If you want to distribute, copy or modify Joomla!, you are welcome to do so under the terms of the <a href="http://www.gnu.org/copyleft/gpl.html#SEC1">GNU General Public License</a>.  If you are unfamiliar with this license, you might want to read <a href="http://www.gnu.org/copyleft/gpl.html#SEC4">''How To Apply These Terms To Your Program''</a> and the <a href="http://www.gnu.org/licenses/gpl-faq.html">''GNU General Public License FAQ''</a>.</p>', '', 1, 0, 0, 0, '2004-08-19 20:11:07', 62, '', '2004-08-19 20:14:49', 62, 0, '0000-00-00 00:00:00', '2004-08-19 00:00:00', '0000-00-00 00:00:00', '', '', 'menu_image=\nitem_title=1\npageclass_sfx=\nback_button=\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=', 1, 0, 11, '', '', 0, 10);
INSERT INTO `#__content` VALUES (6, 'Example News Item 1', 'News1', '{mosimage}Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit\r\namet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At vero eos et accusam et justo duo dolores et ea rebum.', '<p>{mosimage}Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at\r\nvero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum\r\nzzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor\r\nsit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt\r\nut laoreet dolore magna aliquam erat volutpat.</p>\r\n\r\n<p>Ut wisi enim ad minim veniam, quis nostrud exerci tation\r\nullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis\r\nautem vel eum iriure dolor in hendrerit in vulputate velit esse molestie\r\nconsequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan\r\net iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis\r\ndolore te feugait nulla facilisi.</p>\r\n\r\n<p>Nam liber tempor cum soluta nobis eleifend option congue\r\nnihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum\r\ndolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod\r\ntincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim\r\nveniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut\r\naliquip ex ea commodo consequat.</p>\r\n\r\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd\r\ngubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum\r\ndolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores\r\nduo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet\r\nclita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero\r\nvoluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,\r\nconsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore\r\net dolore magna aliquyam erat.</p>\r\n\r\n<p>Consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut\r\nlabore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam\r\net justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata\r\nsanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur\r\nsadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore\r\nmagna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo\r\ndolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est\r\nLorem ipsum dolor sit amet.</p>', 1, 1, 0, 1, '2004-07-07 11:54:06', 62, '', '2004-07-07 18:05:05', 62, 0, '0000-00-00 00:00:00', '2004-07-07 00:00:00', '0000-00-00 00:00:00', 'food/coffee.jpg|left||0\r\nfood/bread.jpg|right||0', '', '', 1, 0, 2, '', '', 0, 4);
INSERT INTO `#__content` VALUES (7, 'Example News Item 2', 'News2', '<p>{mosimage}Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit\r\namet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem\r\nipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '', 1, 1, 0, 1, '2004-07-07 11:54:06', 62, '', '2004-07-07 18:11:30', 62, 0, '0000-00-00 00:00:00', '2004-07-07 00:00:00', '0000-00-00 00:00:00', 'food/bun.jpg|right||0', '', '', 1, 0, 3, '', '', 0, 2);
INSERT INTO `#__content` VALUES (8, 'Example News Item 3', 'News3', '<p>{mosimage}Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit\r\namet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem\r\nipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '', 1, 1, 0, 1, '2004-04-12 11:54:06', 62, '', '2004-07-07 18:08:23', 62, 0, '0000-00-00 00:00:00', '2004-07-07 00:00:00', '0000-00-00 00:00:00', 'fruit/pears.jpg|right||0', '', '', 1, 0, 4, '', '', 0, 1);
INSERT INTO `#__content` VALUES (9, 'Example News Item 4', 'News4', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '<p>{mosimage}Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at\r\nvero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum\r\nzzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor\r\nsit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt\r\nut laoreet dolore magna aliquam erat volutpat.</p>\r\n\r\n{mospagebreak}<p>{mosimage}Ut wisi enim ad minim veniam, quis nostrud exerci tation\r\nullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis\r\nautem vel eum iriure dolor in hendrerit in vulputate velit esse molestie\r\nconsequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan\r\net iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis\r\ndolore te feugait nulla facilisi.</p>\r\n\r\n<p>{mosimage}Nam liber tempor cum soluta nobis eleifend option congue\r\nnihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum\r\ndolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod\r\ntincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim\r\nveniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut\r\naliquip ex ea commodo consequat.</p>\r\n\r\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd\r\ngubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum\r\ndolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores\r\nduo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet\r\nclita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero\r\nvoluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,\r\nconsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore\r\net dolore magna aliquyam erat.</p>\r\n\r\n{mospagebreak}<p>Consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut\r\nlabore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam\r\net justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata\r\nsanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur\r\nsadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore\r\nmagna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo\r\ndolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est\r\nLorem ipsum dolor sit amet.</p>', 1, 1, 0, 1, '2004-07-07 11:54:06', 62, '', '2004-07-07 18:10:23', 62, 0, '0000-00-00 00:00:00', '2004-07-07 00:00:00', '0000-00-00 00:00:00', 'fruit/strawberry.jpg|left||0\r\nfruit/pears.jpg|right||0\r\nfruit/cherry.jpg|left||0', '', '', 1, 0, 5, '', '', 0, 6);
INSERT INTO `#__content` VALUES (10, 'Example FAQ Item 1', 'FAQ1', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '', 1, 3, 0, 7, '2004-05-12 11:54:06', 62, '', '2004-07-07 18:10:23', 62, 0, '0000-00-00 00:00:00', '2004-01-01 00:00:00', '0000-00-00 00:00:00', '', '', '', 1, 0, 5, '', '', 0, 8);
INSERT INTO `#__content` VALUES (11, 'Example FAQ Item 2', 'FAQ2', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '<p>{mosimage}Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at\r\nvero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum\r\nzzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor\r\nsit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt\r\nut laoreet dolore magna aliquam erat volutpat.</p>\r\n\r\n<p>{mosimage}Ut wisi enim ad minim veniam, quis nostrud exerci tation\r\nullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis\r\nautem vel eum iriure dolor in hendrerit in vulputate velit esse molestie\r\nconsequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan\r\net iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis\r\ndolore te feugait nulla facilisi.</p>\r\n\r\n<p>{mosimage}Nam liber tempor cum soluta nobis eleifend option congue\r\nnihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum\r\ndolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod\r\ntincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim\r\nveniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut\r\naliquip ex ea commodo consequat.</p>\r\n\r\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd\r\ngubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum\r\ndolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores\r\nduo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet\r\nclita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero\r\nvoluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,\r\nconsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore\r\net dolore magna aliquyam erat.</p>\r\n\r\n<p>Consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut\r\nlabore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam\r\net justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata\r\nsanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur\r\nsadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore\r\nmagna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo\r\ndolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est\r\nLorem ipsum dolor sit amet.</p>', 1, 3, 0, 7, '2004-05-12 11:54:06', 62, 'Web master', '2004-07-07 18:10:23', 62, 0, '0000-00-00 00:00:00', '2004-01-01 00:00:00', '0000-00-00 00:00:00', 'fruit/cherry.jpg|left||0\r\nfruit/peas.jpg|right||0\r\nfood/milk.jpg|left||0', '', '', 1, 0, 5, '', '', 0, 10);

#
# Dumping data for table `#__content_frontpage`
#

INSERT INTO `#__content_frontpage` VALUES (1, 1);
INSERT INTO `#__content_frontpage` VALUES (2, 2);
INSERT INTO `#__content_frontpage` VALUES (3, 3);
INSERT INTO `#__content_frontpage` VALUES (4, 4);
INSERT INTO `#__content_frontpage` VALUES (5, 5);

#
# Dumping data for table `#__menu`
#

INSERT INTO `#__menu` VALUES (2, 'mainmenu', 'News', 'index.php?option=com_content&task=section&id=1', 'content_section', 1, 0, 1, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (3, 'mainmenu', 'Contact Us', 'index.php?option=com_contact', 'components', 1, 0, 7, 0, 6, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (23, 'mainmenu', 'Links', 'index.php?option=com_weblinks', 'components', 1, 0, 4, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=web_links.jpg\npageclass_sfx=\nback_button=\npage_title=1\nheader=\nheadings=1\nhits=\nitem_description=1\nother_cat=1\ndescription=1\ndescription_text=\nimage=-1\nimage_align=right\nweblink_icons=');
INSERT INTO `#__menu` VALUES (5, 'mainmenu', 'Search', 'index.php?option=com_search', 'components', 1, 0, 16, 0, 7, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (6, 'mainmenu', 'Joomla! License', 'index.php?option=com_content&task=view&id=5', 'content_typed', 1, 0, 11, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '');
INSERT INTO `#__menu` VALUES (7, 'mainmenu', 'News Feeds', 'index.php?option=com_newsfeeds', 'components', 1, 0, 12, 0, 8, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\npage_title=1\nheader=');
INSERT INTO `#__menu` VALUES (8, 'mainmenu', 'Wrapper', 'index.php?option=com_wrapper', 'wrapper', 1, 0, 0, 0, 10, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\npage_title=1\nheader=\nscrolling=auto\nwidth=100%\nheight=600\nheight_auto=1\nurl=www.joomla.org');
INSERT INTO `#__menu` VALUES (9, 'mainmenu', 'Blog', 'index.php?option=com_content&task=blogsection&id=0', 'content_blog_section', 1, 0, 0, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\nheader=A blog of all section with no images\npage_title=1\nleading=1\nintro=3\ncolumns=2\nlink=4\norderby_pri=\norderby_sec=\npagination=2\npagination_results=1\nimage=0\ndescription=0\ndescription_image=0\ncategory=0\ncategory_link=0\nitem_title=1\nlink_titles=\nreadmore=\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nsectionid=');
INSERT INTO `#__menu` VALUES (10, 'othermenu', 'Joomla! Home', 'http://www.joomla.org', 'url', 1, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (11, 'othermenu', 'Joomla! Forums', 'http://forum.joomla.org', 'url', 1, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (12, 'othermenu', 'OSM Home', 'http://www.opensourcematters.org', 'url', 1, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (24, 'othermenu', 'Administrator', 'administrator/', 'url', 1, 0, 0, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1');
INSERT INTO `#__menu` VALUES (21, 'usermenu', 'Your Details', 'index.php?option=com_user&task=UserDetails', 'url', 1, 0, 0, 0, 1, 0, '2000-00-00 00:00:00', 0, 0, 1, 3, '');
INSERT INTO `#__menu` VALUES (13, 'usermenu', 'Submit News', 'index.php?option=com_content&task=new&sectionid=1&Itemid=0', 'url', 1, 0, 0, 0, 2, 0, '2000-00-00 00:00:00', 0, 0, 1, 2, '');
INSERT INTO `#__menu` VALUES (14, 'usermenu', 'Submit WebLink', 'index.php?option=com_weblinks&task=new', 'url', 1, 0, 0, 0, 4, 0, '2000-00-00 00:00:00', 0, 0, 1, 2, '');
INSERT INTO `#__menu` VALUES (15, 'usermenu', 'Check-In My Items', 'index.php?option=com_user&task=CheckIn', 'url', 1, 0, 0, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 1, 2, '');
INSERT INTO `#__menu` VALUES (16, 'usermenu', 'Logout', 'index.php?option=com_login', 'components', 1, 0, 15, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 1, 3, '');
INSERT INTO `#__menu` VALUES (17, 'topmenu', 'Home', 'index.php', 'url', 1, 0, 0, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (18, 'topmenu', 'Contact Us', 'index.php?option=com_contact&Itemid=3', 'url', 1, 0, 0, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (19, 'topmenu', 'News', 'index.php?option=com_content&task=section&id=1&Itemid=2', 'url', 1, 0, 0, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');
INSERT INTO `#__menu` VALUES (20, 'topmenu', 'Links', 'index.php?option=com_weblinks&Itemid=22', 'url', 1, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1');
INSERT INTO `#__menu` VALUES (25, 'mainmenu', 'FAQs', 'index.php?option=com_content&task=category&sectionid=3&id=7', 'content_category', 1, 0, 7, 0, 9, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\npage_title=1\npageclass_sfx=\nback_button=\norderby=\ndate_format=\ndate=\nauthor=\ntitle=1\nhits=\nheadings=1\nnavigation=1\norder_select=1\ndisplay=1\ndisplay_num=50\nfilter=1\nfilter_type=title\nother_cat=1\nempty_cat=0\ncat_items=1\ncat_description=1');

#
# Dumping data for table `#__newsfeeds`
#

INSERT INTO `#__newsfeeds` VALUES (4, 1, 'Joomla! - Official News', 'http://www.joomla.org/index.php?option=com_rss_xtd&feed=RSS2.0&type=com_frontpage&Itemid=1', '', 1, 5, 3600, 0, '0000-00-00 00:00:00', 8);
INSERT INTO `#__newsfeeds` VALUES (4, 2, 'Joomla! - Community News', 'http://www.joomla.org/index.php?option=com_rss_xtd&feed=RSS2.0&type=com_content&task=blogcategory&id=0&Itemid=33', '', 1, 5, 3600, 0, '0000-00-00 00:00:00', 9);
INSERT INTO `#__newsfeeds` VALUES (4, 3, 'OpenSourceMatters', 'http://www.opensourcematters.org/index2.php?option=com_rss&feed=RSS2.0&no_html=1', '', 1, 5, 3600, 0, '0000-00-00 00:00:00', 10);
INSERT INTO `#__newsfeeds` VALUES (10, 4, 'Linux Today', 'http://linuxtoday.com/backend/my-netscape.rdf', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 1);
INSERT INTO `#__newsfeeds` VALUES (5, 5, 'Business News', 'http://headlines.internet.com/internetnews/bus-news/news.rss', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 2);
INSERT INTO `#__newsfeeds` VALUES (11, 6, 'Web Developer News', 'http://headlines.internet.com/internetnews/wd-news/news.rss', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 3);
INSERT INTO `#__newsfeeds` VALUES (10, 7, 'Linux Central:New Products', 'http://linuxcentral.com/backend/lcnew.rdf', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 4);
INSERT INTO `#__newsfeeds` VALUES (10, 8, 'Linux Central:Best Selling', 'http://linuxcentral.com/backend/lcbestns.rdf', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 5);
INSERT INTO `#__newsfeeds` VALUES (10, 9, 'Linux Central:Daily Specials', 'http://linuxcentral.com/backend/lcspecialns.rdf', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 6);
INSERT INTO `#__newsfeeds` VALUES (9, 10, 'Internet:Finance News', 'http://headlines.internet.com/internetnews/fina-news/news.rss', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 7);

#
# Dumping data for table `#__poll_data`
#

INSERT INTO `#__poll_data` VALUES (1, 14, 'Absolutely simple', 1);
INSERT INTO `#__poll_data` VALUES (2, 14, 'Reasonably easy', 0);
INSERT INTO `#__poll_data` VALUES (3, 14, 'Not straight-forward but I worked it out', 0);
INSERT INTO `#__poll_data` VALUES (4, 14, 'I had to install extra server stuff', 0);
INSERT INTO `#__poll_data` VALUES (5, 14, 'I had no idea and got my friend to do it', 0);
INSERT INTO `#__poll_data` VALUES (6, 14, 'My dog ran away with the README ...', 0);
INSERT INTO `#__poll_data` VALUES (7, 14, '', 0);
INSERT INTO `#__poll_data` VALUES (8, 14, '', 0);
INSERT INTO `#__poll_data` VALUES (9, 14, '', 0);
INSERT INTO `#__poll_data` VALUES (10, 14, '', 0);
INSERT INTO `#__poll_data` VALUES (11, 14, '', 0);
INSERT INTO `#__poll_data` VALUES (12, 14, '', 0);

#
# Dumping data for table `#__polls`
#

INSERT INTO `#__polls` VALUES (14, 'This Joomla! installation was ....', 0, 0, '2000-00-00 00:00:00', 1, 0, 86400);

#
# Dumping data for table `#__poll_menu`
#

INSERT INTO `#__poll_menu` VALUES (14, 1);

#
# Dumping data for table `#__sections`
#

INSERT INTO `#__sections` VALUES (1, 'News', 'The News', 'articles.jpg', 'content', 'right', 'Select a news topic from the list below, then select a news article to read.', 1, 0, '0000-00-00 00:00:00', 1, 0, 1, '');
INSERT INTO `#__sections` VALUES (2, 'Newsflashes', 'Newsflashes', '', 'content', 'left', '', 1, 0, '0000-00-00 00:00:00', 2, 0, 1, '');
INSERT INTO `#__sections` VALUES (3, 'FAQs', 'Frequently Asked Questions', 'pastarchives.jpg', 'content', 'left', 'From the list below choose one of our FAQs topics, then select an FAQ to read. If you have a question which is not in this section, please contact us.', 1, 0, '0000-00-00 00:00:00', 2, 0, 1, '');


#
# Dumping data for table `#__weblinks`
#

INSERT INTO `#__weblinks` VALUES (1, 2, 0, 'Joomla!', 'http://www.joomla.org', 'Home of Joomla!', '2005-02-14 15:19:02', 2, 1, 0, '0000-00-00 00:00:00', 1, 0, 1, 'target=0');
INSERT INTO `#__weblinks` VALUES (2, 2, 0, 'php.net', 'http://www.php.net', 'The language that Joomla! is developed in', '2004-07-07 11:33:24', 0, 1, 0, '0000-00-00 00:00:00', 3, 0, 1, '');
INSERT INTO `#__weblinks` VALUES (3, 2, 0, 'MySQL', 'http://www.mysql.com', 'The database that Joomla! uses', '2004-07-07 10:18:31', 0, 1, 0, '0000-00-00 00:00:00', 5, 0, 1, '');
INSERT INTO `#__weblinks` VALUES (4, 2, 0, 'OpenSourceMatters', 'http://www.opensourcematters.org', 'Home of OSM', '2005-02-14 15:19:02', 2, 1, 0, '0000-00-00 00:00:00', 1, 0, 1, 'target=0');
INSERT INTO `#__weblinks` VALUES (5, 2, 0, 'Joomla! - Forums', 'http://forum.joomla.org', 'Joomla! Forums', '2005-02-14 15:19:02', 2, 1, 0, '0000-00-00 00:00:00', 1, 0, 1, 'target=0');
