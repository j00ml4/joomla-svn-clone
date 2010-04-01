<?php
/**
 * version $Id$
 * @package		Joomla
 * @subpackage	Newsfeeds
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Newsfeeds component
 *
 * @package		Joomla.Site
 * @subpackage	com_newsfeeds
 * @since		1.0
 */
class NewsfeedsViewCategory extends JView
{
	protected $state;
	protected $items;
	protected $category;
	protected $categories;
	protected $pagination;

	function display($tpl = null)
	{
		$app		= &JFactory::getApplication();
		$params		= &$app->getParams();

		// Get some data from the models
		$state		= &$this->get('State');
		$items		= &$this->get('Items');
		$category	= &$this->get('Category');
		$children	= &$this->get('Children');
		$parent 	= &$this->get('Parent');
		$pagination	= &$this->get('Pagination');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if($category == false)
		{
			return JError::raiseWarning(404, JText::_('COM_NEWSFEEDS_ERRORS_CATEGORY_NOT_FOUND'));
		}

		if($parent == false)
		{
			//TODO Raise error for missing parent category here
		}
		
		// Check whether category access level allows access.
		$user	= &JFactory::getUser();
		$groups	= $user->authorisedLevels();
		if (!in_array($category->access, $groups)) {
			return JError::raiseError(403, JText::_("JERROR_ALERTNOAUTHOR"));
		}

		// Prepare the data.

		// Compute the active category slug.
		$category->slug = $category->alias ? ($category->id.':'.$category->alias) : $category->id;

		// Prepare category description (runs content plugins)
		// TODO: only use if the description is displayed
		$category->description = JHtml::_('content.prepare', $category->description);

		// Compute the newsfeed slug.
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item		= &$items[$i];
			$item->slug	= $item->alias ? ($item->id.':'.$item->alias) : $item->id;
		}

		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('category',	$category);
		$this->assignRef('children',	$children);
		$this->assignRef('params',		$params);
		$this->assignRef('parent',		$parent);
		$this->assignRef('pagination',	$pagination);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= &JFactory::getApplication();
		$menus		= &JSite::getMenu();
		$pathway	= &$app->getPathway();

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		if ($menu = $menus->getActive())
		{
			$menuParams = new JRegistry;
			$menuParams->loadJSON($menu->params);
			if ($title = $menuParams->get('jpage_title')) {
				$this->document->setTitle($title);
			}
			else {
				$this->document->setTitle(JText::_('COM_NEWSFEEDS_DEFAULT_PAGE_TITLE'));
			}

			// Set breadcrumbs.
			if ($menu->query['view'] != 'category') {
				$pathway->addItem($this->category->title, '');
			}
		}
		else {
			$this->document->setTitle(JText::_('COM_NEWSFEEDS_DEFAULT_PAGE_TITLE'));
		}

		// Add alternate feed link
		if ($this->params->get('show_feed_link', 1) == 1)
		{
			$link	= '&view=category&id='.$this->category->slug.'&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$this->document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$this->document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}
	}
}

