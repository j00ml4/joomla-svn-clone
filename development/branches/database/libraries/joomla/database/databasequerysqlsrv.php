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
	 * @return	JDatabaseQueryMsSQLElementMsSQL
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
	 * @return	JDatabaseQueryMsSQL	Returns this object to allow chaining.
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
   * @param string $table_name  A string 
   * 
   * @return  JDatabaseQuerySQLSrv  Returns this object to allow chaining.
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
   * @return  JDatabaseQuery  Returns this object to allow chaining.
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
   * 
   * @return  JDatabaseQuery  Returns this object to allow chaining.
   * @since 1.6
   */
   function renameTable($table_name)
   {
     $this->_type = 'rename';

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
   * @param boolean $increment_field Provinding value for autoincrement primary key or not
   * @return  JDatabaseQuery  Returns this object to allow chaining.
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
   * @return  JDatabaseQuery  Returns this object to allow chaining.
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
   * @return  JDatabaseQuery  Returns this object to allow chaining.
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
   * 
   * 
   * @return  JDatabaseQuery  Returns this object to allow chaining.
   * @since 1.6
   */
   function auto_increment($query)
   {
     return $this->_auto_increment_field.' ON;'.$query.';'.$this->_auto_increment_field.' OFF;' ;
   }
}