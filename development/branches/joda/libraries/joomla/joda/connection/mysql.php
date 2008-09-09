<?php
/**
 * @version     $Id$
 *
 * @package     Joomla.Framework
 * @subpackage  Joda
 *
 * @copyright    Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 */

/**
 * Check to ensure this file is within the rest of the framework
 */
defined( 'JPATH_BASE' ) or die();


/**
 * MySQL Connection Class
 *
 * @package     Joomla.Framework
 * @subpackage  Joda
 * @author      Plamen Petkov <plamendp@zetcom.bg>
 *
 */
class JConnectionMySQL extends JConnection
{
    protected $_drivername            = "mysql";
    protected $_port                      = "3306";
    protected $_transaction_isolevel  = Joda::REPEATABLE_READ;

    /**
    * This driver Transaction Isolation Level Names
    *
    * @var array
    */
    protected $_isolevel_names = array(
        Joda::READ_COMMITED     => "READ COMMITED",
        Joda::REPEATABLE_READ   => "REPEATABLE READ",
        Joda::READ_UNCOMMITTED  => "READ UNCOMMITTED",
        Joda::SERIALIZABLE      => "SERIALIZABLE"
    );

    /**
     * Class constructor
     *
     * @param array Options
     *
     */
     function __construct($options)
    {
        $this->_host = $options["host"];
        $this->_database = $options["database"];
        $this->_user = $options["user"];
        $this->_password = $options["password"];
        $this->_port = $options["port"];
        parent::__construct();

        // Set driver/connection specific PDO options

        // Buffered query must be turn ON, transaction problems arise otherwise.
        // Commit() without fetchAll() brings "There is already active transaction..."
        // Stupid MySQL! Not sure about versions though!
        $this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY , true);
    }


} //JConnection


?>