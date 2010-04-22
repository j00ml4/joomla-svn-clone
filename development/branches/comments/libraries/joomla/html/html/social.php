<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * JHtml Helper class for Social
 *
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @since		1.6
 */
class JHtmlSocial
{
	/**
	 * Generate a list of comments for an item of content.
	 *
	 * @param	string	The context of the content.
	 * @param	string	The proper route to the content.
	 * @param	string	The title of the content.
	 * @param	array	Options for the comments widget display
	 * @return	string	A rendered comments list.
	 * @since	1.6
	 */
	public static function comments($context, $route, $title, $options = array('style'=>'raw'))
	{
		// Initialise variables
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$parts		= explode('.', $context);
		$option		= $parts[0];

		// Build module configuration.
		$config = array(
			'context'	=> $context,
			'option'	=> $option,
			'route'		=> $route,
			'title'		=> $title
		);

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_comment');
		$module->params	= json_encode($config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Computes the number of comments made against an item of content.
	 *
	 * @param	string	The context of the content.
	 * @return	int		The number of comments made against the content.
	 * @since	1.6
	 */
	public static function commentCount($context)
	{
		// Add the appropriate include paths for models.
		jimport('joomla.social2.comments');

		// Get and configure the thread model.
		$model = new JComments;

		// Get the thread data.
		$total = (int) $model->getTotalByContext($context);

		if ($error = $model->getError()) {
			JError::raiseWarning(500, $error);
		}

		return $total;
	}

	/**
	 * Generates an email link to the MailTo component.
	 *
	 * @param	string	The context of the content.
	 * @param	string	The proper route to the content.
	 * @param	string	The title of the content.
	 * @param	array	Link attributes.
	 * @return	string	The mailto link.
	 * @since	1.6
	 */
	public static function email($context, $route, $title, $attribs = array())
	{
		$url	= 'index.php?option=com_mailto&tmpl=component&link='.base64_encode($route);
		$status = 'width=400,height=300,menubar=yes,resizable=yes';

		$attribs = array(
			'title'		=> JText::_('SOCIAL_Email'),
			'onclick'	=> "window.open(this.href,'win2','$status'); return false;",
			'rel'		=> 'nofollow'
		);

		$result	= JHtml::_('link', JRoute::_($url), $title, $attribs);
		return $result;
	}

	/**
	 * Generates a comment submission form.
	 *
	 * @param	string	The context of the content.
	 * @param	string	The proper route to the content.
	 * @param	string	The title of the content.
	 * @param	array	Options for the comments form display.
	 * @return	string	A rendered comment submission form.
	 * @since	1.6
	 */
	public static function form($context, $route, $title, $options = array('style'=>'raw'))
	{
		// Initialise variables
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$parts		= explode('.', $context);
		$option		= $parts[0];

		// Build module configuration
		$config = array(
			'context'	=> $context,
			'option'	=> $option,
			'layout'	=> 'form',
			'route'		=> $route,
			'title'		=> $title
		);

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_comment');
		$module->params	= json_encode($config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Generate a list of ratings stars.
	 *
	 * @param	string	The context of the content.
	 * @param	string	The proper route to the content.
	 * @param	string	The title of the content.
	 * @param	array	Options for the rating widget display.
	 * @return	string	A rendered list of rating stars.
	 * @since	1.6
	 */
	public static function rating($context, $route, $title, $options = array('style'=>'raw'))
	{
		// Initialise variables
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$parts		= explode('.', $context);
		$option		= $parts[0];

		// Build module configuration.
		$config = array(
			'context'	=> $context,
			'option'	=> $option,
			'route'		=> $route,
			'title'		=> $title
		);

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_rating');
		$module->params	= json_encode($config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Generate a list of sharing links.
	 *
	 * @param	string	The context of the content.
	 * @param	string	The proper route to the content.
	 * @param	string	The title of the content.
	 * @param	array	Options for the sharing widget display
	 * @return	string	A sharing widget
	 * @since	1.6
	 */
	public static function share($context, $route, $title, $options = array('style'=>'raw'))
	{
		// Initialise variables
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$parts		= explode('.', $context);
		$option		= $parts[0];

		// Build module configuration.
		$config = array(
			'option'	=> $option,
			'route'		=> $route,
			'title'		=> $title
		);
		if (!empty($options['feedflarepath'])) {
			$config['layout']			= 'feedflare';
			$config['feedflarepath']	= $options['feedflarepath'];
		}

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_share');
		$module->params	= json_encode($config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Generate a summary of the comments made against an item of content.
	 *
	 * @param	string	The context of the content.
	 * @param	string	The proper route to the content.
	 * @param	string	The title of the content.
	 * @param	array	Options for the comments summary display.
	 * @return	string	A comments summary widget.
	 * @since	1.6
	 */
	public static function summary($context, $route, $title, $options = array('style'=>'raw'))
	{
		// Initialise variables.
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$parts		= explode('.', $context);
		$option		= $parts[0];

		// Build module configuration.
		$config = array(
			'context'	=> $context,
			'option'	=> $option,
			'layout'	=> 'simple',
			'route'		=> $route,
			'title'		=> $title
		);

		// build module configuration
		$config[] = 'route='.$route;
		$config[] = 'title='.$title;
		$config[] = 'context='.$context;
		$config[] = 'layout=simple';

		// get the module object for the comments module
		$module	= JModuleHelper::getModule('mod_social_comment');
		$module->params	= json_encode($config);

		// render the module
		$widget	= $renderer->render($module, $options);

		return $widget;
	}

	/**
	 * Generates a trackback RDF widget for an item of content.
	 *
	 * @param	string	The context of the content.
	 * @param	string	The proper route to the content.
	 * @param	string	The title of the content.
	 * @param	string	An excerpt of the item.
	 * @param	string	The item author name.
	 * @return	string	A trackback RDF widget.
	 * @since	1.6
	 */
	public static function trackbackRDF($context, $route, $title, $excerpt, $author)
	{
		jimport('joomla.social.trackback');

		// Initialize variables
		$model	= new JTrackback;
		$parts	= explode('.', $context);
		$option	= $parts[0];

		$model->setState('thread.context', $context);
		$model->setState('thread.route', $route);
		$model->setState('thread.title', $title);

		// Get the thread data.
		$thread = $model->getThread();

		// Get JDate object
		$date = JFactory::getDate($thread->created_time);

		// Get the trackback information for the item.
		jimport('joomla.webservices.trackback');
		$widget = JTrackback::getDiscoveryRdf(JRoute::_($thread->page_route, false, -1), JRoute::_('index.php?option='.$option.'&task=trackback.add&thread_id='.$thread->id, false, -1), $thread->page_title, $date->toRFC822(), $author);

		return $widget;
	}
}