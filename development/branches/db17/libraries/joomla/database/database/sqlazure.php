<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.database.database');
jimport('joomla.database.database.sqlsrv');
jimport('joomla.utilities.string');
jimport('joomla.filesystem.folder');
JLoader::register('JDatabaseQuerySQLAzure', dirname(__FILE__).'/sqlazurequery.php');
defined('JPATH_PLATFORM') or die;
require_once JPATH_LIBRARIES.'/joomla/database/database/sqlsrv.php';
require_once JPATH_LIBRARIES.'/joomla/database/database.php';
JLoader::register('DatabaseException', JPATH_PLATFORM.'/joomla/database/databaseexception.php');

/**
 * SQL Server database driver
 *
 * @package     Joomla.Platform
 * @subpackage  Database
 * @see         http://msdn.microsoft.com/en-us/library/ee336279.aspx
 * @since       11.1
 */
class JDatabaseSQLAzure extends JDatabaseSQLSrv
{
	/**
	 * The name of the database driver.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $name = 'sqlzure';

	/**
	 * Get the current query or new JDatabaseQuery object.
	 *
	 * @param   boolean  $new  False to return the last query set, True to return a new JDatabaseQuery object.
	 *
	 * @return  mixed  The current value of the internal SQL variable or a new JDatabaseQuery object.
	 *
	 * @since   11.1
	 * @throws  DatabaseException
	 */
	public function getQuery($new = false)
	{
		if ($new) {
			// Make sure we have a query class for this driver.
			if (!class_exists('JDatabaseQuerySQLAzure')) {
				throw new DatabaseException(JText::_('JLIB_DATABASE_ERROR_MISSING_QUERY'));
			}
			return new JDatabaseQuerySQLAzure($this);
		}
		else {
			return $this->sql;
		}
	}
	
}
