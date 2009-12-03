
<?php
/**
 * @version		$Id: control_panel0001Test.php 13064 2009-10-05 17:58:54Z dextercowley $
 * @package		Joomla.FunctionalTest
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * checks that all menu choices are shown in back end
 */

require_once 'SeleniumJoomlaTestCase.php';

/**
 * @group ControlPanel
 */
class ControlPanel0005 extends SeleniumJoomlaTestCase
{
	function testMenuTopLevelPresent()
	{
		$this->doAdminLogin();
		$this->gotoAdmin();
		$this->assertTrue($this->isElementPresent("link=Site"));
		$this->assertTrue($this->isElementPresent("link=Users"));
		$this->assertTrue($this->isElementPresent("link=Menus"));
		$this->assertTrue($this->isElementPresent("link=Content"));
		$this->assertTrue($this->isElementPresent("link=Components"));
		$this->assertTrue($this->isElementPresent("link=Extensions"));
		$this->assertTrue($this->isElementPresent("link=Help"));
	    $this->doAdminLogout();
	}
}

