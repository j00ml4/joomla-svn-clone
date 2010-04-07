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
class ContactViewContact extends JView
{
	protected $state = null;
	protected $contact = null;

	function display($tpl = null)
	{
		$app		= &JFactory::getApplication();
		$user		= &JFactory::getUser();
		$document	= & JFactory::getDocument();
		$state		= $this->get('State');
		$contact	= $this->get('Item');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Get the parameters of the active menu item
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

		$pparams = $state->params;

		// check if access is registered/special
		$groups	= $user->authorisedLevels();

		$return ="";
		if ((!in_array($contact->access, $groups)) || (!in_array($contact->category_access, $groups))) {
			$uri		= JFactory::getURI();
			$return		= (string)$uri;

			$url  = 'index.php?option=com_users&view=login';
			$url .= '&return='.base64_encode($return);

			//$app->redirect($url, JText::_('YOU_MUST_LOGIN_FIRST'));

		}

		$options['category_id']	= $contact->catid;
		$options['order by']	= 'cd.default_con DESC, cd.ordering ASC';

		//$contacts = &$this->getModel('Category')->getContacts($options);


		// Make contact parameters available to views
		$contact->params = new JRegistry;
		$contact->params->loadJSON($contact->params);

		// Handle email cloaking
		if ($contact->email_to && $pparams->get('show_email')) {
			$contact->email_to = JHtml::_('email.cloak', $contact->email_to);
		}

		if ($pparams->get('show_street_address') || $pparams->get('show_suburb') || $pparams->get('show_state') || $pparams->get('show_postcode') || $pparams->get('show_country'))
		{
			if (!empty ($contact->address) || !empty ($contact->suburb) || !empty ($contact->state) || !empty ($contact->country) || !empty ($contact->postcode)) {
				$pparams->set('address_check', 1);
			}
		} else {
			$pparams->set('address_check', 0);
		}

		// Manage the display mode for contact detail groups
		switch ($pparams->get('contact_icons'))
		{
			case 1 :
				// text
				$pparams->set('marker_address',	JText::_('Address').": ");
				$pparams->set('marker_email',		JText::_('Email').": ");
				$pparams->set('marker_telephone',	JText::_('Telephone').": ");
				$pparams->set('marker_fax',		JText::_('Fax').": ");
				$pparams->set('marker_mobile',		JText::_('Mobile').": ");
				$pparams->set('marker_misc',		JText::_('Information').": ");
				$pparams->set('marker_class',		'jicons-text');
				break;

			case 2 :
				// none
				$pparams->set('marker_address',	'');
				$pparams->set('marker_email',		'');
				$pparams->set('marker_telephone',	'');
				$pparams->set('marker_mobile',	'');
				$pparams->set('marker_fax',		'');
				$pparams->set('marker_misc',		'');
				$pparams->set('marker_class',		'jicons-none');
				break;

			default :
				// icons
				$image1 = JHTML::_('image','contacts/'.$pparams->get('icon_address','con_address.png'), JText::_('Address').": ", NULL, true);
				$image2 = JHTML::_('image','contacts/'.$pparams->get('icon_email','emailButton.png'), JText::_('Email').": ", NULL, true);
				$image3 = JHTML::_('image','contacts/'.$pparams->get('icon_telephone','con_tel.png'), JText::_('Telephone').": ", NULL, true);
				$image4 = JHTML::_('image','contacts/'.$pparams->get('icon_fax','con_fax.png'), JText::_('Fax').": ", NULL, true);
				$image5 = JHTML::_('image','contacts/'.$pparams->get('icon_misc','con_info.png'), JText::_('Information').": ", NULL, true);
				$image6 = JHTML::_('image','contacts/'.$pparams->get('icon_mobile','con_mobile.png'), JText::_('Mobile').": ", NULL, true);

				$pparams->set('marker_address',	$image1);
				$pparams->set('marker_email',		$image2);
				$pparams->set('marker_telephone',	$image3);
				$pparams->set('marker_fax',		$image4);
				$pparams->set('marker_misc',		$image5);
				$pparams->set('marker_mobile',		$image6);
				$pparams->set('marker_class',		'jicons-icons');
				break;
		}

		// Use link labels from contact if blank in params
		$loopArray = array('a','b','c','d','e');
		foreach ($loopArray as $letter) {
			$thisLable = 'link'.$letter.'_name';
			$thisLink = 'link'.$letter;
			if (!$pparams->get($thisLable)) {
				if ($contact->params->get($thisLable)) {
					$pparams->set($thisLable, $contact->params->get($thisLable));
				} else {
					$pparams->set($thisLable, $contact->params->get($thisLink));
				}
			}
		}

		JHtml::_('behavior.formvalidation');

		$this->assignRef('contact',		$contact);
		$this->assignRef('params',		$pparams);
		$this->assignRef('return',		$return);

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
		if($menu && $menu->query['view'] != 'contact')
		{
			$id = (int) @$menu->query['id'];
			$path = array($this->contact->name => '');
			$category = JCategories::getInstance('Contact')->get($this->contact->catid);
			while($id != $category->id && $category->id > 1)
			{
				$path[$category->title] = ContactHelperRoute::getCategoryRoute($this->contact->catid);
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
		
	}
}

