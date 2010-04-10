<?php

namespace Tests\PHP;

use PHP\Manipulator;

/**
 * @group PHP_Manipulator
 */
class ManipulatorTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator::__construct
     * @covers \PHP\Manipulator::getRules
     */
    public function testDefaultConstruct()
    {
        $manipulator = new Manipulator();
        $this->assertEquals(array(), $manipulator->getRules());
    }

    /**
     * @covers \PHP\Manipulator::__construct
     * @covers \PHP\Manipulator::getRules
     */
    public function testConstructAddsRules()
    {
        $addRules = array(
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\RemoveComments(),

        );

        $manipulator = new Manipulator($addRules);

        $rules = $manipulator->getRules();

        $this->assertCount(3, $rules, 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[1], $rules, 'Rule2 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');
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
     * @covers \PHP\Manipulator::addRule
     * @covers \PHP\Manipulator::getRules
     */
    public function testAddRule()
    {
        $rule = new \PHP\Manipulator\Rule\RemoveComments();
        $manipulator = new Manipulator();
        $fluent = $manipulator->addRule($rule);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $rules = $manipulator->getRules();

        $this->assertCount(1, $rules, 'Wrong rules count');
        $this->assertContains($rule, $rules, 'Rule not found');
    }

    /**
     * @covers \PHP\Manipulator::addRules
     * @covers \PHP\Manipulator::getRules
     */
    public function testAddRules()
    {
        $addRules = array(
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\RemoveComments(),

        );
        $manipulator = new Manipulator();
        $fluent = $manipulator->addRules($addRules);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $rules = $manipulator->getRules();

        $this->assertCount(3, $rules, 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[1], $rules, 'Rule2 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');
    }

    /**
     * @covers \PHP\Manipulator::removeRule
     * @covers \PHP\Manipulator::getRules
     */
    public function testRemoveRule()
    {
        $addRules = array(
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\RemoveComments(),

        );
        $manipulator = new Manipulator($addRules);
        $fluent = $manipulator->removeRule($addRules[1]);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $rules = $manipulator->getRules();

        $this->assertCount(2, $rules, 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $manipulator->removeRule($addRules[0]);
        $manipulator->removeRule($addRules[2]);

        $rules = $manipulator->getRules();

        $this->assertCount(0, $rules, 'Wrong rules count');
    }

    /**
     * @covers \PHP\Manipulator::removeAllRules
     * @covers \PHP\Manipulator::getRules
     */
    public function testRemoveAllRules()
    {
        $addRules = array(
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\RemoveComments(),

        );
        $manipulator = new Manipulator($addRules);
        $fluent = $manipulator->removeAllRules();
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $rules = $manipulator->getRules();

        $this->assertCount(0, $rules, 'Wrong rules count');
    }

    /**
     * @covers \PHP\Manipulator::removeRuleByClassname
     * @covers \PHP\Manipulator::getRules
     */
    public function testRemoveRuleByClassname()
    {
        $addRules = array(
            new \PHP\Manipulator\Rule\RemoveComments(),
            new \PHP\Manipulator\Rule\ChangeLineEndings(),
            new \PHP\Manipulator\Rule\RemoveTrailingWhitespace(),

        );
        $manipulator = new Manipulator($addRules);
        $fluent = $manipulator->removeRuleByClassname('PHP\Manipulator\Rule\ChangeLineEndings');
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $rules = $manipulator->getRules();

        $this->assertCount(2, $rules, 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $manipulator->removeRuleByClassname('\PHP\Manipulator\Rule\RemoveComments');

        $rules = $manipulator->getRules();

        $this->assertCount(1, $rules, 'Wrong rules count');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $manipulator->removeRuleByClassname('PHP\Manipulator\Rule\RemoveTrailingWhitespace');

        $rules = $manipulator->getRules();

        $this->assertCount(0, $rules, 'Wrong rules count');
    }

    /**
     * @covers \PHP\Manipulator::addFiles
     * @covers \PHP\Manipulator::getFiles
     */
    public function testAddFilesWithIteratorAndGetFiles()
    {
        $iterator = \File_Iterator_Factory::getFileIterator(BASE_PATH . '/library/');

        $manipulator = new Manipulator();
        $manipulator->addFiles($iterator);

        $this->assertCount(\iterator_count($iterator), $manipulator->getFiles());

        $iteratorArray = \iterator_to_array($iterator);

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