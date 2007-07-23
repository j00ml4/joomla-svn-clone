<?php
/**
 * Description
 *
 * @package 	Joomla Unittest
 * @subpackage 	package_name
 * @author 		Rene Serradeil <serradeil@webmechanic.biz>
 * @copyright 	Copyright (c)2007, media++|webmechanic.biz
 * @license 	http://creativecommons.org/licenses/by-nd/2.5/ Creative Commons
 * @version 	0.0.1 $Rev$ $Date$
 * @filesource
 */

// $Id$

header('Content-type: text/plain');

function configToIni() {
	$previous = get_defined_constants();
	@include 'TestConfiguration.php';
	$added = get_defined_constants();
	$final = array_reverse(preg_grep('/^JUT_[A-Z]+/', array_keys(array_diff_assoc($added, $previous))));
	array_walk($final, 'constToNames');
	$final = array_values(array_filter($final, create_function('$v', 'return ($v != null);')));

	print_r($final);
	echo count($final);
	return $final;
}

function constToNames(&$v, $k) {
	$parts  = explode("_", $v);
	array_shift($parts);

	$jclass = array_pop($parts);
	if (substr($jclass,0,1) == 'J') {
		$basename= trim($jclass,'J');
		$pattern = '('.implode('|', $parts) . '|HELPER|'.$basename.')';
		$nouns   = preg_split('/'. $pattern .'/i', $basename, -1, PREG_SPLIT_NO_EMPTY);
		$dirname = strtolower(array_shift($parts));
		$v = array(
				'dir'   => $dirname,
				'file'  => strtolower($basename) . '.php',
				'class' => 'J'.ucfirst($basename),
				'name'  => $v,
				'found' => $nouns,
				'const' => $parts,
				'regex' => $pattern);
	} else {
		$v = null;
	}

}

configToIni();
