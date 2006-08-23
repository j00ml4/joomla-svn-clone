<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Application layer database connector class
 * 
 * This class represents the application layer to all database requests.
 * In future a generic JDatasource class may allow requests to other
 * datasources such as content repositories.
 * If a change in the behavior of the application layer e.g. for specific
 * manipulation of data during the access is needed inherit from this class.
 * Do not change or inherit from any of the driver classes in the subfolder.
 * 
 * The class use an apdater patter in order to agregate the connection to the drivers.
 *
 * @abstract
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.0
 */
class JDatabase extends JObject
{
	/** @var object	adapter to the JDBDriver instance */
	var $_dbdriver		= null;
	/** @var int Internal variable to hold the database error number */
	var $_errorNum		= 0;
	/** @var string Internal variable to hold the database error message */
	var $_errorMsg		= '';
	
	/**
	* Database object constructor
	*
	* @param string Database host
	* @param string Database user name
	* @param string Database user password
	* @param string Database name
	* @param string Common prefix for all tables
	*/
	function __construct( $driver='mysql', $host='localhost', $user, $pass, $db='', $table_prefix='')
	{
		// load and set the related database driver
		jimport('joomla.database.dbdriver');
		$this->_dbdriver = JDBDriver::getInstance($driver, $host, $user, $pass, $db, $table_prefix);
		
		// If we opened the connection lets make sure we close it
		register_shutdown_function(array(&$this,'__destruct'));
	}

	/**
	 * Database object destructor
	 *
     * @abstract
     * @access private
     * @return boolean
     * @since 1.5
	 */
	function __destruct()
	{
		return true;
	}

	/**
	 * Returns a reference to the global Database object, only creating it
	 * if it doesn't already exist. And keeps sure that there is only one
	 * instace for a specific combination of the JDatabase signature
	 * 
	 * The reason that the static management is realized within a generic method
	 * and not in this method has to do with the var scops. Only with this generic
	 * method a unique var scope for the static var can be achieved.
	 *
	 * @param string  Database driver
	 * @param string Database host
	 * @param string Database user name
	 * @param string Database user password
	 * @param string Database name
	 * @param string Common prefix for all tables
	 * @return database A database object
	 * @since 1.5
	*/
	function &getInstance( $driver='mysql', $host='localhost', $user, $pass, $db='', $table_prefix='' )
	{
		$signature = serialize(array($driver, $host, $user, $pass, $db, $table_prefix));
		$database = JDatabase::_getStaticInstance($signature,'JDatabase',false);

		return $database;
	}

	/**
	 * This method manages the static instances for a defined JDatabase signature
	 * It is the purpose of this method to allow replacement of the static instances in the
	 * singelton by extended instances for the same signature. It is in the responsiblity of
	 * the extended class to create those different versions.
	 * 
	 * @param string		representing the signature
	 * @param string		class name for the create method
	 * @param boolean		flag to define that the instance shall be created new in any case
	 * @return database		the static database object
	 */
	function &_getStaticInstance( $signature, $factoryClass='JDatabase', $createNew=false ) {
		static $instances;
		
		if (!isset( $instances )) {
			$instances = array();
		}

		if (empty($instances[$signature]) || $createNew) {
			$dbProperties = unserialize( $signature);
			$instances[$signature] = new $factoryClass($dbProperties[0],$dbProperties[1], $dbProperties[2], $dbProperties[3], $dbProperties[4], $dbProperties[5]);
		}
		return $instances[$signature];
	}
	
	/**
	 * Determines UTF support
	 * 
     * @abstract
     * @access public
     * @return boolean
     * @since 1.5
	 */
	function hasUTF() {
		return $this->_dbdriver->hasUTF();
	}

	/**
	 * Custom settings for UTF support
     *
     * @abstract
     * @access public
     * @since 1.5
	 */
	function setUTF() {
		$this->_dbdriver->setUTF();
	}
	
