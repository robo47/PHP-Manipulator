<?php

namespace Tests\PHP\Manipulator;

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
}