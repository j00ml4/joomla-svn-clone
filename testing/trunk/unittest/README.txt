
Joomla! v1.5 Unit-Test Platform
===============================

The Joomla Unit-testing platform is built using SimpleTest for PHP and provides a easy solution for testing Class level code within the Joomla framework.

 * How does it work?
 * Locations of the Framework and UnitTest Platform
 * The URL and Output Options
 * Command-line Use

 * Testcase authors
   * Adding a new Testcase
   * Stub code
   * Custom Testcase parameters
   * Testcase user instructions
   * Retain independence
   * User configuration
   * Logging and messages
   * Assertion $message format
   * Testcase structure [WIP]
   * Test Helper Classes
   * The "Info Object"

 * Mock Object [WIP]
 * Known issues
 * Updates
 * Resources


How does it work?
=================
For it's basic usage, simply place the unpacked './unittest' folder into the root of the Joomla! installation you wish to test. 
From your browser go to http://your.joomla.dlt/unittest/. You will be presented with a list of tests that can be run. The list is hierarchy based therefore if you click on the 'libraries' link all tests located in the 'libraries' folder will be run (Note: with a full set of tests this could take some time). 
To run a subset of the available tests click on the appropriate subfolder or file name.
All tests may also run via the command-line, see 'Command-line Use' below.


Test Configuration
==================
The Joomla! Framework provides access to a variety of resources such as database tables, FTP files, LDAP directories, or RSS feeds. As a procaution, several Testcases have been disabled via the default configuration file if they require some additional configuration, user credentials, or data in order to perform as intended. If your environment is technically capable to run these Tests, i.e. access to a FTP server, you need to enable them explicitly.

First of all you need to copy(!) 
	`TestConfiguration-dist.php` 
as 
	`TestConfiguration.php`

DO NOT modify the distribution file TestConfiguration-dist.php! 
All your personal and local environment settings belong *exclusively* to the copy you've created.

	NOTE for Testcase developers:
	`TestConfiguration-dist.php` is under version control whereas 
	`TestConfiguration.php` is explicitely 'ignored' and will NOT
	be committed by your SVN client if you happen to make changes
	to the UnitTest Framework.


Locations of the Framework and UnitTest Platform
================================================
You're not required to have the './unittest' folder located in your Joomla! installation path. You may drop this folder wherever PHP will be able to find it.

Every Testcase and the UnitTest Controller will load library files using either the
	JPATH_BASE
constant or rely on PHP's include_path, which is taken care of in the UnitTest bootstrap file.
Simply change JPATH_BASE in your local `TestConfiguration.php` to point to the Joomla! installation you wish to run all tests against, i.e. your SVN working copy, and you're basically done.

If a Testcase requires you to copy or move any supplemental files into the "DocumentRoot" you should find those files (and data) in the "_files" subdirectory where the Testcase resides.

As a developer you may wish to keep Joomla! (CMS and Framework) and the UnitTest separated as distinct SVN working copies, and have one VirtualHost to run on Joomla!, nice an clean, and another to run all those evil tests.


The URL and Output Options
==========================
If you run a UnitTest you may control the output format.

?path=
This identifies the test to be run.

Single Tests: The value of `path` should be a relative path of the file you wish to test. The name is derived from that Class followed by the word 'Test'. Each file contains the Testcase for one Framework class only. 

	Example: to test the class "JVersion" located in "libraries/joomla/version.php" use the URL:
	http://your.joomla.dlt/unittest/?path=libraries/joomla/JVersionTest.php

&output=
The Joomla Unit-testing platform supports several output formats; HTML, XML, Serialized PHP, JSON and TEXT. The default is HTML when tests run through the browser, and TEXT when run from the command-line (CLI). 
To change the output type append &output=[html,xml,php,json,text] to the end of a test's url (keywords are case insensitive).

	Example: to output the above example in XML you would use this URL;
	http://your.joomla.dlt/unittest/?path=libraries/joomla/JVersionTest.php&output=xml

You may change the default output (renderer) for web-based testing using the `JUNITTEST_REPORTER` setting in your local `TestConfiguration.php`. This is useful if you want XML as your default output format.


Group Tests
-----------
Testcases can be bundled into groups, aka TestSuites, available via an "AllTests.php" file located in each subdirectory of the UnitTest framework.

	Example: to test ALL classes located in "libraries/joomla/application/" use the URL:
	http://your.joomla.dlt/unittest/?path=libraries/joomla/application/AllTests.php

