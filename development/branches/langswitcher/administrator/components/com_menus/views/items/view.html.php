<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * The HTML Menus Menu Items View.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @version		1.6
 */
class MenusViewItems extends JView
{
	protected $state;
	protected $items;
	protected $pagination;
	protected $f_levels;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$lang 		= &JFactory::getLanguage();
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$pagination	= $this->get('Pagination');
		$form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('form',		$form);

		$this->ordering = array();
		// Preprocess the list of items to find ordering divisions.
		foreach ($items as $i => &$item)
		{
			$this->ordering[$item->parent_id][] = $item->id;

			// item type text
			switch ($item->type) {
				case 'url':
					$value = JText::_('COM_MENUS_TYPE_EXTERNAL_URL');
					break;

				case 'alias':
					$value = JText::_('COM_MENUS_TYPE_ALIAS');
					break;

				case 'separator':
					$value = JText::_('COM_MENUS_TYPE_SEPARATOR');
					break;

				case 'component':
				default:
					// load language
						$lang->load($item->componentname.'.sys', JPATH_ADMINISTRATOR, null, false, false)
					||	$lang->load($item->componentname.'.sys', JPATH_ADMINISTRATOR.'/components/'.$item->componentname, null, false, false)
					||	$lang->load($item->componentname.'.sys', JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
					||	$lang->load($item->componentname.'.sys', JPATH_ADMINISTRATOR.'/components/'.$item->componentname, $lang->getDefault(), false, false);

					$value = JText::_($item->componentname);

					parse_str($item->link, $vars);
					if (isset($vars['view'])) {
						// Attempt to load the view xml file.
						$file = JPATH_SITE.'/components/'.$item->componentname.'/views/'.$vars['view'].'/metadata.xml';
						if (is_file($file) && $xml = simplexml_load_file($file)) {
							// Look for the first view node off of the root node.
							if ($view = $xml->xpath('view[1]')) {
								if (!empty($view[0]['title'])) {
									$vars['layout'] = isset($vars['layout']) ? $vars['layout'] : 'default';

									// Attempt to load the layout xml file.
									$file = JPATH_SITE.'/components/'.$item->componentname.'/views/'.$vars['view'].'/tmpl/'.$vars['layout'].'.xml';
									if (is_file($file) && $xml = simplexml_load_file($file)) {
										// Look for the first view node off of the root node.
										if ($layout = $xml->xpath('layout[1]')) {
											if (!empty($layout[0]['title'])) {
												$value .= ' » ' . JText::_(trim((string) $layout[0]['title']));
											}
										}
										if (!empty($layout[0]->message[0])) {
											$items[$i]->item_type_desc = JText::_(trim((string) $layout[0]->message[0]));
										}
									}
								}
							}
							unset($xml);
						}
						else {
							// Special case for absent views
							$value .= ' » ' . JText::_($item->componentname.'_'.$vars['view'].'_VIEW_DEFAULT_TITLE');
						}
					}
					break;
			}
			$items[$i]->item_type = $value;
		}

		// Levels filter.
		$options	= array();
		$options[]	= JHtml::_('select.option', '1');
		$options[]	= JHtml::_('select.option', '2');
		$options[]	= JHtml::_('select.option', '3');
		$options[]	= JHtml::_('select.option', '4');
		$this->assign('f_levels', $options);

		parent::display($tpl);
		$this->_setToolbar();
	}

	/**
	 * Build the default toolbar.
	 *
	 * @return	void
	 */
	protected function _setToolbar()
	{
		JToolBarHelper::title(JText::_('COM_MENUS_VIEW_ITEMS_TITLE'), 'menumgr.png');
		JToolBarHelper::custom('item.add', 'new.png', 'new_f2.png','JTOOLBAR_NEW', false);
		JToolBarHelper::custom('item.edit', 'edit.png', 'edit_f2.png','JTOOLBAR_EDIT', true);

		JToolBarHelper::divider();

		JToolBarHelper::custom('items.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('items.unpublish', 'unpublish.png', 'unpublish_f2.png','JTOOLBAR_UNPUBLISH', true);
		if ($this->state->get('filter.published') == -2) {
			JToolBarHelper::deleteList('', 'items.delete','JTOOLBAR_EMPTY_TRASH');
		}
		else {
			JToolBarHelper::trash('items.trash','JTOOLBAR_TRASH');
		}
		JToolBarHelper::divider();
		JToolBarHelper::custom('items.rebuild', 'refresh.png', 'refresh_f2.png', 'JToolbar_Rebuild', false);
		JToolBarHelper::divider();


		JToolBarHelper::help('screen.menus.items','JTOOLBAR_HELP');
	}
}
