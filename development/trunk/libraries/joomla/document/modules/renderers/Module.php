<?PHP
/**
* @version $Id: module.php 1593 2005-12-31 03:10:07Z Jinx $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * JDocument Module renderer
 *
 * @author 		Johan Janssens <johan@joomla.be>
 * @package 	Joomla.Framework
 * @subpackage 	Document
 * @since 1.1
 */

class patTemplate_Renderer_Module extends patTemplate_Renderer
{
   /**
	* name of the renderer
	* @access	private
	* @var		string
	*/
	var $_name	=	'Module';
	
   /**
	 * Renders a module script and returns the results as a string
	 *
	 * @access public
	 * @param string 	$name		The name of the module to render
	 * @param array 	$params		Associative array of values
	 * @return string	The output of the script
	 */
	function render( $module, $params = array() )
	{
		global $mosConfig_live_site, $mosConfig_sitename, $mosConfig_lang, $mosConfig_absolute_path;
		global $mainframe, $database, $my, $Itemid, $acl, $task, $option;

		if(!is_object($module)) {
			$module = JModuleHelper::getModule($module);
		}
		
		$style  = isset($params['style']) ? $params['style'] : $module->style;
		
		//get module parameters
		$params = new JParameters( $module->params );

		//get module path
		$path = JPATH_BASE . '/modules/'.$module->module.'.php';

		//load the module
		if (!$module->user && file_exists( $path ))
		{
			$lang =& $mainframe->getLanguage();
			$lang->load($module->module);

			ob_start();
			require $path;
			$module->content = ob_get_contents();
			ob_end_clean();
		}
		
		$contents = '';
		ob_start();
			if ($params->get('cache') == 1 && $mainframe->getCfg('caching') == 1) {
				$cache =& JFactory::getCache( 'com_content' );
				$cache->call('modules_html::module', $module, $params, $style );
			} else {
				modules_html::module( $module, $params, $style);
			}
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}
?>