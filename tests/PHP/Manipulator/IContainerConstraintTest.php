<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\IContainerConstraint;

/**
 * @group ContainerConstraint_Interface
 */
class IContainerConstraintTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\IContainerConstraint
     */
    public function testContainer()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\IContainerConstraint');
        $this->assertTrue($reflection->isInterface(), 'Interface seems to not be an interface ? WTF!');
        $methods = $reflection->getMethods();
        $this->assertCount(1, $methods, 'Interface has wrong number of methods');
        $evaluateMethod = $methods[0];
        /* @var $evaluateMethod ReflectionMethod */
        $this->assertSame('evaluate', $evaluateMethod->getName(), 'Method has wrong name');
        $this->assertSame(2, $evaluateMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $evaluateMethod->getParameters();

        $tokenParameter = $parameters[0];
        /* @var $tokenParameter ReflectionParameter */
        $this->assertSame('container', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertEquals('PHP\Manipulator\TokenContainer', $tokenParameter->getClass()->getName(), 'Parameter is not a PHP\Manipulator\TokenContainer');
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');

        $paramsParameter = $parameters[1];
        /* @var $paramsParameter ReflectionParameter */
        $this->assertSame('params', $paramsParameter->getName(), 'Parameter has wrong name');
        $this->assertTrue($paramsParameter->isOptional(), 'Parameter is optional');
        $this->assertTrue($paramsParameter->allowsNull(), 'Parameter does not allow NULL');
    }
}