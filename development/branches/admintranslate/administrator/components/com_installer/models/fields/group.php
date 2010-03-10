<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: manage.php 14276 2010-01-18 14:20:28Z louis $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */
jimport('joomla.form.formfield');

/**
 * Form Field Place class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.6
 */
class JFormFieldGroup extends JFormField
{

	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Group';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function _getInput() 
	{
		$attributes = '';
		if ($v = (string)$this->_element->attributes()->onchange) 
		{
			$attributes.= ' onchange="' . $this->_replacePrefix($v) . '"';
		}
		$options = array();
		foreach ($this->_element->children() as $option) {
			$options[] = JHtml::_('select.option', (string)$option->attributes()->value, JText::_(trim($option->data())));
		}
		$dbo = JFactory::getDbo();
		$query = new JDatabaseQuery;
		$query->select('DISTINCT `folder`');
		$query->from('#__extensions');
		$query->where('`folder` != ""');
		$query->order('`folder`');
		$dbo->setQuery((string)$query);
		$folders = $dbo->loadResultArray();

		foreach($folders as $folder) {
			$options[] = JHtml::_('select.option', $folder, $folder);
		}
		$return = JHtml::_('select.genericlist', $options, $this->inputName, $attributes, 'value', 'text', $this->value, $this->inputId);
		return $return;
	}
}

