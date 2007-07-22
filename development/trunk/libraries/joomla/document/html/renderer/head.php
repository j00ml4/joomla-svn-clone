<?php
/**
* @version		$Id$
* @package		Joomla.Framework
* @subpackage	Document
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * JDocument head renderer
 *
 * @author		Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla.Framework
 * @subpackage	Document
 * @since		1.5
 */
class JDocumentRendererHead extends JDocumentRenderer
{
   /**
	 * Renders the document head and returns the results as a string
	 *
	 * @access public
	 * @param string 	$name		(unused)
	 * @param array 	$params		Associative array of values
	 * @return string	The output of the script
	 */
	function render( $head = null, $params = array(), $content = null )
	{
		ob_start();

		echo $this->fetchHead($this->_doc);

		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	/**
	 * Generates the head html and return the results as a string
	 *
	 * @access public
	 * @return string
	 */
	function fetchHead(&$document)
	{
		// get line endings
		$lnEnd = $document->_getLineEnd();
		$tab = $document->_getTab();

		$tagEnd		= ' />';
		$strHtml	= $tab . '<title>' . htmlspecialchars($document->getTitle()) . '</title>' . $lnEnd;

		$link = $document->getLink();
		if(!empty($link)) {
			$strHtml .= $tab . '<base href="' . $document->getLink() . '" />' . $lnEnd;
		}

		$strHtml .= $tab . '<meta name="description" content="' . $document->getDescription() . '" />' . $lnEnd;
		$strHtml .= $tab . '<meta name="generator" content="' . $document->getGenerator() . '" />' . $lnEnd;

		// Generate META tags
		foreach ($document->_metaTags as $type => $tag)
		{
			foreach ($tag as $name => $content)
			{
				if ($type == 'http-equiv') {
					$strHtml .= $tab . "<meta http-equiv=\"$name\" content=\"$content\"" . $tagEnd . $lnEnd;
				} elseif ($type == 'standard') {
					$strHtml .= $tab . "<meta name=\"$name\" content=\"$content\"" . $tagEnd . $lnEnd;
				}
			}
		}

		// Generate link declarations
		foreach ($document->_links as $link) {
			$strHtml .= $tab . $link . $tagEnd . $lnEnd;
		}

		// Generate stylesheet links
		foreach ($document->_styleSheets as $strSrc => $strAttr )
		{
			$strHtml .= $tab . "<link rel=\"stylesheet\" href=\"$strSrc\" type=\"".$strAttr['mime'].'"';
			if (!is_null($strAttr['media'])){
				$strHtml .= ' media="'.$strAttr['media'].'" ';
			}

			$strHtml .= JArrayHelper::toString($strAttr['attribs']);

			$strHtml .= $tagEnd . $lnEnd;
		}

		// Generate stylesheet declarations
		foreach ($document->_style as $styledecl)
		{
			foreach ($styledecl as $type => $content)
			{
				$strHtml .= $tab . '<style type="' . $type . '">' . $lnEnd;

				// This is for full XHTML support.
				if ($document->_mime == 'text/html' ) {
					$strHtml .= $tab . $tab . '<!--' . $lnEnd;
				} else {
					$strHtml .= $tab . $tab . '<![CDATA[' . $lnEnd;
				}

				$strHtml .= $content . $lnEnd;

				// See above note
				if ($document->_mime == 'text/html' ) {
					$strHtml .= $tab . $tab . '-->' . $lnEnd;
				} else {
					$strHtml .= $tab . $tab . ']]>' . $lnEnd;
				}
				$strHtml .= $tab . '</style>' . $lnEnd;
			}
		}

		// Generate script file links
		foreach ($document->_scripts as $strSrc => $strType) {
			$strHtml .= $tab . "<script type=\"$strType\" src=\"$strSrc\"></script>" . $lnEnd;
		}

		// Generate script declarations
		foreach ($document->_script as $script)
		{
			foreach ($script as $type => $content)
			{
				$strHtml .= $tab . '<script type="' . $type . '">' . $lnEnd;

				// This is for full XHTML support.
				if ($document->_mime != 'text/html' ) {
					$strHtml .= $tab . $tab . '<![CDATA[' . $lnEnd;
				}

				$strHtml .= $content . $lnEnd;

				// See above note
				if ($document->_mime != 'text/html' ) {
					$strHtml .= $tab . $tab . '// ]]>' . $lnEnd;
				}
				$strHtml .= $tab . '</script>' . $lnEnd;
			}
		}

		foreach($document->_custom as $custom) {
			$strHtml .= $tab . $custom .$lnEnd;
		}

		return $strHtml;
	}
}