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
	<?php if ($this->canDo->get('project.edit')): ?>
	<li>
		<?php 
			if($this->getModel()->getState('type') == 'assign') {
				echo '<button type="submit">'.JText::_('JGLOBAL_ASSIGN').'</button>';
			}
			else
				if($this->getModel()->getState('type') == 'delete') {
					echo '<button type="submit">'.JText::_('JGLOBAL_DELETE').'</button>';
				} ?>
	</li>
	<?php endif; ?>
</ul>