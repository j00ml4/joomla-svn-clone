<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
?>

1. Changelog
------------
This is a non-exhaustive (but still near complete) changelog for
Joomla! 1.1, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and
code fixes.

Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

12-Nov-2005 Johan Janssens
 ^ Moved includes/Cache to libraries/cache
 - Deprecated mosCache, use JFactory::getCache instead
 + Added improved JCache class

11-Nov-2005 Levis Bisson
 ^ Fixed installation - added alert when empty password field
 ^ Wrapped installation static text for translation
 ^ Optimized the installation english.ini file
 # Fixed "GNU Lesser General Public License" link

11-Nov-2005 Johan Janssens
 + Added new JBrowser class
 - Deprecated mosGetOS and mosGetBrowser, use JBrowser instead
 + Added new Visitor Statistics system bot

10-Nov-2005 Johan Janssens
 ^ Installation alterations, backported Mambo 4.5.3 installation
 + Added new JApplication class
 - Deprecated mosMainFrame class, use JApplication instead
 + Introduced JPATH defines, replaced $mosConfig_admin_path by JPATH_ADMINISTRATOR

10-Nov-2005 Andy Miller
 # Fixed IE issues with variable tabs
 ^ Modified the tab code to support variable width tabs (needed for language support)
 ^ Cleaned up and modified some images

10-Nov-2005 Samuel Moffatt
 ^ Installer alterations
 ^ Fixed up a few capitalization issues

09-Nov-2005 Johan Janssens 
 # Fixed artf2018 : Admin Menu strings missing
 ^ Moved includes/gacl.class.php and gacl_api_class.php to libraries/phpgacl
 ^ Moved includes/vcard.class.php and feedcreator.class.php to libraries/bitfolge
 ^ Moved includes/class.pdf.php and class.ezpdf.php to libraries/cpdf
 ^ Moved includes/pdf.php to libraries/joomla
 ^ Moved includes/Archive to libraries/archive
 ^ Moved includes/phpInputFilter to libraries/phpinputfilter
 ^ Moved includes/PEAR to libraries/pear
 ^ Moved administrator/includes/pcl to libraries/pcl

08-Nov-2005 Arno Zijlstra
 # Fixed : Notices in sefurlbot 

08-Nov-2005 Levis Bisson
 + Added the mambots language files 
 ^ Modified some xml mambots files for translation

08-Nov-2005 Johan Janssens
 # Fixed artf2002 : Can't access Site Mambots
 # Fixed artf2003 : Fatal errors - typos in backend

08-Nov-2005 Alex Kempkens
 + Added variable admin path with config vars $mosConfig_admin_path & $mosConfig_admin_site
 ^ changed -hopefully all- administrator references of site or path type to the new variables
 ^ changed config var mbf_content to multilingual_support for future independence

07-Nov-2005 Arno Zijlstra
 # Fixed template css manager
 
07-Nov-2005 Johan Janssens
 # Fixed artf1648 : tinyMCE BR and P elements
 # Fixed artf1700 : TinyMCE doesn't support relative URL's for images

06-Nov-2005 Rey Gigataras
 + Added `Pathway` module, templates now no longer call function directly
 + Added param to `Content SearchBot` allowing you determine whether to search `Content Items`, `Static Content` and `Archived Content`

05-Nov-2005 Rey Gigataras
 ^ Separated newsfeed ability from custom/new module into its own module = `Newsfeed` [mod_rss.php] 
   Backward compatability retained for existing custom modules with newsfeed params

04-Nov-2005 Levis Bisson
 + Added the modules frontend and backend language files
 + Wrapped all frontend texts with the new $_LANG->_() 
 ^ Optimized the english backend language files

04-Nov-2005 Johan Janssens 
 # Fixed artf1949 : Typo in back-end com_config.ini
 # Fixed artf1866 : Alpha1: Content categories don't show

02-Nov-2005 Andrew Eddie
 ^ Reworked ACL ACO's to better align with future requirements

02-Nov-2005 Arno Zijlstra
 ^ Changed footer module
 # Fixed : version include path in joomla installer
 
02-Nov-2005 Johan Janssens
 + Added XML-RPC support
 # Fixed artf1918 : Edit News Feeds gives error
 # Fixed artf1841 : Problem with E-Mail / Print Icon Links

01-Nov-2005 Arno Zijlstra
 + Added footer module english language file

01-Nov-2005 Johan Janssens
 - Removed global $version, use $_VERSION->getLongVersion() instead.
 ^ Moved includes/version.php to libraries/joomla/version.php
 # Fixed artf1901 : english.com_templates.ini
 + Added artf1895 : Footer as as module

31-Oct-2005 Johan Janssens 
 # Fixed : artf1883 : DESCMENUGROUP twice in english.com_menus.ini
 # Fixed : artf1891 : When trying to register a new user get Fatal error.

