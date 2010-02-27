<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.base.node');

/**
 * JCategories Class.
 *
 * @package		Joomla.Framework
 * @subpackage	Application
 * @since		1.6
 */
class JCategories
{
	/**
	 * Array to hold the object instances
	 *
	 * @param array
	 */
	static $instances = array();

	/**
	 * Array of category nodes
	 *
	 * @var mixed
	 */
	protected $_nodes = null;

	/**
	 * Name of the extension the categories belong to
	 *
	 * @var string
	 */
	protected $_extension = null;

	/**
	 * Name of the linked content table to get category content count
	 *
	 * @var string
	 */
	protected $_table = null;

	/**
	 * Name of the category field
	 *
	 * @var string
	 */
	protected $_field = null;

	/**
	 * Name of the key field
	 *
	 * @var string
	 */
	protected $_key = null;

	/**
	 * Array of options
	 *
	 * @var array
	 */
	protected $_options = null;

	/**
	 * Save the information, if a tree is loaded
	 *
	 * @var boolean
	 */
	protected $_treeloaded = false;


	/**
	 * Class constructor
	 *
	 * @access public
	 * @return boolean True on success
	 */
	public function __construct($options)
	{
		$this->_extension	= $options['extension'];
		$this->_table		= $options['table'];
		$this->_field		= (isset($options['field'])&&$options['field'])?$options['field']:'catid';
		$this->_key			= (isset($options['key'])&&$options['key'])?$options['key']:'id';
		$this->_options		= $options;
		return true;
	}

	/**
	 * Returns a reference to a JCategories object
	 *
	 * @param $extension Name of the categories extension
	 * @param $options An array of options
	 * @return object
	 */
	public static function getInstance($extension, $options = array())
	{
		if (isset(self::$instances[$extension]))
		{
			return self::$instances[$extension];
		}
		$classname = ucfirst(substr($extension,4)).'Categories';
		if (!class_exists($classname))
		{
			$path = JPATH_SITE.DS.'components'.DS.$extension.DS.'helpers'.DS.'category.php';
			if (is_file($path))
			{
				require_once $path;
			} else {
				return false;
			}
		}
		self::$instances[$extension] = new $classname($options);
		return self::$instances[$extension];
	}

	/**
	 * Loads a specific category and all its children in a JCategoryNode object
	 * @param an optional id integer or equal to 'root'
	 * @param an optional array of boolean options (all set to true by default).
	 *   'load' to force loading
	 *   'children' to get its direct children,
	 *   'parent' to get its direct parent,
	 *   'siblings' to get its siblings
	 *   'ascendants' to get its ascendants
	 *   'descentants' to get its descendants
	 *   'level-min' to get nodes from this level
	 *   'level-max' to get nodes until this level 
	 * @return JCategoryNode|null
	 */
	public function get($id='root')
	{
		if ($id != 'root')
		{
			$id = (int) $id;
			if ($id == 0)
			{
				return null;
			}
		}
		if (!isset($this->_nodes[$id]))
		{
			$this->_load($id);
		}
		
		return $this->_nodes[$id];
	}

	/**
	 * Return the root or null
	 *
	 * @return JCategoryNode|null
	 */
	public function root()
	{
		return $this->node('root');
	}

	protected function _load($id)
	{
		$db	= JFactory::getDbo();
		$user = JFactory::getUser();
		$extension = $this->_extension;
	
		$query = new JDatabaseQuery;
		
		// right join with c for category
		$query->select('c.*');
		$query->select('CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as slug');
		$query->from('#__categories as c');
		$query->where('(c.extension='.$db->Quote($extension).' OR c.extension='.$db->Quote('system').')');
		$query->where('c.access IN ('.implode(',', $user->authorisedLevels()).')');		
		$query->order('c.lft');

		//add author name and modified user
		$query->select('u.name AS author, m.name AS modified_user');
		$query->leftJoin('#__users AS u ON u.id = c.created_user_id');
		$query->leftJoin('#__users AS m ON m.id = c.modified_user_id');
		
		// s for selected id
		if ($id!='root')
		{
			// Get the selected category
			$query->leftJoin('#__categories AS s ON (s.lft <= c.lft AND s.rgt >= c.rgt) OR (s.lft > c.lft AND s.rgt < c.rgt)');
			$query->where('s.id='.(int)$id);
		}
		
		// i for item
		$query->leftJoin($db->nameQuote($this->_table).' AS i ON i.'.$db->nameQuote($this->_field).' = c.id ');
		$query->select('COUNT(i.'.$db->nameQuote($this->_key).') AS numitems');
		
		// Group by
		$query->group('c.id');

		// Get the results
		$db->setQuery($query);
		$results = $db->loadObjectList('id');
		
		if (count($results))
		{
			// foreach categories
			foreach($results as $result)
			{
				// Deal with root category
				if($result->id == 1)
				{
					$result->id = 'root';	
				}
				// Deal with parent_id
				if ($result->parent_id == 1)
				{
					$result->parent_id = 'root';
				}	
				// Create the node
				if (!isset($this->_nodes[$result->id]))
				{
					// Create the JCategoryNode
					$this->_nodes[$result->id] = new JCategoryNode($result);
				}
				
				if($result->id != 'root')
				{
					// Compute relationship between node and its parent
					$this->_nodes[$result->id]->setParent($this->_nodes[$result->parent_id]);
				}
			}
		}
		else
		{
			$this->_nodes[$id] = null;
		}
	}
}

