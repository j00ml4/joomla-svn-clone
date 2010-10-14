<?php
/**
 * @version		$Id: mssql.php 18554 2010-08-21 03:19:19Z ian $
 * @package		Joomla.Framework
 * @subpackage	Database
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * SQL Server database driver
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.0
 */
class JDatabaseMSSql extends JDatabase
{
	/**
	 * The database driver name
	 *
	 * @var string
	 */
	public $name = 'mssql';

	/**
	 *  The null/zero date string
	 *
	 * @var string
	 */
	protected $_nullDate = '1900-01-01 00:00:00';

	/**
	 * Quote for named objects
	 *
	 * @var string
	 */
	protected $_nameQuote = null;

	/**
	 * Database object constructor
	 *
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
	
		
		// Perform a number of fatality checks, then return gracefully
		if (!function_exists('mssql_connect')) {
			$this->_errorNum = 1;
			$this->_errorMsg = 'The MS SQL adapter "mssql" is not available.';
			return;
		}

		// Connect to the server
		if (!($this->_connection = @mssql_connect($host, $user, $password, true))) {
			$this->_errorNum = 2;
			$this->_errorMsg = JText::_('JLIB_DATABASE_ERROR_CONNECT_mssql');
			return;
		}
		
		$conf = & JFactory::getConfig();
		
	    $slave_host = array_key_exists('slavehost', $options)	? $options['slavehost']		: '';
		$slave_user = array_key_exists('slavename', $options)	? $options['slavename']		: '';
		$slave_password = array_key_exists('slavepass', $options)	? $options['slavepass']		: '';
	
		if (empty($slave_host))
			$slave_host             = $conf->getValue('config.slave_db_host');

		if (empty($slave_user))
			$slave_user             = $conf->getValue('config.slave_db_user');

		if (empty($slave_password))
			$slave_password         = $conf->getValue('config.slave_db_password');
		
	
	    if ($slave_host != "" && $slave_user != "" && $slave_password != "") {
			if (!($this->_slave_resource = @mssql_connect( $slave_host, $slave_user, $slave_password, true ))) {
				$this->_errorNum = 2;
				$this->_errorMsg = 'Could not connect to mssql';
	
				return;
			}
			
			
		}

		// Finalize initialisation
		parent::__construct($options);

		// Set sql_mode to non_strict mode
		//mssql_query("SET @@SESSION.sql_mode = '';", $this->_connection);
		

		// select the database
	$select=true;
		if ($select) {
					
			$this->select($database);
	
			if (is_resource($this->_slave_connection)) {
			   // mssql_query("SET @@SESSION.sql_mode = '';", $this->_slave_connection);
				$this->selectSlave($database);
		    }
		   
		}
		 
	}

	/**
	 * Database object destructor
	 *
	 * @return boolean
	 * @since 1.5
	 */
	public function __destruct()
	{
		if (is_resource($this->_connection)) {
			mssql_close($this->_connection);
		}
	    if (is_resource($this->_slave_connection)) {
			mssql_close($this->_slave_connection);
		}
	}

