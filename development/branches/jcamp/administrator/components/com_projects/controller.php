<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	Projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

defined("_JEXEC") or die("Restricted access");
jimport('joomla.application.component.controller');

class ProjectsController extends JControllerAdmin {

	public function display()
	{
		// Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName		= JRequest::getWord('view', 'config');
		$vFormat	= $document->getType();

		// Get and render the view.
		if ($view = &$this->getView($vName, $vFormat)) {
			// Get the model for the view.
			$model = &$this->getModel($vName);

			// Push the model into the view (as default).
			$view->setModel($model, true);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}

}