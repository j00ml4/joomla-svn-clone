<?php
/**
 * @version     $Id: sybasemssql.php 13708 2009-12-11 23:20:40Z pasamio $
 * @package     Joomla.Framework
 * @subpackage  Database
 * @copyright   Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Sybase database driver
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.0
 */
class JDatabaseSybaseMSSQL extends JDatabase
{
	/**
	 * The database driver name
	 *
	 * @var string
	 */
	var $name			= 'sybasemssql';

	/**
	 *  The null/zero date string
	 *
	 * @var string
	 */
	var $_nullDate		= '0000-00-00 00:00:00';

	/**
	 * Quote for named objects
	 *
	 * @var string
	 */
	var $_nameQuote		= '';

	/**
	 * Database object constructor
	 *
	 * @access	public
	 * @param	array	List of options used to configure the connection
	 * @since	1.5
	 * @see		JDatabase
	 */
	function __construct($options)
	{
		$host		= array_key_exists('host', $options)	? $options['host']		: 'localhost';
		$user		= array_key_exists('user', $options)	? $options['user']		: '';
		$password	= array_key_exists('password',$options)	? $options['password']	: '';
		$database	= array_key_exists('database',$options)	? $options['database']	: '';
		$prefix		= array_key_exists('prefix', $options)	? $options['prefix']	: 'jos_';
		$select		= array_key_exists('select', $options)	? $options['select']	: true;

		// perform a number of fatality checks, then return gracefully
		if (!function_exists('sybase_connect')) {
			$this->_errorNum = 1;
			$this->_errorMsg = 'The adapter "sybase" is not available.';
			return;
		}

		// connect to the server
		if (!($this->_resource = @sybase_connect($host, $user, $password))) {
			$this->_errorNum = 2;
			$this->_errorMsg = 'Could not connect to the database using supplied connection details.';
			return;
		}

		// finalize initialization
		parent::__construct($options);

		// select the database
		if ($select) {
			$this->select($database);
		}
	}

	/**
	 * Database object destructor
	 *
	 * @return boolean
	 * @since 1.5
	 */
	function __destruct()
	{
		$return = false;
		if (is_resource($this->_resource)) {
			$return = sybase_close($this->_resource);
		}
		return $return;
	}

	/**
	 * Test to see if the MySQL connector is available
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function test()
	{
		return (function_exists('sybase_connect'));
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @access	public
	 * @return	boolean
	 * @since	1.5
	 */
	function connected()
	{
		die(get_class($this).'::connected not implemented');
	}

	/**
	 * Select a database for use
	 *
	 * @access	public
	 * @param	string $database
	 * @return	boolean True if the database has been successfully selected
	 * @since	1.5
	 */
	function select($database)
	{
		if (!$database) {
			return false;
		}

		if (!sybase_select_db($database, $this->_resource)) {
			$this->_errorNum = 3;
			$this->_errorMsg = 'Could not connect to database';
			return false;
		}

		return true;
	}

	/**
	 * Determines UTF support
	 *
	 * @access public
	 * @return boolean True - UTF is supported
	 */
	function hasUTF()
	{
		return false;
	}

	/**
	 * Custom settings for UTF support
	 *
	 * @access	public
	 */
	function setUTF()
	{
		die(get_class($this).'::setUTF not implemented');
	}

	/**
	 * Get a database escaped string
	 *
	 * @param	string	The string to be escaped
	 * @param	boolean	Optional parameter to provide extra escaping
	 * @return	string
	 * @access	public
	 * @abstract
	 */
	function getEscaped($text, $extra = false)
	{
		$result = str_replace("'", "''", $text);
		return $result;
	}

