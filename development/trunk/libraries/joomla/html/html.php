<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Utility class for all HTML drawing classes
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
class JHTML 
{
	/**
	 * Write a <a></a> element
	 *
	 *  @access public
	 * @param string 	The relative URL to use for the href attribute
	 * @param string	The target attribute to use
	 * @param array		An associative array of attributes to add
	 * @param integer	Set the SSL functionality
	 * @since 1.5
	 */

	function Link($url, $text, $attribs = null, $ssl = 0) 
	{
		global $mainframe;

		$href = JURI::resolve(ampReplace($url), $ssl, $mainframe->getCfg('sef'));

		if (is_array($attribs)) {
            $attribs = JHTML::_implode_assoc('=', ' ', $attribs);
		 }
		 
		return '<a href="'.$href.'" '.$attribs.'>'.$text.'</a>';
	}

	/**
	 * Write a <img></amg> element
	 *
	 * @access public
	 * @param string 	The relative URL to use for the src attribute
	 * @param string	The target attribute to use
	 * @param array		An associative array of attributes to add
	 * @since 1.5
	 */
	function Image($url, $alt, $attribs = null) 
	{
		global $mainframe;

		$src = substr( $url, 0, 4 ) != 'http' ? $mainframe->getCfg('live_site') . $url : $url;

		 if (is_array($attribs)) {
            $attribs = JHTML::_implode_assoc('=', ' ', $attribs);
		 }

		return '<img src="'.$src.'" alt="'.$alt.'" '.$attribs.' />';

	}

	/**
	 * Write a <script></script> element
	 *
	 * @access public
	 * @param string 	The relative URL to use for the src attribute
	 * @param string	The target attribute to use
	 * @param array		An associative array of attributes to add
	 * @since 1.5
	 */
	function Script($url, $attribs = null) 
	{
		global $mainframe;

		$src = $mainframe->getCfg('live_site') . $url;

		 if (is_array($attribs)) {
            $attribs = JHTML::_implode_assoc('=', ' ', $attribs);
		 }

		return '<script type="text/javascript" src="'.$src.'" '.$attribs.'></script>';
	}

	/**
	 * Write a <iframe></iframe> element
	 *
	 * @access public
	 * @param string 	The relative URL to use for the src attribute
	 * @param string	The target attribute to use
	 * @param array		An associative array of attributes to add
	 * @param integer	Set the SSL functionality
	 * @since 1.5
	 */
	function Iframe($url, $name, $attribs = null, $ssl = 0)	
	{
		global $mainframe;

		$src = JURI::resolve(ampReplace($url), $ssl, $mainframe->getCfg('sef'));

		 if (is_array($attribs)) {
            $attribs = JHTML::_implode_assoc('=', ' ', $attribs);
		 }

		return '<iframe src="'.$src.'" '.$attribs.' />';

	}
	
	/**
	 * Returns formated date according to current local and adds time offset
	 *
	 * @access public
	 * @param string date in an US English date format
	 * @param string format optional format for strftime
	 * @returns formated date
	 * @see strftime
	 * @since 1.5
	 */
	function Date($date, $format = DATE_FORMAT_LC, $offset = NULL)
	{
		jimport('joomla.utilities.date');
		
		if(is_null($offset)) 
		{
			$config =& JFactory::getConfig();
			$offset = $config->getValue('config.offset');
		}
		
		$instance = new JDate($date);
		$instance->setOffset($offset);
		
		return $instance->toFormat($format);
	}

	function makeOption( $value, $text='', $value_name='value', $text_name='text' ) 
	{
		$obj = new stdClass;
		$obj->$value_name = $value;
		$obj->$text_name = trim( $text ) ? $text : $value;
		return $obj;
	}

