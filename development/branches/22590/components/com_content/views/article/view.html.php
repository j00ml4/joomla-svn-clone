<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML Article View class for the Content component
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since		1.5
 */
class ContentViewArticle extends JView
{
	protected $item;
	protected $params;
	protected $print;
	protected $state;
	protected $user;

	function display($tpl = null)
	{
		// Initialise variables.
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$dispatcher	= JDispatcher::getInstance();

		$this->item		= $this->get('Item');
		$this->print	= JRequest::getBool('print');
		$this->state	= $this->get('State');
		$this->user		= $user;

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		// Create a shortcut for $item.
		$item = &$this->item;

		// Add router helpers.
		$item->slug			= $item->alias ? ($item->id.':'.$item->alias) : $item->id;
		$item->catslug		= $item->category_alias ? ($item->catid.':'.$item->category_alias) : $item->catid;
		$item->parent_slug	= $item->category_alias ? ($item->parent_id.':'.$item->parent_alias) : $item->parent_id;

		// TODO: Change based on shownoauth
		$item->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));

		// Merge article params. If this is single-article view, menu params override article params
		// Otherwise, article params override menu item params
		$this->params	= $this->state->get('params');
		$active	= $app->getMenu()->getActive();
		$temp	= clone ($this->params);

		// TODO: Need more comments on this block!!!
		if ($active) {
			$currentLink = $active->link;

			if (strpos($currentLink, 'view=article')) {
				$item->params->merge($temp);
			}
			else {
				$temp->merge($item->params);
				$item->params = $temp;
			}
		}
		else {
			$temp->merge($item->params);
			$item->params = $temp;
		}

		$offset = $this->state->get('list.offset');

		// Check the view access to the article (the model has already computed the values).
		if ($item->params->get('access-view') != true) {
			// TODO: This curtails the ability for a layout to show teaser information!!!
			// If a guest user, they may be able to log in to view the full article
			if (($this->params->get('show_noauth')) AND ($user->get('guest'))) {
				// Redirect to login
				$uri = JFactory::getURI();
				$app->redirect('index.php?option=com_users&view=login&return=' . base64_encode($uri), JText::_('COM_CONTENT_ERROR_LOGIN_TO_VIEW_ARTICLE'));

				return;
			}
			else {
				JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));

				return;
			}
		}

		if ($item->params->get('show_intro','1')=='1') {
			$item->text = $item->introtext.' '.$item->fulltext;
		}
		else if ($item->fulltext) {
			$item->text = $item->fulltext;
		}
		else  {
			$item->text = $item->introtext;
		}

		//
		// Process the content plugins.
		//
		JPluginHelper::importPlugin('content');
		$results = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$item, &$this->params, $offset));

		$item->event = new stdClass();
		$results = $dispatcher->trigger('onContentAfterTitle', array('com_content.article', &$item, &$this->params, $offset));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.article', &$item, &$this->params, $offset));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.article', &$item, &$this->params, $offset));
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		// Override the layout.
		if ($layout = $item->params->get('layout')) {
			$this->setLayout($layout);
		}

		// Increment the hit counter of the article.
		if (!$this->params->get('intro_only') && $offset == 0) {
			$model = $this->getModel();
			$model->hit();
		}

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$pathway = $app->getPathway();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->getCfg('sitename'));
		}
		elseif ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->getCfg('sitename')), $title);
		}
		$this->document->setTitle($title);

		$id = (int) @$menu->query['id'];

		// if the menu item does not concern this article
		if ($menu && ($menu->query['option'] != 'com_content' || $menu->query['view'] != 'article' || $id != $this->item->id))
		{
			$path = array(array('title' => $this->item->title, 'link' => ''));
			$category = JCategories::getInstance('Content')->get($this->item->catid);
			while (($menu->query['option'] != 'com_content' || $menu->query['view'] == 'article' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => ContentHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}
			$path = array_reverse($path);
			foreach($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		if (empty($title))
		{
			$title = $this->item->title;
		}
		$this->document->setTitle($title);

		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}

		if ($app->getCfg('MetaTitle') == '1')
		{
			$this->document->setMetaData('title', $this->item->title);
		}

		if ($app->getCfg('MetaAuthor') == '1')
		{
			$this->document->setMetaData('author', $this->item->author);
		}

		$mdata = $this->item->metadata->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}

		// If there is a pagebreak heading or title, add it to the page title
		if (!empty($this->item->page_title))
		{
			$this->item->title = $this->item->title . ' - ' . $this->item->page_title;
			$this->document->setTitle($this->item->page_title . ' - ' . JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->state->get('list.offset') + 1));
		}

		if ($this->print)
		{
			$this->document->setMetaData('robots', 'noindex, nofollow');
		}
	}
}
