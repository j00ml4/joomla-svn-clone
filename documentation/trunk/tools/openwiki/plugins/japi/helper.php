<?php
/**
 * Joomla! API Reference Plugin helper.
 *
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 * @license 	CC-SA-NC Creative Commons Share Alike Non Commercial
 * @author 		CirTap <cirtap-joomla@webmechanic.biz>
 * @version 	0.1.0 $Id$
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

if (!defined('PHP_EOL')) {
	define('PHP_EOL', DOKU_LF);
}

class JApiHelper
{
/** some JApiHelperEntry thingies */
var $row       = array();
var $revision  = null;
var $docstatus = null;

var $namespace = 'references';

/** API (Wiki) Homepage */
var $home = null;

/** Package name */
var $pack = null;
/** Subpackage name */
var $sub  = null;
/** Class name */
var $cls  = null;
/** Method of $cls */
var $meth = null;
/** Global function (substitutes cls+meth) */
var $func = null;
/** Constant        (substitutes cls+meth or func)  */
var $cons = null;
/** Document revision date */
var $rev  = null;
/** Document revision status */
var $stat = null;

/** dealing with the ancestor? */
var $legacy = false;

/** phpDocumentor URI */
var $doc  = null;

/** input data, plain array */
var $_raw      = array();

/** reference to the current renderer object */
var $_renderer = null;

/** output format (currently ignored) */
var $_format   = 'xhtml';

/** syntax format (currently ignored) */
var $_syntax   = 'JAPI';

/** phpDocumentor, filename candidate of the current class */
var $_filename;

/**@#+
 * @todo take from a config file / localize
 */

/**
 * Possible columns + header labels IN ORDER OF APPEARANCE
 * of the {#JAPI#} parameters
 */
var $_cols = array(
	'home' => 'API',
	'pack' => 'Package',
	'sub'  => 'Subpackage',
	'cls'  => 'Class',
	'meth' => 'Method',
	'func' => 'Function',
	'cons' => 'Constant',
	'doc'  => 'Reference',
	'rev'  => 'Last reviewed',
	'stat' => 'Doc status',
	);

/**
 * Default icon decoration, 'externalmedia' or 'internalmedia'
 */
var $_icons = array(
	'home' => array('externalmedia', 'package.png'),
	'pack' => array('externalmedia', 'package_folder.png'), # <-- lowercase?
	'sub'  => array('externalmedia', 'package.png'),        # <-- lowercase?
	'cls'  => array('externalmedia', 'Class.png'),
	'meth' => array('externalmedia', 'Method.png'),
	// handy supplementals and derivates
	'acls' => array('externalmedia', 'AbstractClass.png'),
	'const'=> array('externalmedia', 'Constant.png'),
	'func' => array('externalmedia', 'Function.png'),
	// these are nice, too, but not "required"
	'doc'  => array('externalmedia', 'Index.png'),
	'rev'  => array('externalmedia', 'tutorial.png'),
	);

/**
 * Package + Subpackage defaults
 * this will probably break in future versions of J! if the
 * the legacy grows AND current 1.5 remains documented!
 * by then, we hopefully have a more adequate documentation
 * system than err.. ah.. a wiki :)
 * @todo verify assignments
 */
var $_packs = array(
	# pkg-key   => array('package', 'default subpackage'),
	'joomla'    => array('Joomla', 'Libraries'),
	'framework' => array('Joomla.Framework', false),	// do we have a default for this?
	'legacy'    => array('Joomla.Legacy', '1.5'),
	'xml'       => array('XML_Parameters', false),
	'3pd'       => array('3PD-Libraries',  false),
	);

/**
 * External icons URI with a TRAILING slash !
 */
var $_icon_uri = 'http://api.joomla.org/media/images/';

/**
 * phpDocumentor URI with a TRAILING slash !
 */
var $_doc_uri  = 'http://api.joomla.org/';

