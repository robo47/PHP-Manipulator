<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveErrorControlOperator
extends Action
{

    /**
     * Manipulate Container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

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