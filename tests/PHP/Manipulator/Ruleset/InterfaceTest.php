<?php

class PHP_Manipulator_Ruleset_InterfaceTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_Ruleset_Interface
     */
    public function testContainer()
    {
        $reflection = new ReflectionClass('PHP_Manipulator_Ruleset_Interface');
        $this->assertTrue($reflection->isInterface(), 'Interface seems to not be an interface ? WTF!');
        $methods = $reflection->getMethods();
        $this->assertSame(1, count($methods), 'Interface has wrong number of methods');
        $getRulesMethod = $methods[0];
        /* @var $getRulesMethod ReflectionMethod */
        $this->assertSame('getRules', $getRulesMethod->getName(), 'Method has wrong name');
        $this->assertSame(0, $getRulesMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
    }
}