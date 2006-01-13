<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Templates
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// ensure user has access to this function
if (!$acl->acl_check('com_templates', 'manage', 'users', $GLOBALS['my']->usertype)) {
	josRedirect('index2.php', JText :: _('ALERTNOTAUTH'));
}

require_once (JApplicationHelper :: getPath('admin_html'));
require_once (JPATH_ADMINISTRATOR.'/components/com_templates/admin.templates.class.php');

$option	= JRequest :: getVar('option', '');
$task	= JRequest :: getVar('task', '');
$client = JRequest :: getVar('client', '');

$cid = JRequest :: getVar('cid', array (0), '', 'array');

if (!is_array($cid)) {
	$cid = array (0);
}

switch ($task) {
	case 'new' :
		// Removing the install option from the template manager
		//josRedirect ( 'index2.php?option=com_installer&task=installer&client='. $client );
		break;

	case 'edit_params' :
		JTemplatesController :: editTemplateParams($cid[0]);
		break;

	case 'save_params' :
		JTemplatesController :: saveTemplateParams();
		break;

	case 'edit_source' :
		JTemplatesController :: editTemplateSource($cid[0]);
		break;

	case 'save_source' :
		JTemplatesController :: saveTemplateSource();
		break;

	case 'choose_css' :
		JTemplatesController :: chooseTemplateCSS($cid[0]);
		break;

	case 'edit_css' :
		JTemplatesController :: editTemplateCSS($cid[0]);
		break;

	case 'save_css' :
		JTemplatesController :: saveTemplateCSS($cid[0]);
		break;

	case 'remove' :
		// Remove from template manager
		//removeTemplate( $cid[0], $client );
		break;

	case 'publish' :
		JTemplatesController :: defaultTemplate($cid[0]);
		break;

	case 'default' :
		JTemplatesController :: defaultTemplate($cid[0]);
		break;

	case 'assign' :
		JTemplatesController :: assignTemplate($cid[0]);
		break;

	case 'save_assign' :
		JTemplatesController :: saveTemplateAssign();
		break;

	case 'cancel' :
		josRedirect('index2.php?option='.$option.'&client='.$client);
		break;

	case 'positions' :
		JTemplatesController :: editPositions();
		break;

	case 'save_positions' :
		JTemplatesController :: savePositions();
		break;

	default :
		JTemplatesController :: viewTemplates();
		break;
}

class JTemplatesController {

	/**
	* Compiles a list of installed, version 4.5+ templates
	*
	* Based on xml files found.  If no xml file found the template
	* is ignored
	*/
	function viewTemplates() {
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$db 	= & $mainframe->getDBO();
		$option = JRequest::getVar( 'option', '' );
		$client = JRequest::getVar( 'client', 'site' );
		$rows	= array();
		$i = 0;

		// Initialize the pagination variables
		$limit 		= $mainframe->getUserStateFromRequest( "limit", 'limit', $mainframe->getCfg('list_limit') );
		$limitstart = $mainframe->getUserStateFromRequest( "$option.limitstart", 'limitstart', 0 );

		if ($client == 'administration') {
			$templateBaseDir = JPath :: clean(JPATH_ADMINISTRATOR.DS.'templates');
		} else {
			$templateBaseDir = JPath :: clean(JPATH_SITE.DS.'templates');
		}

		// Read the template folder to find templates
		$templateDirs = JFolder :: folders($templateBaseDir);

		$id = intval($client == 'administration');

		if ($client == 'administration') {
			$query = "SELECT template".
						"\n FROM #__templates_menu".
						"\n WHERE client_id = 1".
						"\n AND menuid = 0";
			$db->setQuery($query);
		} else {
			$query = "SELECT template".
						"\n FROM #__templates_menu".
						"\n WHERE client_id = 0".
						"\n AND menuid = 0";
			$db->setQuery($query);
		}
		$curTemplate = $db->loadResult();

		// Check that the directory contains an xml file
		foreach ($templateDirs as $templateDir) {
			$dirName = JPath :: clean($templateBaseDir.$templateDir);
			$xmlFilesInDir = JFolder :: files($dirName, '.xml$');

			foreach ($xmlFilesInDir as $xmlfile) {
				// Read the file to see if it's a valid template XML file
				$xmlDoc = & JFactory :: getXMLParser();
				$xmlDoc->resolveErrors(true);
				if (!$xmlDoc->loadXML($dirName.$xmlfile, false, true)) {
					continue;
				}

				$root = & $xmlDoc->documentElement;

				if ($root->getTagName() != 'mosinstall' && $root->getTagName() != 'install') {
					continue;
				}
				if ($root->getAttribute('type') != 'template') {
					continue;
				}

				$row = new StdClass();
				$row->id = $i;
				$row->directory = $templateDir;
				$element = & $root->getElementsByPath('name', 1);
				$row->name = $element->getText();

				$element = & $root->getElementsByPath('creationDate', 1);
				$row->creationdate = $element ? $element->getText() : 'Unknown';

				$element = & $root->getElementsByPath('author', 1);
				$row->author = $element ? $element->getText() : 'Unknown';

				$element = & $root->getElementsByPath('copyright', 1);
				$row->copyright = $element ? $element->getText() : '';

				$element = & $root->getElementsByPath('authorEmail', 1);
				$row->authorEmail = $element ? $element->getText() : '';

				$element = & $root->getElementsByPath('authorUrl', 1);
				$row->authorUrl = $element ? $element->getText() : '';

				$element = & $root->getElementsByPath('version', 1);
				$row->version = $element ? $element->getText() : '';

				// Get info from db
				if ($curTemplate == $templateDir) {
					$row->published = 1;
				} else {
					$row->published = 0;
				}

				$row->checked_out = 0;
				$row->mosname = strtolower(str_replace(' ', '_', $row->name));

				// check if template is assigned
				$query = "SELECT COUNT(*)".
							"\n FROM #__templates_menu".
							"\n WHERE client_id = 0".
							"\n AND template = '$row->directory'".
							"\n AND menuid <> 0";
				$db->setQuery($query);
				$row->assigned = $db->loadResult() ? 1 : 0;

				$rows[] = $row;
				$i ++;
			}
		}

		jimport('joomla.pagination');
		$page = new JPagination(count($rows), $limitstart, $limit);

		$rows = array_slice($rows, $page->limitstart, $page->limit);

		JTemplatesView :: showTemplates($rows, $page, $option, $client);
	}

