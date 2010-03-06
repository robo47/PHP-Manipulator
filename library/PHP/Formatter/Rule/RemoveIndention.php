<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_RemoveIndention extends PHP_Formatter_Rule_Abstract
{
    public function init()
    {
        
    }

    /**
     *
     * @param array $tokens
     * @todo always outputs linux-line-endings!
     * @todo possible without tokens2code2tokens ?
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens)
    {
        $formatter = new PHP_Formatter();
        $code = $formatter->tokensToCode($tokens);

        $code = preg_split('~[\n|\r\n|\r]~', $code, -1);
        $code = array_map('ltrim', $code);
        $code = implode("\n", $code);

        $tokenArray = $formatter->getTokens($code);

        $tokens->exchangeArray($tokenArray);
    }
}