<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

class JControllerFormTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		jimport('joomla.application.component.controllerform');
		require_once 'controller_classes.php';
	}

	/**
	 * Returns an array of the structure of the object
	 *
	 * @return mixed	Boolean false on parsing error, array if successful.
	 */
	private function getStructure($object)
	{
		$m = array();
		preg_match('#(.*)__set_state\((.*)\)$#s', var_export($object, true), $m);
		if (empty($m[2])) {
			return false;
		}
		else
		{
			$structure = null;
			eval('$structure = '.$m[2].';');
			return $structure;
		}
	}

	public function testConstructor()
	{
		//
		// Test the auto-naming of the _option, _context, _view_item and _view_list
		//

		$object = new MincesControllerMince(
			array(
				// Neutralise a JPATH_COMPONENT not defined error.
				'base_path'	=> 'null'
			)
		);
		if ($structure = $this->getStructure($object))
		{
			// Check the _option variable was created properly.
			$this->assertEquals(
				'com_minces',
				$structure['_option']
			);

			// Check the _context variable was created properly.
			$this->assertEquals(
				'mince',
				$structure['_context']
			);

			// Check the _view_item variable was created properly.
			$this->assertEquals(
				'mince',
				$structure['_view_item']
			);

			// Check the _view_list variable was created properly.
			$this->assertEquals(
				'minces',
				$structure['_view_list']
			);
		}
		else {
			$this->fail('Could not parse '.get_class($object));
		}

		//
		// Test for correct pluralisation.
		//

		$object = new MiniesControllerMiny(
			array(
				// Neutralise a JPATH_COMPONENT not defined error.
				'base_path'	=> 'null'
			)
		);
		if ($structure = $this->getStructure($object))
		{
			// Check the _view_list variable was created properly.
			$this->assertEquals(
				'minies',
				$structure['_view_list']
			);
		}
		else {
			$this->fail('Could not parse '.get_class($object));
		}

		$object = new MintsControllerMint(
			array(
				// Neutralise a JPATH_COMPONENT not defined error.
				'base_path'	=> 'null'
			)
		);
		if ($structure = $this->getStructure($object))
		{
			// Check the _view_list variable was created properly.
			$this->assertEquals(
				'mints',
				$structure['_view_list']
			);
		}
		else {
			$this->fail('Could not parse '.get_class($object));
		}
	}

	public function testDisplay()
	{
		$object = new MintsControllerMint(
			array(
				// Neutralise a JPATH_COMPONENT not defined error.
				'base_path'	=> 'null'
			)
		);
		/*
		Need to mock JRoute!!!
		$object->display();
		if ($structure = $this->getStructure($object))
		{
			print_r($structure);
		}
		else {
			$this->fail('Could not parse '.get_class($object));
		}
		*/
	}
}
