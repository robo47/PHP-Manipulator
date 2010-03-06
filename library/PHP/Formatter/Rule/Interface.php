<?php

interface PHP_Formatter_Rule_Interface {

    /**
     * Performs the rule on the tokens
     *
     * @param PHP_Formatter_TokenContainer $tokens
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens);
}