	/**
	 * Execute the query
	 *
	 * @access	public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	function query()
	{
		//if (!is_resource($this->_resource)) {
		//	return false;
		//}

		if ($this->_limit > 0 || $this->_offset > 0)
		{
			// This is the fun bit for mssql
			//$this->_sql .= ' LIMIT '.$this->_offset.', '.$this->_limit;

			if ($this->_offset <= 0)
			{
				$this->_sql = preg_replace(
					'/(^\s*select\s+(distinctrow|distinct)?)/i','\\1 TOP '.$this->_limit.' ',$this->_sql);
			}
			else
			{

			}
		}
		if ($this->_debug) {
			$this->_ticker++;
			$this->_log[] = $this->_sql;
		}
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		$this->_cursor = sybase_query($this->_sql, $this->_resource);

		if (!$this->_cursor)
		{
			$this->_errorNum = 1;
			$this->_errorMsg = sybase_get_last_message()." SQL=$this->_sql";

			if ($this->_debug) {
				JError::raiseError(500, 'JDatabaseMySQL::query: '.$this->_errorNum.' - '.$this->_errorMsg);
			}
			return false;
		}
		return $this->_cursor;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return int The number of affected rows in the previous operation
	 * @since 1.0.5
	 */
	function getAffectedRows()
	{
		return sybase_affected_rows($this->_resource);
	}

