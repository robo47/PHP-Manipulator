<?php

namespace Tests\Constraint;

use \PHP\Manipulator\TokenContainer;
use \PHP\Manipulator\Token;
use \PHP\Manipulator\Util;

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
     *
     * @param PHP\Manipulator\TokenContainer $expected
     * @param boolean $strict
     */
    public function __construct(TokenContainer $expected, $strict)
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
     *
     * @param PHP\Manipulator\TokenContainer $other
     * @return boolean
     */
    public function evaluate($other)
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
                return false;
            }

            $i++;
            $expectedIterator->next();
            $actualIterator->next();
        }

        if ($expectedIterator->valid() || $actualIterator->valid()) {
            return false;
        }
        return true;
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     */
    protected function failureDescription($other, $description, $not)
    {
        $containerDiff = Util::compareContainers(
                $other,
                $this->_expectedContainer
        );

        $message = 'Tokens are different: [length]' . PHP_EOL .
            PHP_EOL . $containerDiff;

        return $message;
    }

    /**
     *
     * @return string
     */
    public function toString()
    {
        return 'TokenContainer matches another Container';
    }
}