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
 * HTML View class for the Content component
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since 1.5
 */
class ContentViewCategory extends JView
{
	protected $state = null;
	protected $item = null;
	protected $articles = null;
	protected $pagination = null;

	protected $lead_items = array();
	protected $intro_items = array();
	protected $link_items = array();
	protected $columns = 1;

	/**
	 * Display the view
	 *
	 * @return	mixed	False on error, null otherwise.
	 */
	function display($tpl = null)
	{
		// Initialise variables.
		$user =& JFactory::getUser();
		$app =& JFactory::getApplication();
		$uri =& JFactory::getURI();

		$state = $this->get('State');
		$params =& $state->params;
		$item = $this->get('Item');
		$articles = $this->get('Articles');

		// Get child categories based on params
		$children = array();
		$showSubcategories = $params->get('show_subcategory_content', '0');
		if ($showSubcategories == 'next_list' OR $showSubcategories == 'all_list')
		{
			$children = $this->get('Children');
		}
		$pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// PREPARE THE DATA

		// Get the metrics for the structural page layout.
		$numLeading = $params->def('num_leading_articles', 1);
		$numIntro = $params->def('num_intro_articles', 4);
		$numLinks = $params->def('num_links', 4);

		// Compute the category slug and prepare description (runs content plugins).
		$item->slug = $item->path ? ($item->id . ':' . $item->path) : $item->id;
		$item->description = JHtml::_('content.prepare', $item->description);

		// Compute the article slugs and prepare introtext (runs content plugins).
		foreach ($articles as $i => & $article)
		{
			$article->slug = $article->alias ? ($article->id . ':' . $article->alias) : $article->id;
			$article->catslug = $article->category_alias ? ($article->catid . ':' . $article->category_alias) : $article->catid;
			$article->parent_slug = $article->parent_alias ? ($article->parent_id . ':' . $article->parent_alias) : $article->parent_id;
			$article->event = new stdClass();

			$dispatcher =& JDispatcher::getInstance();

			// Ignore content plugins on links.
			if ($i < $numLeading + $numIntro)
			{
				$article->introtext = JHtml::_('content.prepare', $article->introtext);

				$results = $dispatcher->trigger('onAfterDisplayTitle', array(&$article, &$article->params, 0));
				$article->event->afterDisplayTitle = trim(implode("\n", $results));

				$results = $dispatcher->trigger('onBeforeDisplayContent', array(&$article, &$article->params, 0));
				$article->event->beforeDisplayContent = trim(implode("\n", $results));

				$results = $dispatcher->trigger('onAfterDisplayContent', array(&$article, &$article->params, 0));
				$article->event->afterDisplayContent = trim(implode("\n", $results));
			}
		}

		// Preprocess the breakdown of leading, intro and linked articles.
		// This makes it much easier for the designer to just interogate the arrays.
		$max = count($articles);

		// The first group is the leading articles.
		$limit = $numLeading;
		for ($i = 0; $i < $limit && $i < $max; $i++)
		{
			$this->lead_items[$i] =& $articles[$i];
		}

		// The second group is the intro articles.
		$limit = $numLeading + $numIntro;
		// Order articles across, then down (or single column mode)
		for ($i = $numLeading; $i < $limit && $i < $max; $i++)
		{
			$this->intro_items[$i] =& $articles[$i];
		}

		$this->columns = max(1, $params->def('num_columns', 1));
		$order = $params->def('multi_column_order', 1);

		if ($order == 0 && $this->columns > 1)
		{
			// call order down helper
			$this->intro_items = ContentHelperQuery::orderDownColumns($this->intro_items, $this->columns);
		}

		// The remainder are the links.
		for ($i = $numLeading + $numIntro; $i < $max; $i++)
		{
			$this->link_items[$i] =& $articles[$i];
		}

		// Compute the children category slugs and prepare description (runs content plugins).
		foreach ($children as $i => & $child)
		{
			$child->slug = $child->route ? ($child->id . ':' . $child->route) : $child->id;
			$child->description = JHtml::_('content.prepare', $child->description);
		}

		$this->assign('action', str_replace('&', '&amp;', $uri));

		$this->assignRef('params', $params);
		$this->assignRef('item', $item);
		$this->assignRef('articles', $articles);
		$this->assignRef('siblings', $siblings);
		$this->assignRef('children', $children);
		$this->assignRef('parents', $parents);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('user', $user);
		$this->assignRef('state', $state);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app =& JFactory::getApplication();
		$menus =& JSite::getMenu();
		$pathway =& $app->getPathway();
		$title = $this->item->title;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		if ($menu = $menus->getActive())
		{
			$menuParams = new JObject(json_decode($menu->params, true));
			if ($pageTitle = $menuParams->get('page_title'))
			{
				$title = $pageTitle;
			}
		}
		if (empty($title))
		{
			$title = htmlspecialchars_decode($app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
		if ($this->item->metadesc) {
			$this->document->setDescription($this->item->metadesc);
		}

		if ($this->item->metakey) {
			$this->document->setMetadata('keywords', $this->item->metakey);
		}

		$mdata = $this->item->metadata->toArray();
		if ($app->getCfg('MetaTitle') == '1') {
			$this->document->setMetaData('title', $mdata['page_title']);
		}

		if ($app->getCfg('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $mdata['author']);
		}
		

		foreach ($mdata as $k => $v)
		{
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}
		// Add feed links
		if ($this->params->get('show_feed_link', 1))
		{
			$link = '&format=feed&limitstart=';

			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$this->document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);

			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$this->document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
		}

		// Set the pathway.
		$path = array();

		if ($menu && isset($menu->query))
		{
			$view = JArrayHelper::getValue($menu->query, 'view');
			$id = JArrayHelper::getValue($menu->query, 'id');

			if ($view != 'category' || ($view == 'category' && $id != $this->item->id))
			{
				$pathway->addItem($this->item->title);
			}
		}
	}
}