	/**
	 * Test to see if the SQL Server connector is available
	 *
	 * @return boolean  True on success, false otherwise.
	 */
	public function test()
	{
		return (function_exists('mssql_connect'));
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @return	boolean
	 * @since	1.5
	 */
	public function connected()
	{
		/*if (is_resource($this->_connection)) {
			return mssql_ping($this->_connection);
		}
		return false;*/
		return true;
	}
	
    public function slave_connected()
	{
		/*if (is_resource($this->_slave_connection)) {
			return mssql_ping($this->_slave_connection);
		}
		return false;*/
		return true;
	}

	/**
	 * Select a database for use
	 *
	 * @param	string $database
	 * @return	boolean True if the database has been successfully selected
	 * @since	1.5
	 */
	public function select($database)
	{
		if (!$database) {
			return false;
		}

		if (!mssql_select_db($database, $this->_connection)) {
			$this->_errorNum = 3;
			$this->_errorMsg = JText::_('JLIB_DATABASE_ERROR_DATABASE_CONNECT');
			return false;
		}
		
		
		return true;
	}
	
	/**
	 * Select a Slave database for use
	 *
	 * @access	public
	 * @param	string $database
	 * @return	boolean True if the database has been successfully selected
	 * @since	1.5
	 */
	function selectSlave($database) {
		if ( ! $database ) {
			return false;
		}

		if ( !mssql_select_db( $database, $this->_slave_connection )) {
			$this->_errorNum = 3;
			$this->_errorMsg = 'Could not connect to database';
			return false;
		}

		return true;
	}
	

	/**
	 * Determines UTF support
	 *
	 * @return	boolean	True - UTF is supported
	 */
	public function hasUTF()
	{
	//	$verParts = explode('.', $this->getVersion());
	//	return ($verParts[0] == 5 || ($verParts[0] == 4 && $verParts[1] == 1 && (int)$verParts[2] >= 2));
	return true;
	}

	/**
	 * Custom settings for UTF support
	 */
	public function setUTF()
	{
		return mssql_query("SET NAMES 'utf8'", $this->_connection);
		//return true;
	}

	/**
	 * Get a database escaped string
	 *
	 * @param	string	The string to be escaped
	 * @param	boolean	Optional parameter to provide extra escaping
	 * @return	string
	 */
	public function getEscaped($text, $extra = false)
	{
		//$result = mssql_real_escape_string($text, $this->_connection);
		$result = addslashes($text);
		if ($extra) {
			$result = addcslashes($result, '%_');
		}
		return $result;
	}

	/**
	 * Execute the query
	 *
	 * @return	mixed	A database resource if successful, FALSE if not.
	 */
	public function query()
	{
		ini_set('display_errors',1);
    error_reporting(E_ALL);
		if (!is_resource($this->_connection)) {
			return false;
		}
	//echo $this->_sql;
		// Take a local copy so that we don't modify the original query and cause issues later
		$sql = $this->replacePrefix((string) $this->_sql);
		$sql = str_replace('`', '', $sql);
    $sql = str_replace('LENGTH', 'DATALENGTH', $sql);
    $sql = str_ireplace('insert ignore into', 'insert into', $sql);
		if ($this->_limit > 0 || $this->_offset > 0) {
		  if($this->_offset == 0)
        $sql = str_ireplace('select', 'select top '.$this->_limit, $sql);
			//$sql .= ' LIMIT '.$this->_offset.', '.$this->_limit;
		}
		if ($this->_debug) {
			$this->_ticker++;
			$this->_log[] = $sql;
		}
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		
		
		//echo $sql;echo '<br>';
		jimport("joomla.utilities.string");
		
		$select_in_sql = JString::startsWith(ltrim(strtoupper($sql)), 'SELECT') ;
		
	    if($select_in_sql && is_resource($this->_slave_connection)) {
			$this->_cursor = mssql_query( $sql, $this->_slave_connection );
		} else {
			$this->_cursor = mssql_query( $sql, $this->_connection );
		}
		
		//$this->_cursor = mssql_query($sql, $this->_connection);
	    if (!$this->_cursor) {
	      //echo $sql;echo '<br>';
	        if($select_in_sql && is_resource($this->_slave_connection)) {	
    			//$this->_errorNum = mssql_errno($this->_slave_connection);
    			//$this->_errorMsg = mssql_error($this->_slave_connection)." SQL=$sql";
    			$this->_errorNum = '1';
			$this->_errorMsg = 'DId not execute';
	        } else {
	           // $this->_errorNum = mssql_errno($this->_connection);
    			//$this->_errorMsg = mssql_error($this->_connection)." SQL=$sql";
    			$this->_errorNum = '1';
			$this->_errorMsg = 'DId not execute';
	        }
    
    		if ($this->_debug) {
    			JError::raiseError(500, 'JDatabasemssql::query: '.$this->_errorNum.' - '.$this->_errorMsg);
    		}
    			return false;
    		}
	   
		return $this->_cursor;
	}

/**
   * Get the current or query, or new JDatabaseQuery object.
   *
   * @param boolean False to return the last query set by setQuery, True to return a new JDatabaseQuery object.
   * @return  string  The current value of the internal SQL variable
   */
  public function getQuery($new = false)
  {
    if ($new) {
      jimport('joomla.database.databasequerymssql');
      return new JDatabaseQueryMsSQL;
    } else {
      return $this->_sql;
    }
  }

	/**
	 * @return	int		The number of affected rows in the previous operation
	 * @since	1.0.5
	 */
	public function getAffectedRows()
	{
	    if(is_resource($this->_slave_connection))
		    return mssql_rows_affected($this->_slave_connection);
		else return mssql_rows_affected($this->_connection);
	}

	/**
	 * Execute a batch query
	 *
	 * @return	mixed	A database resource if successful, FALSE if not.
	 */
	public function queryBatch($abort_on_error=true, $p_transaction_safe = false)
	{
		/*$sql = $this->replacePrefix((string) $this->_sql);
		$this->_errorNum = 0;
		$this->_errorMsg = '';

		if ($p_transaction_safe) {
			$sql = rtrim($sql, "; \t\r\n\0");
			$si = $this->getVersion();
			preg_match_all("/(\d+)\.(\d+)\.(\d+)/i", $si, $m);
			if ($m[1] >= 4) {
				$sql = 'START TRANSACTION;' . $sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 19) {
				$sql = 'BEGIN WORK;' . $sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 17) {
				$sql = 'BEGIN;' . $sql . '; COMMIT;';
			}
		}
		$query_split = $this->splitSql($sql);
		$error = 0;
		foreach ($query_split as $command_line) {
			$command_line = trim($command_line);
			if ($command_line != '') {
				$this->_cursor = mssql_query($command_line, $this->_connection);
				if ($this->_debug) {
					$this->_ticker++;
					$this->_log[] = $command_line;
				}
				if (!$this->_cursor) {
					$error = 1;
					$this->_errorNum .= mssql_errno($this->_connection) . ' ';
					$this->_errorMsg .= mssql_error($this->_connection)." SQL=$command_line <br />";
					if ($abort_on_error) {
						return $this->_cursor;
					}
				}
			}
		}
		return $error ? false : true;*/
		return true;
	}

	/**
	 * Diagnostic function
	 *
	 * @return	string
	 */
	public function explain()
	{
		/*$temp = $this->_sql;
		$this->_sql = "EXPLAIN $this->_sql";

		if (!($cur = $this->query())) {
			return null;
		}
		$first = true;

		$buffer = '<table id="explain-sql">';
		$buffer .= '<thead><tr><td colspan="99">'.$this->getQuery().'</td></tr>';
		while ($row = mssql_fetch_assoc($cur)) {
			if ($first) {
				$buffer .= '<tr>';
				foreach ($row as $k=>$v) {
					$buffer .= '<th>'.$k.'</th>';
				}
				$buffer .= '</tr></thead><tbody>';
				$first = false;
			}
			$buffer .= '<tr>';
			foreach ($row as $k=>$v) {
				$buffer .= '<td>'.$v.'</td>';
			}
			$buffer .= '</tr>';
		}
		$buffer .= '</tbody></table>';
		mssql_free_result($cur);

		$this->_sql = $temp;

		return $buffer;*/
	}

	/**
	 * Description
	 *
	 * @return	int	The number of rows returned from the most recent query.
	 */
	public function getNumRows($cur=null)
	{
		return mssql_num_rows($cur ? $cur : $this->_cursor);
	}

	/**
	 * This method loads the first field of the first row returned by the query.
	 *
	 * @return	mixed	The value returned in the query or null if the query failed.
	 */
	public function loadResult()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = mssql_fetch_row($cur)) {
			$ret = $row[0];
		}
		mssql_free_result($cur);
		return $ret;
	}

	/**
	 * Load an array of single field results into an array
	 */
	public function loadResultArray($numinarray = 0)
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mssql_fetch_row($cur)) {
			$array[] = $row[$numinarray];
		}
		mssql_free_result($cur);
		return $array;
	}

	/**
	 * Fetch a result row as an associative array
	 *
	 * @return	array
	 */
	public function loadAssoc()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($array = mssql_fetch_assoc($cur)) {
			$ret = $array;
		}
		mssql_free_result($cur);
		return $ret;
	}

	/**
	 * Load a assoc list of database rows.
	 *
	 * @param	string	The field name of a primary key.
	 * @param	string	An optional column name. Instead of the whole row, only this column value will be in the return array.
	 * @return	array	If <var>key</var> is empty as sequential list of returned records.
	 */
	public function loadAssocList($key = null, $column = null)
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mssql_fetch_assoc($cur)) {
			$value = ($column) ? (isset($row[$column]) ? $row[$column] : $row) : $row;
			if ($key) {
				$array[$row[$key]] = $value;
			} else {
				$array[] = $value;
			}
		}
		mssql_free_result($cur);
		return $array;
	}

	/**
	 * This global function loads the first row of a query into an object.
	 *
	 * @param	string	The name of the class to return (stdClass by default).
	 *
	 * @return	object
	 */
	public function loadObject($className = 'stdClass')
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($object = mssql_fetch_object($cur)) {
			$ret = $object;
		}
		mssql_free_result($cur);
		return $ret;
	}

	/**
	 * Load a list of database objects
	 *
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string	The field name of a primary key
	 * @param	string	The name of the class to return (stdClass by default).
	 *
	 * @return	array	If <var>key</var> is empty as sequential list of returned records.
	 */
	public function loadObjectList($key='', $className = 'stdClass')
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mssql_fetch_object($cur)) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		mssql_free_result($cur);
		return $array;
	}

	/**
	 * Description
	 *
	 * @return The first row of the query.
	 */
	public function loadRow()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = mssql_fetch_row($cur)) {
			$ret = $row;
		}
		mssql_free_result($cur);
		return $ret;
	}

	/**
	 * Load a list of database rows (numeric column indexing)
	 *
	 * @param	string	The field name of a primary key
	 * @return	array	If <var>key</var> is empty as sequential list of returned records.
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * the database key.  Returns <var>null</var> if the query fails.
	 */
	public function loadRowList($key=null)
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mssql_fetch_row($cur)) {
			if ($key !== null) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		mssql_free_result($cur);
		return $array;
	}

	/**
	 * Load the next row returned by the query.
	 *
	 * @return	mixed	The result of the query as an array, false if there are no more rows, or null on an error.
	 *
	 * @since	1.6.0
	 */
	public function loadNextRow()
	{
		static $cur;

		if (!($cur = $this->query())) {
			return $this->_errorNum ? null : false;
		}

		if ($row = mssql_fetch_row($cur)) {
			return $row;
		}

		mssql_free_result($cur);
		$cur = null;

		return false;
	}

	/**
	 * Load the next row returned by the query.
	 *
	 * @param	string	The name of the class to return (stdClass by default).
	 *
	 * @return	mixed	The result of the query as an object, false if there are no more rows, or null on an error.
	 *
	 * @since	1.6.0
	 */
	public function loadNextObject($className = 'stdClass')
	{
		static $cur;

		if (!($cur = $this->query())) {
			return $this->_errorNum ? null : false;
		}

		if ($row = mssql_fetch_object($cur)) {
			return $row;
		}

		mssql_free_result($cur);
		$cur = null;

		return false;
	}

	/**
	 * Inserts a row into a table based on an objects properties
	 *
	 * @param	string	The name of the table
	 * @param	object	An object whose properties match table fields
	 * @param	string	The name of the primary key. If provided the object property is updated.
	 */
	public function insertObject($table, &$object, $keyName = NULL)
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
	 * @param [type] $updateNulls
	 */
	public function updateObject($table, &$object, $keyName, $updateNulls=false)
	{
		$fmtsql = 'UPDATE '.$this->nameQuote($table).' SET %s WHERE %s';
		$tmp = array();

		foreach (get_object_vars($object) as $k => $v) {
			if (is_array($v) or is_object($v) or $k[0] == '_') { // internal or NA field
				continue;
			}

			if ($k == $keyName) {
				// PK not to be updated
				$where = $keyName . '=' . $this->Quote($v);
				continue;
			}

			if ($v === null) {
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

		// Nothing to update.
		if (empty($tmp)) {
			return true;
		}

		$this->setQuery(sprintf($fmtsql, implode(",", $tmp) , $where));
		return $this->query();
	}

	/**
	 * Description
	 */
	public function insertid()
	{
		//return mssql_insert_id($this->_connection);
		$this->setQuery('SELECT @@IDENTITY');
		return $this->loadResult();
	}

	/**
	 * Description
	 */
	public function getVersion()
	{
		//return mssql_get_server_info($this->_connection);
		return '5.1.0';
	}

	/**
	 * Assumes database collation in use by sampling one text field in one table
	 *
	 * @return	string	Collation in use
	 */
	public function getCollation ()
	{
		/*if ($this->hasUTF()) {
			$this->setQuery('SHOW FULL COLUMNS FROM #__content');
			$array = $this->loadAssocList();
			return $array['4']['Collation'];
		} else {
			return "N/A (mssql < 4.1.2)";
		}*/
	}

	/**
	 * Description
	 *
	 * @return	array	A list of all the tables in the database
	 */
	public function getTableList()
	{
		$this->setQuery('SHOW TABLES');
		return $this->loadResultArray();
	}

	/**
	 * Shows the CREATE TABLE statement that creates the given tables
	 *
	 * @param	array|string	A table name or a list of table names
	 * @return	array	A list the create SQL for the tables
	 */
	public function getTableCreate($tables)
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
	 * @param	array|string	A table name or a list of table names
	 * @param	boolean			Only return field types, default true
	 * @return	array	An array of fields by table
	 */
	public function getTableFields($tables, $typeonly = true)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval) {
			//$this->setQuery('SHOW FIELDS FROM ' . $tblval);
			
			$tblval = $this->replacePrefix((string) $tblval);
			$this->setQuery('select column_name as Field, data_type as Type, is_nullable as \'Null\', column_default as \'Default\' from information_schema.columns where table_name= ' . $this->Quote($tblval));
			$fields = $this->loadObjectList();

			if ($typeonly) {
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type);
				}
			} else {
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = $field;
				}
			}
		}

		return $result;
	}
}
