INTRODUCTION

The manual is written in Docbook XML and requires a Unix-like
operating system with the standard GNU toolchain and xsltproc
or a similar XSLT processor to build the source XML into the
HTML that is shipped with the Joomla Framework distributions.

On Windows, you can compile the docbook using Cygwin.  See:
http://www.cygwin.com



INSTALLATION FOR WINDOWS USERS

Installation steps for Cygwin:
  1. Choose "Install from Internet", click [Next]
  2. Choose the directory where you want to install Cygwin. Leave the other
     options on their "RECOMMENDED" selection. Click [Next]
  3. Select a directory where you want downloaded files to be stored. Click
     [Next]
  4. Select your way of connecting to the Internet. Click [Next]
  5. Choose the most suitable mirror in the mirrorlist. Click [Next]
  6. Select the following packages to install:
     * Devel > automake1.9
     * Devel > libxslt
     * Devel > make
     All dependent packages will automatically be selected for you.
     Click [Next]
  7. Sit back and relax while Cygwin and the selected packages are being
     downloaded and installed. This may take a while.
  8. Check the option "Create icon on Desktop" and "Add icon to Start Menu" to
     your liking. Click [Finish].



BUILDING THE DOCUMENTATION (*NIX AND CYGWIN)

To build the documentation into HTML:
  1. Go to a shell prompt, or Windows users will run Cygwin (you can 
     double-click the icon on the Desktop or in the Start menu if you've 
     chosen any of these options at install-time)
  2. Navigate to the directory where the documentation files are stored 
     using the traditional Unix commands.
     For Cygwin users, drives are stored under "/cygdrive".  So if your 
     documentation  files are stored under "c:\joomla\documentation", 
     you'll need to run the command "cd c:/joomla/documentation/".
     You're under a Unix environment, so don't forget all paths are 
     case sensitive!
  3. To compile the html docs, go to the directory in which manual.xml 
     is located  and run:
     $ autoconf
     $ ./configure
     $ make html



TROUBLESHOOTING

If the `autoconf` and `configure` commands were successful, and you 
"just" get regular xml/xslt procesing errors from xsltproc, or have 
updated and modified the documentation structure:
  1. Run:  `$ make clean`  OR  `$ make cleanall`
  2. Fix any errors in your .xml files
  3. repeat the built process as explained above


WINDOWS:
If you're using Cygwin's xsltproc,  make sure you have a copy of the
DocBook 4.4 DTD and DocBook XSLT directories available in the manual 
folder:
 ./docbook/docbook-xml-4.4
 ./docbook/docbook-xsl
Start the Cygwin shell, cd to the docbook-xsl/ directory and run 
`install.sh` to update the XSL catalog.


GENERAL:
If you're encountering errors while trying the build instructions above...
  1. Remove all files from the html/ subdir except dbstyle.css

  2. Remove all files from the root dir except *.in, and README.

  3. You can optionally remove the "/autom4te.cache" directory and the
     "./docbook-xsl" directory if the latter already comes installed on
     your machine and xsltproc is able to find your copy ...

  4. Try to build again following the instructions given above. If it still
     throws errors, post a message to the Developer Documentation Forum
     http://forum.joomla.org/index.php/board,60.0.html
     and provide detailed information of your steps, your environment,
     program versions incl. the DocBook XML and XSLT versions.


DOCBOOK DTD + XSL locations
The `Makefile.in` template contains two URIs that point to the locations
of the DocBook 4.4 DTD and the XSLT (v1.72) stylesheets. 
  DOCBOOK_DTD=http://www.oasis-open.org/docbook/xml/4.4/
  DOCBOOK_XSL=http://docbook.sourceforge.net/release/xsl/current/

If your system is properly configured, you do not need to change these
URIs to point to a local path. xsltproc should use the LOCAL copies of 
each as configured in the 'catalog files'.

The file `/etc/xml/catalog` should contain these entries:
  <rewriteSystem 
       systemIdStartString="http://www.oasis-open.org/docbook/xml/4.4/" 
             rewritePrefix="/usr/share/xml/docbook/4.4/"/>
  <rewriteURI 
       uriStartString="http://docbook.sourceforge.net/release/xsl/current/" 
             rewritePrefix="/usr/share/docbook-xsl/"/>

In a future version of the documentation built files, these URIs may be
replaced with joomla.org URIs to ensure the integrity of the DTD and XSL
files used, and bypass the dependency to the public versions in case
they may change and thus break the built process.

The DocBook XSLT should be version 1.72 -- if your system comes with 1.69
or earlier you may want to update. You can find the version number in
/usr/share/docbook-xsl/VERSION 
   <fm:Version>1.72.0</fm:Version>

The 'docbook-xml-*' and 'docbook-xsl' subdirectories have been excluded
from SVN so you can safely unpack these archives in the docbook/ folder.


TODO
 -	extend the current XSLT stylesheet (html.xsl) to match the corporate
	design of other Joomla! websites.
 -	add built files to support the Win32 native binaries of xsltproc to
	avoid people having to install Cygwin.
 -	add sanity checks, xref tools


CREDITS
This file and several parts of the xsltproc built files are based on the
work and ideas of the Zend Framework documentation team.

2007-06-12 .CirTap
