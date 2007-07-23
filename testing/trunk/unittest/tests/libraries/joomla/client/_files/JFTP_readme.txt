
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

If your FTP server runs on a windows box, add ?ftp_sys=WIN
	ftp://username:password@servername/?ftp_sys=WIN

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


