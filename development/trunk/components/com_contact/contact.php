<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Contact
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Load the HTML view class
require_once (JApplicationHelper :: getPath('front_html'));
require_once (JApplicationHelper :: getPath('class'));

// Set the base page title
$mainframe->setPageTitle(JText :: _('Contact'));

// Set the component level breadcrumbs name
$breadcrumbs = & $mainframe->getPathWay();
$breadcrumbs->setItemName(1, JText :: _('Contact'));

// Get the task variable
$task = JRequest :: getVar( 'task' );

switch ($task) {
	case 'view' :
		JContactController :: contactPage();
		break;

	case 'vcard' :
		JContactController :: vCard();
		break;

	case 'sendmail' :
		JContactController :: sendmail();
		break;

	default :
		JContactController :: listContacts();
		break;
}

/**
 * Contact Component Controller 
 * 
 * @static
 * @package Joomla
 * @subpackage Contact
 * @since 1.1
 */
class JContactController
{

	/**
	 * Build the data for a contact category document
	 * 
	 * @static
	 * @since 1.0
	 */
	function listContacts()
	{
		global $mainframe, $Itemid;

		/*
		 * Initialize some variables
		 */
		$db 	= & $mainframe->getDBO();
		$my 	= & $mainframe->getUser();
		$option = JRequest :: getVar('option');
		$catid 	= JRequest :: getVar('catid', 0, '', 'int');

		/*
		 * Query to retrieve all categories that belong under the contacts
		 * section and that are published.
		 */
		$query = "SELECT *, COUNT( a.id ) AS numlinks, a.id as cid" .
				"\n FROM #__categories AS cc" .
				"\n LEFT JOIN #__contact_details AS a ON a.catid = cc.id" .
				"\n WHERE a.published = 1" .
				"\n AND cc.section = 'com_contact_details'" .
				"\n AND cc.published = 1" .
				"\n AND a.access <= $my->gid" .
				"\n AND cc.access <= $my->gid" .
				"\n GROUP BY cc.id" .
				"\n ORDER BY cc.ordering";
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		$count = count($categories);
		if (($count < 2) && (@ $categories[0]->numlinks == 1))
		{
			// if only one record exists loads that record, instead of displying category list
			JContactController :: contactPage($categories[0]->cid);
		} else
		{
			$rows = array ();
			$current = new stdClass();

			// Parameters
			$menu = & JModel :: getInstance('menu', $db);
			$menu->load($Itemid);
			$params = new JParameters($menu->params);

			$params->def('page_title', 			1);
			$params->def('header', 				$menu->name);
			$params->def('pageclass_sfx', 		'');
			$params->def('headings', 			1);
			$params->def('back_button', 		$mainframe->getCfg('back_button'));
			$params->def('description_text', 	JText :: _('The Contact list for this Website.'));
			$params->def('image', 				-1);
			$params->def('image_align', 		'right');
			$params->def('other_cat_section', 	1);
			// Category List Display control
			$params->def('other_cat', 			1);
			$params->def('cat_description', 	1);
			$params->def('cat_items', 			1);
			// Table Display control
			$params->def('headings', 			1);
			$params->def('position', 			1);
			$params->def('email', 				0);
			$params->def('phone', 				0);
			$params->def('fax', 				0);
			$params->def('telephone', 			0);

			if ($catid == 0)
			{
				$catid = $params->get('catid', 0);
			}

			if ($catid)
			{
				$params->set('type', 'category');
			} else
			{
				$params->set('type', 'section');
			}

			/*
			 * If a category id is set, lets get its information
			 */
			if ($catid)
			{
				$query = "SELECT cd.*, cc.name AS cname, cc.description AS cdescription, cc.image AS cimage, cc.image_position AS cimage_position" .
						"\n FROM #__contact_details AS cd" .
						"\n INNER JOIN #__categories AS cc on cd.catid = cc.id" .
						"\n WHERE cd.catid = $catid" .
						"\n AND cc.published = 1" .
						"\n AND cd.published = 1" .
						"\n AND cc.access <= $my->gid" .
						"\n AND cd.access <= $my->gid" .
						"\n ORDER BY cd.ordering";
				$db->setQuery($query);
				$rows = $db->loadObjectList();

				// Quick trick to use one query for two things
				if (count($rows))
				{
					$current = & $rows[0];
				}
			
				/*
				Check if the category is published
				*/
				if (!$current->cname) {
					mosNotAuth();
					return;
				}
			}

			/*
			 * Lets get a description on the current category
			 */
			if (empty ($current->cdescription))
			{
				if ($params->get('description'))
				{
					$current->cdescription = $params->get('description_text');
				}
			}

			/*
			 * Lets get a description on the current category
			 */
			$path = $mainframe->getCfg('live_site').'/images/stories/';
			if (empty ($current->cimage))
			{
				if ($params->get('image') != -1)
				{
					$current->cimage = $path.$params->get('image');
					$current->cimage_position = $params->get('image_align');
				}
			} else
			{
				$current->cimage = $path.$current->cimage;
			}

			/*
			 * Time to set the page header
			 */
			if (empty ($current->cname))
			{
				$current->header = $params->get('header');
			} else
			{
				$current->header = $params->get('header').' - '.$current->cname;
			}

			/*
			 * Lets set the page title
			 */
			$document = & $mainframe->getDocument();
			if (!empty ($current->cname))
			{
				$document->setTitle(JText :: _('Contact').' - '.$current->cname);
			}

			/*
			 * Lets add the category breadcrumbs item
			 */
			$breadcrumbs = & $mainframe->getPathWay();
			if (!empty ($current->cname))
			{
				$breadcrumbs->addItem($current->cname, "");
			}

			JContactView :: displaylist($categories, $rows, $current, $catid, $params);
		}
	}

