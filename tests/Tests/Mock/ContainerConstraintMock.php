<?php

namespace Tests\Mock;

use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\TokenContainer;

class ContainerConstraintMock
extends ContainerConstraint
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(TokenContainer $container, $params = null)
    {
        return self::$return;
    }
}