	/**
	* Generates an HTML select list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @param mixed The key that is selected
	* @returns string HTML for the select list
	*/
	function selectList( &$arr, $tag_name, $tag_attribs, $key, $text, $selected=NULL, $idtag=false, $flag=false ) 
	{
		// check if array
		if ( is_array( $arr ) ) {
			reset( $arr );
		}

        $id = $tag_name;
		if ( $idtag ) {
			$id = $idtag;
		}
		$id = str_replace('[','',$id);
		$id = str_replace(']','',$id);

		$html = '<select name="'. $tag_name .'" id="'. $id .'" '. $tag_attribs .'>';
		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			if( is_array( $arr[$i] ) ) {
				$k 		= $arr[$i][$key];
				$t	 	= $arr[$i][$text];
				$id 	= ( isset( $arr[$i]['id'] ) ? $arr[$i]['id'] : null );
			} else {
				$k 		= $arr[$i]->$key;
				$t	 	= $arr[$i]->$text;
				$id 	= ( isset( $arr[$i]->id ) ? $arr[$i]->id : null );
			}
            //if no string after hypen - take hypen out
            $splitText = explode( " - ", $t, 2 );
            $t = $splitText[0];
            if(isset($splitText[1])){ $t .= " - ". $splitText[1]; }

			$extra = '';
			//$extra .= $id ? ' id="' . $arr[$i]->id . '"' : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj->$key;
					if ($k == $k2) {
						$extra .= ' selected="selected"';
						break;
					}
				}
			} else {
				$extra .= ( $k == $selected ? ' selected="selected"' : '' );
			}
			//if flag translate text
			if($flag) $t = JText::_( $t );

			$html .= '<option value="'. $k .'" '. $extra .'>' . $t . '</option>';
		}
		$html .= '</select>';

		return $html;
	}

	/**
	* Writes a select list of integers
	* @param int The start integer
	* @param int The end integer
	* @param int The increment
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The printf format to be applied to the number
	* @returns string HTML for the select list
	*/
	function integerSelectList( $start, $end, $inc, $tag_name, $tag_attribs, $selected, $format="" ) 
	{
		$start 	= intval( $start );
		$end 	= intval( $end );
		$inc 	= intval( $inc );
		$arr 	= array();

		for ($i=$start; $i <= $end; $i+=$inc) {
			$fi = $format ? sprintf( "$format", $i ) : "$i";
			$arr[] = JHTML::makeOption( $fi, $fi );
		}

		return JHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Generates an HTML radio list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @returns string HTML for the select list
	*/
	function radioList( &$arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text', $idtag=false ) 
	{
		reset( $arr );
		$html = '';

		$id_text = $tag_name;
		if ( $idtag ) {
			$id_text = $idtag;
		}

		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = ( isset($arr[$i]->id) ? @$arr[$i]->id : null);

			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj->$key;
					if ($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " checked=\"checked\"" : '');
			}
			$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" id=\"$id_text$k\" value=\"".$k."\"$extra $tag_attribs />";
			$html .= "\n\t<label for=\"$id_text$k\">$t</label>";
		}
		$html .= "\n";
		return $html;
	}

	/**
	* Writes a yes/no radio list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	function yesnoRadioList( $tag_name, $tag_attribs, $selected, $yes='yes', $no='no', $id=false ) {

		$arr = array(
			JHTML::makeOption( '0', JText::_( $no ) ),
			JHTML::makeOption( '1', JText::_( $yes ) )
		);
		return JHTML::radioList( $arr, $tag_name, $tag_attribs, (int) $selected, 'value', 'text', $id );
	}

	/**
	* @param int The row index
	* @param int The record id
	* @param boolean
	* @param string The name of the form element
	* @return string
	*/
	function idBox( $rowNum, $recId, $checkedOut=false, $name='cid' ) {
		if ( $checkedOut ) {
			return '';
		} else {
			return '<input type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />';
		}
	}

	/**
	* simple Javascript Cloaking
	* email cloacking
 	* by default replaces an email with a mailto link with email cloacked
	*/
	function emailCloaking( $mail, $mailto=1, $text='', $email=1 ) {
		// convert text
		$mail 			= JHTML::_encoding_converter( $mail );
		// split email by @ symbol
		$mail			= explode( '@', $mail );
		$mail_parts		= explode( '.', $mail[1] );
		// random number
		$rand			= rand( 1, 100000 );

		$replacement 	= "\n <script language='JavaScript' type='text/javascript'>";
		$replacement 	.= "\n <!--";
		$replacement 	.= "\n var prefix = '&#109;a' + 'i&#108;' + '&#116;o';";
		$replacement 	.= "\n var path = 'hr' + 'ef' + '=';";
		$replacement 	.= "\n var addy". $rand ." = '". @$mail[0] ."' + '&#64;';";
		$replacement 	.= "\n addy". $rand ." = addy". $rand ." + '". implode( "' + '&#46;' + '", $mail_parts ) ."';";

		if ( $mailto ) {
			// special handling when mail text is different from mail addy
			if ( $text ) {
				if ( $email ) {
					// convert text
					$text 			= JHTML::_encoding_converter( $text );
					// split email by @ symbol
					$text 			= explode( '@', $text );
					$text_parts		= explode( '.', $text[1] );
					$replacement 	.= "\n var addy_text". $rand ." = '". @$text[0] ."' + '&#64;' + '". implode( "' + '&#46;' + '", @$text_parts ) ."';";
				} else {
					$replacement 	.= "\n var addy_text". $rand ." = '". $text ."';";
				}
				$replacement 	.= "\n document.write( '<a ' + path + '\'' + prefix + ':' + addy". $rand ." + '\'>' );";
				$replacement 	.= "\n document.write( addy_text". $rand ." );";
				$replacement 	.= "\n document.write( '<\/a>' );";
			} else {
				$replacement 	.= "\n document.write( '<a ' + path + '\'' + prefix + ':' + addy". $rand ." + '\'>' );";
				$replacement 	.= "\n document.write( addy". $rand ." );";
				$replacement 	.= "\n document.write( '<\/a>' );";
			}
		} else {
			$replacement 	.= "\n document.write( addy". $rand ." );";
		}
		$replacement 	.= "\n //-->";
		$replacement 	.= '\n </script>';

		// XHTML compliance `No Javascript` text handling
		$replacement 	.= "<script language='JavaScript' type='text/javascript'>";
		$replacement 	.= "\n <!--";
		$replacement 	.= "\n document.write( '<span style=\'display: none;\'>' );";
		$replacement 	.= "\n //-->";
		$replacement 	.= "\n </script>";
		$replacement 	.= JText::_('CLOAKING');
		$replacement 	.= "\n <script language='JavaScript' type='text/javascript'>";
		$replacement 	.= "\n <!--";
		$replacement 	.= "\n document.write( '</' );";
		$replacement 	.= "\n document.write( 'span>' );";
		$replacement 	.= "\n //-->";
		$replacement 	.= "\n </script>";

		return $replacement;
	}

	function keepAlive()
	{
		$js = "
				function keepAlive() {
					setTimeout('frames[\'keepAliveFrame\'].location.href=\'index.php?option=com_admin&tmpl=component&task=keepalive\';', 60000);
				}";

		$html = "<iframe id=\"keepAliveFrame\" name=\"keepAliveFrame\" " .
				"style=\"width:0px; height:0px; border: 0px\" " .
				"src=\"index.php?option=com_admin&tmpl=component&task=keepalive\" " .
				"onload=\"keepAlive();\"></iframe>";

		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration($js);
		echo $html;
	}

	function _encoding_converter( $text ) {
		// replace vowels with character encoding
		$text 	= str_replace( 'a', '&#97;', $text );
		$text 	= str_replace( 'e', '&#101;', $text );
		$text 	= str_replace( 'i', '&#105;', $text );
		$text 	= str_replace( 'o', '&#111;', $text );
		$text	= str_replace( 'u', '&#117;', $text );

		return $text;
	}

	function _implode_assoc($inner_glue = "=", $outer_glue = "\n", $array = null, $keepOuterKey = false)
    {
        $output = array();
		
        foreach($array as $key => $item)
        if (is_array ($item)) {
            if ($keepOuterKey)
                $output[] = $key;
            // This is value is an array, go and do it again!
            $output[] = JHTML::_implode_assoc($inner_glue, $outer_glue, $item, $keepOuterKey);
        } else
            $output[] = $key . $inner_glue . $item;
				
        return implode($outer_glue, $output);
    }
}

