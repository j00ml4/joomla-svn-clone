<?php
/**
* @version $Id: pageNavigation.php,v 1.1 2005/08/25 14:17:43 johanjanssens Exp $
* @package Mambo
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* Page navigation support class
* @package Mambo
*/
class mosPageNav {
	/** @var int The record number to start dislpaying from */
	var $limitstart = null;
	/** @var int Number of rows to display per page */
	var $limit = null;
	/** @var int Total number of rows */
	var $total = null;

	function mosPageNav( $total, $limitstart, $limit ) {
		$this->total = intval( $total );
		$this->limitstart = max( $limitstart, 0 );
		$this->limit = max( $limit, 1 );
		if ($this->limit > $this->total) {
			$this->limitstart = 0;
		}
		if (($this->limit-1)*$this->limitstart > $this->total) {
			$this->limitstart -= $this->limitstart % $this->limit;
		}
	}
	/**
	* @return string The html for the limit # input box
	*/
	function getLimitBox () {
		$limits = array();
		for ($i=5; $i <= 30; $i+=5) {
			$limits[] = mosHTML::makeOption( "$i" );
		}
		$limits[] = mosHTML::makeOption( "50" );

		// build the html select list
		$html = mosHTML::selectList( $limits, 'limit', 'class="inputbox" size="1" onchange="document.adminForm.submit();"',
		'value', 'text', $this->limit );
		$html .= '<input type="hidden" name="limitstart" value="'. $this->limitstart .'" />';
		return $html;
	}
	/**
	* Writes the html limit # input box
	*/
	function writeLimitBox () {
		echo mosPageNav::getLimitBox();
	}
	function writePagesCounter() {
		echo $this->getPagesCounter();
	}
	/**
	* @return string The html for the pages counter, eg, Results 1-10 of x
	*/
	function getPagesCounter() {
		global $_LANG;

		$html = '';
		$from_result = $this->limitstart+1;
		if ($this->limitstart + $this->limit < $this->total) {
			$to_result = $this->limitstart + $this->limit;
		} else {
			$to_result = $this->total;
		}
		if ($this->total > 0) {
			$html .= "\n". $_LANG->sprintf( 'Results X-Y of Z', $from_result, $to_result, $this->total );
		} else {
			$html .= "\n". $_LANG->_( 'No records found' ) .".";
		}
		return $html;
	}
	/**
	* Writes the html for the pages counter, eg, Results 1-10 of x
	*/
	function writePagesLinks() {
	    echo $this->getPagesLinks();
	}
	/**
	* @return string The html links for pages, eg, previous, next, 1 2 3 ... x
	*/
	function getPagesLinks() {
		global $_LANG;

	    $html = '';
		$displayed_pages = 10;
		$total_pages = ceil( $this->total / $this->limit );
		$this_page = ceil( ($this->limitstart+1) / $this->limit );
		$start_loop = (floor(($this_page-1)/$displayed_pages))*$displayed_pages+1;
		if ($start_loop + $displayed_pages - 1 < $total_pages) {
			$stop_loop = $start_loop + $displayed_pages - 1;
		} else {
			$stop_loop = $total_pages;
		}

		// previous and first links
		if ($this_page > 1) {
			$page = ($this_page - 2) * $this->limit;
			$html .= '<a href="#beg" class="pagenav" title="'. $_LANG->_( 'first page' ) .'" onclick="javascript: document.adminForm.limitstart.value=0; document.adminForm.submit();return false;"><< '. $_LANG->_( 'START' ) .'</a>';
			$html .= '<a href="#prev" class="pagenav" title="'. $_LANG->_( 'previous page' ) .'" onclick="javascript: document.adminForm.limitstart.value='. $page .'; document.adminForm.submit();return false;">< '. $_LANG->_( 'PREVIOUS' ) .'</a>';
		} else {
			$html .= '<span class="pagenav">&lt;&lt; '. $_LANG->_( 'Start' ) .'</span>';
			$html .= '<span class="pagenav">&lt; '. $_LANG->_( 'Previous' ) .'</span>';
		}

		for ($i=$start_loop; $i <= $stop_loop; $i++) {
			$page = ($i - 1) * $this->limit;
			if ($i == $this_page) {
				$html .= '<span class="pagenav"> '. $i .' </span>';
			} else {
				$html .= '<a href="#'. $i .'" class="pagenav" onclick="javascript: document.adminForm.limitstart.value='. $page .'; document.adminForm.submit();return false;"><strong>'. $i .'</strong></a>';
			}
		}

		// next and end links
		if ($this_page < $total_pages) {
			$page = $this_page * $this->limit;
			$end_page = ($total_pages-1) * $this->limit;
			$html .= '<a href="#next" class="pagenav" title="'. $_LANG->_( 'next page' ) .'" onclick="javascript: document.adminForm.limitstart.value='. $page .'; document.adminForm.submit();return false;"> '. $_LANG->_( 'Next' ) .' ></a>';
			$html .= '<a href="#end" class="pagenav" title="'. $_LANG->_( 'end page' ) .'" onclick="javascript: document.adminForm.limitstart.value='. $end_page .'; document.adminForm.submit();return false;"> '. $_LANG->_( 'End' ) .' >></a>';
		} else {
			$html .= '<span class="pagenav">'. $_LANG->_( 'Next' ) .' &gt;</span>';
			$html .= '<span class="pagenav">'. $_LANG->_( 'End' ) .' &gt;&gt;</span>';
		}
		return $html;
	}