	/**
	 * Adds a field or array of field names to the list that are to be quoted
	 * 
	 * @access public
	 * @param mixed Field name or array of names
	 * @since 1.5
	 */
	function addQuoted( $fieldName ) 
	{
		$this->_dbdriver->addQuoted( $fieldName );
	}
	
	/**
	 * Checks if field name needs to be quoted
	 * 
	 * @access public
	 * @param string The field name
	 * @return bool
	 */
	function isQuoted( $fieldName ) 
	{
		return $this->_dbdriver->isQuoted( $fieldName );
	}

	/**
	 * Sets the debug level on or off
	 * 
	 * @access public
	 * @param int 0 = off, 1 = on
	 */
	function debug( $level ) {
		$this->_dbdriver->debug( $level );
	}

	/**
	 * Get the database UTF-8 support
	 * 
	 * @access public
	 * @return boolean 
	 * @since 1.5
	 */
	function getUtfSupport() {
		return $this->_dbdriver->getUtfSupport();
	}

	/**
	 * Get the error number
	 * 
	 * @access public
	 * @return int The error number for the most recent query
	 */
	function getErrorNum() {
		return $this->_dbdriver->getErrorNum();
	}
	
	
	/**
	 * Get the error message
	 * 
	 * @access public
	 * @return string The error message for the most recent query
	 */
	function getErrorMsg() {
		return $this->_dbdriver->getErrorMsg();
	}
	
	/**
	 * Get a database escaped string
	 * 
     * @abstract
     * @access public
	 * @return string
	 */
	function getEscaped( $text ) {
		return $this->_dbdriver->getEscaped( $text );
	}
	
	/**
	 * Quote an identifier name (field, table, etc)
	 * 
	 * @access public
	 * @param string The name
	 * @return string The quoted name
	 */
	function NameQuote( $s ) {
		return $this->_dbdriver->NameQuote( $s );
	}
	/**
	 * Get the database table prefix
	 * 
	 * @access public
	 * @return string The database prefix
	 */
	function getPrefix() {
		return $this->_dbdriver->getPrefix();
	}
	
	/**
	 * Get the database null date
	 * 
	 * @access public
	 * @return string Quoted null/zero date string
	 */
	function getNullDate() {
		return $this->_dbdriver->getNullDate();
	}
	
	/**
	 * Sets the SQL query string for later execution.
	 * 
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @access public
	 * @param string The SQL query
	 * @param string The offset to start selection
	 * @param string The number of results to return
	 * @param string The common table prefix
	 */
	function setQuery( $sql, $offset = 0, $limit = 0, $prefix='#__' ) 
	{
		 $this->_dbdriver->setQuery( $sql, $offset, $limit, $prefix );
	}

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @access public
	 * @param string The SQL query
	 * @param string The common table prefix
	 * @return string
	 */
	function replacePrefix( $sql, $prefix='#__' ) 
	{
		return $this->_dbdriver->replacePrefix( $sql, $prefix );
	}
	
	/**
	 * Get the active query
	 * 
	 * @access public
	 * @return string The current value of the internal SQL vairable
	 */
	function getQuery() {
		return $this->_dbdriver->getQuery();
	}
	
	/**
	 * Execute the query
	 * 
	 * @abstract
	 * @access public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	function query() {
		return $this->_dbdriver->query();
	}

	/**
	 * Get the affected rows by the most recent query 
	 * 
	 * @abstract
	 * @access public
	 * @return int The number of affected rows in the previous operation
	 * @since 1.0.5
	 */
	function getAffectedRows() {
		return $this->_dbdriver->getAffectedRows();
	}

   /**
	* Execute a batch query
	* 
    * @abstract
    * @access public
	* @return mixed A database resource if successful, FALSE if not.
	*/
	function query_batch( $abort_on_error=true, $p_transaction_safe = false) {
		return $this->_dbdriver->query_batch( $abort_on_error, $p_transaction_safe );
	}

