<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class __classname__
extends Rule
{

    /**
     * Apply Rule to Tokens
     *
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function apply(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();

            $iterator->next();
        }
        $container->retokenize();
    }
}