You should take 'ALL' literally! The example will also run any test located in the subfolders `application/components/` and `application/modules/`.

In order to run every test of the Framework use:
	http://your.joomla.dlt/unittest/?path=AllTests.php


Command-Line Use
================
Although it's been around since PHP 4.2.0, PHP command-line interface (CLI) has suffered many name changes and relocations - look at http://www.php.net/manual/en/features.commandline.php for more information, and to check which of its many avatars you have. Once you've figured out where it is and what it's called, invoke it and run the script `index.php` as below.

The command-line interface allows you to both run existing Testcases and groups as well as to create a kickstarter Skeletal file for new Testcases. The latter "feature" is only of interest if you're a developer writing a new Testcase. As a User you won't usually need this.

All tests can be run from the command-line using the following options:

-list
-path   path of a testcase, group test, or library file
-output [text,html,xml,php,json], defaults to 'text', written to `stdout`

The -list option prints out test cases one per line, this is the same list seen from the default HTML view. 
If no arguments are provided, "list mode" is automatically enabled.

	Example: to view all available tests:
	>php index.php -list
	or simply:
	>php index.php

	Example: to view all tests in libraries/joomla/application/:
	>php index.php -list -path libraries/joomla/application/


In CLI mode -path is expected to locate a *Test.php file in the JUNITTEST_BASE directory. If you ommit the filename, the Group Test located in that directory (package) will be run.

	Example 1: to run the Testcase for "JVersion" in "libraries/joomla/version.php" you would use:
	>php index.php -path libraries/joomla/JVersionTest.php

	Example 2: to run the Group Test for the "application package" use:
	>php index.php -path libraries/joomla/application/AllTests.php
 OR
	>php index.php -path libraries/joomla/application/

If you're using PHP5 from the command-line, and the UnitTest controller is unable to locate the test case, -path is assumed to point to a file in the Joomla! Framework directory (JPATH_BASE) in order to create a new Skeletal Testcase for any class therein. 
With PHP4 this feature is not available and you'll receive an error message that the given "Testcase" does not exist.


Enabling a Testcase
-------------------
Each Testcase is associated with a configuration setting (constant) that tells the UnitTest controller whether this test should run or not. This value is likely to be set to FALSE for any Test that requires access to an external resource or a server, read: such tests won't run by default.

You'll be notified in the list views and test results whether a test was present but disabled, thus you don't need to dig into the ./tests folder to locate them. It's up to you to activate the test if your environment permits.

To enable a Testcase find the comment section in the config file that contains the directory path of the Framework source file, i.e.
  /libraries/joomla/application
or search for the uppercase classname the Testcase applies to
  JROUTE

As of JROUTE, you should see:
	define('JUT_APPLICATION_JROUTER', false);
	define('JUT_APPLICATION_JROUTE', false);
To enable "your Testcase", simply change its value to: true.


Troubleshooting
---------------


Testcase authors (aka developers)
=================================
You may either write a new Testcase from scratch or use the "Skeleton builder" to have it create one for you.
To utilize the Skeleton builder you must however run PHP5 from the command-line. The ability to create class skeltons with PHP4 OR via the browser interface has been removed -- and it won't come back! The skeleton files are more accurate when using PHP5, and it's more likely the author of a UnitTest case can use the PHP5 CLI for that matter.

Note: Creation of skeletal Classes may not work where Classes extend from external files that are not located in the PHP include_path.


The nature of a test
--------------------
	"A test that does not operate in isoation is not a unit test.
	 It is safe to assume that a test that connects to the network
	 or a database or a real file is not a unit test.
	 Use mock objects or stubs to test in isolation."
		- Gunja Doshi

You may now think of something like: "but Joomla! uses a database and connects to a network and it's reading files." 
Yes, Joomla! the CMS, but not the enire Framework.
There are in fact only very few classes in the Joomla! Framework that actually do such nasty things, and these are the only ones that form a partial exception to the statement above.

Example 1: 
A database' connect() method must be able to connect to a physical database server to handle it's return values, connection errores etc. Some internal "SQL builder" or data formatter methods must not. They deals with result sets, strings, or any other arbitrary data, usually provided as arrays or objects. There's no need to have a database server anywhere near, up and running to test these methods.

Example 2: 
A function that "believes" it writes some data to a file using some other "file class" must only know about the interface of that very class. Whether the file is actually created or not is neither the problem not in the scope of the function using the "file writer". It can safely assume that, should the write operation fail, the writer class would have returned an error code. And it's that error code only that the tested class needs to deal with.

