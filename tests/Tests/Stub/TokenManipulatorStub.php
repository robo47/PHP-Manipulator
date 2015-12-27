<?php

namespace Tests\Stub;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator;

class TokenManipulatorStub extends TokenManipulator
{
    /**
     * @var bool
     */
    public static $called = false;

    /**
     * @param Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        self::$called = true;
    }
}
