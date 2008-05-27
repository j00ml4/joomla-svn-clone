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
 * PgSQL Connection Class
 *
 * @package     Joomla.Framework
 * @subpackage  Joda
 * @author      Plamen Petkov <plamendp@zetcom.bg>
 *
 */
class JConnectionPgSQL extends JConnection
{
    public $driver                 = "pgsql";
    public $port                   = "5432";
    public $transaction_isolevel   = Joda::READ_COMMITED;
    public $driver_options         = array();

    /**
    * This driver Transaction Isolation Level Names
    *
    * @var array
    */
    public $isolevel_names = array(
        Joda::READ_COMMITED     => "READ COMMITED",
        Joda::REPEATABLE_READ   => "REPEATABLE READ",
        Joda::READ_UNCOMMITTED  => "READ UNCOMMITTED",
        Joda::SERIALIZABLE      => "SERIALIZABLE"
    );

    /**
     * Description
     *
     * @param
     * @return
     */
     function __construct($options)
    {
        $this->host = $options["host"];
        $this->database = $options["database"];
        $this->user = $options["user"];
        $this->password = $options["password"];
        $this->port = $options["port"];
        parent::__construct();
    }


} //JConnection


?>