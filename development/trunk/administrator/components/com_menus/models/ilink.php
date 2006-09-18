<?php
/**
 * @version $Id: component.php 4890 2006-09-03 00:25:44Z Jinx $
 * @package Joomla
 * @subpackage Menus
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights
 * reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Import library dependencies
jimport('joomla.common.abstract.tree');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Internal link builder
 * 
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package		Joomla!
 * @subpackage	Menus
 * @since		1.5
 */
class iLink extends JTree
{
	var $_com		= null;
	var $_output	= null;
	var $_nodes		= array();

	function __construct($component, $id=null, $menutype=null)
	{
		parent::__construct();
		if ($id) {
			$this->_cid = "&amp;cid[]=".$id;
		} else {
			$this->_cid = null;
		}
		if ($menutype) {
			$this->_menutype = "&amp;menutype=".$menutype;
		} else {
			$this->_menutype = null;
		}
		$this->_com = preg_replace( '#\W#', '', $component );;
		// Build the tree
		if (!$this->_getViews()) {
			if (!$this->_getOptions($this->_getXML(JPATH_SITE.'/components/com_'.$this->_com.'/metadata.xml', 'menu/options'), $this->_root)) {
				// Default behavior
			}
		}
	}

	/**
	 * Returns the component
	 * @return string
	 */
	function getComponent()
	{
		return $this->_com;
	}

