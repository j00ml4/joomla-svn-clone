<?php
/**
 * @version                $Id: default.php 12296 2009-06-22 11:14:59Z pentacle $
 * @package                Joomla.Site
 * @subpackage        com_content
 * @copyright        Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license                GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// Create shortcut to parameters.
$params = $this->state->get('params');
// $pageClass = $this->params->get('pageclass_sfx');
?>
<article id="page<?php if ($pageClass) { echo 'class="'.$pageClass.'"';} ?>">
<?php if ($params->get('show_page_title', 1) && $params->get('page_title') != $this->item->title) : ?>
        <h1>
                <?php echo $this->escape($params->get('page_title')); ?>
        </h1>
<?php endif; ?>

<?php if ($params->get('show_title')) : ?>
        <h2>
                <?php if ($params->get('link_titles') && $this->item->readmore_link != '') : ?>
                <a href="<?php echo $this->item->readmore_link; ?>">
                        <?php echo $this->escape($this->item->title); ?></a>
                <?php else : ?>
                        <?php echo $this->escape($this->item->title); ?>
                <?php endif; ?>
        </h2>
<?php endif; ?>

<?php if ($params->get('access-edit') || $params->get('show_title') ||  $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
        <ul class="jactions">
        <?php if (!$this->print) : ?>
                <?php if ($params->get('show_print_icon')) : ?>
                <li class="jprint">
                        <?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?>
                </li>
                <?php endif; ?>

                <?php if ($params->get('show_email_icon')) : ?>
                <li class="jemail">
                        <?php echo JHtml::_('icon.email',  $this->item, $params); ?>
                </li>
                <?php endif; ?>
               	<?php if ($this->user->authorise('core.edit', 'com_content.article.'.$this->item->id)) : ?>
						<li class="jedit">
							<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
						</li>
					<?php endif; ?>
        <?php else : ?>
                <li>
                        <?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?>
                </li>
        <?php endif; ?>
        </ul>
<?php endif; ?>

<?php  if (!$params->get('show_intro')) :
        echo $this->item->event->afterDisplayTitle;
endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

<?php // to do not that elegant ?>
<?php if (($params->get('show_author')) or ($params->get('link_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date'))) : ?>
 <dl class="article_info">
<?php endif; ?>
<?php if ($params->get('show_category')) : ?>
<dt class="category_term"><?php  echo JText::_('CATEGORY'); ?></dt>
<dd class="category">
                <?php if ($params->get('link_category')) : ?>


                        <?php echo '<a href="'.JRoute::_(ContentRoute::category($this->item->catslug)).'">'; ?>
                                <?php echo $this->escape($this->item->category); ?></a>
                <?php else : ?>
                       <?php echo $this->escape($this->item->category_title); ?>

                <?php endif; ?>
     </dd>
<?php endif; ?>


<?php if ($params->get('show_create_date')) : ?>
        <dt class="create_term"> <?php echo JText::_('CREATION_DATE'); ?> </dt>
        <dd class="create_date">
                <?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?>
        </dd>
<?php endif; ?>


	<?php if ($params->get('show_author') && !empty($this->item->author_name)) : ?>
		<dt class="createdby_term"> <?php echo JText::_('WRITTEN_BY'); ?></dt>
		<dd class="createdby">
			<?php echo ($this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author_name); ?>
		</dd>
	<?php endif; ?>
<?php if (($params->get('show_author')) or ($params->get('link_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date'))) : ?>
 </dl>
<?php endif; ?>

<?php if (isset ($this->item->toc)) : ?>
        <?php echo $this->item->toc; ?>
<?php endif; ?>

<?php echo $this->item->text; ?>

<?php if (intval($this->item->modified) !=0 && $params->get('show_modify_date')) : ?>
       <p class="article_info"><span>
                <?php echo JText::sprintf('LAST_UPDATED2', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
        </span></p>
<?php endif; ?>

<span class="article_separator">&nbsp;</span>
<?php echo $this->item->event->afterDisplayContent; ?>

</article>