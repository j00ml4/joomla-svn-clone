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
class JFormFieldState extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'State';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		$published		= (string) $this->element['published'];
		$unpublished	= (string) $this->element['unpublished'];
		$archived		= (string) $this->element['archived'];
		$trash			= (string) $this->element['trash'];
		$all			= (string) $this->element['all'];
		$config = array();
		if ($published=='no') {
			$config['published'] = false;
		}
		if ($unpublished=='no') {
			$config['unpublished'] = false;
		}
		if ($archived=='no') {
			$config['archived'] = false;
		}
		if ($trash=='no') {
			$config['trash'] = false;
		}
		if ($all=='no') {
			$config['all'] = false;
		}
		// Merge any additional options in the XML definition.
		$options = array_merge(
			parent::getOptions(),
			JHtml::_('jgrid.publishedOptions', $config)
		);

		return $options;
	}
}
