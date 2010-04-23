<?php

namespace Tests\PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli;
use PHP\Manipulator;
use PHP\Manipulator\Cli\Action\Version;

/**
 * @group TokenContainerIterator
 */
class VersionTest extends \Tests\TestCase
{
    
    public function setUp()
    {
        ob_start();
    }
    
    public function tearDown()
    {
        \ob_clean();
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\Version::run
     */
    public function testRun()
    {
        $cli = new Cli();
        $action = new Version($cli);
        $action->run();
        $output = \ob_get_contents();
        $this->assertSame(
                PHP_EOL . 'Version: ' . Manipulator::VERSION . ' (' . Manipulator::GITHASH . ')' . PHP_EOL .
                'Author: Benjamin Steininger <robo47@robo47.net>' . PHP_EOL .
                'Homepage: TBD' . PHP_EOL .
                'License: New BSD License' . PHP_EOL . PHP_EOL, $output);
    }
}