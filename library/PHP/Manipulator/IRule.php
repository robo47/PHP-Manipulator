<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;

interface IRule
{

    /**
     * Performs the rule on the container
     *
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function applyRuleToTokens(TokenContainer $container);

}