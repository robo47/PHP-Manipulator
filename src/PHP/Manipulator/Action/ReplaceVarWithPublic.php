<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ReplaceVarWithPublic extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(T_VAR)) {
                $token->setType(T_PUBLIC)
                      ->setValue('public');
            }
            $iterator->next();
        }
    }
}
