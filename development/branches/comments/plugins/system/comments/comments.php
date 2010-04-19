<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	plgSystemComments
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.event.plugin');

/**
 * The Comments System Plugin.
 *
 * @package		JXtended.Comments
 * @subpackage	plgSystemComments
 * @since		2.0
 */
class plgSystemComments extends JPlugin
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

		// Add the Comments component JHtml helpers.
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

			// Get the category slug if available.
			if ($article->catid) {
				$db = & JFactory::getDBO();
				$db->setQuery('SELECT `alias` FROM `#__categories` WHERE `id` = '.(int) $article->catid);
				$catslug = $article->catid.':'.$db->loadResult();
			}
			else {
				$catslug = $article->catid;
			}

			// Get the full article route.
			$route = ContentHelperRoute::getArticleRoute($article->id.':'.$article->alias, $catslug, $article->sectionid);

			// Add the appropriate include paths for models.
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_SITE.'/components/com_social/models');

			// Get and configure the thread model.
			$model = JModel::getInstance('Thread', 'CommentsModel');
			$model->getState();
			$model->setState('thread.context', 'content');
			$model->setState('thread.context_id', (int)$article->id);
			$model->setState('thread.url', 'index.php?option=com_content&view=article&id='.$article->id);
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
				$db->setQuery('UPDATE `#__comments_threads` SET `pings` = '.$db->quote(json_encode($pings)).' WHERE `id` = '.(int) $thread->id);
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
			$application->set('jx.context', 'content');
			$application->set('jx.context_id', $article->id);
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
		if ($show) {
			$article->parameters->def('enable_comments', 1);
		}

		// If the plugin is not set to show for the current view, display nothing.
		$view = JRequest::getCmd('view');
		$showFrontpage	= $this->params->get('show_on_frontpage', 1);
		$showSection	= $this->params->get('show_on_sections', 1);
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
		if (empty($article->parameters)) {
			$article->parameters = new JParameter($article->attribs);
		}

		// Get global display settings for sharing, ratings and comments.
		$showShare		= $article->parameters->get('enable_bookmarks', 0);
		$showRatings	= $article->parameters->get('enable_ratings', 0);
		$showComments	= $article->parameters->get('enable_comments', 0);

		// Get display settings for sharing, ratings and comments for the given placement.
		$shareHere		= ($this->params->get('share_placement', 2) == $location);
		$ratingsHere	= ($this->params->get('ratings_placement', 2) == $location);
		$commentsHere	= ($this->params->get('comments_placement', 2) == $location);

		// Get the article url and route.
		$url	= 'index.php?option=com_content&view=article&id='.$article->id;
		$route	= ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid);

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
				$this->_catParams[$article->catid] = new JParameter($catParams);
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

	/**
	 * Method to inject comments integration HTML into necessary pages after the
	 * component has been dispatched.
	 *
	 * @return	void
	 * @since	2.0
	 */
	public function onAfterDispatch()
	{
		// Get some important request values.
		$component	= JRequest::getCmd('option');
		$task		= JRequest::getCmd('task');
		$view		= JRequest::getCmd('view');
		$layout		= JRequest::getCmd('layout');

		// Get the document and application objects.
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();

		if ($app->isAdmin())
		{
			switch ($component)
			{
				case 'com_content':
					if ($task == 'edit')
					{
						$id = JFilterInput::getInstance()->clean(empty($_REQUEST['cid'][0]) ? $_REQUEST['id'] : $_REQUEST['cid'][0], 'int');

						// Get the parameters for the article.
						$params = $this->_getArticleParams($id);

						// Get the HTML to include for comments integration.
						ob_start();
						include (dirname(__FILE__).'/comments/content_admin.php');
						$output = ob_get_contents();
						ob_end_clean();

						// Attempt to inject the integration markup into the page.
						$buffer = $doc->getBuffer('component');
						if ($start = strpos($buffer, '<h3 class="jpane-toggler title" id="metadata-page">'))
						{
							$inject = strpos($buffer, '</div>', $start);
							$buffer = substr($buffer, 0, $inject+12).$output.substr($buffer, $inject+12);
						}
						$doc->setBuffer($buffer, 'component');
					}
					break;
			}
		}
		else
		{
			switch ($component)
			{
				case 'com_content':
					if ($view == 'article' && $task == 'edit')
					{
						// Get the parameters for the article.
						$params = $this->_getArticleParams(JRequest::getInt('id'));

						// Get the HTML to include for comments integration.
						ob_start();
						include (dirname(__FILE__).'/comments/content_site.php');
						$output = ob_get_contents();
						ob_end_clean();

						// Attempt to inject the integration markup into the page.
						$buffer = $doc->getBuffer('component');
						if ($start = strpos($buffer, 'id="metakey" name="metakey"></textarea>'))
						{
							$inject = strpos($buffer, '</fieldset>', $start);
							$buffer = substr($buffer, 0, $inject+11).$output.substr($buffer, $inject+11);
						}

						$doc->setBuffer($buffer, 'component');
					}
					break;
			}
		}
	}

	protected function _getArticleParams($id)
	{
		$db = JFactory::getDBO();

		$db->setQuery(
			'SELECT attribs' .
			' FROM #__content' .
			' WHERE id = '.(int) $id
		);

		if ($text = $db->loadResult()) {
			$params = new JParameter($text);
		}
		else {
			$params = new JParameter('');
		}

		return $params;
	}

	/*************************
	 * User Related Handlers *
	 *************************/

	/**
	 * After a user's data has been stored in the database, if the user is set to blocked
	 * remove all references to the user from the comments system.
	 *
	 * @param	array	New user data.
	 * @param	boolean	True if the stored user was new.
	 * @param	boolean	True if user was succesfully stored in the database.
	 * @param	string	Message.
	 * @return	void
	 * @since	2.0
	 */
	public function onAfterStoreUser($user, $isnew, $success, $msg)
	{
		if ($user['block'] == 1) {
			$this->_deleteReferences($user['id']);
		}
	}

	/**
	 * Before a user's data has been deleted from the database remove all
	 * references to the user from the comments system.
	 *
	 * @param	array	Deleted user data.
	 * @return	boolean	True on success.
	 * @since	2.0
	 */
	public function onBeforeDeleteUser($user)
	{
		return $this->_deleteReferences($user['id']);
	}

	/**
	 * Delete the referencing information for a user from the comments tables.
	 *
	 * @param	integer	The Id of the user for which to remove comments.
	 * @return	boolean	True on success.
	 * @since	2.0
	 */
	protected function _deleteReferences($userId)
	{
		// Get a database connection object.
		$db = JFactory::getDBO();

		// Delete comments for the specified user.
		$db->setQuery('DELETE FROM #__social_comments WHERE user_id = '.(int) $userId);
		if (!$db->query())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return false;
		}

		return true;
	}
}
