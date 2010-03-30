<?php
/**
 * @version		$Id: default.php 14165 2010-01-14 11:06:43Z a.radtke $
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
	require(JPATH_BASE.'/components/com_content/views/frontpage/tmpl/default.php');
	//evtl. ersetzen durch JPATH_COMPONENT.'/views/...'
} else {
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
$pageClass = $this->params->get('pageclass_sfx');
?>

<section class="blog-featured<?php echo $pageClass;?>">

<?php if ($this->params->get('show_page_title', 1)) : ?>
<h1>
	<?php if ($this->escape($this->params->get('page_heading'))) :?>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	<?php else : ?>
		<?php echo $this->escape($this->params->get('page_title')); ?>
	<?php endif; ?>
</h1>
<?php endif; ?>
<?php $leadingcount=0 ; ?>
<?php if (!empty($this->lead_items)) : ?>
<div class="items-leading">
	<?php foreach ($this->lead_items as &$item) : ?>
		<article class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
			<?php
				$this->item = &$item;
				echo $this->loadTemplate('item');
			?>
		</article>
		<?php
		      $leadingcount=$leadingcount +1;
		?>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<?php
      $introcount=(count($this->intro_items));
      $counter=0;
?>
<?php if (!empty($this->intro_items)) : ?>
	<?php foreach ($this->intro_items as $key => &$item) : ?>

	<?php
	    $key= ($key-$leadingcount)+1;
	    $rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
	    $row =   $counter / $this->columns ;

		if($rowcount==1) : ?>

	          <div class="items-row cols-<?php echo (int) $this->columns;?> <? echo 'row-'.$row ; ?>">
		  <?php endif; ?>
          <article class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished"' : null; ?>">
			  <?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
			  ?>
		  </article>
		  <?php $counter=$counter +1; ?>
			<?php if (($rowcount == $this->columns) or ($counter ==$introcount)): ?>
				<span class="row-separator"></span>
				</div>

			<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($this->link_items)) : ?>

	<?php echo $this->loadTemplate('links'); ?>

<?php endif; ?>

<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">


		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
         	<p class="counter">
                <?php echo $this->pagination->getPagesCounter(); ?>
        	</p>
        <?php  endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>

</section>

<?php } ?>
