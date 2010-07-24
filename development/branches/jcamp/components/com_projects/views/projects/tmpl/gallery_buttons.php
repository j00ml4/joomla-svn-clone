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
?>

<div class="formelm_buttons">
	<ul class="actions"> 
			<?php if($this->canDo->get('project.create', 1)): ?>
			<li>
				<?php echo JHTML::_('action.link', JText::_('COM_PROJECTS_PROJECT_ADD'), 'projects.add'); ?>
			</li>
			<?php endif;?>
			
			<li>
				<?php echo JHTML::_('action.link', JText::_('COM_PROJECTS_BACK_TO_PORTFOLIOS_PROJECTS_LINK'), 'projects.back'); ?>
			</li>
		</ul>
</div>