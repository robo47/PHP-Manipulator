<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\Tokenfinder;
use PHP\Manipulator\Tokenfinder\Result;
use Tests\Util;

class ResultsMatch extends \PHPUnit_Framework_Constraint
{

    /**
     * @var \PHP\Manipulator\Tokenfinder\Result
     */
    protected $_expectedResult = null;

    /**
     * Cause of missmatch
     *
     * @var string
     */
    protected $_cause = '';

    /**
     *
     * @param \PHP\Manipulator\Tokenfinder\Result $expected
     */
    public function __construct($expected)
    {
        if (!$expected instanceof Result) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, '\PHP\Manipulator\Tokenfinder\Result'
            );
        }

        $this->_expectedResult = $expected;
    }

    /**
     *
     * @param \PHP\Manipulator\Tokenfinder\Result $other
     * @return boolean
     */
    public function evaluate($other)
    {
        if (!$other instanceof Result) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, '\PHP\Manipulator\Tokenfinder\Result'
            );
        }
        $expectedResultTokens = $this->_expectedResult->getTokens();
        $actualResultTokens = $other->getTokens();

        if (count($expectedResultTokens) != count($actualResultTokens)) {
            $this->_cause = 'length';
            return false;
        }

        foreach ($expectedResultTokens as $key => $token) {
            if ($token !== $actualResultTokens[$key]) {
                $this->_cause = 'missmatch of token: ' . $key;
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
        return 'Results do not match: ' . PHP_EOL .
            'Cause: ' . $this->_cause . PHP_EOL .
            Util::compareResults($this->_expectedResult, $other);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Result matches ';
    }
}