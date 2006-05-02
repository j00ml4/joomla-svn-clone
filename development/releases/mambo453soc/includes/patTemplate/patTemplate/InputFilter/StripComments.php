<?PHP
/**
 * patTemplate StripComments input filter
 *
 * $Id: StripComments.php,v 1.1 2005/08/25 14:21:16 johanjanssens Exp $
 *
 * Will remove all HTML comments.
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate StripComments output filter
 *
 * $Id: StripComments.php,v 1.1 2005/08/25 14:21:16 johanjanssens Exp $
 *
 * Will remove all HTML comments.
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */
class patTemplate_InputFilter_StripComments extends patTemplate_InputFilter
{
   /**
    * filter name
	*
	* @access	protected
	* @abstract
	* @var	string
	*/
	var	$_name	=	'StripComments';

   /**
	* compress the data
	*
	* @access	public
	* @param	string		data
	* @return	string		data without whitespace
	*/
	function apply( $data )
	{
        $data = preg_replace( '�<!--.*-->�msU', '', $data );
        $data = preg_replace( '�/\*.*\*/�msU', '', $data );

		return $data;
	}
}
?>