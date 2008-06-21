<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2002-2008, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Testing
 * @package    PHPUnit
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2002-2008 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: ErrorHandler.php 2439 2008-02-14 08:18:31Z sb $
 * @link       http://www.phpunit.de/
 * @since      File available since Release 2.3.0
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Util/Filter.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * @param  integer $errno
 * @param  string  $errstr
 * @param  string  $errfile
 * @param  integer $errline
 * @throws PHPUnit_Framework_Error
 * @since  Function available since Release 2.3.0
 */
function PHPUnit_Util_ErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!($errno & error_reporting())) {
        return FALSE;
    }

    $trace = debug_backtrace();
    array_shift($trace);

    foreach ($trace as $frame) {
        if ($frame['function'] == '__toString') {
            return FALSE;
        }
    }

    if ($errno == E_NOTICE || $errno == E_STRICT) {
        if (PHPUnit_Framework_Notice::$enabled !== TRUE) {
            return FALSE;
        }

        $exception = 'PHPUnit_Framework_Notice';
    } else {
        $exception = 'PHPUnit_Framework_Error';
    }

    throw new $exception(
      $errstr,
      $errno,
      $errfile,
      $errline,
      $trace
    );
}
?>
