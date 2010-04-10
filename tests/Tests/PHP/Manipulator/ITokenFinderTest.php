<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\ITokenFinder;

/**
 * @group TokenFinder_Interface
 */
class ITokenFinderTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\ITokenFinder
     */
    public function testFinder()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\ITokenFinder');
        $this->assertTrue($reflection->isInterface(), 'Interface seems to not be an interface ? WTF!');
        $methods = $reflection->getMethods();
        $this->assertCount(1, $methods, 'Interface has wrong number of methods');
        $evaluateMethod = $methods[0];
        /* @var $evaluateMethod ReflectionMethod */
        $this->assertSame('find', $evaluateMethod->getName(), 'Method has wrong name');
        $this->assertSame(3, $evaluateMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $evaluateMethod->getParameters();

        $tokenParameter = $parameters[0];
        /* @var $tokenParameter ReflectionParameter */
        $this->assertSame('token', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertEquals('PHP\Manipulator\Token', $tokenParameter->getClass()->getName(), 'Parameter is not a PHP\Manipulator\TokenContainer');
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');

        $tokenParameter = $parameters[1];
        /* @var $tokenParameter ReflectionParameter */
        $this->assertSame('container', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertEquals('PHP\Manipulator\TokenContainer', $tokenParameter->getClass()->getName(), 'Parameter is not a PHP\Manipulator\TokenContainer');
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');

        $paramsParameter = $parameters[2];
        /* @var $paramsParameter ReflectionParameter */
        $this->assertSame('params', $paramsParameter->getName(), 'Parameter has wrong name');
        $this->assertTrue($paramsParameter->isOptional(), 'Parameter is optional');
        $this->assertTrue($paramsParameter->allowsNull(), 'Parameter does not allow NULL');
    }
}