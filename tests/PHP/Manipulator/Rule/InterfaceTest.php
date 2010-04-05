<?php

/**
 * @group Rule_Interface
 */
class PHP_Manipulator_Rule_InterfaceTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_Rule_Interface
     */
    public function testInterfaceMethods()
    {
        $reflection = new ReflectionClass('PHP_Manipulator_Rule_Interface');
        $this->assertTrue($reflection->isInterface(), 'Interface seems to not be an interface ? WTF!');
        $methods = $reflection->getMethods();
        $this->assertSame(1, count($methods), 'Interface has wrong number of methods');
        $applyRuleToTokensMethod = $methods[0];
        /* @var $applyRuleToTokensMethod ReflectionMethod */
        $this->assertSame('applyRuleToTokens', $applyRuleToTokensMethod->getName(), 'Method has wrong name');
        $this->assertSame(1, $applyRuleToTokensMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $applyRuleToTokensMethod->getParameters();
        $tokenParameter = $parameters[0];
        /* @var $tokenParameter ReflectionParameter */
        $this->assertSame('container', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertEquals('PHP_Manipulator_TokenContainer', $tokenParameter->getClass()->getName(), 'Parameter is not a PHP_Manipulator_TokenContainer');
        $this->assertFalse($tokenParameter->isPassedByReference(), 'Parameter is passed as reference');
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');
    }
}