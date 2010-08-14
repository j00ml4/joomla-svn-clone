<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$params =  $this->params;
$pageClass = $this->escape($params->get('pageclass_sfx'));
?>


<div class="projects<?php echo $pageClass;?> blog<?php echo $pageClass;?>">
	<div class="projects-content">
		<h1><?php echo JText::_('COM_PROJECTS_PORTFOLIO_LIST_HEADER');?></h1>
		
		<p><?php echo JText::_('COM_PROJECTS_PORTFOLIO_LIST_DESC');?></p>
		
		
		<div class="TabView" id="TabView">

<!-- *** Tabs ************************************************************** -->

<div class="Tabs" style="width: 350px;">
  <a>Portfolios(Y)</a>
  <a>Projects(X)</a>
</div>

<!-- *** Pages ************************************************************* -->

<div class="Pages" style="width: 100%; height: 100%; text-align: left;">

  <div class="Page">
  <div class="Pad">

  <!-- *** Page1 Start *** -->

<br>
		
			<?php foreach ($this->items as $item) :
				$this->item = $item;
				echo $this->loadTemplate('item');
		endforeach; ?>
		<?php if(($params->def('show_pagination', 1) == 1 || ($params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) { ?>
		<div class="pagination">
			<?php  if ($this->params->def('show_pagination_results', 1)) { ?>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php } ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<?php  } ?>
		
<!-- *** Page1 End ***** -->

  </div>
  </div>

  <!-- *** Page2 Start *** -->

  <div class="Page">
  <div class="Pad">
  
  <p></p>

  <!-- *** Page2 End ***** -->

  </div>
  </div>
  </div>
  </div>
	</div>
</div>