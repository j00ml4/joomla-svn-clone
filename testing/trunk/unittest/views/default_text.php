<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

if (JUNITTEST_LISTMODE_HEADER) {
	echo PHP_EOL, 'Joomla! v1.5 UnitTest Platform',
		 PHP_EOL, 'Date run: ' . date('Y-m-d H:n:s');
	echo PHP_EOL, str_repeat('=', 70);
}

/* Loop through the tests and print a line for each one */
$i = 0;
foreach( $tests as $unittest )
{
	if ( !$unittest->enabled ) {
		continue;
	}

	$i++;

	echo PHP_EOL, ' ', str_pad($i, 2, ' ', STR_PAD_LEFT), ') '
		, $unittest->dirname;

	$docs = array();
	foreach ( $unittest->docs as $doc )
	{
		$docs[] = $doc;
	}
	if ( count($docs) ) {
		echo PHP_EOL, "\t", '(', implode(', ', $docs), ')';
	}

}

if (JUNITTEST_LISTMODE_FOOTER) {
	echo PHP_EOL, str_repeat('-', 70);
}

if (JUNITTEST_LISTMODE_STATS) {
	$s = count($tests);
	if ( $i != $s ) {
		echo PHP_EOL, str_pad($i,    3, ' ', STR_PAD_LEFT), ' of ', $s, ' tests available.';
		echo PHP_EOL, str_pad($s-$i, 3, ' ', STR_PAD_LEFT), ' tests disabled by configuration.';
	} else {
		echo PHP_EOL, 'All tests enabled.';
	}

	echo PHP_EOL, str_repeat('=', 70);
}

echo PHP_EOL;

/* eof. */
