<?php
/**
 * @version		$Id: group.php 16463 2010-04-26 01:39:58Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
JLoader::register('JFormFieldList', dirname(__FILE__).'/list.php');

/**
 * Form Field Place class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.6
 */
class JFormFieldExtensionFolder extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'ExtensionFolder';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$plugins	= ((string) $this->element['plugins']) ? (((string) $this->element['plugins'])=='yes') : true;
		$libraries	= ((string) $this->element['libraries']) ? (((string) $this->element['libraries'])=='yes') : true;
		$options	= array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('DISTINCT `folder`');
		$query->from('#__extensions');
		$query->where('`folder` != ""');
		if (!$plugins) {
			$query->where('`type` !='. $db->quote('plugin'));
		}
		if (!$libraries) {
			$query->where('`type` !='. $db->quote('library'));
		}
		$query->order('`folder`');
		
		$db->setQuery((string)$query);
		$folders = $db->loadResultArray();

		foreach($folders as $folder) {
			$options[] = JHtml::_('select.option', $folder, $folder);
		}
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
