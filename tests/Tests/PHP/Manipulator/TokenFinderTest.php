<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\TokenFinder;

/**
 * @group TokenFinder
 */
class TokenFinderTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\TokenFinder
     */
    public function testFinder()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\TokenFinder');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
        $this->assertTrue($reflection->isSubclassOf('\PHP\Manipulator\AHelper'));

        $findMethod = $reflection->getMethod('find');
        /* @var $findMethod ReflectionMethod */
        $this->assertSame('find', $findMethod->getName(), 'Method has wrong name');
        $this->assertSame(3, $findMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $findMethod->getParameters();

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