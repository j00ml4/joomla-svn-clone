// <?php !! This fools phpdocumentor into parsing this file
/**
* @version $Id: core.js,v 1.1 2005/08/25 14:17:44 johanjanssens Exp $
* @package Mambo
* @subpackage javascript
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/**
 * @param object Usually this
 * @param string
 * @param string
 */
function goDoTask()
{
	caller = arguments[0] ? arguments[0] : null;
	task   = arguments[1] ? arguments[1] : null;
	params = arguments[2] ? arguments[2].toMap() : new Map();
	
	try {
		var disp = document.taskDispatcher;
		var ctrl = disp.getControllerForTask(task);
		ctrl.doTask(task, caller, params)
	}
	catch(e) {
		throw("Command Unknow: " + task + ". Error: " + e);
	}
}