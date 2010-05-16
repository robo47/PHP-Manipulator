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
     * @var boolean
     */
    public $init = false;

    public function init()
    {
        $this->init = true;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        self::$called = true;
    }
}