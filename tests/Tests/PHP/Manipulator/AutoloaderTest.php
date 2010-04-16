<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Autoloader;

/**
 * @runTestsInSeparateProcesses
 *
 * @group Autoloader
 */
class AutoloaderTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Autoloader::autoload
     */
    public function testAutoload()
    {
        $autoloader = new Autoloader();
        $this->assertFalse(class_exists('Baa\Autoloader\Foo', false));
        $autoloader->autoload('Baa\Autoloader\Foo');
        $this->assertTrue(class_exists('Baa\Autoloader\Foo', false));
    }

    /**
     * @covers \PHP\Manipulator\Autoloader::register
     */
    public function testRegisterAutoloader()
    {
        $autoloadersBefore = \spl_autoload_functions();
        Autoloader::register();
        $autoloadersAfter = \spl_autoload_functions();
        // check there is one more autoloader
        $this->assertEquals(1, (count($autoloadersAfter) - count($autoloadersBefore)));
        // check it is an instance of \PHP\Manipulator\Autoloader
        $this->assertType('\PHP\Manipulator\Autoloader', $autoloadersAfter[count($autoloadersBefore)][0]);
    }

    /**
     * @covers \PHP\Manipulator\Autoloader::autoload
     */
    public function testAutoloaderworksWithezComponentsClasses()
    {
        $autoloader = new \PHP\Manipulator\Autoloader();
        $this->assertFalse(class_exists('ezcConsoleDialogViewer', false));
        $autoloader->autoload('ezcConsoleDialogViewer');
        $this->assertTrue(class_exists('ezcConsoleDialogViewer', false));
    }
}