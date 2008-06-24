
JFTP README
===========
Configuration directives in TestConfiguration.php

The availability of this test depends on:
 - JUT_CLIENT_JFTP        boolean

Required settings:
 - JUT_JFTP_CREDENTIALS   string

Environment settings:
 - JUT_JFTP_NATIVE        boolean


Information for Users
=====================
You need to set correct FTP credentials in your local TestConfiguration.php
using the JUT_JFTP_CREDENTIALS directive:
	ftp://username:password@servername/

Make sure to run the test in an empty subdirectory of your FTP server:
	ftp://username:password@servername/directory

If you know what your FTP server should report for the SYST command, add ?ftp_sys=[WIN|MAC|UNIX]
	ftp://username:password@servername/directory?ftp_sys=WIN

If your server does not listen on port 21, specify the port as follows:
	ftp://username:password@servername:port/directory

You may also override the JUT_JFTP_NATIVE option to either
	1 = native ftp enabled
	0 = native ftp disabled


Information for Developers
==========================
Static property:
 - (array) 'TestOfJFTP', 'errorhandler'
	to store error handler status
 - (array) 'TestOfJFTP', 'credentials'
	holds FTP credentials; host, port, user, pass