	/**
	 * Diagnostic function
	 * 
     * @abstract
     * @access public
	 */
	function explain() {
		return $this->_dbdriver->explain();
	}

	/**
	 * Get the number of rows returned by the most recent query
	 * 
	 * @abstract
	 * @access public
	 * @param object Database resource
	 * @return int The number of rows
     */
	function getNumRows( $cur=null ) {
		return $this->_dbdriver->getNumRows( $cur );
	}

	/**
	 * This method loads the first field of the first row returned by the query.
	 * 
     * @abstract
     * @access public
	 * @return The value returned in the query or null if the query failed.
	 */
	function loadResult() {
		return $this->_dbdriver->loadResult();
	}

	/**
	 * Load an array of single field results into an array
	 *
     * @abstract
	 */
	function loadResultArray($numinarray = 0) {
		return $this->_dbdriver->loadResultArray($numinarray);
	}
	
	/**
	* Fetch a result row as an associative array
	*
	* @abstract
	*/
	function loadAssoc()
	{
		return $this->_dbdriver->loadAssoc();
	}

	/**
	 * Load a associactive list of database rows
	 * 
     * @abstract
     * @access public
	 * @param string The field name of a primary key
	 * @return array If key is empty as sequential list of returned records.
	 */
	function loadAssocList( $key='' ) {
		return $this->_dbdriver->loadAssocList( $key );
	}
	
	/**
	 * This global function loads the first row of a query into an object
	 * 
	 * 
     * @abstract
     * @access public
	 * @param object
	 */
	function loadObject( ) {
		return $this->_dbdriver->loadObject() ;
	}

	/**
	* Load a list of database objects
    *
    * @abstract
    * @access public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.

    * If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*/
	function loadObjectList( $key='' ) {
		return $this->_dbdriver->loadObjectList( $key );
	}
	
	/**
	 * Load the first row returned by the query
	 * 
	 * @abstract
	 * @access public
     * @return The first row of the query.
	 */
	function loadRow() {
		return $this->_dbdriver->loadRow();
	}
	
	/**
	* Load a list of database rows (numeric column indexing)
	* 
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*
    * @abstract
    * @access public
    * @param string The field name of a primary key
	* @return array
	*/
	function loadRowList( $key='' ) {
		return $this->_dbdriver->loadRowList( $key );
	}
	
	/**
	 * Insert an object in the database
	 * 
	 * @abstract
	 * @access public
     * @param string The table name
	 * @param object
	 * @param string
	 * @param boolean
	 */
	function insertObject( $table, &$object, $keyName = NULL, $verbose=false ) {
		return $this->_dbdriver->insertObject( $table, $object, $keyName, $verbose );
	}

	/**
	 * Update ab object in the database
	 * 
     * @abstract
     * @access public
	 * @param string
	 * @param object
	 * @param string
	 * @param boolean
	 */
	function updateObject( $table, &$object, $keyName, $updateNulls=true ) {
		return $this->_dbdriver->updateObject( $table, $object, $keyName, $updateNulls );
	}

	/**
	 * Print out an error statement
	 * 
	 * @param boolean If TRUE, displays the last SQL statement sent to the database
	 * @return string A standised error message
	 */
	function stderr( $showSQL = false ) {
		return $this->_dbdriver->stderr( $showSQL );
	}

	/**
	 * Get the ID generated from the previous INSERT operation
	 * 
     * @abstract
     * @access public
     * @return mixed
	 */
    function insertid() {
		return $this->_dbdriver->insertid();
	}
	
	/**
	 * Get the database collation
	 * 
	 * @abstract
	 * @access public
     * @return string Collation in use
	 */
	function getCollation() {
		return$this->_dbdriver->getCollation();
	}

    /**
     * Get the version of the database connector
     * 
     * @abstract
	 */
	function getVersion() {
		return $this->_dbdriver->getVersion();
	}

