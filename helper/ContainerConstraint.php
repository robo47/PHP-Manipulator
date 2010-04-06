<?php

namespace PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\TokenContainer;

class __classname__
extends ContainerConstraint
{

    /**
     * Evaluate if the container
     *
     * @param PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {

            $iterator->next();
        }
        return false;
    }
}
