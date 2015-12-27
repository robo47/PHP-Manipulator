<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class RemoveErrorControlOperator extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isErrorControlOperator()) {
                $container->removeToken($token);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
