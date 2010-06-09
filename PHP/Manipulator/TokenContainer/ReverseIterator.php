<?php

namespace PHP\Manipulator\TokenContainer;

use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class ReverseIterator extends Iterator
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function __construct(TokenContainer $container)
    {
        parent::__construct($container);
    }

    protected function _init()
    {
        parent::_init();
        $this->_keys = array_reverse($this->_keys);
    }
}