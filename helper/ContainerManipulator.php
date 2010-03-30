<?php

class PHP_Formatter___classname__
extends PHP_Formatter_ContainerManipulator_Abstract
{

    /**
     * Manipulate Container
     * 
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */

            $iterator->next();
        }
        $container->retokenize();
    }
}
