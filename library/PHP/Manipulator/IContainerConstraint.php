<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;

interface IContainerConstraint
{

    /**
     * Evaluates a constraint on a container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function evaluate(TokenContainer $container, $params = null);

}