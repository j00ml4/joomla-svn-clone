<?php
/**
 * Simple REST Application
 * 
 * This rest application will recive an knowledgement query and response result.
 * 
 * @todo Contruct an extension to configure this REST application
 * @todo Extensions will use this same application to resquest your own rest stuff too
 * @version 0.1
 */

// Set flag that this is a parent file
define('_JEXEC', 1);
define('JPATH_BASE', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
require_once JPATH_BASE.DS.'includes'.DS.'framework.php';
require_once JPATH_BASE.DS.'includes'.DS.'helper.php';

//Set the application information
jimport( 'joomla.application.helper' );
$client = array(
	'id' => 3,
	'name' => 'service',
	'path' => JPATH_SERVICE
);
JApplicationHelper::addClientInfo($client);

// Instantiate the application.
$app = &JFactory::getApplication('Service');

// Initialise the application.
$app->initialise();

// Render the application.
$app->render();

// Return the response.
echo $app;