<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class ClosureFactory
{
    /**
     * Returns a Closure matching a Token by Type
     *
     * @param $types integer|array
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
     * Returns a Closure matching a Token by Value
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

    /**
     * Returns a Closure matching a Token by Type and Value
     *
     * @param integer|null $type
     * @param string $value
     * @return \Closure
     */
    public static function getTypeAndValueClosure($type, $value)
    {
        return function(Token $token) use ($value, $type) {
                if ($value === $token->getValue() && $type === $token->getType()) {
                    return true;
            }

            return false;
        };
    }
}
