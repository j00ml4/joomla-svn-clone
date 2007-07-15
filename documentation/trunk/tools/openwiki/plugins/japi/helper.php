<?php
/**
 * Joomla! API Reference Plugin helper.
 *
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 * @license 	CC-SA-NC Creative Commons Share Alike Non Commercial
 * @author 		CirTap <cirtap-joomla@webmechanic.biz>
 * @version 	0.2.2 $Id$
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

if (!defined('PHP_EOL')) {
	define('PHP_EOL', DOKU_LF);
}

/**
 * A helful class for the Joomla! API Wiki.
 */
class JApiHelper
{
/** some {@link JApiHelperEntry} thingies */
var $row       = array();
var $revision  = null;
var $docstatus = null;

/** default (Wiki) namespace (a folder name so to speak...) */
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
 * Configuration settings.
 *
 * @todo take from a config file / localize
 */

/**
 * Possible columns + header labels IN ORDER OF APPEARANCE
 * of the {#JAPI#} parameters
 */
var $_cols = array(
	'home' => 'API',			# not implemented, maybe redundant
	'pack' => 'Package',
	'sub'  => 'Subpackage',
	'cls'  => 'Class',
	'meth' => 'Method',
	'func' => 'Function',
	'cons' => 'Constant',		# not implemented
	'doc'  => 'Reference',		# automagically inserted
	'rev'  => 'Last reviewed',
	'stat' => 'Doc status',
	);

/**
 * Entries that point to the phpDoc API Reference web site
 * <b>IN REVERSE ORDER</b> of the hierarchy listed in $_cols
 * @see JApiHelperDoc::setValue()
 */
var $apicols = array('func','meth','cls','sub','pack');

/**
 * Default icon decoration, 'externalmedia' or 'internalmedia'
 * some icons are not (yet) used and may even be removed should
 * this plugin evolve.
 * @see getIcon()
 */
var $_icons = array(
	'home' => array('externalmedia', 'package.png'),
	'pack' => array('externalmedia', 'package_folder.png'), # <-- lowercase?
	'sub'  => array('externalmedia', 'package.png'),        # <-- lowercase?
	'cls'  => array('externalmedia', 'Class.png'),
	'meth' => array('externalmedia', 'Method.png'),
	// handy supplementals and derivates
	'acls' => array('externalmedia', 'AbstractClass.png'),
	'cons' => array('externalmedia', 'Constant.png'),
	'func' => array('externalmedia', 'Function.png'),
	// these are nice, too, but not "required"
	'doc'  => array('externalmedia', 'Index.png'),
	'rev'  => array('externalmedia', 'tutorial.png'),
	);

/**
 * Package + Subpackage defaults
 * this will probably break in future versions of J! if the
 * legacy grows AND current 1.5 remains documented!
 * by then, we hopefully have a more powerful documentation
 * system than <cough> DokuWiki :)
 * @todo verify assignments
 */
var $_packs = array(
	# pkg-key   => array('package',          'default subpackage'),
	'joomla'    => array('Joomla',          'Libraries'),
	'framework' => array('Joomla.Framework', false),	// do we have a default for this?
/* not implemented (implementable? due to bizarre naming schemata)
	'legacy'    => array('Joomla.Legacy',    '1.5'),
	'xml'       => array('XML_Parameters',   false),
	'3pd'       => array('3PD-Libraries',    false),
*/
	);

/**
 * External icons base URL with a TRAILING slash !
 * Location of the Wiki /media/references/ images folder.
 * Placeholders {xxx} are substituted in __constructor
 * @see getIcon()
 * global $option;
 */
var $_icon_uri = '{live_site}/components/{option}/data/media/references/';

/**
 * phpDocumentor base URI with a TRAILING slash !
 */
var $_api_uri  = 'http://api.joomla.org/';

/**@#- */

	/**
	 * PHP4-style class constructor
	 */
	function JApiHelper(&$data, &$renderer)
	{
		$this->__construct($data, $renderer, 'JAPI');
	}

