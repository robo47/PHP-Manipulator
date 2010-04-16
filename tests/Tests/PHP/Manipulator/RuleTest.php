<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class NonAbstractRule extends Rule
{
    public $init = false;

    public function init()
    {
        $this->init = true;
    }
    
    public function apply(TokenContainer $container)
    {
        
    }
}

/**
 * @group Rule
 */
class RuleTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule
     */
    public function testClassMethods()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\Rule');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
        $methods = $reflection->getMethods();
        $applyMethod = $reflection->getMethod('apply');
        /* @var $applyMethod ReflectionMethod */
        $this->assertTrue($applyMethod->isAbstract());
        $this->assertSame('apply', $applyMethod->getName(), 'Method has wrong name');
        $this->assertSame(1, $applyMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $applyMethod->getParameters();
        $tokenParameter = $parameters[0];
        /* @var $tokenParameter ReflectionParameter */
        $this->assertSame('container', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertEquals('PHP\Manipulator\TokenContainer', $tokenParameter->getClass()->getName(), 'Parameter is not a PHP\Manipulator\TokenContainer');
        $this->assertFalse($tokenParameter->isPassedByReference(), 'Parameter is passed as reference');
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');
    }

    /**
     * @covers \PHP\Manipulator\Rule::__construct
     */
    public function testDefaultConstructor()
    {
        $abstractHelper = new NonAbstractRule();
        $this->assertEquals(array(), $abstractHelper->getOptions(), 'options don\'t match');
    }

    /**
     * @covers \PHP\Manipulator\Rule::__construct
     * @covers \PHP\Manipulator\Rule::init
     */
    public function testConstructorCallsInit()
    {
        $abstractHelper = new NonAbstractRule();
        $this->assertTrue($abstractHelper->init, 'init is not true');
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
     * @covers \PHP\Manipulator\Rule::__construct
     * @dataProvider constructorOptionsProvider
     */
    public function testConstructorSetsOptions($options)
    {
        $abstractHelper = new NonAbstractRule($options);
        $this->assertEquals($options, $abstractHelper->getOptions(), 'options don\'t match');
    }

    /**
     * @covers \PHP\Manipulator\Rule::addOptions
     * @covers \PHP\Manipulator\Rule::getOptions
     */
    public function testAddOptionsAndGetOptions()
    {
        $options = array(
            'baa' => 'foo',
            'blub' => 'bla',
        );
        $abstractHelper = new NonAbstractRule(array('foo' => 'bla'));
        $fluent = $abstractHelper->addOptions($options);
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');

        $this->assertCount(3, $abstractHelper->getOptions(), 'Wrong options count');
    }

    /**
     * @covers \PHP\Manipulator\Rule::setOption
     * @covers \PHP\Manipulator\Rule::getOption
     */
    public function testSetOptionAndGetOption()
    {
        $abstractHelper = new NonAbstractRule();
        $fluent = $abstractHelper->setOption('baa', 'foo');
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');
        $this->assertEquals('foo', $abstractHelper->getOption('baa'), 'Wrong value');
    }

    /**
     * @covers \PHP\Manipulator\Rule::getOption
     * @covers \Exception
     */
    public function testGetOptionThrowsExceptionOnNonExistingOption()
    {
        $abstractHelper = new NonAbstractRule();
        try {
            $abstractHelper->getOption('foo');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Option 'foo' not found", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Rule::hasOption
     */
    public function testHasOption()
    {
        $abstractHelper = new NonAbstractRule(array('foo' => 'bla'));
        $this->assertTrue($abstractHelper->hasOption('foo'));
        $this->assertFalse($abstractHelper->hasOption('blub'));
    }
}