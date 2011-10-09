<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	Application
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * Class to create and parse routes for a component
 *
 * @package		Joomla.Site
 * @subpackage	Application
 * @since		1.5
 */
class JComponentRouter implements JComponentRouterInterface
{	
	/**
	 * Array of buildrules
	 * 
	 * @var array
	 */
	protected $buildrules = array();
	
	/**
	 * Array of parserules
	 * 
	 * @var array
	 */
	protected $parserules = array();
	
	/**
	 * Name of the Router
	 * 
	 * @var string
	 */
	protected $name;
	
	/**
	 * Views of the component
	 * 
	 * @var array
	 */
	protected $views = array();
	
	/**
	 * Mapping names with actual views
	 * 
	 * @var array
	 */
	protected $view_map = array();
	
	/**
	 * Lookup-table for menu items
	 * 
	 * @var array
	 */
	protected $lookup = array();
	
	/**
	 * Constructor for JComponentRouter
	 */
	function __construct()
	{
		$app = JFactory::getApplication();
		$this->attachBuildRule(array($this, 'findItemid'));
		$app->triggerEvent('onComponentRouterRules', array($this));
		
		// Prepare the reverse lookup array.
		$menus = $app->getMenu();
		$component	= JComponentHelper::getComponent('com_'.$this->getName(true));
		$items		= $menus->getItems('component_id', $component->id);
		$views		= $this->getViews();
		foreach ($items as $item)
		{
			if (isset($item->query['view']))
			{
				$view = $item->query['view'];
				if (!isset($this->lookup[$view])) {
					$this->lookup[$view] = array();
				}
				$map = $this->view_map[$view][0];
				if ($views[$map]->id && isset($item->query[$views[$map]->id])) {
					$this->lookup[$view][$item->query[$views[$map]->id]] = $item->id;
				} else {
					$this->lookup[$view] = $item->id;
				}
			}
		}	
	}
	
	/**
	 * Register the views of a component
	 * 
	 * @param string $name Internal name of the view. Has to be unique for the component
	 * @param string $view Identifier of the view
	 * @param string $id Identifier of the ID variable used to identify the primary content item of this view 
	 * @param string $parent Internal name of the parent view
	 * @param string $parent_id Identifier of the ID variable used to identify the content item of the parent view
	 * @param bool $nestable Is this view nestable?
	 * @param string $layouts Layout to use for this view by default
	 * 
	 * @return void
	 * 
	 * @since 11.3
	 */
	function register($name, $view, $id = false, $parent = false, $parent_id = false, $nestable = false, $layout = 'default')
	{
		$viewobj = new stdClass();
		$viewobj->view = $view;
		$viewobj->name = $name;
		$viewobj->id = $id;
		if($parent) {
			$viewobj->parent = $this->views[$parent];
			$this->views[$parent]->children[] = &$viewobj;
			$viewobj->path = $this->views[$parent]->path;
		} else {
			$viewobj->parent = false;
			$viewobj->path = array();
		}
		$viewobj->path[] = $name;
		$viewobj->child_id = false;
		$viewobj->parent_id = $parent_id;
		if($parent_id) {
			$this->views[$parent]->child_id = $parent_id;
		}
		$viewobj->nestable = $nestable;
		$viewobj->layout = $layout;
		
		$this->views[$name] = $viewobj;
		if(!isset($this->view_map[$view]))
		{
			$this->view_map[$view] = array();
		}
		$this->view_map[$view][] = $name; 
	}
	
	/**
	 * Return an array of registered view objects
	 * 
	 * @return array Array of registered view objects
	 * 
	 * @since 11.3
	 */
	function getViews()
	{
		return $this->views;
	}
	
	/**
	 * Return the map of views to names of the registered view objects
	 * 
	 * @return array Array of names
	 * 
	 * @since 11.3
	 */
	function getViewMap($view = false)
	{
		if($view && isset($this->view_map[$view])) {
			return $this->view_map[$view];
		}
		return $this->view_map;
	}
	
	/**
	 * Get the path of views from target view to root view 
	 * including content items of a nestable view
	 * 
	 * @param array $query Array of query elements
	 * 
	 * @return array List of views including IDs of content items
	 */
	function getPath($query)
	{
		$views = $this->getViews();
		$result = array();
		$id = false;
		if(isset($query['view']) && $this->view_map[$query['view']]) {
			$view = $query['view'];
			if(isset($query['layout'])) {
				$layout = $query['layout'];
			} else {
				$layout = 'default';
			}
			foreach($this->view_map[$view] as $name)
			{
				if($layout == $this->views[$name]->layout) {
					$viewobj = $this->views[$name];
					break;
				}
			}
		}
		if(isset($viewobj)) {
			$path = array_reverse($viewobj->path);
			
			$view = $views[array_shift($path)];
			$id = $view->id;
			foreach($path as $element) {
				if($id && isset($query[$id])) {
					$result[$view->name] = array($query[$id]);
					if($view->nestable) {
						$nestable = call_user_func_array(array($this, 'get'.ucfirst($view->name)), array($query[$id]));
						if($nestable) {
							$result[$view->name] = array_reverse($nestable->getPath());
						}			
					}
				} else {
					$result[$view->name] = true;
				}
				$view = $views[$element];
				$id = $view->child_id;
			}
		}
		return $result;
	}
	
