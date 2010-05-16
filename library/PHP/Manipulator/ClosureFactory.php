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
}