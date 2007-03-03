
Joomla! v1.5 Unit-Test Platform

The Joomla Unit-testing platform is built using SimpleTest for PHP and provides a easy solution for testing Class level code within the Joomla framework.

 * How does it work?
 * The URL and Output Options
 * Command Line Use
 * Adding a new TestCase
 * TestCase structure
 * Known issues


How does it work?
=================
Place the unpacked 'unittest' folder into the root of the Joomla installation you wish to test. From you browser go to http://your.joomla.dlt/unittest/. You will be presented with a list of tests that can be run. The list is hierarchy based therefore if you click on the 'libraries' link all tests starting with 'libraries' will be run (Note: with a full set of tests this could take some time). To run a subset of the available tests click on the appropriate header. To run a single test click on the appropriate file name.


The URL and Output Options
==========================
?path=
This identifies the test to be run. The value of path should be a relative path to the file or directory you wish to test. If you run PHP5 from the command line and the file or directory is not found in the test suit, skeletal test Classes will be created (see 'Adding a new TestCase' below).
Each file contains exactly one testcase for one Framework class. The name is derived from that Class followed by the word 'Test'

	Example 1: to test the class JVersion located in "libraries/joomla/version.php" you would use the URL;
	http://your.joomla.dlt/unittest/?path=libraries/joomla/JVersionTest.php

	Example 2: to test ALL classes located in "libraries/joomla/" you would use the URL;
	http://your.joomla.dlt/unittest/?path=libraries/joomla/AllTests.php

&output=
The Joomla Unit-testing platform supports several output formats; HTML, XML, Serialized PHP, JSON and TEXT. The default is HTML. To change the output type append &output=[html,xml,php,json,text] to the end of a tests url.

	Example: to output the above example in XML you would use this URL;
	http://your.joomla.dlt/unittest/?path=libraries/joomla/version.php&output=xml

You may change the default output (renderer) using the JUNITTEST_REPORTER setting in your local copy of TestConfiguration.php.


Command Line Use
================
All tests can be run from the command line using the following options:

-list
-path   [as-per-url]
-output [as-per-url]   defaults to 'TEXT'

The -list option prints out test cases one per line, this is the same list seen from the default HTML view.

	Example: to view all available tests;
	>php index.php -list

	Example: to test the file version.php in libraries/joomla/ you would use the URL;
	>php index.php -path libraries/joomla/version.php

	Example: to output the above example in XML you would use this URL;
	>php index.php -path libraries/joomla/version.php -output xml


Adding a new TestCase
=====================
You may either write a new testcase from scratch or use the "Skelton builder" to create it for you.
To utilize the Skeleton builder you must run PHP5 from the command line. The ability to create Skeltons with PHP4 OR via the browser inteface has been removed -- and it won't come back! The results are not as accurate as when using PHP5, and it's more likely a test writer can use the PHP5 CLI for that matter.

Note: Creation of skeletal Classes may not work where Classes extend from external files that are not located in the PHP include_path.


Location of the Framework and UnitTest Platform
===============================================
(TODO: verify)
You're not required to have the /unittest/ folder in your Joomla! installation path.
As a developer you may wish to keep both things separated and have one VirtualHost to run Joomla! and another run the UnitTest.
All TestCases and the UnitTestController will load library files using the
	JPATH_BASE
constant.
Simply change JPATH_BASE in your local TestConfiguration.php to point to the Joomla! installation you wish to test, i.e. your SVN working copy, and you're done.


TestCase structure
==================
(TODO)

The following examples assume we have a file called
	/stuff/whatever.php
that contains
	class JWhatever

Folder and file layout:
[1]  /stuff/JWhateverTest.php
[2]  /stuff/TODO.txt
[3]  /stuff/_files/JWhatever_helper.php
[4]  /stuff/_files/fixture1.ext
[5]  /stuff/_files/README.txt


[1] The TestCase of: class JWhatever
    located in     : /libraries/joomla/stuff/whatever.php
    implemented as : class TestOfJLoader extends UnitTestCase

[2] Things to do with ANY of the TestCases in this folder.
	Each entry should have a number so others can refer to it as "see: todo 23" in the file itself or "todo 23 in stuff" in the forums or Google group.

[3] option helper class for JWhateverTest.
	The name of this class name MUST follow this convention:
	     JoomlaClass + TestHelper
	e.g. JWhateverTestHelper

[4] optional file resource(s) required by the test case, i.e. our famous TestOfJWhatever testcase may use this in order to test a feature of JWhatever.
	This is also a good place to store Mock objects. However, before you create a new Mock Class you should take a look into the "JUNITTEST_LIBS" folder for an available Mock Class. "JUNITTEST_LIBS" is defined in TestConfiguration.php

[5] optional instructions on how to deal with the fixtures in this folder. The user may need to copy them someplace for JWhatever


Known issues
============
The Joomla! UnitTest Platform undergoes some refactoring. 
The following things do not [yet] work as intended:

 * "default.html" currently broken; you can now run each testcase directly 
	via CLI or browser, i.e. http://localhost/unittest/tests/libraries/JLoaderTest.php
 * testhelper classes: fail loading if run via "AllTests.php"
 * bundled 'custom' renderer: wrong counters if run via "AllTests.php"
 * all renderes: "headers already sent" error if run via "AllTests.php"
 * "PHP 5.2 often breaks SimpleTest"
	http://sourceforge.net/forum/forum.php?forum_id=646018

Resources
=========
Simpletest
 - /simpletest/HELP_MY_TESTS_DONT_WORK_ANYMORE

 - http://simpletest.org/
 - http://simpletest.org/api/

 - http://www.lastcraft.com/first_test_tutorial.php
 - http://www.lastcraft.com/mock_objects_tutorial.php

Joomla! Framework
 - http://api.joomla.org/li_Joomla-Framework.html
 - http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,references:joomla.framework/

Selenium
 - http://www.openqa.org/selenium-ide/
 - http://www.openqa.org/selenium-core/


-----------------------
Last review: 2007-03-03
Status     : WIP
