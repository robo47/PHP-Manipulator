<?php

namespace PHP\Manipulator\TokenContainer;

class ReverseTokenContainerIterator extends TokenContainerIterator
{
    protected function refreshKeys()
    {
        parent::refreshKeys();
        $this->keys = array_reverse($this->keys);
    }
}
