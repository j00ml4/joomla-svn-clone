<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	com_social
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

jimport('joomla.application.component.model');

/**
 * The JXtended Social comment model
 *
 * @package		JXtended.Comments
 * @version	1.0
 */
class SocialModelComment extends JModel
{
	/**
	 * Flag to indicate model state initialization.
	 *
	 * @var		boolean
	 */
	protected $__state_set		= null;

	/**
	 * Container for comment data objects.
	 *
	 * @var		array
	 */
	protected $_items				= array();
	protected $_threads			= array();

	/**
	 * Container for comment total data.
	 *
	 * @var		integer
	 */
	protected $_total				= null;

	/**
	 * Container for the items whereby clause.
	 *
	 * @var		string
	 */
	protected $_whereby			= null;

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
		if (!$this->__state_set) {
			$application	= &JFactory::getApplication('site');
			$context		= 'com_social.comment.';

			// Load the component configuration parameters.
			$this->setState('config', JComponentHelper::getParams('com_social'));

			// Compute the list start offset.
			$page	= $application->getUserStateFromRequest($context.'list.page', 'comments_page', 0, 'int');
			$start	= intval(($page) ? (($page - 1) * $this->state->config->get('pagination', 0)) : 0);

			// Load the pagination information.
			$this->setState('list.direction',	$this->state->config->get('list_order', 'ASC'));
			$this->setState('list.limit',		$this->state->config->get('pagination', 0));
			$this->setState('list.start',		$start);

			// get the comment id(s) from the request
			$c_id = JRequest::getVar('c_id');
			if (is_array($c_id)) {
				jimport('joomla.utilities.arrayhelper');
				JArrayHelper::toInteger($c_id);
				$this->setState('comment.ids', $c_id);
				$this->setState('comment.id', JFilterInput::getInstance()->clean($c_id[0], 'int'));
			} else {
				$this->setState('comment.id', JFilterInput::getInstance()->clean($c_id, 'int'));
			}

			$this->setState('thread.id', JRequest::getInt('thread_id'));

			$this->__state_set = true;
		}
		return parent::getState($property,$default);
	}

	/**
	 * Method to return a comment object
	 *
	 * @param	mixed	$id	NULL to use the $_SESSION or $_REQUEST values or integer to directly declare the comment id
	 * @return	object	Comment object
	 * @since	1.6
	 */
	public function &getItem($id=null)
	{
		// get the id of the item to return.
		$id = (int) (is_null($id)) ? $this->getState('comment.id', 0) : $id;

		// if the item has not already been retrieved, get it.
		if (empty($this->_items[$id])) {

			$db = &$this->getDBO();
			$db->setQuery(
				'SELECT a.*' .
				' FROM `#__social_comments` AS a' .
				' WHERE a.`id` = '.$id
			);
			$this->_items[$id] = $db->loadObject();

			if (empty($this->_items[$id])) {
				// TODO: raise a warning of sorts or return a JException
				$this->_items[$id] = (object) array();
			}
		}
		return $this->_items[$id];
	}

	function &getThread($id=null)
	{
		// get the id of the item to return.
		$id = (int) (is_null($id)) ? $this->getState('thread.id', 0) : $id;

		$state		= $this->getState('filter.state', 1);
		$context	= $this->getState('list.context');
		$context_id	= $this->getState('list.context_id');

		$db = &$this->getDBO();
		if ($id) {
			$where = ' WHERE t.`id` = '.(int)$id;
		} else {
			$where = ' WHERE t.`context` = '.$db->Quote($context) .
				' AND t.`context_id` = '.(int)$context_id;
		}

		$db->setQuery(
			'SELECT t.*, COUNT(a.id) AS total' .
			' FROM `#__social_threads` AS t' .
			' LEFT JOIN `#__social_comments` AS a ON t.`id` = a.`thread_id`' .
			$where .
			' GROUP BY a.`thread_id`'
		);
		$thread = $db->loadObject();

		if (!$thread) {
			$thread = (object) array('context'=>$context,'context_id'=>$context_id,'total'=>0,'id'=>0,'comments'=>array());
		} else {
			$thread->comments = $this->getItems();
		}

		return $thread;
	}

	/**
	 * Method to add a comment to the database
	 *
	 * @param	array	$data	The comment row data to store in the database
	 * @return	mixed	New comment record ID on success or JException object on failure
	 * @since	1.6
	 */
	public function add($data = array())
	{
		global $mainframe;

		$result	= false;
		$user	= &JFactory::getUser();
		$userId	= $user->get('id');
		$config = $this->getState('config');

		// load a table object
		$table = &$this->getTable('Comment', 'CommentsTable');
		if (empty($table) or (JError::isError($table))) {
			return new JException(JText::_('SOCIAL_Unable_To_Load_Table'), 500);
		}

		// bind the posted data to the table object
		if (!$table->bind($data)) {
			return new JException(JText::_('SOCIAL_Unable_To_Bind_Comment_Data'), 500);
		}

		// set the moderation/publishing state
		$moderation	= $config->get('moderate_comments');
		if (($moderation == 1 and $userId == 0) or $moderation == 2) {
			$table->published = 0;
		} else {
			$table->published = 1;
		}

		/*
		 * MOOVUR/MOLLOM SECTION
		 */

		if (($table->published == 0) and ($config->get('enable_moovur')))
		{
			if(class_exists('Moovur'))
			{
				$obj = new stdclass();
				$obj->text = $table->body;
				$obj->author_name = $table->name;
				$obj->author_url = $table->url;
				$obj->author_email = $table->email;
				if ($table->user_id) {
					$obj->author_id = $table->user_id;
				}
				Moovur::checkContent($obj, 'JXtended Social', 'Post Comment');
			}
		}

		/*
		 * END OF MOOVUR/MOLLOM SECTION
		 */

		/*
		 * AKISMET SECTION
		 */

		// if the post is not set to be already published and AKISMET is enabled, lets check it
		if (($table->published == 0) and ($config->get('enable_akismet'))) {
			// get an AKISMET object
			jx('jx.webservices.akismet');
			$akismet = new JXAkismet(JURI::base(), $config->get('akismet_key'));

			// don't do anything if the API key isn't valid
			$valid = $akismet->validateAPIKey();
			if ($valid and !JError::isError($valid)) {

				// create and populate the comment object
				$comment = new JObject();
				$comment->set('author', $table->name);
				$comment->set('email', $table->email);
				$comment->set('website', $table->url);
				$comment->set('body', $table->body);
				$comment->set('permalink', $table->referer);

				// set the comment to the AKISMET handler
				$akismet->setComment($comment);

				// if AKISMET reports the post as spam, set it as such
				if ($akismet->isSpam()) {
					$table->published = 2;
				}
			}
		}

		/*
		 * END OF AKISMET SECTION
		 */

		// make sure all the comment record data is valid
		if (!$table->check()) {
			return new JException($table->getError(), 500);
		}

		/*
		 * CAPTCHA SECTION
		 */

		// do the CAPTCHA validation after the check so the user has a chance to resubmit if an error is caught
		$configCaptcha	= $config->get('captcha');
		if ($configCaptcha == 2 or ($configCaptcha == 1 and $userId == 0))
		{
			// should we be using reCAPTCHA?
			if ($config->get('enable_recaptcha')) {
				// get a reCAPTCHA object
				jx('jx.webservices.recaptcha');
				$recaptcha = JXRecaptcha::getInstance();

				// set the API keys for reCAPTCHA
				$recaptcha->setKeyPair($config->get('recaptcha_public'), $config->get('recaptcha_private'));

				// validate the captcha
				if (!$recaptcha->checkCaptcha()) {
					return new JException(JText::_('reCAPTCHA_Validation_Failed'), 403);
				}
			} else {
				// get a CAPTCHA object
				jx('jx.captcha.captcha');
				// TODO: use a better folder
				$options = array('filePath' => JPATH_SITE.DS.'tmp');
				$captcha = &JXCaptcha::getInstance('image', $options);

				// get the list of CAPTCHA tests from the session and verify that they do exist
				$captchas = $mainframe->getUserState('jxcaptcha.captcha');
				if (empty($captchas)) {
					return new JException(JText::_('Captcha_Image_Has_Expired'), 500);
				}

				// get the post fields from the request
				$post = JRequest::get('post');

				// iterate over the CAPTCHA tests from the session to validate them
				foreach ($captchas as $item)
				{
					// make sure that something was posted for this CAPTCHA test
					if (isset($post[$item['id']])) {
						// make sure that the posted value passes CAPTCHA validation for the test
						if (!$captcha->validate($item['id'], $post[$item['id']], false)) {
							return new JException(JText::_('Captcha_Validation_Failed'), 403);
						}
					}
				}
				$captcha->clean();
			}
		}

		/*
		 * END OF CAPTCHA SECTION
		 */

		// save the data
		if (!$table->save($data)) {
			$result	= new JException($table->getError(), 500);
		} else {
			$result = $table->id;
		}

		return $result;
	}

	/**
	 * Method to add a trackback to the database
	 *
	 * @param	array	$data	The trackback row data to store in the database
	 * @return	mixed	New trackback record ID on success or JException object on failure
	 * @since	1.6
	 */
	public function addTrackback($data = array())
	{
		global $mainframe;

		$result	= false;
		$user	= &JFactory::getUser();
		$userId	= $user->get('id');
		$config = $this->getState('config');

		// load a table object
		$table = &$this->getTable('Comment', 'CommentsTable');
		if (empty($table) or (JError::isError($table))) {
			return new JException(JText::_('SOCIAL_Unable_To_Load_Table'), 500);
		}

		// bind the posted data to the table object
		if (!$table->bind($data)) {
			return new JException(JText::_('SOCIAL_Unable_To_Bind_Comment_Data'), 500);
		}

		// set the moderation/publishing state
		$moderation	= $config->get('moderate_comments');
		if (($moderation == 1 and $userId == 0) or $moderation == 2) {
			$table->published = 0;
		} else {
			$table->published = 1;
		}


		// make sure all the comment record data is valid
		if (!$table->check()) {
			return new JException($table->getError(), 500);
		}


		// save the data
		if (!$table->save($data)) {
			$result	= new JException($table->getError(), 500);
		} else {
			$result = $table->id;
		}

		return $result;
	}

	/**
	 * Method to send a notification email about a comment being posted
	 *
	 * @param	object	$item	The comment record notify about
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	public function sendCommentNotification($item)
	{
		// get the application object and component configuration object
		$application = &JFactory::getApplication();
		$config = &JComponentHelper::getParams('com_social');

		// wrap the comment record object in a JObject
		$comment = new JObject();
		$comment->setProperties($item);

		// generate the email from a template and the comment object
		jx('jx.utilities.template');
		$template = new JXTemplate();
		$template->setBody(
<<<EOC
Date:		{CREATED_DATE}
Page:		{ROUTE}

From:		{NAME}
URL:		{URL}
Email:		{EMAIL}
IP Address:	{ADDRESS}

Message:

{BODY}

EOC
);
		$template->mergeObject($comment);

		// setup the email fields
		$mailfrom	= $application->getCfg('mailfrom');
		$fromname	= $application->getCfg('fromname');

		$subject	= html_entity_decode('New comment at '.$application->getCfg('sitename'), ENT_QUOTES);
		$message	= html_entity_decode($template->getBody(), ENT_QUOTES);
		$email		= $config->get('emailto');

		// Send the mail
		return JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);
	}

	/**
	 * Method to get a comment rendered out as if in the module
	 *
	 * @param	object	$item	The comment record to render
	 * @return	string	Rendered output of a comment
	 * @since	1.6
	 */
	public function getRenderedComment($item)
	{
		// Get the active template.
		$app	= &JFactory::getApplication();
		$tmpl	= $app->getTemplate();

		// Get the module layout.
		jimport('joomla.application.module.helper');
		$helper	= JPATH_SITE.DS.'modules'.DS.'mod_comments_comment'.DS.'helper.php';
		$override = JPATH_SITE.DS.'templates'.DS.$tmpl.DS.'html'.DS.'mod_comments_comment'.DS.'default_comment.php';

		if (file_exists($override)) {
			$layout = $override;
		} else {
			$layout = JModuleHelper::getLayoutPath('mod_comments_comment', 'default_comment');
		}

		if (file_exists($helper) && file_exists($layout))
		{
			$return	= '';
			$params	= $this->getState('config');

			// Load the language file.
			$lang = &JFactory::getLanguage();
			$lang->load('mod_comments_comment');

			// Include the module helper.
			require_once($helper);

			// Load the module layout into a buffer.
			ob_start();
			require_once($layout);
			$return = ob_get_contents();
			ob_end_clean();

			return $return;
		}
		else {
			// no view found, just return the comment body
			return $item->body;
		}
	}

	/**
	 * Method to check if a user has permission to comment.
	 */
	function canComment()
	{
		//
		// TODO: JUST FIRE AN EVENT
		//

		$user	= &JFactory::getUser();
		$uid	= (int)$user->get('id');
		$config	= $this->getState('config');

		// Check comments enabled
		if ($config->get('enable_comments') == 0) {
			$this->setError(JText::_('SOCIAL_Disabled'));
			return false;
		}

		// Get the block helper.
		require_once(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'helpers'.DS.'blocked.php');

		// Check if the user Id is blocked.
		if (CommentHelper::isBlockedUser($config)) {
			$this->setError(JText::_('SOCIAL_Comment_Not_Allowed'));
			return false;
		}
		// Check if the IP address is blocked.
		if (CommentHelper::isBlockedIP($config)) {
			$this->setError(JText::_('SOCIAL_Comment_Not_Allowed_IP'));
			return false;
		}
		// Check if the host is blocked.
		if (CommentHelper::isBlockedHost($config)) {
			$this->setError(JText::_('SOCIAL_Comment_Not_Allowed_Host'));
			return false;
		}

		// Check guest commenting
		if ($uid == 0 && $config->get('guestcomment') == 0) {
			$this->setError(JText::_('SOCIAL_Comment_Not_Signed_In'));
			return false;
		}

		// Check flood control.
		if ($this->isCommentFlood()) {
			$this->setError(JText::_('SOCIAL_Comment_Not_Allowed_So_Soon'));
			return false;
		}

		return true;
	}
}