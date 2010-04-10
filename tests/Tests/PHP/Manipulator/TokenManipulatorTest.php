<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\TokenManipulator;

/**
 * @group TokenManipulator_Interface
 */
class TokenManipulatorTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\TokenManipulator
     */
    public function testContainer()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\TokenManipulator');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
        $this->assertTrue($reflection->isSubclassOf('\PHP\Manipulator\AHelper'));

        $evaluateMethod = $reflection->getMethod('manipulate');
        /* @var $evaluateMethod ReflectionMethod */
        $this->assertSame(2, $evaluateMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $evaluateMethod->getParameters();

        $tokenParameter = $parameters[0];
        /* @var $tokenParameter ReflectionParameter */
        $this->assertSame('token', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertEquals('PHP\Manipulator\Token', $tokenParameter->getClass()->getName(), 'Parameter is not a PHP\Manipulator\TokenContainer');
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');

        $paramsParameter = $parameters[1];
        /* @var $paramsParameter ReflectionParameter */
        $this->assertSame('params', $paramsParameter->getName(), 'Parameter has wrong name');
        $this->assertTrue($paramsParameter->isOptional(), 'Parameter is optional');
        $this->assertTrue($paramsParameter->allowsNull(), 'Parameter does not allow NULL');
    }
}