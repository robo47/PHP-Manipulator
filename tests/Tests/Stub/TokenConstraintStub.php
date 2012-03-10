<?php

namespace Tests\Stub;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class TokenConstraintStub
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
