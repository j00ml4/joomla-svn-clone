<?php
/**
 * @version		$Id: view.php 13109 2009-10-08 18:15:33Z ian $
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

include_once dirname(__FILE__).DS.'..'.DS.'default'.DS.'view.php';

/**
 * Extension Manager Templates View
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.5
 */
class InstallerViewTemplates extends InstallerViewDefault
{
	function display($tpl=null)
	{
		/*
		 * Set toolbar items for the page
		 */
		JToolBarHelper::deleteList('', 'remove', 'Uninstall');
		JToolBarHelper::help('screen.installer');

		// Get data from the model
		$state		= &$this->get('State');
		$items		= &$this->get('Items');
		$pagination	= &$this->get('Pagination');

		$lists = new stdClass();
		$select[] = JHtml::_('select.option', '-1', JText::_('All'));
		$select[] = JHtml::_('select.option', '0', JText::_('Site Templates'));
		$select[] = JHtml::_('select.option', '1', JText::_('Admin Templates'));
		$lists->client = JHtml::_('select.genericlist',  $select, 'client', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $state->get('filter.client'));

		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('lists',		$lists);

		parent::display($tpl);
	}

	function loadItem($index=0)
	{
		$item = &$this->items[$index];
		$item->id		= $item->directory;
		$item->index	= $index;

		if ($item->active) {
			$item->cbd		= 'disabled';
			$item->style	= 'style="color:#999999;"';
		} else {
			$item->cbd		= null;
			$item->style	= null;
		}
		$item->author_information = @$item->authorEmail .'<br />'. @$item->authorUrl;

		$this->assignRef('item', $item);
	}
}
