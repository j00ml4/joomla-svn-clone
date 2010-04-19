<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');

// Add the Social table path to the JTable include paths.
JTable::addIncludePath(JPATH_SITE.'/components/com_social/tables');

/**
 * Base controller class for Social.
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @since		1.6
 */
class SocialController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function display()
	{
		// Get the document object.
		$document = JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName		= JRequest::getWord('view', 'comments');
		$vFormat	= $document->getType();
		$lName		= JRequest::getWord('layout', 'default');

		// Check to make sure a valid request was made.
		$app	= JFactory::getApplication('site');
		$params	= $app->getParams('com_social');
		if ((($vFormat != 'feed') || ($vName != 'comments')) || !$params->get('enable_comment_feeds', 1)) {
			JError::raiseError(404, 'Resource Not Found');
			return false;
		}

		// Get the view and display it.
		if ($view = $this->getView($vName, $vFormat))
		{
			switch ($vName)
			{
				default:
					$model = $this->getModel($vName);
					break;
			}

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}

	function getCaptcha()
	{
		// Get the current application object.
		$app = JFactory::getApplication();

		// Build and set the built-in CAPTCHA test
		jimport('joomla.captcha.captcha');
		$captcha = JCaptcha::getInstance('image', array('direct' => true));

		$id = JRequest::getCmd('x');
		if (empty($id)) {


			$app->close();
		}

		// Test and initialize the CAPTCHA object.
		if (!$captcha->test() or !$captcha->initialize())
		{
			// either the test failed or the object could not initialize, raise an error and return

			$app->close();
		}

		// output the CAPTCHA image
		$output = $captcha->create($id);
		if (!$output) {
			// couldn't generate the CAPTCHA image

			$app->close();
		}
	}
}