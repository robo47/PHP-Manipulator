<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\Exception\TokenFinderException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder;

class SwitchFinder extends TokenFinder
{
    public function find(Token $token, TokenContainer $container, $params = null)
    {
        if (!$token->isType(T_SWITCH)) {
            $message = 'Starttoken is not T_SWITCH';
            throw new TokenFinderException($message, TokenFinderException::UNSUPPORTED_START_TOKEN);
        }
        $result   = new Result();
        $iterator = $container->getIterator();
        $iterator->seekToToken($token);

        $level  = 0;
        $inside = false;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isOpeningCurlyBrace()) {
                if (0 === $level) {
                    $inside = true;
                }
                $level++;
            }
            if ($token->isClosingCurlyBrace()) {
                $level--;
            }
            $result->addToken($token);

            if ($inside && 0 === $level) {
                break;
            }
            $iterator->next();
        }

        return $result;
    }
}
