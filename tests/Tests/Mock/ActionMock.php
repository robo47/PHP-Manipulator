<?php

namespace Tests\Mock;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ActionMock
extends Action
{

    /**
     * @var boolean
     */
    public static $called = true;

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        self::$called = true;
    }
}