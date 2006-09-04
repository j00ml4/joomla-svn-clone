<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Content
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

jimport( 'joomla.application.view');

/**
 * HTML View class for the Content component
 *
 * @static
 * @package Joomla
 * @subpackage Content
 * @since 1.5
 */
class ContentViewSection extends JView
{
	var $_viewName = 'section';
	
	function display($layout)
	{
		global $mainframe, $Itemid, $option;

		// Initialize some variables
		$user	  =& JFactory::getUser();
		$document =& JFactory::getDocument();
		$pathway  = & $mainframe->getPathWay();

		// Get some data from the model
		$categories	= & $this->get( 'Categories' );
		$items      = & $this->get( 'Content');
		$section    = & $this->get( 'Section' );
		$section->total = count($items);

		// Get the menu object of the active menu item
		$menu    =& JSiteHelper::getCurrentMenuItem();
		$params  =& JSiteHelper::getMenuParams();

		// Request variables
		$task 	    = JRequest::getVar('task');
		$limit		= JRequest::getVar('limit', $params->get('display_num'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		//add alternate feed link
		$link    = JURI::base() .'feed.php?option=com_content&task=section&id='.$section->id.'&Itemid='.$Itemid;
		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
		$document->addHeadLink($link.'&format=rss', 'alternate', 'rel', $attribs);
		$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
		$document->addHeadLink($link.'&format=atom', 'alternate', 'rel', $attribs);

		// Create a user access object for the user
		$access					= new stdClass();
		$access->canEdit		= $user->authorize('action', 'edit', 'content', 'all');
		$access->canEditOwn		= $user->authorize('action', 'edit', 'content', 'own');
		$access->canPublish		= $user->authorize('action', 'publish', 'content', 'all');

		// Set the page title and breadcrumbs
		$pathway->addItem($section->title, '');

		if (!empty ($menu->name)) {
			$mainframe->setPageTitle($menu->name);
		}

		$intro		= $params->def('intro', 	4);
		$leading	= $params->def('leading', 	1);
		$links		= $params->def('link', 		4);

		$params->def('empty_cat_section', 	0);
		$params->def('other_cat', 			1);
		$params->def('empty_cat', 			0);
		$params->def('cat_items', 			1);
		$params->def('pageclass_sfx', 		'');
		$params->set('intro_only', 			1);

		if ($section->total == 0) {
			$params->set('other_cat_section', false);
		}

		if ($params->def('page_title', 1)) {
			$params->def('header', $menu->name);
		}

		for($i = 0; $i < count($categories); $i++)
		{
			$category =& $categories[$i];
			$category->link = sefRelToAbs('index.php?option=com_content&amp;task=category&amp;sectionid='.$section->id.'&amp;id='.$category->id.'&amp;Itemid='.$Itemid);
		}

		$limit	= $intro + $leading + $links;
		$i		= $limitstart;

		jimport('joomla.presentation.pagination');
		$pagination = new JPagination(count($items), $limitstart, $limit);
		$link = 'index.php?option=com_content&amp;task=section&amp;id='.$section->id.'&amp;Itemid='.$Itemid;

		$request = new stdClass();
		$request->limit	 		= $limit;
		$request->limitstart	= $limitstart;

		$data = new stdClass();
		$data->link = $link;

		$this->set('data'      , $data);
		$this->set('items'     , $items);
		$this->set('request'   , $request);
		$this->set('section'   , $section);
		$this->set('categories', $categories);
		$this->set('params'    , $params);
		$this->set('user'      , $user);
		$this->set('access'    , $access);
		$this->set('pagination', $pagination);

		$this->_loadTemplate($layout);
	}

	function item( $index )
	{
		global $mainframe, $Itemid;

		// Initialize some variables
		$user		=& JFactory::getUser();
		$dispatcher	=& JEventDispatcher::getInstance();

		$SiteName	= $mainframe->getCfg('sitename');

		$task		= JRequest::getVar( 'task' );

		$linkOn		= null;
		$linkText	= null;

		// Get some parameters from global configuration
		$this->params->def('link_titles',	$mainframe->getCfg('link_titles'));
		$this->params->def('author',		!$mainframe->getCfg('hideAuthor'));
		$this->params->def('createdate',	!$mainframe->getCfg('hideCreateDate'));
		$this->params->def('modifydate',	!$mainframe->getCfg('hideModifyDate'));
		$this->params->def('print',			!$mainframe->getCfg('hidePrint'));
		$this->params->def('pdf',			!$mainframe->getCfg('hidePdf'));
		$this->params->def('email',			!$mainframe->getCfg('hideEmail'));
		$this->params->def('rating',		$mainframe->getCfg('vote'));
		$this->params->def('icons',			$mainframe->getCfg('icons'));
		$this->params->def('readmore',		$mainframe->getCfg('readmore'));
		$this->params->def('back_button', 	$mainframe->getCfg('back_button'));

		// Get some item specific parameters
		$this->params->def('image',				1);
		$this->params->def('section',			0);
		$this->params->def('section_link',		0);
		$this->params->def('category',			0);
		$this->params->def('category_link',		0);
		$this->params->def('introtext',			1);
		$this->params->def('pageclass_sfx',		'');
		$this->params->def('item_title',		1);
		$this->params->def('url',				1);
		$this->params->set('image',				1);

		$this->item =& $this->items[$index];

		// Process the content preparation plugins
		$this->item->text	= ampReplace($this->item->introtext);
		JPluginHelper::importPlugin('content');
		$results = $dispatcher->trigger('onPrepareContent', array (& $this->item, & $this->params, 0));

		// Build the link and text of the readmore button
		if (($this->params->get('readmore') && @ $this->item->readmore) || $this->params->get('link_titles'))
		{
			if ($this->params->get('intro_only'))
			{
				// checks if the item is a public or registered/special item
				if ($this->item->access <= $user->get('gid'))
				{
					$linkOn = sefRelToAbs("index.php?option=com_content&amp;task=view&amp;id=".$this->item->id."&amp;Itemid=".$Itemid);
					$linkText = JText::_('Read more...');
				}
				else
				{
					$linkOn = sefRelToAbs("index.php?option=com_registration&amp;task=register");
					$linkText = JText::_('Register to read more...');
				}
			}
		}

		$this->item->readmore_link = $linkOn;
		$this->item->readmore_text = $linkText;

		$this->item->print_link = $mainframe->getCfg('live_site').'/index2.php?option=com_content&amp;task=view&amp;id='.$this->item->id.'&amp;Itemid='.$Itemid.'&amp;pop=1';

		$this->item->event = new stdClass();
		$results = $dispatcher->trigger('onAfterDisplayTitle', array (& $this->item, & $this->params,0));
		$this->item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onBeforeDisplayContent', array (& $this->item, & $this->params, 0));
		$this->item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterDisplayContent', array (& $this->item, & $this->params, 0));
		$this->item->event->afterDisplayContent = trim(implode("\n", $results));

		$this->_loadTemplate('blog_item');
	}

	function icon($type, $attribs = array())
	{
		 global $Itemid, $mainframe;

		$url  = '';
		$text = '';

		$article = $this->item;

		switch($type)
		{
			case 'pdf' :
			{
				$url   = 'index2.php?option=com_content&amp;view=article&amp;id='.$article->id.'&amp;format=pdf';
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

				// checks template image directory for image, if non found default are loaded
				if ($this->params->get('icons')) {
					$text = mosAdminMenus::ImageCheck('pdf_button.png', '/images/M_images/', NULL, NULL, JText::_('PDF'), JText::_('PDF'));
				} else {
					$text = JText::_('PDF').'&nbsp;';
				}

				$attribs['title']   = JText::_( 'PDF' );
				$attribs['onclick'] = "window.open('".$url."','win2','".$status."'); return false;";

			} break;

			case 'print' :
			{
				$url    = 'index2.php?option=com_content&amp;task=view&amp;id='.$article->id.'&amp;Itemid='.$Itemid.'&amp;pop=1&amp;page='.@ $this->request->limitstart;
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

				// checks template image directory for image, if non found default are loaded
				if ( $this->params->get( 'icons' ) ) {
					$text = mosAdminMenus::ImageCheck( 'printButton.png', '/images/M_images/', NULL, NULL, JText::_( 'Print' ), JText::_( 'Print' ) );
				} else {
					$text = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'Print' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
				}

				$attribs['title']   = JText::_( 'Print' );
				$attribs['onclick'] = "window.open('".$url."','win2','".$status."'); return false;";

			} break;

			case 'email' :
			{
				$url   = 'index2.php?option=com_mailto&amp;link='.urlencode( JRequest::getUrl());
				$status = 'width=400,height=300,menubar=yes,resizable=yes';

				$attribs['title']   = JText::_( 'Email ' );
				$attribs['onclick'] = "window.open('".$url."','win2','".$status."'); return false;";

				if ($this->params->get('icons')) 	{
					$text = mosAdminMenus::ImageCheck('emailButton.png', '/images/M_images/', NULL, NULL, JText::_('Email'), JText::_('Email'));
				} else {
					$text = '&nbsp;'.JText::_('Email');
				}
			} break;

			case 'edit' :
			{
				if ($this->params->get('popup')) {
					return;
				}
				if ($article->state < 0) {
					return;
				}
				if (!$this->access->canEdit && !($this->access->canEditOwn && $article->created_by == $this->user->get('id'))) {
					return;
				}

				mosCommonHTML::loadOverlib();

				$url = 'index.php?option=com_content&amp;task=edit&amp;id='.$article->id.'&amp;Itemid='.$Itemid.'&amp;Returnid='.$Itemid;
				$text = mosAdminMenus::ImageCheck('edit.png', '/images/M_images/', NULL, NULL, JText::_('Edit'), JText::_('Edit'). $article->id );

				if ($article->state == 0) {
					$overlib = JText::_('Unpublished');
				} else {
					$overlib = JText::_('Published');
				}
				$date = mosFormatDate($article->created);
				$author = $article->created_by_alias ? $article->created_by_alias : $article->author;

				$overlib .= '<br />';
				$overlib .= $article->groups;
				$overlib .= '<br />';
				$overlib .= $date;
				$overlib .= '<br />';
				$overlib .= $author;

				$attribs['onmouseover'] = "return overlib('".$overlib."', CAPTION, '".JText::_( 'Edit Item' )."', BELOW, RIGHT)";
				$attribs['onmouseover'] = "return nd();";

			} break;
		}

		echo mosHTML::Link($url, $text, $attribs);
	}

	function links( $index )
	{
		global $Itemid;

		$this->links = array_splice($this->items, $index);

		for($i = 0; $i < count($this->links); $i++)
		{
			$link =& $this->links[$i];
			$link->link	= sefRelToAbs('index.php?option=com_content&amp;task=view&amp;id='.$link->id.'&amp;Itemid='.$Itemid);
		}

		$this->_loadTemplate('blog_links');
	}

	/**
	 * Name of the view.
	 *
	 * @access	private
	 * @var		string
	 */
	function _displayFeed()
	{
		$doc =& JFactory::getDocument();

		// Lets get our data from the model
		$rows = & $this->get( 'Section' );

		foreach ( $rows as $row )
		{
			// strip html from feed item title
			$title = htmlspecialchars( $row->title );
			$title = html_entity_decode( $title );

			// url link to article
			// & used instead of &amp; as this is converted by feed creator
			$link = 'index.php?option=com_content&task=view&id='. $row->id . $Itemid;
			$link = sefRelToAbs( $link );

			// strip html from feed item description text
			$description = $row->introtext;
			@$date = ( $row->created ? date( 'r', $row->created ) : '' );

			// load individual item creator class
			$item = new JFeedItem();
			$item->title 		= $title;
			$item->link 		= $link;
			$item->description 	= $description;
			$item->date			= $date;
			$item->category   	= $row->category;

			// loads item info into rss array
			$doc->addItem( $item );
		}
	}
}
?>