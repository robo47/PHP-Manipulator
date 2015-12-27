<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class AddPublicKeyword extends Action
{

    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $insideClassOrInterface = false;
        $classLevel = null;
        $level = 0;
        $insideMethod = false;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isOpeningCurlyBrace()) {
                $level++;
            }
            if ($token->isClosingCurlyBrace()) {
                $level--;
                if ($classLevel === $level && true === $insideClassOrInterface) {
                    $insideClassOrInterface = false;
                    $classLevel = null;
                    if (true === $insideMethod) {
                        $insideMethod = false;
                    }
                }
            }
            if ($token->isType([T_CLASS, T_INTERFACE])) {
                $insideClassOrInterface = true;
                $classLevel = $level;
            }
            if (true === $insideClassOrInterface && false === $insideMethod && $token->isType(T_FUNCTION)) {
                $insideMethod = true;
                if (!$this->isPrecededByTokenType($iterator, [T_PUBLIC, T_PRIVATE, T_PROTECTED])) {
                    $token = $iterator->current();
                    $publicToken = Token::createFromValueAndType('public', T_PUBLIC);
                    $whitespaceToken = Token::createFromValueAndType(' ', T_WHITESPACE);

                    $container->insertTokensBefore($token, [$publicToken, $whitespaceToken]);
                    $iterator->update($token);
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
