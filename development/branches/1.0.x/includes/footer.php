<?php
/**
* @version $Id: footer.php 173 2005-09-13 10:00:33Z eddieajau $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $_VERSION;

// NOTE - You may change this file to suit your site needs
?>
<div align="center">
	(C) <?php echo date( 'Y' ) . ' ' . $GLOBALS['mosConfig_sitename'];?>
</div>

<div align="center">
	<?php echo $_VERSION->URL; ?>
</div>