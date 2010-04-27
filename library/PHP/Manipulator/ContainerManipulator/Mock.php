<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class Mock
extends ContainerManipulator
{

    /**
     * @var boolean
     */
    public static $called = true;

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        self::$called = true;
    }
}