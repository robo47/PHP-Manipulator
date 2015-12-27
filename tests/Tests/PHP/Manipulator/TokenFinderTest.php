<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder;
use ReflectionClass;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenFinder
 */
class TokenFinderTest extends TestCase
{
    public function testFinder()
    {
        $reflection = new ReflectionClass(TokenFinder::class);
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
        $this->assertTrue($reflection->isSubclassOf(AHelper::class));

        $findMethod = $reflection->getMethod('find');
        $this->assertSame('find', $findMethod->getName(), 'Method has wrong name');
        $this->assertSame(3, $findMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $findMethod->getParameters();

        $tokenParameter = $parameters[0];
        $this->assertSame('token', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertSame(
            Token::class,
            $tokenParameter->getClass()->getName(),
            'Parameter is not a PHP\Manipulator\TokenContainer'
        );
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');

        $tokenParameter = $parameters[1];
        $this->assertSame('container', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertSame(
            TokenContainer::class,
            $tokenParameter->getClass()->getName(),
            'Parameter is not a PHP\Manipulator\TokenContainer'
        );
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');

        $paramsParameter = $parameters[2];
        $this->assertSame('params', $paramsParameter->getName(), 'Parameter has wrong name');
        $this->assertTrue($paramsParameter->isOptional(), 'Parameter is optional');
        $this->assertTrue($paramsParameter->allowsNull(), 'Parameter does not allow NULL');
    }
}
