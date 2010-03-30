<?php

class PHP_Formatter___classname__
extends PHP_Formatter_Rule_Abstract
{

    /**
     *
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */

            $iterator->next();
        }
        $container->retokenize();
    }
}