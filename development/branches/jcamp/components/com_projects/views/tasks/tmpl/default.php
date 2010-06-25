<?php
/**
 * @version		$Id:
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$params =  $this->params;
?>
<div class="projects<?php echo $params->get('pageclass_sfx'); ?>">
	<a href="<?php echo JRoute::_('index.php?option=com_projects&view=task&layout=form'); ?>">
		<?php echo JText::_('COM_PROJECTS_NEW_TASK_LINK'); ?>
	</a>
	<?php dump($this->items)?>
</div>