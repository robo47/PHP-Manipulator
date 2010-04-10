<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\Tokenfinder;
use PHP\Manipulator\Tokenfinder\Result;

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
     *
     * @param \PHP\Manipulator\Tokenfinder\Result $expectedResult
     * @param \PHP\Manipulator\Tokenfinder\Result $actualResult
     */
    public static function compareResults(Result $expectedResult,Result $actualResult)
    {
        $expectedIterator = new \ArrayIterator($expectedResult->getTokens());
        $actualIterator = new \ArrayIterator($expectedResult->getTokens());

        $values = array();
        $longest = 0;

        while($actualIterator->valid() && $expectedIterator->valid()) {

            $expected = '';
            $actual = '';

            if($expectedIterator->valid()) {
                $expected = (string)self::dumpToken($expectedIterator->current());
            }
            if($actualIterator->valid()) {
                $actual = (string)self::dumpToken($actualIterator->current());
            }

            $values[] = array(
                'actual' => $actual,
                'expected' => $expected,
                'missmatch' => (bool)($actualIterator->current() === $expectedIterator)
            );

            if (strlen($actual) > $longest) {
                $longest = strlen($actual);
            }

            if (strlen($expected) > $longest) {
                $longest = strlen($expected);
            }

            $expectedIterator->next();
            $actualIterator->next();
        }
        
        $comparision = '    ';
        $comparision .= str_pad('expected (' . count($expectedResult) . ')', $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= ' | ';
        $comparision .= str_pad('actual(' . count($actualResult) . ')', $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= PHP_EOL;
        $comparision .= PHP_EOL;
        $i = 0;
        foreach($values as $val) {
            if (true === $val['missmatch']) {
                $comparision .= '####### NEXT IS DIFFERENT ## ' . PHP_EOL;
            }
            $comparision .= str_pad($i . ') ', 4, ' ');
            $comparision .= str_pad($val['expected'], $longest + 2, ' ');
            $comparision .= ' | ';
            $comparision .= str_pad($val['actual'], $longest + 2, ' ');
            $comparision .= PHP_EOL;
            $i++;
        }
        return $comparision;
    }

    /**
     * @todo duplicates with \PHP\Manip\Util::dumpToken
     * @param Token $token
     * @return <type>
     */
    public static function dumpToken(Token $token)
    {
        $type = $token->getType();
        $value = $token->getValue();
        $typeName = '[SIMPLE]';
        if (null !== $type) {
            $typeName = token_name($token->getType());
        }
        $length = (string) mb_strlen($value, 'utf-8');
        $search = array("\n\r", "\n", "\r", "\t", " ");
        $replace = array("\\n\\r", "\\n", "\\r", "\\t", ".");

        $value = str_replace($search, $replace, $value);

        $line = $token->getLinenumber();

        if (null === $line) {
            $line = 'NULL';
        }
        return str_pad($typeName, 28, ' ', STR_PAD_RIGHT) . '| ' .
            str_pad($length, 4, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad($line, 4, ' ', STR_PAD_LEFT) . ' | ' . $value;
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
            self::compareResults($this->_expectedResult, $other);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Result matches ';
    }
}