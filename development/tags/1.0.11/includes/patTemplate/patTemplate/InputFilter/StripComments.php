<?PHP
/**
 * patTemplate StripComments input filter
 *
 * $Id: StripComments.php 3718 2006-05-29 06:48:03Z stingrey $
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
 * $Id: StripComments.php 3718 2006-05-29 06:48:03Z stingrey $
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
		//$data = preg_replace( '�<!--.*-->�msU', '', $data );
		//$data = preg_replace( '�/\*.*\*/�msU', '', $data );
		$data = preg_replace( '�<!--.*-->�msU', '', $data );
		$data = preg_replace( '�/\*.*\*/�msU', '', $data );

		return $data;
	}
}
?>