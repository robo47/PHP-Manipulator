<?php

class PHP_Manipulator_TokenContainer_ReverseIterator extends PHP_Manipulator_TokenContainer_Iterator
{
    
    public function __construct(PHP_Manipulator_TokenContainer $container)
    {
        parent::__construct($container);
        $this->_keys = array_reverse($this->_keys);
    }
}