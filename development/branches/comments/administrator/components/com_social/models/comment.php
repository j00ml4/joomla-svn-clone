<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Comment model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @since		1.6
 */
class SocialModelComment extends JModelAdmin
{
	/**
	 * Filter the body text according to configuration options.
	 *
	 * @param	string	The body text
	 * @return	string
	 * @since	1.6
	 */
	public static function filterBody($value)
	{
		// If html is not enabled, then lets filter it out
		$config = JComponentHelper::getParams('com_social');
		if ($config->get('enable_html')) {
			$return = JFilterInput::getInstance(null, null, 1, 1)->clean($value, 'string');
		} else {
			$return = JFilterInput::getInstance()->clean($value);
		}
		return $return;
	}

	/**
	 * Method to get the record form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function getForm()
	{
		// Get the form.
		try {
			$form = parent::getForm('com_social.comment', 'comment', array('control' => 'jform'));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		// Check the session for previously entered form data.

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
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
		$data = JFactory::getApplication()->getUserState('com_social.edit.comment.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		} else {
			$form->bind($this->getItem());
		}
	}

	/**
	 * Method to return a short list of comment objects with the same IP in descending order of date
	 *
	 * @return	array	Array list of comment objects with the same IP
	 * @since	1.6
	 */
	public function getListByIP()
	{
		if (empty($this->_listByIP)) {
			$item = &$this->getItem();

			// lets get a list of comments with the same IP in descending order of created date
			$db = &$this->getDBO();
			$db->setQuery(
				'SELECT *' .
				' FROM `#__social_comments`' .
				' WHERE `address` = '.$db->Quote($item->address) .
				' AND `id` != '.(int)$item->id .
				' ORDER BY `created_time` DESC',
				0, 5
			);
			$this->_listByIP = $db->loadObjectList();

			if (empty($this->_listByIP)) {
				$this->_listByIP = array();
			}
		}
		return $this->_listByIP;
	}

	/**
	 * Method to return a short list of comment objects with a given author name in descending order of date
	 *
	 * @return	array	Array list of comment objects with the same author name
	 * @since	1.6
	 */
	public function getListByName()
	{
		if (empty($this->_listByName)) {
			$item = &$this->getItem();

			// lets get a list of comments with the same author name in descending order of created date
			$db = &$this->getDBO();
			$db->setQuery(
				'SELECT *' .
				' FROM `#__social_comments`' .
				' WHERE `name` = '.$db->Quote($item->name) .
				' AND `id` != '.(int)$item->id .
				' ORDER BY `created_time` DESC',
				0, 5
			);
			$this->_listByName = $db->loadObjectList();

			if (empty($this->_listByName)) {
				$this->_listByName = array();
			}
		}
		return $this->_listByName;
	}

	/**
	 * Method to return a short list of comment objects in a given tread in descending order of date
	 *
	 * @return	array	Array list of comment objects in the same thread
	 * @since	1.6
	 */
	public function getListByThread()
	{
		if (empty($this->_listByContext)) {
			$item = &$this->getItem();

			// lets get a list of comments
			$db = &$this->getDBO();
			$db->setQuery(
				'SELECT a.*' .
				' FROM `#__social_comments` AS a' .
				' WHERE a.`thread_id` = '.(int)$item->thread_id .
				' AND a.`created_time` < '.$db->Quote($item->created_time) .
				' AND a.`id` != '.(int)$item->id .
				' ORDER BY a.`created_time` DESC',
				0, 5
			);
			$this->_listByContext = $db->loadObjectList();

			if (empty($this->_listByContext)) {
				$this->_listByContext = array();
			}
		}
		return $this->_listByContext;
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Comment', $prefix = 'SocialTable', $config = array())
	{
		JTable::addIncludePath(JPATH_SITE.'/components/com_social/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a thread for a comment.
	 *
	 * @since	1.6
	 */
	public function getThread()
	{
		$comment = $this->getItem();

		if ($threadId = (int) $comment->thread_id) {
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('b.id AS thread_id, b.context, b.context_id, b.page_title, b.page_route, b.page_url')
				->from('#__social_threads AS b')
				->where('b.id = '.$threadId);
			$thread = $db->setQuery($query)->loadObject();

			if ($error = $db->getErrorMsg()) {
				$this->setError($error);
				return false;
			}

			return $thread;
		} else {
			$this->setError('Comment_Error_Thread_not_found');
			return false;
		}
	}

	/**
	 * Method to set the moderation state on a list of comments
	 *
	 * @param	array	The list of {COMMENT_ID}=>{STATE} values to set
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	public function moderate($ids)
	{
		// Initialise variables.
		$db		= $this->getDBO();
		$config	= JComponentHelper::getParams('com_social');

		$useAkismet= false;
		if ($config->get('enable_akismet')) {
			jimport('joomla.webservices.akismet');
			$akismet = new JXAkismet(JURI::base(), $config->get('akismet_key'));

			$valid = $akismet->validateAPIKey();
			if ($valid and !JError::isError($valid)) {
				$useAkismet = true;
			} else {
				JError::raiseNotice(500, JText::_('COM_SOCIAL_INVALID_AKISMET_KEY'));
			}
		}

		// iterate over the ids to moderate.
		foreach ($ids as $id => $state) {
			$db->setQuery(
				'SELECT *' .
				' FROM `#__social_comments`' .
				' WHERE `id` = '.(int)$id
			);
			$c = $db->loadObject();

			if (($state == -1) and ($c->published != -1)) {
				// notify Akismet of spam
				if ($useAkismet and is_object($akismet)) {
					// create and populate the comment object
					$comment = new JObject();
					$comment->set('author', $c->name);
					$comment->set('email', $c->email);
					$comment->set('website', $c->url);
					$comment->set('body', $c->body);
					$comment->set('permalink', $c->referer);

					// set the comment to the Akismet handler and set the comment as spam
					$akismet->setComment($comment);
					$akismet->submitSpam();
				}
			} elseif (($state == 1) and ($c->published == -1)) {
				// notify Akismet of ham
				if ($useAkismet and is_object($akismet)) {
					// create and populate the comment object
					$comment = new JObject();
					$comment->set('author', $c->name);
					$comment->set('email', $c->email);
					$comment->set('website', $c->url);
					$comment->set('body', $c->body);
					$comment->set('permalink', $c->referer);

					// set the comment to the Akismet handler and set the comment as spam
					$akismet->setComment($comment);
					$akismet->submitHam();
				}
			}

			if ($state === -2) {
				// delete the comment
				$db->setQuery(
					'DELETE FROM `#__social_comments`' .
					' WHERE `id` = '.(int)$id
				);
			} else {
				// set the actual state of the comment
				$db->setQuery(
					'UPDATE `#__social_comments`' .
					' SET `published` = '.(int)$state .
					' WHERE `id` = '.(int)$id
				);
			}

			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
}