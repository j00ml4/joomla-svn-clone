<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Users mail model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since	1.6
 */
class UsersModelMail extends JModelAdmin
{
	/**
	 * Method to get the row form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function getForm()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = parent::getForm('com_users.mail', 'mail', array('control' => 'jform'));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to load the form data.
	 *
	 * @param	JForm	The form object.
	 * @throws	Exception if there is an error in the data load.
	 * @since	1.6
	 */
	protected function loadFormData(JForm $form)
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_users.display.mail.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}
	}

	/**
	 * Override preprocessForm to load the user plugin group instead of content.
	 *
	 * @param	object	A form object.
	 * @throws	Exception if there is an error in the form event.
	 * @since	1.6
	 */
	protected function preprocessForm(JForm $form)
	{
		parent::preprocessForm($form, 'user');
	}

	public function send()
	{
		// Initialise variables.
		$data	= JRequest::getVar('jform', array(), 'post', 'array');
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$acl	= JFactory::getACL();
		$db		= $this->getDbo();

		$mode		= array_key_exists('mode',$data) ? intval($data['mode']) : 0;
		$subject	= array_key_exists('subject',$data) ? $data['subject'] : '';
		$grp		= array_key_exists('group',$data) ? intval($data['group']) : 0;
		$recurse	= array_key_exists('recurse',$data) ? intval($data['recurse']) : 0;
		$bcc		= array_key_exists('bcc',$data) ? intval($data['bcc']) : 0;
		$message_body = array_key_exists('message',$data) ? $data['message'] : '';

		// automatically removes html formatting
		if (!$mode) {
			$message_body = JFilterInput::getInstance()->clean($message_body, 'string');
		}

		// Check for a message body and subject
		if (!$message_body || !$subject) {
			$this->setError(JText::_('COM_USERS_MAIL_PLEASE_FILL_IN_THE_FORM_CORRECTLY'));
			return false;
		}

		// get users in the group out of the acl
		$to = $acl->getUsersByGroup($grp, $recurse);

		// Get all users email and group except for senders
		$query	= $db->getQuery(true);
		$query->select('email');
		$query->from('#__users');
		$query->where('id != '.(int) $user->get('id'));
		if ($grp !== 0) {
			if (empty($to)) {
				$query->where('0');
			} else {
				$query->where('id IN (' . implode(',', $to) . ')');
			}
		}

		$db->setQuery($query);
		$rows = $db->loadResultArray();

		// Check to see if there are any users in this group before we continue
		if (!count($rows)) {
			$this->setError(JText::_('COM_USERS_MAIL_NO_USERS_COULD_BE_FOUND_IN_THIS_GROUP'));
			return false;
		}

		// Get the Mailer
		$mailer = JFactory::getMailer();
		$params = &JComponentHelper::getParams('com_users');

		// Build e-mail message format.
		$mailer->setSender(array($app->getCfg('mailfrom'), $app->getCfg('fromname')));
		$mailer->setSubject($params->get('mailSubjectPrefix') . stripslashes($subject));
		$mailer->setBody($message_body . $params->get('mailBodySuffix'));
		$mailer->IsHTML($mode);

		// Add recipients
		if ($bcc) {
			$mailer->addBCC($rows);
			$mailer->addRecipient($app->getCfg('mailfrom'));
		} else {
			$mailer->addRecipient($rows);
		}

		// Send the Mail
		$rs	= $mailer->Send();

		// Check for an error
		if (JError::isError($rs)) {
			$this->setError($rs->getError());
			return false;
		} elseif (empty($rs)) {
			$this->setError(JText::_('COM_USERS_MAIL_THE_MAIL_COULD_NOT_BE_SENT'));
			return false;
		} else {
			// Fill the data (specially for the 'mode', 'group' and 'bcc': they could not exist in the array
			// when the box is not checked and in this case, the default value would be used instead of the '0'
			// one)
			$data['mode']=$mode;
			$data['subject']=$subject;
			$data['group']=$grp;
			$data['recurse']=$recurse;
			$data['bcc']=$bcc;
			$data['message']=$message_body;
			$app->enqueueMessage(JText::sprintf('COM_USERS_MAIL_EMAIL_SENT_TO', count($rows)),'message');
			$app->setUserState('com_users.display.mail.data', $data);
			return true;
		}
	}
}