	/**
	 * PHP5 class constructor
	 * @param array  $data     Parser results
	 * @param string $renderer Instance of the DokuWiki renderer
	 * @param string $syntax   Syntax mode {#JAPI#} | {#REF#}, not currently used
	 */
	function __construct(&$data, &$renderer, $syntax = 'JAPI')
	{
		$this->_icon_uri = str_replace(
							array('{live_site}', '{option}'),
							array($GLOBALS['mosConfig_live_site'], $GLOBALS['option']),
							$this->_icon_uri);

		$renderer->acronyms['J!']  = 'Joomla!';
		$renderer->acronyms['J!F'] = 'Joomla! Framework';

		if (count($data[0]) < 1) return;

		$this->_syntax = strtoupper($syntax);

		/* Doku_Renderer_xhtml == format 'xhtml' => decorate_xhtml() */
		$this->_format = array_pop(explode('_', get_class($renderer)));

		/* renumber array indexes from 0..n */
		$this->_raw = array_values($data[0]);

		$this->_cleanup();

		/* now, populate the main properties and hope for the best
		 * the argument order won't change :)
		 * @TODO allow "named arguments"? like Pascal-esque
		 * 			{#JAPI p:Packagename c:Classname r:2006-12-31 #}
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
		 * revision and doc status which are taken care of by default.
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

		/* here they are: the defaults */
		$this->addEntry('home', array());
		$this->addEntry('rev',  array());
		$this->addEntry('stat', array());

		/* and these should have a corresponding phpDoc page,
		 * we need the schema of the "lowest" item to generate
		 * its URI to the API web site. */
		foreach ( $this->apicols as $col) {
			$schema = '';
			switch (true)
			{
			case isset($this->row['func']):
				$schema = 'func';
				break;
			case isset($this->row['meth']):
				$schema = 'meth';
				break;
			case isset($this->row['cls']):
				$schema = 'cls';
				break;
			case isset($this->row['sub']):
				$schema = 'sub';
				break;
			case isset($this->row['pack']):
				$schema = 'pack';
				break;
			}
		}

		if ($schema != '') {
			$this->addEntry('doc', $this->row[$schema]);
		}
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
		// if it's not a known column, we can't create an object for it
		if ( !isset($this->_cols[$col]) ) {
			return $col;
		}
$this->_dump($siblings, "addEntry($col)");

		$classname = 'JApiHelper'.ucwords($col);

		$this->row[$col] =& new $classname($this, $siblings);
		$this->$col = $this->row[$col]->label;

		return $this->row[$col];
	}

	/**
	 * return the Entry object given by its column name.
	 * @param string $col
	 * @see $_cols
	 */
	function getEntry($col) {
		return @$this->row[$col];
	}

	/**
	 * Return a property of an Entry object or empty string
	 * @param string $col  column key
	 * @param string $prop property name
	 */
	function getEntryProp($col, $prop) {
		if ( isset($this->row[$col]) ) {
			return $this->row[$col]->$prop;
		}
		return '';
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

		if ( isset($_SERVER['SCRIPTBASE']) ) {
			$this->_renderer->doc .= implode('  * ', array_keys($this->row));
		}


		return true;
	}

	/**
	 * @param bool $head true = <th>, false = <td>
	 */
	function renderTableRow($head = true) {
		$this->_renderer->tablerow_open();

		/* loop thru the list of columns */
		foreach ( array_keys($this->_cols) as $col) {
			if ( $entry = $this->getEntry($col) ) {
				$decorator = 'decorate_'.$this->_format;
				$this->$decorator($entry, $head);
				$this->_renderer->doc .= DOKU_LF;
			}
		}

		$this->_renderer->tablerow_close();
	}

