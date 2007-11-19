<?php
/**
* @version		$Id: error.php 9330 2007-11-10 06:25:19Z tcp $
* @package		Joomla.Framework
* @subpackage	Utilities
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Joomla! Exception object.
 *
 * This class is inspired in design and concept by patError <http://www.php-tools.net>
 *
 * patError contributors include:
 * 	- gERD Schaufelberger	<gerd@php-tools.net>
 * 	- Sebastian Mordziol	<argh@php-tools.net>
 * 	- Stephan Schmidt		<scst@php-tools.net>
 *
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package 	Joomla.Framework
 * @subpackage	Utilities
 * @since		1.5
 */
class JException extends JObject
{
	/**
	* Error level
	* @var string
	*/
	var	$level		= null;

	/**
	* Error code
	* @var string
	*/
	var	$code		= null;

	/**
	* Error message
	* @var string
	*/
	var	$message	= null;

	/**
	* Additional info about the error relevant to the developer
	*  - e.g. if a database connect fails, the dsn used
	* @var string
	*/
	var	$info		= '';

	/**
	* Name of the file the error occurred in [Available if backtrace is enabled]
	* @var string
	*/
	var	$file		= null;

	/**
	* Line number the error occurred in [Available if backtrace is enabled]
	* @var int
	*/
	var	$line		= 0;

	/**
	* Name of the method the error occurred in [Available if backtrace is enabled]
	* @var string
	*/
	var	$function	= null;

	/**
	* Name of the class the error occurred in [Available if backtrace is enabled]
	* @var string
	*/
	var	$class		= null;

	/**
    * Error type
	* @var string
	*/
	var	$type		= null;

	/**
	* Arguments recieved by the method the error occurred in [Available if backtrace is enabled]
	* @var array
	*/
	var	$args		= array();

	/**
	* Backtrace information
	* @var mixed
	*/
	var	$backtrace	= false;

	/**
	* Constructor
	* 	- used to set up the error with all needed error details.
	*
	* @access	protected
	* @param	int		$level	The error level (use the PHP constants E_ALL, E_NOTICE etc.).
	* @param	string	$code	The error code from the application
	* @param	string	$msg	The error message
	* @param	string	$info	Optional: The additional error information.
	*/
    function __construct( $msg, $level, $code, $info = null, $backtrace = false )
    {
		$this->level	=	$level;
		$this->code		=	$code;
		$this->message	=	$msg;

		if( $info != null ) {
			$this->info = $info;
		}

		if( $backtrace && function_exists( 'debug_backtrace' ) ) {
			$this->backtrace = debug_backtrace();

			for( $i = count( $this->backtrace ) - 1; $i >= 0; --$i )
			{
				++$i;
				if( isset( $this->backtrace[$i]['file'] ) )
					$this->file		= $this->backtrace[$i]['file'];
				if( isset( $this->backtrace[$i]['line'] ) )
					$this->line		= $this->backtrace[$i]['line'];
				if( isset( $this->backtrace[$i]['class'] ) )
					$this->class	= $this->backtrace[$i]['class'];
				if( isset( $this->backtrace[$i]['function'] ) )
					$this->function	= $this->backtrace[$i]['function'];
				if( isset( $this->backtrace[$i]['type'] ) )
					$this->type		= $this->backtrace[$i]['type'];

				$this->args		= false;
				if( isset( $this->backtrace[$i]['args'] ) ) {
					$this->args		= $this->backtrace[$i]['args'];
				}
				break;
			}
		}
    }

	/**
	* Method to get the exception message
	*
	* @final
	* @access	public
	* @return	string
	* @since	1.5
	*/
	function getMessage() {
		return $this->message;
	}
	
	/**
	* Method to get the exception code
	*
	* @final
	* @access	public
	* @return	integer
	* @since	1.5
	*/
	function getCode() {
		return $this->code;
	}
	
	/**
	* Method to get the source filename where the exception occured
	*
	* @final
	* @access	public
	* @return	string
	* @since	1.5
	*/
	function getFile() {
		return $this->file;
	}
	
	/**
	* Method to get the source line where the exception occured
	*
	* @final
	* @access	public
	* @return	integer
	* @since	1.5
	*/
	function getLine() {
		return $this->line;
	}
	
	/**
	* Method to get the array of the backtrace() 
	*
	* @final
	* @access	public
	* @return	array backtrace
	* @since	1.5
	*/
	function getTrace( $formatted = false )
	{
		if (isset( $this->backtrace )) {
			$trace = &$this->backtrace;
		} else {
			$trace = function_exists( 'debug_backtrace' ) ? debug_backtrace() : null;
		}
		
		return $trace;

		if ($formatted && is_array( $trace ))
		{
			$result = '';
			foreach ($trace as $back)
			{
				if (isset($back['file']) && strpos($back['file'], 'error.php') === false) {
					$result .= '<br />'.$back['file'].':'.$back['line'];
				}
			}
			return $result;
		}
		return $this->backtrace;
	}
	
	/**
	 * Method to get the formatted backtrace information
	 *
	 * @final
	 * @access	public
	 * @return	string Formated string of trace
	 * @since	1.5
	 */
	function getTraceAsString( )
	{
		//Get the trace array
		$trace = $this->getTrace(); 

		$result = '';
		foreach ($trace as $back)
		{
			if (isset($back['file']) && strpos($back['file'], 'error.php') === false) {
				$result .= '<br />'.$back['file'].':'.$back['line'];
			}
		}
		return $result;
	}

	/**
	 * Returns to error message
	 *
	 * @access	public
	 * @return	string Error message
	 * @since	1.5
	 */
	function toString()
	{
		return $this->message;
	}
}