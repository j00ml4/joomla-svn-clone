<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */
jimport('joomla.form.formfield');

/**
 * Form Field Search class.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
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
	protected function getInput()
	{
		$submit		= ($this->element['submit'] && in_array((string) $this->element['submit'], array('yes','true'))) ? ('<button type="submit" class="btn">' . JText::_('JSEARCH_FILTER_SUBMIT') . '</button>') : '';
		$onchange	= $this->element['onchange'] ? (string) $this->element['onchange'] : '';
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$id			=  str_replace('-', '_', $this->id);

		$html = '<input type="text" name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value . '" title="' . JText::_('JSEARCH_FILTER') . '" onchange="' . $onchange. '"'.$class.$size.$maxLength. ' />'.$submit.'<button type="button" class="btn" onclick="old_'.$id.'=document.id(\'' . $this->id . '\').value;document.id(\'' . $this->id . '\').value=\'\';if (old_'.$id.'!=\'\') {'.$onchange.'}">' . JText::_('JSEARCH_FILTER_CLEAR') . '</button>';
		return $html;
	}
}
