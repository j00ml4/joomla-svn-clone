<?PHP
/**
 * Base class for patTemplate output filter
 *
 * $Id: OutputFilter.php,v 1.1 2005/08/25 14:21:16 johanjanssens Exp $
 *
 * An output filter is used to modify the output
 * after it has been processed by patTemplate, but before
 * it is sent to the browser.
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * Base class for patTemplate output filter
 *
 * $Id: OutputFilter.php,v 1.1 2005/08/25 14:21:16 johanjanssens Exp $
 *
 * An output filter is used to modify the output
 * after it has been processed by patTemplate, but before
 * it is sent to the browser.
 *
 * @abstract
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */
class patTemplate_OutputFilter extends patTemplate_Module
{
   /**
	* apply the filter
	*
	* @access	public
	* @param	string		data
	* @return	string		filtered data
	*/
	function apply( $data )
	{
		return $data;
	}
}
?>