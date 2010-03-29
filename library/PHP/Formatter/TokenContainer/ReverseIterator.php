<?php

class PHP_Formatter_TokenContainer_ReverseIterator extends PHP_Formatter_TokenContainer_Iterator
{

    public function __construct(PHP_Formatter_TokenContainer $container)
    {
        parent::__construct($container);
        $this->_keys = array_reverse($this->_keys);
    }
}