	function getTree()
	{
		$depth = 0;
		$this->reset();
		$class = null;

		// Recurse through children if they exist
		while ($this->_current->hasChildren())
		{
			$this->_output .= '<ul>';
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->renderLevel($depth);
			}
			$this->_output .= '</ul>';
		}
		return $this->_output;
	}

	function renderLevel($depth)
	{
		$depth++;
		if (!isset($this->_depthHash[$depth])) {
			$this->_depthHash[$depth] = 0;
		}
		$this->_depthHash[$depth]++;

		if ($this->_current->hasChildren()) {
			$classes = 'node';
		} else {
			$classes = 'leaf';
		}

		$parent = & $this->_current->getParent();
		// Print the item
		$this->_output .= "<li class=\"".$classes."\">";

		// Print the url
		if ($this->_current->hasChildren()) {
			$this->_output .= "<a title=\"".$this->_current->msg."\">".$this->_current->title."</a>";
		} else {
			$this->_output .= "<a href=\"index.php?option=com_menus&amp;task=edit&amp;type=component&amp;".$this->_current->url.$this->_cid.$this->_menutype."\" title=\"".$this->_current->msg."\">".$this->_current->title."</a>";
		}

		// Recurse through children if they exist
		while ($this->_current->hasChildren())
		{
			$this->_output .= "<ul>";
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->renderLevel($depth);
			}
			$this->_output .= "</ul>";
		}

		// Close item
		$this->_output .= "</li>";
	}

	function _getOptions($e, &$parent, $purl=null)
	{
		if (!$purl) {
			$purl = 'url[option]=com_'.$this->_com;
		}
		if ($e) {
			$children = $e->children();
			foreach ($children as $child)
			{
				if ($child->name() == 'option') {
					$url = $purl.'&amp;url['.$e->attributes('var').']='.$child->attributes('value');
					$node =& new iLinkNode($child->attributes('name'), $url, $child->attributes('msg'));
					$parent->addChild($node);
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @access private
	 */
	function _getViews()
	{
		$return = false;
		$path = JPATH_SITE.DS.'components'.DS.'com_'.$this->_com.DS.'views';
		if (JFolder::exists($path)) {
			$views = JFolder::folders($path);
		} else {
			return $return;
		}

		if (is_array($views) && count($views)) {
			//$this->addChild(new iLinkNode('Views', null, 'Select the view'), true);
			$return = true;
			foreach ($views as $view)
			{
				// Load view metadata if it exists
				$xmlpath = $path.DS.$view.DS.'metadata.xml';
				if (JFile::exists($xmlpath)) {
					$data = &$this->_getXML($xmlpath, 'view');
				} else {
					$data = null;
				}

				$url = 'url[option]=com_'.$this->_com.'&amp;url[view]='.$view;
				if ($data) {
					$m = $data->getElementByPath('message');
					if ($m) {
						$message = $m->data();
					}
					$node =& new iLinkNode($data->attributes('title'), $url, $message);
					$this->addChild($node);
					if ($options = $data->getElementByPath('options')) {
						$this->_getOptions($options, $node, $url);
					} else {
						$this->_getLayouts(dirname($xmlpath), $node);
					}
				} else {
					$onclick = null;
					$node =& new iLinkNode(ucfirst($view), $url);
					$this->addChild($node);
					$this->_getLayouts(dirname($xmlpath), $node);
				}
			}
		}
		return $return;
	}

	/**
	 * @access private
	 */
	function _getLayouts($path, &$node)
	{
		$return = false;
		$files = JFolder::files($path.DS.'tmpl', ".php$");
		if (count($files)) {
			foreach ($files as $file)
			{
				if (strpos($file, '_') === false) {
					// Load view metadata if it exists
					$layout = JFile::stripext($file);
					$xmlpath = $path.DS.'tmpl'.DS.$layout.'.xml';
					if (JFile::exists($xmlpath)) {
						$data = &$this->_getXML($xmlpath, 'layout');
					} else {
						$data = null;
					}

					if ($layout != 'default') {
						$url = 'url[option]=com_'.$this->_com.'&amp;url[view]='.basename($path).'&amp;url[layout]='.$layout;
					} else {
						$url = 'url[option]=com_'.$this->_com.'&amp;url[view]='.basename($path);
					}
					if ($data) {
						$m = $data->getElementByPath('message');
						if ($m) {
							$message = $m->data();
						}
						$child =& new iLinkNode($data->attributes('title'), $url, $message);
						$node->addChild($child);
					} else {
						// Add default info for the layout
						$child =& new iLinkNode(ucfirst($layout).' '.JText::_('Layout'), $url);
						$node->addChild($child);
					}
				}
			}
		}
		return $return;
	}

	function _getXML($path, $xpath='control')
	{
		// Initialize variables
		$result = null;
		// load the xml metadata
		if (file_exists( $path )) {
			$xml = JFactory::getXMLParser('Simple');
			if ($xml->loadFile($path)) {
				if (isset( $xml->document )) {
					$result = $xml->document->getElementByPath($xpath);
				}
			}
			return $result;
		}
		return $result;
	}

	function _findNodes(&$node)
	{
		foreach ($node->children() as $step) 
		{
			/*
			 * For each child we need to see if it is an include and if so we
			 * need to get those children and process them as well (break out into
			 * another method).  Then we need to create the objects in the _steps
			 * array for each child of type step.  For now we aren't going to handle
			 * nested includes.
			 */
			if ($step->name() == 'include') {
				// Handle include
				$this->_getIncludedSteps($step, $node);
			} elseif ($step->name() == 'step') {
				// Include step to array
				$this->_nodes[] = $step;
			} else {
				// Do nothing
				continue;
			}
		}
	}

	function _getIncludedSteps($include, &$parent)
	{
		$tags	= array();
		$source	= $include->attributes('source');
		$path	= $include->attributes('path');

		preg_match_all( "/{([A-Za-z\-_]+)}/", $source, $tags);
		if (isset( $tags[1] )) {
			$n = count( $tags[1] );
			for ($i=0; $i < $n; $i++)
			{
				$source = str_replace($tags[0][$i], @$this->_vars[$tags[1][$i]], $source);
			}
		}

		// load the source xml file
		if (file_exists( JPATH_ROOT.$source ))
		{
			$xml = & JFactory::getXMLParser('Simple');
			if ($xml->loadFile(JPATH_ROOT.$source))
			{
				$document	= &$xml->document;
				$steps		= $document->getElementByPath($path);

				foreach($steps->children() as $step)
				{
					if ($step->name() == 'include') {
						// Handle include
					} elseif ($step->name() == 'step') {
						// Include step to array
						$node->addChild('step', $step->attributes(), $node->level()+1);
					} else {
						// Do nothing
						continue;
					}
				}
			}
		}
	}
}

class iLinkNode extends JNode
{
	/**
	 * Node Title
	 */
	var $title = null;

	/**
	 * Node URL
	 */
	var $url = null;

	/**
	 * Node message
	 */
	var $msg = null;

	function __construct($title, $url = null, $msg = null)
	{
		$this->title	= $title;
		$this->url		= $url;
		$this->msg		= $msg;
	}
}
?>