/**@#- */

	/**
	 * PHP4-style class constructor
	 */
	function JApiHelper(&$data, &$renderer)
	{
		$this->__construct($data, $renderer);
	}

	/**
	 * PHP5 class constructor
	 * @param array  $data     Parser results
	 * @param string $renderer Instance of the DokuWiki renderer
	 * @param string $syntax   Syntax mode {#JAPI#} | {#REF#}, not currently used
	 */
	function __construct(&$data, &$renderer, $syntax = 'JAPI')
	{
		$renderer->acronyms['J!']  = 'Joomla!';
		$renderer->acronyms['J!F'] = 'Joomla! Framework';

		if (count($data[0]) < 1) return;

		$this->_syntax = strtoupper($syntax);

		/* Doku_Renderer_xhtml == format 'xhtml' => decorate_xhtml() */
		$this->_format = array_pop(explode('_', get_class($renderer)));

		/* renumber array indexes from 0..n */
		$this->_raw = array_values($data[0]);

		$this->_cleanup();
$this->_dump($this->_raw, 'after ');

		/* now, populate the main properties and hope for the best
		 * the argument order won't change :)
		 * @TODO allow shorthand, "named arguments"? like
		 * 			Pascal-esque p:Packagename c:Classname r:2006-12-31
		 */
		list(
			$this->pack,
			$this->sub,
			$this->cls,
			$this->rev,
			$this->stat) = $this->_raw;

		foreach (array_keys($this->_cols) as $col) {
			$this->$col = trim($this->$col);
			if (empty($this->$col) || $this->$col == '-') {
				$this->$col = false;
			}
		}

		// no package? get name of 'framework'
		if ( empty($this->pack) ) {
			$this->pack = $this->getPack('framework', 'pack');
		}
		$this->legacy = (strcasecmp($this->pack, $this->getPack('legacy', 'pack')) == 0);

		// no subpackage? get name of default subpackage for the 'pack'.
		// @see $_packs
		if ( empty($this->sub) ) {
			$this->sub = $this->getPack(
									($this->legacy ? 'legacy' : 'framework'),
									'sub');
		}

		/* grab the renderer for output */
		$this->setRenderer($renderer);

		/* place an instance */
		$self =& JApiHelper::getInstance();
		$self = $this;

		/* starting with the "right-most" column, each JApiHelperEntry will
		 * take care of it's siblings and will populate $this->row, EXCEPT
		 * revision and doc status which are taken care of later.
		 */
		$siblings = $this->__toArray();

		foreach ( array_reverse($siblings) as $col => $value) {
			if ( strpos("$this->pack", '.legacy') !== false ) {
				$classname = 'LegacyCls';
			} else {
				$classname = ucwords($col);
			}

			$this->addEntry($col, $siblings);
		}

		$this->addEntry('home', array());
		$this->addEntry('rev',  array());
		$this->addEntry('stat', array());
	}

	/**
	 * Save a reference of the current Wiki renderer to generate
	 * internal/external (media-)links from native Wiki syntax
	 * and reusing a bunch of handy markup functions.
	 */
	function setRenderer(&$renderer) {
		$this->_renderer =& $renderer;
	}

	function &getRenderer() {
		return $this->_renderer;
	}

	/* a reference of $this is assigned in __construct */
	function &getInstance() {
		static $instance;
		return $instance;
	}

	/**
	 * pushes a new column to the $row
	 */
	function &addEntry($col, $siblings=array()) {
		if ( isset($this->row[$col]) ) {
			return $this->row[$col];
		}
		$classname = 'JApiHelper'.ucwords($col);
		$this->row[$col] =& new $classname($this, $siblings);
		$this->$col = $this->row[$col]->label;
		return $this->row[$col];
	}

	function getEntry($col) {
		return @$this->row[$col];
	}
	function getEntryProp($col, $prop) {
		if ( isset($this->row[$col]) ) {
			return $this->row[$col]->$prop;
		} else {
			return '';
		}
	}

	/**
	 * Do the magic
	 */
	function render() {
		// no renderer, no output
		if (!is_object($this->_renderer)) return false;

		$this->_renderer->table_open();

		$this->renderTableRow(true);
		$this->renderTableRow(false);

		$this->_renderer->table_close();

		return true;
	}

	function renderTableRow($head = true) {
		$this->_renderer->tablerow_open();

		/* loop thru the list of columns */
		foreach ( array_keys($this->_cols) as $col) {
			if ( $entry =& $this->getEntry($col) ) {
				$decorator = 'decorate_'.$this->_format;
				$this->$decorator($entry, $head);
				$this->_renderer->doc .= DOKU_LF;
			}
		}

		$this->_renderer->tablerow_close();

	}

	/**
	 * XHTML Decorator for the various collumns
	 *
	 * @param bool $head true = <th>, false = <td>
	 */
	function decorate_xhtml(&$instance, $head = true)
	{
		if ( empty($instance->value) ) {
			return;
		}

		$html = '';
		if ($head) {
			$html .= $this->_cols[$instance->type];
		} else {
			if ($instance->icon) {
				$html .= $this->getIcon($instance->type);
			}
			$value = $instance->getFormattedValue($this);
		}

		list($open_func, $close_func) = ($head)
								? array('tableheader_open', 'tableheader_close')
								: array('tablecell_open',   'tablecell_close');

		# TODO: find name of the CSS "center" class to be use as the 2nd argument
		$this->_renderer->$open_func(1 /*, 'center'*/ );

		if ( !$head ) {
			if (is_array($value)) {
				// sth. like 'internallink', 'externallink'
				$func  = array_shift($value);
				$html .= call_user_func_array( array($this->_renderer, $func), $value);
			} else {
				$html .= $value;
			}
		}
		$this->_renderer->doc .= $html;

		$this->_renderer->$close_func(1 /*, 'center'*/ );
	}

	/**
	 * Nifty little icons for the table cells
	 externalmedia ($src, $title=NULL, $align=NULL,
	 				$width=NULL, $height=NULL,
	 				$cache=NULL, $linking=NULL)
	 */
	function getIcon($col, $align='left') {
		$image = '';
		if ( isset($this->_icons[$col]) ) {
			// $func = 'externalmedia' or 'internalmedia'
			list($func, $src) = $this->_icons[$col];
#			$this->_renderer->$func($src, @$this->_cols[$col], $align, NULL,NULL, true, 'nolink');

			$image .= '<img src="'. $this->_icon_uri . $src
						.'" alt="'. $this->_cols[$col]
						.'" title="'. $this->_cols[$col]
						.'" align="bottom" />';
			$image .= '&nbsp;';

		}
		return $image;
	}

	/**
	 * gets the default name of a package or subpackage
	 */
	function getPack($key, $level)
	{
		$item = array_search($level, array('pack','sub') );
		return $this->_packs[$key][$item];
	}


	/**
	 * Internal Toolz
	 */

	function _cleanup() {
		/* goodie: lazy rev-date column shift.
		 * expected order: pack,sub,cls,rev,stat
		 * push 'rev' to it's intended position (3) and make 'stat' (4)
		 * whatever follows and have JApiHelperStat deal with it ;) */
		reset($this->_raw);
		$val   = current($this->_raw);
		$stack = array();
		do {
			$i = key($this->_raw);
			if ( ctype_digit(str_replace('-', '', $val)) ) {
				$stack[3] = $val; 			// official 'rev' position
				$stack[4] = @$this->_raw[$i+1];	// followed by 'stat'
				break;
			}
			$stack[$i] = $val;
		} while ($val = next($this->_raw) );
		unset($val, $i);

		if ( $this->_syntax == 'JAPI' ) {
			$this->_raw = $stack + array_fill(0, 5, '-');
		} else {
			// @todo implement "inline" {#JREF class:method #} variant
			// using only pack,sub,[cls,meth|func|cons]
			$this->_raw = $stack + array_fill(0, 5, '-');
		}
		ksort($this->_raw);
	}

	function _dump($that, $rem='') {
		if (!empty($rem)) $rem = "$rem\n";
		echo '<xmp style="clear:both">'. $rem . print_r($that, 1) .'</xmp>';
	}

	/** PHP 5.2+ */
	function __toString() {
		$str = '';
		foreach (array_keys($this->_cols) as $col) {
			if (empty($this->$col)) continue;
			if (is_array($this->$col)) {
				$value = print_r($this->$col, 1);
			} else {
				$value = $this->$col;
			}
			$str .= "$col:{$value} ";
		}
		return $str;
	}
	function __toArray() {
		$arr = array();
		foreach (array_keys($this->_cols) as $col) {
			if (empty($this->$col)) continue;
			$arr[$col] = $this->$col;
		}
		return $arr;
	}

}