The second example denotes a typical case when a "Mock Object" should be used instead of the real file-writer (or anything alike.) Any possible errors that may occure when creating and writing a file or data stream must *not* be handled by the testcase of a class that simply interacts and uses an external file class. If it does, it is not a valid testcase.
You can read more about Mock Objects below.


Adding a new Testcase
---------------------
Let's assume you want to write a Testcase for the 'JRoute' class.
This class is located in 
	libraries/joomla/application/route.php

To trigger the Skeleton builder go to the command-line and execute:
	$ php index.php -path libraries/joomla/application/route.php

Because there's no 'route.php' file present in the tests folder, the controller will look for it in the Framework source directory given in JPATH_BASE.

In necessary the UnitTest Framework will update the folder structure in the ./tests directory  (JUNITTEST_BASE) to match the given filepath
	./tests/libraries/joomla/application
and create a Testcase skeletal file for EVERY CLASS found in the source file you provided. 
In case of `route.php` you'll find the following files in the target directory for each of the two classes declared (JRouter, JRoute):
	JRouterTest.php
	JRouteTest.php

If a file already exists, it will not be overwritten, hence you should not "play" with the Skeleton builder to polute the tests folder with empty and as such, useless testcases.
In case you don't have the time (mood, intend, knowledge ...) to implementing any newly created Testcase, remove the obsolete skeletal files before you commit to SVN for somebody else to take it over.


Stub code
---------
Each skeletal *Test.php file contains a preconfigured Testcase with a set of stub functions for each of the source class' methods and their parameters. Although you may now *try* to run the generated file, the test won't do anything but to report that the tests need to be implemented -- in fact, the testcase may not even run at all because it's initially disabled.
If you believe the content of these stub-functions is rather disturbing, you may tell the Skeleton builder not to add any such code, by changing the
	JUNITTEST_ADD_STUBS
config value in your local `TestConfiguration.php`.


Custom Testcase parameters
--------------------------
Depending on the complexity or dependencies of the class you're about to write a Testcase for, the user may need to provided additional settings. If ever possible try to make usage of the existing general parameters available at the very top of the TestConfiguration.php file. They're prefixed with 'JUNITTEST_' and include:
 - URI for a PHP4 / PHP5 enabled host
 - credentials to connect to (three) different database servers
 - constants for a variety of paths
Unlike the 'JUT_' family of constants, they provide global settings for all Testcases and the UnitTest controller itself.


Testcase user instructions
--------------------------
Users may need to copy supplementary files someplace in the DocumentRoot or some other data storage in order for your test to work properly. The UnitTest controller will look for any README* files in the `_files` subfolder and provide a facility for the user to access them easily, i.e. the webbrowser output will create links to these README files.

Since several tests may share a `_files` folder, you should add the classname prefix to the README's filename, e.g. README_JFTP, README_JVersion. You may or may not add a .txt extension.
The README files should be simple textfiles w/o any markup to allow viewing them on the command-line.


Retain independence
-------------------
This is serious! 
DON'T ever use any other class from the J! Framwork that's not in the scope of your Testcase unless it's totally unavoidable or their usage is implied by design, i.e. JFactory or JConfig.
If your Testcase MUST use another Joomla class (JSession, JRegistry), "ask" the UnitTestHelper FIRST whether its Testcase is enabled, (bool)TRUE. If the Controller returns (bool)FALSE cancel your test, and tell the UnitTestHelper in return to mark your test "disabled" as well. This is to allow subsequent Testcases to do the same should they rely on your tested class. 

For example, it doesn't make sense to rely on JDocument & Co. if there's no Testcase that prooves this class would perform as intended. The user may also have disabled a certain class from beeing tested on purpose, i.e. JFTP or JLDAP. You tests would fail anyway if the user can't or won't provide an environment for these "other classes" to work.

Simply put: should JFactory be disabled, almost any other Testcase will be prohibited to run as well. Chain reaction.


User configuration
------------------
You Testcase may require specific settings from the Joomla! configuration object to be available. It's very inconvenient for a UnitTest Framework user if s/he needs to set any kind of values in order for some Testcase to perform, but toggle them back and forth for any other tests.
DON'T try to write a Testcase that covers a gazillion different user settings per se: each user will provide an environment that differs from yours; this should be enough of fuzziness to stress the J! Framework classes for that matter.


