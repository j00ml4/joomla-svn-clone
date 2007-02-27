Joomla! v1.5 Unit-Test Platform

The Joomla Unit-testing platform is built using SimpleTest for PHP and provides a easy solution for testing Class level code within the Joomla framework.

How does it work?
Place the unpacked 'unittest' folder into the root of the Joomla installation you wish to test. From you browser go to http://your.joomla.dlt/unittest/. You will be presented with a list of tests that can be run. The list is hierarchy based therefore if you click on the 'libraries' link all tests starting with 'libraries' will be run (Note: with a full set of tests this could take some time). To run a subset of the available tests click on the appropriate header. To run a single test click on the appropriate file name.

The URL and Output Options
?path=
This identifies the test to be run. The value of path should be a relative path to the file or directory you wish to test. If the file or directory is not found in the test suit; skeletal test Classes will be created (Note: Creation of skeletal Classes may not work where Classes extend from external files).

Example: to test the file version.php in libraries/joomla/ you would use the URL;
http://your.joomla.dlt/unittest/?path=libraries/joomla/version.php

&output=
The Joomla Unit-testing platform supports several output formats; HTML, XML, Serialized PHP, JSON and TEXT. The default is HTML. To change the output type append &output=[html,xml,php,json,text] to the end of a tests url.

Example: to output the above example in XML you would use this URL;
http://your.joomla.dlt/unittest/?path=libraries/joomla/version.php&output=xml

Command Line Use
All tests can be run from the command line using the following options:

-list
-path [as-per-url]
-output[as-per-url]

The -list option prints out test cases one per line, this is the same list seen from the default HTML view.

Example: to view all available tests;
>php index.php -list

Example: to test the file version.php in libraries/joomla/ you would use the URL;
>php index.php -path libraries/joomla/version.php

Example: to output the above example in XML you would use this URL;
>php index.php -path libraries/joomla/version.php -output xml