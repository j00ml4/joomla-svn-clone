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

// HTML Helpers
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

// Vars
$params = $this->params;
?>
<div class="project-item <?php echo $params->get('pageclass_sfx'); ?>">
    <div class="edit item-page">
        <form action="<?php echo ProjectsHelper::getLink('form'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

			<?php echo $this->loadTemplate($this->type); ?>
     
           	<input type="hidden" name="task" value="task.save" />
            <?php echo JHTML::_('form.token'); ?>
        </form>
    </div>
</div>