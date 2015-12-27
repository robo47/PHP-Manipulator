<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenContainer;
use PHPUnit_Framework_Constraint;
use PHPUnit_Util_InvalidArgumentHelper;
use Tests\Util;

class TokenContainerMatch extends PHPUnit_Framework_Constraint
{
    /**
     * @var TokenContainer
     */
    protected $expectedContainer = null;

    /**
     * @var bool
     */
    protected $strict = false;

    /**
     * Constraint for checking two TokenContainer match
     *
     * Strict checking compares linenumbers too
     *
     * @param TokenContainer $expected
     * @param bool           $strict
     */
    public function __construct(TokenContainer $expected, $strict)
    {
        parent::__construct();
        if (!is_bool($strict)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'bool'
            );
        }

        $this->expectedContainer = $expected;
        $this->strict            = $strict;
    }

    /**
     * @param TokenContainer $other
     * @param string         $description  Additional information about the test
     * @param bool           $returnResult Whether to return a result or throw an exception
     *
     * @return bool
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        if (!$other instanceof TokenContainer) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                1,
                TokenContainer::class
            );
        }

        $expectedIterator = $this->expectedContainer->getIterator();
        $actualIterator   = $other->getIterator();

        $i = 0;
        while ($expectedIterator->valid() && $actualIterator->valid()) {
            $expectedToken = $expectedIterator->current();

            $actualToken = $actualIterator->current();

            if (!$actualToken->equals($expectedToken, $this->strict)) {
                if ($returnResult) {
                    return false;
                }
                $this->fail($other, $description);
            }

            $i++;
            $expectedIterator->next();
            $actualIterator->next();
        }

        if ($expectedIterator->valid() || $actualIterator->valid()) {
            if ($returnResult) {
                return false;
            }
            $this->fail($other, $description);
        }

        return true;
    }

    protected function failureDescription($other)
    {
        $containerDiff = Util::compareContainers(
            $this->expectedContainer,
            $other,
            $this->strict
        );

        $message = 'Tokens are different:'.PHP_EOL.
            PHP_EOL.$containerDiff;

        return $message;
    }

    public function toString()
    {
        return 'TokenContainer matches another Container';
    }
}
