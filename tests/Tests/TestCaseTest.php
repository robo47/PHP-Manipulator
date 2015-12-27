<?php

namespace Tests;

use Exception;
use PHP\Manipulator\Config;
use PHP\Manipulator\Config\XmlConfig;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHPUnit_Framework_TestCase;

/**
 * @covers Tests\Testcase
 */
class TestCaseTest extends PHPUnit_Framework_TestCase
{
    public function testGetFixtureFileContent()
    {
        $test     = new TestCase();
        $filename = '/TokenFinder/SwitchFinder/input0.php';
        $content  = $test->getFixtureFileContent($filename);
        $this->assertSame(file_get_contents(TESTS_PATH.'/_fixtures/'.$filename), $content);
    }

    public function testGetFixtureFileContentThrowsExceptionOnNonExistingFile()
    {
        $test     = new TestCase();
        $filename = '/non-existing-file';
        $this->setExpectedException(
            Exception::class,
            $filename
        );
        $test->getFixtureFileContent($filename);
    }

    public function testGetResultFromContainer()
    {
        $test      = new TestCase();
        $t1        = Token::createFromValueAndType('<?php'.PHP_EOL, T_OPEN_TAG);
        $t2        = Token::createFromValueAndType('echo', T_ECHO);
        $t3        = Token::createFromValueAndType(' ', T_WHITESPACE);
        $t4        = Token::createFromValueAndType('$var', T_VARIABLE);
        $t5        = Token::createFromValue(';');
        $container = TokenContainer::factory([$t1, $t2, $t3, $t4, $t5]);
        $result    = $test->getResultFromContainer($container, 1, 3);
        $this->assertCount(3, $result->getTokens());
        $this->assertContains($t2, $result->getTokens());
        $this->assertContains($t3, $result->getTokens());
        $this->assertContains($t4, $result->getTokens());
    }

    public function testGetContainerFromFixture()
    {
        $this->markTestIncomplete('not implemented yet');
        $test      = new TestCase();
        $filename  = '/TokenFinder/SwitchFinder/input0';
        $container = $test->getContainerFromFixture($filename);
        $this->assertInstanceOf(TokenContainer::class, $container);
        $this->assertCount(91, $container);
    }

    public function testAssertTokenMatch()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    public function testAssertTokenContainerMatch()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    public function testCheckAsptags()
    {
        // not really testable because of setting ini-values is required, which fails for shorttags and asptags
        $this->markTestIncomplete('not implemented yet');
    }

    public function testCheckShorttags()
    {
        // not really testable because of setting ini-values is required, which fails for shorttags and asptags
        $this->markTestIncomplete('not implemented yet');
    }

    public function testGetConfig()
    {
        $test   = new TestCase();
        $config = $test->getConfig();
        $this->assertInstanceOf(Config::class, $config);
    }

    public function testGetXmlConfig()
    {
        $test   = new TestCase();
        $config = $test->getXmlConfig(1);
        $this->assertInstanceOf(XmlConfig::class, $config);
        $this->markTestIncomplete('test right file was loaded ?');
    }
}
