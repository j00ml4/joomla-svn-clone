
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
You're not required to have the /unittest/ folder located in your Joomla! installation path. If you want to test on the command line only (cron jobs), you may drop this folder where PHP will be able to find it.
As a developer you may wish to keep both things separated and have one VirtualHost to run and test Joomla! nice an clean, and another to run the unit tests.
All TestCases and the UnitTestController will load library files using either the
	JPATH_BASE
constant or rely on PHP's inlude_path which is taken care of the test setup scripts.

Simply change JPATH_BASE in your local TestConfiguration.php to point to the Joomla! installation you wish to test, i.e. your SVN working copy, and you're done.


TestCase structure [WIP]
==================

The following examples assume we have a framework library file called
	/libraries/joomla/stuff/whatever.php
that contains
	class JWhatever

Folder and file layout in the unit test folder /libraries/joomla/:
[1]  /stuff/JWhateverTest.php
[2]  /stuff/TODO.txt
[3]  /stuff/_files/jwhatever_helper.php
[4]  /stuff/_files/fixture1.ext
[5]  /stuff/_files/README.txt

Except for [1], the actual test case, the other entries are optional.

[1] The TestCase of: class JWhatever
               from: /libraries/joomla/stuff/whatever.php
    implemented as : class TestOfJLoader extends UnitTestCase

[2] Optional notes with "things to do" about ANY of the TestCases in this folder.
	Each entry should have a number so others can refer to it as "see: todo 23" in the file itself or "todo 23 in /stuff/" in the forums or Google group.
	Comments about test failures should refer to this file rather then become an elaborate explanation in the PHP sources.

[3] Optional helper class for JWhateverTest.
	The name of this class name MUST follow this convention:
	     JoomlaClass + TestHelper
	e.g. JWhateverTestHelper
	The filename MUST be all lowercase using
		joomlaclass + _helper.php
	e.g. jwhatever_helper.php

[4] Optional file resource(s) required by the test case.
	Our TestOfJWhatever test case may use this in order to verify AND "stress" a feature of class JWhatever. The _files folder is also a good place to store Mock objects (see 'Mock Objects' section below)

[5] Optional instructions on how to deal with the fixtures in this folder. The user may need to copy them someplace for JWhatever

Mock Objects [WIP]
============
(@instance: anything you'd like to add?)

Before you create a new Mock Class you should take a look into the "JUNITTEST_LIBS" folder for an available Mock Class. "JUNITTEST_LIBS" is defined in TestConfiguration.php


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
