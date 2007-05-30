<programlisting>&lt;?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license    GNU/GPL
*/

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the base controller

require_once( JPATH_COMPONENT.DS.'controller.php' );

// Require specific controller if requested
if($controller = JRequest::getVar( 'controller' )) {
    require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php' );
}

// Create the controller
$classname    = 'HellosController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller-&gt;execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller-&gt;redirect();

?&gt;</programlisting>
