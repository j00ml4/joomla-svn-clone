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

<ul class="actions">
		<li>
			<?php 
//				echo JHTML::_('action.link',JText::_('COM_PROJECTS_DOCUMENTS_BACK_PROJECT_LINK'), 'display', $model->getState('project.id'),
//											'index.php?option=com_projects&view=project&layout=default');
			?>
		</li>
	<?php if($this->canDo->get('project.edit')): ?>
		<li>
			<?php 
//				echo JHTML::_('action.link',JText::_('COM_PROJECTS_DOCUMENTS_ADD_DOCUMENT_LINK'), 'display', null,
//											'index.php?option=com_projects&view=document&layout=form');			
			?>
		</li>
		<?php if (!empty($this->items)) : ?>
		<li>
		<?php 
				echo '<button onclick="submitform(\'documents.delete\')">'.JText::_('COM_PROJECTS_DOCUMENTS_DELETE_DOCUMENT_LINK').'</button>';
		?>
		</li>
		<?php endif; ?>
	<?php endif; ?>
</ul>