<?php

namespace Test\PHP\Manipulator;

use PHP\Manipulator\Actionset;

/**
 * @group Actionset
 */
class ActionsetTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Actionset
     */
    public function testContainer()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\Actionset');
        $this->assertTrue($reflection->isAbstract(), 'Class must be abstract');

        $getActionsMethod = $reflection->getMethod('getActions');
        /* @var $getActionsMethod ReflectionMethod */
        $this->assertSame(0, $getActionsMethod->getNumberOfParameters(), 'Method has wrong number of parameters');

        $constructor = $reflection->getMethod('__construct');
        /* @var $constructor ReflectionMethod */
        $this->assertSame(1, $constructor->getNumberOfParameters(), 'Method has wrong number of parameters');
        $params = $constructor->getParameters();
        $optionsParameter = $params[0];
        /* @var $optionsParameter \ReflectionParameter */
        $this->assertTrue($optionsParameter->isArray(), 'Parameter has no array type hint');
        $this->assertEquals(array(), $optionsParameter->getDefaultValue(), 'Parameter was wrong default value');
    }
}