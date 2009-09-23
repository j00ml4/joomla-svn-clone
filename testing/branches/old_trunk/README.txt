
Joomla! v1.5 Unit-Test Platform
===============================

The Joomla Unit-testing platform is built using PHPUnit and provides an
easy solution for testing Class level code within the Joomla framework.

Only the most basic information is provided here. For an up to date reference, please visit http://docs.joomla.org/Unit_Testing.

How does it work?
=================
For it's basic usage, simply place the unpacked './unittest' folder into the root of
the Joomla! installation you wish to test. From your browser go to
http://your.joomla.tld/unittest/. You will be presented with a list of tests that
can be run. The list is hierarchy based therefore if you click on the 'libraries'
link all tests located in the 'libraries' folder will be run (Note: with a full set
of tests this could take some time). To run a subset of the available tests click on
the appropriate subfolder or file name. All tests may also run via the command-line,
see 'Command-line Use' below.


Test Configuration
==================

First of all you need to copy
	TestConfiguration-dist.php
to
	TestConfiguration.php

DO NOT modify the distribution file TestConfiguration-dist.php!
All your personal and local environment settings belong *exclusively* to the
copy you've created.

	NOTE for Testcase developers:
	`TestConfiguration-dist.php` is under version control whereas
	`TestConfiguration.php` is explicitely 'ignored' and will NOT
	be committed by your SVN client if you happen to make changes
	to the UnitTest Framework.


Locations of the Framework and UnitTest Platform
================================================
You're not required to have the './unittest' folder located in your Joomla!
installation path. You may drop this folder wherever PHP will be able to find it.

Every Testcase and the UnitTest Controller will load library files using either the
	JPATH_BASE
constant or rely on PHP's include_path, which is taken care of in the UnitTest bootstrap file.
Simply change JPATH_BASE in your local `TestConfiguration.php` to point to the Joomla! installation you wish to run all tests against, i.e. your SVN working copy, and you're basically done.

As a developer you may wish to keep Joomla! (CMS and Framework) and the UnitTest separated as distinct SVN working copies, and have one VirtualHost to run on Joomla!, nice an clean, and another to run all those evil tests.
