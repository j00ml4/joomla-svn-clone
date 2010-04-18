<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

jimport('joomla.application.component.model');

/**
 * The Social rating model
 *
 * @package		Joomla.Site
 * @version	1.0
 */
class SocialModelRating extends JModel
{
	/**
	 * Flag to indicate model state initialization.
	 *
	 * @var		boolean
	 */
	protected $__state_set		= null;

	/**
	 * Overridden getState method to allow autopopulating of model state by the request.
	 *
	 * @param	mixed	$property	The name of the property to return from the state or NULL to return the state
	 * @param	mixed	$default	The default value to return if the property is not set
	 * @return	mixed	The value by name from the state or the state itself
	 * @since	1.6
	 */
	public function getState($property=null, $default=null)
	{
		// if the model state is uninitialized lets set some values we will need from the request.
		if (!$this->__state_set) {

			// load the component configuration parameters.
			$this->setState('config', JComponentHelper::getParams('com_social'));

			$this->setState('thread.id', JRequest::getInt('thread_id'));

			$this->__state_set = true;
		}
		return parent::getState($property,$default);
	}

	/**
	 * Method to return a rating object
	 *
	 * @param	mixed	$context_id	NULL to use model state or integer
	 * @param	mixed	$context	NULL to use model state or string
	 * @return	object	rating object
	 * @since	1.6
	 */
	public function &getItem($tId = null)
	{
		$tId	= !empty($tId) ? $tId : $this->getState('thread.id');
		$false	= false;

		// Load the rating from the database.
		JTable::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'tables');
		$rating = JTable::getInstance('Rating', 'CommentsTable');
		$return = $rating->load($tId);

		// Check for errors.
		if (!$return && $rating->getError()) {
			$this->setError($rating->getError());
			return $false;
		}

		// If no thread was found, just populate the thread id.
		if (!$rating->thread_id) {
			$rating->thread_id = $tId;
		}

		return $rating;
	}

	/**
	 * Method to add a rating to the database
	 *
	 * @param	array	$data	The rating row data to store in the database
	 * @return	mixed	New rating record ID on success or JException object on failure
	 * @since	1.6
	 */
	public function add($data = array())
	{
		$result	= false;
		$user	= &JFactory::getUser();
		$userId	= $user->get('id');
		$config = $this->getState('config');

		// is this a member comment?
		if ($userId > 0)
		{
			// get a member/rating table object
			$table = &$this->getTable('ratingmember', 'CommentsTable');
			if (empty($table) or (JError::isError($table))) {
				return new JException(JText::_('SOCIAL_Unable_To_Load_Table'), 500);
			}

			// load the row if it exists
			if ($table->load($userId, $data['thread_id'], $data['category_id'])) {
				return new JException(JText::_('SOCIAL_Item_Already_Rated'), 403);
			}

			// set the row data fields
			$table->thread_id		= $data['thread_id'];
			$table->context			= $data['context'];
			$table->context_id		= $data['context_id'];
			$table->user_id			= $userId;
			$table->category_id		= $data['category_id'];
			$table->score			= $data['score'];
			$table->address			= $_SERVER['REMOTE_ADDR'];

			// verify the table object data is valid
			if (!$table->check($config)) {
				return new JException($table->getError(), 500);
			}

			// store the table object
			if (!$table->store()) {
				return new JException($table->getError(), 500);
			}
		}

		// Get a Rating table object
		$table = &$this->getTable('Rating', 'CommentsTable');
		if (empty($table) or (JError::isError($table))) {
			return new JException(JText::_('SOCIAL_Unable_To_Load_Table'), 500);
		}

		// load the row
		$table->load($data['thread_id']);

		// Set the row data fields
		$table->context			= $data['context'];
		$table->context_id		= $data['context_id'];
		$table->pscore_total	+= $data['score'];
		$table->pscore_count	+= 1;
		$table->pscore			= $table->pscore_total / $table->pscore_count;

		// verify the table object data is valid
		if (!$table->check($config)) {
			return new JException($table->getError(), 500);
		}

		// store the table object
		if (!$table->store()) {
			return new JException($table->getError(), 500);
		}

		return true;
	}

	/**
	 * Method to check if a user has permission to rate.
	 */
	function canRate()
	{
		$user	= &JFactory::getUser();
		$uid	= (int)$user->get('id');
		$config	= $this->getState('config');

		// Check if rating is enabled.
		if ($config->get('enable_ratings') == 0) {
			$this->setError(JText::_('SOCIAL_Rating_Disabled'));
			return false;
		}

		// Get the block helper.
		require_once(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'helpers'.DS.'blocked.php');

		// Check if the user Id is blocked.
		if (CommentHelper::isBlockedUser($config)) {
			$this->setError(JText::_('SOCIAL_Rating_Not_Allowed'));
			return false;
		}
		// Check if the IP address is blocked.
		if (CommentHelper::isBlockedIP($config)) {
			$this->setError(JText::_('SOCIAL_Rating_Not_Allowed_IP'));
			return false;
		}
		// Check if the host is blocked.
		if (CommentHelper::isBlockedHost($config)) {
			$this->setError(JText::_('SOCIAL_Rating_Not_Allowed_Host'));
			return false;
		}

		// Check guest rating
		if ($uid == 0 && $config->get('guestcomment') == 0) {
			$this->setError(JText::_('SOCIAL_Rating_Not_Signed_In'));
			return false;
		}

		return true;
	}
}