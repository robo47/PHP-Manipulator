<?php

namespace Tests\Mock;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class TokenConstraintMock
extends TokenConstraint
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $params = null)
    {
        return self::$return;
    }
}