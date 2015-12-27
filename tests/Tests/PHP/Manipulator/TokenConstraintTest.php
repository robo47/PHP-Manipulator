<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenConstraint;
use ReflectionClass;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenConstraint
 */
class TokenConstraintTest extends TestCase
{
    public function testConstraint()
    {
        $reflection = new ReflectionClass(TokenConstraint::class);
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
        $this->assertTrue($reflection->isSubclassOf(AHelper::class));

        $evaluateMethod = $reflection->getMethod('evaluate');
        $this->assertSame(2, $evaluateMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
        $parameters = $evaluateMethod->getParameters();

        $tokenParameter = $parameters[0];
        $this->assertSame('token', $tokenParameter->getName(), 'Parameter has wrong name');
        $this->assertSame(
            Token::class,
            $tokenParameter->getClass()->getName(),
            'Parameter is not a PHP\Manipulator\TokenContainer'
        );
        $this->assertFalse($tokenParameter->isOptional(), 'Parameter is optional');

        $paramsParameter = $parameters[1];

        $this->assertSame('params', $paramsParameter->getName(), 'Parameter has wrong name');
        $this->assertTrue($paramsParameter->isOptional(), 'Parameter is optional');
        $this->assertTrue($paramsParameter->allowsNull(), 'Parameter does not allow NULL');
    }
}
