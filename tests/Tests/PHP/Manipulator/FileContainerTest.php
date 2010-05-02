<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group TokenContainer
 * @group FileContainer
 */
class FileContainerTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\FileContainer
     */
    public function testContainer()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\FileContainer');
        $this->assertEquals('PHP\Manipulator\TokenContainer', $reflection->getParentClass()->getName());
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::__construct
     */
    public function testConstruct()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::__construct
     */
    public function testConstructThrowsExceptionIfFileNotExists()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::__construct
     */
    public function testConstructThrowsExceptionIfFileIsNoFile()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::save
     */
    public function testSave()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::saveTo
     */
    public function testSaveTo()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::save
     */
    public function testSaveThrowsExceptionIfFileIsNotWriteable()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::saveTo
     */
    public function testSaveToThrowsExceptionIfFileIsNotWriteable()
    {
        $this->markTestIncomplete('not implemented yet');
    }
}