<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;

interface IRuleset
{

    /**
     * Get Rules
     *
     * Returns array with all Rules used by this ruleset
     *
     * @return array
     */
    public function getRules();

}