	function getListFooter() {
		global $_LANG;

	    $html = '<table class="adminlist"><tr><th colspan="3" class="center">';
		$html .= $this->getPagesLinks();
		$html .= '</th></tr><tr>';
		$html .= '<td nowrap="nowrap" width="48%" align="right">'. $_LANG->_( 'Display Num' ) .'</td>';
		$html .= '<td>'. $this->getLimitBox() .'</td>';
		$html .= '<td nowrap="nowrap" width="48%" align="left">' . $this->getPagesCounter() . '</td>';
		$html .= '</tr></table>';
  		return $html;
	}
/**
* @param int The row index
* @return int
*/
	function rowNumber( $i ) {
		return $i + 1 + $this->limitstart;
	}

/**
 * @param int The row index
 * @param string The task to fire
 * @param string The alt text for the icon
 * @return string
 */
	function orderUpIcon( $i, $condition=true, $task='orderup', $alt='#' ) {
		global $_LANG;

		// handling of default value
		if ( $alt='#' ) {
			$alt = $_LANG->_( 'Move Up' );
		}

		if ( ( $i > 0 || ( $i + $this->limitstart > 0 ) ) && $condition ) {

		    $output = '<a href="#reorder" onClick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $alt .'">';
			$output .= '<img src="images/uparrow.png" width="12" height="12" border="0" alt="'. $alt .'" title="'. $alt .'" />';
			$output .= '</a>';

			return $output;
   		} else {
  		    return '&nbsp;';
		}
	}
/**
 * @param int The row index
 * @param int The number of items in the list
 * @param string The task to fire
 * @param string The alt text for the icon
 * @return string
 */
	function orderDownIcon( $i, $n, $condition=true, $task='orderdown', $alt='#' ) {
		global $_LANG;

		// handling of default value
		if ( $alt='#' ) {
			$alt = $_LANG->_( 'Move Down' );
		}

		if ( ( $i < $n - 1 || $i + $this->limitstart < $this->total - 1 ) && $condition ) {

			$output = '<a href="#reorder" onClick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $alt .'">';
			$output .= '<img src="images/downarrow.png" width="12" height="12" border="0" alt="'. $alt .'" title="'. $alt .'" />';
			$output .= '</a>';

			return $output;
  		} else {
  		    return '&nbsp;';
		}
	}

/**
 * @param int The row index
 * @param string The task to fire
 * @param string The alt text for the icon
 * @return string
 */
	function orderUpIcon2( $id, $order, $condition=true, $task='orderup', $alt='#' ) {
		global $_LANG;

		// handling of default value
		if ($alt = '#') {
			$alt = $_LANG->_( 'Move Up' );
		}

		if ($order == 0) {
			$img = 'uparrow0.png';
			$show = true;
		} else if ($order < 0) {
			$img = 'uparrow-1.png';
			$show = true;
		} else {
			$img = 'uparrow.png';
			$show = true;
		};
		if ($show) {
			$output = '<a href="#ordering" onClick="goDoTask(this, \'submit-ordering\', \'task=orderup,id=cb'.$id.'\')" title="'. $alt .'">';
			$output .= '<img src="images/' . $img . '" width="12" height="12" border="0" alt="'. $alt .'" title="'. $alt .'" /></a>';

			return $output;
   		} else {
  		    return '&nbsp;';
		}
	}
/**
 * @param int The row index
 * @param int The number of items in the list
 * @param string The task to fire
 * @param string The alt text for the icon
 * @return string
 */
	function orderDownIcon2( $id, $order, $condition=true, $task='orderdown', $alt='#' ) {
		global $_LANG;

		// handling of default value
		if ($alt = '#') {
			$alt = $_LANG->_( 'Move Down' );
		}

		if ($order == 0) {
			$img = 'downarrow0.png';
			$show = true;
		} else if ($order < 0) {
			$img = 'downarrow-1.png';
			$show = true;
		} else {
			$img = 'downarrow.png';
			$show = true;
		};
		if ($show) {
			$output = '<a href="#ordering" onClick="goDoTask(this, \'submit-ordering\', \'task=orderdown,id=cb'.$id.'\')" title="'. $alt .'">';
			$output .= '<img src="images/' . $img . '" width="12" height="12" border="0" alt="'. $alt .'" title="'. $alt .'" /></a>';

			return $output;
  		} else {
  		    return '&nbsp;';
		}
	}

	/**
	 * Sets the vars for the page navigation template
	 */
	function setTemplateVars( &$tmpl, $name = 'admin-list-footer' ) {
		$tmpl->addVar( $name, 'PAGE_LINKS', $this->getPagesLinks() );
		$tmpl->addVar( $name, 'PAGE_LIST_OPTIONS', $this->getLimitBox() );
		$tmpl->addVar( $name, 'PAGE_COUNTER', $this->getPagesCounter() );
	}
}
?>