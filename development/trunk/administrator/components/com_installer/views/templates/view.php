<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Extension Manager Templates View
 *
 * @package		Joomla.Administrator
 * @subpackage	Installer
 * @since		1.5
 */

include_once(dirname(__FILE__).DS.'..'.DS.'default'.DS.'view.php');

class InstallerViewTemplates extends InstallerViewDefault
{
	protected $item;
	protected $items;
	protected $pagination;
	protected $lists;
	function display($tpl=null)
	{
		/*
		 * Set toolbar items for the page
		 */
		JToolBarHelper::deleteList('', 'remove', 'Uninstall');
		JToolBarHelper::help('screen.installer2');

		// Get data from the model
		$state		= &$this->get('State');
		$items		= &$this->get('Items');
		$pagination	= &$this->get('Pagination');

		$lists = new stdClass();
		$select[] = JHtml::_('select.option', '-1', JText::_('All'));
		$select[] = JHtml::_('select.option', '0', JText::_('Site Templates'));
		$select[] = JHtml::_('select.option', '1', JText::_('Admin Templates'));
		$lists->client = JHtml::_('select.genericlist', $select, 'client', 
			array(
				'list.attr' => 'class="inputbox" size="1" onchange="document.adminForm.submit();"',
				'list.select' => $state->get('filter.client')
			)
		);

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
