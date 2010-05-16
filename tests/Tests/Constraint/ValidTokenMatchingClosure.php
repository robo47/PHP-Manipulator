<?php

namespace Tests\Constraint;

class ValidTokenMatchingClosure extends \PHPUnit_Framework_Constraint
{

    /**
     * @var \Closure
     */
    protected $_cause = '';

    public function __construct()
    {
    }

    /**
     * Evaluate
     *
     * @param \Closure $other
     * @return boolean
     */
    public function evaluate($other)
    {
        if (!$other instanceof \Closure) {
             $this->_cause = 'Variable is no Closure';
            return false;
        }

        $reflection = new \ReflectionFunction($other);

        $requiredParameters = new \PHPUnit_Framework_Constraint_IsEqual(1);
        if (false === $requiredParameters->evaluate($reflection->getNumberOfRequiredParameters())) {
            $this->_cause = 'Closure does not have 1 required parameter';
            return false;
        }

        $params = $reflection->getParameters();
        $tokenParameter = $params[0];
        /* @var $tokenParameter \ReflectionParameter */

        $parameterType = new \PHPUnit_Framework_Constraint_IsEqual('PHP\Manipulator\Token');
        if (false === $parameterType->evaluate($tokenParameter->getClass()->name)) {
            $this->_cause = 'Closures Token-Parameter has wrong Typehint';
            return false;
        }

        return true;
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     * @return string
     */
    protected function failureDescription($other, $description, $not)
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