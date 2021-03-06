<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Cache Model
 *
 * @package		Joomla.Administrator
 * @subpackage	Cache
 * @since		1.6
 */
class CacheModelCache extends JModel
{
	/**
	 * An Array of CacheItems indexed by cache group ID
	 *
	 * @var Array
	 */
	protected $_data = array();

	/**
	 * Group total
	 *
	 * @var integer
	 */
	protected $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	protected $_pagination = null;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = &JFactory::getApplication();

		$clientId = JRequest::getInt('client', 0);
		$this->setState('clientId', $clientId == 1 ? 1 : 0);

		$client	= &JApplicationHelper::getClientInfo($clientId);
		$this->setState('client', $client);

		$this->setState('path', $client->path.DS.'cache');

		$context	= 'com_cache.cache.';

		$start = $app->getUserStateFromRequest($context.'list.start', 'limitstart', 0, 'int');
		$limit = $app->getUserStateFromRequest($context.'list.limit', 'limit', $app->getCfg('list_limit', 20), 'int');

		$this->setState('list.start', $start);
		$this->setState('list.limit', $limit);
	}


	/**
	 * Method to get cache data
	 *
	 * @return array
	 */
	public function getData()
	{
		if (empty($this->_data)) {
		    $conf =& JFactory::getConfig();
            $storage = $conf->get('cache_handler', 'file');
			$cache = &JFactory::getCache('', 'callback', $storage);
			$data = $cache->getAll();
			if ($data != false) {$this->_data = $data;} else {$this->_data = array();}
		}

		return $this->_data;
	}

	/**
	 * Method to get client data
	 *
	 * @return array
	 */
	public function getClient()
	{
		return $this->getState('client');
	}

	/**
	 * Get the number of current Cache Groups
	 *
	 * @return int
	 */
	public function getTotal()
	{
		if (empty($this->_total)) {
			$this->_total = count($this->getData());
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the cache
	 *
	 * @return integer
	 */
	public function getPagination()
	{
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('list.start'), $this->getState('list.limit'));
		}

		return $this->_pagination;
	}

	/**
	 * Clean out a cache group as named by param.
	 * If no param is passed clean all cache groups.
	 *
	 * @param String $group
	 */
	public function clean($group = '')
	{   $conf =& JFactory::getConfig();
        $storage = $conf->get('cache_handler', 'file');
		$cache = &JFactory::getCache('', 'callback', $storage);
		$cache->clean($group);
	}

	public function cleanlist($array)
	{
		foreach ($array as $group) {
			$this->clean($group);
		}
	}

	public function purge()
	{
		$cache = &JFactory::getCache('');
		return $cache->gc();
	}
}

