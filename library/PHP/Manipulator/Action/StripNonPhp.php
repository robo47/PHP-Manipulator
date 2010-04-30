<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class StripNonPhp
extends Action
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $open = false;
        $deleteTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, array(T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO))) {
                $open = true;
            }
            if (!$open) {
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