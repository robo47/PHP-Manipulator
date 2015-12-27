<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveTypehints extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $functionTokens = [];
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(T_FUNCTION)) {
                $functionTokens[] = $token;
            }
            $iterator->next();
        }
        foreach ($functionTokens as $token) {
            $this->parseFunctionArguments($container, $token);
        }
        $container->retokenize();
    }

    /**
     * @param TokenContainer $container
     * @param Token          $startToken
     */
    private function parseFunctionArguments(TokenContainer $container, Token $startToken)
    {
        $iterator = $container->getIterator();
        $iterator->seekToToken($startToken);
        $indentionLevel = 0;
        $inside         = false;
        $arguments      = [];
        $argumentTokens = [];

        while ($iterator->valid()) {
            $token = $iterator->current();

            if ($token->isOpeningBrace()) {
                $indentionLevel++;
            } elseif ($token->isClosingBrace()) {
                $indentionLevel--;
            }

            // next argument
            if ($token->isComma()) {
                $arguments[]    = $argumentTokens;
                $argumentTokens = [];
            }

            if ($indentionLevel > 0) {
                $argumentTokens[] = $token;
                $inside           = true;
            } elseif ($indentionLevel === 0 && true === $inside) {
                // break if we are at the end of the arguments
                break;
            }
            $iterator->next();
        }

        // add last argument
        if (!empty($argumentTokens)) {
            $arguments[] = $argumentTokens;
        }

        foreach ($arguments as $argument) {
            $this->parseSingleArgument($argument, $container);
        }
    }

    /**
     * @param TokenContainer $container
     * @param Token[]        $argumentTokens
     */
    private function parseSingleArgument(array $argumentTokens, $container)
    {
        foreach ($argumentTokens as $token) {
            if ('=' === $token->getValue()) {
                break;
            }
            if ($token->isType([T_STRING, T_ARRAY, T_NS_SEPARATOR])) {
                $container->removeToken($token);
            }
        }
    }
}
