<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for JForm.
 *
 * @package		Joomla.UnitTest
 * @subpackage	Form
 */
class JFormFieldModuleLayoutsTest extends JoomlaTestCase
{
	/**
	 * Sets up dependancies for the test.
	 */
	protected function setUp()
	{
		jimport('joomla.form.formfield');
		require_once JPATH_BASE.'/libraries/joomla/form/fields/modulelayout.php';
	}

	/**
	 * Test the getInput method.
	 */
	public function testGetInput()
	{
		$this->markTestIncomplete();
	}
}