Logging and messages
--------------------
Avoid to have your Testcase spit out any notification messages or status information to the user's screen via echo, print, or var_dump. If at all, use $this->_reporter->paintMessage(). 
A Test either works or fails. Period. 
A test's internals and statistics are of no particular interest for the user. If a Test fails, it fails, thus use the $message argument of the assert-function to provide a brief reason why it failed.
The output of a Testcase is "captured" by the UnitTest Controller and its renderes attempting to create different output formats such as clean HTML, XML, TEXT, PHP arrays or JSON data. If your code starts to yell inbetween you're likely to screw up and invalidate the output format and prevent subsequent analyzing or postprocessing of the results (think of cron jobs!)

If you can't resist to create elaborous status messages to tell the user about all the vivid things your Testcase performed, write them *INTO A DEDICATED LOG FILE* using the plain file I/O functions of PHP (fopen, fputs, etc.) or the UnitTestHelper::log() function. 
DON'T use any J! Framework classes to perform file loggin: these classes might be broken or disabled at the time the user runs your test. 
Just K.I.S.S!

A vanilla logger is available via:
	UntTestHelper::log($label, $testfile, $message = '')
which in it's current incarnation serves as a proxy to PHP's error_log() function.
Its destination and format etc. may be configurable in the future using some yet to be invented JUNITTEST_LOG_* settings.

Error reporting and skipping
----------------------------
Simpletest allows each UnitTestCase to signal an error, an exception, or to skip further processing of the test. Incidently, these methods are:
 - error($severity, $message, $file, $line)
 - exception(Exception $exception)
 - skip()
available as class methods of a UnitTestCase instance.

Like the startUp() and tearDown() methods, skip() is called everytime a new test function is about to run and if implemented, should call:
 - $this->skipIf($should_skip, $message = '%s')
to evaluate a condition causing the tests to be skipped OR
 - $this->skipUnless($shouldnt_skip, $message = false)
to evaluate a condition causing the tests to be run.

A typical usage would be to verify the existance of user credentials.

Assertion $message format
-------------------------
The $message argument avilable for any standard assert-function should be brief.

If you need to add a line break for whatever reason, use the PHP_EOL constant to do so. DON'T use \r, \n, <br /> or any combination thereof. Each UnitTest renderer class will transform PHP_EOL into the appropriate "line feed" of its output format. The HTML renderer for instance will translate this into the '<br />' element.

Any lenghty instructions or descriptions 'why-things-didn't-work' should go into your Testcase' README file. Refer to this file in your failure $message if you can't express the problem in a few words:
	Error: 4711 Bad response - see JWhatever_readme.txt

In `JWhatever_readme.txt` expain what error 4711 means and how the user may help to prevent it, i.e. by installing a database or ftp server ;)


Testcase structure [WIP]
------------------
The following examples assume a framework library file
	/libraries/joomla/foobar/whatever.php
that contains
	class JWhatever

Folder and file layout in the unit test folder /tests/libraries/joomla/:
[1]  /foobar/JWhateverTest.php
[2]  /foobar/TODO.txt
[3]  /foobar/_files/JWhatever_helper.php
[4]  /foobar/_files/fixture1.ext
[5]  /foobar/_files/JWhatever_readme.txt

Except for [1], the actual Testcase, other entries are optional.

[1] JWhateverTest.php -- The Testcase of class JWhatever
               from: /libraries/joomla/foobar/whatever.php
     implemented as: class TestOfJWhatever extends UnitTestCase

[2] TODO.txt -- Optional notes with "things to do" about ANY of the Testcases in the ./foobar package. 
	Each entry should have a unique number so others can refer to it as "see: #23" in the file itself or "Foobar todo 23" in other areas of communication (forum, tracker, mail, IRC ...)
	Comments and reasons about Tests failing should go into this file in prose and be simply referenced in the test file's sources via "@todo number [other package]".

[3] JWhatever_helper.php -- Optional helper class for JWhateverTest.
	The name of this class name MUST follow this convention:
	     JoomlaClassname + 'TestHelper'
	i.e. JWhateverTestHelper
	The filename derives from the Joomla class + the '_helper' prefix
		JoomlaClass + _helper.php
	i.e. JWhatever_helper.php

[4] Optional file resource(s) required by the test case.
	Our TestOfJWhatever test case may use this in order to verify AND "stress" a feature of class JWhatever. The _files folder is also a good place to store Mock objects (see 'Mock Objects' section below)

[5] JWhatever_*.txt -- In list mode, any "JWhatever_*.txt" file will be listed as a related "document" for this test case. User instructions about how to deal with the fixtures [4] in this folder and/or how to prepare the local Test environment to run tests for "JWhatever".


