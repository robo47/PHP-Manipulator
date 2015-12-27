<?php

namespace Tests;

use Closure;
use Exception;
use PHP\Manipulator\Config;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\ValueObject\ReadableFile;
use PHPUnit_Framework_TestCase;
use Tests\Constraint\ResultsMatch;
use Tests\Constraint\TokenContainerMatch;
use Tests\Constraint\TokensMatch;
use Tests\Constraint\ValidTokenMatchingClosure;
use Tests\Stub\ConfigStub;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Returns content of fixture file
     *
     * @param string $filename Name of the fixture file
     *
     * @throws Exception
     *
     * @return string
     */
    public function getFixtureFileContent($filename)
    {
        $path = sprintf('%s/_fixtures/%s', TESTS_PATH, $filename);
        $file = ReadableFile::createFromPath($path);

        return file_get_contents($file->asString());
    }

    /**
     * @param int[]  $incomingData
     * @param string $basePath
     *
     * @return array
     *
     * @throws Exception
     *
     * @todo nicer exception
     */
    protected function convertContainerFixtureToProviderData(array $incomingData, $basePath)
    {
        $data = [];
        foreach ($incomingData as $name => $line) {
            if (array_key_exists($name, $data)) {
                // Clean up
                $message = 'Foo';
                throw new \Exception($message);
            }
            $data[$name] = [
                $this->getContainerFromFixture(sprintf('%sinput%u.php', $basePath, $line)),
                $this->getContainerFromFixture(sprintf('%soutput%u.php', $basePath, $line)),
            ];
        }

        return $data;
    }

    /**
     * Get Result From Container
     *
     * @param TokenContainer $container
     * @param int            $start
     * @param int            $end
     *
     * @return Result
     */
    public function getResultFromContainer(TokenContainer $container, $start, $end)
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
     *
     * @return TokenContainer
     */
    public function getContainerFromFixture($filename)
    {
        return TokenContainer::factory(
            $this->getFixtureFileContent($filename)
        );
    }

    /**
     * Compares if two Tokens Match
     *
     * @param Token  $expectedToken
     * @param Token  $actualToken
     * @param bool   $strict
     * @param string $message
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
     * @param Closure $closure
     * @param string  $message
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
     * @param TokenContainer $expectedTokens
     * @param TokenContainer $actualTokens
     * @param bool           $strict
     * @param string         $message
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
     * @param Result $expectedResult
     * @param Result $actualResult
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
            $this->markTestSkipped('Can\'t '.__CLASS__.' with asp_tags deactivated');
        }
    }

    /**
     * Marks test as skipped if short-tags are inactive
     */
    public function checkShorttags()
    {
        if (false === (bool) ini_get('short_open_tag')) {
            $this->markTestSkipped('Can\'t run '.__CLASS__.' with short_open_tag deactivated');
        }
    }

    /**
     * @return ConfigStub
     */
    public function getConfig()
    {
        return Config::factory(ConfigStub::class, '', false);
    }

    /**
     * @param int $number
     *
     * @return Config\XmlConfig
     */
    public function getXmlConfig($number)
    {
        return Config::factory(
            'xml',
            TESTS_PATH.'/_fixtures/Config/config'.$number.'.xml',
            true
        );
    }
}
