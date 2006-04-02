<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Load compatibility files
 *
 * @package		Joomla.Framework
 * @subpackage	Compatibility
 * @since		1.1
 */

if (phpversion() < '4.2.0') {
	jimport('joomla.common.compat.php41x' );
}
if (phpversion() < '4.3.0') {
	jimport('joomla.common.compat.php42x' );
}
if (version_compare( phpversion(), '5.0' ) < 0) {
	jimport('joomla.common.compat.php50x' );
}
?>