/**
 * Utility class for drawing common HTML elements
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
class JCommonHTML {

	function ContentLegend( ) 
	{
		?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr align="center">
			<td>
			<img src="images/publish_y.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Pending' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'Published, but is' ); ?> <u><?php echo JText::_( 'Pending' ); ?></u> |
			</td>
			<td>
			<img src="images/publish_g.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Visible' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'Published and is' ); ?> <u><?php echo JText::_( 'Current' ); ?></u> |
			</td>
			<td>
			<img src="images/publish_r.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Finished' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'Published, but has' ); ?> <u><?php echo JText::_( 'Expired' ); ?></u> |
			</td>
			<td>
			<img src="images/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Finished' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'Not Published' ); ?>
			</td>
		</tr>
		<tr>
			<td colspan="8" align="center">
			<?php echo JText::_( 'Click on icon to toggle state.' ); ?>
			</td>
		</tr>
		</table>
		<?php
	}

	function checkedOut( &$row, $overlib=1 ) 
	{
		$hover = '';
		if ( $overlib ) {

			$text = addslashes(htmlspecialchars($row->editor));

			$date 				= JHTML::Date( $row->checked_out_time, '%A, %d %B %Y' );
			$time				= JHTML::Date( $row->checked_out_time, '%H:%M' );
			$checked_out_text 	= '<table>';
			$checked_out_text 	.= '<tr><td>'. $text .'</td></tr>';
			$checked_out_text 	.= '<tr><td>'. $date .'</td></tr>';
			$checked_out_text 	.= '<tr><td>'. $time .'</td></tr>';
			$checked_out_text 	.= '</table>';

			$hover = 'onMouseOver="return overlib(\''. $checked_out_text .'\', CAPTION, \''. JText::_( 'Checked Out' ) .'\', BELOW, RIGHT);" onMouseOut="return nd();"';
		}
		$checked	 		= '<img src="images/checked_out.png" '. $hover .'/>';

		return $checked;
	}

	/*
	* Loads all necessary files for JS Overlib tooltips
	*/
	function loadOverlib()
	{
		global $mainframe;

		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();

		if ( !$mainframe->get( 'loadOverlib' ) ) {
		// check if this function is already loaded
			$doc =& JFactory::getDocument();
			$doc->addScript($url.'includes/js/overlib_mini.js');
			$doc->addScript($url.'includes/js/overlib_hideform_mini.js');
			?>
			<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
			<?php
			// change state so it isnt loaded a second time
			$mainframe->set( 'loadOverlib', true );
		}
	}

	/*
	* Loads all necessary files for JS Calendar
	*/
	function loadCalendar()
	{
		global $mainframe;

		$doc =& JFactory::getDocument();
		$lang =& JFactory::getLanguage();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();

		$doc->addStyleSheet( $url. 'includes/js/calendar/calendar-mos.css', 'text/css', null, array(' title' => JText::_( 'green' ) ,' media' => 'all' ));
		$doc->addScript( $url. 'includes/js/calendar/calendar_mini.js' );
		$langScript = JPATH_SITE.DS.'includes'.DS.'js'.DS.'calendar'.DS.'lang'.DS.'calendar-'.$lang->getTag().'.js';
		if( file_exists( $langScript ) ){
			$doc->addScript( $url. 'includes/js/calendar/lang/calendar-'.$lang->getTag().'.js' );
		} else {
			$doc->addScript( $url. 'includes/js/calendar/lang/calendar-en-GB.js' );
		}
	}

	function AccessProcessing( &$row, $i, $archived=NULL ) 
	{
		if ( !$row->access ) {
			$color_access = 'style="color: green;"';
			$task_access = 'accessregistered';
		} else if ( $row->access == 1 ) {
			$color_access = 'style="color: red;"';
			$task_access = 'accessspecial';
		} else {
			$color_access = 'style="color: black;"';
			$task_access = 'accesspublic';
		}

		if ($archived == -1) {
			$href = JText::_( $row->groupname );
		} else {
			$href = '
			<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task_access .'\')" '. $color_access .'>
			'. JText::_( $row->groupname ) .'
			</a>'
			;
		}

		return $href;
	}

	function CheckedOutProcessing( &$row, $i )
	{
		$user =& JFactory::getUser();
		if ( $row->checked_out ) {
			$checked = JCommonHTML::checkedOut( $row );
		} else {
			$checked = JHTML::idBox( $i, $row->id, ($row->checked_out && $row->checked_out != $user->get('id') ) );
		}

		return $checked;
	}

	function PublishedProcessing( &$row, $i, $imgY='tick.png', $imgX='publish_x.png' ) {

		$img 	= $row->published ? $imgY : $imgX;
		$task 	= $row->published ? 'unpublish' : 'publish';
		$alt 	= $row->published ? JText::_( 'Published' ) : JText::_( 'Unpublished' );
		$action 	= $row->published ? JText::_( 'Unpublish Item' ) : JText::_( 'Publish item' );

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" />
		</a>'
		;

		return $href;
	}

	function selectState( $filter_state='*', $published='Published', $unpublished='Unpublished', $archived=NULL )	
	{
		$state[] = JHTML::makeOption( '', '- '. JText::_( 'Select State' ) .' -' );
		$state[] = JHTML::makeOption( '*', JText::_( 'Any' ) );
		$state[] = JHTML::makeOption( 'P', JText::_( $published ) );
		$state[] = JHTML::makeOption( 'U', JText::_( $unpublished ) );

		if ($archived) {
			$state[] = JHTML::makeOption( 'A', JText::_( $archived ) );
		}

		return JHTML::selectList( $state, 'filter_state', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_state );
	}

	function saveorderButton( $rows, $image='filesave.png' ) {
		$image = JAdminMenus::ImageCheckAdmin( $image, '/images/', NULL, NULL, JText::_( 'Save Order' ), '', 1 );
		?>
		<a href="javascript:saveorder(<?php echo count( $rows )-1; ?>)" title="<?php echo JText::_( 'Save Order' ); ?>">
			<?php echo $image; ?></a>
		<?php
	}

	function tableOrdering( $text, $ordering, &$lists, $task=NULL ) 
	{
		?>
		<a href="javascript:tableOrdering('<?php echo $ordering; ?>','<?php echo $lists['order_Dir']; ?>','<?php echo $task; ?>');" title="<?php echo JText::_( 'Order by' ); ?> <?php echo JText::_( $text ); ?>">
			<?php echo JText::_( $text ); ?>
			<?php JCommonHTML::tableOrdering_img( $ordering, $lists ); ?></a>
		<?php
	}

	function tableOrdering_img( $current, &$lists ) 
	{
		if ( $current == $lists['order']) {
			if ( $lists['order_Dir'] == 'ASC' ) {
				$image = 'sort_desc.png';
			} else {
				$image = 'sort_asc.png';
			}
			echo JAdminMenus::ImageCheckAdmin( $image, '/images/', NULL, NULL, '', '', 1 );
		}
	}
}