	/**
	 * Build the data for an individual contact document
	 * 
	 * @static
	 * @since 1.0
	 */
	function contactPage($cid = 0)
	{
		global $mainframe, $Itemid;

		/*
		 * Initialize some variables
		 */
		$db 		= & $mainframe->getDBO();
		$my 		= & $mainframe->getUser();
		$contactId 	= JRequest :: getVar('contact_id', $cid, '', 'int');
		$contact 	= null;

		/*
		 * Get the parameters for this particular menu item
		 */
		$menu 		= & JModel :: getInstance('menu', $db);
		$menu->load($Itemid);
		$menuParams = new JParameters($menu->params);

		/*
		 * Set some defaults for the menu item parameters
		 */
		$menuParams->def('page_title', 1);
		$menuParams->def('header', $menu->name);
		$menuParams->def('pageclass_sfx', '');

		/*
		 * Ok, now lets get the information on the particular contact id
		 */
		$query = "SELECT a.*, cc.title as catname" .
				"\n FROM #__contact_details AS a" .
				"\n INNER JOIN #__categories AS cc ON cc.id = a.catid" .
				"\n WHERE a.published = 1" .
				"\n AND a.id = $contactId" .
				"\n AND a.access <= $my->gid";
		$db->SetQuery($query);
		$db->loadObject($contact);

		if (is_object($contact))
		{
			/*
			 * If the drop_down parameter is true, then we need to build a
			 * dropdown select list of contacts in the given category
			 */
			if ($menuParams->get('drop_down'))
			{
				$query = "SELECT a.id AS value, CONCAT_WS( ' - ', a.name, a.con_position ) AS text, a.catid" .
						"\n FROM #__contact_details AS a" .
						"\n INNER JOIN #__categories AS cc ON cc.id = a.catid" .
						"\n WHERE a.catid = $contact->catid" .
						"\n AND a.published = 1" .
						"\n AND cc.published = 1" .
						"\n AND a.access <= $my->gid" .
						"\n AND cc.access <= $my->gid" .
						"\n ORDER BY a.default_con DESC, a.ordering ASC";
				$db->setQuery($query);
				$list = $db->loadObjectList();
				$contact->select = mosHTML :: selectList($list, 'contact_id', 'class="inputbox" onchange="ViewCrossReference(this);"', 'value', 'text', $contactId);
			}

			// Adds parameter handling
			$params = new JParameters($contact->params);

			$params->set('page_title', 			0);
			$params->def('pageclass_sfx', 		'');
			$params->def('back_button', 		$mainframe->getCfg('back_button'));
			$params->def('print', 				!$mainframe->getCfg('hidePrint'));
			$params->def('name', 				1);
			$params->def('email', 				0);
			$params->def('street_address', 		1);
			$params->def('suburb', 				1);
			$params->def('state', 				1);
			$params->def('country', 			1);
			$params->def('postcode', 			1);
			$params->def('telephone', 			1);
			$params->def('fax', 				1);
			$params->def('misc', 				1);
			$params->def('image', 				1);
			$params->def('email_description', 	1);
			$params->def('email_description_text', JText :: _('Send an Email to this Contact:'));
			$params->def('email_form', 			1);
			$params->def('email_copy', 			0);
			// global pront|pdf|email
			$params->def('icons', 				$mainframe->getCfg('icons'));
			// contact only icons
			$params->def('contact_icons', 		0);
			$params->def('icon_address', 		'');
			$params->def('icon_email', 			'');
			$params->def('icon_telephone', 		'');
			$params->def('icon_fax', 			'');
			$params->def('icon_misc', 			'');
			$params->def('drop_down', 			0);
			$params->def('vcard', 				0);

			if ($contact->email_to && $params->get('email'))
			{
				// email cloacking
				$contact->email = mosHTML :: emailCloaking($contact->email_to);
			}

			/*
			 * If the popup var is set, make some parameter changes
			 */
			$pop = JRequest :: getVar('pop', 0, '', 'int');
			if ($pop)
			{
				$params->set('popup', 1);
				$params->set('back_button', 0);
			}

			/*
			 * If e-mail description is set, lets prepare it for display
			 */
			if ($params->get('email_description'))
			{
				$params->set('email_description', $params->get('email_description_text'));
			} else
			{
				$params->set('email_description', '');
			}

			/*
			 * If there is no address information to display we set a flag so
			 * the display method for address does not get called in the view
			 * class
			 */
			if (!empty ($contact->address) || !empty ($contact->suburb) || !empty ($contact->state) || !empty ($contact->country) || !empty ($contact->postcode))
			{
				$params->set('address_check', 1);
			} else
			{
				$params->set('address_check', 0);
			}

			/*
			 * Time to manage the display mode for contact detail groups
			 */
			switch ($params->get('contact_icons'))
			{
				case 1 :
					// text
					$params->set('marker_address', JText :: _('Address').": ");
					$params->set('marker_email', JText :: _('Email').": ");
					$params->set('marker_telephone', JText :: _('Telephone').": ");
					$params->set('marker_fax', JText :: _('Fax').": ");
					$params->set('marker_misc', JText :: _('Information').": ");
					$params->set('column_width', '100');
					break;
				case 2 :
					// none
					$params->set('marker_address', '');
					$params->set('marker_email', '');
					$params->set('marker_telephone', '');
					$params->set('marker_fax', '');
					$params->set('marker_misc', '');
					$params->set('column_width', '0');
					break;
				default :
					// icons
					$image1 = mosAdminMenus :: ImageCheck('con_address.png', '/images/M_images/', $params->get('icon_address'), '/images/M_images/', JText :: _('Address').": ", JText :: _('Address').": ");
					$image2 = mosAdminMenus :: ImageCheck('emailButton.png', '/images/M_images/', $params->get('icon_email'), '/images/M_images/', JText :: _('Email').": ", JText :: _('Email').": ");
					$image3 = mosAdminMenus :: ImageCheck('con_tel.png', '/images/M_images/', $params->get('icon_telephone'), '/images/M_images/', JText :: _('Telephone').": ", JText :: _('Telephone').": ");
					$image4 = mosAdminMenus :: ImageCheck('con_fax.png', '/images/M_images/', $params->get('icon_fax'), '/images/M_images/', JText :: _('Fax').": ", JText :: _('Fax').": ");
					$image5 = mosAdminMenus :: ImageCheck('con_info.png', '/images/M_images/', $params->get('icon_misc'), '/images/M_images/', JText :: _('Information').": ", JText :: _('Information').": ");
					$params->set('marker_address', $image1);
					$params->set('marker_email', $image2);
					$params->set('marker_telephone', $image3);
					$params->set('marker_fax', $image4);
					$params->set('marker_misc', $image5);
					$params->set('column_width', '40');
					break;
			}

			/*
			 * Set the document page title
			 */
			$document = & $mainframe->getDocument();
			$document->setTitle(JText :: _('Contact').' - '.$contact->name);

			/*
			 * Add the breadcrumbs items
			 * 	- Category item if the parameter is set
			 * 	- Contact item always
			 */
			$breadcrumbs = & $mainframe->getPathWay();
			if (!$menuParams->get('hideCatCrumbs'))
			{
				$breadcrumbs->addItem($contact->catname, "index.php?option=com_contact&catid=$contact->catid&Itemid=$Itemid");
			}
			$breadcrumbs->addItem($contact->name, '');

			JContactView :: viewContact($contact, $params, count($list), $list, $menuParams);
		} else
		{
			$params = new JParameters('');
			$params->def('back_button', $mainframe->getCfg('back_button'));
			JContactView :: noContact($params);
		}
	}

