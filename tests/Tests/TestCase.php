<?php

namespace Tests;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\Config;
use PHP\Manipulator\Util;
use Tests\Constraint\Count;
use Tests\Constraint\ResultsMatch;
use Tests\Constraint\TokenContainerMatch;
use Tests\Constraint\TokensMatch;
use Tests\Constraint\ValidTokenMatchingClosure;

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
            throw new \Exception('Fixture ' . $filename . ' not found');
        }

        return file_get_contents($file);
    }

    /**
     * Get Result From Container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param integer $start
     * @param integer $end
     * @return \PHP\Manipulator\TokenFinder\Result
     */
    public function getResultFromContainer($container, $start, $end)
    {
        $result = new Result();
        for ($index = $start; $index <= $end; $index++) {
            $result->addToken($container[$index]);
        }

        return $result;
    }

    /**
     * Returns tokens-array from fixture-file
     *
     * @param string $filename
     * @return \PHP\Manipulator\TokenContainer
     */
    public function getContainerFromFixture($filename)
    {
        return new TokenContainer(
            $this->getFixtureFileContent($filename)
        );
    }

    /**
     * Compares if two Tokens Match
     *
     * @param \PHP\Manipulator\Token $expectedToken
     * @param \PHP\Manipulator\Token $actualToken
     * @param boolean $strict
     */
    public function assertTokenMatch($expectedToken, $actualToken, $strict = false, $message = '')
    {
        $constraint = new TokensMatch(
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
     * Compares if two Tokens Match
     *
     * @param \PHP\Manipulator\Token $expectedToken
     * @param \PHP\Manipulator\Token $actualToken
     * @param boolean $strict
     */
    public function assertValidTokenMatchingClosure($closure, $message = '')
    {
        $constraint = new ValidTokenMatchingClosure(
            $closure
        );

        self::assertThat(
            $closure,
            $constraint,
            $message
        );
    }

    /**
     * Compares if two TokenContainer tokens match
     *
     * @param \PHP\Manipulator\TokenContainer $expectedTokens
     * @param \PHP\Manipulator\TokenContainer $actualTokens
     * @param string $message
     */
    public function assertTokenContainerMatch($expectedTokens, $actualTokens, $strict = false, $message = '')
    {
        $constraint = new TokenContainerMatch(
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
     * @param \PHP\Manipulator\TokenFinder\Result $expectedResult
     * @param \PHP\Manipulator\TokenFinder\Result $actualResult
     * @param string $message
     */
    public function assertFinderResultsMatch($expectedResult, $actualResult, $message = '')
    {
        $constraint = new ResultsMatch(
            $expectedResult
        );

        self::assertThat(
            $actualResult,
            $constraint,
            $message
        );
    }

    /**
     * Marks test as skipped if asp-tags are inactive
     */
    public function checkAsptags()
    {
        if (false === (bool) ini_get('asp_tags')) {
            $this->markTestSkipped('Can\'t ' . __CLASS__ . ' with asp_tags deactivated');
        }
    }

    /**
     * Marks test as skipped if short-tags are inactive
     */
    public function checkShorttags()
    {
        if (false === (bool) ini_get('short_open_tag')) {
            $this->markTestSkipped('Can\'t run ' . __CLASS__ . ' with short_open_tag deactivated');
        }
    }

    /**
     * @param integer$number
     * @return \PHP\Manipulator\Config
     */
    public function getConfig()
    {
        return Config::factory('Tests\\Stub\\ConfigStub', '', false);
    }

    /**
     * @param integer $number
     * @return \PHP\Manipulator\Config\Xml
     */
    public function getXmlConfig($number)
    {
        return Config::factory(
            'xml',
            TESTS_PATH . '/_fixtures/Config/config' . $number . '.xml'
            , true
        );
    }
}
