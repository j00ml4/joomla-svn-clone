<?php
/**
 * @version		$Id: default.php 17187 2010-05-19 11:18:22Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

$pageClass = $this->params->get('pageclass_sfx');

?>

<div class="projects<?php echo $pageClass;?> category-list<?php echo $pageClass;?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1>
		<?php echo JTEXT::_('COM_PROJECTS_DOCUMENTS_TITLE'); ?>
	</h1>
	<?php endif; ?>
	<div class="cat-items">
		<?php echo $this->loadTemplate('documents'); ?>
	</div>

</div>