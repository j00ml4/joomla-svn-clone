<?php
/**
 * Temporary place for jutdump until we figure out if/where it belongs
 *
 * full_description_here
 *
 * @package package_name
 * @version $Id: $
 * @author Alan Langford <addr>
 */

/**
 * Simple variable dumper.
 *
 * recommended usage: jutdump($data, 'hint '.__FILE__.__LINE__)
 *
 * For anthing but JUNIT_CLI, output in wrapped in <pre>.
 *
 * @ignore
 */
function jutdump($data, $rem='')
{
	if (!empty($rem)) {
		$rem = preg_replace('/(?:[\.\-\:]?)(\d+)$/', ' (\1)', $rem);
	} else {
		if (function_exists('debug_backtrace')) {
			$trace = array_shift(debug_backtrace());
			$rem = $trace['file'].' ('.$trace['line']. ')';
		}
	}
	echo PHP_EOL, 'DEBUG: ';
	if (JUNIT_CLI) {
		echo $rem, PHP_EOL, print_r($data, true);
	} else {
		echo htmlentities($rem), PHP_EOL,
			'<pre>', htmlentities(print_r($data, true)), '</pre>';
	}
	echo PHP_EOL;
}

?>
