<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2002-2007, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @author     Mike Lively <m@digitalsandwich.com>
 * @copyright  2002-2007 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: DataSetIsEqual.php 1362 2007-09-29 10:56:13Z sb $
 * @link       http://www.phpunit.de/
 * @since      File available since Release 3.2.0
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Util/Filter.php';
require_once 'PHPUnit/Framework/Constraint.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * Asserts whether or not two dbunit datasets are equal.
 *
 * @category   Testing
 * @package    PHPUnit
 * @author     Mike Lively <m@digitalsandwich.com>
 * @copyright  2007 Mike Lively <m@digitalsandwich.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.2.6
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 3.2.0
 */
class PHPUnit_Extensions_Database_Constraint_DataSetIsEqual extends PHPUnit_Framework_Constraint
{

    /**
     * @var PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected $value;

    /**
     * @var string
     */
    protected $failure_reason;

    /**
     * Creates a new constraint.
     *
     * @param PHPUnit_Extensions_Database_DataSet_IDataSet $value
     */
    public function __construct(PHPUnit_Extensions_Database_DataSet_IDataSet $value)
    {
        $this->value = $value;
    }

    /**
     * Determines whether or not the given dataset matches the dataset used to 
     * create this constraint.
     *
     * @param PHPUnit_Extensions_Database_DataSet_IDataSet $other
     * @return bool
     */
    public function evaluate($other)
    {
        if ($other instanceof PHPUnit_Extensions_Database_DataSet_IDataSet) {
            try {
                $this->value->assertEquals($other);
                return TRUE;
            } catch (Exception $e) {
                $this->failure_reason = $e->getMessage();
                return FALSE;
            }
        } else {
            throw new InvalidArgumentException("PHPUnit_Extensions_Database_DataSet_IDataSet expected");
        }
    }

    /**
     * @param   mixed   $other The value passed to evaluate() which failed the
     *                         constraint check.
     * @param   string  $description A string with extra description of what was
     *                               going on while the evaluation failed.
     * @param   boolean $not Flag to indicate negation.
     * @throws  PHPUnit_Framework_ExpectationFailedException
     */
    protected function failureDescription($other, $description, $not)
    {
        $failureDescription = sprintf('Failed asserting that %s %s Reason: %s', 
            $other->__toString(), 
            $this->toString(), 
            $this->failure_reason);
        
        if ($not) {
            $failureDescription = self::negate($failureDescription);
        }
        
        if (!empty($description)) {
            $failureDescription = $description . "\n" . $failureDescription;
        }
        
        return $failureDescription;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     * @access public
     */
    public function toString()
    {
        return sprintf('is equal to %s', 
            $this->value->__toString());
    }
}
?>
