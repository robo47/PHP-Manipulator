<?php

namespace PHP\Manipulator;

use Closure;

class MatcherFactory
{
    /**
     * Returns a Closure matching a Token by Type
     *
     * @param int|int[] $types
     *
     * @return Closure
     */
    public static function createIsTypeMatcher($types)
    {
        return function (Token $token) use ($types) {
            return $token->isType($types);
        };
    }

    /**
     * Returns a Closure matching a Token by Value
     *
     * @param string|string[] $values
     *
     * @return Closure
     */
    public static function createHasValueMatcher($values)
    {
        return function (Token $token) use ($values) {
            return $token->hasValue($values);
        };
    }

    /**
     * Returns a Closure matching a Token by Type and Value
     *
     * @param int|null $type
     * @param string   $value
     *
     * @return Closure
     */
    public static function getTypeAndValueClosure($type, $value)
    {
        return function (Token $token) use ($value, $type) {
            return ($token->hasValue($value) && $token->isType($type));
        };
    }
}
