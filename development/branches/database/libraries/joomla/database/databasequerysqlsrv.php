<?php
/**
 * @version		$Id: databasequery.php 18383 2010-08-10 05:07:25Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
require_once('databasequery.php');
/**
 * Query Element Class.
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.6
 */
class JDatabaseQueryElementSQLSrv extends JDatabaseQueryElement
{
	/**
	 * Constructor.
	 *
	 * @param	string	$name		The name of the element.
	 * @param	mixed	$elements	String or array.
	 * @param	string	$glue		The glue for elements.
	 *
	 * @return	JDatabaseQueryElementSQLSrv
	 * @since	1.6
	 */
	public function __construct($name, $elements, $glue = ',')
	{
		parent::__construct($name, $elements, $glue);
	}
}

/**
 * Query Building Class.
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.6
 */
class JDatabaseQuerySQLSrv extends JDatabaseQuery
{
	/**
	 * @param	mixed	$columns	A string or an array of field names.
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function select($columns)
	{
		$this->_type = 'select';

		if (is_null($this->_select)) {
			$this->_select = new JDatabaseQueryElementSQLSrv('SELECT', $columns);
		}
		else {
			$this->_select->append($columns);
		}

		return $this;
	}

	/**
	 * @param	string	$table	The name of the table to delete from.
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function delete($table = null)
	{
		$this->_type	= 'delete';
		$this->_delete	= new JDatabaseQueryElementSQLSrv('DELETE', null);

		if (!empty($table)) {
			$this->from($table);
		}

		return $this;
	}

	/**
	 * @param	mixed	$tables	A string or array of table names.
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function insert($tables)
	{
		$this->_type	= 'insert';
		$this->_insert	= new JDatabaseQueryElementSQLSrv('INSERT INTO', $tables);

		return $this;
	}

	/**
	 * @param	mixed	$tables	A string or array of table names.
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function update($tables)
	{
		$this->_type = 'update';
		$this->_update = new JDatabaseQueryElementSQLSrv('UPDATE', $tables);

		return $this;
	}

	/**
	 * @param	mixed	A string or array of table names.
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function from($tables)
	{
		if (is_null($this->_from)) {
			$this->_from = new JDatabaseQueryElementSQLSrv('FROM', $tables);
		}
		else {
			$this->_from->append($tables);
		}

		return $this;
	}

	/**
	 * @param	string	$type
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function join($type, $conditions)
	{
		if (is_null($this->_join)) {
			$this->_join = array();
		}
		$this->_join[] = new JDatabaseQueryElementSQLSrv(strtoupper($type) . ' JOIN', $conditions);

		return $this;
	}

	/**
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function innerJoin($conditions)
	{
		$this->join('INNER', $conditions);

		return $this;
	}

	/**
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function outerJoin($conditions)
	{
		$this->join('OUTER', $conditions);

		return $this;
	}

	/**
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function leftJoin($conditions)
	{
		$this->join('LEFT', $conditions);

		return $this;
	}

	/**
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function rightJoin($conditions)
	{
		$this->join('RIGHT', $conditions);

		return $this;
	}

	/**
	 * @param	mixed	$conditions	A string or array of conditions.
	 * @param	string	$glue
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function set($conditions, $glue=',')
	{
		if (is_null($this->_set)) {
			$glue = strtoupper($glue);
			$this->_set = new JDatabaseQueryElementSQLSrv('SET', $conditions, "\n\t$glue ");
		}
		else {
			$this->_set->append($conditions);
		}

		return $this;
	}

	/**
	 * @param	mixed	$conditions	A string or array of where conditions.
	 * @param	string	$glue
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function where($conditions, $glue='AND')
	{
		if (is_null($this->_where)) {
			$glue = strtoupper($glue);
			$this->_where = new JDatabaseQueryElementSQLSrv('WHERE', $conditions, " $glue ");
		}
		else {
			$this->_where->append($conditions);
		}

		return $this;
	}

	/**
	 * @param	mixed	$columns	A string or array of ordering columns.
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function group($columns)
	{
		if (is_null($this->_group)) {
			$this->_group = new JDatabaseQueryElementSQLSrv('GROUP BY', $columns);
		}
		else {
			$this->_group->append($columns);
		}

		return $this;
	}

	/**
	 * @param	mixed	$conditions	A string or array of columns.
	 * @param	string	$glue
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function having($conditions, $glue='AND')
	{
		if (is_null($this->_having)) {
			$glue = strtoupper($glue);
			$this->_having = new JDatabaseQueryElementSQLSrv('HAVING', $conditions, " $glue ");
		}
		else {
			$this->_having->append($conditions);
		}

		return $this;
	}

	/**
	 * @param	mixed	$columns	A string or array of ordering columns.
	 *
	 * @return	JDatabaseQuerySQLSrv	Returns this object to allow chaining.
	 * @since	1.6
	 */
	function order($columns)
	{
		if (is_null($this->_order)) {
			$this->_order = new JDatabaseQueryElementSQLSrv('ORDER BY', $columns);
		}
		else {
			$this->_order->append($columns);
		}

		return $this;
	}

	/**
	 * @param string $name  A string
	 *
	 * @return  Show table query syntax
	 * @since 1.6
	 */
	function showTables($name)
	{
		$this->select('NAME');
		$this->from($name.'..sysobjects');
		$this->where('xtype = \'U\'');
		 
		return $this;
	}
	 
