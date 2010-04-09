<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator;
use PHP\Manipulator\Cli;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenContainerIterator
 */
class CliTest extends \Tests\TestCase
{

    public function setUp()
    {
        $this->setUseOutputBuffering(true);
    }

    public function tearDown()
    {
        $this->setUseOutputBuffering(false);
    }

    /**
     * @covers \PHP\Manipulator\Cli::__construct
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::getParams
     */
    public function testConstructorSavesParams()
    {
        $params = array(
            'foo',
            'baa'
        );
        $cli = new Cli($params);
        $this->assertEquals($params, $cli->getParams());
    }

    /**
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::getConsoleInput
     */
    public function testConstructorInitsConsoleInput()
    {
        $cli = new Cli();
        $this->assertType('ezcConsoleInput', $cli->getConsoleInput());
    }

    /**
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::getConsoleOutput
     */
    public function testConstructorInitsConsoleOutput()
    {
        $cli = new Cli();
        $this->assertType('ezcConsoleOutput', $cli->getConsoleOutput());
    }

    /**
     * @covers \PHP\Manipulator\Cli::getHeader
     */
    public function testGetHeader()
    {
        $cli = new Cli();
        $this->assertEquals(
            'PHP Manipulator ' . Manipulator::VERSION . ' by Benjamin Steininger' . PHP_EOL,
            $cli->getHeader()
        );
    }

    /**
     * @covers \PHP\Manipulator\Cli::getFooter
     */
    public function testGetFooter()
    {
        $cli = new Cli();
        $footer = '~Time: (\d+)s' . PHP_EOL;
        $footer .= 'Memory: (\d+).(\d+)kb' . PHP_EOL . '~';
        $match = preg_match($footer, $cli->getFooter());
        $this->assertTrue(false !== $match);
    }

    /**
     * @covers \PHP\Manipulator\Cli::getStartTime
     */
    public function testGetStartTime()
    {
        $cli = new Cli();
        $this->assertType('float', $cli->getStartTime());
    }

    /**
     * @covers \PHP\Manipulator\Cli::getAction
     */
    public function testGetAction()
    {
        $cli = new Cli();
        $action = $cli->getAction('ShowTokens');
        $this->assertType('\PHP\Manipulator\Cli\Action', $action);
        $this->assertSame($cli, $action->getCli());
    }

    /**
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::setConsoleInput
     */
    public function testSetConsoleInput()
    {
        $cli = new Cli();
        $consoleInput = new \ezcConsoleInput();
        $cli->setConsoleInput($consoleInput);
        $this->assertSame($consoleInput, $cli->getConsoleInput());
    }

    /**
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::setConsoleOutput
     */
    public function testSetConsoleOutput()
    {
        $cli = new Cli();
        $consoleOutput = new \ezcConsoleOutput();
        $cli->setConsoleOutput($consoleOutput);
        $this->assertSame($consoleOutput, $cli->getConsoleOutput());
    }

    /**
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::getConsoleOptions
     */
    public function testGetConsoleOptions()
    {
        $cli = new Cli();
        $cli->getConsoleOptions();
    }

    /**
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::run
     */
    public function testRun()
    {
        $this->markTestIncomplete('not implemented yet');
    }
}