<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * JHtml Helper class for Social
 *
 * @package		Joomla.Site
 * @version	1.0
 */
class JHtmlComments
{
	/**
	 * Generate a trackback RDF widget
	 *
	 * @static
	 * @param	string	$context	The item context
	 * @param	int		$id			The item id
	 * @param	string	$url		The standardized, simple url of the item
	 * @param	string	$route		The proper route to the item
	 * @param	string	$title		The title of the item
	 * @param	string	$excerpt	An excerpt of the item
	 * @param	string	$author		The item author name
	 * @return	string	A trackback RDF widget
	 * @since	1.6
	 */
	function trackbackRDF($context, $id, $url, $route, $title, $excerpt, $author)
	{
		// Initialize variables
		$widget = null;

		// Add the appropriate include paths for models.
		jimport('joomla.application.component.model');
		JModel::addIncludePath(JPATH_SITE.'/components/com_social/models');

		// Get and configure the thread model.
		$model = & JModel::getInstance('Thread', 'CommentsModel');
		$model->getState();
		$model->setState('thread.context', $context);
		$model->setState('thread.context_id', (int) $id);
		$model->setState('thread.url', $url);
		$model->setState('thread.route', $route);
		$model->setState('thread.title', $title);

		// Get the thread data.
		$thread = $model->getThread();

		// Get JDate object
		$date = JFactory::getDate($thread->created_date);

		// Get the trackback information for the item.
		jx('jx.webservices.trackback');
		$widget = JTrackback::getDiscoveryRdf(JRoute::_($thread->page_route, false, -1), JRoute::_('index.php?option=com_social&task=trackback.add&thread_id='.$thread->id, false, -1), $thread->page_title, $date->toRFC822(), $author);

		return $widget;
	}

	/**
	 * Generate a comments summary widget
	 *
	 * @static
	 * @param	string	$context	The item context to summarize
	 * @param	int		$id			The item id to summarize
	 * @param	string	$url		The standardized, simple url of the item to summarize
	 * @param	string	$route		The proper route to the them to summarize.
	 * @param	array	$options	Options for the comments summary display
	 * @return	string	A comments summary widget
	 * @since	1.6
	 */
	function summary($context, $id, $url, $route, $title, $options = array('style'=>'raw'))
	{
		// initialize variables
		$widget = null;

		// get the module renderer
		$document	= &JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');

		// build module configuration
		$config[] = 'url='.$url;
		$config[] = 'route='.$route;
		$config[] = 'title='.$title;
		$config[] = 'context='.$context;
		$config[] = 'context_id='.$id;
		$config[] = 'layout=simple';

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_comment');
		$module->params	= implode("\n", $config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Generate a comments list widget
	 *
	 * @static
	 * @param	string	$context	The item context to comment on
	 * @param	int		$id			The item id
	 * @param	string	$url		The standardized, simple url
	 * @param	string	$route		The proper route to the them
	 * @param	array	$options	Options for the comments widget display
	 * @return	string	A comments widget
	 * @since	1.6
	 */
	function comments($context, $id, $url, $route, $title, $options = array('style'=>'raw'))
	{
		// initialize variables
		$widget = null;

		// get the module renderer
		$document	= &JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');

		// build module configuration
		$config[] = 'url='.$url;
		$config[] = 'route='.$route;
		$config[] = 'title='.$title;
		$config[] = 'context='.$context;
		$config[] = 'context_id='.$id;

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_comment');
		$module->params	= implode("\n", $config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	function count($context, $id)
	{
		// Add the appropriate include paths for models.
		jimport('joomla.application.component.model');
		JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'models');

		// Get and configure the thread model.
		$model = & JModel::getInstance('Comments', 'CommentsModel');
		$model->getState();
		$model->setState('filter.context', $context);
		$model->setState('filter.context_id', (int) $id);

		// Get the thread data.
		$total = (int)$model->getTotal();

		return $total;
	}

	/**
	 * Generate a comments form widget
	 *
	 * @static
	 * @param	string	$context	The item context to post a comment for
	 * @param	int		$id			The item id to post a comment form
	 * @param	string	$url		The url of the item to post a comment form for
	 * @param	string	$route		The proper route to the item
	 * @param	array	$options	Options for the comments form display
	 * @return	string	A comments form widget
	 * @since	1.6
	 */
	function form($context, $id, $url, $route, $title, $options = array('style'=>'raw'))
	{
		// initialize variables
		$widget = null;

		// get the module renderer
		$document	= &JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');

		// build module configuration
		$config[] = 'url='.$url;
		$config[] = 'route='.$route;
		$config[] = 'title='.$title;
		$config[] = 'context='.$context;
		$config[] = 'context_id='.$id;
		$config[] = 'layout=form';

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_comment');
		$module->params	= implode("\n", $config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Generate a sharing widget
	 *
	 * @static
	 * @param	string	$route		The proper route to the item.
	 * @param	string	$title		The title of the item to share
	 * @param	array	$options	Options for the sharing widget display
	 * @return	string	A sharing widget
	 * @since	1.6
	 */
	function share($route, $title, $options = array('style'=>'raw'))
	{
		// initialize variables
		$widget = null;

		// get the module renderer
		$document	= &JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');

		// build module configuration
		$config[] = 'route='.$route;
		$config[] = 'title='.$title;
		if (!empty($options['feedflarepath'])) {
			$config[] = 'layout=feedflare';
			$config[] = 'feedflarepath='.$options['feedflarepath'];
		}

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_share');
		$module->params	= implode("\n", $config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Generate a rating widget
	 *
	 * @static
	 * @param	string	$context	The item context to rate
	 * @param	string	$id			The item id to rate
	 * @param	int		$categoryId	A category Id for the ratings (that is, a rating category relative to the context - not a real category)
	 * @param	array	$options	Options for the rating widget display
	 * @return	string	A rating widget
	 * @since	1.6
	 */
	function rating($context, $id, $url, $route, $title, $categoryId = 0, $options = array('style'=>'raw'))
	{
		// initialize variables
		$widget = null;

		// get the module renderer
		$document	= &JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');

		// build module configuration
		$config[] = 'url='.$url;
		$config[] = 'route='.$route;
		$config[] = 'title='.$title;
		$config[] = 'context='.$context;
		$config[] = 'context_id='.$id;
		$config[] = 'category_id='.$categoryId;

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_rating');
		$module->params	= implode("\n", $config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Generate a link to a mailto popup for
	 *
	 * @static
	 * @param	string	$title		Title for the link
	 * @param	string	$route		The proper route to the item
	 * @param	array	$attribs	Link attributes
	 * @return	string	The mailto link
	 * @since	1.6
	 */
	function email($title, $route, $attribs = array())
	{
		$url	= 'index.php?option=com_mailto&tmpl=component&link='.base64_encode($route);
		$status = 'width=400,height=300,menubar=yes,resizable=yes';

		$attribs['title']	= JText::_('SOCIAL_Email');
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
		$attribs['rel']		= 'nofollow';

		$result	= JHtml::_('link', JRoute::_($url), $title, $attribs);
		return $result;
	}
}
