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
				switch($model->getState('type'))
				{
					case 'assign' :
						echo JHTML::_('action.question',JText::_('JGLOBAL_'.$this->prefix_text),
													JText::_('COM_PROJECTS_MEMBERS_'.$this->prefix_text.'_MSG'),
													null,
													null,
													'members.assign');
						break;
						
					case 'delete' :
						echo JHTML::_('action.question',JText::_('JGLOBAL_'.$this->prefix_text),
													JText::_('COM_PROJECTS_MEMBERS_'.$this->prefix_text.'_MSG'),
													JText::_('COM_PROJECTS_MEMBERS_'.$this->prefix_text.'_MSG_CONFIRM'),
													JText::_('COM_PROJECTS_MEMBERS_'.$this->prefix_text.'_MSG_CONFIRM_PLURAL'),
													'members.delete');
				}
			?>
		</li>
		<?php endif; ?>
		<li>
			<?php echo JHTML::_('action.link', JText::_('COM_PROJECTS_MEMBERS_BACK_TO_PROJECT_LINK'),'members.back')?>
		</li>
	</ul>
</div>