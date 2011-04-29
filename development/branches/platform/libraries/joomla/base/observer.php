<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Abstract observer class to implement the observer design pattern
 *
 * @abstract
 * @package		Joomla.Platform
 * @subpackage	Base
 * @since		11.1
 */
abstract class JObserver extends JObject
{
	/**
	 * Event object to observe.
	 *
	 * @var		object
	 */
	protected $_subject = null;

	/**
	 * Constructor
	 *
	 * @param	object		$subject	The object to observe.
	 * @return	void
	 */
	public function __construct(&$subject)
	{
		// Register the observer ($this) so we can be notified
		$subject->attach($this);

		// Set the subject to observe
		$this->_subject = &$subject;
	}

	/**
	 * Method to update the state of observable objects
	 *
	 * @abstract	Implement in child classes
	 * @param		array		$args		An array of arguments to pass to the listener.
	 *
	 * @return		mixed
	 */
	public abstract function update(&$args);
}