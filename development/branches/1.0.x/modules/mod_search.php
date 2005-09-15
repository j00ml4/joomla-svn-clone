<?php
/**
* @version $Id: mod_search.php 141 2005-09-12 12:13:49Z stingrey $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from works
* licensed under the GNU General Public License or other free or open source
* software licenses. See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$button			= $params->get( 'button', '' );
$button_pos		= $params->get( 'button_pos', 'left' );
$button_text	= $params->get( 'button_text', _SEARCH_TITLE );
$width 			= intval( $params->get( 'width', 20 ) );
$text 			= $params->get( 'text', _SEARCH_BOX );

$output = '<input name="searchword" id="mod_search_searchword" alt="search" class="inputbox'. $moduleclass_sfx .'" type="text" size="'. $width .'" value="'. $text .'"  onblur="if(this.value==\'\') this.value=\''. $text .'\';" onfocus="if(this.value==\''. $text .'\') this.value=\'\';" />';

if ( $button ) {
	$button = '<input type="submit" value="'. $button_text .'" class="button'. $moduleclass_sfx .'"/>';
}

switch ( $button_pos ) {
	case 'top':
		$button = $button .'<br/>';
		$output = $button . $output;
		break;

	case 'bottom':
		$button =  '<br/>'. $button;
		$output = $output . $button;
		break;

	case 'right':
		$output = $output . $button;
		break;

	case 'left':
	default:
		$output = $button . $output;
		break;
}
?>

<form action="<?php echo sefRelToAbs("index.php"); ?>" method="post">
	<div align="left" class="search<?php echo $moduleclass_sfx; ?>">
		<?php echo $output; ?>
	</div>

	<input type="hidden" name="option" value="search" />
</form>