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
		$item->description = JHtml::_('content.prepare', $item->description);

		// Compute the article slugs and prepare introtext (runs content plugins).
		foreach ($articles as $i => & $article)
		{
			$article->slug = $article->alias ? ($article->id . ':' . $article->alias) : $article->id;
			$article->catslug = $article->category_alias ? ($article->catid . ':' . $article->category_alias) : $article->catid;
			$article->parent_slug = $article->parent_alias ? ($article->parent_id . ':' . $article->parent_alias) : $article->parent_id;
			
			// No link for ROOT category
			if ($article->parent_alias == 'root') {
				$article->parent_slug = null;
			}

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
		$app		= &JFactory::getApplication();
		$menus		= &JSite::getMenu();
		$pathway	= &$app->getPathway();
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_CONTENT_DEFAULT_PAGE_TITLE')); 
		}		
		if($menu && $menu->query['view'] != 'article' && $menu->query['id'] != $this->item->id)
		{
			$this->params->set('page_subheading', $this->item->title);
			$path = array($this->item->title => '');
			$category = $this->item->getParent();
			while($menu->query['id'] != $category->id)
			{
				$path[$category->title] = ContentHelperRoute::getCategoryRoute($category->id);
				$category = $category->getParent();
			}
			$path = array_reverse($path);
			foreach($path as $title => $link)
			{
				$pathway->addItem($title, $link);
			}
		}
		
		$title = $this->params->get('page_title', '');
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

		if ($app->getCfg('MetaTitle') == '1') {
			$this->document->setMetaData('title', $this->item->getMetadata()->get('page_title'));
		}

		if ($app->getCfg('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $this->item->metadata->get('author'));
		}
		

		$mdata = $this->item->metadata->toArray();
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
	}
}
