<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action\StripPhp;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class StripNonPhp
extends StripPhp
{
    /**
     * @param boolean $open
     * @return boolean
     */
    protected function _shoudDelete($open)
    {
        return !$open;
    }
}