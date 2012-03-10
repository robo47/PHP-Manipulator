<?php

namespace Tests\Constraint;

use \PHP\Manipulator\TokenContainer;
use \PHP\Manipulator\Token;

class TokensMatch extends \PHPUnit_Framework_Constraint
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
     * Difference found on evaluation
     *
     * @var string
     */
    protected $_difference = '';

    /**
     * @param \PHP\Manipulator\Token $expected
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
     * @param \PHP\Manipulator\Token $other
     * @param  string $description Additional information about the test
     * @param  bool $returnResult Whether to return a result or throw an exception
     * @return boolean
     */
    public function evaluate($other, $description = '', $returnResult = FALSE)
    {
        if (!$other instanceof Token) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'PHP\Manipulator\Token'
            );
        }
        $expectedToken = $this->_expectedToken;

        $equal = $this->_getEqualsConstraint($expectedToken->getValue());
        if (!$equal->evaluate($other->getValue(), $description, true)) {
            $this->_difference = 'values';
            if ($returnResult) {
                return FALSE;
            }
            $this->fail($other, $description);
        }

        $equal = $this->_getEqualsConstraint($expectedToken->getType());
        if (!$equal->evaluate($other->getType(), $description, true)) {
            $this->_difference = 'types';
            if ($returnResult) {
                return FALSE;
            }
            $this->fail($other, $description);
        }

        if (true === $this->_strict) {
            $equal = $this->_getEqualsConstraint($expectedToken->getLinenumber());
            if (!$equal->evaluate($other->getLinenumber(), $description, true)) {
                $this->_difference = 'linenumber';
                if ($returnResult) {
                    return FALSE;
                }
                $this->fail($other, $description);
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return \PHPUnit_Framework_Constraint_IsEqual
     */
    protected function _getEqualsConstraint($value)
    {
        return new \PHPUnit_Framework_Constraint_IsEqual($value);
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     * @return string
     */
    protected function failureDescription($other)
    {
        $message = PHP_EOL . \PHPUnit_Util_Diff::diff(
            (string) $this->_expectedToken,
            (string) $other
        );
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
