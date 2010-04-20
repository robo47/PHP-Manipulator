<?php

namespace Tests;

use Tests\Util;
use Tests\TestCase;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TestCase
 */
class TestCaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Tests\Testcase::getFixtureFileContent
     */
    public function testGetFixtureFileContent()
    {
        $test = new TestCase();
        $filename = '/TokenFinder/SwitchFinder/input0';
        $content = $test->getFixtureFileContent($filename);
        $this->assertEquals(file_get_contents(TESTS_PATH . '/_fixtures/' . $filename), $content);
    }
    /**
     * @covers Tests\Testcase::getFixtureFileContent
     */
    public function testGetFixtureFileContentThrowsExceptionOnNonExistingFile()
    {
        $test = new TestCase();
        $filename = '/non-existing-file';
        try {
            $test->getFixtureFileContent($filename);
            $this->fail('no exception thrown');
        } catch(\Exception $e) {
            $this->assertEquals('Fixture ' . $filename . ' not found', $e->getMessage());
        }
    }

    /**
     * @covers Tests\Testcase::getResultFromContainer
     */
    public function testGetResultFromContainer()
    {
        $test = new TestCase();
        $t1 = new Token("<?php\n", T_OPEN_TAG);
        $t2 = new Token("echo", T_ECHO);
        $t3 = new Token(" ", T_WHITESPACE);
        $t4 = new Token("\$var", T_VARIABLE);
        $t5 = new Token(";");
        $container = new TokenContainer(array($t1, $t2, $t3, $t4, $t5));
        $result = $test->getResultFromContainer($container, 1, 3);
        $this->assertEquals(3, count($result->getTokens()));
        $this->assertContains($t2, $result->getTokens());
        $this->assertContains($t3, $result->getTokens());
        $this->assertContains($t4, $result->getTokens());
    }

    /**
     * @covers Tests\Testcase::getContainerFromFixture
     */
    public function testGetContainerFromFixture()
    {
        $this->markTestIncomplete('not implemented yet');
        $test = new TestCase();
        $filename = '/TokenFinder/SwitchFinder/input0';
        $container = $test->getContainerFromFixture($filename);
        $this->assertType('\PHP\Manipulator\TokenContainer', $container);
        $this->assertCount(91, $container);
    }

    /**
     * @covers Tests\Testcase::assertCount
     */
    public function testAssertCount()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers Tests\Testcase::assertTokenMatch
     */
    public function testAssertTokenMatch()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers Tests\Testcase::assertTokenContainerMatch
     */
    public function testAssertTokenContainerMatch()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers Tests\Testcase::checkAsptags
     */
    public function testCheckAsptags()
    {
        // not really testable because of setting ini-values is required, which fails for shorttags and asptags
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers Tests\Testcase::checkShorttags
     */
    public function testCheckShorttags()
    {
        // not really testable because of setting ini-values is required, which fails for shorttags and asptags
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers Tests\Testcase::getConfig
     */
    public function testGetConfig()
    {
        $test = new TestCase();
        $config = $test->getConfig();
        $this->assertType('\PHP\Manipulator\Config', $config);
    }

    /**
     * @covers Tests\Testcase::getXmlConfig
     */
    public function testGetXmlConfig()
    {
        $test = new TestCase();
        $config = $test->getXmlConfig(0);
        $this->assertType('\PHP\Manipulator\Config\Xml', $config);
        $this->markTestIncomplete('test right file was loaded ?');
    }
}