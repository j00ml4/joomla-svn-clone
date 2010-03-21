<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the WebLinks component
 *
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @since		1.5
 */
class WeblinksViewCategory extends JView
{
	protected $state;
	protected $items;
	protected $category;
	protected $categories;
	protected $pagination;

	function display($tpl = null)
	{
		$app		= &JFactory::getApplication();
		$params		= &$app->getParams('com_weblinks');

		// Get some data from the models
		$state		= &$this->get('State');

		$items		= &$this->get('Items');
		$category	= &$this->get('Category');
		$pagination	= &$this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Validate the category.

		// Make sure the category was found.
		if (empty($category)) {
			return JError::raiseWarning(404, JText::_('COM_WEBLINKS_ERROR_CATEGORY_NOT_FOUND'));
		}

		// Check whether category access level allows access.
		$user	= &JFactory::getUser();
		$groups	= $user->authorisedLevels();
		if (!in_array($category->access, $groups)) {
			return JError::raiseError(403, JText::_("JERROR_ALERTNOAUTHOR"));
		}

		// Prepare the data.
		// Prepare category description (runs content plugins)
		$category->description = JHtml::_('content.prepare', $category->description);

		// Compute the weblink slug & link url.
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item		= &$items[$i];
			if ($item->params->get('count_clicks', $params->get('count_clicks')) == 1) {
				$item->slug	= $item->alias ? ($item->id.':'.$item->alias) : $item->id;
				$item->link = JRoute::_(WeblinksHelperRoute::getWeblinkRoute($item->slug, $item->catid));
			} else {
				$item->link = $item->url;
			}
		}

		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('category',	$category);
		$this->assignRef('parent',		$category->getParent());
		$this->assignRef('children',	$category->getChildren());
		$this->assignRef('categories',	$categories);
		$this->assignRef('params',		$params);
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
			$menuParams = new JParameter($menu->params);
			if ($title = $menuParams->get('page_title')) {
				$this->document->setTitle($title);
			}
			else {
				$this->document->setTitle(JText::_('COM_WEBLINKS_WEB_LINKS'));
			}

			// Set breadcrumbs.
			if ($menu->query['view'] != 'category') {
				$pathway->addItem($this->category->title, '');
			}
		}
		else {
			$this->document->setTitle(JText::_('COM_WEBLINKS_WEB_LINKS'));
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
