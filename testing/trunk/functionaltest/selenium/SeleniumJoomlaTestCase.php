<?php

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumJoomlaTestCase extends PHPUnit_Extensions_SeleniumTestCase
{

	function setUp()
	{
		$cfg = new SeleniumConfig();
		$this->setBrowser($cfg->browser);
		$this->setBrowserUrl($cfg->host.$cfg->path);
		echo 'Starting '.get_class($this).".\n";
	}
		
	function doAdminLogin()
	{
		echo "Logging in to admin.\n";
		$cfg = new SeleniumConfig();
		$this->open($cfg->path . "administrator");
		$this->waitForPageToLoad("30000");
		$this->type("modlgn_username", $cfg->username);
		$this->type("modlgn_passwd", $cfg->password);
		$this->click("link=Login");
		$this->waitForPageToLoad("30000");
	}

	function gotoAdmin()
	{
		echo "Browsing to admin.\n";
		$cfg = new SeleniumConfig();
		$this->open($cfg->path . "administrator");
	}

	function gotoSite()
	{
		echo "Browsing to site.\n";
		$cfg = new SeleniumConfig();
		$this->open($cfg->path);
	}

}
