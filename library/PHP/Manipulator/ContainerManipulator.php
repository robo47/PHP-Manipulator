<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\TokenContainer;

abstract class ContainerManipulator
extends AHelper
{

    /**
     * Manipulates a container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    abstract public function manipulate(TokenContainer $container, $params = null);

}