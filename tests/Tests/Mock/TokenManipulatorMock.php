<?php

namespace Tests\Mock;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

class TokenManipulatorMock
extends TokenManipulator
{

    /**
     * @var boolean
     */
    public static $called = false;

    /**
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        self::$called = true;
    }
}