<?php

namespace Tests\Constraint;

use PHP\Manipulator\Token;
use PHPUnit_Framework_Constraint;
use PHPUnit_Framework_Constraint_IsEqual;
use PHPUnit_Util_InvalidArgumentHelper;
use SebastianBergmann\Diff\Differ;

class TokensMatch extends PHPUnit_Framework_Constraint
{
    /**
     * @var Token
     */
    protected $expectedToken = null;

    /**
     * @var bool
     */
    protected $strict = false;

    /**
     * Difference found on evaluation
     *
     * @var string
     */
    protected $difference = '';

    /**
     * @param Token $expected
     * @param bool  $strict
     */
    public function __construct(Token $expected, $strict)
    {
        parent::__construct();

        if (!is_bool($strict)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'bool'
            );
        }

        $this->expectedToken = $expected;
        $this->strict        = $strict;
    }

    /**
     * @param Token  $other
     * @param string $description  Additional information about the test
     * @param bool   $returnResult Whether to return a result or throw an exception
     *
     * @return bool
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        if (!$other instanceof Token) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                1,
                Token::class
            );
        }
        $expectedToken = $this->expectedToken;

        $equal = $this->getEqualsConstraint($expectedToken->getValue());
        if (!$equal->evaluate($other->getValue(), $description, true)) {
            $this->difference = 'values';
            if ($returnResult) {
                return false;
            }
            $this->fail($other, $description);
        }

        $equal = $this->getEqualsConstraint($expectedToken->getType());
        if (!$equal->evaluate($other->getType(), $description, true)) {
            $this->difference = 'types';
            if ($returnResult) {
                return false;
            }
            $this->fail($other, $description);
        }

        if (true === $this->strict) {
            $equal = $this->getEqualsConstraint($expectedToken->getLineNumber());
            if (!$equal->evaluate($other->getLineNumber(), $description, true)) {
                $this->difference = 'linenumber';
                if ($returnResult) {
                    return false;
                }
                $this->fail($other, $description);
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     *
     * @return PHPUnit_Framework_Constraint_IsEqual
     */
    protected function getEqualsConstraint($value)
    {
        return new PHPUnit_Framework_Constraint_IsEqual($value);
    }

    /**
     * @param mixed $other
     *
     * @return string
     */
    protected function failureDescription($other)
    {
        $message = PHP_EOL.(new Differ())->diff(
            (string) $this->expectedToken,
            (string) $other
        );
        $difference = $this->difference;

        return 'Tokens are different: ['.$difference.']'.$message;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Token matches another Token';
    }
}
