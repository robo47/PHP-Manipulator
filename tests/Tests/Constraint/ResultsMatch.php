<?php

namespace Tests\Constraint;

use PHP\Manipulator\Tokenfinder\Result;
use PHPUnit_Framework_Constraint;
use PHPUnit_Util_InvalidArgumentHelper;
use Tests\Util;

class ResultsMatch extends PHPUnit_Framework_Constraint
{
    /**
     * @var Result
     */
    protected $expectedResult = null;

    /**
     * Cause of missmatch
     *
     * @var string
     */
    protected $cause = '';

    /**
     * @param Result $expected
     */
    public function __construct(Result $expected)
    {
        parent::__construct();
        $this->expectedResult = $expected;
    }

    /**
     * @param Result $other
     * @param string $description  Additional information about the test
     * @param bool   $returnResult Whether to return a result or throw an exception
     *
     * @return bool
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        if (!$other instanceof Result) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                1,
                Result::class
            );
        }
        $expectedResultTokens = $this->expectedResult->getTokens();
        $actualResultTokens   = $other->getTokens();

        if (count($expectedResultTokens) !== count($actualResultTokens)) {
            $this->cause = 'length';
            if ($returnResult) {
                return false;
            }
            $this->fail($other, $description);
        }

        foreach ($expectedResultTokens as $key => $token) {
            if ($token !== $actualResultTokens[$key]) {
                $this->cause = 'missmatch of token: '.$key;
                if ($returnResult) {
                    return false;
                }
                $this->fail($other, $description);
            }
        }

        return true;
    }

    /**
     * @param mixed $other
     *
     * @return string
     */
    protected function failureDescription($other)
    {
        return 'Results do not match: '.PHP_EOL.
        'Cause: '.$this->cause.PHP_EOL.
        Util::compareResults($this->expectedResult, $other);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Result matches ';
    }
}
