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
 * User Table Class (SQL)
 *
 * @package     Joomla.Framework
 * @subpackage  Joda
 * @author      Plamen Petkov <plamendp@zetcom.bg>
 *
 */
class JRelationUser extends JRelation
{

    /**
     * Description
     *
     * @param
     * @return
     */
     function __construct($connectionname="")
    {
        parent::__construct($connectionname);
        $qb = $this->getQueryBuilder();
        $qb->select("*")->from('#__users');
        $this->setSQL($qb->getSQL());
    }


} //JConnection


?>