Test Helper Classes
-------------------
Each Testcase may provide a Helper class that contains utility functions. The Helper class will be loaded by the UnitTest Controller prior to the actual testcase, so it's a good place to add conditional define() statements that would not be possible otherwise.


The "Info Object"
-----------------
In order to allow as much automation as possible and to create a persistant structure of the TestSuites, Testcases, and their suplementary files, the UnitTest controller uses an "info object" which is passed along the internal routines of the JUT Framework. This is a stdClass object with various properties derived from the $path argument (or lack of it) when the UnitTestController is called.
It tells whether a given file is a TestSuite, Testcase or a framework source; if there are helper classes available for the Testcase; if a Skelton file needs to be created (CLI only); if there is any user documentation. Of course it also tells the Controller whether each of these files actually exist.

Because the Joomla! Framework is not truely consistant with its class names, filenames, and the pretended Package structure, the information provided in this object may be plain wrong for the given input file, and thus will cause the automation to fail on some tests.

There is currently no intend to work around this "lack of integrity" by writing a bunch of exception rules (which would be possible), 'cos frankly: a Testcase or TestSuite that fails because the Controller is unable to "identify" or locate the sources and origin in the Joomla! Framework, simply proves there's "a bug" in the naming schema that ought to be fixed rather than to "hack" around.


UnitTest API [WIP]
============
Hey, you don't really expect to find any extensive documentation here, do you?
Feel free to provide some: this is "open source" ;)

For a starter, look at 
	<JUNITTEST_ROOT>/_files/UnitTestHelper_examples.txt 
and of course 
	<JUNITTEST_ROOT>/_files/libs/helper.php 
for the various methods provided by the UnitTestHelper class.


Mock Objects [WIP]
============
(@instance: anything you'd like to add?)

Before you create a new Mock Class you should take a look into the 'JUNITTEST_LIBS' folder for an available Mock Class. 'JUNITTEST_LIBS' is defined in `TestConfiguration.php`


Known issues
============
The following things are missing or do not [yet] work as intended:

 * scoring is not yet implemented

 * "list mode" might be broken/imcomplete for some renderers; 
	you can however run each testcase directly via CLI or browser, 
	i.e. http://localhost/unittest/tests/libraries/JLoaderTest.php

 * testhelper classes fail to load if run via "AllTests.php"

 * example 'custom' reporter: wrong counters if run via "AllTests.php"

 * 'Joomla' reporter: "headers already sent" error if run via "AllTests.php"

 * "PHP 5.2 often breaks SimpleTest"
	http://sourceforge.net/forum/forum.php?forum_id=646018
	(has been reported to be 'Fixed in CVS')

 * only 1 AllTests.php per package allowed, this prevents creating Suites 
	for complex/large/long tests that'd better be done in individual parts
	"per feature", i.e. for JFTP, JLDAP, JDatabase (per mysql, msqli). 
	Need support for "JWhatever_AllTests.php"

 * YouTestCaseHelper::tearDownTestCase() not called
	might req. register_shutdown() or JTestSuite to call it after run()


Updates
=======
The Joomla! Unit-testing Framework is still under development. New Tests will be added and others revised should classes of the Joomla! Framework change, move, or even disappear. 
Since every change to the UnitTests may also imply a change in the configuration file, you should have an eye on `TestConfiguration-dist.php`, and update/synchronize your local copy of `TestConfiguration.php` whenever there's a change to the distribution file.

Substatial modifications will be denoted in the CHANGELOG.txt -- at least, that's the plan ;)


Resources
=========
Joomla! Framework API
 - http://api.joomla.org/li_Joomla-Framework.html
 - http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,references:joomla.framework/

Simpletest
 - /simpletest/HELP_MY_TESTS_DONT_WORK_ANYMORE
 - http://simpletest.org/
 - http://simpletest.org/api/
 - http://www.lastcraft.com/first_test_tutorial.php
 - http://www.lastcraft.com/mock_objects_tutorial.php

Selenium
 - http://www.openqa.org/selenium-ide/
 - http://www.openqa.org/selenium-core/

Test-driven Development
 - http://www.instrumentalservices.com/media/TDDRhythmReference.pdf
 - http://www.instrumentalservices.com/media/TestDrivenDevelopmentReferenceGuide.pdf


Status of this document
-----------------------
Last review: 2007-07-23
Status     : WIP

