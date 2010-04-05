<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');
?>
	<div class="metadata">
		<p class="author">
			<a href="<?php echo $item->url;?>" target="_blank" rel="nofollow"><?php echo $item->subject; ?></a>
			<?php echo JText::sprintf('Comments_Makes_This_Comment', JRoute::_('#comment-'.$item->id)); ?>
		</p>
		<p class="date">
			<?php echo JHtml::_('date', $item->created_date, $params->get('date_format')); ?>
		</p>
	</div>
	<div class="comment">
		[...] <?php echo $item->body; ?> [...]
	</div>
