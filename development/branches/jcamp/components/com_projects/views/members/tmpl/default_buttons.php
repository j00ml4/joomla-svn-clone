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

// Create a shortcut for params.
$params	=& $item->params;
$model	=$this->getModel();
?>
<div class="tabs">
<?php switch($this->type){
	case 'list':
	case 'delete':	
		echo '<a class="tab readmore" title="'. JText::_('COM_PROJECTS_MEMBERS_ASSIGN_DESC') .'" href="'.$this->links['assign'].'">'. 
			JText::_('COM_PROJECTS_MEMBERS_ASSIGN_TITLE') .'</a>';
		break;		
		
	case 'assign':
		echo '<a class="tab readmore" title="'. JText::_('COM_PROJECTS_MEMBERS_DELETE_DESC') .'" href="'.$this->links['delete'].'">'. 
			JText::_('COM_PROJECTS_MEMBERS_DELETE_TITLE') .'</a>';
		break;		
} ?>
</div>
