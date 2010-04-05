<?php

class PHP_ManipulatorTest extends TestCase
{

    /**
     * @covers PHP_Manipulator::__construct
     * @covers PHP_Manipulator::getRules
     */
    public function testDefaultConstruct()
    {
        $manipulator = new PHP_Manipulator();
        $this->assertEquals(array(), $manipulator->getRules());
    }

    /**
     * @covers PHP_Manipulator::__construct
     * @covers PHP_Manipulator::getRules
     */
    public function testConstructAddsRules()
    {
        $addRules = array(
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_RemoveComments(),

        );

        $manipulator = new PHP_Manipulator($addRules);

        $rules = $manipulator->getRules();

        $this->assertEquals(3, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[1], $rules, 'Rule2 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');
    }

    /**
     * @covers PHP_Manipulator::addRule
     * @covers PHP_Manipulator::getRules
     */
    public function testAddRule()
    {
        $rule = new PHP_Manipulator_Rule_RemoveComments();
        $manipulator = new PHP_Manipulator();
        $fluent = $manipulator->addRule($rule);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $rules = $manipulator->getRules();

        $this->assertEquals(1, count($rules), 'Wrong rules count');
        $this->assertContains($rule, $rules, 'Rule not found');
    }

    /**
     * @covers PHP_Manipulator::addRules
     * @covers PHP_Manipulator::getRules
     */
    public function testAddRules()
    {
        $addRules = array(
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_RemoveComments(),

        );
        $manipulator = new PHP_Manipulator();
        $fluent = $manipulator->addRules($addRules);
        $this->assertSame($fluent, $manipulator, 'No fluent interface');
        $rules = $manipulator->getRules();

        $this->assertEquals(3, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[1], $rules, 'Rule2 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');
    }

    /**
     * @covers PHP_Manipulator::removeRule
     * @covers PHP_Manipulator::getRules
     */
    public function testRemoveRule()
    {
        $addRules = array(
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_RemoveComments(),

        );
        $manipulator = new PHP_Manipulator($addRules);
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
     * @covers PHP_Manipulator::removeAllRules
     * @covers PHP_Manipulator::getRules
     */
    public function testRemoveAllRules()
    {
        $addRules = array(
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_RemoveComments(),

        );
        $manipulator = new PHP_Manipulator($addRules);
        $fluent = $manipulator->removeAllRules();
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $rules = $manipulator->getRules();

        $this->assertEquals(0, count($rules), 'Wrong rules count');
    }

    /**
     * @covers PHP_Manipulator::removeRuleByClassname
     * @covers PHP_Manipulator::getRules
     */
    public function testRemoveRuleByClassname()
    {
        $addRules = array(
            new PHP_Manipulator_Rule_RemoveComments(),
            new PHP_Manipulator_Rule_ChangeLineEndings(),
            new PHP_Manipulator_Rule_RemoveTrailingWhitespace(),

        );
        $manipulator = new PHP_Manipulator($addRules);
        $fluent = $manipulator->removeRuleByClassname('PHP_Manipulator_Rule_ChangeLineEndings');
        $this->assertSame($fluent, $manipulator, 'No fluent interface');

        $rules = $manipulator->getRules();

        $this->assertEquals(2, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $manipulator->removeRuleByClassname('PHP_Manipulator_Rule_RemoveComments');

        $rules = $manipulator->getRules();

        $this->assertEquals(1, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $manipulator->removeRuleByClassname('PHP_Manipulator_Rule_RemoveTrailingWhitespace');

        $rules = $manipulator->getRules();

        $this->assertEquals(0, count($rules), 'Wrong rules count');
    }
}