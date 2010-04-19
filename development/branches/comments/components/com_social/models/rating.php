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

/**
 * The Social rating model
 *
 * @package		Joomla.Site
 * @since		1.6
 */
class SocialModelRating extends JModel
{
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
		$user	= JFactory::getUser();
		$userId	= $user->get('id');
		$config = $this->getState('config');

		// Get a Rating table object
		$table = $this->getTable('Rating', 'SocialTable');
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
		$user	= JFactory::getUser();
		$uid	= (int)$user->get('id');
		$config	= $this->getState('config');

		// Check if rating is enabled.
		if ($config->get('enable_ratings') == 0) {
			$this->setError(JText::_('SOCIAL_Rating_Disabled'));
			return false;
		}

		// Get the block helper.
		require_once JPATH_SITE.'/components/com_social/helpers/blocked.php';

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

		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('pscore, pscore_count, pscore_total, thread_id');
		$query->from('#__social_ratings');
		$query->where('thread_id = '.(int) $tId);

		$rating = $db->setQuery($query)->loadObject();

		// Check for errors.
		if ($error = $db->getErrorMsg()) {
			$this->setError($error);
			return $false;
		}

		if (empty($rating)) {
			$rating = new stdClass;
			$rating->pscore_count = 0;
			$rating->pscore = 0;
			$rating->pscore_total = 0;
			$rating->thread_id = $tId;
		}

		return $rating;
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
		// load the component configuration parameters.
		$this->setState('config', JComponentHelper::getParams('com_social'));

		$this->setState('thread.id', JRequest::getInt('thread_id'));
	}
}