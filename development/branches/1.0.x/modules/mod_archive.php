<?php
/**
* @version $Id: mod_archive.php 141 2005-09-12 12:13:49Z stingrey $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from works
* licensed under the GNU General Public License or other free or open source
* software licenses. See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $mosConfig_offset;

$count 	= intval( $params->def( 'count', 10 ) );
$now 	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset * 60 * 60 );

$query = "SELECT MONTH(created) AS created_month, created, id, sectionid, title, YEAR(created) AS created_year"
. "\n FROM #__content"
. "\n WHERE ( state = -1 AND checked_out = 0 AND sectionid > 0 )"
. "\n GROUP BY created_year DESC, created_month DESC LIMIT $count";
$database->setQuery( $query );
$rows = $database->loadObjectList();
?>
<ul>
<?php
foreach ( $rows as $row ) {
	$created_month 	= mosFormatDate ( $row->created, "%m" );
	$month_name 	= mosFormatDate ( $row->created, "%B" );
	$created_year 	= mosFormatDate ( $row->created, "%Y" );
	$link			= sefRelToAbs( 'index.php?option=com_content&amp;task=archivecategory&amp;year='. $created_year .'&amp;month='. $created_month .'&amp;module=1' );
	$text			= $month_name .', '. $created_year;
	?>
	<li>
		<a href="<?php echo $link; ?>">
			<?php echo $text; ?></a>
	</li>
	<?php
}
?>
</ul>