<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from works
* licensed under the GNU General Public License or other free or open source
* software licenses. See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$query = "SELECT menutype, COUNT(id) AS numitems"
. "\n FROM #__menu"
. "\n WHERE published = 1"
. "\n GROUP BY menutype"
;
$database->setQuery( $query );
$rows = $database->loadObjectList();
?>
<table class="adminlist">
	<tr>
		<th class="title" width="80%">
			Menu
		</th>
		<th class="title">
			# Items
		</th>
	</tr>
<?php
foreach ($rows as $row) {
	$link = 'index2.php?option=com_menus&amp;menutype='. $row->menutype;
	?>
	<tr>
		<td>
			<a href="<?php echo $link; ?>">
				<?php echo $row->menutype;?></a>
		</td>
		<td>
			<?php echo $row->numitems;?>
		</td>
	</tr>
<?php
}
?>
<tr>
	<th colspan="2">
	</th>
</tr>
</table>