	/**
	 * XHTML Decorator for the various columns
	 *
	 * @param JApiHelperEntry $instance object representing a column entry
	 * @param bool $head true = <th>, false = <td>
	 */
	function decorate_xhtml(&$entry, $head = true)
	{
		if ( empty($entry->value) ) {
			return;
		}

		$html = '';

		if ($head) {
			$html .= $this->_cols[$entry->type];
		} else {
			if ($entry->icon) {
				$html .= $this->getIcon($entry->type);
				$html  = rtrim($html);
			}
			$value = $entry->getFormattedValue($this);
		}

		list($open_func, $close_func) = ($head)
								? array('tableheader_open', 'tableheader_close')
								: array('tablecell_open',   'tablecell_close');

		# TODO: need the name of the CSS "center" class ("???align") to be use as the 2nd argument
		$this->_renderer->$open_func(1 /*, 'center'*/ );

		if ( !$head ) {
			if (is_array($value)) {
				// sth. like 'internallink', 'externallink'
				switch ( array_shift($value) ) {

				case 'internallink':
				# Doku_Renderer_xhtml::internallink($id, $name = NULL, $search=NULL,$returnonly=false)
					$html .= $this->_renderer->internallink($value[0], $value[1], null, true);
					break;

				case 'externallink':
				# Doku_Renderer_xhtml::externallink($url, $name = NULL)
//					$html .= $this->_renderer->externallink($value['url'], $value['name']);
					$html .= sprintf('<a href="%s" title="API: %s">%s</a>',
									$value['url'], $value['url'], $value['name']);
					break;

				default:
					$html .= $value[1];
				}
			} else {
				$html .= $value;
			}
		}

		/* drop linefeeds so icon and text lign up nicely */
		$this->_renderer->doc .= str_replace(DOKU_LF, '', $html);

		$this->_renderer->$close_func(1 /*, 'center'*/ );
	}

	/**
	 * Nifty little icons for the table cells.
	 * Theory of operation:
	 * <code>
	 *	$this->_renderer->externalmedia ($src, $title=NULL, $align=NULL,
	 *						$width=NULL, $height=NULL,
	 *						$cache=NULL, $linking=NULL)
	 * </code>
	 * Practice: no go! The results suck, hence this routine currently
	 * spits out its own "native" HTML image element which aligns nicely.
	 *
	 * @todo see if we can't have all these icons in the "local" Wiki
	 * media folder, instead of loading them remote from api.joomla.org,
	 * a not-so-nice external dependency.
	 * @see $_icon_uri
	 */
	function getIcon($col, $align='left') {
		$image = '';
		if ( isset($this->_icons[$col]) ) {
			/* $func = 'externalmedia' or 'internalmedia' */
			list($func, $src) = $this->_icons[$col];
//			$this->_renderer->$func($src, @$this->_cols[$col], $align, NULL,NULL, true, 'nolink');

			$image .= '<img src="'. $this->_icon_uri . $src
						.'" alt="'. $this->_cols[$col]
						.'" title="'. $this->_cols[$col]
						.'" align="bottom" />&nbsp;';

		}
		return $image;
	}

	/**
	 * gets the default name of a package or subpackage
	 * @param string $key    A main J! Package
	 * @param string $level 'pack' or 'sub'
	 */
	function getPack($key, $level)
	{
		$item = array_search($level, array('pack','sub') );
		return $this->_packs[$key][$item];
	}

	/**
	 * Return the phpDoc base URI for the API web site
	 */
	function getApiUri()
	{
		return $this->_api_uri;
	}

	/**
	 * Internal Toolz
	 */

	/**
	 * Return file and pathname information for unresolvable entries,
	 * such as global functions, helper classes, abstract classes.
	 * @param string $type column type or '*' to return all hints
	 */
	function getHints($type='*')
	{
		static $hints = null;
		if ( null === $hints ) {
			// yep, hints.php IS a .ini file.
			$hints = parse_ini_file(dirname(__FILE__) . '/hints.php', true);
		}
		return ($type=='*') ? $hints : @$hints[$type];
	}

