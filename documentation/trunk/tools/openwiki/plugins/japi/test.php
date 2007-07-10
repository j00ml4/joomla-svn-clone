<?php
/**
 * Requires PHP 5!
 * Test script for helper.php. This MUST NOT be published to the live webserver
 * that runs J! and the Wiki! It contains a stub class to mimic the behaviour
 * of the Wiki XHTML parser and calls a series of {JAPI} tags for evaluation
 * and: tests!
 *
 * /components/com_openwiki/lib/plugins/japi/test.php
 *
 * If you get this error:
 *   Parse error: syntax error, unexpected T_STATIC, expecting
 *   T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}' in
 *   <local-path-to>/plugins/japi/test.php on line 26
 * you're NOT running PHP 5.
 *
 * @license CC-SA-NC Creative Commons Share Alike Non Commercial
 * @author  CirTap <cirtap-joomla@webmechanic.biz>
 * @version 0.1.0 $Id$
 */

if ((int)phpversion() < 5) die("This test script requires PHP 5 to run.");

define( '_VALID_MOS', 'anyway');
define( '_JEXEC', 'certainly');

define( 'DOKU_LF', "\r\n");
define( 'DOKU_TAB', "\t");

/* mimic J! component name */
if ( isset($_SERVER['SCRIPTBASE']) ) {
	// CirTap's local test box
	$GLOBALS['option'] = 'com_openwiki';
} else {
	// anybody else :)
	$GLOBALS['option'] = 'com_jd-wiki';
}

require_once( dirname(__FILE__) . '/helper.php');

ini_set('html_errors', 'On');
/* stub */
class TestCaseHelper_xhtml
{
static $pattern = '\{#JAPI\s+[\w\s-_:\.]+#\}';
private $JApi;

public $tags = array(

//	'{#JAPI Joomla.Framework #}',
	'{#JAPI Joomla.Framework 2007-02-01 #}',
	'{#JAPI Joomla.Framework 2007-02-01 PR #}',
//
	'{#JAPI Joomla.Framework Environment #}',
	'{#JAPI Joomla.Framework Environment 2007-02-01 Classname WIP #}',
//
	'{#JAPI Joomla.Framework Environment JSession #}',
	'{#JAPI Joomla.Framework Environment JSession 2007-02-01 FIN #}',
//
	'{#JAPI Joomla.Framework Environment JSession:: #}',
	'{#JAPI Joomla.Framework Environment JSession::close #}',
	'{#JAPI Joomla.Framework -           ::jimport #}',

//	'{#JAPI Joomla.Legacy 1.5 mosHTML #}',
//	'{#JAPI -             -   mosHTML #}',
//	'{#JAPI Joomla.Legacy 1.5 mosHTML::BackButton #}',
//	'{#JAPI -             -   mosHTML::BackButton #}',
//	'{#JAPI Joomla.Legacy 1.5 ::mosMainBody #}',
//	'{#JAPI -             -   ::mosMainBody #}',

	'{#JAPI Joomla Utilities Foo      - -	#}',
//	'{#JAPI Joomla Utilities Foo::bar - WIP #}',
//	'{#JAPI Joomla Utilities Foo::bar - PR  #}',
//	'{#JAPI Joomla Utilities Foo::bar - IR  #}',

	);

	// basically the same as in syntax.php
	public function connectTo()
	{
		array_walk($this->tags, array($this, 'handle') );
	}

	public function handle($match)
	{

		$match = trim($match, '{}#');
		$match = preg_split('/[\s]+/', trim($match), -1, PREG_SPLIT_NO_EMPTY);

echo '<hr><pre>handle(<b>', implode(' ', $match),'</b>)</pre>';

		// drop '#JAPI' tag
		array_shift($match);
		$data = array($match, 0, 0);
		self::render($data);
	}

    public function render($data)
    {
    	$this->JApi = new JApiHelper($data, $this);
    	$this->JApi->render();
    }

	// catch-all for the calls to $renderer
	public function __call($m, $a)
	{
		switch ($m) {
		case 'table_open':
			echo PHP_EOL,'<table border="1" style="width:80%;height:2em;">';
#			echo '<caption><small>'.$this->JApi->__toString().'</small></caption>';
			break;
		case 'table_close':
			echo PHP_EOL,'</table><br />',PHP_EOL;
			break;

		case 'tablerow_open':
			echo PHP_EOL,'<tr>';
			break;
		case 'tablerow_close':
			echo '</tr>';
			break;

		case 'tableheader_open':
			echo '<th>';
			break;
		case 'tableheader_close':
			echo '</th>';
			break;

		case 'tablecell_open':
			echo '<td>';
			break;
		case 'tablecell_close':
			echo '</td>';
			break;

		case 'internallink':
			$a[0] = str_replace(']]', '|%s]]', $a[0]);
			return sprintf($a[0], $a[1]);
			break;

		case 'externallink':
			return sprintf('<a href="%s" title="%s">%s</a>', $a[0], $a[0], $a[1]);
			break;
		}

		return true;
	}

	public function __get($p)
	{
		# $this->dump($p, "__get()");
		return '';
	}
	public function __set($p, $v)
	{
		echo "$v";
	}

	function dump($what, $rem='')
	{
		if (!empty($rem)) $rem = "$rem\n";
		echo '<xmp style="white-space:normal;">'. $rem;
		print_r($what);
		echo '</xmp>';
	}
}

$Test = new TestCaseHelper_xhtml;
$Test->connectTo();