	/**
	* Publish, or make current, the selected template
	*/
	function defaultTemplate($p_tname) {
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$db 	= & $mainframe->getDBO();
		$option = JRequest::getVar( 'option', '' );
		$client = JRequest::getVar( 'client', '' );
		
		if ($client == 'administration') {
			$clientID = 1;
		} else {
			$clientID = 0;
		}

		$query = "DELETE FROM #__templates_menu".
					"\n WHERE client_id = $clientID". 
					"\n AND menuid = 0";
		$db->setQuery($query);
		$db->query();

		$query = "INSERT INTO #__templates_menu".
					"\n SET client_id = $clientID, template = '$p_tname', menuid = 0";
		$db->setQuery($query);
		$db->query();

//			$_SESSION['cur_template'] = $p_tname;

		josRedirect('index2.php?option='.$option.'&client='.$client);
	}

	/**
	* Remove the selected template
	*/
	function removeTemplate($cid) {
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$db 	= & $mainframe->getDBO();
		$option = JRequest::getVar( 'option', '' );
		$client = JRequest::getVar( 'client', '' );

		$client_id = $client == 'administration' ? 1 : 0;

		$query = "SELECT template".
					"\n FROM #__templates_menu".
					"\n WHERE client_id = $client_id".
					"\n AND menuid = 0";
		$db->setQuery($query);
		$cur_template = $db->loadResult();

		if ($cur_template == $cid) {
			mosErrorAlert(JText :: _('You can not delete template in use.'));
		}

		// Un-assign
		$query = "DELETE FROM #__templates_menu".
					"\n WHERE template = '$cid'".
					"\n AND client_id = $client_id".
					"\n AND menuid <> 0";
		$db->setQuery($query);
		$db->query();

		josRedirect('index2.php?option=com_installer&type=template&client='.$client.'&task=remove&eid[]='.$cid);
	}

	function editTemplateParams($p_tname) {
		/*
		 * Initialize some variables
		 */
		$option = JRequest::getVar( 'option', '' );
		$client = JRequest::getVar( 'client', '' );

		if ($client == 'administration') {
			$ini = JPATH_ADMINISTRATOR.DS.'templates'.DS.$p_tname.DS.'params.ini';
			$xml = JPATH_ADMINISTRATOR.DS.'templates'.DS.$p_tname.DS.'templateDetails.xml';
		} else {
			$ini = JPATH_SITE.DS.'templates'.DS.$p_tname.DS.'params.ini';
			$xml = JPATH_SITE.DS.'templates'.DS.$p_tname.DS.'templateDetails.xml';
		}

		// Read the ini file
		if (JFile :: exists($ini)) {
			$content = JFile :: read($ini);
		} else {
			$content = null;
		}
		
		$params = new JParameters($content, $xml, 'template');

		JTemplatesView :: editTemplateParams($p_tname, $params, $option, $client);
	}