	/**
	 * attempts to clean up the parameter list and repopulate an array
	 * in a reasonably manner a stupid programm script can deal with.
	 */
	function _cleanup() {
		/* goodie: lazy revision date column shift.
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
			// @todo implement "inline" link syntax variant
			//    {#JREF class::method #}
			// using only pack,sub,[cls,meth|func|cons]
			$this->_raw = $stack + array_fill(0, 5, '-');
		}
		ksort($this->_raw);
	}

	/**
	 * dumps $that with $rem as a tiny remark in the deprecated but
	 * handy <xmp> HTML element.
	 */
	function _dump($that, $rem='') {
		// local machine debug flag:
		// add to httpd.conf `SetEnv SCRIPTBASE whatever`
		if ( !isset($_SERVER['SCRIPTBASE']) ) return;

		if (!empty($rem)) $rem = "$rem\n";
		echo '<xmp style="clear:both">'. $rem . print_r($that, 1) .'</xmp>';
	}

	/** PHP 5.2+ stuff */
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
 * Root class all Entry-Helper extend from.
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperEntry
{
/** @var string  type of entry (pack,sub,cls,...), see {@link JApiHelper::_cols}  */
var $type      = 'home';

/** @var string  output value */
var $value = 'API';

/** @var string  Wiki namespace the entry belongs to, see {@link JApiHelper::namespace} */
var $namespace = null;

/** @var string  this is what you "see"; link label, caption, title, you name it */
var $label = null;

/** @var bool  show the icon */
var $icon = false;

/** @var string  wiki link */
var $wiki = null;

/** @var bool  this Entry object is a legacy */
var $legacy = false;

/** @var array  siblings of this column, e.g. Pack to Sub or Meth */
var $siblings = null;

/** @var string  phpDoc URI schema, @see JApiHelperDoc */
var $schema = null;

/** @var string  phpDoc URI, populated by {@link JApiHelperDoc} */
var $uri = '';

/** @var string  filename used in $schema to generate $uri */
var $filename = false;

	/**
	 * Creates a new object representing a table column.
	 * $siblings may be added by other column objects to complement the table,
	 * i.e. the 'cls' column object may add 'meth' or 'cons' sibling as
	 * given by the JAPI params.
	 *
	 * @param JApiHelper $helper Reference of the main JApiHelper instance.
	 * @param array $siblings column name(s) related to the entry
	 */
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

	/**
	 * Will take care than columns are only added once to the JApiHelper.
	 * @uses JApiHelper::addEntry()
	 */
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

/*
 * Interface methods; override in derived classes if necessary
 */
	/**
	 * A value and its incarnations.
	 * @param string $value The incoming value for this Entry
	 */
	function setValue($value) {
		$this->value =
		$this->label =
		$this->wiki  = $value;
		$this->uri   = str_replace('.', '-', $value);
	}

	/**
	 * gets the formatted value - what else?
	 * @param JApiHelper $helper Reference of the main JApiHelper instance.
	 * @return string|array
	 * @see JApiHelper::decorate_xhtml()
	 */
	function getFormattedValue(&$helper) {
		return $this->value;
	}

}

/**
 * Creates the link to the API Referende Home Page.
 *
 * /component/option,com_jd-wiki/Itemid,/id,start/#api_references
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperHome extends JApiHelperEntry
{
var $namespace = ':start';
var $section   = '#api_references';
var $type      = 'home';
var $icon      = true;

	function JApiHelperHome(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function setValue($value) {
		parent::setValue('Home');
	}

	function getFormattedValue(&$helper) {
		return array(
				'internallink',
				sprintf('[[%s]]', $this->namespace . $this->section),
				$this->label
				);
	}
}

/**
 * Creates the link for the package index page.
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperPack extends JApiHelperEntry
{
var $type   = 'pack';
var $icon   = true;

# /classtrees_Joomla-Framework.html
var $schema = 'classtrees_%pack%.html';

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
				$this->label
			);
	}
}

/**
 * Generates the link for the Subpackage index page in the Wiki.
 * The phpDoc URI will point to the Package index in lack of such.
 *
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
		/* this one points to the package index using an anchor */
		'section'    => '[[%ns%:%pack%#%sub%]]',
	);

/** can't create a phpDoc link for something that doesn't exist */
var $schema = null;

	function JApiHelperSub(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function getFormattedValue(&$helper) {
		$pack = $helper->getEntryProp('pack', 'wiki');

		// temporary; should ask $helper what format to use based on its configuration
		$format = $this->format['section'];

		$link = str_replace(
					array('%ns%', '%pack%', '%sub%'),
					array($helper->namespace, $pack, $this->value),
					$format);

		$link = str_replace('::', ':', $link);
		return array( 'internallink', $link, $this->label);

	}
}

/**
 * Generates the link for a class.
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperCls extends JApiHelperEntry
{
var $type   ='cls';
var $icon   = true;

# /Joomla-Framework/Environment/JRequest.html
var $schema = '%pack%/%sub%/%cls%.html';
var $helper = false;

/**
 * these files(!) have a 'Helper' companion class usually located in
 * it's own file ... like this one ;)
 * other classes take their JWhateverHelper with them.
 * We need to know this to create the phpDocumentor URI for
 * such helpers which requires a filename and location.
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
	 * - ClassName::		alias for __constructor
	 * - ::functionName
	 *
	 * @todo: add code to find if the class is a'*Helper' via
	 * 		guessHelperClass(), and/or if it's a known abstract class,
	 * 		to create the correct URI for phpDocumentor
	 */
	function setValue($value) {
		$meth   = false;
		$constructor = false;
		$helper =& JApiHelper::getInstance();
		$v = $value;
		if ( $value ) {
			// just in case
			$value = trim($value, '()');
			if ( strpos($value, '::') !== false) {
				list($value, $meth) = explode('::', $value);
			}
			// "Classname::"
			$constructor = empty($meth) && is_string($meth);
		}

		// set if 'Class::method' or '::functionName'
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
		}

