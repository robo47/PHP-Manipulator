<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;

class ClosureFactory
{
    /**
     * @param string|array
     * @return \Closure
     */
    public static function getIsTypeClosure($allowedTypes)
    {
        return function(Token $token) use ($allowedTypes) {
            $helper = new AHelper();
            return $helper->isType($token, $allowedTypes);
        };
    }

    /**
     * @param string
     * @return \Closure
     */
    public static function getHasValueClosure($value)
    {
        return function(Token $token) use ($value) {
            return ($token->getValue() === $value);
        };
    }
}