/**
 * Utility class for drawing admin menu HTML elements
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.0
 */
class JAdminMenus
{
	/**
	* build the select list for Menu Ordering
	*/
	function Ordering( &$row, $id ) 
	{
		$db =& JFactory::getDBO();

		if ( $id ) {
			$query = "SELECT ordering AS value, name AS text"
			. "\n FROM #__menu"
			. "\n WHERE menutype = '$row->menutype'"
			. "\n AND parent = $row->parent"
			. "\n AND published != -2"
			. "\n ORDER BY ordering"
			;
			$order = mosGetOrderingList( $query );
			$ordering = JHTML::selectList( $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval( $row->ordering ) );
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. JText::_( 'DESCNEWITEMSLAST' );
		}
		return $ordering;
	}

	/**
	* build the select list for access level
	*/
	function Access( &$row ) 
	{
		$db =& JFactory::getDBO();

		$query = "SELECT id AS value, name AS text"
		. "\n FROM #__groups"
		. "\n ORDER BY id"
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();
		$access = JHTML::selectList( $groups, 'access', 'class="inputbox" size="3"', 'value', 'text', intval( $row->access ), '', 1 );

		return $access;
	}

	/**
	* build the multiple select list for Menu Links/Pages
	*/
	function MenuLinks( &$lookup, $all=NULL, $none=NULL, $unassigned=1 ) 
	{
		$db =& JFactory::getDBO();

		// get a list of the menu items
		$query = "SELECT m.*"
		. "\n FROM #__menu AS m"
		. "\n WHERE m.published = 1"
		. "\n ORDER BY m.menutype, m.parent, m.ordering"
		;
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$mitems_temp = $mitems;

		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach ( $mitems as $v ) {
			$id = $v->id;
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v );
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = mosTreeRecurse( intval( $mitems[0]->parent ), '', array(), $children, 9999, 0, 0 );

		// Code that adds menu name to Display of Page(s)
		$text_count 	= 0;
		$mitems_spacer 	= $mitems_temp[0]->menutype;
		foreach ($list as $list_a) {
			foreach ($mitems_temp as $mitems_a) {
				if ($mitems_a->id == $list_a->id) {
					// Code that inserts the blank line that seperates different menus
					if ($mitems_a->menutype <> $mitems_spacer) {
						$list_temp[] 	= JHTML::makeOption( -999, '----' );
						$mitems_spacer 	= $mitems_a->menutype;
					}
					$text = $mitems_a->menutype." | ".$list_a->treename;
					$list_temp[] = JHTML::makeOption( $list_a->id, $text );
					if ( JString::strlen($text) > $text_count) {
						$text_count = JString::strlen($text);
					}
				}
			}
		}
		$list = $list_temp;

		$mitems = array();
		if ( $all ) {
			// prepare an array with 'all' as the first item
			$mitems[] = JHTML::makeOption( 0, JText::_( 'All' ) );
			// adds space, in select box which is not saved
			$mitems[] = JHTML::makeOption( -999, '----' );
		}
		if ( $none ) {
			// prepare an array with 'all' as the first item
			$mitems[] = JHTML::makeOption( -999, JText::_( 'None' ) );
			// adds space, in select box which is not saved
			$mitems[] = JHTML::makeOption( -999, '----' );
		}
		if ( $none ) {
			// prepare an array with 'all' as the first item
			$mitems[] = JHTML::makeOption( 99999999, JText::_( 'Unassigned' ) );
			// adds space, in select box which is not saved
			$mitems[] = JHTML::makeOption( -999, '----' );
		}
		// append the rest of the menu items to the array
		foreach ($list as $item) {
			$mitems[] = JHTML::makeOption( $item->value, $item->text );
		}
		$pages = JHTML::selectList( $mitems, 'selections[]', 'class="inputbox" size="26" multiple="multiple"', 'value', 'text', $lookup, 'selections' );
		return $pages;
	}

