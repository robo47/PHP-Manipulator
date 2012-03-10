<?php

namespace Tests\PHP;

use PHP\Manipulator;
use Symfony\Component\Finder\Finder;

/**
 * @group Manipulator
 */
class ManipulatorTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator::__construct
     * @covers \PHP\Manipulator::getActions
     */
    public function testDefaultConstruct()
    {
        $manipulator = new Manipulator();
        $this->assertEquals(array(), $manipulator->getActions());
    }

    /**
     * @covers \PHP\Manipulator::__construct
     * @covers \PHP\Manipulator::getActions
     */
    public function testConstructAddsActions()
    {
        $addActions = array(
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\RemoveComments(),

        );

        $manipulator = new Manipulator($addActions);

        $actions = $manipulator->getActions();

        $this->assertCount(3, $actions, 'Wrong actions count');
        $this->assertContains($addActions[0], $actions, 'Action1 not found');
        $this->assertContains($addActions[1], $actions, 'Action2 not found');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');
    }

    /**
     * @covers \PHP\Manipulator::__construct
     * @covers \PHP\Manipulator::getFiles
     */
    public function testConstructAddsFiles()
    {
        $files = array(
            'some File',
            'another File',
        );

        $manipulator = new Manipulator(array(), $files);

        $this->assertCount(2, $manipulator->getFiles());
        $this->assertContains($files[0], $manipulator->getFiles());
        $this->assertContains($files[1], $manipulator->getFiles());
    }

    /**
     * @covers \PHP\Manipulator::addAction
     * @covers \PHP\Manipulator::getActions
     */
    public function testAddAction()
    {
        $action = new \PHP\Manipulator\Action\RemoveComments();
        $manipulator = new Manipulator();
        $fluent = $manipulator->addAction($action);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $actions = $manipulator->getActions();

        $this->assertCount(1, $actions, 'Wrong actions count');
        $this->assertContains($action, $actions, 'Action not found');
    }

    /**
     * @covers \PHP\Manipulator::addActions
     * @covers \PHP\Manipulator::getActions
     */
    public function testAddActions()
    {
        $addActions = array(
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\RemoveComments(),

        );
        $manipulator = new Manipulator();
        $fluent = $manipulator->addActions($addActions);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $actions = $manipulator->getActions();

        $this->assertCount(3, $actions, 'Wrong actions count');
        $this->assertContains($addActions[0], $actions, 'Action1 not found');
        $this->assertContains($addActions[1], $actions, 'Action2 not found');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');
    }

    /**
     * @covers \PHP\Manipulator::removeAction
     * @covers \PHP\Manipulator::getActions
     */
    public function testRemoveAction()
    {
        $addActions = array(
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\RemoveComments(),

        );
        $manipulator = new Manipulator($addActions);
        $fluent = $manipulator->removeAction($addActions[1]);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $actions = $manipulator->getActions();

        $this->assertCount(2, $actions, 'Wrong actions count');
        $this->assertContains($addActions[0], $actions, 'Action1 not found');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');

        $manipulator->removeAction($addActions[0]);
        $manipulator->removeAction($addActions[2]);

        $actions = $manipulator->getActions();

        $this->assertCount(0, $actions, 'Wrong actions count');
    }

    /**
     * @covers \PHP\Manipulator::removeAllActions
     * @covers \PHP\Manipulator::getActions
     */
    public function testRemoveAllActions()
    {
        $addActions = array(
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\RemoveComments(),

        );
        $manipulator = new Manipulator($addActions);
        $fluent = $manipulator->removeAllActions();
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $actions = $manipulator->getActions();

        $this->assertCount(0, $actions, 'Wrong actions count');
    }

    /**
     * @covers \PHP\Manipulator::removeActionByClassname
     * @covers \PHP\Manipulator::getActions
     */
    public function testRemoveActionByClassname()
    {
        $addActions = array(
            new \PHP\Manipulator\Action\RemoveComments(),
            new \PHP\Manipulator\Action\ChangeLineEndings(),
            new \PHP\Manipulator\Action\RemoveTrailingWhitespace(),

        );
        $manipulator = new Manipulator($addActions);
        $fluent = $manipulator->removeActionByClassname('PHP\\Manipulator\\Action\\ChangeLineEndings');
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $actions = $manipulator->getActions();

        $this->assertCount(2, $actions, 'Wrong actions count');
        $this->assertContains($addActions[0], $actions, 'Action1 not found');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');

        $manipulator->removeActionByClassname('PHP\\Manipulator\\Action\\RemoveComments');

        $actions = $manipulator->getActions();

        $this->assertCount(1, $actions, 'Wrong actions count');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');

        $manipulator->removeActionByClassname('PHP\\Manipulator\\Action\\RemoveTrailingWhitespace');

        $actions = $manipulator->getActions();

        $this->assertCount(0, $actions, 'Wrong actions count');
    }

    /**
     * @covers \PHP\Manipulator::addFiles
     * @covers \PHP\Manipulator::getFiles
     */
    public function testAddFilesWithIteratorAndGetFiles()
    {
        $finder = new Finder();
        $iterator = $finder->files()->in(TESTS_PATH . '/Foo/');

        $manipulator = new Manipulator();
        $manipulator->addFiles($iterator->getIterator());

        $this->assertCount(iterator_count($iterator), $manipulator->getFiles());

        foreach ($iterator as $file) {
            $this->assertContains($file->__toString(), $manipulator->getFiles());
        }
    }

    /**
     * @covers \PHP\Manipulator::addFiles
     * @covers \PHP\Manipulator::getFiles
     */
    public function testAddFilesWithStringAndGetFiles()
    {
        $file = 'baa';

        $manipulator = new Manipulator();
        $manipulator->addFiles($file);

        $this->assertCount(1, $manipulator->getFiles());
        $this->assertContains($file, $manipulator->getFiles());
    }

    /**
     * @covers \PHP\Manipulator::addFiles
     * @covers \PHP\Manipulator::getFiles
     */
    public function testAddFilesWithArrayAndGetFiles()
    {
        $files = array(
            'some File',
            'another File',
        );

        $manipulator = new Manipulator();
        $manipulator->addFiles($files);

        $this->assertCount(2, $manipulator->getFiles());
        $this->assertContains($files[0], $manipulator->getFiles());
        $this->assertContains($files[1], $manipulator->getFiles());
    }

    /**
     * @covers \PHP\Manipulator::removeAllFiles
     */
    public function testRemoveAllFiles()
    {
        $files = array(
            'some File',
            'another File',
        );

        $manipulator = new Manipulator();
        $manipulator->addFiles($files);

        $this->assertCount(2, $manipulator->getFiles());

        $manipulator->removeAllFiles();

        $this->assertCount(0, $manipulator->getFiles());
    }

    /**
     * @covers \PHP\Manipulator::addFile
     */
    public function testAddFile()
    {
        $manipulator = new Manipulator();
        $manipulator->addFile('foo');
        $manipulator->addFile('baa');

        $this->assertCount(2, $manipulator->getFiles());

        $this->assertContains('foo', $manipulator->getFiles());
        $this->assertContains('baa', $manipulator->getFiles());
    }
}
