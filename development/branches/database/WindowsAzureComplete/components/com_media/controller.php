<?php
/**
 * @version		$Id: controller.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_media
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Media Manager Component Controller
 *
 * @package		Joomla.Site
 * @subpackage	com_media
 * @version 1.5
 */
class MediaController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		JPluginHelper::importPlugin('content');
		$vName = JRequest::getCmd('view', 'images');

		switch ($vName)
		{
			case 'images':
				$vLayout = JRequest::getCmd('layout', 'default');
				if(JFactory::isCloudEnabled())
					$mName = 'azuremanager';
				else
					$mName = 'manager';

				break;

			case 'imagesList':
				if(JFactory::isCloudEnabled())
					$mName = 'azurelist';
				else
					$mName = 'list';
				$vLayout = JRequest::getCmd('layout', 'default');

				break;

			case 'mediaList':
				$app	= JFactory::getApplication();
				if(JFactory::isCloudEnabled())
					$mName = 'azurelist';
				else
					$mName = 'list';
				$vLayout = $app->getUserStateFromRequest('media.list.layout', 'layout', 'thumbs', 'word');

				break;

			case 'media':
			default:
				$vName = 'media';
				$vLayout = JRequest::getCmd('layout', 'default');
				if(JFactory::isCloudEnabled())
					$mName = 'azuremanager';
				else
					$mName = 'manager';
				break;
		}
		

		$document = JFactory::getDocument();
		$vType		= $document->getType();

		// Get/Create the view
		$view = $this->getView($vName, $vType);
		$view->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.'/views/'.strtolower($vName).'/tmpl');

		// Get/Create the model
		if ($model = $this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		
	if($mName == 'azuremanager')
			$model->syncLocaltoAzure();
			
		//Enable Azure plugin if windows azure
		if(JFactory::isCloudEnabled()) {
			$db = JFactory::getDBO();
			$db->setQuery("update #__extensions set enabled=1 where extension_id=436");
			if (!$db->query()) {
				JError::raiseWarning(500,'Could not update the azure plugin- enable manually from "Plugin Manager"');
			}
		}
		// Set the layout
		$view->setLayout($vLayout);

		// Display the view
		$view->display();

		return $this;
	}

	function ftpValidate()
	{
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
	}
}
