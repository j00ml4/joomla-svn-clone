<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	plgContentComments
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

jimport('joomla.event.plugin');

/**
 * The JXtended Comments content plugin
 *
 * @package		JXtended.Comments
 * @subpackage	plgContentComments
 * @version		1.2
 */
class plgContentComments extends JPlugin
{
	/**
	 * Array of category parameter objects.
	 *
	 * @var		array
	 * @since	1.0
	 */
	private $_catParams = array();

	/**
	 * Constructor.
	 *
	 * @param	object	The event dispatcher to observe.
	 * @param	object	The plugin parameter object.
	 * @return	void
	 * @version	1.0
	 */
	public function __construct(& $subject, $params)
	{
		// Run the parent constructor.
		parent::__construct($subject, $params);

		// Import the route helper.
		require_once(JPATH_SITE.'/components/com_content/helpers/route.php');

		// Add the JXtended Comments component JHtml helpers.
		JHtml::addIncludePath(JPATH_SITE.'/components/com_social/helpers/html');
	}

	/**
	 * Method to catch the afterContentSave event.
	 *
	 * @param	object	A reference to the content object being saved.
	 * @param	bool	A flag indicating whether the item is new or not.
	 * @return	mixed	Boolean true on success, JException otherwise.
	 * @since	1.2
	 */
	public function onAfterContentSave(& $article, $isNew)
	{
		// Make sure we are dealing with a joomla article and it is world readable.
		if (($article instanceof JTableContent) && ($article->state == 1) && ($article->access == 0))
		{
			$cconfig = JComponentHelper::getParams('com_social');

			// Get enabled settings for trackbacks.
			$enableTrackbacks = $cconfig->get('enable_trackbacks', 0);

			// Get the full article route.
			$route = ContentHelperRoute::getArticleRoute($article->id.':'.$article->alias, $article->catid);

			// Add the appropriate include paths for models.
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_SITE.'/components/com_social/models');

			// Get and configure the thread model.
			$model = JModel::getInstance('Thread', 'CommentsModel');
			$model->getState();
			$model->setState('thread.context', 'content');
			$model->setState('thread.context_id', (int)$article->id);
			$model->setState('thread.url', 'index.php?option=com_content&view=article'.$article->catid.'&id='.$article->id);
			$model->setState('thread.route', $route);
			$model->setState('thread.title', $article->title);

			// Get the thread data.
			$thread = $model->getThread();

			if ($thread->pings) {
				$pings = (array) json_decode($thread->pings);
			} else {
				$pings = array();
			}

			if ($enableTrackbacks)
			{
				// Get a JXTrackback object.
				jimport('joomla.webservices.trackback');
				$trackback = JTrackback::getInstance();
				$config = JFactory::getConfig();

				// Get trackback URIs from any relevant sites.
				$uris = $trackback->discovery($article->introtext.$article->fulltext);

				// Send trackback pings to found trackback URIs
				if ($uris)
				{
					foreach ($uris as $link)
					{
						if (in_array($link, $pings)) {
							continue;
						}

						// Attempt to send a trackback ping
						$trackback->send($link, $this->_getContentRoute($route), $article->title, ($article->metadesc) ? $article->metadesc : strip_tags($article->introtext), $config->getValue('config.sitename'));

						// Save the ping to the thread row.
						$pings = array_merge($pings, array($link));
					}
				}
			}

			if ($cconfig->get('enable_pings', 0) && !in_array('Ping:Google', $pings))
			{
				// Get a JXPing object.
				jimport('joomla.webservices.ping');
				$config = JFactory::getConfig();
				$ping = new JXPing();

				// Send ping request to Google (Hardcoded for now).
				if ($ping->send('google', $config->getValue('config.sitename'), JURI::root(), $this->_getContentRoute($route)))
				{
					// Save the ping to the thread row.
					$pings = array_merge($pings, array('Ping:Google'));
				}
			}

			// Update the thread ping values if it exists.
			if ($thread->id)
			{
				$db = JFactory::getDBO();
				$db->setQuery('UPDATE `#__social_threads` SET `pings` = '.$db->quote(json_encode($pings)).' WHERE `id` = '.(int) $thread->id);
				$db->query();
			}

			return true;
		}
	}

	/**
	 * This method sets up the context and context_id in the application object for auto-detecting
	 * by the comments and ratings modules.
	 *
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	integer	The 'page' number
	 * @return	void
	 * @since	1.2
	 */
	public function onPrepareContent(& $article, & $params, $page)
	{
		// Get the option and view from the request.
		$option	= JRequest::getCmd('option');
		$view	= JRequest::getCmd('view');

		// Only set the context if we are looking at an article view of com_content.
		if ($option == 'com_content' && $view == 'article')
		{
			// Get the application object to set the context information.
			$application = JFactory::getApplication('site');

			// Set the context information to the application object.
			$application->set('joomla.context', 'content');
			$application->set('joomla.context_id', $article->id);
		}
	}

	/**
	 * This method gets data to display after the title is displayed for an article according
	 * to the plugin configuration.
	 *
	 *  - Display Placement 0 -
	 *
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	integer	The 'page' number
	 * @return	string	Data to append after the title display for the article.
	 * @since	1.2
	 */
	public function onAfterDisplayTitle(& $article, & $params, $limitstart)
	{
		// If there is nothing to show for this placement return the empty string.
		if (!$this->_getShowState($article, 0)) {
			return '';
		}

		// Return the rendered data for this placement.
		$result = $this->_getRenderedData($article, 0);
		return $result;
	}

	/**
	 * This method gets data to display just before the article is displayed according
	 * to the plugin configuration.
	 *
	 *  - Display Placement 1 -
	 *
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	integer	The 'page' number
	 * @return	string	Data to append after the title display for the article.
	 * @since	1.2
	 */
	public function onBeforeDisplayContent(& $article, & $params, $limitstart)
	{
		// If there is nothing to show for this placement return the empty string.
		if (!$this->_getShowState($article, 1)) {
			return '';
		}

		// Return the rendered data for this placement.
		$result = $this->_getRenderedData($article, 1);
		return $result;
	}

	/**
	 * This method gets data to display just after the article is displayed according
	 * to the plugin configuration.
	 *
	 *  - Display Placement 2 -
	 *
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	integer	The 'page' number
	 * @return	string	Data to append after the title display for the article.
	 * @since	1.0
	 */
	public function onAfterDisplayContent(& $article, & $params, $limitstart)
	{
		// If there is nothing to show for this placement return the empty string.
		if (!$this->_getShowState($article, 2)) {
			return '';
		}

		// Return the rendered data for this placement.
		$result = $this->_getRenderedData($article, 2);
		return $result;
	}

	/**
	 * Method to determine if there is any data to show in a given placement.
	 *
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	integer	The display placement identifier.
	 * @return	boolean	True if there is something to display in this placement.
	 * @since	1.1
	 */
	protected function _getShowState(& $article, $location)
	{
		// If we are dealing with a JXtended Magazine Article, display nothing.
		if (isset($article->context) && $article->context == 'zine/article') {
			return false;
		}

		// If sharing, ratings and comments are all not set to show, display nothing.
		$shareHere		= ($this->params->get('share_placement', 2) == $location);
		$ratingsHere	= ($this->params->get('ratings_placement', 2) == $location);
		$commentsHere	= ($this->params->get('comments_placement', 2) == $location);
		if (!$shareHere and !$ratingsHere and !$commentsHere) {
			return false;
		}

		// If the plugin is not set to show for the category of the given article, display nothing.
		$showUncat		= $this->params->get('show_uncat', 0);
		$showCategories = (array) $this->params->get('categories');
		$show = (($showUncat and isset($article->sectionid) and $article->sectionid == 0 and isset($article->catid) and $article->catid == 0) or (isset($article->catid) and in_array($article->catid, $showCategories)));
		if (!$show) {
			return false;
		}

		// If the plugin is not set to show for the current view, display nothing.
		$view = JRequest::getCmd('view');
		$showFrontpage	= $this->params->get('show_on_frontpage', 1);
		$showCategory	= $this->params->get('show_on_categories', 1);
		if ((($view == 'frontpage') and !$showFrontpage) or (($view == 'section') and !$showSection) or (($view == 'category') and !$showCategory)) {
			return false;
		}

		// Don't show on the print screen.
		if (JRequest::getBool('print') == true) {
			return false;
		}

		return true;
	}

	/**
	 * Method to get rendered data for a given placement.
	 *
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	integer	The display placement identifier.
	 * @return	string	Data to display in the given placement.
	 * @since	1.1
	 */
	protected function _getRenderedData(& $article, $location)
	{
		// Get global display settings for sharing, ratings and comments.
		$showShare		= $this->params->get('show_share', 1);
		$showRatings	= $this->params->get('show_ratings', 1);
		$showComments	= $this->params->get('show_comments', 1);

		// Get display settings for sharing, ratings and comments for the given placement.
		$shareHere		= ($this->params->get('share_placement', 2) == $location);
		$ratingsHere	= ($this->params->get('ratings_placement', 2) == $location);
		$commentsHere	= ($this->params->get('comments_placement', 2) == $location);

		// Get the article url and route.
		$url	= 'index.php?option=com_content&view=article&id='.$article->id;
		$route	= ContentHelperRoute::getArticleRoute($article->slug, $article->catid);

		// Initialize variables.
		$result		= '';
		$options	= array('style'=>'raw');

		// Show sharing module if enabled for this placement.
		if ($shareHere and $showShare)
		{
			// Load the category parameters if they exist and not already set.
			if (empty($this->_catParams[$article->catid]))
			{
				$db = JFactory::getDBO();
				$db->setQuery(
					'SELECT `params`' .
					' FROM `#__categories`' .
					' WHERE `id` = '.(int)$article->catid
				);
				$catParams = $db->loadResult();
				$this->_catParams[$article->catid] = new JRegistry();
				$this->_catParams[$article->catid]->loadJSON($catParams);
			}

			// If the feed flare path is set for the article category, set it in the sharing module options.
			if (!empty($this->_catParams[$article->catid])) {
				if ($ffpath = $this->_catParams[$article->catid]->get('feedflarepath')) {
					$options['feedflarepath'] = $ffpath;
				}
			}

			// Render the sharing module.
			$result .= JHtml::_('comments.share', $route, $article->title, $options);
		}

		// Show ratings module if enabled for this placement.
		if ($ratingsHere and $showRatings)
		{
			// Render the ratings module.
			$result .= JHtml::_('comments.rating', 'content', $article->id, $url, $route, $article->title, $options);
		}

		// Show comments module if enabled for this placement.
		if ($commentsHere and $showComments)
		{
			// Get enabled settings for trackbacks.
			$cconfig = JComponentHelper::getParams('com_social');
			if ($cconfig->get('enable_trackbacks', 0)) {
				// Show the trackback RDF widget if enabled.
				$result .= JHtml::_('comments.trackbackRDF', 'content', $article->id, $url, $route, $article->title, $article->introtext, $article->author);
			}

			// Show the summary layout for non-article view pages.
			$view = JRequest::getCmd('view');
			if ($view == 'article') {
				// Render the comments module.
				$result .= JHtml::_('comments.comments', 'content', $article->id, $url, $route, $article->title, $options);
			} else {
				// Render the comments module with the summary layout.
				$result .= JHtml::_('comments.summary', 'content', $article->id, $url, $route, $article->title, $options);
			}
		}

		return $result;
	}

	/**
	 * Method to get the SEF route of a content item.
	 *
	 * @param	string	The non-SEF route to the content item.
	 * @return	string	The SEF route to the content item.
	 * @since	1.1
	 */
	protected function _getContentRoute($url)
	{
		static $router;

		jimport('joomla.application.router');
		require_once(JPATH_SITE.'/includes/application.php');

		// Only get the router once.
		if (!is_object($router))
		{
			// Get and configure the site router.
			$config	= JFactory::getConfig();
			$router = JRouter::getInstance('site');
			$router->setMode($config->getValue('sef', 1));
		}

		// Build the route.
		$base	= JURI::getInstance();
		$uri	= $router->build($url);
		$route	= $base->toString( array('scheme', 'host', 'port')).$uri->toString(array('path', 'query', 'fragment'));

		// Strip out the administrator segment of the route.
		$route = str_replace('/administrator/', '/', $route);

		return $route;
	}
}