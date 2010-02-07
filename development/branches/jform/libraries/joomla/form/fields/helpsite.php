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
JLoader::register('JFormFieldList', dirname(__FILE__).'/list.php');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldHelpsite extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	public $type = 'Helpsite';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions()
	{
		jimport('joomla.language.help');
		$options	= array_merge(
						parent::getOptions(),
						JHelp::createSiteList(JPATH_ADMINISTRATOR.DS.'help'.DS.'helpsites-15.xml', $this->value)
					);

		return $options;
	}
}