	/**
	 * Add a number of router rules to the object
	 * 
	 * @param array $rules Associative multi-dimensional array of callbacks
	 * 
	 * @return void
	 * 
	 * @since 11.3
	 */
	function setRules($rules)
	{
		foreach($rules['build'] as $rule) {
			$this->attachBuildRule($rule);
		}
		foreach($rules['parse'] as $rule) {
			$this->attachParseRule($rule);
		}
	}
	
	/**
	 * Attach a build rule
	 *
	 * @param	callback	The function to be called.
	 * @param	position	The position where this 
	 * 						function is supposed to be executed.
	 * 						Valid values: 'first', 'last'
	 */
	public function attachBuildRule($callback, $position = 'last')
	{
		if($position == 'last')	{
			$this->buildrules[] = $callback;
		} elseif ($position == 'first') {
			array_unshift($this->buildrules, $callback);
		}
	}

	/**
	 * Attach a parse rule
	 *
	 * @param	callback	The function to be called.
	 * @param	position	The position where this 
	 * 						function is supposed to be executed.
	 * 						Valid values: 'first', 'last'
	 */
	public function attachParseRule($callback, $position = 'last')
	{
		if($position == 'last')	{
			$this->parserules[] = $callback;
		} elseif ($position == 'first') {
			array_unshift($this->parserules, $callback);
		}
	}
	
	/**
	 * Build method for URLs
	 * 
	 * @param array $query Array of query elements
	 * 
	 * @return array Array of URL segments
	 */
	function build(&$query)
	{
		$segments = array();
		// Process the parsed variables based on custom defined rules
		foreach($this->buildrules as $rule) {
			call_user_func_array($rule, array(&$this, &$query, &$segments));
		}
		return $segments;
	}
	
	/**
	 * Parse method for URLs
	 * 
	 * @param array $segments Array of URL string-segments
	 * 
	 * @return array Associative array of query values
	 */
	function parse(&$segments)
	{
		$vars = array();
		// Process the parsed variables based on custom defined rules
		foreach($this->parserules as $rule) {
			call_user_func_array($rule, array(&$this, &$segments, &$vars));
		}
		return $vars;
	}
	
	/**
	 * Method to return the name of the router
	 * 
	 * @return string Name of the router
	 * 
	 * @since 11.3
	 */
	function getName()
	{
		if (empty($this->name)) {
			$r = null;
			if (!preg_match('/(.*)Router/i', get_class($this), $r)) {
				JError::raiseError (500, 'JLIB_APPLICATION_ERROR_ROUTER_GET_NAME');
			}
			$this->name = strtolower($r[1]);
		}

		return $this->name;
	}
	
	/**
	 * Get content items of the type category
	 * This is a generic function for all components that use the JCategories
	 * system and can be overriden if necessary.
	 * 
	 * @param int $id ID of the category to load
	 * 
	 * @return JCategoryNode Category identified by $id
	 * 
	 * @since 11.3
	 */
	function getCategory($id)
	{
		jimport('joomla.application.categories');
		$category = JCategories::getInstance($this->getName())->get($id);
		return $category;
	}
	
	/**
	 * Find the correct Itemid for this URL
	 * 
	 * @param object $crouter Component-Router object
	 * @param array $query Array of query elements
	 * @param array $segments Array of segments
	 * 
	 * @return void
	 * 
	 * @since 11.3
	 */
	public function findItemid($crouter, $query, $segments)
	{
		if(isset($query['Itemid'])) {
			return $query;
		}
		
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		$needles = $this->getPath($query);

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset($this->lookup[$view]))
				{
					if(is_bool($ids)) {
						$query['Itemid'] = $this->lookup[$view];
						return;
					}
					foreach($ids as $id)
					{
						if (isset($this->lookup[$view][(int)$id])) {
							$query['Itemid'] = $this->lookup[$view][(int)$id];
							return;
						}
					}
				}
			}
		} else {
			$active = $menus->getActive();
			if ($active && $active->component == $this->getName(true)) {
				$query['Itemid'] = $active->id;
				return;
			}
		}
		return null;
	}
}

interface JComponentRouterInterface {
	/**
	 * Build method for URLs
	 * 
	 * @param array $query Array of query elements
	 * 
	 * @return array Array of URL segments
	 */
	function build(&$query);
	
	/**
	 * Parse method for URLs
	 * 
	 * @param array $segments Array of URL string-segments
	 * 
	 * @return array Associative array of query values
	 */
	function parse(&$segments);
}