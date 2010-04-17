<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveErrorControlOperator
extends ContainerManipulator
{

    /**
     * Manipulate Container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $tokensToDelete = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsErrorControlOperator', $token)) {
                $container->removeToken($token);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
