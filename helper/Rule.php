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
    public function applyRuleToTokens(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */

            $iterator->next();
        }
        $container->retokenize();
    }
}