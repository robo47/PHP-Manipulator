<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ReplaceVarWithPublic
extends Action
{

    /**
     * Replace var $foo; with public $foo;
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_VAR)) {
                $token->setType(T_PUBLIC);
                $token->setValue('public');
            }
            $iterator->next();
        }
    }
}