<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Page break plugin
 *
 * <b>Usage:</b>
 * <code><hr class="system-pagebreak" /></code>
 * <code><hr class="system-pagebreak" title="The page title" /></code>
 * or
 * <code><hr class="system-pagebreak" alt="The first page" /></code>
 * or
 * <code><hr class="system-pagebreak" title="The page title" alt="The first page" /></code>
 * or
 * <code><hr class="system-pagebreak" alt="The first page" title="The page title" /></code>
 *
 * @package		Joomla
 * @subpackage	plg_pagebreak
 */
class plgContentPagebreak extends JPlugin
{

	public function onPrepareContent(&$row, &$params, $page=0)
	{
		// Expression to search for.
		$regex = '#<hr(.*)class=\"system-pagebreak\"(.*)\/>#iU';

		$print = JRequest::getBool('print');
		$showall = JRequest::getBool('showall');

		if (!$this->params->get('enabled', 1)) {
			$print = true;
		}

		if ($print) {
			$row->text = preg_replace($regex, '<BR/>', $row->text);
			return true;
		}

		// Simple performance check to determine whether bot should process further.
		if (JString::strpos($row->text, 'class="system-pagebreak') === false) {
			return true;
		}

		$db = &JFactory::getDbo();
		$full = JRequest::getBool('fullview');

		if (!$page) {
			$page = 0;
		}

		if ($params->get('intro_only') || $params->get('popup') || $full) {
			$row->text = preg_replace($regex, '', $row->text);
			return;
		}

		// Find all instances of plugin and put in $matches.
		$matches = array();
		preg_match_all($regex, $row->text, $matches, PREG_SET_ORDER);

		if (($showall && $this->params->get('showall', 1)))
		{
			$hasToc = $this->params->get('multipage_toc', 1);
			if ($hasToc)
			{
				// Display TOC.
				$page = 1;
				$this->_createToc($row, $matches, $page);
			}
			else {
				$row->toc = '';
			}
			$row->text = preg_replace($regex, '<BR/>', $row->text);
			return true;
		}

		// Split the text around the plugin.
		$text = preg_split($regex, $row->text);

		// Count the number of pages.
		$n = count($text);

		// We have found at least one plugin, therefore at least 2 pages.
		if ($n > 1)
		{
			$title	= $this->params->get('title', 1);
			$hasToc = $this->params->get('multipage_toc', 1);
			// Adds heading or title to <site> Title.
			if ($title)
			{
				if ($page)
				{
					$page_text = $page + 1;
					if ($page && @$matches[$page-1][2])
					{
						$attrs = JUtility::parseAttributes($matches[$page-1][1]);

						if (@$attrs['title']) {
							$row->page_title = $attrs['title'];
						}
					}
				}
			}

			// Reset the text, we already hold it in the $text array.
			$row->text = '';

			// Display TOC.
			if ($hasToc) {
				$this->_createToc($row, $matches, $page);
			} else {
				$row->toc = '';
			}

			// Page counter.
			$row->text .= '<div class="pagenavcounter">';
			$row->text .= $pageNav->getPagesCounter();
			$row->text .= '</div>';

			// Page text.
			$text[$page] = str_replace("<hr id=\"\"system-readmore\"\" />", "", $text[$page]);
			$row->text .= $text[$page];

			$row->text .= '<br />';
			$row->text .= '<div class="pagenavbar">';

			// Adds navigation between pages to bottom of text.
			if ($hasToc) {
				$this->_createNavigation($row, $page, $n);
			}

			// Page links shown at bottom of page if TOC disabled.
			if (!$hasToc) {
				$row->text .= $pageNav->getPagesLinks();
			}

			$row->text .= '</div><br />';
		}

		return true;
	}

	protected function _createTOC(&$row, &$matches, &$page)
	{

		$heading = $row->title;

		// TOC header.
		$row->toc = '
		<table cellpadding="0" cellspacing="0" class="contenttoc" align="right">
		<tr>
			<th>'
			. JText::_('Article Index') .
			'</th>
		</tr>
		';

		// TOC first Page link.
		$row->toc .= '
		<tr>
			<td>
			<a href="'. JRoute::_('&showall=&limitstart=') .'" class="toclink">'
			. $heading .
			'</a>
			</td>
		</tr>
		';

		$i = 2;

		foreach ($matches as $bot)
		{
			$link = JRoute::_('&showall=&limitstart='. ($i-1));


			if (@$bot[0])
			{
				$attrs2 = JUtility::parseAttributes($bot[0]);

				if (@$attrs2['alt']) {
					$title	= stripslashes($attrs2['alt']);
				} elseif (@$attrs2['title']) {
					$title	= stripslashes($attrs2['title']);
				} else {
					$title	= JText::sprintf('Page #', $i);
				}
			}
			else {
				$title	= JText::sprintf('Page #', $i);
			}

			$row->toc .= '
				<tr>
					<td>
					<a href="'. $link .'" class="toclink">'
					. $title .
					'</a>
					</td>
				</tr>
				';
			$i++;
		}

		if ($this->params->get('showall'))
		{
			$link = JRoute::_('&showall=1&limitstart=');
			$row->toc .= '
			<tr>
				<td>
					<a href="'. $link .'" class="toclink">'
					. JText::_('All Pages') .
					'</a>
				</td>
			</tr>
			';
		}
		$row->toc .= '</table>';
	}

	protected function _createNavigation(&$row, $page, $n)
	{
		$pnSpace = "";
		if (JText::_('LT') || JText::_('GT')) $pnSpace = " ";

		if ($page < $n-1)
		{
			$page_next = $page + 1;

			$link_next = JRoute::_('&limitstart='. ($page_next));
			// Next >>
			$next = '<a href="'. $link_next .'">' . JText::_('Next') . $pnSpace . JText::_('JCOMMON_GT') . JText::_('JCOMMON_GT') .'</a>';
		}
		else {
			$next = JText::_('Next');
		}

		if ($page > 0)
		{
			$page_prev = $page - 1 == 0 ? "" : $page - 1;

			$link_prev = JRoute::_( '&limitstart='. ($page_prev));
			// << Prev
			$prev = '<a href="'. $link_prev .'">'. JText::_('JCOMMON_LT') . JText::_('JCOMMON_LT') . $pnSpace . JText::_('Prev') .'</a>';
		}
		else {
			$prev = JText::_('Prev');
		}

		$row->text .= '<div>' . $prev . ' - ' . $next .'</div>';
	}
}