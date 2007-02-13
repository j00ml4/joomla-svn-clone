<?php
/**
 * Joomla! API Reference Plugin helper.
 *
 * @license CC-SA-NC Creative Commons Share Alike Non Commercial
 * @author  Rene Serradeil <serradeil@webmechanic.biz>
 * @version 0.1.0 $Id$
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

if (!defined('PHP_EOL')) {
	define('PHP_EOL', DOKU_LF);
}

class JApiHelper
{
/** Package name */
var $pack = null;
/** Subpackage name */
var $sub  = null;
/** Class name */
var $cls  = null;
/** Method/Constant name */
var $meth = null;
/** phpDocumentor URI */
var $doc  = null;
/** Document revision date */
var $rev  = null;
/** Document revision status */
var $stat = null;

/** input data, plain array */
var $_raw      = array();

/** reference to the current renderer object */
var $_renderer = null;

/** output format (currently ignored) */
var $_format   = 'xhtml';

/** phpDocumentor, filename candidate of the current class */
var $_filename;

/** these files DON'T carry a 'Helper' companion class */
var $_external_helpers = array('application','component','module','plugin','client','user');

/** more about the class */
var $_cls_flags = array('helper'=>false, 'abstract'=>false);

/**@#+
 * @todo take from a config file / localize
 */

/**
 * Column header labels
 */
var $_cols = array(
	'home' => 'API',
	'pack' => 'Package',
	'sub'  => 'Subpackage',
	'cls'  => 'Class',
	'clsf' => 'dummy.php', # just a note, will be set in constructor
	'meth' => 'Method',
	'doc'  => 'Reference',
	'rev'  => 'Last reviewed',
	'stat' => 'Doc status',
	);

/**
 * Default icon decoration, 'externalmedia' or 'internalmedia'
 */
var $_icons = array(
	'home' => array('externalmedia', 'package.png'),
	'pack' => array('externalmedia', 'package_folder.png'),
	'sub'  => array('externalmedia', 'package.png'),
	'cls'  => array('externalmedia', 'Class.png'),
	'acls' => array('externalmedia', 'AbstractClass.png'),
	'meth' => array('externalmedia', 'Method.png'),
	'const'=> array('externalmedia', 'Constant.png'),
	'func' => array('externalmedia', 'Function.png'),
	// these are nice, too, but not "required"
	'doc'  => array('externalmedia', 'Index.png'),
	'rev'  => array('externalmedia', 'tutorial.png'),
	);

/**
 * 'Last reviewed' labels mapping
 */
var $_rev  = array(
		'never' => 'Never'
		);

/**
 * 'Doc status' labels mapping
 */
var $_stat = array(
		'WIP' => 'Work in Progress',
		'IR'  => 'Internal Review',
		'PR'  => 'Public Review',
		);

/**
 * External icons URI with a TRAILING slash !
 */
var $_icon_uri = 'http://api.joomla.org/media/images/';

/**
 * phpDocumentor URI with a TRAILING slash !
 */
var $_doc_uri    = 'http://api.joomla.org/';
var $_doc_schema = array(
	# /classtrees_Joomla-Framework.html
	'pack' => 'classtrees_%pack%.html',
	# /Joomla-Framework/Environment/_libraries---joomla---environment---request.php.html
	'sub'  => '%pack%/%sub%/_libraries---joomla---%sub%---%filename%.php.html',
	# /Joomla-Framework/Environment/JRequest.html
	'cls'  => '%pack%/%sub%/%cls%.html',
	# /Joomla-Framework/Environment/JRequest.html#getVar
	'meth' => '%pack%/%sub%/%cls%.html#%meth%',
	/* special handling Joomla.Jegacy */
	# /Joomla-Legacy/1-5/_joomla---common---legacy---classes.php.html#classmosHTML
	'lcls' => '',
	# /Joomla-Legacy/1-5/_joomla---common---legacy---functions.php.html#functioninitEditor
	'lfnc' => '',
	);

/**
 * columns used for bogous input
 */
var $_struct       = 'home,rev,stat';

/**
 * columns used for Package documentation
 */
var $_struct_pack  = 'home,doc,rev,stat';

/**
 * columns used for Subpackage documentation
 */
var $_struct_pack  = 'home,pack,doc,rev,stat';

/**
 * columns used for Class documentation
 */
var $_struct_cls   = 'home,pack,sub,doc,rev,stat';

/**
 * columns for Class member documentation (methods, constants...)
 */
var $_struct_meth  = 'home,pack,sub,cls,doc,rev,stat';

