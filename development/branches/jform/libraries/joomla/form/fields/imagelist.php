<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
JLoader::register('JFormFieldFileList', dirname(__FILE__).'/filelist.php');

/**
 * Supports an HTML select list of image
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldImageList extends JFormFieldFileList
{

	/**
	 * The field type.
	 *
	 * @var		string
	 */
	public $type = 'ImageList';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions()
	{
		$filter = '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$';
		$this->_element->addAttribute('filter', $filter);
		return parent::getOptions();
	}
}

