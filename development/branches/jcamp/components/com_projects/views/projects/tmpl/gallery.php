<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$params =  $this->params;
//dump($this->items);
?>

<div class="portifolio-gallery<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php if ($params->get('show_page_heading')){ ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php }

	if ($params->get('show_page_heading', 1)){ ?>
	<?php /* this allows the user to set the title he wants */ ?> 
	<div class="componentheading"><?php echo $this->escape($params->get('page_heading')); ?></div>
	<?php }
	
  if ($this->params->get('show_description', 1) && $this->category->description){ ?>
	<div class="portifolio-desc">
		<?php echo JHtml::_('content.prepare', $this->category->description); ?>
		<div class="clr"></div>
	</div>
<?php } ?>

<div class="portfolio-projects">
	<?php
			$c = count($this->items);
			for($i = 0; $i<$c;$i++) {
				$this->item = &$this->items[$i];
				echo $this->loadTemplate('item');
			} ?>
</div>

	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) { ?>
		<div class="pagination">
						<?php  if ($this->params->def('show_pagination_results', 1)) { ?>
						<p class="counter">
								<?php echo $this->pagination->getPagesCounter(); ?>
						</p>

				<?php } ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
<?php  } ?>
</div>