/**@#- */

	/**
	 * PHP4-style class constructor
	 */
	function JapiHelper(&$data, &$renderer)
	{
		$this->__construct($data, $renderer);
	}

	/**
	 * PHP5 class constructor
	 * @param array  $data   Parser results
	 * @param string $format A DokuWiki output format (currently not used)
	 */
	function __construct(&$data, &$renderer)
	{
		# TODO: allow shorthand "named arguments"?
		#  ie. Pascal-esque p:Packagename c:Classname r:2006-12-31

		// renumber array indexes from 0..n
		$this->_raw = array_values($data[0]);

		// Doku_Renderer_xhtml => 'xhtml' => _decorate_xhtml()
		$this->_format = array_pop(explode('_', get_class($renderer)));

		// hope for the best the order won't change :)
		// PackageName SubpackageName ClassName::MethodName ReviewDate DocStatus
		list(
			$this->pack,
			$this->sub,
			$this->cls,
			$this->rev,
			$this->stat) = $this->_raw;

		foreach (array_keys($this->_cols) as $col) {
			$this->$col = trim($this->$col);
			if (empty($this->$col)) $this->$col = false;
		}

		// prepare doc link
		$this->doc = array();

		// "normalize" Package.Name
		if (strpos($this->pack, '.') !== false) {
			$this->pack = str_replace(' ','.', ucwords(str_replace('.', ' ', $this->pack)) );
			$this->doc['pack'] = $this->pack;
		}
		// dito Subpackage
		if ( $this->sub ) {
			$this->sub = ucwords($this->sub);
			$this->doc['sub'] = $this->sub;
		}

		// split Class::method
		if ( $this->cls ) {
			$this->cls = trim($this->cls, '()');
			// use "::globalFunction" to indicate it's not part of a class
			if ( strpos($this->cls, '::') !== false) {
				list($this->cls, $this->meth) = explode('::', $this->cls);
				// for the phpDoc URI
				$this->doc['cls']  = $this->cls;
				$this->doc['meth'] = $this->meth;
			} else {
				// just the class name, and reset meth
				list($this->cls, $this->meth) = array($this->cls, false);
				$this->doc['cls'] = $this->cls;
			}
		}

		// revision date
		if ( $this->rev ) {
			// date formatting accoring to Joomla!
			# { comment this part, if we should keep the input ISO format
			if ( class_exists('JText') && class_exists('JHTML') ) {
				// J! 1.5+
				$this->rev = JHTML::Date($this->rev, JText::_( 'DATE_FORMAT_LC' ) );
			} else {
				// J! 1.0
				$this->rev = mosFormatDate($this->rev, constant('_DATE_FORMAT_LC') );
			}
			# end J! date }

		} else {
			reset($this->_rev);
			$rev = key($this->_rev);
			# TODO: l10n
			$this->rev = $this->_rev[ $rev ];
		}

		// lookup doc status code
		$stat = (int)$this->stat;
		if ( $this->stat && isset($this->_stat[ $this->stat ]) ) {
			$this->stat = $this->_stat[ $this->stat ];
		} else {
			reset($this->_stat);
			$stat = key($this->_stat);
			# TODO: l10n
			$this->stat = $this->_stat[ $stat ];
		}

# $this->_dump($this->__toArray(), __LINE__);

		// grab the renderer
		$this->setRenderer($renderer);
	}

	/**
	 * Grabs a reference of current Wiki renderer which is used to
	 * generate internal/external (media-)links from native Wiki
	 * syntax and reusing a bunch of handy markup functions.
	 */
	function setRenderer(&$renderer) {
		$this->_renderer =& $renderer;
	}

	/**
	 * Do the magic
	 */
	function render() {
		$this->_renderer->table_open();

		$this->renderTable(true);
		$this->renderTable(false);

		$this->_renderer->table_close();

		return $this->doc . $this->html;
	}

	// ^ API ^ Package ^ Class ^ phpDocumentor ^ Last reviewed ^ Doc status ^
	function renderTable($head = true) {

		$this->_renderer->tablerow_open();

		// find the required table structure
		if ( $this->meth ) {
			$cols = explode(',', $this->_struct_meth);
		} else if ( $this->cls ) {
			$cols = explode(',', $this->_struct_cls);
		} else if ( $this->pack ) {
			$cols = explode(',', $this->_struct_pack);
		} else {
			$cols = explode(',', $this->_struct);
		}

		// loop thru the list of columns
		foreach ($cols as $col) {
			// skip empty or omitted colums
			if ( empty($this->$col) || $this->$col == '-') {
				$this->_renderer->doc .= '<!-- skipped: "'.$col.'" -->' . DOKU_LF;
				continue;
			}
			// column decorator, aka the one doing the markup ;)
			$decorator = '_decorate_'.$this->_format;
			$this->$decorator($col, $head);
			$this->_renderer->doc .= DOKU_LF;
		}

		$this->_renderer->tablerow_close();

	}

	/**@#+
	 * Decorator for the various (provided) colums
	 *
	 * @param bool $head true = <th> false = <td>
	 * @todo put internallink() "root" (references:xxx) into config file
	 */
	function _decorate_xhtml($col, $head = true) {
		list($open_func, $close_func) = ($head)
								? array('tableheader_open', 'tableheader_close')
								: array('tablecell_open',   'tablecell_close');

		# TODO: match class 'center' with CSS
		$this->_renderer->$open_func(1, 'center');

		if ($head) {
			$this->_renderer->doc .= $this->_cols[$col];
		} else {

			switch ($col) {
			case 'home':
				$this->_getIcon('home');
				$this->_renderer->internallink('[[references:joomla.framework]]', 'API');
				break;

			case 'pack':
				$this->_getIcon('pack');
				$this->_renderer->internallink('[[references:'. $this->pack .']]', $this->pack);
				break;

			case 'sub':
				$this->_getIcon('sub');
				/* this creates a link to some "Subpackage.txt" */
			#	$this->_renderer->internallink('[[references:'. $this->pack .':'. $this->sub .']]', $this->sub);

				/* if the link should point to the sub-section in the
					main index of Joomla.Framework use this statement */
				$this->_renderer->internallink('[[references:'. $this->pack .'/#'. $this->sub .']]', $this->sub);

				break;

			case 'cls':
				// guess abstract class:
				// - JFoo    = "JF"  > abstract
				// - JFooBar = "JFB" > inherited

				if ( $this->_abstract ) {
					$this->_getIcon('acls');
				} else {
					$this->_getIcon('cls');
				}
				$path = implode(':', array($this->pack, $this->sub, $this->cls) );
				$path = str_replace('::', ':', $path);
				$this->_renderer->internallink('[[references:'. $path .']]', $this->cls);

				break;

			case 'doc':
				$this->_getIcon('doc');
				list($url, $label) = $this->_phpDocUrl();
				$this->_renderer->externallink($url, $label);
				break;

			default:
				$this->_getIcon($col);
				$this->_renderer->doc .= $this->$col;
			}

		}

		# TODO: match class 'center' with CSS
		$this->_renderer->$close_func(1, 'center');
	}

	/**
	 * Nifty little icons for the table cells
	 externalmedia ($src, $title=NULL, $align=NULL,
	 				$width=NULL, $height=NULL,
	 				$cache=NULL, $linking=NULL)
	 */
	function _getIcon($col, $align='left') {
		if ( isset($this->_icons[$col]) ) {
			list($func, $src) = $this->_icons[$col];
			// $func = 'externalmedia' or 'internalmedia'
#			$this->_renderer->$func($src, @$this->_cols[$col], $align, NULL,NULL, true, 'nolink');

			$this->_renderer->doc .= '<img src="'. $this->_icon_uri . $src
									.'" alt="'. $this->_cols[$col]
									.'" title="'. $this->_cols[$col]
									.'" align="bottom" />';
			$this->_renderer->doc .= '&nbsp;';
			return true;
		}
		return false;
	}

	/**
	 * Creates the ugly URL for a phpDocumentor API page.
	 */
	function _phpDocUrl() { # WIP!

		// root
		$uri   = $this->_doc_uri;
		$label = 'Reference';

		// builds a filename candidate containing the "JClass", also attempts to
		// guess if it's an abstract class or not ... long live a good nameing scheme ;)
		if ($this->cls && (substr($this->cls, 0, 1) == 'J') ) {
			$sniff = preg_split('/([A-Z])/', substr($this->cls, 1), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
			array_shift($sniff);
			$parts = count($sniff) / 2;

			// JWhatever => whatever
			$this->_filename = strtolower($sniff[0][0] . $sniff[1][0]);

			// this could indicate an abstract class
			if ($parts >= 2) {
				$this->_cls_flags['abstract'] = true;
				$helper = $sniff[2][0] . $sniff[3][0];
				if ($helper == 'Helper') {
					$this->_cls_flags['helper'] = true;
					// there are of course exceptions to every schema ;)
					if ( in_array($basename, $this->_external_helpers) ) {
						$this->_filename .= '/helper.php';
					}
				} else {
					$this->_filename .= '.php';
				}
			}

# $this->_dump($sniff, $this->_filename .' '. $parts);

		}

		return array($uri, $label);
	}

	/**@#- */

	/**
	 * Internal Toolz
	 */
	function _dump($that, $rem='') {
		if (!empty($rem)) $rem = "$rem\n";
		echo '<xmp style="clear:both">'. $rem . print_r($that, 1) .'</xmp>';
	}

	function __toString() {
		$str = '';
		foreach (array_keys($this->_cols) as $col) {
			if (is_array($this->$col)) {
				$value = print_r($this->$col, 1);
			} else {
				$value = $this->$col;
			}
			$str .= "$col: '" . $value . "' ";
		}
		return $str;
	}
	function __toArray() {
		$arr = array();
		foreach (array_keys($this->_cols) as $col) {
			$arr[$col] = $this->$col;
		}
		return $arr;
	}

}

?>