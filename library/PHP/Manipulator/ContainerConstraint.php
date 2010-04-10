<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\TokenContainer;

abstract class ContainerConstraint
extends AHelper
{

    /**
     * Evaluates a constraint on a container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    abstract public function evaluate(TokenContainer $container, $params = null);

}