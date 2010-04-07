<?php

namespace Tests;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\Util;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Returns content of fixture file
     *
     * @param string $filename Name of the fixture file
     * @return string
     */
    public function getFixtureFileContent($filename)
    {
        $file = TESTS_PATH . '/_fixtures/' . $filename;
        if (!file_exists($file) || !is_file($file)) {
            throw new \Exception('Fixture ' . $file . ' not found');
        }
        return file_get_contents($file);
    }

    /**
     * Returns tokens-array from fixture-file
     *
     * @param string $filename
     * @return PHP\Manipulator\TokenContainer
     */
    public function getContainerFromFixture($filename)
    {
        $code = $this->getFixtureFileContent($filename);
        return new TokenContainer($code);
    }

    /**
     * Compares if two Tokens Match
     *
     * @param PHP\Manipulator\Token $expectedToken
     * @param PHP\Manipulator\Token $actualToken
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
     * @param PHP\Manipulator\TokenContainer $expectedTokens
     * @param PHP\Manipulator\TokenContainer $actualTokens
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

    /**
     * @return boolean
     */
    protected function _aspTagsActivated()
    {
        return (bool) ini_get('asp_tags');
    }

    /**
     * @return boolean
     */
    protected function _shortTagsActivated()
    {
        return (bool) ini_get('short_open_tag');
    }

    /**
     * Marks test as skipped if asp-tags are inactive
     */
    public function checkAsptags()
    {
        if (!$this->_aspTagsActivated()) {
            $this->markTestSkipped('Can\'t ' . __CLASS__ . ' with asp_tags deactivated');
        }
    }

    /**
     * Marks test as skipped if short-tags are inactive
     */
    public function checkShorttags()
    {
        if (!$this->_shortTagsActivated()) {
            $this->markTestSkipped('Can\'t run ' . __CLASS__ . ' with short_open_tag deactivated');
        }
    }
}

class PHPFormatter_Constraint_TokenContainerMatch extends \PHPUnit_Framework_Constraint
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

class PHPFormatter_Constraint_TokensMatch extends \PHPUnit_Framework_Constraint
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