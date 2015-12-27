<?php

namespace Tests\Stub;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ActionStub extends Action
{
    /**
     * @var bool
     */
    public static $called = true;

    /**
     * @var bool
     */
    public $init = false;

    public function init()
    {
        $this->init = true;
    }

    /**
     * @param TokenContainer $container
     * @param mixed          $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        self::$called = true;
    }
}
