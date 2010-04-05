<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class TokenContainerReverseIterator extends TokenContainerIterator
{
    
    public function __construct(TokenContainer $container)
    {
        parent::__construct($container);
        $this->_keys = array_reverse($this->_keys);
    }
}