	/**
	 * @param string $table_name  A string
	 *
	 * @return  Drop if exists syntax
	 * @since 1.6
	 */
	function dropIfExists($table_name)
	{
		$this->_type = 'drop';

		$drop_syntax = 'IF EXISTS(SELECT TABLE_NAME FROM'.
                    ' INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = \''
                    .$table_name.'\') DROP TABLE';

                    if (is_null($this->_drop)) {
                    	$this->_drop = new JDatabaseQueryElementSQLSrv($drop_syntax, $table_name);
                    }

                    return $this;
	}
	 
	/**
	 * @param string $table_name  A string
	 * @param object $db  Database object
	 * @param string $prefix  A string
	 * @param string $backup  A string
	 * 
	 * @return  Rename table syntax
	 * @since 1.6
	 */
	function renameTable($table_name, &$db, $prefix = null, $backup = null)
	{
		 $this->_type = 'rename';
		 $constraints = array();
		 
		 if(!is_null($prefix) && !is_null($backup)){
		 	$constraints = $this->_get_table_constraints($table_name, $db);
		 }
		 
		 if(!empty($constraints))
		 	$this->_renameConstraints($constraints, $prefix, $backup, $db);
		  
		 if (is_null($this->_rename)) {
		 	$this->_rename = new JDatabaseQueryElementSQLSrv('sp_rename', $table_name);
		 }
		 else {
		 	$this->_rename->append($table_name);
		 }
	
		 return $this;
	}
	 
	/**
	 * @param string $table_name  A string
	 * @param string $prefix  A string
	 * @param string $backup  A string
	 * @param object $db  Database object
	 * @return  Rename Constraints syntax
	 * @since 1.6
	 */
	private function _renameConstraints($constraints = array(), $prefix = null, $backup = null, $db)
	{
		foreach($constraints as $constraint)
		{
			$db->setQuery('sp_rename '.$constraint.','.str_replace($prefix, $backup, $constraint));
			$db->query();
			
			// Check for errors.
			if ($db->getErrorNum()) {
				
			}
		}
	}
	
	/**
	 * @param string $table_name  A string
	 * @param object $db  Database object
	 * @return  Any constraints available for the table
	 * @since 1.6
	 */
	private function _get_table_constraints($table_name, $db)
	{
		$sql = "SELECT CONSTRAINT_NAME FROM".
				" INFORMATION_SCHEMA.TABLE_CONSTRAINTS".
				" WHERE TABLE_NAME = ".$db->quote($table_name);
		$db->setQuery($sql);
		return $db->loadResultArray();
	}
	/**
	 * @param string $table_name  A string
	 * @param boolean $increment_field Provinding value for autoincrement primary key or not
	 * @return  JDatabaseQuerySQLSrv  Returns this object to allow chaining.
	 * @since 1.6
	 */
	function insertInto($table_name, $increment_field=false)
	{
		$this->_type = 'insert_into';
		if($increment_field)
		$this->_auto_increment_field = 'SET IDENTITY_INSERT '.$table_name;
		 
		$this->_insert_into = new JDatabaseQueryElementSQLSrv('INSERT INTO', $table_name);
		 
		return $this;
	}
	 
	/**
	 * @param string $fields A string
	 *
	 * @return  JDatabaseQuerySQLSrv  Returns this object to allow chaining.
	 * @since 1.6
	 */
	function fields($fields)
	{
		if (is_null($this->_fields)) {
			$this->_fields = new JDatabaseQueryElementSQLSrv('(', $fields);
		}
		else {
			$this->_fields->append($fields);
		}

		return $this;
	}
	 
	/**
	 * @param string $values  A string
	 *
	 * @return  JDatabaseQuerySQLSrv  Returns this object to allow chaining.
	 * @since 1.6
	 */
	function values($values)
	{
		if (is_null($this->_values)) {
			$this->_values = new JDatabaseQueryElementSQLSrv('VALUES (', $values);
		}
		else {
			$this->_values->append($values);
		}

		return $this;
	}
	 
	/**
	 * @param string $query A string
	 *
	 * @return  JDatabaseQuerySQLSrv  Returns this object to allow chaining.
	 * @since 1.6
	 */
	function auto_increment($query)
	{
		return $this->_auto_increment_field.' ON;'.$query.';'.$this->_auto_increment_field.' OFF;' ;
	}
	 
	/**
	 * @param $field A string
	 *
	 * @return  JDatabaseQuerySQLSrv  Returns this object to allow chaining.
	 * @since 1.6
	 */
	function castToChar($field)
	{
		return 'CAST('.$field.' as nvarchar(10))';
	}
	 
	/**
	 * @param $field A string
	 *
	 * @return  JDatabaseQuerySQLSrv  Returns this object to allow chaining.
	 * @since 1.6
	 */
	function charLength($field)
	{
		return 'DATALENGTH('.$field.') IS NOT NULL';
	}
	 
	/**
	 * @param array $fields
	 *
	 * @param string separator
	 * @return  String concantenaation of all the fields
	 * @since 1.6
	 */
	function concat($fields, $separator = null)
	{
		if($separator)
		{
			return '('.implode("+'".$separator."'+", $fields).')';
		}else{
			return '('.implode('+', $fields).')';
		}
	}
	 
	/**
	 * @param string $field
	 *
	 * @param string separator
	 * @return  Length function for the field
	 * @since 1.6
	 */
	function length($field)
	{
		return 'LEN('.$field.')';
	}
	 
	/**
	 *
	 * @return  NOW function
	 * @since 1.6
	 */
	function now()
	{
		return 'GETDATE()';
	}
}