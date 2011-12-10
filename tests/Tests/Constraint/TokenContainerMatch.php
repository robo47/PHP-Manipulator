<?php

namespace Tests\Constraint;

use \PHP\Manipulator\TokenContainer;
use \PHP\Manipulator\Token;
use \Tests\Util;

class TokenContainerMatch extends \PHPUnit_Framework_Constraint
{

    /**
     * @var PHP\Manipulator\TokenContainer
     */
    protected $_expectedContainer = null;

    /**
     * @var boolean
     */
    protected $_strict = false;

    /**
     * Constraint for checking two TokenContainer match
     *
     * Strict checking compares linenumbers too
     *
     * @param \PHP\Manipulator\TokenContainer $expected
     * @param boolean $strict
     */
    public function __construct($expected, $strict)
    {
        if (!$expected instanceof TokenContainer) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'PHP\Manipulator\TokenContainer'
            );
        }

        if (!is_bool($strict)) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                2, 'boolean'
            );
        }

        $this->_expectedContainer = $expected;
        $this->_strict = $strict;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $other
     * @param  string $description Additional information about the test
     * @param  bool $returnResult Whether to return a result or throw an exception
     * @return boolean
     */
    public function evaluate($other, $description = '', $returnResult = FALSE)
    {
        if (!$other instanceof TokenContainer) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'PHP\Manipulator\TokenContainer'
            );
        }

        $expectedIterator = $this->_expectedContainer->getIterator();
        $actualIterator = $other->getIterator();

        $i = 0;
        while ($expectedIterator->valid() && $actualIterator->valid()) {

            $expectedToken = $expectedIterator->current();
            /* @var $expectedToken PHP\Manipulator\Token */

            $actualToken = $actualIterator->current();
            /* @var $actualToken PHP\Manipulator\Token */

            if (!$actualToken->equals($expectedToken, $this->_strict)) {
                if ($returnResult) {
                    return FALSE;
                }
                $this->fail($other, $description);
            }

            $i++;
            $expectedIterator->next();
            $actualIterator->next();
        }

        if ($expectedIterator->valid() || $actualIterator->valid()) {
            if ($returnResult) {
                return FALSE;
            }
            $this->fail($other, $description);
        }
        return true;
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     */
    protected function failureDescription($other)
    {
        $containerDiff = Util::compareContainers(
            $this->_expectedContainer,
            $other,
            $this->_strict
        );

        $message = 'Tokens are different:' . PHP_EOL .
        PHP_EOL . $containerDiff;

        return $message;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'TokenContainer matches another Container';
    }
}