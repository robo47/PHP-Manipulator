<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_RemoveIndention extends PHP_Formatter_Rule_Abstract
{

    public function init()
    {
        
    }

    /**
     * Unindents all Code
     * @param array $tokens
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens)
    {
        $code = $tokens->toString();
        // @todo ideas on how to remove all indention: all T_WHITESPACE -> check for \t and <space> and remove all expect 1 <space> and keep brakes, no need for \t or <space> between brakes

        $code = preg_split('~(\n|\r\n|\r)~', $code, - 1);
        $code = array_map('ltrim', $code);
        $code = implode("\n", $code);

        // @todo seems like a expensive task, with all type-checking and stuff like that ?
        $tokenArrayContainer = PHP_Formatter_TokenContainer::createFromCode($code)
            ->getContainer();

        $tokens->setContainer($tokenArrayContainer);
    }
}