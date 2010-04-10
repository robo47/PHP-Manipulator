<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class StripPhp
extends Rule
{

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function apply(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $open = false;
        $deleteTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP\Manipulator\Token */
            if ($this->evaluateConstraint('IsType', $token, array(T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO))) {
                $open = true;
            }
            if ($open) {
                $deleteTokens[] = $token;
            }
            if ($this->evaluateConstraint('IsType', $token, T_CLOSE_TAG)) {
                $open = false;
            }
            $iterator->next();
        }
        $container->removeTokens($deleteTokens);
        $container->retokenize();
    }
}