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
 * Table Class
 *
 * @package     Joomla.Framework
 * @subpackage  Joda
 * @author      Plamen Petkov <plamendp@zetcom.bg>
 *
 */
class JRelation extends JDataset
{
   /**
    * This class internal name
    *
    * @var string
    */
    protected $_name = "";

   /**
    * Relation name this class represents, e.g. table name
    *
    * @var string
    */
    protected $_relation = "";

    /**
     * Constructor.
     *
     * @param
     * @return
     */
    function __construct($name, $relationname, $connectionname="")
    {
        $this->_name = $name;
        $this->_relation = $relationname;
        parent::__construct($connectionname);
    }



    /**
     * Return an instance of JRelation descendant class
     *
     * @param string Relation name, e.g. table name, view, etc.
     * @return object JRelation
     */
    function &getInstance($name, $connectionname="")
    {
        $file = dirname(__FILE__) .DS. "relation" .DS. $name . ".php";
        require_once($file);
        $class = "JRelation" . $name;
        $instance = new $class($connectionname);
        return $instance;
    }



} //JTable

?>