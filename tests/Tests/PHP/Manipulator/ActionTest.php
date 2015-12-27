<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Action;
use PHP\Manipulator\Exception\ActionException;
use PHP\Manipulator\TokenContainer;
use ReflectionClass;
use Tests\Stub\ActionStub;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action
 */
class ActionTest extends TestCase
{
    public function testClassMethods()
    {
        $reflection = new ReflectionClass(Action::class);
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');

        $runMethod = $reflection->getMethod('run');
        $this->assertTrue($runMethod->isAbstract());
        $this->assertSame('run', $runMethod->getName(), 'Method has wrong name');
        $this->assertSame(1, $runMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters         = $runMethod->getParameters();
        $containerParameter = $parameters[0];
        $this->assertSame('container', $containerParameter->getName(), 'Parameter has wrong name');
        $this->assertSame(
            TokenContainer::class,
            $containerParameter->getClass()->getName(),
            'Parameter is not a PHP\Manipulator\TokenContainer'
        );
        $this->assertFalse($containerParameter->isOptional(), 'Parameter is optional');
    }

    public function testDefaultConstructor()
    {
        $abstractHelper = new ActionStub();
        $this->assertSame([], $abstractHelper->getOptions(), 'options don\'t match');
    }

    public function testConstructorCallsInit()
    {
        $abstractHelper = new ActionStub();
        $this->assertTrue($abstractHelper->init, 'init is not true');
    }

    /**
     * @return array
     */
    public function constructorOptionsProvider()
    {
        $data = [];

        $data[] = [[]];
        $data[] = [['baa' => 'foo']];
        $data[] = [['baa' => 'foo', 'blub' => 'bla']];

        return $data;
    }

    /**
     * @dataProvider constructorOptionsProvider
     *
     * @param array $options
     */
    public function testConstructorSetsOptions(array $options)
    {
        $abstractHelper = new ActionStub($options);
        $this->assertSame($options, $abstractHelper->getOptions(), 'options don\'t match');
    }

    public function testSetOptionAndGetOption()
    {
        $abstractHelper = new ActionStub();
        $fluent         = $abstractHelper->setOption('baa', 'foo');
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');
        $this->assertSame('foo', $abstractHelper->getOption('baa'), 'Wrong value');
    }

    public function testGetOptionThrowsExceptionOnNonExistingOption()
    {
        $abstractHelper = new ActionStub();
        $this->setExpectedException(ActionException::class, 'foo', ActionException::NO_OPTION_BY_NAME);
        $abstractHelper->getOption('foo');
    }

    public function testHasOption()
    {
        $abstractHelper = new ActionStub(['foo' => 'bla']);
        $this->assertTrue($abstractHelper->hasOption('foo'));
        $this->assertFalse($abstractHelper->hasOption('blub'));
    }
}
