<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Comment table object for Social
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @since		1.6
 */
class SocialTableComment extends JTable
{
	/**
	 * Constructor
	 *
	 * @param	object	Database object
	 * @return	void
	 * @since	1.6
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__social_comments', 'id', $db);
	}

	/**
	 * Method to check the current record to save
	 *
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	public function check()
	{
		// Get the Social configuration object.
		$config = JComponentHelper::getParams('com_social');

		// Import library dependencies.
		jimport('joomla.mail.helper');
		jimport('joomla.filter.input');

		// Validate the comment data.
		$result	= false;

		if ($this->trackback)
		{
			if (empty($this->thread_id)) {
				$this->setError('SOCIAL_Trackback_Thread_Empty');
			} else if (empty($this->subject)) {
				$this->setError('SOCIAL_Trackback_Subject_Is_Empty');
			} else if (empty($this->url) || JFilterInput::checkAttribute(array('href', $this->url))) {
				$this->setError('SOCIAL_Trackback_URL_Invalid');
			} else {
				$result = true;
			}
		}
		else
		{
			if (empty($this->thread_id)) {
				$this->setError('SOCIAL_Comment_Thread_Empty');
			} else if (empty($this->name)) {
				$this->setError('SOCIAL_Comment_Name_Is_Empty');
			//} else if (strlen($this->body) < $config->get('minlength')) {
			//	$this->setError('SOCIAL_Comment_Is_Too_Short');
			//} else if (strlen($this->body) > $config->get('maxlength')) {
			//	$this->setError('SOCIAL_Comment_Is_Too_Long');
			} else if (!JMailHelper::isEmailAddress($this->email)) {
				$this->setError('SOCIAL_Comment_Email_Invalid');
			} else if ($this->url && JFilterInput::checkAttribute(array('href', $this->url))) {
				$this->setError('SOCIAL_Comment_URL_Invalid');
			} else {
				$result = true;
			}
		}

		// Check for URI scheme on webpage.
		if (strlen($this->url) > 0 && (!(eregi('http://', $this->url) or (eregi('https://', $this->url)) or (eregi('ftp://', $this->url))))) {
			$this->url = 'http://'.$this->url;
		}

		// Clean the various mail fields.
		$this->subject	= JMailHelper::cleanSubject($this->subject);
		$this->email	= JMailHelper::cleanAddress($this->email);
		$this->body		= JMailHelper::cleanBody($this->body);

		// Strip out bad words.
		$badWords		= explode(',', $config->get('censorwords'));
		$this->subject	= str_replace($badWords, '', $this->subject);
		$this->name		= str_replace($badWords, '', $this->name);
		$this->url		= str_replace($badWords, '', $this->url);
		$this->email	= str_replace($badWords, '', $this->email);
		$this->body		= str_replace($badWords, '', $this->body);

		return $result;
	}
}
