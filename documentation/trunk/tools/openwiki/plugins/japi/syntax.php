<?php
/**
 * Joomla! API Reference Plugin.
 *
 * Allows to simplify the creation of the API reference header for DokuWiki
 * at http://dev.joomla.org/
 * Includes formatting of links to
 * - Package index
 * - Subpackage index
 * - Class index
 * - api.joomla.org (phpDocumentor URL)
 * and information on
 * - review status (using keywords)
 * - doc status (using keywords)
 *
 * @synopsis {#JAPI PackageName SubpackageName ClassName::MethodName ReviewDate DocStatus#}
 *
 * To skip a "column" insert a hyphen (minus sign), i.e. for a class
 * that does not belong to a particular subpackage:
 *   {#JAPI PackageName - ClassName#}
 *
 * ReviewDate and DocStatus are optional. Defaults are:
 * 	- ReviewDate: never
 *  - DocStatus : WIP
 * If these columns are provided, their values must contain:
 * - ReviewDate:
 * 		an ISO date (YYYY-MM-DD), i.e. 2006-12-31
 *		Date formatting is handled by Joomla!
 * - DocStatus is any of:
 * 		WIP = Work in progress
 * 		IP  = Internal Review
 * 		PR  = Public Review
 * other values are ignored and the column will not be rendered.
 *
 * @license CC-SA-NC Creative Commons Share Alike Non Commercial
 * @author  Rene Serradeil <serradeil@webmechanic.biz>
 * @version 0.1.0 $Id$
 *
 * @todo use external config file for helper.php (see comments there)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path.'/components/com_openwiki/lib/plugins/syntax.php');

define('JAPI_PLUGIN', dirname(__FILE__));

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_japi extends DokuWiki_Syntax_Plugin {

    /**
     * General Info
     */
    function getInfo(){
        return array(
            'author' => 'Rene Serradeil',
            'email'  => 'serradeil@webmechanic.biz',
            'date'   => '2006-11-27',
            'name'   => 'Joomla! API Helper Plugin',
            'desc'   => 'Simplified formatting for the Joomla! API reference wiki.',
            'url'    => 'http://api.joomla.org/',
        );
    }

    /**
     * What kind of syntax are we?
     */
    function getType()  { return 'protected'; }
    function getPType() { return 'block'; }
    /**
     * Where to sort in?
     */
    function getSort(){ return 2707; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
    	// too bad we can't use parenthesis :(
		$this->Lexer->addSpecialPattern('\{#JAPI\s+[\w\s-_:\.]+#\}',$mode,'plugin_japi');
    }

/*
    function connectTo($mode) {
      $this->Lexer->addEntryPattern('<file(?=[^\r\n]*?\x3E.*?\x3C/file\x3E)',$mode,'plugin_code_file');
    }

    function postConnect() {
	  $this->Lexer->addExitPattern('\x3C/file\x3E', 'plugin_code_file');
    }
*/

    /**
     * Handler to prepare matched data for the rendering process
     *
     * This function can only pass data to render() via its return value - render()
     * may be not be run during the object's current life.
     *
     * Usually you should only need the $match param.
     *
     * @param   $match   string    The text matched by the patterns
     * @param   $state   int       The lexer state for the match
     * @param   $pos     int       The character position of the matched text
     * @param   $handler ref       Reference to the Doku_Handler object
     * @return  array              Return an array with all data you want to use in render()
     */
    function handle($match, $state, $pos, &$handler) {
    	$er = error_reporting(E_ALL);

		switch ($state) {
			case DOKU_LEXER_SPECIAL:
				$match = trim($match, '{}#');

				$match = preg_split('/[\s]+/', trim($match), -1, PREG_SPLIT_NO_EMPTY);

				// drop '#JAPI' tag
				array_shift($match);
				$result = array($match, $state, $pos);
			break;

			default:
				$result = false;
		}

    	error_reporting($er);
		return $result;
    }

    /**
     * Handles the actual output creation.
     *
     * The function must not assume any other of the classes methods have been run
     * during the object's current life. The only reliable data it receives are its
     * parameters.
     *
     * The function should always check for the given output format and return false
     * when a format isn't supported.
     *
     * $renderer contains a reference to the renderer object which is
     * currently handling the rendering. You need to use it for writing
     * the output. How this is done depends on the renderer used (specified
     * by $format
     *
     * The contents of the $data array depends on what the handler() function above
     * created
     *
     * @param   $format   string   output format to being Rendered
     * @param   $renderer ref      reference to the current renderer object
     * @param   $data     array    data created by handler()
     * @return  boolean            rendered correctly?
     */
    function render($format, &$renderer, $data) {
    	if ($format != 'xhtml') {
    		die('The JAPI plugin requires the "xhtml" renderer!');
    	}

		require_once(dirname(__FILE__) . '/helper.php');

    	$Japi =& new JApiHelper($data, $renderer);
    	$Japi->render();

    	return true;
    }

}

?>