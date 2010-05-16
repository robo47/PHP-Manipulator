<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use Tests\Mock\ActionMock;

/**
 * @group Action
 */
class ActionTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action
     */
    public function testClassMethods()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\Action');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');

        $runMethod = $reflection->getMethod('run');
        /* @var $runMethod ReflectionMethod */
        $this->assertTrue($runMethod->isAbstract());
        $this->assertSame('run', $runMethod->getName(), 'Method has wrong name');
        $this->assertSame(1, $runMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $runMethod->getParameters();
        $containerParameter = $parameters[0];
        /* @var $containerParameter ReflectionParameter */
        $this->assertSame('container', $containerParameter->getName(), 'Parameter has wrong name');
        $this->assertEquals('PHP\Manipulator\TokenContainer', $containerParameter->getClass()->getName(), 'Parameter is not a PHP\Manipulator\TokenContainer');
        $this->assertFalse($containerParameter->isOptional(), 'Parameter is optional');
    }

    /**
     * @covers \PHP\Manipulator\Action::__construct
     */
    public function testDefaultConstructor()
    {
        $abstractHelper = new ActionMock();
        $this->assertEquals(array(), $abstractHelper->getOptions(), 'options don\'t match');
    }

    /**
     * @covers \PHP\Manipulator\Action::__construct
     * @covers \PHP\Manipulator\Action::init
     */
    public function testConstructorCallsInit()
    {
        $abstractHelper = new ActionMock();
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
     * @covers \PHP\Manipulator\Action::__construct
     * @dataProvider constructorOptionsProvider
     */
    public function testConstructorSetsOptions($options)
    {
        $abstractHelper = new ActionMock($options);
        $this->assertEquals($options, $abstractHelper->getOptions(), 'options don\'t match');
    }

    /**
     * @covers \PHP\Manipulator\Action::addOptions
     * @covers \PHP\Manipulator\Action::getOptions
     */
    public function testAddOptionsAndGetOptions()
    {
        $options = array(
            'baa' => 'foo',
            'blub' => 'bla',
        );
        $abstractHelper = new ActionMock(array('foo' => 'bla'));
        $fluent = $abstractHelper->addOptions($options);
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');

        $this->assertCount(3, $abstractHelper->getOptions(), 'Wrong options count');
    }

    /**
     * @covers \PHP\Manipulator\Action::setOption
     * @covers \PHP\Manipulator\Action::getOption
     */
    public function testSetOptionAndGetOption()
    {
        $abstractHelper = new ActionMock();
        $fluent = $abstractHelper->setOption('baa', 'foo');
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');
        $this->assertEquals('foo', $abstractHelper->getOption('baa'), 'Wrong value');
    }

    /**
     * @covers \PHP\Manipulator\Action::getOption
     * @covers \Exception
     */
    public function testGetOptionThrowsExceptionOnNonExistingOption()
    {
        $abstractHelper = new ActionMock();
        try {
            $abstractHelper->getOption('foo');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Option 'foo' not found", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Action::hasOption
     */
    public function testHasOption()
    {
        $abstractHelper = new ActionMock(array('foo' => 'bla'));
        $this->assertTrue($abstractHelper->hasOption('foo'));
        $this->assertFalse($abstractHelper->hasOption('blub'));
    }
}