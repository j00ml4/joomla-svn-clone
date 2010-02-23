<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Error
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * Joomla! Logging class
 *
 * This class is designed to build log files based on the
 * W3C specification at: http://www.w3.org/TR/WD-logfile.html
 *
 * @package	Joomla.Framework
 * @subpackage	Error
 * @since		1.5
 */
class JLog extends JObject
{
	/**
	 * Log File Pointer
	 * @var	resource
	 */
	var $_file;

	/**
	 * Log File Path
	 * @var	string
	 */
	var $_path;
	
	/**
	 * Storage method
	 * @var string	 
	 */     	
	var $_storage;
	
	/**
	 * Current log entries
	 * @var array
	 */ 
	var $_entries;

	/**
	 * Log Format
	 * @var	string
	 */
	var $_format = "{DATE}\t{TIME}\t{LEVEL}\t{C-IP}\t{STATUS}\t{COMMENT}";

	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	string	$path		Log file path
	 * @param	array	$options	Log file options
	 * @since	1.5
	 */
	function __construct($path, $options)
	{
		// Set default values
		$this->_path    = $path;
		$this->_entries = array();
		$this->setOptions($options);
		
		register_shutdown_function(array(&$this,'_store'));
	}

	/**
	 * Returns the global log object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access	public
	 * @static
	 * @return	object	The JLog object.
	 * @since	1.5
	 */
	static function getInstance($file = 'error.php', $options = null, $path = null)
	{
		static $instances;

		// Set default path if not set
		if (!$path)
		{
			$config = &JFactory::getConfig();
			$path = $config->getValue('config.log_path');
		}

		jimport('joomla.filesystem.path');
		$path = JPath :: clean($path . DS . $file);
		$sig = md5($path);

		if (!isset ($instances)) {
			$instances = array ();
		}

		if (empty ($instances[$sig])) {
			$instances[$sig] = new JLog($path, $options);
		}

		return $instances[$sig];
	}

	/**
	 * Set log file options
	 *
	 * @access	public
	 * @param	array	$options	Associative array of options to set
	 * @return	boolean				True if successful
	 * @since	1.5
	 */
	function setOptions($options) 
    {
        if(!is_array($options)) return false;
        
		if (isset ($options['format'])) $this->_format = $options['format'];
		if (isset ($options['storage'])) $this->_storage = $options['storage'];
		
		return true;
	}
	
	/**
	 * Clears the current log
	 *
	 * @access	public
	 * @return	boolean true
	 * @since	1.6
	 */
	function clear()
	{
        $this->_entries = array();
        
        return true;
    }

    /**
	 * Returns all log entries
	 *
	 * @access	public
	 * @return	array   $_entries        The current log entries
	 * @since	1.6
	 */
    function getEntries()
    {
         return $this->_entries;
    }
    
    /**
	 * Adds an entry to the log
	 *
	 * @access	public
	 * @param	array	$entry    	Associative array of options to set
	 * @return	boolean				True if successful
	 * @since	1.5
	 */
	function addEntry($entry)
	{
	    // Make sure we have an array
	    if(!is_array($entry)) return false;
	    
		// Set some default field values if not already set.
		$date = &JFactory::getDate();
		if (!isset ($entry['date'])) {

			$entry['date'] = $date->toFormat("%Y-%m-%d");
		}
		if (!isset ($entry['time'])) {

			$entry['time'] = $date->toFormat("%H:%M:%S");
		}
		if (!isset ($entry['c-ip'])) {
			$entry['c-ip'] = $_SERVER['REMOTE_ADDR'];
		}
		
		// Format the entry and save it
        $this->_entries[] = $this->_format($entry);
		
		return true;
	}
	
	/**
	 * Formats an entry according to $_format
	 *
	 * @access	public
	 * @param   array    $entry    	 The original entry
	 * @return	string               The formatted entry
	 * @since	1.6
	 */
	function _format($entry)
	{
        static $fields;
        
        // Ensure that the log entry keys are all uppercase
		$entry = array_change_key_case($entry, CASE_UPPER);
		
        // Find all fields in the format string
        if(!is_array($fields)) {
		    $fields = array ();
		    $regex  = "/{(.*?)}/i";
		    preg_match_all($regex, $this->_format, $fields);
        }
        
        // Fill in the field data
		$line = $this->_format;
		for ($i = 0; $i < count($fields[0]); $i++)
		{
			$line = str_replace($fields[0][$i], (isset ($entry[$fields[1][$i]])) ? $entry[$fields[1][$i]] : "-", $line);
		}
		
		return $line;
    }
    
    /**
	 * Stores the current log entries
	 *
	 * @access	public
	 * @param   string    $storage   The storage method
	 * @return	boolean              True if successful
	 * @since	1.6
	 */
    function _store($storage = NULL)
    {
        if($storage) $this->_storage = $storage;
        
        switch($this->_storage)
        {
             case 'database':
                  $result = $this->_toDB();
                  break;
                  
             case 'email':
                 $result = $this->_toEmail();
                 break;
                      
             case 'file':
             default:
                  $result = $this->_toFile();
                  break;
        }
        
        return $result;
    }

    /**
	 * Saves the log in the database
	 *
	 * @access	public
	 * @return	boolean       True if successful
	 * @since	1.6
	 */
    function _toDB()
    {
        $db   = &JFactory::getDBO();
        $user = &JFactory::getUser();
        
        $uid   = $db->Quote($user->get('id'));
        $lines = $db->Quote(implode("\n",$this->_entries));
        
        $query = "INSERT INTO #__core_log VALUES (NULL, $uid, TIME(),$lines)";
               $db->setQuery($query);
               $db->query();
               
        if($db->getErrorNum()) {
            return false;
        }
        
        return true;
    }
    
    /**
	 * Sends the log to an email address
	 *
	 * @access	public
	 * @return	boolean       True if successful
	 * @since	1.6
	 */
    function _toEmail()
    {
        // TODO
    }
    
    /**
	 * Writes the log entries to a flat file
	 *
	 * @access	public
	 * @return	boolean       True if successful
	 * @since	1.6
	 */
    function _toFile()
    {
        if (!file_exists($this->_path))
		{
			jimport("joomla.filesystem.folder");
			if (!JFolder :: create(dirname($this->_path))) {
				return false;
			}
			$header[] = "#<?php die('Direct Access To Log Files Not Permitted'); ?>";
			$header[] = "#Version: 1.0";
			$header[] = "#Date: " . JFactory::getDate()->toMySQL();

			// Prepare the fields string
			$fields = str_replace("{", "", $this->_format);
			$fields = str_replace("}", "", $fields);
			$fields = strtolower($fields);
			$header[] = "#Fields: " . $fields;

			// Prepare the software string
			$version = new JVersion();
			$header[] = "#Software: " . $version->getLongVersion();

			$head = implode("\n", $header);
		} else {
			$head = false;
		}
		
		// Open the log file
		if (!$this->_file = fopen($this->_path, "a")) {
			return false;
		}
		
		// Write log header
		if ($head) {
			if (!fputs($this->_file, $head)) {
				return false;
			}
		}
		
		// Write log entries
		$lines = implode("\n", $this->_entries);
		if (!fputs($this->_file, $lines)) {
				return false;
		}
		
		if (is_resource($this->_file)) {
			fclose($this->_file);
		}
		
		return true;
    }
}
