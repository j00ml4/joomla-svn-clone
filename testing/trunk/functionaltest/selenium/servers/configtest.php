<?php
/*
 * This is an example of a second config class.
 * You can select the desired config class at runtime with the following command:
 * phpunit --bootstrap servers\configtest.php tests\control_panel_menu.php
 * In this way, you can create multiple configurations and run them separately 
 * using a batch file or shell script. 
 */

class SeleniumConfig
{
	var $host = 'http://localhost';
	var $path = '/j1513/';
	var $username = 'admin';
	var $password = 'password';
	var $browser = '*chrome';
}
