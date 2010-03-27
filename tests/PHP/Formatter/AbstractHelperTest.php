<?php

require_once 'PHP/Formatter/AbstractHelper.php';

class PHP_Formatter_NonAbstractHelper extends PHP_Formatter_AbstractHelper
{
    public $init = false;

    public function init()
    {
        $this->init = true;
    }
}

class PHP_Formatter_AbstractHelperTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_AbstractHelper
     */
    public function testAbstractClassAndMethods()
    {
        $reflection = new ReflectionClass('PHP_Formatter_AbstractHelper');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::__construct
     */
    public function testDefaultConstructor()
    {
        $rule = new PHP_Formatter_NonAbstractHelper();
        $this->assertEquals(array(), $rule->getOptions(), 'options don\'t match');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::__construct
     * @covers PHP_Formatter_AbstractHelper::init
     */
    public function testConstructorCallsInit()
    {
        $rule = new PHP_Formatter_NonAbstractHelper();
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
     * @covers PHP_Formatter_AbstractHelper::__construct
     * @dataProvider constructorOptionsProvider
     */
    public function testConstructorSetsOptions($options)
    {
        $rule = new PHP_Formatter_NonAbstractHelper($options);
        $this->assertEquals($options, $rule->getOptions(), 'options don\'t match');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::addOptions
     * @covers PHP_Formatter_AbstractHelper::getOptions
     */
    public function testAddOptionsAndGetOptions()
    {
        $options = array(
            'baa' => 'foo',
            'blub' => 'bla',
        );
        $rule = new PHP_Formatter_NonAbstractHelper(array('foo' => 'bla'));
        $fluent = $rule->addOptions($options);
        $this->assertSame($fluent, $rule, 'No fluent interface');

        $this->assertEquals(3, count($rule->getOptions()), 'Wrong options count');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::setOption
     * @covers PHP_Formatter_AbstractHelper::getOption
     */
    public function testSetOptionAndGetOption()
    {
        $rule = new PHP_Formatter_NonAbstractHelper();
        $fluent = $rule->setOption('baa', 'foo');
        $this->assertSame($fluent, $rule, 'No fluent interface');
        $this->assertEquals('foo', $rule->getOption('baa'), 'Wrong value');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::getOption
     * @covers PHP_Formatter_Exception
     */
    public function testGetOptionThrowsExceptionOnNonExistingOption()
    {
        $rule = new PHP_Formatter_NonAbstractHelper();
        try {
            $rule->getOption('foo');
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("Option 'foo' not found", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::hasOption
     */
    public function testHasOption()
    {
        $rule = new PHP_Formatter_NonAbstractHelper(array('foo' => 'bla'));
        $this->assertTrue($rule->hasOption('foo'));
        $this->assertFalse($rule->hasOption('blub'));
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::evaluateConstraint
     */
    public function testEvaluateConstraint()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::manipulateToken
     */
    public function testManipulateToken()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::getClassInstance
     */
    public function testGetClassInstanceWithAutoPrefix()
    {
        $rule = new PHP_Formatter_NonAbstractHelper();
        $instance = $rule->getClassInstance('Dummy1', 'PHP_Formatter_Temp_', true);
        $this->assertTrue(class_exists('PHP_Formatter_Temp_Dummy1', false), 'Class not loaded');
        $this->assertType('PHP_Formatter_Temp_Dummy1', $instance, 'Wrong type');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::getClassInstance
     */
    public function testGetClassInstanceWithoutAutoPrefix()
    {
        $rule = new PHP_Formatter_NonAbstractHelper();
        $instance = $rule->getClassInstance('PHP_Formatter_Temp_Dummy2', '', false);
        $this->assertTrue(class_exists('PHP_Formatter_Temp_Dummy2', false), 'Class not loaded');
        $this->assertType('PHP_Formatter_Temp_Dummy2', $instance, 'Wrong type');
    }

    /**
     * @covers PHP_Formatter_AbstractHelper::getClassInstance
     */
    public function testGetClassInstanceWithDirectClass()
    {
        $class = new PHP_Formatter_Temp_Dummy2();
        $rule = new PHP_Formatter_NonAbstractHelper();
        $instance = $rule->getClassInstance($class, '', false);
        $this->assertSame($class, $instance);
    }
}