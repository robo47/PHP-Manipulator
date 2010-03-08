<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_StripNonPhp extends PHP_Formatter_Rule_Abstract
{

    public function init()
    {

    }

    /**
     *
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $open = false;
        $deleteTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            if ($token->isType(T_OPEN_TAG) || $token->isType(T_OPEN_TAG_WITH_ECHO)) {
                $open = true;
            }
            if (!$open) {
                $deleteTokens[] = $token;
            }
            if ($token->isType(T_CLOSE_TAG)) {
                $open = false;
            }
            $iterator->next();
        }
        $container->removeTokens($deleteTokens);
        $container->retokenize();
    }
}