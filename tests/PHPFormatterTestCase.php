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
        $file = $this->getFixtureFilePath($filename);
        return PHP_Formatter_TokenContainer::createTokenArrayFromFile($file);
    }

    /**
     * Compares if tokens match (ignores arrays third element [fileline])
     *
     * @param PHP_Formatter_TokenContainer $expectedTokens
     * @param PHP_Formatter_TokenContainer $actualTokens
     * @param string $message
     * @todo refactor and look @phpunit how it
     */
    public function assertTokensMatch($expectedTokens, $actualTokens, $message)
    {
        $this->assertType(
            'PHP_Formatter_TokenContainer',
            $expectedTokens,
            'expected Tokens should be an PHP_Formatter_TokenContainer'
        );
        $this->assertType(
            'PHP_Formatter_TokenContainer',
            $actualTokens,
            'actual Tokens should be an PHP_Formatter_TokenContainer'
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
}