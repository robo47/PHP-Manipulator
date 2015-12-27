<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Actionset;
use ReflectionClass;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Actionset
 */
class ActionsetTest extends TestCase
{
    public function testActionset()
    {
        $reflection = new ReflectionClass(Actionset::class);
        $this->assertTrue($reflection->isAbstract(), 'Class must be abstract');

        $getActionsMethod = $reflection->getMethod('getActions');
        $this->assertSame(0, $getActionsMethod->getNumberOfParameters(), 'Method has wrong number of parameters');

        $constructor = $reflection->getMethod('__construct');
        $this->assertSame(1, $constructor->getNumberOfParameters(), 'Method has wrong number of parameters');

        $params           = $constructor->getParameters();
        $optionsParameter = $params[0];

        $this->assertTrue($optionsParameter->isArray(), 'Parameter has no array type hint');
        $this->assertSame([], $optionsParameter->getDefaultValue(), 'Parameter was wrong default value');
    }

    public function testConstruct()
    {
        $actionset = new MyActionSet();
        $this->assertSame([], $actionset->getOptions());
    }

    public function testGetOptions()
    {
        $actionset = new MyActionSet(['foo' => 'blub']);
        $this->assertSame(['foo' => 'blub'], $actionset->getOptions());
    }
}
