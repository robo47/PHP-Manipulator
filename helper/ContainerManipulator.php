<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class __classname__
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

            $iterator->next();
        }
        $container->retokenize();
    }
}