	/**
	* build the select list to choose an image
	*/
	function Images( $name, &$active, $javascript=NULL, $directory=NULL ) 
	{
		if ( !$directory ) {
			$directory = '/images/stories/';
		}

		if ( !$javascript ) {
			$javascript = "onchange=\"javascript:if (document.forms[0]." . $name . ".options[selectedIndex].value!='') {document.imagelib.src='..$directory' + document.forms[0]." . $name . ".options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
		}

		jimport( 'joomla.filesystem.folder' );
		$imageFiles = JFolder::files( JPATH_SITE . $directory );
		$images 	= array(  JHTML::makeOption( '', '- '. JText::_( 'Select Image' ) .' -' ) );
		foreach ( $imageFiles as $file ) {
			if ( eregi( "bmp|gif|jpg|png", $file ) ) {
				$images[] = JHTML::makeOption( $file );
			}
		}
		$images = JHTML::selectList( $images, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $images;
	}

	/**
	* build the select list for Ordering of a specified Table
	*/
	function SpecificOrdering( &$row, $id, $query, $neworder=0 ) 
	{
		$db =& JFactory::getDBO();

		if ( $id ) {
			$order = mosGetOrderingList( $query );
			$ordering = JHTML::selectList( $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval( $row->ordering ) );
		} else {
    		if ( $neworder ) {
    			$text = JText::_( 'descNewItemsFirst' );
    		} else {
    			$text = JText::_( 'descNewItemsLast' );
    		}
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. $text;
		}
		return $ordering;
	}

	/**
	* Select list of active users
	*/
	function UserSelect( $name, $active, $nouser=0, $javascript=NULL, $order='name', $reg=1 ) 
	{
		$db =& JFactory::getDBO();

		$and = '';
		if ( $reg ) {
		// does not include registered users in the list
			$and = "\n AND gid > 18";
		}

		$query = "SELECT id AS value, name AS text"
		. "\n FROM #__users"
		. "\n WHERE block = 0"
		. $and
		. "\n ORDER BY $order"
		;
		$db->setQuery( $query );
		if ( $nouser ) {
			$users[] = JHTML::makeOption( '0', '- '. JText::_( 'No User' ) .' -' );
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}

		$users = JHTML::selectList( $users, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $users;
	}

	/**
	* Select list of positions - generally used for location of images
	*/
	function Positions( $name, $active=NULL, $javascript=NULL, $none=1, $center=1, $left=1, $right=1, $id=false ) {

		if ( $none ) {
			$pos[] = JHTML::makeOption( '', JText::_( 'None' ) );
		}
		if ( $center ) {
			$pos[] = JHTML::makeOption( 'center', JText::_( 'Center' ) );
		}
		if ( $left ) {
			$pos[] = JHTML::makeOption( 'left', JText::_( 'Left' ) );
		}
		if ( $right ) {
			$pos[] = JHTML::makeOption( 'right', JText::_( 'Right' ) );
		}

		$positions = JHTML::selectList( $pos, $name, 'class="inputbox" size="1"'. $javascript, 'value', 'text', $active, $id );

		return $positions;
	}

	/**
	* Select list of active categories for components
	*/
	function ComponentCategory( $name, $section, $active=NULL, $javascript=NULL, $order='ordering', $size=1, $sel_cat=1 ) 
	{
		global $mainframe;

		$db =& JFactory::getDBO();

		$query = "SELECT id AS value, name AS text"
		. "\n FROM #__categories"
		. "\n WHERE section = '$section'"
		. "\n AND published = 1"
		. "\n ORDER BY $order"
		;
		$db->setQuery( $query );
		if ( $sel_cat ) {
			$categories[] = JHTML::makeOption( '0', '- '. JText::_( 'Select a Category' ) .' -' );
			$categories = array_merge( $categories, $db->loadObjectList() );
		} else {
			$categories = $db->loadObjectList();
		}

		if ( count( $categories ) < 1 ) {
			$mainframe->redirect( 'index2.php?option=com_categories&section='. $section, JText::_( 'You must create a category first.' ) );
		}

		$category = JHTML::selectList( $categories, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $active );

		return $category;
	}

	/**
	* Select list of active sections
	*/
	function SelectSection( $name, $active=NULL, $javascript=NULL, $order='ordering' ) 
	{
		$db =& JFactory::getDBO();

		$categories[] = JHTML::makeOption( '-1', '- '. JText::_( 'Select Section' ) .' -' );
		$categories[] = JHTML::makeOption( '0', JText::_( 'Uncategorized' ) );
		$query = "SELECT id AS value, title AS text"
		. "\n FROM #__sections"
		. "\n WHERE published = 1"
		. "\n ORDER BY $order"
		;
		$db->setQuery( $query );
		$sections = array_merge( $categories, $db->loadObjectList() );

		$category = JHTML::selectList( $sections, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $category;
	}

	/**
	* Checks to see if an image exists in the current templates image directory
 	* if it does it loads this image.  Otherwise the default image is loaded.
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*/
	function ImageCheck( $file, $directory='/images/M_images/', $param=NULL, $param_directory='/images/M_images/', $alt=NULL, $name='image', $type=1, $align='top' ) 
	{
		static $paths;
		global $mainframe;

		if (!$paths)
		{
			$paths = array();
		}

		$cur_template = $mainframe->getTemplate();

		// strip html
		$alt	= html_entity_decode( $alt );

		if ( $param ) {
			$image = $param_directory . $param;
			if ( $type ) {
				$image = '<img src="'. $image .'" align="'. $align .'" alt="'. $alt .'" border="0" />';
			}
		} else if ( $param == -1 ) {
			$image = '';
		} else {
			$path = JPATH_SITE .'/templates/'. $cur_template .'/images/'. $file;
			if (!isset( $paths[$path] ))
			{
				if ( file_exists( JPATH_SITE .'/templates/'. $cur_template .'/images/'. $file ) ) {
					$paths[$path] = 'templates/'. $cur_template .'/images/'. $file;
				} else {
					// outputs only path to image
					$paths[$path] = $directory . $file;
				}
			}
			$image = $paths[$path];
		}

		if (substr($image, 0, 1 ) == "/") {
			$image = substr_replace($image, '', 0, 1);
		}

		// outputs actual html <img> tag
		if ( $type ) {
			$image = '<img src="'. $image .'" alt="'. $alt .'" align="'. $align .'" border="0" />';
		}

		return $image;
	}

	/**
	* Checks to see if an image exists in the current templates image directory
 	* if it does it loads this image.  Otherwise the default image is loaded.
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*/
	function ImageCheckAdmin( $file, $directory='/images/', $param=NULL, $param_directory='/images/', $alt=NULL, $name=NULL, $type=1, $align='middle' )	
	{
		global $mainframe;

		$cur_template = $mainframe->getTemplate();

		// strip html
		$alt	= html_entity_decode( $alt );

		if ( $param ) {
			$image = $param_directory . $param;
		} else if ( $param == -1 ) {
			$image = '';
		} else {
			if ( file_exists( JPATH_ADMINISTRATOR .'/templates/'. $cur_template .'/images/'. $file ) ) {
				$image = 'templates/'. $cur_template .'/images/'. $file;
			} else {
				// compability with previous versions
				if ( substr($directory, 0, 14 )== "/administrator" ) {
					$image = substr($directory,15) . $file;
				} else {
					$image = $directory . $file;
				}
			}
		}

		if (substr($image, 0, 1 ) == "/") {
			$image = substr_replace($image, '', 0, 1);
		}

		// outputs actual html <img> tag
		if ( $type ) {
			$image = '<img src="'. $image .'" alt="'. $alt .'" align="'. $align .'" border="0" />';
		}

		return $image;
	}
}
?>