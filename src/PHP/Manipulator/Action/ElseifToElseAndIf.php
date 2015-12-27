<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ElseifToElseAndIf extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(T_ELSEIF)) {
                $token->setType(T_ELSE);
                $token->setValue('else');
                $whitespaceToken = Token::createFromValueAndType(' ', T_WHITESPACE);
                $ifToken         = Token::createFromValueAndType('if', T_IF);
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
