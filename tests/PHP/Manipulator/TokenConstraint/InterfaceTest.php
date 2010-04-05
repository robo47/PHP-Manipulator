<?php

/**
 * @group TokenConstraint_Interface
 */
class PHP_Manipulator_TokenConstraint_InterfaceTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_TokenConstraint_Interface
     */
    public function testContainer()
    {
        $reflection = new ReflectionClass('PHP_Manipulator_TokenConstraint_Interface');
        $this->assertTrue($reflection->isInterface(), 'Interface seems to not be an interface ?');
        $methods = $reflection->getMethods();
        $this->assertSame(1, count($methods), 'Interface has wrong number of methods');
        $evaluateMethod = $methods[0];
        /* @var $evaluateMethod ReflectionMethod */
        $this->assertSame('evaluate', $evaluateMethod->getName(), 'Method has wrong name');
        $this->assertSame(2, $evaluateMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $evaluateMethod->getParameters();

        $tokenParameter = $parameters[0];
        /* @var $tokenParameter ReflectionParameter */
        $this->assertSame('token', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertEquals('PHP_Manipulator_Token', $tokenParameter->getClass()->getName(), 'Parameter is not a PHP_Manipulator_TokenContainer');
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');

        $paramsParameter = $parameters[1];
        /* @var $paramsParameter ReflectionParameter */
        $this->assertSame('params', $paramsParameter->getName(), 'Parameter has wrong name');
        $this->assertTrue($paramsParameter->isOptional(), 'Parameter is optional');
        $this->assertTrue($paramsParameter->allowsNull(), 'Parameter does not allow NULL');
    }
}