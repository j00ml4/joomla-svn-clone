<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.mail.helper');

/**
 * @package		Joomla.Site
 * @subpackage	Contacts
 */
class ContactViewCategory extends JView
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

		if($params->get('max_levels', 0) > 0)
		{
			$params->set('max_levels', $params->get('max_levels') + $category->level);
		}

		foreach($items as &$contact)
		{
			$contact->slug = $contact->alias ? ($contact->id.':'.$contact->alias) : $contact->id;

			$contact->link = JRoute::_(ContactHelperRoute::getContactRoute($contact->slug, $contact->catid));
			if ($params->get('show_email', 0) == 1) {
				$contact->email_to = trim($contact->email_to);
				if (!empty($contact->email_to) && JMailHelper::isEmailAddress($contact->email_to)) {
					$contact->email_to = JHtml::_('email.cloak', $contact->email_to);
				} else {
					$contact->email_to = '';
				}
			}
		}

		// Prepare category description
		$category->description = JHtml::_('content.prepare', $category->description);

		$children = array($category->id => $children);

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
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_CONTACT_DEFAULT_PAGE_TITLE')); 
		}		
		$id = (int) @$menu->query['id'];
		if($menu && $menu->query['view'] != 'contact' && $id != $this->category->id)
		{
			$this->params->set('page_subheading', $this->category->title);
			$path = array($this->category->title => '');
			$category = $this->category->getParent();
			while($id != $category->id && $category->id > 1)
			{
				$path[$category->title] = ContactHelperRoute::getCategoryRoute($category->id);
				$category = $category->getParent();
			}
			$path = array_reverse($path);
			foreach($path as $title => $link)
			{
				$pathway->addItem($title, $link);
			}
		}
		
		$title = $this->params->get('page_title', '');
		if (empty($title))
		{
			$title = htmlspecialchars_decode($app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		// Add alternate feed link
		if ($this->params->get('show_feed_link', 1) == 1)
		{
			$link	= '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$this->document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$this->document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}
	}
}