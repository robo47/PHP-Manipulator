<?php

class PHP_FormatterTest extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter::__construct
     * @covers PHP_Formatter::getRules
     */
    public function testDefaultConstruct()
    {
        $formatter = new PHP_Formatter();
        $this->assertEquals(array(), $formatter->getRules());
    }

    /**
     * @covers PHP_Formatter::__construct
     * @covers PHP_Formatter::getRules
     */
    public function testConstructAddsRules()
    {
        $addRules = array(
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_RemoveComments(),

        );

        $formatter = new PHP_Formatter($addRules);

        $rules = $formatter->getRules();

        $this->assertEquals(3, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[1], $rules, 'Rule2 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');
    }

    /**
     * @covers PHP_Formatter::addRule
     * @covers PHP_Formatter::getRules
     */
    public function testAddRule()
    {
        $rule = new PHP_Formatter_Rule_RemoveComments();
        $formatter = new PHP_Formatter();
        $fluent = $formatter->addRule($rule);
        $this->assertSame($fluent, $formatter, 'No fluent interface');
        $rules = $formatter->getRules();

        $this->assertEquals(1, count($rules), 'Wrong rules count');
        $this->assertContains($rule, $rules, 'Rule not found');
    }

    /**
     * @covers PHP_Formatter::addRules
     * @covers PHP_Formatter::getRules
     */
    public function testAddRules()
    {
        $addRules = array(
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_RemoveComments(),

        );
        $formatter = new PHP_Formatter();
        $fluent = $formatter->addRules($addRules);
        $this->assertSame($fluent, $formatter, 'No fluent interface');
        $rules = $formatter->getRules();

        $this->assertEquals(3, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[1], $rules, 'Rule2 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');
    }

    /**
     * @covers PHP_Formatter::removeRule
     * @covers PHP_Formatter::getRules
     */
    public function testRemoveRule()
    {
        $addRules = array(
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_RemoveComments(),

        );
        $formatter = new PHP_Formatter($addRules);
        $fluent = $formatter->removeRule($addRules[1]);
        $this->assertSame($fluent, $formatter, 'No fluent interface');

        $rules = $formatter->getRules();

        $this->assertEquals(2, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $formatter->removeRule($addRules[0]);
        $formatter->removeRule($addRules[2]);

        $rules = $formatter->getRules();

        $this->assertEquals(0, count($rules), 'Wrong rules count');
    }

    /**
     * @covers PHP_Formatter::removeAllRules
     * @covers PHP_Formatter::getRules
     */
    public function testRemoveAllRules()
    {
        $addRules = array(
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_RemoveComments(),

        );
        $formatter = new PHP_Formatter($addRules);
        $fluent = $formatter->removeAllRules();
        $this->assertSame($fluent, $formatter, 'No fluent interface');

        $rules = $formatter->getRules();

        $this->assertEquals(0, count($rules), 'Wrong rules count');
    }

    /**
     * @covers PHP_Formatter::removeRuleByClassname
     * @covers PHP_Formatter::getRules
     */
    public function testRemoveRuleByClassname()
    {
        $addRules = array(
            new PHP_Formatter_Rule_RemoveComments(),
            new PHP_Formatter_Rule_ChangeLineEndings(),
            new PHP_Formatter_Rule_RemoveTrailingWhitespace(),

        );
        $formatter = new PHP_Formatter($addRules);
        $fluent = $formatter->removeRuleByClassname('PHP_Formatter_Rule_ChangeLineEndings');
        $this->assertSame($fluent, $formatter, 'No fluent interface');

        $rules = $formatter->getRules();

        $this->assertEquals(2, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[0], $rules, 'Rule1 not found');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $formatter->removeRuleByClassname('PHP_Formatter_Rule_RemoveComments');

        $rules = $formatter->getRules();

        $this->assertEquals(1, count($rules), 'Wrong rules count');
        $this->assertContains($addRules[2], $rules, 'Rule3 not found');

        $formatter->removeRuleByClassname('PHP_Formatter_Rule_RemoveTrailingWhitespace');

        $rules = $formatter->getRules();

        $this->assertEquals(0, count($rules), 'Wrong rules count');
    }
}