<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Document
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * Abstract class for a renderer
 *
 * @abstract
 * @package		Joomla.Framework
 * @subpackage	Document
 * @since		1.5
 */
abstract class JDocumentRenderer extends JClass
{
	/**
	* reference to the JDocument object that instantiated the renderer
	*
	* @var		object
	* @access	protected
	*/
	protected $_doc = null;

	/**
	 * Renderer mime type
	 *
	 * @var		string
	 * @access	private
	 */
	 protected $_mime = "text/html";

	/**
	* Class constructor
	*
	* @access protected
	* @param object A reference to the JDocument object that instantiated the renderer
	*/
	public function __construct(&$doc) {
		$this->_doc = &$doc;
	}

	/**
	 * Renders a script and returns the results as a string
	 *
	 * @abstract
	 * @access public
	 * @param string 	$name		The name of the element to render
	 * @param array 	$array		Array of values
	 * @param string 	$content	Override the output of the renderer
	 * @return string	The output of the script
	 */
	abstract public function render($name, $params = array(), $content = null);

	/**
	 * Return the content type of the renderer
	 *
	 * @return string The contentType
	 */
	public function getContentType() {
		return $this->_mime;
	}
}
