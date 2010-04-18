<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	mod_social_summary
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');
?>

<table class="adminlist">
	<thead>
		<tr>
			<td class="title">
				<strong><?php echo JText::_('Title'); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_('Submitted'); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_('Author'); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_('State'); ?></strong>
			</td>
		</tr>
	</thead>
	<tbody>
		<?php
		if (!empty($list)):
			foreach($list as $item):
				$itemTitle = JText::sprintf('COMMENTS_RE', !empty($item->subject) ? $item->subject : $item->page_title);
				$itemTitle = htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8');
				$itemRoute = JRoute::_('index.php?option=com_social&view=comment&c_id='.$item->id);

				switch ($item->published)
				{
					case 0:
						$stateTitle = JText::_('COMMENTS_STATE_QUEUED');
						$stateRoute = JRoute::_('index.php?option=com_social&view=comments&filter_state=0');
						break;
					case 1:
						$stateTitle = JText::_('COMMENTS_STATE_PUBLISHED');
						$stateRoute = JRoute::_('index.php?option=com_social&view=comments&filter_state=1');
						break;
					case 2:
						$stateTitle = JText::_('COMMENTS_STATE_SPAM');
						$stateRoute = JRoute::_('index.php?option=com_social&view=comments&filter_state=2');
						break;
				}
				?>
				<tr>
					<td>
						<a href="<?php echo $itemRoute; ?>" title="<?php echo $itemTitle; ?>">
							<?php echo $itemTitle; ?>
						</a>
					</td>
					<td>
						<?php echo JHtml::date($item->created_date, $params->get('dformat', '%b %e, %Y %T')); ?>
					</td>
					<td>
						<?php echo $item->name; ?>
					</td>
					<td>
						<a href="<?php echo $stateRoute; ?>" title="<?php echo $stateTitle; ?>">
							<?php echo $stateTitle; ?>
						</a>
					</td>
				</tr>
				<?php
			endforeach;
		endif;
		?>
	</tbody>
</table>