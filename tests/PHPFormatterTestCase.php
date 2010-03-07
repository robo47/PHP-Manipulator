<?php

require_once 'PHPUnit/Framework/TestCase.php';

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
        if (!file_exists($file) && is_file($file)) {
            throw new Exception('Fixture ' . $file . ' not found');
        }
        return file_get_contents($file);
    }

    /**
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
     */
    public function getTokenArrayFromFixtureFile($filename)
    {
        $code = $this->getFixtureFileContent($filename);
        return PHP_Formatter_TokenContainer::createFromCode($code);
    }

    /**
     *
     * @param PHP_Formatter_Token $expectedToken
     * @param PHP_Formatter_Token $actualToken
     * @param boolean $strict
     * @todo add message ? 
     */
    public function assertTokenMatch($expectedToken, $actualToken, $strict = false)
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

        $this->assertEquals($expectedToken->getValue(), $actualToken->getValue(), 'Different Values');
        $this->assertEquals($expectedToken->getType(), $actualToken->getType(), 'Different Types');

        if (true === $strict) {
            $this->assertEquals($expectedToken->getLinenumber(), $actualToken->getLinenumber(), 'Different Linenumber');
        }
    }

    /**
     * Compares if tokens match (ignores arrays third element [fileline])
     *
     * @param PHP_Formatter_TokenContainer $expectedTokens
     * @param PHP_Formatter_TokenContainer $actualTokens
     * @param string $message
     * @todo refactor and look @phpunit how it is done the right way ... 
     */
    public function assertTokensMatch($expectedTokens, $actualTokens, $message)
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

        while($expectedIterator->valid()) {

            $expectedToken = $expectedIterator->current();
            /* @var $expectedToken PHP_Formatter_Token */
            $actualToken = $actualIterator->current();
            /* @var $actualToken PHP_Formatter_Token */

            if(!$actualToken->equals($expectedToken)) {
                $message = $this->getTokenArrayDifferenceAsCodeDiff($actualTokens, $expectedTokens);
                $this->fail('Tokens are different: ' . PHP_EOL . $message);
            }

            $expectedIterator->next();
            $actualIterator->next();
        }
    }

    /**
     * Transforms the Tokens into Code and makes a diff of the code
     *
     * @param PHP_Formatter_TokenContainer $actualTokens
     * @param PHP_Formatter_TokenContainer $expectedTokens
     * @return string
     */
    public static function getTokenArrayDifferenceAsCodeDiff($actualTokens, $expectedTokens)
    {
        return PHPUnit_Util_Diff::diff(
            $actualTokens->toString(),
            $expectedTokens->toString()
        );
    }

    /**
     *
     * @param string $code
     * @return PHP_Formatter_TokenContainer
     */
    public static function getTokenContainerFromCode($code)
    {
        return PHP_Formatter_TokenContainer::createFromCode($code);
    }
}