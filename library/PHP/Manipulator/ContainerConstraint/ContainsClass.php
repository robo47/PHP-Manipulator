<?php

namespace PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\TokenContainer;

// @todo Create generic Constraint for checking if a container contains a specific token-type
class ContainsClass
extends ContainerConstraint
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            if ($this->isType($iterator->current(), T_CLASS)) {
                return true;
            }
            $iterator->next();
        }
        return false;
    }
}