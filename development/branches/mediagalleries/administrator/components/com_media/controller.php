<?php
/**
 * Controller for mediagalleries Component
 * 
 * @package  			mediagalleries Suite
 * @subpackage 	Components
 * @link 				http://3den.org
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/** TODO
 * media Controller
 *
 * @package		Joomla
 * @subpackage	media
 * @since 1.5
 */
class MediaController extends JController
{
	protected $default_view = 'images';
	
	/**
	 * Method to display a view.
	 *
	 * @since	1.6
	 */
	function display()
	{
		$vName = JRequest::getCmd('view', 'images');
		switch ($vName)
		{
			case 'images':
				$vLayout = JRequest::getCmd('layout', 'default');
				$mName = 'manager';

				break;

			case 'imagesList':
				$mName = 'list';
				$vLayout = JRequest::getCmd('layout', 'default');

				break;

			case 'mediaList':
				$app	= JFactory::getApplication();
				$mName = 'list';
				$vLayout = $app->getUserStateFromRequest('media.list.layout', 'layout', 'thumbs', 'word');

				break;

			case 'media':
			default:
				$vName = 'media';
				$vLayout = JRequest::getCmd('layout', 'default');
				$mName = 'media';
				break;
		}

		$document = JFactory::getDocument();
		$vType		= $document->getType();

		// Get/Create the view
		$view = $this->getView($vName, $vType);

		// Get/Create the model
		if ($model = $this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}

		// Set the layout
		$view->setLayout($vLayout);

		parent::display();

		// Load the submenu.
		mediaHelper::addSubmenu(JRequest::getWord('view', $this->default_view));
	}
	
	function ftpValidate()
	{
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
	}
}