31-Oct-2005 Rey Gigataras
 # Fixed artf1666 : Notice: on component installation
 # Fixed artf1573 : Manage Banners | Error in Field Name
 # Fixed artf1597 : Small bug in loadAssocList function in database.php
 # Fixed artf1832 : Logout problem
 # Fixed artf1769 : Undefined index: 2 in includes/joomla.php on line 2721
 # Fixed artf1749 : Email-to-friend is NOT actually from friend
 # Fixed artf1591 : page is expired at installation
 # Fixed artf1851 : 1.0.2 copy content has error
 # Fixed artf1569 : Display of mouseover in IE gives a problem with a dropdown-box
 # Fixed artf1869 : Poll produces MySQL-Error when accessed via Component Link
 # Fixed artf1694 : 1.0.3 undefined indexes filter_sectionid and catid on "Add New Content"
 # Fixed artf1834 : English Localisation
 # Fixed artf1771 : Wrong mosmsg
 # Fixed artf1792 : "Receive Submission Emails" label is misleading
 # Fixed artf1770 : Undefined index: HTTP_USER_AGENT 
 
30-Oct-2005 Rey Gigataras
 ^ Upgraded TinyMCE Compressor [1.02]
 ^ Upgraded TinyMCE [2.0 RC4]

30-Oct-2005 Johan Janssens
 # Fixed artf1878 : english.com_config.ini missing Berlin
 ^ Moved editor/editor.php to libraries/joomla/editor.php
 - Removed editor folder

30-Oct-2005 Levis Bisson
 + Added the new frontend language files (structure)

28-Oct-2005 Samuel Moffatt
 + Library Support Added
 + Added getUserList() and userExists($username) functions to mosUser
 ^ LDAP userbot modified (class moved to libraries)

28-Oct-2005 Johan Janssens
 ^ Changed artf1719 : Don't run initeditor from template

27-Oct-2005 Marko Schmuck
 # Fixed artf1805 : Time Offset problem

27-Oct-2005 Johan Janssens
 # Fixed artf1826 : Typo's in language files
 # Fixed artf1820 : Call to undefined function: mosmainbody()
 # Fixed artf1825 : Can't delete uploaded pic from media manager
 # Fixed artf1818 : Error at "Edit Your Details"
 ^ Moved backtemplate head handling into new mosShowHead_Admin();

27-Oct-2005 Robin Muilwijk
 # Fixed artf1824, fatal error in Private messaging, backend

-------------------- 1.1.0 Alpha Released [26-Oct-2005] ------------------------

26-Oct-2005 Samuel Moffatt
 # Fixed user login where only the first user bot would be checked.
 # Fixed bug where a new database object with the same username, password and host but different database name would kill Joomla!

26-Oct-2005 Levis Bisson
 # Fixed Artifact artf1713 : Hardcoded text in searchbot
 # Fixed selectlist finishing by an Hypen

25-Oct-2005 Johan Janssens
 # Fixed artf1724 : Back end language is not being selected
 # Fixed artf1784 : Back end language selected on each user not working

25-Oct-2005 Emir Sakic
 # Fixed a bug with live_site appended prefix in SEF
 # $mosConfig_mbf_content missing in mosConfig class
 + Added handle buttons to filter box in content admin managers

23-Oct-2005 Johan Janssens
 # Fixed artf1684 : Media manager broken
 # Fixed artf1742 : Can't login in front-end, wrong link
 ^ Artifact artf1413 : My Settings Page: editor selection option

23-Oct-2005 Arno Zijlstra
 + Added reset statistics functions

23-Oct-2005 Andrew Eddie
 ^ Changed globals.php to emulate off mode (fixes many potential security holes)

22-Oct-2005 Emir Sakic
 + Turned SEF in system bot and added the mambot
 - Removed SEF include

21-Oct-2005 Arno Zijlstra
 ^ Changed template css editor. Choose css file to edit.

20-Oct-2005 Levis Bisson
 Applied Feature Requests:
 ^ Artifact artf1206 : Don't show Database Password in admin area
 ^ Artifact artf1301 : Expand content title lengths
 ^ Artifact artf1282 : Easier sorting of static content in creating menu links
 ^ Artifact artf1162 : Remove hardcoding of <<, <, > and >> in pageNavigation.php

19-Oct-2005 Johan Janssens
 + Added full UTF-8 support

18-Oct-2005 Johan Janssens
 + Added RTL compilance changes (submitted by David Gal)

17-Oct-2005 Alex Kempkens
 + Added site url system bot for URL rewrite depending on protocol and domain name

15-Oct-2005 Johan Janssens
 + Added user bot triggers

14-Oct-2005 Levis Bisson
 + Added the choice for the admin language
 + Wrapped all backend static texts
 + Added the english admin language files

14-Oct-2005 Johan Janssens
 + Added userbot group
 + Added Joomla, LDAP and example userbots
 + Added onUserLogin and onUserLogout triggers
 + Added backend language chooser on login page

12-Oct-2005 Andy Miller
 + Added advanced SSL support plus new mosLink() method for 3pd's to use

-------------------- 1.0.3 Released [14-Oct-2005 08:00 UTC] ----------------------

----- Branched from Joomla 1.0.x on 2 october 2005 -----

2. Copyright and disclaimer
---------------------------
This application is opensource software released under the GPL.  Please
see source code and the LICENSE file
