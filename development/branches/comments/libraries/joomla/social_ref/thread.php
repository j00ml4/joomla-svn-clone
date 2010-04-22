<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.database.databasequery');

/**
 * Thread model for the Social package.
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @since		1.6
 */
class JThread extends JModel
{
	protected $option = '-';
	protected $name = '-';

	/**
	 * Flag to indicate model state initialization.
	 *
	 * @var		boolean
	 */
	protected $__state_set		= null;

	/**
	 * Method to add a thread based on model state data.
	 *
	 * @return	mixed	Thread object on success, false on failure.
	 * @since	1.6
	 */
	protected function autoCreateThread()
	{
		$date	= JFactory::getDate();
		$db		= $this->getDbo();

		// Populate the thread data.
		$thread					= new JObject();
		$thread->id				= null;
		$thread->context		= $this->getState('thread.context');
		$thread->context_id		= (int)$this->getState('thread.context_id');
		$thread->page_url		= $this->getState('thread.url');
		$thread->page_route		= $this->getState('thread.route');
		$thread->page_title		= $this->getState('thread.title');
		$thread->status			= 1;
		$thread->pings			= '';

		// Insert the thread object.
		$db->insertObject('#__social_threads', $thread, 'id');

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return $thread;
	}

	/**
	 * Method to update a thread based on model state data.
	 *
	 * @return	mixed	Thread object on success, false on failure.
	 * @since	1.6
	 */
	protected function autoUpdateThread($thread)
	{
		$update = false;

		// Check the route.
		if (($this->getState('thread.route')) && $thread->page_route != $this->getState('thread.route')) {
			$thread->page_route = $this->getState('thread.route');
			$update = true;
		}

		// Check the title.
		if (($this->getState('thread.title')) && $thread->page_title != $this->getState('thread.title')) {
			$thread->page_title = $this->getState('thread.title');
			$update = true;
		}

		// If the thread should be updated, run the update query.
		if ($update)
		{
			// Update the thread row.
			$db->updateObject('#__social_threads', $thread, 'id');

			// Check for a database error.
			if ($db->getErrorNum()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return $thread;
	}

	/**
	 * Method to get a thread object.
	 *
	 * This method can get a thread by Id or by context and context Id.
	 *
	 * @param	int		$threadId	An optional thread Id.
	 * @return	mixed	Thread object on success, false on failure.
	 * @since	1.6
	 */
	public function &getThread($threadId = null)
	{
		// Get the thread and/or context information.
		$threadId	= (int) (is_null($threadId)) ? $this->getState('thread.id', 0) : $threadId;
		$context	= $this->getState('thread.context');
		$contextId	= $this->getState('thread.context_id');

		$false	= false;
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select all fields from the table.
		$query->select('t.*');
		$query->from('#__social_threads AS t');

		// Check if we have a thread Id to use for lookup.
		if ($threadId) {
			$query->where('t.id = '.(int)$threadId);
		} else {
			$query->where('t.context = '.$db->Quote($context));
			$query->where('t.context_id = '.(int)$contextId);
		}

		// Load the thread.
		$db->setQuery($query);
		$thread = $db->loadObject();

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return $false;
		}

		// If no thread was found, create one.
		if (!$thread) {
			// Create the thread using the state data.
			$thread = $this->autoCreateThread();

			// Check the auto create return.
			if ($thread === false) {
				return $false;
			}
		}
		// Else, check to see if it needs to be updated.
		else {
			$thread = $this->autoUpdateThread($thread);
		}

		return $thread;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app		= JFactory::getApplication('site');

		$params		= JComponentHelper::getParams('com_social');
		$this->setState('params', $params);

		$this->setState('thread.id', JRequest::getInt('thread_id'));
	}
}