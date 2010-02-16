<?php
/**
 * @version		$Id: button.php 12586 2009-07-30 23:27:38Z pentacle $
 * @package		Hathor Accessible Administrator Template
 * @since		1.6
 * @version  	1.04
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * remove the duplicate alt text on the icons
 */

// No direct access.
defined('_JEXEC') or die;

?>

<div class="icon-wrapper">
	<div class="icon">
		<a href="<?php echo $button['link']; ?>">
			<?php echo JHtml::_('image', 'header/'.$button['image'], NULL, NULL, true); ?>
			<span><?php echo $button['text']; ?></span></a>
	</div>
</div>