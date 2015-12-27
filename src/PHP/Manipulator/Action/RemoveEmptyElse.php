<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveEmptyElse extends Action
{
    const OPTION_IGNORE_COMMENTS = 'ignoreComments';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_IGNORE_COMMENTS)) {
            $this->setOption(self::OPTION_IGNORE_COMMENTS, false);
        }
    }

    public function run(TokenContainer $container)
    {
        $iterator        = $container->getIterator();

        $lastElse      = null;
        $noOtherTokens = true;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(T_ELSE)) {
                $lastElse      = $token;
                $noOtherTokens = true;
            }

            if (null !== $lastElse && !$this->isAllowedTokenInsideEmptyElse($token)) {
                $noOtherTokens = false;
            }

            if ($this->isEndElse($token) && true === $noOtherTokens && null !== $lastElse) {
                $start    = $lastElse;
                $end      = $token;
                $previous = $container->getPreviousToken($start);
                if ($end->isType(T_ENDIF)) {
                    $end = $container->getPreviousToken($end);
                }
                $container->removeTokensFromTo($start, $end);
                $iterator->update($previous);
                $lastElse = null;
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isEndElse(Token $token)
    {
        if ($token->isClosingCurlyBrace()) {
            return true;
        }
        if ($token->isType(T_ENDIF)) {
            return true;
        }

        return false;
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isAllowedTokenInsideEmptyElse(Token $token)
    {
        if ($token->isColon() ||
            $token->isType([T_ELSE, T_ENDIF, T_WHITESPACE]) ||
            $token->isClosingCurlyBrace() ||
            $token->isOpeningCurlyBrace()
        ) {
            return true;
        }
        // check for ignored comments
        if (true === $this->getOption(self::OPTION_IGNORE_COMMENTS) &&
            $token->isType([T_COMMENT, T_DOC_COMMENT])
        ) {
            return true;
        }

        return false;
    }
}