	/**
	 * Execute a batch query
	 *
	 * @access	public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	function queryBatch($abort_on_error=true, $p_transaction_safe = false)
	{
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		if ($p_transaction_safe) {
			$this->_sql = rtrim($this->_sql, '; \t\r\n\0');
			$si = $this->getVersion();
			preg_match_all("/(\d+)\.(\d+)\.(\d+)/i", $si, $m);
			if ($m[1] >= 4) {
				$this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 19) {
				$this->_sql = 'BEGIN WORK;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 17) {
				$this->_sql = 'BEGIN;' . $this->_sql . '; COMMIT;';
			}
		}
		$query_split = $this->splitSql($this->_sql);
		$error = 0;
		foreach ($query_split as $command_line) {
			$command_line = trim($command_line);
			if ($command_line != '') {
				$this->_cursor = sybase_query($command_line, $this->_resource);
				if ($this->_debug) {
					$this->_ticker++;
					$this->_log[] = $command_line;
				}
				if (!$this->_cursor) {
					$error = 1;
					$this->_errorNum .= 1 . ' ';
					$this->_errorMsg .= sybase_get_last_message()." SQL=$command_line <br />";
					if ($abort_on_error) {
						return $this->_cursor;
					}
				}
			}
		}
		return $error ? false : true;
	}

	/**
	 * Diagnostic function
	 *
	 * @access	public
	 * @return	string
	 */
	function explain()
	{
		return null;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return int The number of rows returned from the most recent query.
	 */
	function getNumRows($cur=null)
	{
		return sybase_num_rows($cur ? $cur : $this->_cursor);
	}

	/**
	 * This method loads the first field of the first row returned by the query.
	 *
	 * @access	public
	 * @return The value returned in the query or null if the query failed.
	 */
	function loadResult()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = sybase_fetch_row($cur)) {
			$ret = $row[0];
		}
		sybase_free_result($cur);
		return $ret;
	}

	/**
	 * Load an array of single field results into an array
	 *
	 * @access	public
	 */
	function loadResultArray($numinarray = 0)
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = sybase_fetch_row($cur)) {
			$array[] = $row[$numinarray];
		}
		sybase_free_result($cur);
		return $array;
	}

	/**
	 * Fetch a result row as an associative array
	 *
	 * @access	public
	 * @return array
	 */
	function loadAssoc()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($array = sybase_fetch_assoc($cur)) {
			$ret = $array;
		}
		sybase_free_result($cur);
		return $ret;
	}

	/**
	 * Load a assoc list of database rows
	 *
	 * @access	public
	 * @param string The field name of a primary key
	 * @return array If <var>key</var> is empty as sequential list of returned records.
	 */
	function loadAssocList($key='')
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = sybase_fetch_assoc($cur)) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		sybase_free_result($cur);
		return $array;
	}

	/**
	 * This global function loads the first row of a query into an object
	 *
	 * @access	public
	 * @return 	object
	 */
	function loadObject()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($object = sybase_fetch_object($cur)) {
			$ret = $object;
		}
		sybase_free_result($cur);
		return $ret;
	}

	/**
	 * Load a list of database objects
	 *
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @access	public
	 * @param string The field name of a primary key
	 * @return array If <var>key</var> is empty as sequential list of returned records.
	 */
	function loadObjectList($key='')
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = sybase_fetch_object($cur)) {

			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		sybase_free_result($cur);
		return $array;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return The first row of the query.
	 */
	function loadRow()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = sybase_fetch_row($cur)) {
			$ret = $row;
		}
		sybase_free_result($cur);
		return $ret;
	}

	/**
	 * Load a list of database rows (numeric column indexing)
	 *
	 * @access public
	 * @param string The field name of a primary key
	 * @return array If <var>key</var> is empty as sequential list of returned records.
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * the database key.  Returns <var>null</var> if the query fails.
	 */
	function loadRowList($key=null)
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = sybase_fetch_row($cur)) {
			if ($key !== null) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		sybase_free_result($cur);
		return $array;
	}

	/**
	 * Inserts a row into a table based on an objects properties
	 *
	 * @access	public
	 * @param	string	The name of the table
	 * @param	object	An object whose properties match table fields
	 * @param	string	The name of the primary key. If provided the object property is updated.
	 */
	function insertObject($table, &$object, $keyName = NULL)
	{
		$fmtsql = 'INSERT INTO '.$this->nameQuote($table).' (%s) VALUES (%s) ';
		$fields = array();
		foreach (get_object_vars($object) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			$fields[] = $this->nameQuote($k);
			$values[] = $this->isQuoted($k) ? $this->Quote($v) : (int) $v;
		}
		$this->setQuery(sprintf($fmtsql, implode(",", $fields) ,  implode(",", $values)));
		if (!$this->query()) {
			return false;
		}
		$id = $this->insertid();
		if ($keyName && $id) {
			$object->$keyName = $id;
		}
		return true;
	}

	/**
	 * Description
	 *
	 * @access public
	 * @param [type] $updateNulls
	 */
	function updateObject($table, &$object, $keyName, $updateNulls=true)
	{
		$fmtsql = 'UPDATE '.$this->nameQuote($table).' SET %s WHERE %s';
		$tmp = array();
		foreach (get_object_vars($object) as $k => $v)
		{
			if(is_array($v) or is_object($v) or $k[0] == '_') { // internal or NA field
				continue;
			}
			if($k == $keyName) { // PK not to be updated
				$where = $keyName . '=' . $this->Quote($v);
				continue;
			}
			if ($v === null)
			{
				if ($updateNulls) {
					$val = 'NULL';
				} else {
					continue;
				}
			} else {
				$val = $this->isQuoted($k) ? $this->Quote($v) : (int) $v;
			}
			$tmp[] = $this->nameQuote($k) . '=' . $val;
		}
		$this->setQuery(sprintf($fmtsql, implode(",", $tmp) , $where));
		return $this->query();
	}

	/**
	 * Description
	 *
	 * @access public
	 */
	function insertid()
	{
		die(get_class($this).'::insertid not implemented');
	}

	/**
	 * Description
	 *
	 * @access public
	 */
	function getVersion()
	{
		return 0;
	}

	/**
	 * Assumes database collation in use by sampling one text field in one table
	 *
	 * @access	public
	 * @return string Collation in use
	 */
	function getCollation ()
	{
		die(get_class($this).'::getCollation not implemented');
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return array A list of all the tables in the database
	 */
	function getTableList()
	{
		$this->setQuery('SHOW TABLES');
		return $this->loadResultArray();
	}

	/**
	 * Shows the CREATE TABLE statement that creates the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @return 	array A list the create SQL for the tables
	 */
	function getTableCreate($tables)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery('SHOW CREATE table ' . $this->getEscaped($tblval));
			$rows = $this->loadRowList();
			foreach ($rows as $row) {
				$result[$tblval] = $row[1];
			}
		}

		return $result;
	}

	/**
	 * Retrieves information about the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @param	boolean			Only return field types, default true
	 * @return	array An array of fields by table
	 */
	function getTableFields($tables, $typeonly = true)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval)
		{
			$this->setQuery('SHOW FIELDS FROM ' . $tblval);
			$fields = $this->loadObjectList();

			if($typeonly)
			{
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type);
				}
			}
			else
			{
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = $field;
				}
			}
		}

		return $result;
	}
}
