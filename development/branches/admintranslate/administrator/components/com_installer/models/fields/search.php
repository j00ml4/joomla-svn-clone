<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: manage.php 14276 2010-01-18 14:20:28Z louis $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */
jimport('joomla.form.formfield');

/**
 * Form Field Search class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.6
 */
class JFormFieldSearch extends JFormField
{

	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Search';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function _getInput() 
	{
		$html = '';
		$html.= '<input type="text" name="' . $this->inputName . '" id="' . $this->inputId . '" value="' . $this->value . '" title="' . JText::_('JSearch_Filter') . '" onchange="this.form.submit();" />';
		$html.= '<button type="submit" class="btn">' . JText::_('JSearch_Filter_Submit') . '</button>';
		$html.= '<button type="button" class="btn" onclick="document.id(\'' . $this->inputId . '\').value=\'\';this.form.submit();">' . JText::_('JSearch_Filter_Clear') . '</button>';
		return $html;
	}
}
