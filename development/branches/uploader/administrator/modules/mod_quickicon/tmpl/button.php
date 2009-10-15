<?php
/**
 * @version		$Id: button.php 12678 2009-09-08 08:11:52Z severdia $
 * @package		Joomla.Administrator
 * @subpackage	mod_quickicon
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

?>

<div class="icon-wrapper">
	<div class="icon">
		<a href="<?php echo $button['link']; ?>">
			<?php echo JHtml::_('image.site', $button['image'], $button['imagePath'], NULL, NULL, $button['text']); ?>
			<span><?php echo $button['text']; ?></span></a>
	</div>
</div>