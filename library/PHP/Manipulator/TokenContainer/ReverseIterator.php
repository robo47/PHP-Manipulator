<?php

namespace PHP\Manipulator\TokenContainer;

use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class ReverseIterator extends Iterator
{

    /**
     *
     * @param TokenContainer $container
     */
    public function __construct(TokenContainer $container)
    {
        parent::__construct($container);
        $this->_keys = array_reverse($this->_keys);
    }
}