/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperEntry
{
/** @var string  type of entry (pack,sub,cls,...), see {@link JApiHelper::_cols}  */
var $type      = 'home';

/** @var string  output value */
var $value = 'API';

/** @var string  namespace the entry belongs to, see {@link JApiHelper::namespace} */
var $namespace = null;

/** @var string  this is what you "see"; link label, caption, title, you name it */
var $label = null;

/** @var bool  show the icon */
var $icon = false;

/** @var string  wiki link */
var $wiki = null;

/** @var bool  this entry is a legacy */
var $legacy = false;

/** @var array  siblings of this column, e.g. Pack to Sub or Meth */
var $siblings = null;

/** @var string  phpDoc URI scheme */
var $scheme = '';

/** @var string  filename used in $scheme */
var $filename = false;

	function JApiHelperEntry(&$helper, $siblings=array() ) {
		$this->__construct($helper, $siblings);
	}

	function __construct(&$helper, $siblings=array()) {
		$this->legacy   = $helper->legacy;
		if (null == $this->namespace) {
			$this->namespace = $helper->namespace;
		}
		$this->siblings = $siblings;

		$this->setValue($helper->{$this->type});

		if ( count($this->siblings) ) {
			$this->spreadSiblings();
		}
	}

	function spreadSiblings() {
		$helper =& JApiHelper::getInstance();
		foreach ( (array)$this->siblings as $sibling => $value) {
			if ( in_array($sibling, array($this->type,'doc')) ) {
				continue;
			}
			// drop current
			unset($this->siblings[$sibling]);
			// pass remaining
			$helper->addEntry( $sibling, $this->siblings );
		}
	}

	/**
	 * Interface methods to override in derived classes if necessary
	 */
	/** a value and it incarnations */
	function setValue($value) {
		$this->value =
		$this->label =
		$this->wiki  = $value;
		$this->uri   = str_replace('.', '-', $value);
	}

	/* override in subclasses */
	function getFormattedValue(&$helper) {
		return $this->value;
	}

}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperHome extends JApiHelperEntry
{
var $namespace = 'start';
var $type      = 'home';
var $icon      = true;

	function JApiHelperHome(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function getFormattedValue(&$helper) {
		return array(
				'internallink',
				sprintf('[[%s]]', $this->namespace),
				$this->label,
				null, true
			);
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperPack extends JApiHelperEntry
{
var $type   = 'pack';
var $icon   = true;

# /classtrees_Joomla-Framework.html
var $scheme = 'classtrees_%pack%.html';

	function JApiHelperPack(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function setValue($value) {
		// "normalize" Package.Name
		if (strpos($value, '.') !== false) {
			$value = str_replace(' ','.', ucwords(str_replace('.', ' ', $value)) );
		} else {
			$value = ucwords( $value );
		}
		parent::setValue($value);
	}

	function getFormattedValue(&$helper) {
		return array(
				'internallink',
				sprintf('[[%s:%s]]', $helper->namespace, $this->value),
				$this->label,
				null, true
			);
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 * @todo using what $format? check/decide whether we use:
 * 		- /package/#subpackage
 * 		- /subpackage/index.txt
 * 		- /subpackage/subpackage.txt
 */
class JApiHelperSub extends JApiHelperPack
{
var $type   = 'sub';
var $icon   = true;
var $format = array(
		/* this one req. /sub/sub.txt as the subpackage index */
		'subpackage' => '[[%ns%:%pack%:%sub%:%sub%]]',
		/* this one points to an index.txt in the subpackage folder*/
		'index'      => '[[%ns%:%pack%:%sub%:index]]',
		/* this one points to the package index using a section link */
		'section'    => '[[%ns%:%pack%#%sub%]]',
	);

# /Joomla-Framework/Environment/_libraries---joomla---environment---request.php.html
var $scheme = '%pack%/%sub%/_libraries---joomla---%sub%---%filename%.php.html';

	function JApiHelperSub(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function getFormattedValue(&$helper) {
		$pack = $helper->getEntryProp('pack', 'wiki');

		// temporary; late ask $helper what format to use
		$format = $this->format['section'];

		$link = str_replace(
					array('%ns%', '%pack%', '%sub%'),
					array($helper->namespace, $pack, $this->value),
					$format);

		$link = str_replace('::', ':', $link);
		return array( 'internallink', $link, $this->label, null, true);

	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperCls extends JApiHelperEntry
{
var $type   ='cls';
var $icon   = true;

# /Joomla-Framework/Environment/JRequest.html
var $scheme = '%pack%/%sub%/%cls%.html';
var $helper = false;

/**
 * these files(!) have a 'Helper' companion class
 * located in it's own file ... like this one ;)
 * other classes take their JWhateverHelper with them.
 * We need to know this to create the phpDocumentor URI
 * for such helpers which requires the filename.
 */
var $_external_helpers = array(
# 	sourcefile    => rel. location of "helper.php"
	'application' => 'helper.php',
	'component'   => 'helper.php',
	'module'      => 'helper.php',
	'plugin'      => 'helper.php',
	'user'        => 'helper.php',
	# JClientHelper, used by JFile and JFolder
	'client'      => '../client/helper.php',
	);


	function JApiHelperCls(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	/**
	 * Value can have any of the following values:
	 * - ClassName
	 * - ClassName::methodName
	 * - ::functionName
	 *
	 * @todo: add code to find if the class is a'*Helper' via
	 * 		isHelperClass(), and/or if it's a known abstract class,
	 * 		to create the correct URI for phpDocumentor
	 */
	function setValue($value) {
		$meth   = false;
		$constructor = false;
		$helper =& JApiHelper::getInstance();

		if ( $value ) {
			// just in case
			$value = trim($value, '()');
			if ( strpos($value, '::') !== false) {
				list($value, $meth) = explode('::', $value);
			}
			// "Classname::"
			$constructor = empty($meth);
		}

		// set with 'Class::meth' or '::function'
		if ( $meth ) {
			$sibling = 'meth';
		}

		// empty if '::functionName' was split by '::'
		if ( empty($value) ) {
			$sibling = 'func';
			$helper->meth = false;
			unset($this->siblings['meth']);
		} else {
			if ($constructor) {
				$sibling = 'meth';
				$meth    = '__construct';
			}
		}

		// tell $helper we changed our mind and
		// we have a 'meth' or 'func' declaration,
		if ( isset($sibling) ) {
			// if it's just a 'func', cls will not be rendered
			$helper->cls = $value;
			$helper->$sibling = $meth;
			// add found $sibling for spreadSiblings()
			$this->siblings[$sibling] = $meth;
			$helper->addEntry($sibling, $meth);
		}

		parent::setValue($value);
	}

	function getFormattedValue(&$helper) {
		$pack = $helper->getEntryProp('pack', 'wiki');
		$sub  = $helper->getEntryProp('sub', 'wiki');
		$link = sprintf('[[%s:%s:%s:%s]]', $helper->namespace, $pack, $sub, $this->value);
		$link = str_replace('::', ':', $link);
		return array( 'internallink', $link, $this->label, null, true);
	}

	/**
	 * @todo harden the algo if somehow possible.
	 *
	 * builds a filename candidate likely to containing the "JWhatever"
	 * and it's JWheteverHelper.
	 * long live smart nameing schemes ;)
	 */
	function isHelperClass() {
		if ( substr($this->value, 0, 1) != 'J' ) {
			return;
		}

		$sniff = preg_split('/([A-Z])/', substr($this->value, 1), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
		array_shift($sniff);
		$parts = count($sniff) / 2;

		// JWhatever => whatever
		$this->filename = strtolower($sniff[0][0] . $sniff[1][0]);

		// this could indicate an helper class, but there
		// are of course exceptions to every scheme ;)
		if ($parts >= 2) {
			$helper = $sniff[2][0] . $sniff[3][0];
			if ($helper == 'Helper') {
				$this->helper = true;
				if ( in_array($this->filename, $this->_external_helpers) ) {
					$this->filename .= '/helper.php';
				}
			} else {
				$this->filename .= '.php';
			}
		}
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperMeth extends JApiHelperEntry
{
var $type   = 'meth';
var $icon   = true;

# /Joomla-Framework/Environment/JRequest.html#getVar
var $scheme = '%pack%/%sub%/%cls%.html#%meth%';

	function JApiHelperMeth(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function setValue($value) {
		parent::setValue($value);
	}

}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperFunc extends JApiHelperMeth
{
var $type   = 'func';
var $icon   = true;

	function JApiHelperFunc(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function getFormattedValue(&$helper) {
		$pack = $helper->getEntryProp('pack', 'wiki');
		$sub  = $helper->getEntryProp('sub', 'wiki');
		$link = sprintf('[[%s:%s:%s:%s]]', $helper->namespace, $pack, $sub, $this->value);
		$link = str_replace('::', ':', $link);
		return array( 'internallink', $link, $this->label, null, true);
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperCons extends JApiHelperMeth
{
var $type   = 'cons';
var $icon   = true;

	function JApiHelperCons(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperRev extends JApiHelperEntry
{
var $type   = 'rev';
var $scheme = null;

	function JApiHelperRev(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function setValue($value) {
		// numbers only
		if ( !ctype_digit(str_replace('-', '', $value)) ) {
			$value = false;
		}

		if ( $value && $value != '-') {
			// date formatting according to Joomla!
			# { comment this part, if we should keep the input ISO format
			if ( class_exists('JHTML') && class_exists('JText') ) {
				/* J! 1.5+ */
				$value = JHTML::Date($value, JText::_( 'DATE_FORMAT_LC' ) );
			} else if ( function_exists('mosFormatDate') ) {
				/* J! 1.0 */
				$value = mosFormatDate($value, @constant('_DATE_FORMAT_LC') );
			}
			# end J! date }

		} else {
			$value = JApiHelperRev::getLabel($value);
		}
		parent::setValue($value);
	}

	/**
	 * 'Last reviewed' label mappings
	 *
	 * @param string   $value any of 'never',... -- that's it so far
	 * @static
	 * @staticvar array $labels
	 * @todo l10n, read from config file
	 */
	function getLabel($value='')
	{
		static $labels = array(
				'never' => 'Never'
				);
		$value = strtoupper("$value");
		if ( !in_array($value, array_keys($labels)) ) {
			$value = key($labels);
		}
		return $labels[ $value ];
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperStat extends JApiHelperEntry
{
var $type   = 'stat';
var $scheme = null;

	function JApiHelperStat(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function setValue($value) {
		parent::setValue( JApiHelperStat::getLabel($value) );
	}

	/**
	 * 'Doc status' label mappings
	 *
	 * @param string   $value any of 'WIP','IR','PR'
	 * @static
	 * @staticvar array $labels
	 * @todo l10n, read from config file
	 */
	function getLabel($value='')
	{
		static $labels = array(
			'WIP' => 'Work in Progress',
			'IR'  => 'Internal Review',
			'PR'  => 'Public Review',
			'FIN' => 'Final',
			);
		$value = strtoupper("$value");
		if ( !in_array($value, array_keys($labels)) ) {
			$value = key($labels);
		}
		return $labels[ $value ];
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperLegacyCls extends JApiHelperCls
{
var $type ='cls';

# /Joomla-Legacy/1-5/_joomla---common---legacy---classes.php.html#classmosHTML
var $scheme = '';
	function JApiHelperLegacyCls(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperLegacyFunc extends JApiHelperFunc
{
var $type ='func';

# /Joomla-Legacy/1-5/_joomla---common---legacy---functions.php.html#functioninitEditor
var $scheme = '';
	function JApiHelperLegacyFunc(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}
}

?>