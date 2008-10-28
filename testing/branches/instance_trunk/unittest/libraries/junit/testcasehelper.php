<?php
/**
 * Joomla! Unit Test Facility.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id$
 */

/**
 * Utility functions for test cases
 */
class JUnit_TestCaseHelper {

    /**
     * Dump information on where two strings differ.
     */
    static function stringDiff($actual, $expect) {
        $scanTo = min(strlen($actual), strlen($expect));
        for ($posn = 0; $posn < $scanTo; ++$posn) {
            if ($actual[$posn] != $expect[$posn]) {
                break;
            }
        }
        if ($posn > 30) {
            $from = $posn - 30;
            $bias = 30;
        } else {
            $from = 0;
            $bias = $posn;
        }
        $aSeg = substr($actual, $from, 60);
        $eSeg = substr($expect, $from, 60);
        // If the strings match, return nothing.
        if ($aSeg == $eSeg) {
            return '';
        }
        $scanTo = max(strlen($aSeg), strlen($eSeg));
        $endAt = min(strlen($aSeg), strlen($eSeg));
        $diff = str_repeat(' ', $scanTo);
        for ($ind = 0; $ind < $scanTo; ++$ind) {
            if ($ind < strlen($eSeg) && ($ech = $eSeg[$ind]) < ' ') {
                $eSeg[$ind] = ' ';
                $diff[$ind] = '-';
            }
            if ($ind < strlen($aSeg) && ($ach = $aSeg[$ind]) < ' ') {
                $aSeg[$ind] = ' ';
                $diff[$ind] = '-';
            }
            if ($ind >= $endAt) {
                $diff[$ind] = '*';
                continue;
            }
            if ($ech != $ach) {
                $diff[$ind] = '|';
            }
        }
        return 'Mismatched strings at ' . $posn . chr(10)
            . 'Actual: <' . $aSeg . '>' . chr(10)
            . 'Diff:    ' . $diff . chr(10)
            . 'Expect: <' . $eSeg . '>' . chr(10);
    }

}

