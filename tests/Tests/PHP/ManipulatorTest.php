<?php

namespace Tests\PHP;

use PHP\Manipulator;
use PHP\Manipulator\Action\ChangeLineEndings;
use PHP\Manipulator\Action\RemoveComments;
use PHP\Manipulator\Action\RemoveTrailingWhitespace;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator
 */
class ManipulatorTest extends TestCase
{
    public function testDefaultConstruct()
    {
        $manipulator = new Manipulator();
        $this->assertSame([], $manipulator->getActions());
    }

    public function testConstructAddsActions()
    {
        $addActions = [
            new RemoveComments(),
            new RemoveComments(),
            new RemoveComments(),
        ];

        $manipulator = new Manipulator($addActions);

        $actions = $manipulator->getActions();

        $this->assertCount(3, $actions, 'Wrong actions count');
        $this->assertContains($addActions[0], $actions, 'Action1 not found');
        $this->assertContains($addActions[1], $actions, 'Action2 not found');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');
    }

    public function testConstructAddsFiles()
    {
        $files = [
            'some File',
            'another File',
        ];

        $manipulator = new Manipulator([], $files);

        $this->assertCount(2, $manipulator->getFiles());
        $this->assertContains($files[0], $manipulator->getFiles());
        $this->assertContains($files[1], $manipulator->getFiles());
    }

    public function testAddAction()
    {
        $action      = new RemoveComments();
        $manipulator = new Manipulator();
        $fluent      = $manipulator->addAction($action);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $actions = $manipulator->getActions();

        $this->assertCount(1, $actions, 'Wrong actions count');
        $this->assertContains($action, $actions, 'Action not found');
    }

    public function testAddActions()
    {
        $addActions = [
            new RemoveComments(),
            new RemoveComments(),
            new RemoveComments(),

        ];
        $manipulator = new Manipulator();
        $fluent      = $manipulator->addActions($addActions);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $actions = $manipulator->getActions();

        $this->assertCount(3, $actions, 'Wrong actions count');
        $this->assertContains($addActions[0], $actions, 'Action1 not found');
        $this->assertContains($addActions[1], $actions, 'Action2 not found');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');
    }

    public function testRemoveAction()
    {
        $addActions = [
            new RemoveComments(),
            new RemoveComments(),
            new RemoveComments(),

        ];
        $manipulator = new Manipulator($addActions);
        $fluent      = $manipulator->removeAction($addActions[1]);
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

    public function testRemoveAllActions()
    {
        $addActions = [
            new RemoveComments(),
            new RemoveComments(),
            new RemoveComments(),

        ];
        $manipulator = new Manipulator($addActions);
        $fluent      = $manipulator->removeAllActions();
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $actions = $manipulator->getActions();

        $this->assertCount(0, $actions, 'Wrong actions count');
    }

    public function testRemoveActionByClassname()
    {
        $addActions = [
            new RemoveComments(),
            new ChangeLineEndings(),
            new RemoveTrailingWhitespace(),

        ];
        $manipulator = new Manipulator($addActions);
        $fluent      = $manipulator->removeActionByClassname(ChangeLineEndings::class);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $actions = $manipulator->getActions();

        $this->assertCount(2, $actions, 'Wrong actions count');
        $this->assertContains($addActions[0], $actions, 'Action1 not found');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');

        $manipulator->removeActionByClassname(RemoveComments::class);

        $actions = $manipulator->getActions();

        $this->assertCount(1, $actions, 'Wrong actions count');
        $this->assertContains($addActions[2], $actions, 'Action3 not found');

        $manipulator->removeActionByClassname(RemoveTrailingWhitespace::class);

        $actions = $manipulator->getActions();

        $this->assertCount(0, $actions, 'Wrong actions count');
    }

    public function testAddFilesWithIteratorAndGetFiles()
    {
        $finder   = new Finder();
        $iterator = $finder->files()->in(TESTS_PATH.'/Foo/');

        $manipulator = new Manipulator();
        $manipulator->addFiles($iterator->getIterator());

        $this->assertCount(iterator_count($iterator), $manipulator->getFiles());

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            $this->assertContains((string) $file, $manipulator->getFiles());
        }
    }

    public function testAddFilesWithArrayAndGetFiles()
    {
        $files = [
            'some File',
            'another File',
        ];

        $manipulator = new Manipulator();
        $manipulator->addFiles($files);

        $this->assertCount(2, $manipulator->getFiles());
        $this->assertContains($files[0], $manipulator->getFiles());
        $this->assertContains($files[1], $manipulator->getFiles());
    }

    public function testRemoveAllFiles()
    {
        $files = [
            'some File',
            'another File',
        ];

        $manipulator = new Manipulator();
        $manipulator->addFiles($files);

        $this->assertCount(2, $manipulator->getFiles());

        $manipulator->removeAllFiles();

        $this->assertCount(0, $manipulator->getFiles());
    }

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