	/**
	 * List tables in a database
	 * 
	 * @abstract
	 * @acces public
     * @return array A list of all the tables in the database
	 */
	function getTableList() {
		return $this->_dbdriver->getTableList();
	}
	
	/**
	 * 
	 * 
	 * @abstract
	 * @access public
     * @param array A list of table names
	 * @return array A list the create SQL for the tables
	 */
	function getTableCreate( $tables ) {
		return $this->_dbdriver->getTableCreate( $tables );
	}
	
	/**
	 * List database table fields
	 * 
	 * @abstract
	 * @acces public
     * @param array A list of table names
	 * @return array An array of fields by table
	 */
	function getTableFields( $tables ) {
		return $this->_dbdriver->getTableFields( $tables );
	}

	// ----
	// ADODB Compatibility Functions
	// ----

	/**
	* Get a quoted database escaped string
	* 
	* @access public
	* @return string
	*/
	function Quote( $text ) {
		return '\'' . $this->getEscaped( $text ) . '\'';
	}
	
	/**
	 * ADODB compatability function
	 * 
	 * @access public
	 * @param string SQL
	 * @since 1.5
	 */
	function GetCol( $query )
	{
		$this->setQuery( $query );
		return $this->loadResultArray();
	}
	
	/**
	 * ADODB compatability function
	 * 
	 * @access public
	 * @param string SQL
	 * @return object
	 * @since 1.5
	 */
	function Execute( $query )
	{
		jimport( 'joomla.database.recordset' );

		$query = trim( $query );
		$this->setQuery( $query );
		if (eregi( '^select', $query )) {
			$result = $this->loadRowList();
			return new JRecordSet( $result );
		} else {
			$result = $this->query();
			if ($result === false) {
				return false;
			} else {
				return new JRecordSet( array() );
			}
		}
	}
	
	/**
	 * ADODB compatability function
	 *
	 * @access public
	 * @since 1.5
	 */
	function SelectLimit( $query, $count, $offset=0 )
	{
		jimport( 'joomla.database.recordset' );

		$this->setQuery( $query, $offset, $count );
		$result = $this->loadRowList();
		return new JRecordSet( $result );
	}
	
	/**
	 * ADODB compatability function
	 *
	 * @access public
	 * @since 1.5
	 */
	function PageExecute( $sql, $nrows, $page, $inputarr=false, $secs2cache=0 )
	{
		jimport( 'joomla.database.recordset' );

		$this->setQuery( $sql, $page*$nrows, $nrows );
		$result = $this->loadRowList();
		return new JRecordSet( $result );
	}
	/**
	 * ADODB compatability function
	 *
	 * @access public
	 * @param string SQL
	 * @return array
	 * @since 1.5
	 */
	function GetRow( $query )
	{
		$this->setQuery( $query );
		$result = $this->loadRowList();
		return $result[0];
	}
	
	/**
	 * ADODB compatability function
	 *
	 * @access public
	 * @param string SQL
	 * @return mixed
	 * @since 1.5
	 */
	function GetOne( $query )
	{
		$this->setQuery( $query );
		$result = $this->loadResult();
		return $result;
	}
	
	/**
	 * ADODB compatability function
	 * 
	 * @since 1.5
	 */
	function BeginTrans() {
	}
	
	/**
	 * ADODB compatability function
	 *
	 * @since 1.5
	 */
	function RollbackTrans() {
	}
	
	/**
	 * ADODB compatability function
	 *
	 * @since 1.5
	 */
	function CommitTrans() {
	}
	
	/**
	 * ADODB compatability function
	 *
	 * @since 1.5
	 */
	function ErrorMsg() {
		return $this->getErrorMsg();
	}
	
	/**
	 * ADODB compatability function
	 *
	 * @since 1.5
	 */
	function ErrorNo() {
		return $this->getErrorNum();
	}
	
	/**
	 * ADODB compatability function
	 *
	 * @since 1.5
	 */
	function GenID( $foo1=null, $foo2=null ) {
		return '0';
	}
}

?>