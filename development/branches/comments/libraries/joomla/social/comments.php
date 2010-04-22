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
		$query->select('a.id');
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
		// Initialize variables.
		$isNew = true;

		// Check to see if we are submitting a new comment or editing an old one.
		if (!empty($data['id'])) {

		}



		var_dump($data);

		// If new, fire onContentSubmit event for external validation/verification/modification.

		// Attempt to save comment -- updating the social_content table as well.

		// Send out moderation queue email as necessary.

		// Send out notification emails as necessary.
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
}
