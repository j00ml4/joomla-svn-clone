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
<div class="formelm_buttons projects-content toolbar-list">
	<ul class="actions">
		<?php if ($this->canDo->get('project.edit')): ?>
		<li>
			<?php 
				if($this->getModel()->getState('type') == 'assign') {
					echo '<button type="submit">'.JText::_('JGLOBAL_ASSIGN').'</button>';
				}
				else
					if($this->getModel()->getState('type') == 'delete') {
						echo JHTML::_('action.question',JText::_('JGLOBAL_DELETE'), JText::_('COM_PROJECTS_DELETE'),'members.delete');
					} ?>
		</li>
		<?php endif; ?>
		<li>
			<?php echo JHTML::_('action.link', JText::_('COM_PROJECT_PROJECT_MEMBERS_BACK_TO_PROJECT_LINK'),'members.back')?>
		</li>
	</ul>
</div>