<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHP/Formatter/Util.php';

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
     * @todo look @phpunit how asserts are done the right way ...
     * @todo add message ? 
     */
    public function assertTokenMatch($expectedToken, $actualToken, $strict = false, $message = '')
    {
        $this->assertType(
            'PHP_Formatter_Token',
            $expectedToken,
            'expected Token should be a PHP_Formatter_Token'
        );
        $this->assertType(
            'PHP_Formatter_Token',
            $actualToken,
            'actual Token should be a PHP_Formatter_Token'
        );

        $this->assertEquals(
            $expectedToken->getValue(),
            $actualToken->getValue(),
            'Different Values' . $message
        );

        $this->assertEquals(
            $expectedToken->getType(),
            $actualToken->getType(),
            'Different Types' . $message
        );

        if (true === $strict) {
            $this->assertEquals(
                $expectedToken->getLinenumber(),
                $actualToken->getLinenumber(),
                'Different Linenumber' . $message
            );
        }
    }

    /**
     * Compares if two TokenContainer tokens match
     *
     * @param PHP_Formatter_TokenContainer $expectedTokens
     * @param PHP_Formatter_TokenContainer $actualTokens
     * @param string $message
     * @todo look @phpunit how asserts are done the right way ...
     */
    public function assertTokenContainerMatch($expectedTokens, $actualTokens, $strict = false, $message = '')
    {
        $this->assertType(
            'PHP_Formatter_TokenContainer',
            $expectedTokens,
            'expected Tokens should be a PHP_Formatter_TokenContainer'
        );
        
        $this->assertType(
            'PHP_Formatter_TokenContainer',
            $actualTokens,
            'actual Tokens should be a PHP_Formatter_TokenContainer'
        );

        $expectedIterator = $expectedTokens->getIterator();
        $actualIterator = $actualTokens->getIterator();

        $i = 0;
        while($expectedIterator->valid() && $actualIterator->valid()) {

            $expectedToken = $expectedIterator->current();
            /* @var $expectedToken PHP_Formatter_Token */
            
            $actualToken = $actualIterator->current();
            /* @var $actualToken PHP_Formatter_Token */

            if(!$actualToken->equals($expectedToken, $strict)) {
                $message = PHP_Formatter_Util::compareContainers($actualTokens, $expectedTokens);
                $this->fail('Tokens are different: [mismatch] : ' . $i . PHP_EOL . $message);
            }
            $i++;
            $expectedIterator->next();
            $actualIterator->next();
        }
        if($expectedIterator->valid() || $actualIterator->valid()) {
            $message = PHP_Formatter_Util::compareContainers($actualTokens, $expectedTokens);
            $this->fail('Tokens are different: [length]' . PHP_EOL . $message);
        }
    }
}