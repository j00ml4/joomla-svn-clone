<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

/**
 * Utility class for creating HTML Grids
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	Content
 * @since		1.5
 */
class JHtmlContent
{
	/**
	 * Displays warnings relating to an article (emtpy metadecs, metakey, etc)
	 *
	 * @param	object $article	The article object
	 * @return	string
	 */
	function warnings($article)
	{
		$html		= '';
		$warnings	= array();
		if (empty($article->metadesc)) {
			$warnings[] = JText::_('Content_Warning_Empty_metadesc');
		}
		if (empty($article->metakey)) {
			$warnings[] = JText::_('Content_Warning_Empty_metakey');
		}
		if (!empty($warnings)) {
			JHtml::_('behavior.tooltip');
			$html	= '<span class="hasTip" title="'.JText::_('Content_Warning_Title').'::'.implode('<br />', $warnings).'">'
					. '<img src="'.JURI::root().'media/system/images/warning.png" border="0"  alt="" /></span>';
		}
		return $html;
	}

	/**
	 * Displays the publishing state legend for articles
	 */
	function Legend()
	{
		?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr align="center">
			<td>
			<img src="images/publish_y.png" width="16" height="16" border="0" alt="<?php echo JText::_('Pending'); ?>" />
			</td>
			<td>
			<?php echo JText::_('Published, but is'); ?> <u><?php echo JText::_('Pending'); ?></u> |
			</td>
			<td>
			<img src="images/publish_g.png" width="16" height="16" border="0" alt="<?php echo JText::_('Visible'); ?>" />
			</td>
			<td>
			<?php echo JText::_('Published and is'); ?> <u><?php echo JText::_('Current'); ?></u> |
			</td>
			<td>
			<img src="images/publish_r.png" width="16" height="16" border="0" alt="<?php echo JText::_('Finished'); ?>" />
			</td>
			<td>
			<?php echo JText::_('Published, but has'); ?> <u><?php echo JText::_('Expired'); ?></u> |
			</td>
			<td>
			<img src="images/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_('Finished'); ?>" />
			</td>
			<td>
			<?php echo JText::_('Not Published'); ?> |
			</td>
			<td>
			<img src="images/disabled.png" width="16" height="16" border="0" alt="<?php echo JText::_('Archived'); ?>" />
			</td>
			<td>
			<?php echo JText::_('Archived'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="10" align="center">
			<?php echo JText::_('Click on icon to toggle state.'); ?>
			</td>
		</tr>
		</table>
		<?php
	}
}