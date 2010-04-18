<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// Add the appropriate include paths for models.
jimport('joomla.application.component.model');
JModel::addIncludePath(JPATH_SITE.'/components/com_social/models');

class modSocialCommentHelper
{
	function getThread(&$params)
	{
		// Get and configure the thread model.
		$model = &JModel::getInstance('Thread', 'CommentsModel');
		$model->getState();
		$model->setState('thread.context', $params->get('context'));
		$model->setState('thread.context_id', (int)$params->get('context_id'));
		$model->setState('thread.url', $params->get('url'));
		$model->setState('thread.route', $params->get('route'));
		$model->setState('thread.title', $params->get('title'));

		// Get the thread data.
		$thread = &$model->getThread();

		if ($thread) {
			$params->set('thread.id', (int)$thread->id);
		}

		return $thread;
	}

	function &getComments(&$params)
	{
		$model = &JModel::getInstance('Comments', 'CommentsModel');
		$model->getState();
		$model->setState('filter.thread_id', $params->get('thread.id'));
		$model->setState('filter.state', 1);
		if (strtolower($params->get('list_order')) == 'asc') {
			$model->setState('list.ordering', 'a.created_date ASC');
		} else {
			$model->setState('list.ordering', 'a.created_date DESC');
		}
		$model->setState('list.limit', $params->get('pagination', 10));

		$comments = &$model->getItems();

		return $comments;
	}

	function &getPagination(&$params)
	{
		$model = &JModel::getInstance('Comments', 'CommentsModel');
		$model->getState();
		$model->setState('filter.thread_id', $params->get('thread.id'));
		$model->setState('filter.state', 1);
		if (strtolower($params->get('list_order')) == 'asc') {
			$model->setState('list.ordering', 'a.created_date ASC');
		} else {
			$model->setState('list.ordering', 'a.created_date DESC');
		}
		$model->setState('list.limit', $params->get('pagination', 10));

		$pagination = &$model->getPagination();

		return $pagination;
	}

	/**
	 * Method to render an input string of BBCode to XHTML
	 *
	 * @static
	 * @param	string	$input	A BBCode input string
	 * @return	string	XHTML rendered version of the BBCode input string
	 * @since	1.0
	 */
	function renderBBCode($input)
	{
		// import library dependencies
		//jx('jx.html.html.bbcode');

		// Render BBcode.
		return JHtml::_('bbcode.render', $input);
	}

	function getEmoticonList($params)
	{
		// Temporarily hard coded.
		$paths = array (
			'smiley_path' => JPATH_ROOT.'/media/jxtended/img/smilies/default',
			'smiley_url' => JURI::base().'media/jxtended/img/smilies/default'
		);

		$smilies = array();
		if (!empty($paths['smiley_path'])) {
			if (file_exists($paths['smiley_path'].'/manifest.xml')) {

				$parser = &JFactory::getXMLParser('simple');
				if ($parser->loadFile($paths['smiley_path'].'/manifest.xml')) {
					$icons = $parser->document->icons[0];
					foreach ($icons->children() as $icon)
					{
						if ($icon->attributes('palette') && $code = $icon->code[0]->data()) {
							$smilies[] = (object) array('name'=>$icon->attributes('name'), 'code'=>$code, 'path'=>$paths['smiley_url'].'/'.$icon->attributes('file'));
						}
					}
				}
			}
		}

		return $smilies;
	}

