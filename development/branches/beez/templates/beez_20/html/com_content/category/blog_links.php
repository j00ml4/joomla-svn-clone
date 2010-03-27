<?php
/**
 * @version		$Id: blog_links.php 14164 2010-01-14 11:06:21Z a.radtke $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$params = new JParameter($app->getTemplate(true)->params);

if(!$params->get('html5', 0))
{
	require(JPATH_BASE.'/components/com_content/views/category/tmpl/blog_links.php');
	//evtl. ersetzen durch JPATH_COMPONENT.'/views/...'
} else {
?>

<?php if (!empty($this->link_items) && $this->params->get('num_links') > 0) :
	$i=0;
?>

<h3><?php echo JText::_('MORE_ARTICLES'); ?></h3>

<ol class="jlinks">

<?php
	foreach ($this->link_items as &$item) :


		if ($i >= $this->params->get('num_links')) :
  			break;
  		endif;
  		$i++;
?>
		 <li>
            <a href="<?php echo JRoute::_(ContentRoute::article($item->slug, $item->catslug)); ?>">
			<?php echo $item->title; ?></a>
        </li>
<?php endforeach; ?>
	</ol>
<?php endif ; ?>

<?php } ?>
