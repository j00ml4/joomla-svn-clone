<?php

/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldPassword extends JFormField
{

	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Password';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function getInput()
	{
		$size = (string)$this->element->attributes()->size ? ' size="'.$this->element->attributes()->size.'"' : '';
		$class = (string)$this->element->attributes()->class ? ' class="'.$this->element->attributes()->class.'"' : '';
		$auto = (string)$this->element->attributes()->autocomplete == 'off' ? 'autocomplete="off"' : '';

		return '<input type="password" name="'.$this->name.'" id="'.$this->id.'" value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"'.$auto.$class.$size.'/>';
	}
}
