<?php
/**
* @version		$Id$
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Joomla! Page Cache Plugin
 *
 * @author		Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla
 * @subpackage	System
 */
class  plgCache extends JPlugin
{

	var $_cache = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object		$subject The object to observe
	 * @since	1.0
	 */
	function plgCache(& $subject)
	{
		parent::__construct($subject);

		// load plugin parameters
		$this->_plugin = & JPluginHelper::getPlugin('system', 'cache');
		$this->_params = new JParameter($this->_plugin->params);

		$this->_cache =& JFactory::getCache('page', 'page');
		$this->_cache->setCaching(true);
	}

	/**
	* Converting the site URL to fit to the HTTP request
	*
	*/
	function onAfterInitialise()
	{
		global $mainframe, $_PROFILER;

		if($mainframe->isAdmin()) {
			return;
		}

		$data  = $this->_cache->get();

		if($data !== false)
		{
			JResponse::setBody($data);
			echo JResponse::toString($mainframe->getCfg('gzip'));

			JDEBUG ? $_PROFILER->mark('afterCache') : null;

			echo implode( '', $_PROFILER->getBuffer() );
			$mainframe->close();
		}
	}

	function onAfterRender()
	{
		global $mainframe;

		if($mainframe->isAdmin()) {
			return;
		}

		$this->_cache->store();
	}
}

// Attach sef handler to event dispatcher
$dispatcher = & JEventDispatcher::getInstance();
$dispatcher->attach(new plgCache($dispatcher));