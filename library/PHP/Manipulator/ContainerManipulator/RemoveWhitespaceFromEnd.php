<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveWhitespaceFromEnd
extends ContainerManipulator
{

    /**
     * Manipulate
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getReverseIterator();

        $tokensToDelete = array();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_WHITESPACE)) {
                $tokensToDelete[] = $token;
            } else {
                break;
            }
            $iterator->next();
        }

        foreach ($tokensToDelete as $token) {
            $container->removeToken($token);
        }
    }
}