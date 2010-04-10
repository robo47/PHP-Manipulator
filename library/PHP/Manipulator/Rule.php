<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;

abstract class Rule
extends AHelper
{

    /**
     * Performs the rule on the container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    abstract public function apply(TokenContainer $container);

}