		parent::setValue($value);

		$this->guessHelperClass();
	}

	function getFormattedValue(&$helper) {
		$pack = $helper->getEntryProp('pack', 'wiki');
		$sub  = $helper->getEntryProp('sub', 'wiki');
		// might contain __constructor
		if ( $meth = $helper->getEntryProp('meth', 'wiki') ) {
# echo '<br> ** getFormattedValue ', $meth;
		}
		$link = sprintf('[[%s:%s:%s:%s]]', $helper->namespace, $pack, $sub, $this->value);
		$link = str_replace('::', ':', $link);
		return array( 'internallink', $link, $this->label);
	}

	/**
	 * @todo harden the algo if somehow possible
	 * @todo add fallback to hints.php
	 * @todo add icon toggle if abstract class is present
	 *
	 * builds a filename candidate likely to contain both, "JWhatever"
	 * and its "JWheteverHelper" class.
	 * long live smart nameing schemas ;)
	 */
	function guessHelperClass() {
		if ( substr($this->value, 0, 1) != 'J' ) {
			return;
		}

		$sniff = preg_split('/([A-Z])/', substr($this->value, 1), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
		array_shift($sniff);
		$parts = count($sniff) / 2;

		// blatant assumption: JWhatever => whatever
		$this->filename = strtolower($sniff[0][0] . $sniff[1][0]);

		// this could indicate a helper class, but there
		// are of course exceptions to every schema ;)
		if ($parts >= 2) {
			// 'H' + 'elper'
			$helper = $sniff[2][0] . $sniff[3][0];
			if ($helper == 'Helper') {
				$this->helper = true;
				if ( in_array($this->filename, $this->_external_helpers) ) {
					$this->filename .= '/helper.php';
				}
			} else {
				$this->filename .= '.php';
			}
		} else {
			$this->filename .= '.php';
		}

		return $this->filename;
	}
}

/**
 * Generates the link for a class' member method.
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperMeth extends JApiHelperEntry
{
var $type   = 'meth';
var $icon   = true;
var $constructor = false;

# /Joomla-Framework/Environment/JRequest.html#getVar
var $schema = '%pack%/%sub%/%cls%.html#%meth%';

	function JApiHelperMeth(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function setValue($value) {
		parent::setValue($value);
		$this->constructor = ( $value == '__construct' );
	}

	function getFormattedValue(&$helper) {
		$pack = $helper->getEntryProp('pack', 'wiki');
		$sub  = $helper->getEntryProp('sub', 'wiki');
		$cls  = $helper->getEntryProp('cls', 'wiki');
		$link = sprintf('[[%s:%s:%s:%s]]', $helper->namespace, $pack, $sub, $cls.'-'.$this->value);
		$link = str_replace('::', ':', $link);
		return array( 'internallink', $link, $this->label);
	}
}

/**
 * Generates the link for a global function.
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperFunc extends JApiHelperMeth
{
var $type   = 'func';
var $icon   = true;
# /Joomla-Framework/_loader.php.html#functionjimport
var $schema = '%pack%/_%filename%.html#function%func%';

	function JApiHelperFunc(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	function getFormattedValue(&$helper) {
		$pack = $helper->getEntryProp('pack', 'wiki');
		$sub  = $helper->getEntryProp('sub', 'wiki');
		$link = sprintf('[[%s:%s:%s:%s]]', $helper->namespace, $pack, $sub, $this->value);
		$link = str_replace('::', ':', $link);
$helper->_dump($this, 'FUNC '.$this->label);
		return array( 'internallink', $link, $this->label);
	}
}

/**
 * Generates the link for a constant.
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 * @todo implement if CONSTANTS get documented,
 * 		there's a section for constants in phpDoc class pages.
 * 		might be useful for {#JREF#} only
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
 * Generates the revision date.
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperRev extends JApiHelperEntry
{
var $type   = 'rev';
var $schema = null;

	function JApiHelperRev(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}

	/**
	 * Using a ISO date input, reformats the date using Joomla!s
	 * date function: mosFormatDate in J! 1.x, JHTML::date in J! 1.5
	 * @param string $value an ISO date YYYY-MM-DD
	 */
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
				$value = JHTML::date($value, JText::_( 'DATE_FORMAT_LC' ) );
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
	 * @param string   $value any of 'never',... -- well, that's it so far
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
 * Generates the documentation status given by its abbreviation or the default
 * if not a match.
 *
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperStat extends JApiHelperEntry
{
var $type   = 'stat';
var $schema = null;

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
		return str_replace(' ', '&nbsp;', $labels[ $value ]);
	}
}

