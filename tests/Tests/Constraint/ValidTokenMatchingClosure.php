<?php

namespace Tests\Constraint;

use Closure;
use PHP\Manipulator\Token;
use PHPUnit_Framework_Constraint;
use PHPUnit_Framework_Constraint_IsEqual;
use ReflectionFunction;

class ValidTokenMatchingClosure extends PHPUnit_Framework_Constraint
{
    /**
     * Evaluate
     *
     * @param Closure $other
     * @param string  $description  Additional information about the test
     * @param bool    $returnResult Whether to return a result or throw an exception
     *
     * @return bool
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        if (!$other instanceof Closure) {
            if ($returnResult) {
                return false;
            }
            $this->fail($other, 'Variable must be a Closure');
        }

        $reflection = new ReflectionFunction($other);

        $requiredParameters = new PHPUnit_Framework_Constraint_IsEqual(1);
        if (false === $requiredParameters->evaluate($reflection->getNumberOfRequiredParameters(), $description, true)) {
            if ($returnResult) {
                return false;
            }
            $this->fail($other, 'Closure does not have 1 required parameter');
        }

        $params         = $reflection->getParameters();
        $tokenParameter = $params[0];

        $parameterType = new PHPUnit_Framework_Constraint_IsEqual(Token::class);
        if (false === $parameterType->evaluate($tokenParameter->getClass()->name, $description, true)) {
            if ($returnResult) {
                return false;
            }
            $this->fail($other, 'Closures Token-Parameter has wrong Typehint');
        }

        return true;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Is a valid Token Matching Closure ';
    }
}
