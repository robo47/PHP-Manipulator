<?php

class PHP_Formatter___classname__
extends PHP_Formatter_ContainerConstraint_Abstract
{

    /**
     * Evaluate if the container 
     *
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(PHP_Formatter_TokenContainer $container, $params = null)
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
