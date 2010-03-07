<?php

interface PHP_Formatter_Rule_Interface
{

    /**
     * Performs the rule on the container
     *
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container);

}