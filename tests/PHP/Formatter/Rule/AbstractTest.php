
<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_NonAbstract extends PHP_Formatter_Rule_Abstract
{
    public $init = false;

    public function init()
    {
        $this->init = true;
    }

    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens)
    {
    }
}

class PHP_Formatter_Rule_AbstractTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_Abstract
     */
    public function testAbstractClassAndMethods()
    {
        $reflection = new ReflectionClass('PHP_Formatter_Rule_Abstract');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
        $this->assertTrue($reflection->isSubclassOf('PHP_Formatter_Rule_Interface'), 'Class not implements PHP_Formatter_Rule_Interface');
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::__construct
     */
    public function testDefaultConstructor()
    {
        $rule = new PHP_Formatter_Rule_NonAbstract();
        $this->assertEquals(array(), $rule->getOptions(), 'options don\'t match');
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::__construct
     * @covers PHP_Formatter_Rule_Abstract::init
     */
    public function testConstructorCallsInit()
    {
        $rule = new PHP_Formatter_Rule_NonAbstract();
        $this->assertTrue($rule->init, 'init is not true');
    }

    /**
     * @return array
     */
    public function constructorOptionsProvider()
    {
        $data = array();

        $data[] = array(array());
        $data[] = array(array('baa' => 'foo'));
        $data[] = array(array('baa' => 'foo', 'blub' => 'bla'));

        return $data;
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::__construct
     * @dataProvider constructorOptionsProvider
     */
    public function testConstructorSetsOptions($options)
    {
        $rule = new PHP_Formatter_Rule_NonAbstract($options);
        $this->assertEquals($options, $rule->getOptions(), 'options don\'t match');
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::addOptions
     * @covers PHP_Formatter_Rule_Abstract::getOptions
     */
    public function testAddOptionsAndGetOptions()
    {
        $options = array(
            'baa' => 'foo',
            'blub' => 'bla',
        );
        $rule = new PHP_Formatter_Rule_NonAbstract(array('foo' => 'bla'));
        $fluent = $rule->addOptions($options);
        $this->assertSame($fluent, $rule, 'No fluent interface');

        $this->assertEquals(3, count($rule->getOptions()), 'Wrong options count');
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::setOption
     * @covers PHP_Formatter_Rule_Abstract::getOption
     */
    public function testSetOptionAndGetOption()
    {
        $rule = new PHP_Formatter_Rule_NonAbstract();
        $fluent = $rule->setOption('baa', 'foo');
        $this->assertSame($fluent, $rule, 'No fluent interface');
        $this->assertEquals('foo', $rule->getOption('baa'), 'Wrong value');
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::getOption
     * @covers PHP_Formatter_Exception
     */
    public function testGetOptionThrowsExceptionOnNonExistingOption()
    {
        $rule = new PHP_Formatter_Rule_NonAbstract();
        try {
            $rule->getOption('foo');
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("Option 'foo' not found", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::hasOption
     */
    public function testHasOption()
    {
        $rule = new PHP_Formatter_Rule_NonAbstract(array('foo' => 'bla'));
        $this->assertTrue($rule->hasOption('foo'));
        $this->assertFalse($rule->hasOption('blub'));
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::evaluateConstraint
     */
    public function testEvaluateConstraint()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::manipulateToken
     */
    public function testManipulateToken()
    {
        $this->markTestIncomplete('not implemented yet');
    }
}