/**
 * Helper class to load Categorytree
 * @author Hannes
 * @since 1.6
 */
class JCategoryNode extends JObject
{
	/** @var int Primary key */
	public $id					= null;
	public $asset_id			= null;
	public $parent_id			= null;
	public $lft					= null;
	public $rgt					= null;
	public $level				= null;
	public $extension			= null;
	/** @var string The menu title for the category (a short name)*/
	public $title				= null;
	/** @var string The the alias for the category*/
	public $alias				= null;
	/** @var string */
	public $description			= null;
	/** @var boolean */
	public $published			= null;
	/** @var boolean */
	public $checked_out			= 0;
	/** @var time */
	public $checked_out_time	= 0;
	/** @var int */
	public $access				= null;
	/** @var string */
	public $params				= null;
	public $metadesc			= null;
	public $metakey				= null;
	public $metadata			= null;
	public $created_user_id		= null;
	public $created_time		= null;
	public $modified_user_id	= null;
	public $modified_time		= null;
	public $hits				= null;
	public $language			= null;
	/** @var int */
	public $numitems			= null;
	/** @var string */
	public $slug				= null;
	public $assets				= null;
	public $author				= null;
	public $modified_user		= null;
	
	/**
	 * @var Parent Category
	 */
	protected $_parent = null;

	/**
	 * @var Array of Children
	 */
	protected $_children = array();
	
	/**
	 * @var Path from root to this category
	 */
	protected $_path = array();
	
	/**
	 * @var Category left of this one
	 */
	protected $_leftSibling = null;
	
	/**
	 * @var Category right of this one
	 */
	protected $_rightSibling = null;

	/**
	 * Class constructor
	 * @param $category
	 * @return unknown_type
	 */
	public function __construct($category = null)
	{
		if ($category)
		{
			$this->setProperties($category);
			return true;
		}
		return false;
	}
	/**
	 * Test if this node is the system node
	 *
	 * @return bool
	 */
	public function isSystem()
	{
		return $this->id == 'root';
	}
	
	/**
	 * Set the parent of this category
	 *
	 * If the category already has a parent, the link is unset
	 *
	 * @param JCategoryNode|null the parent to be setted
	 */
	
	function setParent(&$parent) 
	{
		if ($parent instanceof JCategoryNode || is_null($parent)) 
		{
			if (!is_null($this->_parent)) 
			{
				$this->_parent->removeChild($this->id);
			}
			if (!is_null($parent)) 
			{
				$parent->_children[$this->id] = & $this;
			}
			$this->_parent = & $parent;
			if($this->_parent->id != 'root')
			{
				$this->_path = $parent->getPath();
				$this->_path[] = $parent->id.':'.$parent->alias;
			} 
			
			if(count($parent->getChildren()) > 1)
			{
				end($parent->_children);
				$this->_leftSibling = &prev($parent->_children);
				$this->_leftSibling->setSibling(&$this);
			}
		}
	}
	
	/**
	 * Add child to this node
	 *
	 * If the child already has a parent, the link is unset
	 *
	 * @param JNode the child to be added
	 */
	function addChild(&$child) 
	{
		if ($child instanceof JCategoryNode) 
		{
			$child->setParent($this);
		}
	}

	/**
	 * Remove a specific child
	 * 
	 * @param int	ID of a category 
	 */
	function removeChild($id)
	{
		unset($this->_children[$id]);		
	}
	
	/**
	 * Get the children of this node
	 *
	 * @return array the children
	 */
	function &getChildren($id = null) 
	{
		if($id != null)
		{
			if(!isset($this->_children[$id]))
			{
				return false;
			}
			return $this->_children[$id];
		}
		return $this->_children;
	}

	/**
	 * Get the parent of this node
	 *
	 * @return JNode|null the parent
	 */
	function &getParent() 
	{
		return $this->_parent;
	}

	/**
	 * Test if this node has children
	 *
	 * @return bool
	 */
	function hasChildren() 
	{
		return count($this->_children);
	}

	/**
	 * Test if this node has a parent
	 *
	 * @return bool
	 */
	function hasParent() 
	{
		return $this->getParent() != null;
	}
	
	function setSibling($sibling, $right = true)
	{
		if($right)
		{
			$this->_rightSibling = $sibling;
		} else {
			$this->_leftSibling = $sibling;
		}
	}
	
	function getSibling($right = true)
	{
		if($right)
		{
			return $this->_rightSibling;
		} else {
			return $this->_leftSibling;
		}
	}
	
	function getParams()
	{
		if(!$this->params instanceof JRegistry)
		{
			$registry = new JRegistry();
			$registry->loadJSON($this->params);
			$this->params = $registry->toArray();
		}
	}
	
	function getMetadata()
	{
		if(!$this->metadata instanceof JRegistry)
		{
			$registry = new JRegistry();
			$registry->loadJSON($this->metadata);
			$this->metadata = $registry->toArray();
		}
	}
	
	function getPath()
	{
		return $this->_path;
	}
}
