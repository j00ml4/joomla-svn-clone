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
		$pathway	= &$app->getPathway();
		$document	= & JFactory::getDocument();
		$contact	= $this->get('Data');

		// report any errors and exit if they exist
		$this->reportErrors($this->get('Errors'));

		// Get the parameters of the active menu item
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

		$params = $app->getParams();

		// check if access is registered/special
		$groups	= $user->authorisedLevels();

		$return ="";
		if ((!in_array($contact->access, $groups)) || (!in_array($contact->cat_access, $groups))) {
			$uri		= JFactory::getURI();
			$return		= (string)$uri;

			$url  = 'index.php?option=com_users&view=login';
			$url .= '&return='.base64_encode($return);

			$app->redirect($url, JText::_('YOU_MUST_LOGIN_FIRST'));

		}

		// Set the document page title
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object($menu) && isset($menu->query['view']) && $menu->query['view'] == 'contact' && isset($menu->query['id']) && $menu->query['id'] == $contact->id) {
			$menu_params = new JParameter($menu->params);
			if (!$menu_params->get('page_title')) {
				$params->set('page_title',	$contact->name);
			}
		} else {
			$params->set('page_title',	$contact->name);
		}
		$document->setTitle($params->get('page_title'));

		//set breadcrumbs
		if (isset($menu) && isset($menu->query['view']) && $menu->query['view'] != 'contact'){
			$pathway->addItem($contact->name, '');
		}

		// Make contact parameters available to views
		$contact->params = new JParameter($contact->params);

		// Handle email cloaking
		if ($contact->email_to && $params->get('show_email')) {
			$contact->email_to = JHtml::_('email.cloak', $contact->email_to);
		}

		if ($params->get('show_street_address') || $params->get('show_suburb') || $pparams->get('show_state') || $pparams->get('show_postcode') || $pparams->get('show_country'))
		{
			if (!empty ($contact->address) || !empty ($contact->suburb) || !empty ($contact->state) || !empty ($contact->country) || !empty ($contact->postcode)) {
				$params->set('address_check', 1);
			}
		} else {
			$params->set('address_check', 0);
		}

		// Manage the display mode for contact detail groups
		switch ($params->get('contact_icons'))
		{
			case 1 :
				// text
				$params->set('marker_address',	JText::_('Address').": ");
				$params->set('marker_email',		JText::_('Email').": ");
				$params->set('marker_telephone',	JText::_('Telephone').": ");
				$params->set('marker_fax',		JText::_('Fax').": ");
				$params->set('marker_mobile',		JText::_('Mobile').": ");
				$params->set('marker_misc',		JText::_('Information').": ");
				$params->set('marker_class',		'jicons-text');
				break;

			case 2 :
				// none
				$params->set('marker_address',	'');
				$params->set('marker_email',		'');
				$params->set('marker_telephone',	'');
				$params->set('marker_mobile',	'');
				$params->set('marker_fax',		'');
				$params->set('marker_misc',		'');
				$params->set('marker_class',		'jicons-none');
				break;

			default :
				// icons
				$image1 = JHTML::_('image','contacts/'.$params->get('icon_address','con_address.png'), JText::_('Address').": ", NULL, true);
				$image2 = JHTML::_('image','contacts/'.$params->get('icon_email','emailButton.png'), JText::_('Email').": ", NULL, true);
				$image3 = JHTML::_('image','contacts/'.$params->get('icon_telephone','con_tel.png'), JText::_('Telephone').": ", NULL, true);
				$image4 = JHTML::_('image','contacts/'.$params->get('icon_fax','con_fax.png'), JText::_('Fax').": ", NULL, true);
				$image5 = JHTML::_('image','contacts/'.$params->get('icon_misc','con_info.png'), JText::_('Information').": ", NULL, true);
				$image6 = JHTML::_('image','contacts/'.$params->get('icon_mobile','con_mobile.png'), JText::_('Mobile').": ", NULL, true);

				$params->set('marker_address',	$image1);
				$params->set('marker_email',		$image2);
				$params->set('marker_telephone',	$image3);
				$params->set('marker_fax',		$image4);
				$params->set('marker_misc',		$image5);
				$params->set('marker_mobile',		$image6);
				$params->set('marker_class',		'jicons-icons');
				break;
		}

		// Use link labels from contact if blank in params
		$loopArray = array('a','b','c','d','e');
		foreach ($loopArray as $letter) {
			$thisLable = 'link'.$letter.'_name';
			$thisLink = 'link'.$letter;
			if (!$params->get($thisLable)) {
				if ($contact->params->get($thisLable)) {
					$params->set($thisLable, $contact->params->get($thisLable));
				} else {
					$params->set($thisLable, $contact->params->get($thisLink));
				}
			}
		}

		JHtml::_('behavior.formvalidation');

		$this->assignRef('contact',		$contact);
		$this->assignRef('contacts',	$contacts);
		$this->assignRef('params',		$params);
		$this->assignRef('return',		$return);

		parent::display($tpl);
	}
	/**
	 * Checks for errors and exits with messages if found
	 * @param	Array of errors
	 * @return	null
	 */
	function reportErrors($errors)
	{
		if (!$errors || !is_array($errors)) {
			return;
		}
		foreach ($errors as &$error)
		{
			if ($error instanceof Exception)
			{
				if ($error->getCode() == 404)
				{
					// If there is a 404, throw a hard error.
					JError::raiseError(404, $error->getMessage());
					return false;
				}
				else
				{
					JError::raiseError(500, $error->getMessage());
				}
			}
			else
			{
				JError::raiseWarning(500, $error);
			}
		}
		return false;

	}
}

