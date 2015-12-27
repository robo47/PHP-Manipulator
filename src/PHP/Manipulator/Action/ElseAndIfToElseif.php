<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ElseAndIfToElseif extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $waitingForIf  = false;
        $replaceTokens = [];

        $allowedTypes = [T_IF, T_ELSE, T_WHITESPACE];

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(T_ELSE)) {
                $waitingForIf  = true;
                $replaceTokens = [];
            }
            if (true === $waitingForIf && !$token->isType($allowedTypes)) {
                $waitingForIf = false;
            } else {
                $replaceTokens[] = $token;
            }

            if (true === $waitingForIf && $token->isType(T_IF)) {
                $waitingForIf = false;
                $token        = array_pop($replaceTokens);
                $token->setType(T_ELSEIF);
                $token->setValue('elseif');

                $container->removeTokens($replaceTokens);

                $replaceTokens = [];
            }

            $iterator->next();
        }
        $container->retokenize();
    }
}
