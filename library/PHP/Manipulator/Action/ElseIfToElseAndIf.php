<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ElseIfToElseAndIf
extends Action
{

    /**
     * Run Action
     *
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_ELSEIF)) {
                $token->setType(T_ELSE);
                $token->setValue('else');
                $whitespaceToken = new Token(' ', T_WHITESPACE);
                $ifToken = new Token('if', T_IF);
                $container->insertTokenAfter($token, $whitespaceToken);
                $container->insertTokenAfter($whitespaceToken, $ifToken);
                $iterator = $container->getIterator();
                $iterator->seekToToken($ifToken);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}