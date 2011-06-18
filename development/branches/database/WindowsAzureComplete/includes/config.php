<?php
/**
 * @version		$Id: router.php 20757 2011-02-18 04:38:02Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	Application
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * Class to create and parse routes for the site application
 *
 * @package		Joomla.Site
 * @subpackage	Application
 * @since		1.5
 */
require_once ( JPATH_ROOT.'/libraries/joomla/factory.php' );

function azure_getconfiguration($value){
$db = JFactory::getDBO();
$query = "select config from #__configuration";
$db->setQuery($query);
if($db->loadResult()!="")
{		if (!$db->query()) {
			JError::raiseError( 500, $db->stderr());
		}
		else{
		$config_values = unserialize($db->loadResult());
		}
		return $config_values->getValue($value);
}
elseif($db->loadResult() == ""){
if (file_exists(JPATH_INSTALLATION.'/index.php')) {
				header('Location: '.substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'index.php')).'installation/index.php');
				exit();
			}
}
}
?>