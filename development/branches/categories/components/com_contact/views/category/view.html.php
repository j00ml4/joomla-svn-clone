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
		$params		= &$app->getParams('com_contact');

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
			return JError::raiseWarning(404, JText::_('COM_CONTACT_ERROR_CATEGORY_NOT_FOUND'));
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

		//prepare contacts
		if ($params->get('show_email', 0) == 1) {
			jimport('joomla.mail.helper');
		}
		
		// Compute the contact slug.
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$contact = &$items[$i];

			$contact->slug	= $contact->alias ? ($contact->id.':'.$contact->alias) : $contact->id;
			
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
			if ($title = $menuParams->get('jpage_title')) {
				$this->document->setTitle($title);
			}
			else {
				$this->document->setTitle(JText::_('Contacts'));
			}

			// Set breadcrumbs.
			if ($menu->query['view'] != 'category') {
				$pathway->addItem($this->category->title, '');
			}
		}
		else {
			$this->document->setTitle(JText::_('Contacts'));
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
