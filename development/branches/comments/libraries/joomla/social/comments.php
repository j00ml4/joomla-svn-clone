<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Comments Class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Social
 * @since		1.6
 */
class JComments
{
	/**
	 * Method to get a comment object by id.
	 *
	 * @param   int     $id     The publishing state to filter comment data over.
	 *
	 * @return  array   A list of coment objects.
	 *
	 * @since   1.6
	 */
	public function getCommentById($id)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Get a new query object.
		$query = $db->getQuery(true);

		// Setup the base query parameters to select comment by id.
		$query->select('a.*');
		$query->from('#__social_comments AS a');
		$query->where('a.id = '.(int) $id);

		// Get content item information.
		$query->select('b.page_route, b.page_title');
		$query->join('LEFT', '#__social_content AS b ON b.id = a.content_id');

		// Get registered user name information if it exists.
		$query->select('c.name AS user_full_name, c.username AS user_login_name');
		$query->join('LEFT', '#__users AS c ON c.id = a.user_id');

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';

		// Execute the query and load the object from the database.
		$db->setQuery($query);
		$comment = $db->loadObject();

		return $comment;
	}

	/**
	 * Method to get a list of comment objects by context.
	 *
	 * @param   string  $context    The content item context for which to get comment data.
	 * @param   int     $state      The publishing state to filter comment data over.
	 * @param   boolean $old2new    True to order the result list from oldest to newest, false for
	 *                              returning the results from newest to oldest.
	 * @param   int     $limit      The maximum number of comment objects to get.
	 * @param   int     $offset     The offset for which start building the list of comment objects.
	 *
	 * @return  array   A list of coment objects.
	 *
	 * @since   1.6
	 */
	public function getCommentsByContext($context, $state = 1, $old2new = true, $limit = 0, $offset = 0)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Get a new query object.
		$query = $db->getQuery(true);

		// Setup the base query parameters to select comments by context.
		$query->select('a.*');
		$query->from('#__social_comments AS a');
		$query->where('a.context = '.$db->quote($context));

		// Get content item information.
		$query->select('b.page_route, b.page_title');
		$query->join('LEFT', '#__social_content AS b ON b.id = a.content_id');

		// Get registered user name information if it exists.
		$query->select('c.name AS user_full_name, c.username AS user_login_name');
		$query->join('LEFT', '#__users AS c ON c.id = a.user_id');

		// Set the ordering clause.
		$query->order('a.created_date '.($old2new ? 'ASC' : 'DESC'));

		// If set to filter on state add the filter clause to the query.
		if ($state !== null) {
			$query->where('a.state = '.(int) $state);
		}

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';

		// Execute the query and load the list of objects from the database.
		$db->setQuery($query, $limit, $offset);
		$list = $db->loadObjectList();

		return $list;
	}

	/**
	 * @param	JRegistry
	 */
	public function getForm($params = null)
	{
		jimport('joomla.form.form');

		// Add the local form path.
		JForm::addFormPath(dirname(__FILE__).'/forms');

		// Get the form.
		try {
			$form = JForm::getInstance('social.comment', 'comment', array('control' => 'jform'), false);

			// Allow for additional modification of the form, and events to be triggered.

			// Get the dispatcher.
			$dispatcher	= JDispatcher::getInstance();

			// Trigger the form preparation event.
			$results = $dispatcher->trigger('onPrepareForm', array($form->getName(), $form));

			// Check for errors encountered while preparing the form.
			if (count($results) && in_array(false, $results, true)) {
				// Get the last error.
				$error = $dispatcher->getError();

				// Convert to a JException if necessary.
				if (!JError::isError($error)) {
					throw new Exception($error);
				}
			}

			if ($params instanceof JRegistry) {
				$uri = JFactory::getUri();
				$data = array(
					'context'	=> $params->get('context'),
					'redirect'	=> base64_encode($uri->toString(array('path', 'query', 'fragment'))),
					'subject'	=> $params->get('title')
				);
				$form->bind($data);
			}

			return $form;

		} catch (Exception $e) {
			JError::raiseWarning(500, $e->getMessage());
			return false;
		}

	}

	/**
	 * Method to get the total number of comments by context.
	 *
	 * @param   string  $context    The content item context for which to get the number of comments.
	 * @param   int     $state      The publishing state to filter comment data over.
	 *
	 * @return  int     The total number of comments for the content item.
	 *
	 * @since   1.6
	 */
	public function getTotalByContext($context, $state = 1)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Get a new query object.
		$query = $db->getQuery(true);

		// Setup the base query parameters to select comments by context.
		$query->select('COUNT(a.id)');
		$query->from('#__social_comments AS a');
		$query->where('a.context = '.$db->quote($context));

		// If set to filter on state add the filter clause to the query.
		if ($state !== null) {
			$query->where('a.state = '.(int) $state);
		}

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';

		// Execute the query and load the list of objects from the database.
		$db->setQuery($query);
		$total = (int) $db->loadResult();

		return $total;
	}

	public function save($data)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Build a query to get data from the content item row.
		$query = $db->getQuery(true);
		$query->select('a.id, a.component');
		$query->from('#__social_content AS a');
		$query->where('a.context = '.$db->quote($data['context']));

		// Execute the query and load the object from the database.
		$db->setQuery($query);
		$content = $db->loadObject();

		// Make sure a content item exists to rate.
		if (!$content) {
			// Throw error
			return false;
		}

		// Get the user's IP address... first checking for proxy information.
		$userIp = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

		// Check to see if we are submitting a new comment or editing an old one.
		if (!empty($data['id'])) {

			// Build a query to determine if the comment exists.
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__social_comments');
			$query->where('(id = '.(int) $data['id']);

			// Execute the query and load the value from the database.
			$db->setQuery($query);
			$commentId = (int) $db->loadResult();

			// Make sure the comment to edit actually exists.
			if (!$commentId) {
				// Throw error.
				return false;
			}

			// For now don't allow frontend comment editing until we figure out permissions rules.
			return false;
		}
		// New comment.
		else {

			// Fire onContentSubmit event for external validation/verification/modification.

			$query = $db->getQuery(true);
			$query->insert('#__social_comments');
			$query->set('context = '.$db->quote($data['context']));
			$query->set('component = '.$db->quote($content->component));
			$query->set('content_id = '.(int) $content->id);
			$query->set('created_date = '.$db->quote(JFactory::getDate()->toMySQL(), false));
			$query->set('modified_date = '.$db->quote(JFactory::getDate()->toMySQL(), false));
			$query->set('user_id = '.(int) JFactory::getUser()->id);
			$query->set('user_ip = '.(int) ip2long($userIp));

			$query->set('state = 1');
			$query->set('trackback = 0');

			$query->set('user_name = '.$db->quote($data['name']));
			$query->set('user_link = '.$db->quote($data['url']));
			$query->set('user_email = '.$db->quote($data['email']));
			$query->set('user_notify = 0');

			$query->set('subject = '.$db->quote($data['subject']));
			$query->set('body = '.$db->quote($data['body']));

//			$query->set('score = 0');
//			$query->set('score_like = 0');
//			$query->set('score_dislike = 0');

			$db->setQuery($query);
			$db->query();

			if ($db->getErrorNum()) {
				// Throw error.
				return false;
			}

			$commentId = (int) $db->insertid();


			// Send out moderation queue email as necessary.
		}

		// Send out notification emails as necessary.

		// Update the cumulative comment data for the content item.
		if (!self::updateCumulativeByContext($data['context'])) {
			// Throw error.
			return false;
		}

		return $commentId;
	}

	public function delete()
	{
		// Attempt to delete comment -- updating the social_content table as well.
	}

	public function setState()
	{
		// Check to see if we are performing moderation (coming from defer state).

		// If moderating, fire onContentModerate event for external notification/verification.

		// Attempt to save comment state -- updating the social_content table as well.

		// Send out notification emails as necessary.
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param	object		$form		The form to validate against.
	 * @param	array		$data		The data to validate.
	 * @return	mixed		Array of filtered data if valid, false otherwise.
	 * @since	1.1
	 */
	function validate($form, $data)
	{
		// Filter and validate the form data.
		$data	= $form->filter($data);
		$return	= $form->validate($data);

		// Check for an error.
		if (JError::isError($return)) {
			$this->setError($return->getMessage());
			return false;
		}

		// Check the validation results.
		if ($return === false) {
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message) {
				$this->setError($message);
			}

			return false;
		}

		return $data;
	}

	/**
	 * Method to update the cumulative aggregate comment data for the content item based on context.
	 *
	 * @param   string  $context    The context of the content item for which to update.
	 *
	 * @return  boolean True on success.
	 *
	 * @since   1.6
	 */
	protected function updateCumulativeByContext($context)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Build a query to get the cumulative data for the content item comments.
		$query = $db->getQuery(true);
		$query->select('COUNT(a.id) AS count');
		$query->from('#__social_comments AS a');
		$query->where('a.context = '.$db->quote($context));
		$query->where('a.state = 1');

		// Execute the query and load the object from the database.
		$db->setQuery($query);
		$cumulative = (int) $db->loadResult();

		// Update the cumulative comment data in the content row.
		$query = $db->getQuery(true);
		$query->update('#__social_content');
		$query->set('comment_count = '.(int) $cumulative);
		$query->where('context = '.$db->quote($context));

		$db->setQuery($query);
		$db->query();

		if ($db->getErrorNum()) {
			// Throw error.
			return false;
		}

		return true;
	}
}
