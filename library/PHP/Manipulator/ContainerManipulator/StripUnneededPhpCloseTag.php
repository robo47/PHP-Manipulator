<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class StripUnneededPhpCloseTag
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
        $iterator = $container->getReverseIterator();

        $tokensToDelete = array();

        while ($iterator->valid()) {
            $token = $iterator->current();

            if (!$this->evaluateConstraint('IsType', $token, array(T_WHITESPACE, T_CLOSE_TAG))) {
                break;
            } elseif($this->evaluateConstraint('IsType', $token, T_CLOSE_TAG)) {
                $container->removeToken($token);
                break;
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