	/**
	 * Method to send an email to a contact
	 * 
	 * @static
	 * @since 1.0
	 */
	function sendmail() 
	{
		global $mainframe, $Itemid;
		
		/*
		 * Initialize some variables
		 */
		$db = & $mainframe->getDBO();
		
		$SiteName 	= $mainframe->getCfg('sitename');
		$MailFrom 	= $mainframe->getCfg('mailfrom');
		$FromName 	= $mainframe->getCfg('fromname');
		$option 	= JRequest :: getVar('option');
		$contactId 	= JRequest :: getVar('con_id');
		$validate 	= JRequest :: getVar(mosHash('validate'), 0, 'post');
		$default 	= sprintf(JText :: _('MAILENQUIRY'), $SiteName);
		$email 		= JRequest :: getVar('email', '', 'post');
		$text 		= JRequest :: getVar('text', '', 'post');
		$name 		= JRequest :: getVar('name', '', 'post');
		$subject 	= JRequest :: getVar('subject', $default, 'post');
		$emailCopy 	= JRequest :: getVar('email_copy', 0, 'post');

		// probably a spoofing attack
		if (!$validate) {
			echo _NOT_AUTH;
			return;
		}
		
		/*
		 * This obviously won't catch all attempts, but it does not hurt to make
		 * sure the request came from a client with a user agent string.
		 */
		if (!isset ($_SERVER['HTTP_USER_AGENT'])) {
			echo _NOT_AUTH;
			return;
		}

		/*
		 * This obviously won't catch all attempts either, but we ought to check
		 * to make sure that the request was posted as well.
		 */
		if (!$_SERVER['REQUEST_METHOD'] == 'POST') {
			echo _NOT_AUTH;
			return;
		}

		// An array of e-mail headers we do not want to allow as input
		$headers = array ('Content-Type:',
						  'MIME-Version:',
						  'Content-Transfer-Encoding:',
						  'bcc:',
						  'cc:');

		// An array of the input fields to scan for injected headers
		$fields = array ('email',
						 'text',
						 'name',
						 'subject',
						 'email_copy');

		/*
		 * Here is the meat and potatoes of the header injection test.  We
		 * iterate over the array of form input and check for header strings.
		 * If we fine one, send an unauthorized header and die.
		 */
		foreach ($fields as $field) {
			foreach ($headers as $header) {
				if (strpos($_POST[$field], $header) !== false) {
					echo _NOT_AUTH;
					return;
				}
			}
		}

		/*
		 * Now that we have passed the header injection tests lets free up the
		 * used memory and continue.
		 */
		unset ($fields, $field, $headers, $header);

		/*
		 * Load the contact details
		 */
		$contact = new JContactModel($db);
		$contact->load($contactId);

		/*
		 * If there is no valid email address or message body then we throw an
		 * error and return false.
		 */
		jimport('joomla.mail');
		if (!$email || !$text || (JMailHelper :: isEmailAddress($email) == false)) {
			JContactView :: emailError();
		} else {
			$menu = new JModelMenu( $database );
			$menu->load( $Itemid );
			$mparams = new JParameters( $menu->params );		
			$bannedEmail 	= $mparams->get( 'bannedEmail', 	'' );		
			$bannedSubject 	= $mparams->get( 'bannedSubject', 	'' );		
			$bannedText 	= $mparams->get( 'bannedText', 		'' );
			
			// Prevent form submission if one of the banned text is discovered in the email field
			if ( $bannedEmail ) {
				$bannedEmail = explode( ';', $bannedEmail );
				foreach ($bannedEmail as $value) {
					if ( stristr($email, $value) ) {
						echo _NOT_AUTH;
						return;
					}
				}
			}
			// Prevent form submission if one of the banned text is discovered in the subject field		
			if ( $bannedSubject ) {
				$bannedSubject = explode( ';', $bannedSubject );
				foreach ($bannedSubject as $value) {
					if ( stristr($subject, $value) ) {
						echo _NOT_AUTH;
						return;
					}
				}
			}
			// Prevent form submission if one of the banned text is discovered in the text field		
			if ( $bannedText ) {
				$bannedText = explode( ';', $bannedText );
				foreach ($bannedText as $value) {
					if ( stristr($text, $value) ) {
						echo _NOT_AUTH;
						return;
					}
				}
			}			

			/*
			 * Prepare email body
			 */
			
			$url = );
			 
			$prefix = sprintf(JText :: _('ENQUIRY_TEXT'), $mainframe->getBaseURL());
			$text 	= $prefix."\n".$name.' <'.$email.'>'."\r\n\r\n".stripslashes($text);

			// Send mail
			josMail($email, $name, $contact->email_to, $FromName.': '.$subject, $text);

			/*
			 * If we are supposed to copy the admin, do so.
			 */
			// parameter check
			$params 		= new JParameters( $contact->params );		
			$emailcopyCheck = $params->get( 'email_copy', 0 );
			
			if ( $emailCopy && $emailcopyCheck ) {
				$copyText 		= sprintf(JText :: _('Copy of:'), $contact->name, $SiteName);
				$copyText 		.= "\r\n\r\n".$text;
				$copySubject 	= JText :: _('Copy of:')." ".$subject;
				josMail($MailFrom, $FromName, $email, $copySubject, $copyText);
			}
		
			$link = 'index.php?option=com_contact&task=view&contact_id='. $contact[0]->id .'&Itemid='. $Itemid;
			$text = JText::_( 'Thank you for your e-mail', true );
			
			mosRedirect( $link, $text );
		}
	}

	/**
	 * Method to output a vCard
	 * 
	 * @static
	 * @since 1.0
	 */
	function vCard() 
	{
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$db = & $mainframe->getDBO();
		
		$SiteName = $mainframe->getCfg('sitename');
		$contactId = JRequest :: getVar('contact_id', 0, '', 'int');

		/*
		 * Get a JContact model object and load the selected contact details
		 */
		$contact = new JContactModel($db);
		$contact->load($contactId);

		/*
		 * Get the contact detail parameters
		 */
		$params = new JParameters($contact->params);
		$show 	= $params->get('vcard', 0);

		/*
		 * Should we show the vcard?
		 */
		if ($show) {
			/*
			 * We need to parse the contact name field and build the name
			 * information for the vcard.
			 */
			$firstname 	= null;
			$middlename = null;
			$surname 	= null;

			// How many parts do we have?
			$parts = explode(' ', $contact->name);
			$count = count($parts);

			switch ($count) {
				case 1 :
					// only a first name
					$firstname = $parts[0];
					break;

				case 2 :
					// first and last name
					$firstname = $parts[0];
					$surname = $parts[1];
					break;

				default :
					// we have full name info
					$firstname = $parts[0];
					$surname = $parts[$count -1];
					for ($i = 1; $i < $count -1; $i ++) {
						$middlename .= $parts[$i].' ';
					}
					break;
			}
			// quick cleanup for the middlename value
			$middlename = trim($middlename);

			/*
			 * Create a new vcard object and populate the fields
			 */
			$v = new JvCard();

			$v->setPhoneNumber($contact->telephone, 'PREF;WORK;VOICE');
			$v->setPhoneNumber($contact->fax, 'WORK;FAX');
			$v->setName($surname, $firstname, $middlename, '');
			$v->setAddress('', '', $contact->address, $contact->suburb, $contact->state, $contact->postcode, $contact->country, 'WORK;POSTAL');
			$v->setEmail($contact->email_to);
			$v->setNote($contact->misc);
			$v->setURL( $mainframe->getBaseURL(), 'WORK');
			$v->setTitle($contact->con_position);
			$v->setOrg($SiteName);

			$filename = str_replace(' ', '_', $contact->name);
			$v->setFilename($filename);

			$output = $v->getVCard($SiteName);
			$filename = $v->getFileName();

			// Send vCard file headers
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Length: '.strlen($output));
			header('Connection: close');
			header('Content-Type: text/x-vCard; name='.$filename);
			header('Cache-Control: store, cache');
			header('Pragma: cache');

			print $output;
		} else {
			JError :: raiseWarning('SOME_ERROR_CODE', 'JContactController::vCard: '.JText :: _('NOTAUTH'));
			return false;
		}
	}
}
?>