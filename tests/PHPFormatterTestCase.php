<?php

class PHPFormatterTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Returns content of fixture file
     *
     * @param string $filename Name of the fixture file
     * @return string
     */
    public function getFixtureFileContent($filename)
    {
        $file = $this->getFixtureFilePath($filename);
        if (!file_exists($file) || !is_file($file)) {
            throw new Exception('Fixture ' . $file . ' not found');
        }
        return file_get_contents($file);
    }

    /**
     * Get Fixture Filepath
     *
     * @param string $filename
     * @return string
     */
    public function getFixtureFilePath($filename)
    {
        return TESTS_PATH . '/_fixtures/' . $filename;
    }

    /**
     * Returns tokens-array from fixture-file
     *
     * @param string $filename
     * @return PHP_Formatter_TokenContainer
     * @todo rename + refactor
     */
    public function getTokenArrayFromFixtureFile($filename)
    {
        $code = $this->getFixtureFileContent($filename);
        return PHP_Formatter_TokenContainer::createFromCode($code);
    }

    /**
     * Compares if two Tokens Match
     *
     * @param PHP_Formatter_Token $expectedToken
     * @param PHP_Formatter_Token $actualToken
     * @param boolean $strict
     */
    public function assertTokenMatch($expectedToken, $actualToken, $strict = false, $message = '')
    {
        $constraint = new PHPFormatter_Constraint_TokensMatch(
            $expectedToken,
            $strict
        );

        self::assertThat(
            $actualToken,
            $constraint,
            $message
        );
    }

    /**
     * Compares if two TokenContainer tokens match
     *
     * @param PHP_Formatter_TokenContainer $expectedTokens
     * @param PHP_Formatter_TokenContainer $actualTokens
     * @param string $message
     */
    public function assertTokenContainerMatch($expectedTokens, $actualTokens, $strict = false, $message = '')
    {
        $constraint = new PHPFormatter_Constraint_TokenContainerMatch(
            $expectedTokens,
            $strict
        );

        self::assertThat(
            $actualTokens,
            $constraint,
            $message
        );
    }
}

class PHPFormatter_Constraint_TokenContainerMatch extends PHPUnit_Framework_Constraint
{
    /**
     * @var PHP_Formatter_TokenContainer
     */
    protected $_expectedContainer = null;

    /**
     * @var boolean
     */
    protected $_strict = false;

    /**
     *
     * @param PHP_Formatter_TokenContainer $expected
     * @param boolean $strict
     */
    public function __construct(PHP_Formatter_TokenContainer $expected, $strict)
    {
        if (!$expected instanceof PHP_Formatter_TokenContainer) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
              1, 'PHP_Formatter_TokenContainer'
            );
        }

        if (!is_bool($strict)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
              2, 'boolean'
            );
        }

        $this->_expectedContainer = $expected;
        $this->_strict = $strict;
    }

    /**
     *
     * @param PHP_Formatter_TokenContainer $other
     * @return boolean
     */
    public function evaluate($other)
    {
        if (!$other instanceof PHP_Formatter_TokenContainer) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
              1, 'PHP_Formatter_TokenContainer'
            );
        }
        
        $expectedIterator = $this->_expectedContainer->getIterator();
        $actualIterator = $other->getIterator();

        $i = 0;
        while($expectedIterator->valid() && $actualIterator->valid()) {

            $expectedToken = $expectedIterator->current();
            /* @var $expectedToken PHP_Formatter_Token */

            $actualToken = $actualIterator->current();
            /* @var $actualToken PHP_Formatter_Token */

            if(!$actualToken->equals($expectedToken, $this->_strict)) {
                return false;
            }

            $i++;
            $expectedIterator->next();
            $actualIterator->next();
        }

        if($expectedIterator->valid() || $actualIterator->valid()) {
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
        $containerDiff = PHP_Formatter_Util::compareContainers(
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


class PHPFormatter_Constraint_TokensMatch extends PHPUnit_Framework_Constraint
{
    /**
     * @var PHP_Formatter_Token
     */
    protected $_expectedToken = null;

    /**
     * @var boolean
     */
    protected $_strict = false;

    /**
     *
     * @param PHP_Formatter_TokenContainer $expected
     * @param boolean $strict
     */
    public function __construct(PHP_Formatter_Token $expected, $strict)
    {
        if (!$expected instanceof PHP_Formatter_Token) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
              1, 'PHP_Formatter_Token'
            );
        }

        if (!is_bool($strict)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
              2, 'boolean'
            );
        }

        $this->_expectedToken = $expected;
        $this->_strict = $strict;
    }

    /**
     *
     * @param PHP_Formatter_Token $other
     * @return boolean
     */
    public function evaluate($other)
    {
        if (!$other instanceof PHP_Formatter_Token) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
              1, 'PHP_Formatter_Token'
            );
        }
        $expectedToken = $this->_expectedToken;

        $equalValueConstraint = new PHPUnit_Framework_Constraint_IsEqual($expectedToken->getValue());
        if (!$equalValueConstraint->evaluate($other->getValue())) {
            $this->_difference = 'values';
            return false;
        }

        $equalValueConstraint = new PHPUnit_Framework_Constraint_IsEqual($expectedToken->getType());
        if (!$equalValueConstraint->evaluate($other->getType())) {
            $this->_difference = 'types';
            return false;
        }

        if (true === $this->_strict) {
            $equalValueConstraint = new PHPUnit_Framework_Constraint_IsEqual($expectedToken->getType());
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
     */
    protected function failureDescription($other, $description, $not)
    {
        $message = 'expected: ' . (string)$this->_expectedToken .
                   PHP_EOL . 'actual: ' . (string) $other;
        $difference = $this->_difference;
        $this->fail($other, 'Tokens are different: [' . $difference . ']' . $message);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Token matches another Token';
    }
}