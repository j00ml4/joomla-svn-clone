<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Jul 7, 2008
 * 
 * @package package_name
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Developer Name 
 * @version SVN: $Id:$
 * @see http://joomlacode.org/gf/project/   JoomlaCode Project:    
 */
 
class AlphaInstallerScript {
	
	function install($parent) {
		echo "<p>1.6 Custom install script</p>";
	}
	
	function uninstall($parent) {
		echo "<p>1.6 Custom uninstall script</p>";
	}
	
	function update($parent) {
		echo "<p>1.6 Custom update script</p>";
	}
	
	function preflight($type, $parent) {
		echo "<p>1.6 Preflight for $type</p>";
	}
	
	function postflight($type, $parent) {
		echo "<p>1.6 Postflight for $type</p>";
	}
}
