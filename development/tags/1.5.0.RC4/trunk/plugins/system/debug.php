<?php
/**
* @version		$Id$
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Joomla! Debug plugin
 *
 * @author		Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla
 * @subpackage	System
 */
class  plgSystemDebug extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemDebug(& $subject, $config)
	{
		parent::__construct($subject, $config);

		//load the translation
		$this->loadLanguage( );
	}

	/**
	* Converting the site URL to fit to the HTTP request
	*
	*/
	function onAfterRender()
	{
		global $_PROFILER, $mainframe, $database;

		// Do not render if debugging is not enabled
		if(!JDEBUG) { return; }

		$document	=& JFactory::getDocument();
		$doctype	= $document->getType();

		// Only render for HTML output
		if ( $doctype !== 'html' ) { return; }

		$profiler	=& $_PROFILER;

		ob_start();
		echo '<div id="system-debug" class="profiler">';
		echo '<h4>'.JText::_( 'Profile Information' ).'</h4>';
		foreach ( $profiler->getBuffer() as $mark ) {
			echo '<div>'.$mark.'</div>';
		}

		if ($this->params->get('memory', 1)) {
			echo '<h4>'.JText::_( 'Memory Usage' ).'</h4>';
			echo $profiler->getMemory();
		}

		if ($this->params->get('queries', 1))
		{
			jimport('geshi.geshi');

			$geshi = new GeSHi( '', 'sql' );
			$geshi->set_header_type(GESHI_HEADER_DIV);
			//$geshi->enable_line_numbers( GESHI_FANCY_LINE_NONE );

			$newlineKeywords = '/<span style="color: #993333; font-weight: bold;">'
				.'(FROM|LEFT|INNER|OUTER|WHERE|SET|VALUES|ORDER|GROUP|HAVING|LIMIT|ON|AND)'
				.'<\\/span>/i'
			;

			$db	=& JFactory::getDBO();

			echo '<h4>'.JText::sprintf( 'Queries logged',  $db->getTicker() ).'</h4>';

			if ($log = $db->getLog())
			{
				echo '<ol>';
				foreach ($log as $k=>$sql)
				{
					$geshi->set_source($sql);
					$text = $geshi->parse_code();
					$text = preg_replace($newlineKeywords, '<br />&nbsp;&nbsp;\\0', $text);
					echo '<li>'.$text.'</li>';
				}
				echo '</ol>';
			}

			if(isset($database))
			{
				echo '<h4>'.JText::sprintf( 'Legacy Queries logged',  $database->getTicker() ).'</h4>';
				echo '<ol>';

					foreach ($database->getLog() as $k=>$sql)
					{
						$geshi->set_source($sql);
						$text = $geshi->parse_code();
						$text = preg_replace($newlineKeywords, '<br />&nbsp;&nbsp;\\0', $text);
						echo '<li>'.$text.'</li>';
					}

				echo '</ol>';
			}
		}

		if ($this->params->get('language', 1))
		{
			echo '<h4>'.JText::_( 'Language Files Loaded' ).'</h4>';
			echo '<ul>';
			$lang = &JFactory::getLanguage();
			$extensions	= $lang->getPaths();
			foreach ( $extensions as $extension => $files)
			{
				foreach ( $files as $file => $status )
				{
					echo "<li>$file $status</li>";
				}
			}
			echo '</ul>';

			echo '<h4>'.JText::_( 'Untranslated strings' ).'</h4>';
			echo '<pre>';
			$orphans = $lang->getOrphans();
			if (count( $orphans ))
			{
				ksort( $orphans, SORT_STRING );
				foreach ($orphans as $key => $occurance) {
					foreach ( $occurance as $i => $info) {
						$class	= $info['class'];
						$func	= $info['function'];
						$file	= $info['file'];
						$line	= $info['line'];
						echo strtoupper( $key )."\t$class::$func()\t[$file:$line]\n";
					}
				}
			}
			else {
				echo JText::_( 'None' );
			}
			echo '</pre>';
		}
		echo '</div>';

		$debug = ob_get_clean();

		$body = JResponse::getBody();
		$body = str_replace('</body>', $debug.'</body>', $body);
		JResponse::setBody($body);
	}
}