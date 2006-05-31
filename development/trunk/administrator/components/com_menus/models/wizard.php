<?php
/**
 * @version $Id: admin.menus.php 3504 2006-05-15 05:25:43Z eddieajau $
 * @package Joomla
 * @subpackage Menus
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights
 * reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

jimport( 'joomla.application.model' );

/**
 * @package Joomla
 * @subpackage Menus
 * @author Andrew Eddie
 */
class JMenuModelWizard extends JModel
{

	var $_wizard = null;
	var $_helper = null;

	/** @var object JRegistry object */
	var $_item = null;

	function init($type='')
	{
		// Create the JWizard object
		jimport('joomla.presentation.wizard');
		$app =& $this->getApplication();
		$type = $app->getUserStateFromRequest('menuwizard.type', 'type', $type);

		// Include and create the helper object
		if ($type) {
			require_once(COM_MENUS.'helpers'.DS.$type.'.php');
			$class = 'JMenuHelper'.ucfirst($type);
			$this->_helper =& new $class($this);
			$name = $this->_helper->getWizardName();
		} else {
			$name = 'menu';
		}

		// Instantiate wizard
		$this->_wizard =& new JWizard($app, $name);

		// Load the XML if helper is set
		if (isset($this->_helper)) {
			$this->_helper->loadXML();
		}
	}

	function &getForm()
	{
		return $this->_wizard->getForm();
	}

	function getMessage()
	{
		return $this->_wizard->getMessage();
	}

	function &getConfirmation()
	{
		return $this->_wizard->getConfirmation();
	}

	function getStep()
	{
		return $this->_wizard->getStep();
	}

	function getStepName()
	{
		return $this->_wizard->getStepName();
	}

	function getSteps()
	{
		return $this->_wizard->getSteps();
	}

	/**
	 * Get a list of the menu_types records
	 * @return array An array of records as objects
	 */
	function getMenuTypeList()
	{
		$db = $this->getDBO();
		$query = 'SELECT * FROM #__menu_types';
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

	/**
	 * Get a list of the menutypes
	 * @return array An array of menu type names
	 */
	function getMenuTypes()
	{
		$db = $this->getDBO();
		$query = 'SELECT menutype FROM #__menu_types';
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	/**
	 * Gets a list of components that can link to the menu
	 */
	function getComponentList()
	{
		$db = $this->getDBO();
		$query = "SELECT c.id, c.name, c.link, c.option"
		. "\n FROM #__components AS c"
		. "\n WHERE c.link <> '' AND parent = 0"
		. "\n ORDER BY c.name"
		;
		$db->setQuery( $query );
		$result = $db->loadObjectList( );
		return $result;
	}
}
?>