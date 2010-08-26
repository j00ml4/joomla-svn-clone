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

// Vars
$params =  $this->params;
$pageClass = $this->escape($params->get('pageclass_sfx'));
?>
<div class="projects<?php echo $pageClass;?> blog<?php echo $pageClass;?>">
    <div class="projects-content">
        <?php if ($params->get('show_description', 1) || $params->def('show_description_image', 1)) : ?>
        <div class="category-desc">
            <?php if ($params->get('show_description_image') && $this->portfolio->getParams()->get('image')) : ?>
                <img src="<?php echo $this->portfolio->getParams()->get('image'); ?>"/>
            <?php endif; ?>
            <?php if ($this->portfolio->description) : ?>
                <?php echo JHtml::_('content.prepare', $this->portfolio->description); ?>
            <?php endif; ?>
            <div class="clr"></div>
        </div>
        <?php endif; ?>
		<form action="<?php echo ProjectsHelper::getLink('projects'); ?>" method="post" id="adminForm" name="adminForm">
        <div class="TabView" id="TabView">
            <?php if ($this->portfolio->numcategories && $this->portfolio->numitems) : ?>
            <ul class="tabnav">
                <li><a title="<?php echo JText::_('COM_PROJECTS_PORTFOLIOS_LINK_DESC'); ?>" href="<?php echo ProjectsHelper::getLink('portfolios', $this->portfolio->id); ?>" >
                    <?php echo JText::sprintf('COM_PROJECTS_PORTFOLIOS_LINK', $this->portfolio->numcategories); ?>
                </a></li>
                <li class="active"><a title="<?php echo JText::_('COM_PROJECTS_PROJECTS_LINK_DESC'); ?>">
                    <?php echo JText::sprintf('COM_PROJECTS_PROJECTS_LINK', $this->portfolio->numitems); ?>
                </a></li>
            </ul>
            <?php endif; ?>
            <!-- *** Pages ************************************************************* -->

            <div class="page" >
                <div class="pad">
                <?php if (count($this->items)): ?>
                    <ul>
                    <?php
                        foreach ($this->items as $i => $item) :
                            $this->item = $item;
                            echo $this->loadTemplate('item');
                    endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p></p>
                <?php endif; ?>

                </div>
            </div>

			<?php if ($this->params->get('show_pagination', 1) && ($this->pagination->get('pages.total') > 1)) : ?>
			<div class="pagination">
				<?php  if ($this->params->get('show_pagination_results', 1)) : ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
				<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php  endif; ?>
			</div>
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" id="task" name="task" value="" />
		</form>
	</div>

	</div>
</div>