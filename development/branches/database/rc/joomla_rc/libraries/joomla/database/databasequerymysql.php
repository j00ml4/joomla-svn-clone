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
class JDatabaseQueryElementMySQL extends JDatabaseQueryElement
{
	/**
	 * Constructor.
	 *
	 * @param	string	$name		The name of the element.
	 * @param	mixed	$elements	String or array.
	 * @param	string	$glue		The glue for elements.
	 *
	 * @return	JDatabaseQueryElementMySQL
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
class JDatabaseQueryMySQL extends JDatabaseQuery
{
	/**
	 * @param	mixed	$columns	A string or an array of field names.
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function select($columns)
	{
		$this->_type = 'select';

		if (is_null($this->_select)) {
			$this->_select = new JDatabaseQueryElementMySQL('SELECT', $columns);
		}
		else {
			$this->_select->append($columns);
		}

		return $this;
	}

	/**
	 * @param	string	$table	The name of the table to delete from.
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function delete($table = null)
	{
		$this->_type	= 'delete';
		$this->_delete	= new JDatabaseQueryElementMySQL('DELETE', null);

		if (!empty($table)) {
			$this->from($table);
		}

		return $this;
	}

	/**
	 * @param	mixed	$tables	A string or array of table names.
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function insert($tables)
	{
		$this->_type	= 'insert';
		$this->_insert	= new JDatabaseQueryElementMySQL('INSERT INTO', $tables);

		return $this;
	}

	/**
	 * @param	mixed	$tables	A string or array of table names.
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function update($tables)
	{
		$this->_type = 'update';
		$this->_update = new JDatabaseQueryElementMySQL('UPDATE', $tables);

		return $this;
	}

	/**
	 * @param	mixed	A string or array of table names.
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function from($tables)
	{
		if (is_null($this->_from)) {
			$this->_from = new JDatabaseQueryElementMySQL('FROM', $tables);
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
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function join($type, $conditions)
	{
		if (is_null($this->_join)) {
			$this->_join = array();
		}
		$this->_join[] = new JDatabaseQueryElementMySQL(strtoupper($type) . ' JOIN', $conditions);

		return $this;
	}

	/**
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function innerJoin($conditions)
	{
		$this->join('INNER', $conditions);

		return $this;
	}

	/**
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function outerJoin($conditions)
	{
		$this->join('OUTER', $conditions);

		return $this;
	}

	/**
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function leftJoin($conditions)
	{
		$this->join('LEFT', $conditions);

		return $this;
	}

	/**
	 * @param	string	$conditions
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function rightJoin($conditions)
	{
		$this->join('RIGHT', $conditions);

		return $this;
	}

	/**
	 * @param	mixed	$conditions	A string or array of conditions.
	 * @param	string	$glue
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function set($conditions, $glue=',')
	{
		if (is_null($this->_set)) {
			$glue = strtoupper($glue);
			$this->_set = new JDatabaseQueryElementMySQL('SET', $conditions, "\n\t$glue ");
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
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function where($conditions, $glue='AND')
	{
		if (is_null($this->_where)) {
			$glue = strtoupper($glue);
			$this->_where = new JDatabaseQueryElementMySQL('WHERE', $conditions, " $glue ");
		}
		else {
			$this->_where->append($conditions);
		}

		return $this;
	}

	/**
	 * @param	mixed	$columns	A string or array of ordering columns.
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function group($columns)
	{
		if (is_null($this->_group)) {
			$this->_group = new JDatabaseQueryElementMySQL('GROUP BY', $columns);
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
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function having($conditions, $glue='AND')
	{
		if (is_null($this->_having)) {
			$glue = strtoupper($glue);
			$this->_having = new JDatabaseQueryElementMySQL('HAVING', $conditions, " $glue ");
		}
		else {
			$this->_having->append($conditions);
		}

		return $this;
	}

	/**
	 * @param	mixed	$columns	A string or array of ordering columns.
	 *
	 * @return	JDatabaseQueryMySQL	Returns this object to allow chaining.
	 * @since	1.6
	 */
	public function order($columns)
	{
		if (is_null($this->_order)) {
			$this->_order = new JDatabaseQueryElementMySQL('ORDER BY', $columns);
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
      $this->_type = 'showTables';

      $this->_show_tables = new JDatabaseQueryElementMySQL('SHOW TABLES FROM', $name);

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

      if (is_null($this->_drop)) {
        $this->_drop = new JDatabaseQueryElementMySQL('DROP TABLE IF EXISTS', $table_name);
      }
      else {
        $this->_drop->append($table_name);
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

      if (is_null($this->_rename)) {
        $this->_rename = new JDatabaseQueryElementMySQL('RENAME TABLE', $table_name, ' TO ');
      }
      else {
        $this->_rename->append($table_name);
      }

      return $this;
   }

   /**
   * @param string $table_name  A string 
   * @param boolean $increment_field Provinding value for autoincrement primary key or not
   * @return  JDatabaseQueryMySQL  Returns this object to allow chaining.
   * @since 1.6
   */
   function insertInto($table_name, $increment_field=false)
   {
     $this->_type = 'insert_into';
     $this->_insert_into = new JDatabaseQueryElementMySQL('INSERT INTO', $table_name);
     
      return $this;
   }
   
   /**
   * @param string $fields A string 
   * 
   * @return  JDatabaseQueryMySQL  Returns this object to allow chaining.
   * @since 1.6
   */
   function fields($fields)
   {
     if (is_null($this->_fields)) {
      $this->_fields = new JDatabaseQueryElementMySQL('(', $fields);
    }
    else {
      $this->_fields->append($fields);
    }

    return $this;
   }
   
   /**
   * @param string $values  A string 
   * 
   * @return  JDatabaseQueryMySQL  Returns this object to allow chaining.
   * @since 1.6
   */
   function values($values)
   {
     if (is_null($this->_values)) {
      $this->_values = new JDatabaseQueryElementMySQL('VALUES (', $values);
    }
    else {
      $this->_values->append($values);
    }

    return $this;
   }
   
   /**
   * @param string $query A string
   * 
   * @return  JDatabaseQueryMySQL  Returns this object to allow chaining.
   * @since 1.6
   */
   function auto_increment($query)
   {
     return $query;
   }
   
    
   /**
   * @param $field A string
   * 
   * @return  JDatabaseQueryMySQL  Returns this object to allow chaining.
   * @since 1.6
   */
   function castToChar($field)
   {
     return $field;
   }
   
   /**
   * @param $field A string
   * 
   * @return  JDatabaseQueryMySQL  Returns this object to allow chaining.
   * @since 1.6
   */
   function charLength($field)
   {
     return 'CHAR_LENGTH('.$field.')';
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
       $concat_string = "CONCAT_WS('".$separator."'";
       foreach($fields as $field)
       {
         $concat_string .= ', '.$field;
       }
       return $concat_string.')';
     }else{
       return 'CONCAT('.implode(',', $fields).')';
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
     return 'LENGTH('.$field.')';
   }
   
   /**
   * 
   * @return  NOW function
   * @since 1.6
   */
   function now()
   {
   	 return 'NOW()';
   }
   
   /**
   * Method to lock the database table for writing.
   *
	* @return	boolean	True on success.
	 * @since	1.6
	 */
	public function lock($table_name, &$db)
	{
		// Lock the table for writing.
		$db->setQuery('LOCK TABLES `'.$table_name.'` WRITE');
		$db->query();

		// Check for a database error.
		if ($db->getErrorNum()) {
			//$this->setError($db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Method to unlock the database table for writing.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function unlock(&$db)
	{
		// Unlock the table.
		$db->setQuery('UNLOCK TABLES');
		$db->query();

		// Check for a database error.
		if ($db->getErrorNum()) {
			//$this->setError($db->getErrorMsg());

			return false;
		}

		return true;
	}
}