	function saveTemplateParams() {
		/*
		 * Initialize some variables
		 */
		$option 		= JRequest::getVar( 'option', '' );
		$client 		= JRequest::getVar( 'client', '' );
		$template 		= JRequest::getVar( 'template', '' );
		$params 		= JRequest::getVar( 'params', array(), '', 'array' );

		if (!$template) {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('Operation Failed').': '.JText :: _('No template specified.'));
		}

		if ($client == 'administration') {
			$file = JPATH_ADMINISTRATOR.DS.'templates'.DS.$template.DS.'params.ini';
		} else {
			$file = JPATH_SITE.DS.'templates'.DS.$template.DS.'params.ini';
		}

		if (is_array( $params )) {
			$txt = null;
			foreach ($params as $k=>$v) {
				$txt .= "$k=$v\n";
			}
		} else {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('No Parameters To Save'));
		}

		if (JFile::write($file, $txt)) {
			josRedirect('index2.php?option='.$option.'&client='.$client);
		} else {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('Operation Failed').': '.JText :: _('Failed to open file for writing.'));
		}
	}

	function editTemplateSource($p_tname) {
		/*
		 * Initialize some variables
		 */
		$option = JRequest::getVar( 'option', '' );
		$client = JRequest::getVar( 'client', '' );

		if ($client == 'administration') {
			$file = JPATH_ADMINISTRATOR.DS.'templates'.DS.$p_tname.DS.'index.php';
		} else {
			$file = JPATH_SITE.DS.'templates'.DS.$p_tname.DS.'index.php';
		}

		// Read the source file
		$content = JFile :: read($file);
		if ($content !== false) {
			$content = htmlspecialchars($content);

			JTemplatesView :: editTemplateSource($p_tname, $content, $option, $client);
		} else {
			$msg = sprintf(JText :: _('Operation Failed Could not open'), $file);
			josRedirect('index2.php?option='.$option.'&client='.$client, $msg);
		}
	}

	function saveTemplateSource() {
		/*
		 * Initialize some variables
		 */
		$option 		= JRequest::getVar( 'option', '' );
		$client 		= JRequest::getVar( 'client', '' );
		$template 		= JRequest::getVar( 'template', '' );
		$enableWrite 	= JRequest::getVar( 'enable_write', 0, '', 'int' );
		$filecontent 	= JRequest::getVar( 'filecontent', '', '', '', _J_ALLOWHTML);

		if (!$template) {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('Operation Failed').': '.JText :: _('No template specified.'));
		}
		if (!$filecontent) {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('Operation Failed').': '.JText :: _('Content empty.'));
		}

		if ($client == 'administration') {
			$file = JPATH_ADMINISTRATOR.DS.'templates'.DS.$template.DS.'index.php';
		} else {
			$file = JPATH_SITE.DS.'templates'.DS.$template.DS.'index.php';
		}

		if (JFile::write($file, $filecontent)) {
			josRedirect('index2.php?option='.$option.'&client='.$client);
		} else {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('Operation Failed').': '.JText :: _('Failed to open file for writing.'));
		}
	}

	function chooseTemplateCSS($p_tname) {
		/*
		 * Initialize some variables
		 */
		$option = JRequest::getVar( 'option', '' );
		$client = JRequest::getVar( 'client', '' );

		if ($client == 'administration') {
			// Admin template css dir
			$a_dir = JPATH_ADMINISTRATOR.DS.'templates'.DS.$p_tname.DS.'css';
			// List .css files
			$a_files = JFolder :: files($a_dir, $filter = '\.css$', $recurse = false, $fullpath = false);
			$fs_dir = null;
			$fs_files = null;

			JTemplatesView :: chooseCSSFiles($p_tname, $a_dir, $fs_dir, $a_files, $fs_files, $option, $client);

		} else {
			// Template css dir
			$f_dir = JPATH_SITE.DS.'templates'.DS.$p_tname.DS.'css';
			// System css dir
			$fs_dir = JPATH_SITE.DS.'templates'.DS.'_system'.DS.'css';

			// List template .css files
			$f_files = JFolder :: files($f_dir, $filter = '\.css$', $recurse = false, $fullpath = false);
			// List system .css files
			$fs_files = JFolder :: files($fs_dir, $filter = '\.css$', $recurse = false, $fullpath = false);

			JTemplatesView :: chooseCSSFiles($p_tname, $f_dir, $fs_dir, $f_files, $fs_files, $option, $client);

		}
	}

	function editTemplateCSS($p_tname) {
		/*
		 * Initialize some variables
		 */
		$option 	= JRequest::getVar( 'option', '' );
		$client 	= JRequest::getVar( 'client', '' );
		$template 	= JRequest::getVar( 'template', '' );
		$tp_name 	= JRequest::getVar( 'tp_name', '' );

		if ($client == 'administration') {
			$file = JPATH_ADMINISTRATOR.$tp_name;
			$p_tname = $template;

		} else {
			$file = JPATH_SITE.$tp_name;
			$p_tname = $template;
		}

		$content = JFile::read($file);
		if ($content !== false) {
			$content = htmlspecialchars($content);

			JTemplatesView :: editCSSSource($p_tname, $tp_name, $content, $option, $client);
		} else {
			$msg = sprintf(JText :: _('Operation Failed Could not open'), $file);
			josRedirect('index2.php?option='.$option.'&client='.$client, $msg);
		}
	}

	function saveTemplateCSS($option, $client) {
		/*
		 * Initialize some variables
		 */
		$option 		= JRequest::getVar( 'option', '' );
		$client 		= JRequest::getVar( 'client', '' );
		$template 		= JRequest::getVar( 'template', '' );
		$tp_fname 		= JRequest::getVar( 'tp_fname', '' );
		$filecontent 	= JRequest::getVar( 'filecontent', '', '', '', _J_ALLOWHTML);

		if (!$template) {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('Operation Failed').': '.JText :: _('No template specified.'));
		}

		if (!$filecontent) {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('Operation Failed').': '.JText :: _('Content empty.'));
		}

		if (JFile::write($tp_fname, $filecontent)) {
			josRedirect('index2.php?option='.$option.'&client='.$client);
		} else {
			josRedirect('index2.php?option='.$option.'&client='.$client, JText :: _('Operation Failed').': '.JText :: _('Failed to open file for writing.'));
		}
	}

	function assignTemplate($p_tname) {
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$db 	= & $mainframe->getDBO();
		$option = JRequest::getVar( 'option', '' );
		$client = JRequest::getVar( 'client', '' );

		// get selected pages for $menulist
		if ($p_tname) {
			$query = 	"SELECT menuid AS value".
						"\n FROM #__templates_menu".
						"\n WHERE client_id = 0". 
						"\n AND template = '$p_tname'";
			$db->setQuery($query);
			$lookup = $db->loadObjectList();
		}

		// build the html select list
		$menulist = mosAdminMenus :: MenuLinks($lookup, 0, 1);

		JTemplatesView :: assignTemplate($p_tname, $menulist, $option, $client);
	}

	function saveTemplateAssign() {
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$db 		= & $mainframe->getDBO();
		$option 	= JRequest::getVar( 'option', '' );
		$client 	= JRequest::getVar( 'client', '' );
		$menus 		= JRequest::getVar( 'selections', array(), 'post', 'array' );
		$template 	= JRequest::getVar( 'template', '' );

		$query = 	"DELETE FROM #__templates_menu".	
					"\n WHERE client_id = 0".
					"\n AND template = '$template'".
					"\n AND menuid <> 0";
		$db->setQuery($query);
		$db->query();

		if (!in_array('', $menus)) {
			foreach ($menus as $menuid) {
				// If 'None' is not in array
				if ($menuid != -999) {
					// check if there is already a template assigned to this menu item
					$query = 	"DELETE FROM #__templates_menu".
								"\n WHERE client_id = 0".
								"\n AND menuid = $menuid";
					$db->setQuery($query);
					$db->query();

					$query = 	"INSERT INTO #__templates_menu".
								"\n SET client_id = 0, template = '$template', menuid = $menuid";
					$db->setQuery($query);
					$db->query();
				}
			}
		}

		josRedirect('index2.php?option='.$option.'&client='.$client);
	}

	/**
	*/
	function editPositions() {
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$db 		= & $mainframe->getDBO();
		$option 	= JRequest::getVar( 'option', '' );
		$client 	= JRequest::getVar( 'client', '' );

		$query = 	"SELECT *".
					"\n FROM #__template_positions";
		$db->setQuery($query);
		$positions = $db->loadObjectList();

		JTemplatesView :: editPositions($positions, $option);
	}

	/**
	*/
	function savePositions() {
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$db 			= & $mainframe->getDBO();
		$option 		= JRequest::getVar( 'option', '' );
		$positions 		= JRequest::getVar( 'position', array(), 'post', 'array' );
		$descriptions 	= JRequest::getVar( 'description', array(), 'post', 'array' );

		$query = "DELETE FROM #__template_positions";
		$db->setQuery($query);
		$db->query();

		foreach ($positions as $id => $position) {
			$position = trim($db->getEscaped($position));
			$description = $descriptions[$id];
			if ($position != '') {
				$id = intval($id);
				$query = 	"INSERT INTO #__template_positions".
							"\n VALUES ( $id, '$position', '$description' )";
				$db->setQuery($query);
				$db->query();
			}
		}
		josRedirect('index2.php?option='.$option.'&task=positions', JText :: _('Positions saved'));
	}
}
?>