<?PHP
/**
 * patTemplate modfifier Translate
 *
 * $Id$
 *
 * @package		patTemplate
 * @subpackage	Modifiers
 * @author		Andrew Eddie <eddie.andrew@gmail.com>
 */

/**
 * Implements the Joomla translation function on a var
 *
 * @package		patTemplate
 * @subpackage	Modifiers
 * @author		Andrew Eddie <eddie.andrew@gmail.com>
 */
class patTemplate_Modifier_Translate extends patTemplate_Modifier
{
   /**
	* modify the value
	*
	* @access	public
	* @param	string		value
	* @return	string		modified value
	*/
	function modify( $value, $params = array() )
	{
		if (is_object( $GLOBALS['_LANG'] )) {
			return $GLOBALS['_LANG']->_( $value );
		} else {
			return $value;
		}
	}
}
?>