<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die();

/**
 * Social helper class
 *
 * @static
 * @package		Joomla.Framework
 * @subpackage	Social
 * @since		1.6
 */
class JSocialHelper
{
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

}