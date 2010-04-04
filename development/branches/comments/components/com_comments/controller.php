<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');

// Add the JXtended Comments table path to the JTable include paths.
JTable::addIncludePath(JPATH_SITE.'/components/com_comments/tables');

/**
 * Base controller class for JXtended Comments.
 *
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @since		1.0
 */
class CommentsController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function display()
	{
		// Check for the JXtended Libraries.
		if (!function_exists('jximport')) {
			JError::raiseWarning(500, JText::_('JX_Libraries_Missing'));
			return;
		}
		elseif (version_compare(JXVERSION,'1.1.0', '<')) {
			JError::raiseWarning(500, JText::sprintf('JX_Libraries_Outdated', '1.1.0'));
			return;
		}

		// Get the document object.
		$document = &JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName		= JRequest::getWord('view', 'comments');
		$vFormat	= $document->getType();
		$lName		= JRequest::getWord('layout', 'default');

		// Check to make sure a valid request was made.
		$app	= JFactory::getApplication('site');
		$params	= $app->getParams('com_comments');
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
		jx('jx.captcha.captcha');
		$captcha = JXCaptcha::getInstance('image', array('direct' => true));

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