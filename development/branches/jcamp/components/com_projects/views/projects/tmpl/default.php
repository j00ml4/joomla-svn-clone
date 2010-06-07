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
?>
<div class="componenetheading"><?php echo JText::_('COM_PROJECTS_PROJECTS_GREETING');?></div>

URL must be passed by JRoute
<a href="<?php echo JRoute::_('index.php?option=com_projects&view=project&layout=form'); ?>">
<?php echo JText::_('COM_PROJECTS_PROJECT_ADD');?></a>
