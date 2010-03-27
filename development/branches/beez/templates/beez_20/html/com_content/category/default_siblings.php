<?php
/**
 * @version		$Id: default_siblings.php 13151 2009-10-11 17:10:52Z severdia $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$params = new JParameter($app->getTemplate(true)->params);

if(!$params->get('html5', 0))
{
	require(JPATH_BASE.'/components/com_content/views/category/tmpl/default_siblings.php');
	//evtl. ersetzen durch JPATH_COMPONENT.'/views/...'
} else {
?>
<?php if (empty($this->siblings)) : ?>
	no siblings
<?php else : ?>
	<h3>Siblings</h3>
	<ul>
		<?php foreach ($this->siblings as &$item) : ?>
		<li>
			<?php if ($item->id != $this->item->id) : ?>
			<a href="<?php echo JRoute::_(ContentRoute::category($item->slug)); ?>">
				<?php echo $this->escape($item->title); ?></a>
			<?php else : ?>
				<?php echo $this->escape($item->title); ?>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>
<?php } ?>