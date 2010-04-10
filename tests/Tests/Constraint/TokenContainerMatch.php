<?php

namespace Tests\Constraint;

use \PHP\Manipulator\TokenContainer;
use \PHP\Manipulator\Token;

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

class PHPManipulator_Constraint_TokensMatch extends \PHPUnit_Framework_Constraint
{

    /**
     * @var PHP\Manipulator\Token
     */
    protected $_expectedToken = null;
    /**
     * @var boolean
     */
    protected $_strict = false;

    /**
     *
     * @param PHP\Manipulator\Token $expected
     * @param boolean $strict
     */
    public function __construct($expected, $strict)
    {
        if (!$expected instanceof Token) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'PHP\Manipulator\Token'
            );
        }

        if (!is_bool($strict)) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                2, 'boolean'
            );
        }

        $this->_expectedToken = $expected;
        $this->_strict = $strict;
    }

    /**
     *
     * @param PHP\Manipulator\Token $other
     * @return boolean
     */
    public function evaluate($other)
    {
        if (!$other instanceof Token) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'PHP\Manipulator\Token'
            );
        }
        $expectedToken = $this->_expectedToken;

        $equalValueConstraint = new \PHPUnit_Framework_Constraint_IsEqual($expectedToken->getValue());
        if (!$equalValueConstraint->evaluate($other->getValue())) {
            $this->_difference = 'values';
            return false;
        }

        $equalValueConstraint = new \PHPUnit_Framework_Constraint_IsEqual($expectedToken->getType());
        if (!$equalValueConstraint->evaluate($other->getType())) {
            $this->_difference = 'types';
            return false;
        }

        if (true === $this->_strict) {
            $equalValueConstraint = new \PHPUnit_Framework_Constraint_IsEqual($expectedToken->getType());
            if (!$equalValueConstraint->evaluate($other->getType())) {
                $this->_difference = 'linenumber';
                return false;
            }
        }
        return true;
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     * @return string
     */
    protected function failureDescription($other, $description, $not)
    {
        $message = PHP_EOL . \PHPUnit_Util_Diff::diff((string) $this->_expectedToken, (string) $other);
        $difference = $this->_difference;
        return 'Tokens are different: [' . $difference . ']' . $message;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Token matches another Token';
    }
}