/**
 * Generates the link for the phpDoc page
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 */
class JApiHelperDoc extends JApiHelperEntry
{
var $type   = 'doc';
var $icon   = true;

/** copy of JApiHelper::$_cols_api */
var $cols = array();

/**
 * although declared as an array for consistancy, the single item
 * is an instance of THE entry object being documented. setValue()
 * and getFormattedValue() will query this item to read its $schema,
 * $uri, and $label. */
var $siblings = array();

	function JApiHelperDoc(&$helper, &$sibling) {
		parent::__construct($helper, array(0 => &$sibling) );
	}

	function setValue($value) {
		// no URI schematics? leave.
		if ( empty($this->siblings[0]->schema) ) {
			parent::setValue( null );
			return;
		}

		// spread some defaults
		parent::setValue( $this->siblings[0]->label );

		// steal The Entry's schema
		$this->schema = $this->siblings[0]->schema;
		$this->uri    = $this->schema;

		$helper = JApiHelper::getInstance();
		$this->cols = $helper->apicols;

		foreach ($this->cols as $entry) {
			if ( $uri = $helper->getEntryProp($entry, 'uri') ) {
				$this->uri = str_replace("%{$entry}%", $uri, $this->uri);
			}
		}

		if ( in_array($this->siblings[0]->type, array('cls','func')) ) {
			if ( $filename = $helper->getEntryProp($entry, 'filename') ) {
				$this->uri = str_replace("%filename%", $filename, $this->uri);
			}
		}

		// entry type specific adjustments
		switch ($this->siblings[0]->type)
		{
			case 'func':
			case 'meth':
				$this->label .= '()';
				break;
		}

		// if there's still a %placeholder%, try the static lookup table
		if ( strpos($this->uri, '%') !== false) {
			$hints = $helper->getHints($this->siblings[0]->type);
			if ( isset($hints[$this->value]) ) {
				list($filename, $location) = explode(' ', $hints[$this->value]);
				$this->uri = str_replace("%filename%", $filename, $this->uri);
				$this->uri = str_replace("%location%", $location, $this->uri);
			}
		}
	}

	function getFormattedValue(&$helper) {
		return array( 'externallink',
					'url'=> $helper->getApiUri() . $this->uri,
					'name'=> $this->label);
	}

}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 * @deprecated
 */
class JApiHelperLegacyCls extends JApiHelperCls
{
var $type ='cls';

# /Joomla-Legacy/1-5/_joomla---common---legacy---classes.php.html#classmosHTML
var $schema = '';
	function JApiHelperLegacyCls(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}
}

/**
 * @package 	Joomla.Documentation
 * @subpackage 	Utilities
 * @deprecated
 */
class JApiHelperLegacyFunc extends JApiHelperFunc
{
var $type ='func';

# /Joomla-Legacy/1-5/_joomla---common---legacy---functions.php.html#functioninitEditor
var $schema = '';
	function JApiHelperLegacyFunc(&$helper, $siblings=array() ) {
		parent::__construct($helper, $siblings);
	}
}

