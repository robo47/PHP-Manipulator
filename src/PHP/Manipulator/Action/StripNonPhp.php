<?php

namespace PHP\Manipulator\Action;

class StripNonPhp extends StripPhp
{
    /**
     * @param bool $open
     *
     * @return bool
     */
    protected function shoudDelete($open)
    {
        return !$open;
    }
}
