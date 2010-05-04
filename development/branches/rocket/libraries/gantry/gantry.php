<?php
/**
 * @package		gantry
 * @version		${project.version} ${build_date}
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - ${copyright_year} RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

defined('JPATH_BASE') or die();

if (!defined('GANTRY_VERSION')) {
    /**
     * @global Gantry $gantry
     */
    global $gantry;
    
    /**
     * @name GANTRY_VERSION
     */
    define('GANTRY_VERSION', '${project.version}');

    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }

    /**
     * @param  string $path the gantry path to the class to import
     * @return 
     */
    function gantry_import($path) {
        require_once (realpath(dirname(__FILE__)) . '/core/gantryloader.class.php');
        return GantryLoader::import($path);
    }

    gantry_import('core.gantrysingleton');
    gantry_import('core.gantry');
    jimport('joomla.html.parameter');

    $site = JFactory::getApplication();
    $template = $site->getTemplate();
    $template_params = null;

    $mainframe = JFactory::getApplication();

    if (!$mainframe->isAdmin()) {
        if (is_readable( JPATH_SITE.DS."templates".DS.$template.DS.'params.ini' ) )
		{
			$content = file_get_contents(JPATH_SITE.DS."templates".DS.$template.DS.'params.ini');
			$template_params = new JParameter($content);
		}
        $conf = & JFactory :: getConfig();
    }

    if (!$mainframe->isAdmin() && !empty($template_params) && ($template_params->get("cache-enabled", 0) == 1)) {
        $user = & JFactory :: getUser();
        $cache = & JFactory :: getCache('Gantry');
        $cache->setCaching(true);
        $cache->setLifeTime($template_params->get("cache-time", $conf->getValue('config.cachetime') * 60));
        $gantry = $cache->get(array('GantrySingleton','getInstance'), array('Gantry'), 'Gantry-'.$template."-".$user->get('aid', 0));
    } else {
        $gantry = GantrySingleton :: getInstance('Gantry');
    }

    if (!$gantry->isAdmin()){
        $gantry->init();
    }
}