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

        $errorControllTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP\Manipulator\Token */
            if ($this->evaluateConstraint('IsErrorControlOperator', $token)) {
                $errorControllTokens[] = $token;
            }
            $iterator->next();
        }
        foreach ($errorControllTokens as $errorControllToken) {
            $container->removeToken($errorControllToken);
        }
        $container->retokenize();
    }
}
