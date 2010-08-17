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

// Vars
$params = $this->params;
$pageClass = $this->escape($params->get('pageclass_sfx'));
?>


<div class="projects<?php echo $pageClass; ?> blog<?php echo $pageClass; ?>">
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

        <div class="TabView" id="TabView">
            <ul class="tabnav" style="width: 350px;">
                <li calss=�ctive"><a title="<?php echo JText::_('COM_PROJECTS_PORTFOLIOS_LINK_DESC'); ?>">
                    <?php echo JText::sprintf('COM_PROJECTS_PORTFOLIOS_LINK', $this->portfolio->numcategories); ?>
                </a></li>
                <?php if (!$params->get('is.root')): ?>
                <li><a title="<?php echo JText::_('COM_PROJECTS_PROJECTS_LINK_DESC'); ?>" href="<?php echo $this->getLink('projects', $this->portfolio->id); ?>" >
                    <?php echo JText::sprintf('COM_PROJECTS_PROJECTS_LINK', $this->portfolio->numitems); ?>
                </a></li>
                <?php endif; ?>
            </ul>
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
            <?php if (($params->def('show_pagination', 1) == 1 || ($params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) { ?>
            <div class="pagination">
                <?php if ($this->params->def('show_pagination_results', 1)) { ?>
                    <p class="counter"><?php echo $this->pagination->getPagesCounter(); ?></p>
                <?php } ?>
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>	