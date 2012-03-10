<?php

namespace Tests\Constraint;

class ValidTokenMatchingClosure extends \PHPUnit_Framework_Constraint
{

    /**
     * Evaluate
     *
     * @param \Closure $other
     * @param  string $description Additional information about the test
     * @param  bool $returnResult Whether to return a result or throw an exception
     * @return boolean
     */
    public function evaluate($other, $description = '', $returnResult = FALSE)
    {
        if (!$other instanceof \Closure) {
            $this->_cause = 'Variable must be a Closure';
            if ($returnResult) {
                return FALSE;
            }
            $this->fail($other, $description);
        }

        $reflection = new \ReflectionFunction($other);

        $requiredParameters = new \PHPUnit_Framework_Constraint_IsEqual(1);
        if (false === $requiredParameters->evaluate($reflection->getNumberOfRequiredParameters(), $description, true)) {
            $this->_cause = 'Closure does not have 1 required parameter';
            if ($returnResult) {
                return FALSE;
            }
            $this->fail($other, $description);
        }

        $params = $reflection->getParameters();
        $tokenParameter = $params[0];
        /* @var $tokenParameter \ReflectionParameter */

        $parameterType = new \PHPUnit_Framework_Constraint_IsEqual('PHP\Manipulator\Token');
        if (false === $parameterType->evaluate($tokenParameter->getClass()->name, $description, true)) {
            $this->_cause = 'Closures Token-Parameter has wrong Typehint';
            if ($returnResult) {
                return FALSE;
            }
            $this->fail($other, $description);
        }

        return true;
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     * @return string
     */
    protected function failureDescription($other)
    {
        return $this->_cause;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Is a valid Token Matching Closure ';
    }

}
