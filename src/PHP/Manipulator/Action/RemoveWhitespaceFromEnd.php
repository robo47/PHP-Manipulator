<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class RemoveWhitespaceFromEnd extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator = $container->getReverseIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isWhitespace()) {
                $container->removeToken($token);
            } elseif ($token->isType(T_INLINE_HTML)) {
                if ($token->containsOnlyWhitespace()) {
                    $container->removeToken($token);
                } else {
                    $token->setValue(rtrim($token->getValue()));
                    break;
                }
            } else {
                $token->setValue(rtrim($token->getValue()));
                break;
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