	function getCaptcha($params)
	{
		// initialize variables
		$user = &JFactory::getUser();
		$enableCaptcha = $params->get('captcha');
		$captcha = new JObject();

		// should captcha be enabled
		if (($enableCaptcha == 2) || (($enableCaptcha == 1) && (!$user->get('id')))) {
			$captcha->enabled = true;
			$captcha->recaptcha = false;
			$captcha->recaptchaKey = null;
			$captcha->c_id = null;

			// are we using reCAPTCHA for our captcha system?
			if ($params->get('enable_recaptcha'))
			{
				// set recaptcha use to true
				$captcha->recaptcha = true;

				// import library dependencies
				jx('jx.webservices.recaptcha');

				// build and set the reCAPTCHA test
				$recaptcha = JXRecaptcha::getInstance();

				// set the API keys for reCAPTCHA
				$recaptcha->setKeyPair($params->get('recaptcha_public'), $params->get('recaptcha_private'));
				$captcha->recaptchaKey = $params->get('recaptcha_public');

				// render the reCAPTCHA output based on if the current URI is SSL encrypted or not
				$uri = &JFactory::getURI();
				$recaptcha->injectHeadScript($uri->isSSL());
			}
			else
			{
				// import library dependencies
				jx('jx.captcha.captcha');

				// build and set the CAPTCHA test
				$options = array('direct' => true);
				$jxcaptcha = &JXCaptcha::getInstance('image', $options);

				// test and initialize the CAPTCHA object
				if (!$jxcaptcha->test() or !$jxcaptcha->initialize())
				{
					// either the test failed or the object could not initialize, raise an error and return
					JError::raiseWarning(500, $jxcaptcha->getError());
					$document = &JFactory::getDocument();
					echo $document->getBuffer('message');
					return false;
				}

				// create a CAPTCHA test and return the values
				$data = $jxcaptcha->generateId();
				$captcha->c_id = $data['id'];
			}
		} else
		{
			$captcha->enabled = false;
			$captcha->recaptcha = false;
		}

		// Only prime the document with CAPTCHA options once.
		static $loaded;
		if (empty($loaded))
		{
			$options = array('captcha' => intval((bool) $captcha->enabled));

			if ($captcha->recaptcha) {
				$options['reCaptchaPubKey'] = $captcha->recaptchaKey;
			}

			JFactory::getDocument()->addScriptDeclaration('	JX.Options.Comments = '.json_encode($options));

			$loaded = true;
		}

		return $captcha;
	}

	function isBlocked($params)
	{
		// import library dependencies
		require_once(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'helpers'.DS.'blocked.php');

		// run some tests to see if the comment submission should be blocked
		$blocked = (CommentHelper::isBlockedUser($params) or CommentHelper::isBlockedIP($params));

		return $blocked;
	}

	function loadBBCodeEditor()
	{
		// build a list of language strings to insert into the document head
		$lang = array(
			'INVALID_NAME_TXT' =>	JText::_('Comments_Comment_Name_Invalid', true),
			'INVALID_EMAIL_TXT' =>	JText::_('Comments_Comment_Email_Invalid', true),
			'INVALID_BODY_TXT' =>	JText::_('Comments_Comment_Body_Invalid', true),
			'BOLD' =>				JText::_('Comments_Bold', true),
			'ITALIC' =>				JText::_('Comments_Italic', true),
			'UNDERLINE' =>			JText::_('Comments_Underline', true),
			'STRIKETHROUGH' =>		JText::_('Comments_Strikethrough', true),
			'QUOTE' =>				JText::_('Comments_Quote', true),
			'IMAGE' =>				JText::_('Comments_Image', true),
			'ENTER_IMAGE' =>		JText::_('Comments_Enter_Image', true),
			'ENTER_LINK' =>			JText::_('Comments_Enter_Link', true),
			'LINK' =>				JText::_('Comments_Link', true),
			'LINK_TEXT' =>			JText::_('Comments_Link_Text', true),
			'ENTER_LANG' =>			JText::_('Comments_Enter_Lang', true),
			'CODE' =>				JText::_('Comments_Code', true)
		);

		// get the application object
		$app = &JFactory::getApplication();

		// merge our list of language strings into any existing ones and set them in the application object
		$lang = array_merge($app->get('jx.js', array()), $lang);
		$app->set('jx.js', $lang);
	}

	function getForceFormURL($params)
	{
		$pageURI = JURI::getInstance($params->get('route'));
		$pageURI->setVar('scf',1);

		return $pageURI->toString(array('path', 'query'));
	}
}
