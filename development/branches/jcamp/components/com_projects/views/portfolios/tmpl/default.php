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
	
		<br>
 
	<ul class="tabnav"> 
		<li class="tab1"><a href="index.html">Portfolio</a></li> 
		<li class="tab2"><a href="index2.html">Projects</a></li> 
	 
	</ul> 
  
	</div>
</div>