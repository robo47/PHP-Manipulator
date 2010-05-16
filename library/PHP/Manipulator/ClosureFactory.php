<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;

class ClosureFactory
{
    /**
     * @param $types string|array
     * @return \Closure
     */
    public static function getIsTypeClosure($types)
    {
        return function(Token $token) use ($types) {
            $helper = new AHelper();
            return $helper->isType($token, $types);
        };
    }

    /**
     *
     * @param string|array $values
     * @return \Closure
     */
    public static function getHasValueClosure($values)
    {
        return function(Token $token) use ($values) {
            $helper = new AHelper();
            return $helper->hasValue($token, $values);
        };
    }
}