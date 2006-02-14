<?php
/**
* @version $Id: mod_rssfeed.php 588 2005-10-23 15:20:09Z stingrey $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

//check if cache diretory is writable as cache files will be created for the feed
$cacheDir = $mosConfig_cachepath .'/';
if ( !is_writable( $cacheDir ) ) {
	echo '<div>';
	echo JText::_( 'Please make cache directory writable.' );
	echo '</div>';
	return;
}

// module params
$moduleclass_sfx   	= $params->get( 'moduleclass_sfx' );
$rssurl 			= $params->get( 'rssurl', '');
$rssitems 			= $params->get( 'rssitems', 5 );
$rssdesc 			= $params->get( 'rssdesc', 1 );
$rssimage 			= $params->get( 'rssimage', 1 );
$rssitemdesc		= $params->get( 'rssitemdesc', 1 );
$words 				= $params->def( 'word_count', 0 );
$rsstitle			= $params->get( 'rsstitle', 1 );

$cacheDir 			= JPATH_SITE .'/cache/';
$LitePath 			= JPATH_SITE .'/includes/Cache/Lite.php';

if(empty($rssurl)) {
	//TODO : provided warning : No feedurl specified
	return;
}

$rssDoc =& JFactory::getXMLParser('RSS');
$rssDoc->useCacheLite(true, $LitePath, $cacheDir, 3600);
$rssDoc->useHTTPClient(true); 
$success = $rssDoc->loadRSS( $rssurl );

if ( $success ) {
	$totalChannels 	= $rssDoc->getChannelCount();
	
	for ( $i = 0; $i < $totalChannels; $i++ ) {
		$currChannel =& $rssDoc->getChannel($i);
		$elements 	= $currChannel->getElementList();
		$iUrl		= 0;
		foreach ( $elements as $element ) {
			//image handling
			if ( $element == 'image' ) {
				$image =& $currChannel->getElement( DOMIT_RSS_ELEMENT_IMAGE );
				$iUrl	= $image->getUrl();
				$iTitle	= $image->getTitle();
			}
		}
	
		// feed title
		?>
		<table cellpadding="0" cellspacing="0" class="moduletable<?php echo $moduleclass_sfx; ?>">
		<?php
		// feed description
		if ( $currChannel->getTitle() && $rsstitle ) {
			?>
			<tr>
				<td>
					<strong>
					<a href="<?php echo ampReplace( $currChannel->getLink() ); ?>" target="_blank">
						<?php echo $currChannel->getTitle(); ?></a>
					</strong>
				</td>
			</tr>
			<?php
		}
	
		// feed description
		if ( $rssdesc ) {
			?>
			<tr>
				<td>
					<?php echo $currChannel->getDescription(); ?>
				</td>
			</tr>
			<?php
		}
	
		// feed image
		if ( $rssimage && $iUrl ) {
			?>
			<tr>
				<td align="center">
					<image src="<?php echo $iUrl; ?>" alt="<?php echo @$iTitle; ?>"/>
				</td>
			</tr>
			<?php
		}
	
		$actualItems = $currChannel->getItemCount();
		$setItems = $rssitems;
	
		if ($setItems > $actualItems) {
			$totalItems = $actualItems;
		} else {
			$totalItems = $setItems;
		}
	
		?>
		<tr>
			<td>
				<ul class="newsfeed<?php echo $moduleclass_sfx; ?>">
				<?php
				for ($j = 0; $j < $totalItems; $j++) {
					$currItem =& $currChannel->getItem($j);
					// item title
	
					// START fix for RSS enclosure tag url not showing
					if ($currItem->getLink()) {
					?>
						<a href="<?php echo $currItem->getLink(); ?>" target="_child">
						<?php echo $currItem->getTitle(); ?>
						</a>
					<?php
					} else if ($currItem->getEnclosure()) {
						$enclosure = $currItem->getEnclosure();
						$eUrl	= $enclosure->getUrl();
					?>
						<a href="<?php echo $eUrl; ?>" target="_child">
						<?php echo $currItem->getTitle(); ?>
						</a>
					<?php
					}  else if (($currItem->getEnclosure()) && ($currItem->getLink())) {
						$enclosure = $currItem->getEnclosure();
						$eUrl	= $enclosure->getUrl();
					?>
						<a href="<?php $currItem->getLink(); ?>" target="_child">
						<?php echo $currItem->getTitle(); ?>
						</a><br>
						Link: <a href="<?php echo $eUrl; ?>" target="_child">
						<?php echo $eUrl; ?>
						</a>
					<?php
					}
					// END fix for RSS enclosure tag url not showing
	
					// item description
					if ( $rssitemdesc ) {
						// item description
						$text = html_entity_decode( $currItem->getDescription() );
	                    $text = str_replace('&apos;', "'", $text);
	
						// word limit check
						if ( $words ) {
							$texts = explode( ' ', $text );
							$count = count( $texts );
							if ( $count > $words ) {
								$text = '';
								for( $i=0; $i < $words; $i++ ) {
									$text .= ' '. $texts[$i];
								}
								$text .= '...';
							}
						}
						?>
						<div>
							<?php echo $text; ?>
						</div>
						<?php
					}
					?>
				</li>
				<?php
				}
				?>
				</ul>
			</td>
		</tr>
		</table>
		<?php
	}
}
?>