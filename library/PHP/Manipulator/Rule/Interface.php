<?php

interface PHP_Manipulator_Rule_Interface
{

    /**
     * Performs the rule on the container
     *
     * @param PHP_Manipulator_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Manipulator_TokenContainer $container);

}