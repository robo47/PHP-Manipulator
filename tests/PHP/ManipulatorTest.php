<?php

namespace Tests\PHP;

use PHP\Manipulator;

/**
 * @group PHP_Manipulator
 */
class ManipulatorTest extends \Tests\TestCase
{

    /**
     * @covers PHP\Manipulator::__construct
     * @covers PHP\Manipulator::getRules
     */
    public function testDefaultConstruct()
    {
        $manipulator = new Manipulator();
        $this->assertEquals(array(), $manipulator->getRules());
    }

    /**
     * @covers PHP\Manipulator::__construct
     * @covers PHP\Manipulator::getRules
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

        $this->assertEquals(3, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[1], $rules, 'Rule2 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');
    }

    /**
     * @covers PHP\Manipulator::addRule
     * @covers PHP\Manipulator::getRules
     */
    public function testAddRule()
    {
        $rule = new \PHP\Manipulator\Rule\RemoveComments();
        $manipulator = new Manipulator();
        $fluent = $manipulator->addRule($rule);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $rules = $manipulator->getRules();

        $this->assertEquals(1, count($rules), 'Wrong rules count');
        $this->assertContains($rule, $rules, 'Rule not found');
    }

    /**
     * @covers PHP\Manipulator::addRules
     * @covers PHP\Manipulator::getRules
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

        $this->assertEquals(3, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[1], $rules, 'Rule2 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');
    }

    /**
     * @covers PHP\Manipulator::removeRule
     * @covers PHP\Manipulator::getRules
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

        $this->assertEquals(2, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $manipulator->removeRule($addRules[0]);
        $manipulator->removeRule($addRules[2]);

        $rules = $manipulator->getRules();

        $this->assertEquals(0, count($rules), 'Wrong rules count');
    }

    /**
     * @covers PHP\Manipulator::removeAllRules
     * @covers PHP\Manipulator::getRules
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

        $this->assertEquals(0, count($rules), 'Wrong rules count');
    }

    /**
     * @covers PHP\Manipulator::removeRuleByClassname
     * @covers PHP\Manipulator::getRules
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

        $this->assertEquals(2, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $manipulator->removeRuleByClassname('\PHP\Manipulator\Rule\RemoveComments');

        $rules = $manipulator->getRules();

        $this->assertEquals(1, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $manipulator->removeRuleByClassname('PHP\Manipulator\Rule\RemoveTrailingWhitespace');

        $rules = $manipulator->getRules();

        $this->assertEquals(0, count($rules), 'Wrong rules count');
    }
}