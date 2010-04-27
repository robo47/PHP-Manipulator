<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ReplaceVarWithPublic
extends ContainerManipulator
{

    /**
     * Manipulate
     *
     * @param PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_VAR)) {
                $token->setType(T_PUBLIC);
                $token->setValue('public');
            }
            $iterator->next();
        }
    }
}